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
// there's no case in this bucket where it's wrong. The 236 gated rules
// where the base rule DOES set its own explicit `display` (flex, grid,
// inline-block, etc.) are deliberately left untouched here -- forcing
// `display: block` onto those would override a real, intentional
// layout, not fix a blockification gap.
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

    // Pass 1: record which (media-context, selector) pairs have an
    // explicit `display` declared in a NON-gated rule.
    var baseHasDisplay = new Set();
    root.walkRules(function (rule) {
        if (rule.selector.indexOf('body.dev-float-refactor') === 0) {
            return;
        }
        var hasDisplay = false;
        rule.walkDecls('display', function () { hasDisplay = true; });
        if (!hasDisplay) {
            return;
        }
        var ctx = mediaContext(rule);
        rule.selector.split(',').forEach(function (sel) {
            baseHasDisplay.add(ctx + '::' + sel.trim());
        });
    });

    // Pass 2: for gated rules whose ONLY declaration is `float: none`,
    // split their selector list into "needs display:block added" vs
    // "leave as-is", based on pass 1, and replace the rule with the
    // (up to two) resulting rules.
    var fixedSelectorCount = 0;
    root.walkRules(function (rule) {
        if (rule.selector.indexOf('body.dev-float-refactor') !== 0) {
            return;
        }
        var decls = rule.nodes ? rule.nodes.filter(function (n) { return n.type === 'decl'; }) : [];
        if (decls.length !== 1 || decls[0].prop !== 'float' || decls[0].value !== 'none') {
            return; // not a pure float:none-only override, leave untouched
        }

        var ctx = mediaContext(rule);
        var selectors = rule.selectors;
        var needsFix = [];
        var leaveAlone = [];
        selectors.forEach(function (sel) {
            var baseSel = sel.replace(/^body\.dev-float-refactor\s+/, '');
            if (baseHasDisplay.has(ctx + '::' + baseSel)) {
                leaveAlone.push(sel);
            } else {
                needsFix.push(sel);
            }
        });

        if (needsFix.length === 0) {
            return; // nothing to change
        }
        fixedSelectorCount += needsFix.length;

        var replacements = [];
        if (needsFix.length) {
            var fixedRule = rule.clone();
            fixedRule.selectors = needsFix;
            fixedRule.append({ prop: 'display', value: 'block' });
            replacements.push(fixedRule);
        }
        if (leaveAlone.length) {
            var unfixedRule = rule.clone();
            unfixedRule.selectors = leaveAlone;
            replacements.push(unfixedRule);
        }
        rule.replaceWith(replacements);
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
