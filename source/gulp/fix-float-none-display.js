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
//
// 2026-09-07, third fix: the exact-media-context check above has its
// own blind spot -- found live on staging via a real browser check
// (?dev=true on a 375px viewport), not guessed. `.main-nav` is
// `float:left` at its base/no-media-query rule, with a SEPARATE
// `@media (max-width: 1250px) { .main-nav { display: none; } }` rule
// hiding it on mobile/tablet. Section 2's hand-written override only
// sets `float: none` on `.main-nav` at the base (no media query)
// context -- correctly, since the comment right above it explains
// `.main-nav` is already hidden below 1250px in production, unchanged.
// But this script's exact-context check only looked for a `display` at
// THAT SAME base context, found none (the real `display: none` lives at
// a *different* context, the 1250px media query), and mechanically
// added `display: block` to the base-context gated rule anyway. Because
// that gated rule has no media query of its own, its `display: block`
// applies at EVERY viewport width, including under 1250px -- and
// because the gated selector has much higher specificity than the
// production media-query rule (`body.dev-float-refactor header
// .container ... .main-nav` vs. plain `.main-nav`), it won the cascade
// there too, silently un-hiding the entire desktop mega-menu (including
// its own separate copy of things like the "Join the ADAPT Insider
// Community" subscribe card) behind the mobile menu. Confirmed via
// getComputedStyle() in a live browser session: `.main-nav`'s computed
// display was "block" at 375px under ?dev=true vs. "none" at the same
// width in production.
//
// This is a narrower version of the same underlying problem the second
// fix (above) already solved for cross-rule-merging: checking only the
// exact context isn't enough when the SAME selector's true display
// behavior is genuinely context-dependent (i.e. different at different
// viewport widths) in production. The correct fix is NOT to try to
// model which media contexts overlap which -- that's real responsive-
// CSS cascade logic and a much easier place to introduce a new subtle
// bug than to fix one. Instead: if a selector has an explicit `display`
// declared in ANY OTHER context ANYWHERE in the stylesheet (not just
// the exact one being checked), its display is context-sensitive in
// production, and this mechanical, context-blind fix is not safe to
// apply -- skip it and log it for a human to write a properly
// media-scoped override instead, the same way the header comment above
// already describes doing for `<span>`/`<a>` cases that couldn't be
// resolved mechanically. This trades a small amount of remaining
// manual work for the same guarantee the rest of this refactor has held
// throughout: never silently guess on a case this script can't fully
// verify.
//
// 2026-09-07, fourth fix (same day, found rebuilding the full stylesheet
// in an isolated verification copy and diffing `.main-nav` specifically
// against the pre-fix output): the third fix above still missed the
// exact `.main-nav` case it was written for. Its base selector in
// Section 2's hand-written gated rule is
// `header .container .header-inner .headerRight .menu .main-nav`
// (includes `.header-inner`, added deliberately for extra specificity
// so the override reliably wins the cascade). Production's
// `@media (max-width: 1250px) { .main-nav { display: none } }` rule
// that actually makes this context-sensitive targets the plain
// `header .container .headerRight .menu .main-nav` -- no `.header-inner`
// segment. Both selectors match the exact same real element
// (`.header-inner` wraps `.headerRight` in the markup either way; CSS
// descendant combinators don't require every ancestor to be named), but
// as *strings* they don't match, so the whole-file
// `displayContextsBySelector` lookup by exact baseSel silently missed
// it -- confirmed by rebuilding the full stylesheet and finding
// `.main-nav`'s gated rule still got a bare, unconditional
// `display: block` appended, identical to the unfixed output.
//
// Exact-string selector matching can't be trusted here: hand-written
// overrides in this file intentionally pad their ancestor chain for
// specificity, so "same selector" and "same string" are different
// questions. Modeling real selector-to-DOM equivalence is out of reach
// for a build-time regex/postcss pass. Instead, add a second, cruder
// but strictly more conservative check alongside the exact-context one:
// also compare by *leaf compound* -- just the last space/combinator-
// separated segment of the selector (e.g. `.main-nav` out of
// `... .headerRight .menu .main-nav`). If any selector anywhere in the
// file, at a different context, ends in the same leaf compound and
// declares a display, treat it as context-sensitive too. This will
// occasionally flag unrelated elements that just happen to share a
// trailing class name in different parts of the DOM -- an acceptable
// false positive (more manual review), never a false negative (a real
// conflict slipping through silently, which is what actually broke
// production behavior here).
//
// A single trailing token turned out too coarse in practice: this
// codebase reuses very generic leaf classes (`.text`, `.column-container`,
// `p`, `a`, `h2`, `.image-container`, ...) across dozens of unrelated
// components, so a 1-token leaf match against the real, full stylesheet
// flagged 343 of ~1,700 gated rules -- mostly coincidental collisions
// between unrelated DOM subtrees, not real conflicts, which would bury
// the handful of genuine cases (like `.main-nav`) in noise a human
// reviewer would eventually start rubber-stamping past. Using the last
// TWO segments (leaf + its immediate parent, e.g. `.menu .main-nav`
// rather than just `.main-nav`) is far more specific -- rare enough to
// almost never coincide between unrelated components -- while still
// catching `.main-nav` itself, since the padded `.header-inner` ancestor
// sits earlier in that chain, not adjacent to `.menu`/`.main-nav`. This
// doesn't restore an absolute guarantee (an ancestor inserted directly
// next to the leaf, between the last two segments, could still slip
// past), but that's a narrower, less likely authoring pattern than what
// actually happened, and the tradeoff against 343 mostly-noise flags is
// worth it for a review list a human will actually work through.
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

