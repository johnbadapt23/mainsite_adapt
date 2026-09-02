// Splits any single CSS rule whose comma-separated selector list is longer
// than SELECTOR_LIMIT into several consecutive rules with the same
// declarations, each staying under the limit. Selector order and
// declaration text are otherwise untouched -- this only affects how many
// selectors share one `{...}` block, never what applies to what, so it is
// provably a no-op for the browser's cascade (identical rules, split up,
// still resolve to the same computed styles for every selector, in the
// same relative order).
//
// Why this exists (2026-09-02, see SESSION-HANDOFF.md "Section 6 float
// refactor" investigation): clean-css's level-2 "restructureRules" /
// "mergeNonAdjacentRules" optimization (source/gulp/tasks/build/styles.js,
// styles-split.js) merges every rule sharing identical declarations across
// the WHOLE file into one rule with a combined selector list -- this is
// deliberate and already verified safe for output size. But the gated
// float-refactor CSS (source/scss/sections/_dev-float-refactor.scss) adds
// well over a thousand small `{float:none}` declarations sitewide, and
// clean-css happily merges ALL of them (plus any other selector anywhere
// in the site sharing that exact declaration) into ONE rule -- confirmed
// via live testing on staging.adapt.com.au?dev=true to reach 1,535
// comma-separated selectors / 166,644 characters in a single rule.
//
// That size trips a real browser limit: confirmed by bisection in a live
// browser session (injecting truncated copies of the exact rule via a
// fresh <style> tag) that selector lists up to ~1,000 selectors apply
// correctly, but the browser silently fails to apply the rule at all
// once it grows past ~1,200 -- so a real chunk of the gated
// `float:none` overrides were never actually being applied to visitors
// testing with ?dev=true, which is what surfaced as "why do I still see
// floats/overlap" even though the CSS source and compiled file both
// looked correct.
//
// Disabling clean-css's merge behavior outright was tried and rejected:
// it changes real cascade-resolution outcomes elsewhere in the site (8
// confirmed selectors changed value in a semantic diff against the
// previous build -- e.g. a couple of `background-black h4` rules'
// text color flipped from white to black), because several parts of the
// codebase rely on clean-css resolving same-selector conflicts across
// non-adjacent rules in true source order. This splitter runs AFTER
// cssmin instead, so all of that already-verified merging/resolution
// still happens first -- it only breaks up whichever handful of
// resulting rules end up oversized, well after cascade order was
// already correctly resolved, so it cannot change what wins.
//
// Implementation note: this edits the postcss AST directly (clone the
// rule, give the clone a selector sub-list, insert as a sibling) rather
// than splicing raw text by character offset -- an earlier byte-offset
// version corrupted a few selectors at chunk boundaries (found via the
// same before/after semantic diff used to verify this file), because
// rule.selector's reported length didn't always exactly match the raw
// source span. Cloning nodes and re-stringifying the whole (already
// single-line/minified) sheet through postcss avoids that class of bug
// entirely -- postcss reproduces byte-identical output for every
// untouched node, and raws are explicitly zeroed on the new nodes below
// so they stay on the same single line as everything else.
var postcss = require('postcss');
var through2 = require('through2');

// Comfortably under the ~1,000-1,200 selector point where the browser
// bisection above started failing -- leaves real margin without giving
// up much of clean-css's size win (only rules bigger than this are
// touched at all; the vast majority of the file is untouched).
var SELECTOR_LIMIT = 400;

function splitOversizedRules(css) {
    var root = postcss.parse(css);
    var splitCount = 0;

    root.walkRules(function (rule) {
        var selectors = rule.selectors; // postcss-normalized array, one per comma segment
        if (!selectors || selectors.length <= SELECTOR_LIMIT) {
            return;
        }

        splitCount++;
        var newRules = [];
        for (var i = 0; i < selectors.length; i += SELECTOR_LIMIT) {
            var chunkSelectors = selectors.slice(i, i + SELECTOR_LIMIT);
            var clone = rule.clone();
            clone.selectors = chunkSelectors;
            clone.raws.before = i === 0 ? rule.raws.before : '';
            clone.raws.between = rule.raws.between;
            clone.raws.semicolon = rule.raws.semicolon;
            newRules.push(clone);
        }

        rule.replaceWith(newRules);
    });

    return { css: root.toString(), splitCount: splitCount };
}

module.exports = function () {
    return through2.obj(function (file, enc, cb) {
        if (file.isNull()) {
            return cb(null, file);
        }
        if (file.isStream()) {
            return cb(new Error('split-oversized-rules: streaming not supported'));
        }

        var result = splitOversizedRules(file.contents.toString());
        if (result.splitCount > 0) {
            // eslint-disable-next-line no-console
            console.log(
                '[split-oversized-rules] ' + file.relative + ': split ' +
                result.splitCount + ' oversized rule(s) (>' + SELECTOR_LIMIT +
                ' selectors) into smaller chunks'
            );
        }
        file.contents = Buffer.from(result.css);
        cb(null, file);
    });
};

module.exports.SELECTOR_LIMIT = SELECTOR_LIMIT;
module.exports.splitOversizedRules = splitOversizedRules;
