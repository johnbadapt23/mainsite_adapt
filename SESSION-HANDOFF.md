# adapt Theme — Session Handoff Notes

Prepared: 2026-09-01
Project: `adapt` WordPress theme (`F:\WORK\staging-mainsite\adapt`)
Environments: `staging.adapt.com.au` (staging), `adapt.com.au` (production)

This document summarizes everything done on this project in this session, so
work can be picked up from another account/session with full context.

---

## 1. Project conventions (read this first)

- **Hard rule throughout:** never change user-facing behavior unless
  explicitly requested. Every change is verified before being called done.
- **Build pipeline:** SCSS source lives in `source/scss/`, compiled via
  `gulp build:styles` (dart-sass + gulp-autoprefixer + clean-css minification)
  into `assets/css/main.min.css`. The compiled CSS is a committed build
  artifact — **always rebuild and commit it alongside any SCSS change.**
- **No deploy credentials in this sandbox.** The user deploys from their own
  machine, sharing the same git working directory. My job here is to commit
  working, verified changes to the `dev` branch (current branch); the user
  pushes/deploys separately.
- **PHP verification:** every PHP change is checked with `phply`
  (`from phply.phpparse import make_parser; import phply.phplex as phplex`)
  before committing.
- **CSS build verification methodology** (used for every SCSS change):
  1. `rsync -a --exclude node_modules` the repo to an isolated scratch dir
  2. `npm ci --ignore-scripts`
  3. `npx gulp build:styles`
  4. Diff the compiled output's relevant selectors against the previous
     commit's build to prove nothing unrelated changed
  5. Confirm the new/changed rule text is actually present in the minified
     output (accounting for clean-css merging identical-declaration rules
     into combined comma-separated selector lists)
  6. Copy `assets/css/main.min.css` back into the repo and commit
- **Sandbox note:** this Linux sandbox's outbound HTTPS is blocked by its
  proxy (confirmed even `curl https://www.google.com` fails). Live-site
  checks were done via the in-app/Claude-in-Chrome browser tools instead,
  which have real internet access.

---

## 2. The gated float→flexbox refactor (biggest single effort this session)

### Why

A sitewide audit found **2,216 raw occurrences of `float:left`/`float:right`
across 37 SCSS files**. Most are dead weight from an old layout approach;
some are real multi-column grids that still need floats (or a proper flex
redesign). Rather than touch the live site blind, the user asked for a
**gate**: all refactor work only activates when a visitor adds `?dev=true`
to the URL, so real visitors see zero change until each piece is verified
live and approved.

### How the gate works

- `functions.php` → `adapt_dev_gate_body_class()`: filters `body_class` to
  add a `dev-float-refactor` class to `<body>` only when
  `$_GET['dev'] === 'true'`.
  ```php
  function adapt_dev_gate_body_class( $classes ) {
      if ( isset( $_GET['dev'] ) && $_GET['dev'] === 'true' ) {
          $classes[] = 'dev-float-refactor';
      }
      return $classes;
  }
  add_filter( 'body_class', 'adapt_dev_gate_body_class' );
  ```
- All override CSS lives in **`source/scss/sections/_dev-float-refactor.scss`**
  (~18,500+ lines), every rule wrapped in `body.dev-float-refactor { ... }`.
  This class gives the overrides a specificity edge over the original rules,
  and `sections/**/*.scss` is imported **last** in `main.scss`, so source
  order also favors the overrides. No `!important` needed anywhere in this
  file.
- **Without `?dev=true`, the original CSS is 100% untouched.** Verified on
  every batch by diffing the full set of `float:left`/`float:right`
  selectors in the compiled CSS against the prior commit — must be byte-
  identical every time.

### Audit categories used to classify all 2,216 declarations

- **Category A** — element's own rule already has `display:flex`/`grid` →
  float is provably inert. Mechanically safe to blank out.
- **Category B** — `float:left; width:100%;` with nothing carousel-related
  in the selector chain → safe, UNLESS it's load-bearing for a Slick
  carousel (Slick positions `.slide`s with float).
- **Category C** — narrower width (not literally 100%) → a real
  multi-column float grid. Needs individual flex/grid redesign, not a blind
  float:none.
- **Category D** — no width in the same rule → ambiguous, needs individual
  review.
- **Position-based safety** — `position:absolute`/`fixed` on the same
  element forces computed float to `none` per CSS2.1 §9.7 — as safe as
  Category A.

**Critical bug caught and fixed:** a naive `'100%' in width_val` substring
check wrongly matched `calc(100% - 150px)` as "safe" (it is NOT 100% wide,
and is often a genuine two-column float partner). Fixed with a strict
`is_literal_100pct()` regex (`re.fullmatch(r'100%(\s*!important)?', ...)`).
47 false-positive declarations across 14 files were removed from the batch
before any of it went live. This was caught by manual review, which is
exactly why the section-by-section, check-in-first approach mattered.

### Work completed so far, in commit order

| Commit | What |
|---|---|
| `e92b0b8` | Added the PHP gate; reverted an earlier direct (ungated) edit |
| `189aebb` | Batch 1: 1,493 mechanically-safe (Category A/B) declarations |
| `e90421e` | Batch 2: 10 position:absolute/fixed dead floats |
| `be79883` | Fixed the 47 `calc(100%-Npx)` false positives; added **Section 1** (header top bar: logo, `.headerRight`) |
| `282e963` | **Section 2**: main header nav row (`.menu`, `.main-nav`, nav `<ul>`/`<li>`) |
| `96d25ee` | **Section 3**: resources sticky-menu row (cluster + push-right pattern, `margin-left:auto`) |
| `5d6ddf0` | **Section 4**: search dropdown (3-column 25/50/25 row + 2 nested sub-pairs) |
| `4eeef4f` | **Section 5**: mobile menu back/close header bar (3 duplicated DOM contexts, `justify-content:space-between`) |
| `482216b` | **Section 6**: remaining mobile-menu floats -- `.logo-tile`/`.column` (already-inert, parent already flex), `.subscribe-sidebar-form` icon/content split (`row-reverse` needed -- DOM order is icon-then-content but icon floats right), `.services-inner-mobile li a` icon+label row (`inline-block`→`inline-flex`), `.overview-image` (already-inert), `.all-link`, and the `.mobileMenuResources` logo/adapt-link split |
| `a7b2f83` | Fix: split oversized clean-css-merged rules (see §2a) |
| `3823069` | Fix: add `display:block` to gated `float:none` overrides on inline tags (see §2b) |
| `4d66d9c` | **Section 7**: `_flexible.scss` (homepage/content sections) -- see §2c below; also fixes a second, related bug in `fix-float-none-display.js` found while verifying this section |
| `75a0928` | **Section 8**: `_resources-types.scss` -- see §2d below; corrects an earlier (§2b) "out of scope" call for `.featured-home` |
| `3b8c1a6` | **Section 9**: `_customer-events.scss` -- see §2e below |
| `02b544a` | **Section 10**: `_events.scss` -- see §2f below |
| `0c3c918` | **Section 11**: `_registrations.scss` -- see §2g below |
| `588e77a` | **Section 12**: `_post.scss` -- see §2h below |
| `e66769a` | **Section 13**: `_login.scss` -- see §2i below |
| `8e88d33` | **Section 14**: `_single-speaker.scss` -- see §2j below |
| `ffdb085` | **Section 15**: `_form-pages.scss` -- see §2k below |
| `91f3cf8` | **Section 16**: `_thank-you.scss` -- see §2l below |

### Flex-equivalent patterns established (reuse these)

- **2-item left+right split** → `justify-content: space-between` on the
  parent.
- **3+ items, all-but-last float left, last floats right** (cluster + push
  away) → `margin-left: auto` on just the last item — NOT
  `space-between`, which would wrongly spread every item out evenly.
- **Plain N-up row, all `float:left` summing to 100%** → flex's default
  `justify-content:flex-start` needs nothing extra.
- Always add `align-items: flex-start` on the flex container (replicates
  float's top-alignment default; flex's own default `stretch` would make
  shorter siblings stretch to match the tallest — a real visual change).
- Add `flex-shrink: 0` to flex items that had an explicit float width
  (including `width:auto`/animated widths), so flex's shrink-to-fit default
  can never squeeze them narrower than float did.
