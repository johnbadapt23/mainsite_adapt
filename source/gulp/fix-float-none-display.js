// Adds `display: block` to any gated (`body.dev-float-refactor`-prefixed)
// override whose only declaration is `float: none`, wherever the
// corresponding un-gated (production) rule for that same selector does
// NOT already set an explicit `display` of its own.
//
// Why this exists (2026-09-02, see SESSION-HANDOFF.md "Section 6 float
// refactor" / the "?dev=true" testing round after it): `float` is not
// just a positioning property -- per the CSS display spec, a non-`none`
// float value blockifies the element's computed `display` (an inline
// element like `<span>` with `float: left` computes to a block box).
// The mechanical Category A/B batch that generated most of
// `_dev-float-refactor.scss`'s `float: none` overrides only ever
// neutralised the float itself. For every element that was actually a
// `<div>`/`<section>`/other already-block-level tag, that was enough --
// removing an inert float changes nothing. But for the (large) subset
// of targets that are `<span>`/`<a>`/other inline-by-default tags
// relying on that blockification (e.g. `.text-animation-introduction-v2
// span.animation-text-container .text`, reported live on staging:
// removing the float without also fixing `display` made a full-width
// text block collapse back to inline flow), the override silently
// broke layout instead of fixing it.
//
// A full audit (postcss walk comparing every gated `float: none`-only
// rule's base selector against its un-gated counterpart) found 1,423 of
// 1,659 such rules have no explicit `display` in the base rule at all --
// i.e. the vast majority were only verified safe for `<div>`-like tags,
// never checked against the actual DOM tag. Cross-referencing every one
// against real markup to find exactly which are `<span>`/`<a>` (rather
// than fixing all 1,423 blind) was tried and abandoned: matching by
// class name against `<span class="...">`/`<a class="...">` usage
// site-wide produced ~880 "risky" hits, but most were false positives
// (the same class name reused on a `<div>` in one template and a
// `<span>` in another) -- not reliable enough to act on selectively.
//
// `display: block` is what a blockified inline element already renders
// as, so applying it universally to this "no explicit display" bucket
// is a no-op for the `<div>`/`<section>`/etc. majority (already
// block-level) and the correct fix for the `<span>`/`<a>` minority --
// there's no case in this bucket where it's wrong. Gated rules where a
// display is already known (see single-pass design below) are
// deliberately left untouched here -- forcing `display: block` onto
// those would override a real, intentional layout, not fix a
// blockification gap.
//
// 2026-09-02, second fix (same day, found while adding Section 7):
// the original two-pass design only checked NON-gated rules for an
// existing `display`, on the assumption that clean-css's own
// same-selector cascade-resolution merge (see styles.js's
// restructureRules comment) would already have folded any earlier
// HAND-DESIGNED gated `display:flex` override (Sections 1-6) together
// with a later, redundant, mechanically-generated gated `float:none`-
// only duplicate for the same selector into one merged rule before this
// script ever runs -- so a hand-designed `display` would already be
// present in the SAME rule this script inspects, never a separate one.
// That merge is not guaranteed: it depends on clean-css's internal
// complexity/size heuristics, and adding ~150 more Section 7 rules was
// enough to make it skip merging 4 particular header selectors
// (.logo-title-container, .search-column-container, .header-inner,
// .resources-sticky-inner) that Sections 1-4 already hand-fixed to
// display:flex. Left as two separate rules, this script "corrected"
// the later, mechanical, still-separate float:none-only duplicate by
// adding display:block to it -- which, being later in the file, then
// won the cascade over the earlier display:flex and silently broke
// those 4 already-fixed layouts. Caught by the semantic diff before
// commit, not live.
//
// Fixed by making this a single forward pass instead of two passes:
// walk every rule in document (cascade) order, GATED OR NOT, and before
// deciding whether a gated float:none-only rule needs display:block,
// check whether ANY earlier rule (gated or not) for that exact
// (media-context, selector) already declared an explicit `display`.
// This is correct regardless of whatever clean-css did or didn't merge,
// since it mirrors real per-property cascade resolution directly
// instead of relying on rules having already been consolidated.
var postcss = require('postcss');
var through2 = require('through2');