function leafCompound(sel) {
    var tokens = sel.trim().split(/\s+/).filter(function (t) {
        return t !== '>' && t !== '+' && t !== '~';
    });
    if (!tokens.length) {
        return sel;
    }
    // Last two segments (leaf + immediate parent) rather than just the
    // leaf -- see "fourth fix" header comment for why.
    return tokens.slice(-2).join(' ');
}

function fixFloatNoneDisplay(css) {
    var root = postcss.parse(css);

    // Whole-file first pass: for every selector that has an explicit
    // `display` declared ANYWHERE (gated or not, any media context),
    // record every context it appears in. Used only for the "is this
    // selector's display context-sensitive at all" safety check below --
    // deliberately whole-file and order-independent, since that
    // question doesn't depend on cascade position, only on whether such
    // a rule exists anywhere.
    var displayContextsBySelector = new Map(); // baseSel -> Set(ctx)
    var displayContextsByLeaf = new Map(); // leaf compound -> Set(ctx)
    root.walkRules(function (rule) {
        var decls = rule.nodes ? rule.nodes.filter(function (n) { return n.type === 'decl'; }) : [];
        var declaresDisplay = decls.some(function (d) { return d.prop === 'display'; });
        if (!declaresDisplay) {
            return;
        }
        var ctx = mediaContext(rule);
        var isGated = rule.selector.indexOf('body.dev-float-refactor') === 0;
        var selList = isGated
            ? rule.selectors.map(function (sel) { return sel.replace(/^body\.dev-float-refactor\s+/, ''); })
            : rule.selector.split(',').map(function (s) { return s.trim(); });
        selList.forEach(function (sel) {
            if (!displayContextsBySelector.has(sel)) {
                displayContextsBySelector.set(sel, new Set());
            }
            displayContextsBySelector.get(sel).add(ctx);

            var leaf = leafCompound(sel);
            if (!displayContextsByLeaf.has(leaf)) {
                displayContextsByLeaf.set(leaf, new Set());
            }
            displayContextsByLeaf.get(leaf).add(ctx);
        });
    });

    // Single forward pass in document order. hasDisplay accumulates as
    // we go, so a rule's own `display` (gated or not) is recorded
    // *after* it's been used to decide any earlier-in-this-same-rule
    // fix, but *before* any later rule for the same selector is
    // evaluated -- matching real cascade order.
    var hasDisplay = new Set();
    var fixedSelectorCount = 0;
    var skippedContextSensitive = []; // { selector, context, otherContexts } -- logged, not fixed

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
                    // Already has an explicit display at this exact
                    // context (e.g. a hand-designed override earlier in
                    // the cascade) -- nothing to fix.
                    leaveAlone.push(sel);
                    return;
                }

                var otherContexts = displayContextsBySelector.get(baseSel);
                var hasContextSensitiveDisplay = otherContexts &&
                    [...otherContexts].some(function (c) { return c !== ctx; });

                // Belt-and-braces check for hand-written overrides whose
                // selector string doesn't exactly match production's
                // (e.g. an extra ancestor added for specificity, like
                // .main-nav's .header-inner) -- compare by leaf compound
                // too. See "fourth fix" header comment.
                var leaf = leafCompound(baseSel);
                var leafContexts = displayContextsByLeaf.get(leaf);
                var hasLeafContextSensitiveDisplay = !hasContextSensitiveDisplay && leafContexts &&
                    [...leafContexts].some(function (c) { return c !== ctx; });

                if (hasContextSensitiveDisplay || hasLeafContextSensitiveDisplay) {
                    // This selector (or its leaf compound) has an
                    // explicit display somewhere else in the stylesheet,
                    // at a DIFFERENT media context -- its display is
                    // genuinely responsive in production. A context-
                    // blind display:block here could win the cascade at
                    // a width it was never meant to apply at (this is
                    // exactly what happened to .main-nav). Not safe to
                    // fix mechanically -- leave the bare float:none as-is
                    // and flag for a human to write a properly
                    // media-scoped override.
                    var matchedContexts = hasContextSensitiveDisplay ? otherContexts : leafContexts;
                    leaveAlone.push(sel);
                    skippedContextSensitive.push({
                        selector: baseSel,
                        context: ctx || '(no media query)',
                        otherContexts: [...matchedContexts].map(function (c) { return c || '(no media query)'; }),
                        matchedBy: hasContextSensitiveDisplay ? 'exact-selector' : 'leaf-compound (' + leaf + ')',
                    });
                    return;
                }

                needsFix.push(sel);
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

    return {
        css: root.toString(),
        fixedSelectorCount: fixedSelectorCount,
        skippedContextSensitive: skippedContextSensitive,
    };
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
        if (result.skippedContextSensitive.length > 0) {
            // eslint-disable-next-line no-console
            console.log(
                '[fix-float-none-display] ' + file.relative + ': SKIPPED ' +
                result.skippedContextSensitive.length + ' context-sensitive selector(s) ' +
                '(explicit display exists at a different media context -- needs a hand-written, ' +
                'media-scoped override, not a mechanical fix):'
            );
            result.skippedContextSensitive.forEach(function (s) {
                // eslint-disable-next-line no-console
                console.log(
                    '    ' + s.selector + '  [at ' + s.context + ']  -- has display at: ' +
                    s.otherContexts.join(', ') + '  (matched by ' + s.matchedBy + ')'
                );
            });
        }
        file.contents = Buffer.from(result.css);
        cb(null, file);
    });
};

module.exports.fixFloatNoneDisplay = fixFloatNoneDisplay;