- Sole-child floats (no sibling to float past) → just `float: none`, no
  flex properties needed (the parent isn't a flex container).
- Generic/reused classes (`.container`, `.column`) are always scoped with
  their **full ancestor selector chain** — never touched bare.

### What's left in this refactor

- **Section 6 (done, not yet deployed):** remaining mobile-menu floats
  that aren't part of the back/close header pattern -- icon-containers,
  `.logo-tile`, `.overview-image`, `.all-link`, `.subscribe-sidebar-form
  .icon-container`, and the `.mobileMenuResources .main-links-container`
  logo/adapt-link split. Two of the six (`.logo-tile`'s `.column` parent,
  and the mobile `.overview-image`) turned out to already be inert --
  their immediate parent (`.logo-link-column-container` /
  `.overview-container-inner`) is *already* unconditionally
  `display:flex` in the live, ungated CSS, so float never did anything
  there; just `float:none`, zero risk. The `.subscribe-sidebar-form`
  icon+content split needed `flex-direction:row-reverse` (not just plain
  `justify-content:space-between`) -- confirmed via
  `_mega-main-menu-mobile.php:438-443` that the DOM order is
  icon-container-then-form-content, but icon-container floats *right*
  and form-content floats *left*, so a plain flex row would visually
  swap them; row-reverse restores the original left-to-right visual
  order. `.services-inner-mobile li a` (icon+label row) needed
  `display:inline-block` → `inline-flex` (not `flex`, which would have
  switched it from shrink-to-fit sizing to block-filling sizing -- a
  real behavior change). Full selector-by-selector semantic diff (same
  postcss script from the footer-CSS-split verification) confirmed
  0 changed / 0 removed against the previous build, 17 added -- all 17
  are the new `body.dev-float-refactor`-gated rules and nothing else.
  Committed as `482216b`. **See §2a below -- testing this surfaced a
  serious, unrelated bug in how the entire gated float-refactor CSS gets
  minified, now fixed (`<pending commit>`).**
- Beyond `_header.scss`: the original audit flagged **260 "narrow width" +
  167 "no width" + 296 "carousel-adjacent" = 723 declarations** needing
  individual review. Sections 1–6 covered `_header.scss`'s share of that.
  **`_flexible.scss` is now done too (Section 7, 2026-09-02, `4d66d9c`)**
  -- see §2c below. **`_resources-types.scss` is now done too (Section 8,
  2026-09-02, `75a0928`)** -- see §2d below. **`_customer-events.scss` is
  now done too (Section 9, 2026-09-02, `3b8c1a6`)** -- see §2e below.
  **`_events.scss` is now done too (Section 10, 2026-09-02, `02b544a`)**
  -- see §2f below. **`_registrations.scss` is now done too (Section 11,
  2026-09-02, `0c3c918`)** -- see §2g below. **`_post.scss` is now done
  too (Section 12, 2026-09-02, `588e77a`)** -- see §2h below.
  **`_login.scss` is now done too (Section 13, 2026-09-02, `e66769a`)**
  -- see §2i below. **`_single-speaker.scss` is now done too (Section
  14, 2026-09-02, `8e88d33`)** -- see §2j below. **`_form-pages.scss`
  is now done too (Section 15, 2026-09-02, `ffdb085`)** -- see §2k
  below. **`_thank-you.scss` is now done too (Section 16, 2026-09-02,
  `91f3cf8`)** -- see §2l below. `_default.scss` and `_author.scss`
  were checked and found already fully covered by the earlier
  mechanical batch (0 uncovered declarations), no fix needed. Still
  untouched: the rest of the ~18 remaining files in
  `source/scss/templates/`.
- **User tested `?dev=true` on staging, 2026-09-02 (after `482216b`):**
  found floats/overlap persisting on completely unrelated homepage
  sections (`.introduction-content-container`, `.video-module`,
  `.infinite-images`, etc. -- none of which are part of Sections 1–6,
  all still on their original float layout by design). Investigating
  *why* those still floated led to §2a's discovery: it wasn't those
  sections being out of scope (expected) -- the override rules for
  things that *were* supposed to be fixed were also silently failing to
  apply, sitewide, for an entirely different reason. Sections 1–5's own
  correctness is still unconfirmed pending a fresh `?dev=true` check now
  that §2a's fix is in.

### 2a. Critical bug found + fixed, 2026-09-02: oversized merged CSS rule silently ignored by the browser

**Symptom:** with `?dev=true` on staging, many `body.dev-float-refactor`
override rules had zero effect -- elements kept their original `float`
even though the compiled CSS clearly contained a correctly-scoped,
higher-specificity `{float:none}` rule for them, and `Element.matches()`
confirmed the selector matched.

**Root cause, found by live bisection in the browser (not guessed):**
clean-css's level-2 `mergeNonAdjacentRules`/`restructureRules`
optimization (`source/gulp/tasks/build/styles.js`, `styles-split.js`) --
already relied on elsewhere in this codebase and confirmed safe for
*that* usage -- merges every rule sharing identical declarations across
the **whole file** into one rule with a combined selector list. The
gated float-refactor CSS adds 1,000+ small `{float:none}` declarations
sitewide (the mechanical Category A/B batch plus Sections 1–6), and
clean-css dutifully merged all of them (plus anything else in the site
sharing that exact declaration) into **one single rule with 1,535
comma-separated selectors / 166,644 characters**. Confirmed via
`document.styleSheets` introspection in a live browser session that this
rule *is* parsed and *is* present with the correct declaration -- but
via directly injecting truncated copies of the exact live selector list
through a fresh `<style>` tag and bisecting, selector lists up to
~1,000 apply correctly, and somewhere between 1,000–1,200 the browser
silently stops applying the rule at all. No console error, no warning --
it just doesn't take effect. This is a genuine browser-engine limit,
not a WordPress/RUCSS/caching/deployment issue (all of those were ruled
out first: confirmed the fully-current, un-cached CSS was what the
browser had loaded, confirmed RUCSS is disabled on the front page via
the existing `pre_get_rocket_option_remove_unused_css` filter, confirmed
no `@layer`/inline-style/specificity issue).

**Fix rejected:** disabling clean-css's merge behavior outright
(`mergeNonAdjacentRules: false`) does stop the oversized-rule problem,
but a full semantic diff against the previous build showed it also
**changes real, already-correct cascade outcomes** in 8 unrelated
places sitewide -- e.g. two `section...background-black h4` rules'
text color flipping from white to black, a `.value h2` margin changing,
a mobile `@media` visibility toggle flipping from `display:none` to
`display:block`. Several parts of the codebase depend on clean-css
resolving same-selector conflicts across non-adjacent rules in true
source order, so this option can't just be turned off sitewide.

**Fix applied:** new `source/gulp/split-oversized-rules.js`, wired into
both `styles.js` and `styles-split.js` immediately after `cssmin`. Runs
*after* clean-css has already done all of its (verified-safe) merging
and cascade resolution, then walks the AST for any single rule whose
selector list is longer than 400 (comfortably under the ~1,000–1,200
break point found above) and splits it into several consecutive rules
with identical declarations, each under the limit -- selector order and
declaration text are untouched, so this cannot change what applies to
what, only how many selectors share one `{...}` block. A full
selector-by-selector semantic diff against the last committed build
(same postcss-based script used throughout this session) came back
**0 changed / 0 removed / 0 added** -- confirming this is a pure,
lossless mechanical split. `main-nofooter.min.css` grew by ~2.9 KB;
`footer.min.css` is untouched (never has oversized rules). Verified
reproducible from a completely fresh `npm ci` + `npx gulp build:styles
build:styles-split` in an isolated scratch dir.

An earlier version of this splitter spliced the raw CSS text by
character offset instead of editing the postcss AST, and silently
corrupted a few selectors at chunk boundaries (e.g. `body...` became
`bbody...`) -- caught by the same before/after semantic diff, which is
why that diff is run on every change in this file, not just spot
checks. Rewritten to clone/replace AST nodes instead, which fixed it (0
changed on the same diff).

**Still needs:** a fresh `?dev=true` check on staging once this is
deployed, to confirm Sections 1–6 (and the mechanical batch) now
actually render correctly with the oversized-rule bug out of the way.

### 2b. Second bug found + fixed, 2026-09-02 (same testing round): `float:none` on `<span>`/`<a>` tags silently un-blockified them

**Symptom:** user reported, after deploying §2a's fix, that specific
text elements (e.g. `.text-animation-introduction-v2
span.animation-text-container .text`, `section.centered-text-links
.text-container span.text`) still rendered wrong with `?dev=true` --
not floating exactly, but collapsed/inline instead of the expected
full-width block.

**Root cause:** `float` isn't purely a positioning property -- per the
CSS Display spec, a non-`none` float value *blockifies* the element's
computed `display` (an inline element like `<span>` with `float: left`
computes to a block box). The mechanical Category A/B batch that
generated most of `_dev-float-refactor.scss` only ever set
`float: none` to neutralise floats -- correct for the (large) majority
of targets that are `<div>`/`<section>`/other already-block-level tags,
but for the subset that are `<span>`/`<a>`/other inline-by-default tags
relying on that blockification, removing the float without also fixing
`display` silently broke their layout instead of fixing it.

**How this was found to be systemic, not a one-off:** a postcss-based
audit (comparing every gated `float: none`-only rule's base selector
against its un-gated counterpart in the same compiled file) found
**1,423 of 1,659** such gated rules have no explicit `display` in the
base (production) rule at all -- meaning the vast majority were only
ever verified safe against the "already block-level" assumption, never
checked against real DOM tag names. A first attempt at narrowing this
down by cross-referencing class names against `<span class="...">`/`<a
class="...">` usage site-wide produced ~880 "risky" hits, but was
abandoned as unreliable -- the same class name is often reused on a
`<div>` in one template and a `<span>` in another, so a class-name match
alone can't tell which specific selector instance needs the fix.

**Fix applied:** new `source/gulp/fix-float-none-display.js`, wired in
right before `split-oversized-rules.js` in both `styles.js` and
`styles-split.js`. For every gated rule whose only declaration is
`float: none`, it checks whether the corresponding un-gated (production)
rule for that same selector already has an explicit `display` of its
own. If it does (236 cases -- already `flex`/`grid`/`inline-block`/etc,
correctly untouched, forcing `display:block` onto those would be
wrong), it's left alone. If it doesn't (1,423 cases), `display: block`
is added to the override. `display: block` is exactly what a blockified
inline element already renders as, so this is a no-op for the
`<div>`/`<section>`-majority (already block by default) and the correct
fix for the `<span>`/`<a>`-minority -- there's no case in the
"no explicit display in base" bucket where it's the wrong value.
`main-nofooter.min.css` fixed 1,447 selectors this way (slightly more
than main.min.css's 1,423 since the two files' selector pools differ
slightly); a semantic diff against the previous build confirmed
**0 removed, 0 added, only the 1,423/1,447 gated selectors changed** --
every non-gated (real, live) selector is untouched.

Verified directly against both user-reported selectors: both now
compile to `float:none;display:block` and both were confirmed at
`float:left` (real, load-bearing) in their base/production rule --
i.e. this genuinely was live-breaking for those two, not a false
positive.

**Also clarified, same round -- NOT bugs, just out of scope:** the user
also flagged `.home-content-slider` (Slick carousel track) and
`resources-featured-slide`/`.featured-module`/`.featured-home` as
"still floating" with `?dev=true`. Checked: **none of these have any
gated override at all** -- they were never touched by any part of this
refactor (Slick carousel floats are explicitly excluded per §2's
Category B note -- Slick's own JS positions `.slide`s using float, so
blindly neutralising it would break the carousel, not fix it; the
`featured-*` classes are simply in files/sections nobody has audited
yet). These render identically with or without `?dev=true` right now,
by design -- not a regression, just unfinished scope. Worth being
explicit about this distinction going forward: "still floats with
`?dev=true`" only indicates a real bug for elements that actually have
a gated override; for anything else it just means that element hasn't
been reached yet.

### 2c. Section 7 (`_flexible.scss`) + a third bug found + fixed, 2026-09-02

**Section 7 itself:** ~88 Category C/D declarations across every
`_flexible.scss` section (the homepage content blocks -- switcher-
module, team-block, text-animation-introduction, two-column-services,
centered-text-links, list-card-module, speakers-block, the speaker
popup, etc.). Same methodology as Sections 1–6: real DOM structure
confirmed via the PHP templates, `main.js` checked to identify which
`.xxx-slider` containers are live Slick carousels (`.staff-slider`,
`.lifestyle-slider`, `.home-content-slider`, `.form-popup-slider`,
`.speakers-bottom.mobile-slider`) and deliberately left floating, same
reasoning as the already-documented `.home-content-slider` exclusion.
`.speakers-bottom .speaker.one-quarter`'s desktop 4-up grid fix is
scoped to `@media (min-width: 768px)` specifically so it can't touch
`.speakers-bottom.mobile-slider`, which `main.js` only initialises
Slick on below 768px. Full writeup and selector list in commit `4d66d9c`.

This directly fixes two of the user's live-reported items:
`.text-animation-introduction` (the base section class underneath the
`-v2` homepage variant, which layers its own inline `<style>` overrides
in `_text-animation-introduction-v2.php` on top but doesn't redeclare
any of this) and `section.centered-text-links .text-container
span.text` (now gated to `float:none`, which `fix-float-none-display.js`
automatically turns into `float:none;display:block` since `span.text`
has no explicit `display` of its own in the base rule -- same fix class
as the `.text-animation-introduction-v2 .text` bug fixed in `3823069`).

**Third bug, found while verifying this section:**
`fix-float-none-display.js` (added earlier today, `3823069`) only
checked **non-gated** rules to decide whether a gated `float:none`-only
override needed `display:block` added. That was safe under an
unstated assumption: that clean-css's own same-selector cascade-
resolution merge (the `restructureRules` behaviour already relied on
elsewhere, see §2a) would already have folded any earlier
**hand-designed** gated `display:flex` override (Sections 1–6) together
with a later, redundant, **mechanically-generated** `float:none`-only
duplicate of the same selector into one merged rule, before this script
ever ran -- so a hand-designed `display` would always already be
present in the very rule being inspected, never sitting in a separate
one. That merge turned out not to be guaranteed: adding Section 7's
~150 new rules was enough to change clean-css's merge decisions
elsewhere in the file, and it stopped merging 4 particular header
selectors that Sections 1–4 had already fixed --
`.logo-title-container`, `.search-column-container`, `.header-inner`,
`.resources-sticky-inner`. Left as two separate rules, the old two-pass
logic "corrected" the still-separate mechanical duplicate by adding
`display:block` to it, which -- being later in the file -- then won the
cascade over the earlier `display:flex` and would have silently broken
those 4 already-working layouts on the next deploy.

**Caught by the semantic diff before commit, not live** -- the diff
against the previous build showed exactly these 4 selectors flipping
from `display:flex` to `display:block`, which is what triggered the
investigation.

**Fix applied:** rewrote `fix-float-none-display.js` as a single
forward pass over every rule in document/cascade order (gated or not,
not two separate passes), tracking a running "this selector already has
an explicit `display`" set that gets updated as each rule is visited --
mirroring real per-property CSS cascade resolution directly instead of
depending on clean-css having already consolidated same-selector rules.
This is correct regardless of what clean-css does or doesn't merge.
Verified: all 4 previously-broken selectors confirmed back to
`display:flex` in the rebuilt output; full semantic diff re-run
afterward came back clean (0/0/0 outside the gated scope, and the only
gated changes left were Section 7's own selectors being upgraded from
the auto-added `display:block` default to Section 7's explicit
`display:flex`/`flex-wrap`, which is correct/intended).

**Implication for future sections:** this class of bug can recur any
time a new hand-designed section is added on top of existing sections
that share a selector with a mechanical-batch duplicate -- but the
fix above makes it structurally impossible going forward, since it no
longer depends on clean-css's merge behaviour at all. Still worth
running the full 3-file semantic diff (`main.min.css`,
`main-nofooter.min.css`, `footer.min.css`) after every future section,
same as this one, rather than assuming the fix alone is enough.

### 2d. Section 8 (`_resources-types.scss`), 2026-09-02

~40 Category C/D declarations across `_resources-types.scss`, on top of
the 85 the earlier mechanical batch already covered. Same methodology:
real DOM confirmed via the PHP templates, `main.js` checked for Slick.

**Corrects an earlier call.** §2b (above) said `.featured-home` had "no
gated override at all... simply out of scope" alongside
`.home-content-slider`/`.resources-featured-slide`, grouping them all as
Slick-carousel exclusions. That was wrong for `.featured-home`
specifically -- re-checked `templates/components/_featured-posts.php`
directly this time (the component that actually renders
`section.resources-featured.featured-module.featured-home` on the
homepage) and confirmed there's no Slick class or `.slick()` init
anywhere in it, unlike the plain `.resources-featured` component
(`_resources-featured-block.php`, which genuinely is a Slick fade
carousel via `.resources-featured-slider` and stays excluded).
`.featured-home` is a real static 2-column layout
(`.first-post-column` 57% + `.side-bar-column` 43%) that had simply
never been reached by any part of the refactor -- now fixed. This is
the exact thing the user reported live ("resources-featured
featured-module featured-home -- most elements are float here").

**New pattern used here, worth reusing:** several components in this
file use a `.column` class on elements that don't yet have any flex
parent wired up (`.item-content-container.column` +
`.read-more-container.column` inside `.item.press-release-item
.container`, confirmed in every template that renders it) -- treat
`.column` the same as the `justify-content`/`flex-direction` "dead CSS"
signal from Section 7's `team-block`: it's a strong hint the layout was
designed as flex and just never got `display:flex` added, safe to
complete rather than guess at from scratch.

**Deliberately skipped, not mechanically safe:** `.sidebar-container`
(in-the-news-listing, 370px) and the `&.market-trends-featured`/
search-listing `.grid-wrapper` (`width: calc(100% - 301px)`) both size
themselves as "100% minus a fixed-width floated sidebar" -- i.e.
content meant to visually wrap around a still-floated aside, not a
simple percentage split. Confirmed via the PHP loop that the
*other* "side-bar" variants in this file (`.market-trend-reports-
container-side-bar`, `.peer-insights-container-side-bar`) open their
wrapping div exactly once and are safe uniform grids, not per-item
interleaves -- but these two calc(100%-301px) ones weren't traced
through their PHP loop with the same rigor this pass, and blindly
flexing a floated aside risks pulling it out of the wrap relationship
it depends on. Left for dedicated individual review. Also skipped:
`&.market-trends-featured .container .sidebar` (no width/sibling
context confirmed) and the generic `.two-thirds`/`.one-third` utility
classes (couldn't confirm which template pairs them as siblings in the
time available this pass).

Verified: same 3-file semantic diff as every other section. 0/0/0
outside the gated scope in `main.min.css`, `main-nofooter.min.css`,
`footer.min.css`. The only gated changes were 9 existing mechanically-
gated selectors correctly upgraded from the auto-added `display:block`
to this section's explicit `display:flex` -- confirming the §2c
single-forward-pass fix holds up under a second section's worth of new
rules. Committed as `75a0928`.

### 2e. Section 9 (`_customer-events.scss`), 2026-09-02

109 Category C/D declarations across the file (7,759 lines, the largest
template touched so far), on top of what the earlier mechanical batch
already covered. Same methodology: real DOM/JS confirmed via `main.js`
Slick calls and direct reads of the SCSS structure; a research subagent
did the first read-through of several unread chunks (fixed-scroller,
speaker-module, topic-industry-switcher, full-image-text, new-cta,
map-moving-text, quote-slider, two/three-column-text-image-cards,
expanding-form-module, full-suite-slider-module, two-column-logo-
carousel, comparison-three-column-text), cross-checked and refined by
hand for the ambiguous/risky cases before writing anything.

**Global `.column-container` utility class.** This file (only this
file) defines a bare, unscoped `.column-container { float:left;
width:100%; display:flex; }` at the very top, reused as a row wrapper
throughout. Its own float wasn't touched here -- it's shared site-wide,
out of scope for a single-file pass -- but every scoped instance
below still inherits `display:flex` from it, which is why so many
`.column-container` children are safe as bare `float:none` even where
the local block never repeats `display:flex` itself.

**Slick boundary resolution (the trickiest part of this section).**
Three modules needed individual main.js cross-referencing:
- `.company-slide-container` (section.company-slider) and
  `.full-suite-slider` (section.full-suite-slider-module) are both
  confirmed `.slick()` targets -- left floating on purpose, wholesale,
  same as `.home-content-slider` elsewhere. Nothing nested inside
  either was touched.
- `.quote-slider-module` (section.quote-slider) is a **third, separate**
  Slick init (`main.js` ~L2650, distinct from `.large-quote-slide-
  container`) -- but its floats live in `.customer-quote-slider-inner`,
  a plain content block one level below where Slick's own slide/track
  mechanics operate, so it was fixed anyway. The same grouped SCSS
  selector also styles the non-Slick `.quote-module` (single-quote
  variant), so one write covers both correctly.

**Two cases needed something other than bare `float:none`:**
- `.full-bio .bio-top` (speaker bio): `.image-container` (175px, not
  itself floated) sits beside `.text` (`calc(100% - 226px)`, floated)
  -- widths sum to 100%, confirming a genuine still-live side-by-side
  pair. Given the parent `display:flex; align-items:flex-start`.
- `.bottom-container .text-outer-container` (topic-industry-switcher
  accordion): floats right below a full-width accordion with zero room
  to tuck beside it, so it already renders on its own row below.
  `margin-left:auto` instead of a flex parent keeps the same
  right-aligned position now that `float:right` no longer does that job.
- `.sticky-slider-cards .mobile-slide-count`: two `width:auto` spans
  (`.slide-number` + a count) that must stay side by side -- given the
  parent `display:flex` so they lay out correctly regardless of what
  `display` value the mechanical safety net (`fix-float-none-display.js`,
  see §2b) ends up adding to the spans themselves; flex items ignore
  their own outer display type for layout purposes, so this is robust
  either way.

**Turned out to need nothing:** `section.comparison-three-column-text`
is already `float:none` end to end in the live CSS -- confirmed by
checking the actual uncovered-declarations list rather than trusting
a first-pass read of the section (an earlier read of this specific
section suggested several lines still needed fixing; the ground-truth
uncovered-line list said otherwise and was trusted over that read).

Verified: same 3-file semantic diff as every other section. 0/0/0
outside the gated scope in `main.min.css`, `main-nofooter.min.css`,
`footer.min.css` (gate isn't imported into the footer bundle -- 0
changes there too, expected). All 107 new selectors are gated, matching
what was authored; spot-checked the 4 hand-designed non-bare-float:none
rules directly in the compiled CSS output to confirm they came out
exactly as intended. Committed as `3b8c1a6`.

### 2f. Section 10 (`_events.scss`), 2026-09-02

26 Category C/D declarations across the file, on top of what the
earlier mechanical batch already covered.

**Cross-file selector collision, worth remembering for future
sections.** This file's compiled selectors overlap by name with other
templates that reuse the same top-level section class with completely
different internal DOM: `section.events-title-block` also exists in
`_services.scss`, `section.community-block` also exists in
`_landing.scss`, and `section.quote-slider`/`section.stats` also exist
in `_benchmarking.scss`/`_gtm.scss` with entirely different children
under the same class name. The initial compiled-CSS "what's still
uncovered" scan pulled in 23 false positives from those other files
(`.benchmarking-quote`, `.gtm-cards-module`, `.three-column-video-gtm`)
before every remaining candidate was individually cross-checked
against a direct read of `_events.scss` itself. **Lesson for future
sections:** never trust a compiled-CSS selector match alone to attribute
a rule to "this file" -- always confirm by reading the actual source
file's structure at that selector.

**Four separate Slick carousels in one file.** `.keynote-slider-module
.keynote-slider`, `.quote-slider-module` (a fourth distinct Slick init
across the whole codebase now, separate from Section 9's three),
`.quote-slider-thumbnails` (a second, independently Slick-initialized
`asNavFor`-synced thumbnail nav for the *same* quote module -- not
just a plain sibling, confirmed via a second `.slick()` call in
main.js), and `.flip-card-container.mobile .slick-list .slick-track
.slide`. All four excluded wholesale, nothing nested touched.

**Deliberately skipped, real width-calc risk:**
`section.events-listing-module`'s event-item card date/content/image
row. Two different listing templates (`_events-listing.php` vs
`_events-listing-partners.php`) render different DOM under the exact
same compiled selector -- one nests the date box inside an unfloated,
unwidthed wrapper that's itself a sibling of the floated image column,
the other has no date box at all. `.item-content-container`'s
`calc(100% - 516px)` only produces the right pixel width because it's
calculated two nesting levels deep against the *outer* container's
width while sitting inside that unfloated wrapper -- correctly
flexing this would mean recalculating that number against a new,
narrower flex-item parent (`calc(100% - 116px)`), which is an actual
size edit, not a mechanical float->flex swap. Left for dedicated
review with both templates open side by side, not touched.

**Two genuine still-live row pairs needed more than bare
`float:none`:** `.icon-text-column-container .column` (icon 80px +
text calc(100% - 80px), needed `display:flex` added to the row);
`.sneak-peak-container` (text 550px + image calc(100% - 550px)
float:right, needed `flex-direction:row-reverse` -- DOM order is
image-then-text but the visual is text-left/image-right, same
row-reversal technique as Section 6's `.subscribe-sidebar-form`
icon+content split); `.events-listing-top` (year-button-container 40%
+ button-container 60% float:right, needed `display:flex;
flex-wrap:wrap` -- the wrap matters because `.button-container`'s own
mobile override switches it to width:100%, which needs to drop to a
new line same as the float version did).

Verified: same 3-file semantic diff as every other section, 0/0/0
outside the gated scope. One extra verification step this time: an
initial single anchored `grep` appeared to show a new rule's
`float:none` missing after merging with a pre-existing mechanically-
generated rule for the same selector -- re-checked with a script that
walks every occurrence of the selector in the compiled output (not
just the first literal match) and confirmed both the old and new
rules are genuinely present and both apply in cascade order; the grep
had just missed an earlier comma-separated occurrence. No actual bug,
but worth the extra check given this is exactly the failure class
documented in `fix-float-none-display.js`'s header comment (clean-css
merge behavior isn't guaranteed to combine same-selector rules the way
you'd expect). Committed as `02b544a`.

### 2g. Section 11 (`_registrations.scss`), 2026-09-02

41 Category C/D declarations, on top of what the earlier mechanical
batch already covered.

**Shared-selector risk, one level deeper than Section 10's.** This
file opens with one grouped SCSS rule covering 6 section roots
(`section.webinar-article, .webinar-speaker-block, .webinar-faq,
.webinarBanner, .webinar-register-form, .registration-agenda-block`)
defining a common `.column`/`.first-column`/`.second-column.right-
column` two-part row. Unlike Section 10's cross-*file* collision, this
is a cross-*section* collision within the same rule -- a shared CSS
definition that different real templates use differently. Traced each
of the 6 roots through the actual PHP (`single-registration.php`,
`template-registration.php`) before deciding whether to add a flex
parent:
- `webinar-article` and `registration-agenda-block` genuinely render
  both columns as siblings -- got a flex parent.
- `webinarBanner`, and one branch of `webinar-speaker-block`
  ($count<=1 speakers), only ever render `.first-column` alone -- no
  pairing to preserve, bare `float:none` only.
- `webinar-speaker-block`'s other branch ($count>1) uses
  `.column.one-half` instead -- a separate, already-covered clear-based
  2-up grid. Deliberately did **not** add a flex parent to this
  section's `.container`, since that would turn the clear:left grid
  into an unwrapped flex row and break it.
- `webinar-faq` and `webinar-register-form` never render as an actual
  `<section>` in any template at all (grepped every `.php` file) --
  dead CSS for both. Fixed anyway since it's zero-risk either way, but
  worth knowing a chunk of this rule affects nothing live.

**Float-reordering pattern reused twice more** (same technique as
Section 10's `.sneak-peak-container`/`.events-listing-top`):
`webinar-article`'s `.second-column.right-column` renders before
`.first-column` in the DOM but floats right while `.first-column`
fills the space to its left -- `flex-direction: row-reverse` on the
parent. `registration-agenda-block`'s second-column has no
`.right-column` modifier in its real markup, so both float left in DOM
order with no reversal needed. `location-block`'s `.text-column`
(float:right, renders first) / `.image-column` (float:left, renders
second) needed the same row-reverse treatment -- confirmed via
`_location-block.php` plus the **global** `.one-half` utility class
(`source/scss/global/_styles.scss`, used site-wide) that both columns
are 50% width even though neither declares its own width locally; the
bare global class wasn't touched (out of scope for a one-file pass,
same reasoning as Section 9's `.column-container`), only the two more
specific local selectors, which win on specificity.

**Best find of this section:** a `.speaker-container-inner.flex-
container { display: flex; ... }` modifier already exists in the base
CSS (used inside `webinar-article`'s `.speakers-block`) and the real
markup always applies both classes together -- meaning its
`.speaker-image`/`.description` floats were **already** dead
(Category A, parent already flex via ordinary non-gated CSS) even
though the mechanical batch never recognized that. Three near-identical-
looking `.speaker-container-inner` definitions exist across this one
file and each needed a different call: this one (Category A, do
nothing extra), a second inside `webinar-article`'s `.second-column`
(genuinely stacked -- `.description` is a plain 100%-wide block, not a
reduced `calc()`, so no flex parent needed), and a third belonging to
`webinar-speaker-block` itself (`.description` is `calc(100% - 125px)`,
confirming a real still-live row with no flex parent of its own yet --
this is the one that got `display: flex` added).

Verified: same 3-file semantic diff as every other section, 0/0/0
outside the gated scope. Extra care taken with
`.webinar-mobile-sticky-footer` (display:none by default, display:block
only in its own ≤767px override) to nest the new display:flex inside
that same media query rather than adding it unconditionally, which
would have made the sticky footer permanently visible on desktop.
Confirmed correct directly in the compiled output. Committed as
`0c3c918`.

### 2h. Section 12 (`_post.scss`), 2026-09-02

25 Category C/D declarations, on top of what the earlier mechanical
batch already covered. Quieter than Sections 10-11 -- no shared-
selector collisions inside the file itself, but two things worth
flagging:

**A stale half-fix from an earlier pass.** `.introduction-hero-module`
(the post-title-block's main content column, `calc(100% - 330px)`)
already had a bare `float:none` sitting in the gated file from before
this session, but its sibling `.sidebar-container` (330px) never got
one -- meaning the pair was left in a broken intermediate state (one
side unfloated as a block, the other still floating) until this
section added the missing flex parent on `.container` and the missing
`float:none` on `.sidebar-container`. Worth remembering that "already
covered" in `find_uncovered.js`'s output doesn't always mean "fully
handled" -- a declaration can be individually gated while its sibling
pairing is still incomplete.

**Two same-named `.sidebar-container`s, two different stories.**
`section.post-title-block .container .sidebar-container` (330px,
published-details/share/contributor info) and `section.post-article-
container .container .sidebar-container` (also 330px, but containing
`.subscribe-sidebar-form`) are unrelated DOM subtrees that happen to
share a class name -- same lesson as Section 11's shared selector, but
here it's two *different* selectors that only look similar by
naming convention. Traced both through direct reads before writing
fixes: the post-title-block one needed a new flex parent (see above),
the post-article-container one turned out to already be a child of
`.post-column-container`, which is display:flex in the ordinary
(non-gated) base CSS -- confirmed by the arithmetic (`.left-column`
190px + this sidebar's 330px = the 520px subtracted from `.post-
content`'s `calc(100% - 520px)`) -- so it was Category A, bare
`float:none` only.

**Carousel-adjacent false positive caught before writing the fix.** A
subagent search confirmed `.post-container` is also used, completely
unrelated, as a slick-carousel container under `section.featured-
module.best-practices-featured` in `source/js/main.js`. The related-
articles 3-up grid fix was scoped to the full `div.related-articles
.container .post-container` path rather than a bare `.post-container`
selector to guarantee no overlap with that carousel.

Verified: same 3-file semantic diff as every other section (0/0/0
ungated on both `main.min.css` and `main-nofooter.min.css`), plus each
hand-designed flex-parent/float:none rule walked directly in the
compiled output -- confirmed clean-css merges rules sharing identical
`display:flex;flex-wrap:wrap;align-items:flex-start` properties into
shared comma-separated selector groups during minification (expected,
not a bug -- same lesson as Section 10's false alarm, still worth
re-confirming every time since a real drop would look identical to a
merge at a glance). Committed as `588e77a`.

### 2i. Section 13 (`_login.scss`), 2026-09-02

Smallest section so far -- 2 remaining declarations, no cross-file or
cross-section collisions, both straightforward: `.column-container`
(already `display:flex` in base CSS) makes `.column` Category A, and
`.content-container span.text` is a sole/stacked width:100% child
next to an already-covered `h4`. Confirmed no Slick involvement and no
collision risk from the `.column-container .column` substring also
appearing in `_customer-events.scss`/`_gtm.scss` (this fix is scoped
to the full `section.login-module .container .login-inner` path).
Same build+diff+spot-check verification as every other section, 0/0/0
ungated on both compiled files. Committed as `e66769a`.

### 2j. Section 14 (`_single-speaker.scss`), 2026-09-02

3 remaining declarations. Worth noting: the SCSS doesn't nest
`.speakerLeft`/`.speakerRight` under `.container` even though the real
markup does -- confirmed via both PHP templates that share this
section (`single-speaker.php` and `single-executive_advisor.php`).
The gated flex parent was added to the selector matching the real DOM
parent (`section.speakerProfile .container`), not the SCSS-authored
(shallower) selector, since specificity/behavior needs to match actual
nesting, not how the source file happened to write it. `.logoContainer`
confirmed sole (only one ever renders per `.logoWrapper`, no loop) via
both templates -- Category C. Same build+diff+spot-check verification,
0/0/0 ungated on both compiled files. Committed as `8e88d33`.

### 2k. Section 15 (`_form-pages.scss`), 2026-09-02

3 remaining declarations. `.contact-innner`'s two columns (40%/60%)
are Category A -- the parent is already display:flex on desktop and
drops to display:block at <=1023px, where it doesn't matter anyway
since both columns become width:100% at that breakpoint. The one
tricky piece: `.fast-track-text` has a second, narrower declaration
inside its own `@media (max-width: 767px)` override (this file uses a
raw media query, not the `@include responsive()` mixin used
elsewhere) -- confirmed the fix landed inside that exact media block,
not at the top level, by walking the compiled output's actual brace
nesting rather than trusting the nearest preceding `@media` text
(nearest-preceding-text can point at an unrelated, already-closed
block -- same lesson as Section 11's sticky-footer scoping, reapplied
here with a more rigorous brace-depth walk). Same build+diff
verification as every other section, 0/0/0 ungated on both compiled
files. Committed as `ffdb085`.

### 2l. Section 16 (`_thank-you.scss`), 2026-09-02

5 remaining declarations. Good reminder that identical class names
don't imply identical structure: `.counter-circle-outer`/`.counter`
reuse the exact same class names as `_post.scss`'s counter-title-text
pattern (Section 12), but here `.counter-title-container` has only one
child -- no sibling `span.counter-title` -- so this instance is
sole/stacked (Category C, no flex parent) rather than the genuine-row
case that needed `display:flex` in Section 12. Checked directly by
reading the SCSS, not assumed from the shared naming.
`.list-container .button-container a` loops an ACF repeater field
(confirmed via `templates/thank-you-components/_two-column.php`), so
more than one button can render -- Category B still applies since
they're inline-level anchors with no explicit adjacency requirement
beyond natural flow. Also checked `_default.scss` and `_author.scss`
in passing (smallest/next-smallest untouched files) -- both already
fully covered by the earlier mechanical batch, nothing to fix. Same
build+diff verification as every other section, 0/0/0 ungated on both
compiled files. Committed as `91f3cf8`.

### Recommended way to continue

Follow the exact same loop for each new section: identify the DOM/CSS
structure via the real PHP template (never guess), classify with the
categories above, hand-write the gated override under
`body.dev-float-refactor { }` in `_dev-float-refactor.scss`, build+diff+
verify per the methodology in §1, commit with a detailed message, then ask
the user to test that specific UI with `?dev=true` before moving to the next
section.

---

## 3. Other completed work this session (chronological, grouped)

### Accessibility / SEO / Lighthouse
- Fixed images missing `alt`, non-descriptive link text, non-crawlable
  links, `href="#"` non-nav links converted to buttons.
- Converted ACF image fields to `wp_get_attachment_image()` sitewide (574
  calls categorized and batch-edited to use proper registered image sizes
  instead of `'full'`).
- Fixed `viewport user-scalable=no`, form elements without labels, links
  without discernible names, heading-order issues (h1–h4 element vs class
  usage) across templates, touch-target sizing, contrast issues.
- `loading="lazy"` applied across ~180 flexible-content template files.

### Performance
- Diagnosed and reported on JS execution time (third-party tracking scripts
  — Clarity, Hotjar, Bing/Google Ads, HubSpot — not theme code; left as-is
  per no-unrequested-changes rule).
- Scoped and fixed sitewide Lottie script loading.
- Removed unused block-library CSS enqueue.
- Fixed excessive `<link rel=preconnect>` connections.
- Investigated render-blocking CSS and image delivery savings.
- **Fixed WebP video poster bug:** Imagify's "Next-Gen format" delivery
  rewrites `<img>` into `<picture>`, but never touches `<video poster="">`
  attributes even though the `.webp` sibling file exists on disk. Added
  `adapt_webp_poster_url()` helper in `functions.php` (checks for the
  `.webp` file via `wp_get_upload_dir()`, falls back to the original URL)
  and applied it across all 6 template files with video posters.
- `#70 Address render-blocking main.min.css` — investigated two approaches
  this session (see "2026-09-02 follow-up" below); likely already resolved
  by RUCSS (see next item), pending final confirmation.
- RUCSS (WP Rocket "Remove Unused CSS") — **resolved, 2026-09-02.** Logged
  into staging wp-admin (pre-existing session in the in-app browser, no
  credentials entered) and found RUCSS is active and working, contradicting
  the earlier "stalled" conclusion:
  - WP Rocket's dashboard shows "The Used CSS of your homepage has been
    processed" and confirms it's actively generating Used CSS for up to
    100 URLs/60s.
  - A CSS safelist is already populated at Settings → WP Rocket → File
    Optimization → Remove Unused CSS, covering exactly the interactive-only
    classes the original audit was looking for: `menu-open`, `search-open`,
    `sticky-open`, `sticky-bottom`, `scrolled-up`, `scrolled-fixed`,
    `overflow-hidden`, `slick-`, `clicked`, `first-click`, `active`,
    `mfp-`, `hbspt`, `hsforms`, `hs-form-html`, `full-bio`,
    `click-overlay`, `mobile-trigger`, `expertise-group`,
    `speaker-button-container`, `resources-sticky`, `post-menu-scrolled`,
    `text-red`, `form-container`/`form-container-preview`,
    `download-container`, `home-animation-popup`.
  - Anonymously fetched (no session cookies, via `web_fetch`) both the
    homepage and `/analyst-presentations` (the page `#84`'s original DOM
    census was built against) — both rendered complete, correct content
    with the full nav/mega-menu, hero, and footer present.
  - Could not independently inspect the raw `<link>`/used-CSS markup an
    anonymous visitor receives (WP Rocket disables its optimizations for
    logged-in admin sessions, and available tools return rendered/extracted
    text, not raw response headers) — so this is strong indirect evidence,
    not a byte-for-byte network trace.
  - Tasks `#86`, `#87`, `#88` marked completed based on the above.

### Security / code quality
- Fixed XSS (unescaped output) across ~8 template files.
- Added nonce verification to 3 AJAX endpoints; consolidated 3 near-
  duplicate AJAX callbacks; fixed unescaped output in AJAX render loops.
- Set up PHPCS with WordPress coding standards; cleaned up dead/commented
  code.

### Bug fixes
- Fixed a 9–10 min CI build by switching deploy sync from full to delta
  sync (with a bash exclude-pattern syntax fix along the way).
- Fixed a Slick carousel marker-count mismatch after a JS rebuild.
- **Speaker/advisor popup:** fixed a HubSpot form silently wiping a
  prefilled field, an AJAX-filtered-list button not opening the popup, and
  a critical regression where `adapt_page_needs_hubspot_forms_embed()` was
  wiping the entire speaker/advisor section off the page.
- `adapt_analyst` query: replaced a `meta_query` with an `expertise`
  taxonomy-query exclusion.

### ACF (Advanced Custom Fields)
- Audited all field groups/fields for actual usage in theme PHP;
  deduplicated near-identical groups/fields; removed confirmed-unused ones.
- Split cleaned groups into `acf-json/group_*.json` files (Local JSON sync)
  and added tabs to the largest/most cluttered field groups.

### This most recent turn (admin bar + phone input)
- **`show_admin_bar` was hardcoded to `__return_false`** in
  `includes/_hooks.php`, hiding the admin bar for every logged-in user
  regardless of role. Replaced with `custom_show_admin_bar_admins_only()`
  (new function in `includes/_customisations.php`), which checks
  `wp_get_current_user()->roles` for `'administrator'` — only admins see
  the bar now. *(commit `8dd261a`)*
- Added a fixed-header offset for when the bar is showing: `header` is
  `position:absolute/fixed` with an explicit `top:0`, so it isn't pushed
  down by the admin bar automatically. New rule in
  `source/scss/partials/_header.scss`:
  ```scss
  body.admin-bar & {
      top: 32px !important;
      @media (max-width: 782px) {
          top: 46px !important;
      }
  }
  ```
  Scoped to WordPress's own `body.admin-bar` class, `!important` required
  to beat the higher-specificity `header.scrolled.scrolled-up` rule.
  *(commit `8dd261a`)*
- **Root-caused why `body.admin-bar` wasn't appearing at all:** a
  pre-existing filter, `custom_body_classs()` in
  `includes/_customisations.php`, does `$classes = array();` — it
  unconditionally **wipes every class WordPress core adds** (including its
  native `admin-bar` class) before rebuilding its own minimal list. Fixed
  by re-adding `'admin-bar'` in that function when `is_admin_bar_showing()`
  is true — the same pattern the function already uses to re-add
  `page-id-{ID}`. *(commit `8d1ce2a`)*
- **`.hs-form-html .hsfc-PhoneInput .hsfc-PhoneInput__FlagAndCaret`**
  (`source/scss/global/_forms.scss` line 987): `height`/`line-height`
  reduced from `40px` to `18px` per request. Note: this element also has a
  `1px solid` border and `14px` font-size, so at 18px the box will look
  noticeably tighter than before — worth a visual check on a live HubSpot
  phone field. *(commit `59478a6`)*

---

### 2026-09-02 follow-up: #70 investigation notes

Tried extracting "critical CSS" (above-the-fold only) from the live
homepage to inline + defer the rest, using real viewport/DOM matching in
the browser (not a static tool) against both desktop (1440×900) and mobile
(375×812). Findings, for whoever picks this up next:

- Raw `main.min.css` is **1.66MB uncompressed**; Lighthouse's "212 KiB"
  figure is the **gzip-compressed transfer size**. Don't compare a raw
  critical-CSS extract against the 212 KiB number directly — compress it
  first, or the comparison is meaningless (I made this mistake initially).
- Desktop critical extract: 148,733 bytes raw (~9% of the raw file).
  Mobile: 88,938 bytes raw (~5%). Gzipped, these would likely land around
  15-20KB — a genuinely reasonable inline payload size, not the "not worth
  it" conclusion I first reported to the user.
- The real problem found: `source/gulp/tasks/build/styles.js:49` enables
  clean-css's `restructureRules`, which (intentionally, and for good reason
  — see the comment there) merges rules across the *entire* file that
  share identical property values, even across completely unrelated
  components. A critical-CSS extraction run against the *already-merged*
  `main.min.css` pulls in every selector riding along in a shared merged
  rule the moment any one of them is critical — e.g. one `float:left;
  width:100%` rule I captured mixed selectors from customer-story
  carousels, keynote sliders, and webinar articles that have nothing to do
  with each other. This inflates the critical set with non-critical
  content and makes the technique unreliable as implemented.
- **Do not disable `restructureRules` on the production build** — it's a
  deliberate, already-verified size optimization; reverting it would grow
  `main.min.css` for every visitor to fix a problem that only affects a
  hypothetical future critical-CSS build.
- A correct fix needs a **separate, unmerged build pass** used only for
  critical-CSS extraction (e.g. same sass+autoprefixer pipeline, skip or
  reconfigure the clean-css step), so selectors stay scoped to their
  original rule and don't drag in unrelated content. Not started — this is
  real infra work, not a quick patch.
- **`templates/**/*.scss` is 1.5MB of source** (vs. 184KB for `partials/`
  and 153KB for `global/`) — the single biggest contributor to
  `main.min.css`'s size, and it ships every template's CSS (events,
  resources, speaker profiles, etc.) on every single page regardless of
  which template is actually being viewed. This is arguably the deeper
  root cause of the file's size, and it's exactly the problem RUCSS is
  designed to solve per-page — which is why the RUCSS finding above may
  make a hand-built critical-CSS pipeline unnecessary. **Confirm RUCSS is
  actually serving a trimmed stylesheet to real visitors (see network
  trace note below) before investing in a separate critical-CSS build.**
- ~~One clean, zero-risk, always-true optimization identified but not yet
  implemented: `partials/_footer.scss`~~ **implemented and promoted to
  default, 2026-09-02** (`972acdc`, tested behind `?dev=true` in
  `f38ef19`→`972acdc`, promoted in `ed4e777`) — see §9 below for the full
  writeup. One wrinkle found along the way: `_footer.scss` also held a
  sitewide `.social-link` touch-target rule used outside the footer,
  which had to be relocated first — the "zero-risk" framing above
  undersold that this needed a careful look, not a blind file move.

## 9. 2026-09-02 continued: footer CSS split, promoted to default (#70)

Implemented the footer-defer idea from the note above. Summary (full
rationale is in the `972acdc` commit message):

- `partials/_footer.scss`'s `.social-link` rule (touch-target sizing) was
  moved to `global/_styles.scss` first — grep showed it's also used by
  `_contact-block.php`, `_form-module.php`, `single-customer_stories.php`,
  and `template-contact.php`, some of which render above the fold, so it
  couldn't travel with the rest of the footer partial into a deferred
  bundle. Confirmed via grep it's declared exactly once anywhere in the
  SCSS source, so relocating it can't flip a cascade conflict (there
  isn't one).
- New entry points `source/scss/main-nofooter.scss` (everything except
  the footer partial) and `source/scss/footer-only.scss` (just the footer
  partial + the two global partials it actually depends on — verified via
  grep), compiled by a new, additive `build:styles-split` gulp task
  (`source/gulp/tasks/build/styles-split.js`) into
  `assets/css/main-nofooter.min.css` and `assets/css/footer.min.css`.
  Now wired into the `_build` task list (`gulpfile.js`) and both CI
  deploy workflows' "Compile CSS and JS" step, alongside `build:styles`
  (which still runs too — `main.min.css` is kept as an unused rollback
  artifact, not deleted).
- `functions.php`'s `my_enqueue_scripts()` enqueues the split bundle
  (+ defers `footer.min.css` via the same preload+onload pattern already
  used for `wp-pagenavi`'s CSS, in a new `adapt_defer_footer_css()`
  filter) unconditionally — this is now the only path, the original
  `?dev=true` gate was removed once staging confirmed it worked (see
  below).
- Verification done in-sandbox (see `972acdc` for full detail): a
  postcss-based script compared every (media-context, selector, property)
  → value triple between the old and new builds. Zero effective
  differences after the `.social-link` move, and zero effective
  differences between `main-nofooter.min.css` + `footer.min.css` combined
  and the original `main.min.css` (44,386/44,386 pairs match). Also
  checked for any other bare `footer` element selector anywhere in the
  SCSS source that could compete with `_footer.scss`'s own top-level rule
  on source order; everything else found is either already
  footer-scoped or nested under a more specific ancestor that wins on
  specificity regardless of load order.
- **Confirmed on staging, 2026-09-02 (after user deploy):** `?dev=true`
  correctly enqueues `main-nofooter.min.css` (handle `main-styles`) +
  `footer.min.css` (handle `footer-styles`, `rel="preload"` +
  `onload` swap, `<noscript>` fallback present) instead of `main.min.css`;
  both files return 200. Verified with `getComputedStyle()` directly on
  the live DOM (not just a screenshot) that `footer`'s
  `background-color`/`padding` match the SCSS source exactly, and that
  `.social-link`'s relocated rule (`global/_styles.scss`, loaded via
  `main-nofooter.min.css`) still correctly applies its `::after`
  touch-target expansion to the footer's social icons even though it now
  ships in a different file than the footer content itself. No
  footer/CSS-related console errors. Note: the in-app browser's
  screenshot tool renders blank white for ANY scrolled-down position on
  this page (reproduces identically with `?dev=true` off, so it's a
  pre-existing tool quirk, not a regression -- confirmed real content is
  there via `elementFromPoint()` + computed styles, just couldn't get a
  usable screenshot of it).
- **Promoted to default, 2026-09-02** (`ed4e777`, after you confirmed the
  staging check above and said to go ahead): removed the `?dev=true`
  conditional from `my_enqueue_scripts()` — every page now always gets
  the split bundle. Also closed a gap this would otherwise have hit on
  the next deploy: both CI workflows only ran `build:styles build:scripts`
  and only force-included `main.min.css`/`main.min.js` in their SFTP
  delta-sync (`sync-delta-includes` — everything else is decided by
  `git diff`, and compiled CSS/JS is never re-committed by CI, so a file
  that isn't force-included silently goes stale on deploys that only
  touch `source/`). Added `build:styles-split` to both workflows' compile
  step and both new files to both workflows' `sync-delta-includes`, and
  added `build:styles-split` to the local `_build` task list too. Updated
  every comment/doc that still described the old dev-gated state (see
  the commit for the full file list). Rebuilt locally and diffed all
  three CSS outputs against what was already committed — byte-for-byte
  identical, confirming the comment-only SCSS edits didn't touch compiled
  output.

## 4. Quick reference: how to rebuild and verify CSS changes

```bash
rm -rf /tmp/slickbuild
rsync -a --exclude node_modules <repo>/ /tmp/slickbuild/
cd /tmp/slickbuild
npm ci --ignore-scripts
npx gulp build:styles
# then diff /tmp/slickbuild/assets/css/main.min.css against the previous
# commit's build before copying it back into the repo
```

## 5. Quick reference: PHP syntax check

```python
from phply.phpparse import make_parser
import phply.phplex as phplex
parser = make_parser()
lexer = phplex.lexer.clone()
parser.parse(open('path/to/file.php').read(), lexer=lexer)
```

---

## 7. 2026-09-02: PHP 8.1 / WordPress standards modernization pass

User asked to update all theme PHP to the latest PHP coding standard and
WP standard, and to use modern PHP functions -- confirmed target: PHP 8.1,
"just fix everything you find" (not gated section-by-section, since none
of this touches CSS/visual output).

**Tooling note (important for future sessions):** there's no PHP CLI in
this sandbox, so PHPCS/PHPCompatibility couldn't be installed or run.
Worse, `phply` (this project's usual PHP syntax checker) is built on
pre-PHP7 grammar and **cannot parse `??`, and likely other modern syntax**
-- it reports false "invalid syntax" on any file using it. Switched to the
`php-parser` npm package (`npm install php-parser` in a scratch dir,
`new parser.default({ version: '8.1' })`) for this pass -- correctly
parses modern PHP. Recommend this (or real PHPCS once PHP/composer is
available) over phply for any future PHP syntax verification.

**Audited the whole theme (350 PHP files, ~55,600 lines) for real PHP 8.1
compatibility risks and found it already clean of:**
- Functions removed in PHP 8.0 (`each()`, `create_function()`,
  `get_magic_quotes_*()`, `FILTER_SANITIZE_STRING`, old `mysql_*` ext).
- Curly-brace string offset access (`$var{0}`) -- fatal in PHP 8.0.
- PHP4-style constructors (method named the same as its class).
- Required function parameters declared after optional ones (deprecated
  since PHP 8.0).
- Deprecated WordPress core functions (`screen_icon()`,
  `get_currentuserinfo()`, `get_settings()`, `wp_specialchars()`,
  `attribute_escape()`, `clean_url()`, `js_escape()`,
  `get_userdatabylogin()`, `extract()`).
- `sizeof()` / `is_null()` (WPCS-discouraged aliases).

**Fixed, committed in this order:**
- `02c1171` -- Replaced the theme's only 2 `query_posts()` calls (both in
  `templates/resources-components/`) with `new WP_Query()`, matching the
  pattern already used by the sibling "most-recent" branch in the same
  ACF field. `query_posts()` is WordPress's own documented anti-pattern
  (overwrites the global `$wp_query`, can corrupt pagination/conditional
  tags for anything rendered later on the page). Also swept all 33
  `wp_reset_query()` calls sitewide to `wp_reset_postdata()` (the correct
  pairing for `WP_Query()`, since `wp_reset_query()` is specifically for
  undoing `query_posts()` and none remain).
- `9a09124` -- Converted 28 `isset($x) ? f($x) : $default` ternaries to
  `f($x ?? $default)` across `functions.php`, `index.php`, and 13
  templates. Only converted where `f($default) === $default` was
  individually verified (sanitize_text_field(''), esc_attr(''),
  intval(1), array_map(fn, array()) all round-trip clean). **Deliberately
  left ~11 sibling cases alone** -- the pattern
  `isset($_GET['x']) ? array_map('sanitize_text_field', (array) $_GET['x']) : ''`
  has a pre-existing type inconsistency (true branch returns an array,
  false branch returns a bare string) that predates this session; a `??`
  conversion would silently change the unset-case result type. Flagged,
  not fixed -- worth a human decision on what the intended behavior
  actually is.
- `6023bd1` -- Converted 6 `list($a, $b) = $x;` to `[$a, $b] = $x;` in
  `functions.php` (fully interchangeable, PHP 7.1+ short syntax).
- `7a019ee` -- Converted the theme's one `strpos($a, $b) !== 0` check to
  `! str_starts_with($a, $b)` (PHP 8.0+).

**Verification:** every changed file checked with `php-parser`
(`version: '8.1'`); additionally ran it across **all 350 PHP files in the
theme** (not just changed ones) as a final whole-repo sanity check --
100% parse clean under PHP 8.1 grammar.

**Deliberately NOT attempted:** bulk `array()` → `[]` short-array-syntax
conversion. This is likely the single largest remaining "modernization"
opportunity by volume, but there's no PHP-aware code *printer* available
in this sandbox (php-parser can parse but not safely regenerate source
while preserving original formatting/whitespace in PHP-mixed-with-HTML
templates), and a blind regex pass across 350 files risks mismatched
parens in nested arrays or false matches inside strings/comments. Needs
either a real PHP toolchain (PHP-CS-Fixer, run on the user's machine or
CI) or a much more careful, file-by-file pass than was practical here.

## 8. 2026-09-02 continued: dead code, debug leaks, no_found_rows sweep

Follow-up to §7, same broad instruction ("do what will make the theme
improve, speed up, optimized"). Three separate small cleanups, each
verified with `php-parser` (PHP 8.1) and committed separately:

**Debug-output leaks removed** (leftover from prior debugging sessions,
were printing raw text/dumps into live production HTML on every request):
- `templates/template-podcast.php` -- `echo 'podcast template loaded';`
- `templates/single-post_author.php` -- `print_r($event_slug); // Debugging output`

**Dead code removed** (per-file live/dead variable tracing -- confirmed
via grep that removed variables/blocks are never referenced elsewhere in
each file; explicitly did NOT assume the same block is dead across
near-identical files -- confirmed the equivalent block is live code in
`template-podcast.php` and the whole "resource-type" template family):
- `templates/template-media.php` -- removed a whole dead block:
  `$q = get_queried_object(); $resourceType = get_field('type', $q);
  $keyword = ...; $filterTopic = ...;` plus its dead `if/else` `$args`
  block. `get_field()` is a real ACF/postmeta DB query, so this is a
  genuine (if small) query-count reduction, not just cosmetic.
- `templates/template-news.php` -- removed a dead `$keyword` var and its
  dead `if/else` `$args` block.
- `templates/template-podcast.php` -- removed a dead `if($keyword != '')`
  `$args` block (the real `$args` used later overwrites it before any
  query runs); kept `$q`, `$resourceType`, `$keyword`, `$filterTopic`
  since all four ARE used later in this file.

**`no_found_rows => true` sweep** -- added to `WP_Query()` calls that are
never used for pagination (confirmed per-file: no `wp_pagenavi()`,
`paginate_links()`, or `next_posts_link()` anywhere touches that query's
`$posts`/`max_num_pages`/`found_posts`). This skips WordPress's
`SQL_CALC_FOUND_ROWS`/COUNT pass, a real (if modest) query-cost
reduction on every affected page load. Applied conservatively, file by
file, specifically to avoid the failure mode of silently breaking
pagination:
- `4b50a36` -- 4 "resource-type family" templates (`template-topic.php`,
  `template-resource-type.php`, `template-resource-type-pre-media.php`,
  `old-template-resource-type.php`): both keyword/no-keyword branches of
  the top-of-file `$loop` query used only for filter-button taxonomy-term
  collection (the real paginated listing query further down in each file
  was left untouched).
- `f38ef19` -- 7 more templates/partials: `single-post_author.php` (5
  queries -- 2 single-item speaker/contributor lookups, 3 fetch-all
  queries for author posts/events/registrations), `_open-positions.php`
  (careers listing), `_advisors-carousel.php` (speakers carousel),
  `_related-articles-taxonomies-grid.php` (3 per-term queries inside
  foreach loops across the event/topic/filter-type taxonomy branches),
  `_events-listing-partners.php` and `_events-listing.php` (2 queries
  each -- year-term collection + main listing; neither file calls
  `wp_pagenavi`/`paginate_links` at all), `single-position.php` (related
  open-positions listing).

**Explicitly left alone:** `template-insights.php` and
`template-search-results.php` -- these share a single `$args` variable
across multiple `WP_Query()` calls in some branches, making it too easy
to misapply `no_found_rows` to a query that's actually paginated
elsewhere. Would need a slower, dedicated pass if this is wanted later.

## 6. Open items for the next session

1. Get user confirmation that Sections 1–5 **and now 6** of the float
   refactor render correctly with `?dev=true` -- still no in-thread
   confirmation for any of it. Test the mobile nav (main menu dropdowns,
   the "Our Services" sub-list, the subscribe form inside it, and the
   separate Resources panel's logo/ADAPT header row) with `?dev=true` on
   staging before extending further.
2. ~~Section 6: remaining `_header.scss` mobile-menu floats (see §2).~~
   **done, 2026-09-02** -- see §2's Section 6 writeup. Awaiting the
   `?dev=true` staging check in item 1 above before it's trustworthy to
   build on further.
3. Extend the float audit beyond `_header.scss`/`_flexible.scss`/
   `_resources-types.scss`/`_customer-events.scss`/`_events.scss`/
   `_registrations.scss`/`_post.scss`/`_login.scss`/
   `_single-speaker.scss`/`_form-pages.scss`/`_thank-you.scss`
   (Sections 1-16, done; `_default.scss`/`_author.scss` checked,
   already fully covered) to the other ~18 flagged SCSS files (see
   §2).
4. `#70` — likely already resolved by RUCSS being active (see §3's
   2026-09-02 follow-up); would benefit from a real network trace as a
   logged-out visitor to fully close it out (needs either browser dev tools
   with the admin session logged out, or a separate anonymous testing
   setup — not something this sandbox could do safely without signing the
   existing browser session out).
5. `#86`/`#87`/`#88` — **done, 2026-09-02.** RUCSS is active with a
   populated safelist; see §3.
6. Visually confirm the phone-input height change looks right in a real
   HubSpot form.
7. If a real PHP toolchain becomes available (user's local machine, or
   CI), run PHPCS with WordPress-Extra + PHPCompatibilityWP (target 8.1)
   for a proper, complete audit -- this session's grep-based sweep is
   solid for the categories it checked, but isn't a substitute for the
   real tool. Also consider PHP-CS-Fixer for the bulk `array()` → `[]`
   conversion (~350 files) mentioned in §7.
9. ~~Check `staging.adapt.com.au/?dev=true` ... footer should render
   identically~~ **done, 2026-09-02** -- spot-checked (correct files
   enqueued, correct computed styles, no console errors), then promoted
   to the default path once confirmed (see §9 above). Still worth an
   actual eyeball check on your end at some point (this sandbox's
   screenshot tool couldn't render a usable image of the scrolled page --
   see the note in §9), but nothing is blocking on it now.
8. ~~`template-insights.php` / `template-search-results.php` -- a
   careful, dedicated pass to add `no_found_rows` where safe~~ **done,
   2026-09-02** (`23567b1`) -- traced each file's reassigned `$args`
   through every `WP_Query()` call site individually; added
   `no_found_rows` to the 6 term-collection/count-only queries in
   `template-insights.php` and the 2 in `template-search-results.php`;
   left the real paginated-listing queries (tied to `wp_pagenavi()`)
   untouched in both. `template-search.php` was checked too -- it only
   uses the main query + `paginate_links()`, no custom `WP_Query`, so
   there was nothing to change there.