function mediaContext(node) {
    var parts = [];
    var p = node.parent;
    while (p && p.type !== 'root') {
        if (p.type === 'atrule') {
            parts.unshift(p.name + ' ' + p.params);
        }
        p = p.parent;
    }
    return parts.join(' > ');
}

function fixFloatNoneDisplay(css) {
    var root = postcss.parse(css);

    // Single forward pass in document order. hasDisplay accumulates as
    // we go, so a rule's own `display` (gated or not) is recorded
    // *after* it's been used to decide any earlier-in-this-same-rule
    // fix, but *before* any later rule for the same selector is
    // evaluated -- matching real cascade order.
    var hasDisplay = new Set();
    var fixedSelectorCount = 0;

    root.walkRules(function (rule) {
        var ctx = mediaContext(rule);
        var isGated = rule.selector.indexOf('body.dev-float-refactor') === 0;
        var decls = rule.nodes ? rule.nodes.filter(function (n) { return n.type === 'decl'; }) : [];
        var declaresDisplay = decls.some(function (d) { return d.prop === 'display'; });
        var isPureFloatNone = isGated && decls.length === 1 && decls[0].prop === 'float' && decls[0].value === 'none';

        if (isPureFloatNone) {
            var selectors = rule.selectors;
            var needsFix = [];
            var leaveAlone = [];
            selectors.forEach(function (sel) {
                var baseSel = sel.replace(/^body\.dev-float-refactor\s+/, '');
                if (hasDisplay.has(ctx + '::' + baseSel)) {
                    leaveAlone.push(sel);
                } else {
                    needsFix.push(sel);
                }
            });

            if (needsFix.length > 0) {
                fixedSelectorCount += needsFix.length;
                var replacements = [];
                var fixedRule = rule.clone();
                fixedRule.selectors = needsFix;
                fixedRule.append({ prop: 'display', value: 'block' });
                replacements.push(fixedRule);
                if (leaveAlone.length) {
                    var unfixedRule = rule.clone();
                    unfixedRule.selectors = leaveAlone;
                    replacements.push(unfixedRule);
                }
                rule.replaceWith(replacements);
                // The just-added display:block now counts as "this
                // selector has a display" for any later rule, same as
                // any other declareDisplay case below.
                needsFix.forEach(function (sel) {
                    hasDisplay.add(ctx + '::' + sel);
                });
            }
            return;
        }

        // Any other rule (gated or not) that sets an explicit display
        // -- record it so later rules for the same selector see it.
        if (declaresDisplay) {
            var selList = isGated
                ? rule.selectors.map(function (sel) { return sel.replace(/^body\.dev-float-refactor\s+/, ''); })
                : rule.selector.split(',').map(function (s) { return s.trim(); });
            selList.forEach(function (sel) {
                hasDisplay.add(ctx + '::' + sel);
            });
        }
    });

    return { css: root.toString(), fixedSelectorCount: fixedSelectorCount };
}

module.exports = function () {
    return through2.obj(function (file, enc, cb) {
        if (file.isNull()) {
            return cb(null, file);
        }
        if (file.isStream()) {
            return cb(new Error('fix-float-none-display: streaming not supported'));
        }

        var result = fixFloatNoneDisplay(file.contents.toString());
        if (result.fixedSelectorCount > 0) {
            // eslint-disable-next-line no-console
            console.log(
                '[fix-float-none-display] ' + file.relative + ': added display:block to ' +
                result.fixedSelectorCount + ' gated float:none selector(s)'
            );
        }
        file.contents = Buffer.from(result.css);
        cb(null, file);
    });
};

module.exports.fixFloatNoneDisplay = fixFloatNoneDisplay;
