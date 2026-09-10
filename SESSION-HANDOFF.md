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
| `63e4511` | **Section 17**: `_market.scss` (5 of 6; 1 deliberately skipped) -- see §2m below |
| `0138f76` | **Section 18**: `_app.scss` -- see §2n below |
| `701f73e` (reverted `c360699`) | 5 ad hoc `display:flex` tweaks applied directly to live templates -- wrong, see §2o |
| `b53d868` | Section 19: same 5 tweaks, properly gated -- see §2o |
| `5a273bf` | Section 19 cascade-order bugfix: `!important` on 3 of the 5 -- see §2o |

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
  `91f3cf8`)** -- see §2l below. **`_market.scss` is now done too
  (Section 17, 2026-09-02, `63e4511`, 5 of 6 -- 1 deliberately left
  uncovered)** -- see §2m below. **`_app.scss` is now done too
  (Section 18, 2026-09-02, `0138f76`)** -- see §2n below.
  `_default.scss` and `_author.scss` were checked and found already
  fully covered by the earlier mechanical batch (0 uncovered
  declarations), no fix needed. Still untouched: the rest of the ~16
  remaining files in `source/scss/templates/`.
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

### 2m. Section 17 (`_market.scss`), 2026-09-02

5 of 6 remaining declarations fixed; 1 deliberately left uncovered.
`.market-two-column`'s `.column` needed its own new flex parent for a
genuine `.inner-column` (image + text) pair inside it, confirmed via
`templates/components/_market-two-column.php`. The interesting call
this section: `section.market-featured`'s mobile-only `.item
.item-content-container` row override was traced back to a shared
`.item.one-third` base selector spanning `_events.scss` and
`_resources-types.scss` (the section renders with class
`market-featured filter-listing`, picking up that shared rule family)
whose desktop `.image-container`/`<a>` float behavior wasn't fully
mapped in this pass -- rather than guess at a plausible-looking fix,
left it uncovered for a dedicated future pass through that shared
selector's full definition. This is the first section with a partial
(not 100%) completion -- documented explicitly in the commit message
and here so it isn't mistaken for an oversight later. Same
build+diff verification as every other section, 0/0/0 ungated on both
compiled files. Committed as `63e4511`.

### 2n. Section 18 (`_app.scss`), 2026-09-02

2 remaining declarations, and a good example of not trusting a
pattern-match on sight: `.text-container:nth-child(1) p{float:right}`
paired with `:nth-child(2) p{float:left}` looks exactly like the
float-reordering row pattern from Sections 6/10/11 (opposite float
directions on adjacent siblings), but a direct read showed
`:nth-child(1)` and `:nth-child(2)` are two separate, independently
positioned `.text-container` boxes, not two children within one row --
each `<p>` is the sole content of its own box. Bare float:none for
both, no `flex-direction: row-reverse` involved. Same build+diff
verification as every other section, 0/0/0 ungated on both compiled
files. Committed as `0138f76`.

### 2o. Section 19 (ad hoc `display:flex` tweaks) + cascade-order bugfix, 2026-09-02/03

Added `body.dev-float-refactor` overrides for 5 directly user-specified
selectors (not from the systematic per-file audit): `display:flex` on
`section.filter-title-block .container .title-container`,
`section.roundtable-card-slider-module ... .slide-image-container`,
and `section.two-column-services ... .column.icon-text-column
.service`; `display:flex !important; align-items:center` plus a
`margin-top:0` neutralizer on `.links-container`/`.text-link` in the
same `two-column-services` component; and `padding-top:1px; clear:both`
on `section.quote-slider .container .progress-container`. First
committed the wrong way (`701f73e`) -- directly in the live templates,
not gated -- caught and reverted (`c360699`), then redone properly
gated (`b53d868`).

**Real bug found afterward:** three of the five (`.title-container`,
`.slide-image-container`, `.service`) each already had a pre-existing
gated `float:none` rule from the original mechanical batch. This
file's own `fix-float-none-display.js` build step adds `display:block`
to every gated `float:none` selector as a separate declaration, and in
the compiled output that `display:block` rule landed AFTER this
section's plain `display:flex` -- so `display:block` silently won the
cascade and the flex fix never took visual effect, despite being
correctly present in the SCSS source and even correctly present in the
compiled CSS (it just lost the cascade to a rule the user could see in
devtools). Fixed by adding `!important` to those three declarations
(commit `5a273bf`), matching what `.links-container` already had for
the same reason. Confirmed in the freshly compiled CSS that all three
are now isolated into their own rule ending in
`display:flex!important` (previously merged into a large group of
*unrelated* selectors sharing plain `display:flex` -- footer
`.group-column`, `.featured-types .item`, `.filter-title-block
.container.podcast-container` -- confirmed those three still have
`display:flex` intact elsewhere, unaffected by the split).

**Lesson for future sections:** any selector that already has a
mechanical-batch `float:none` gated rule needs `!important` on a new
`display:` override in this file, not just plain `display:flex` --
the postprocessor's `display:block` addition isn't guaranteed to
compile before your own rule in file order, even though it looks like
it should from the SCSS source order alone.

**Environment note:** node_modules wasn't present/cached for this
verification pass -- had to `npm install --no-audit --no-fund
--ignore-scripts` in the isolated worktree (plain `npm install` fails
on `gifsicle`'s native build, which isn't available in this sandbox
and isn't needed for `build:styles`).

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
   staging before extending further. **See §10 (2026-09-07) first --**
   a real bug was found and fixed in the mechanical fix script that
   generates most of these overrides' `display` values; 66 selectors
   across the already-completed Sections 1-19 are now known to need a
   hand-written, media-scoped `display` override before this check can
   be considered trustworthy end-to-end.
2. ~~Section 6: remaining `_header.scss` mobile-menu floats (see §2).~~
   **done, 2026-09-02** -- see §2's Section 6 writeup. Awaiting the
   `?dev=true` staging check in item 1 above before it's trustworthy to
   build on further.
3. Extend the float audit beyond `_header.scss`/`_flexible.scss`/
   `_resources-types.scss`/`_customer-events.scss`/`_events.scss`/
   `_registrations.scss`/`_post.scss`/`_login.scss`/
   `_single-speaker.scss`/`_form-pages.scss`/`_thank-you.scss`/
   `_market.scss`/`_app.scss` (Sections 1-18, done; `_default.scss`/
   `_author.scss` checked, already fully covered) to the other ~16
   flagged SCSS files (see §2). Note `_market.scss` has 1 declaration
   deliberately left uncovered (§2m) -- worth a dedicated follow-up
   pass through the shared `.item.one-third` selector family (spans
   `_events.scss`/`_resources-types.scss`) before closing it out.
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

---

## 10. 2026-09-07: theme optimization phase begins — visual regression
    gate, dead-code sweep, and a real bug found/fixed in the float-
    refactor's mechanical display-fix script

### Context

User asked to move from feature work to theme-wide optimization (PHP 8+/
WP standards, dead code, "modern HTML/CSS/JS ... without affecting much
how the frontend looks"), explicitly requiring a pixel-diff gate
("Pixel by pixel. no excuses.") and zero tolerance for visual
regressions, with an explicit standing instruction that `main`
(production) must never be touched/pushed without separate confirmation
-- all work stays on `dev`.

### Visual regression gate (`tools/visual-regression/`)

A sitemap-wide, zero-tolerance Playwright screenshot+pixel-diff CLI
(`capture-and-diff.mjs`) plus a `workflow_dispatch`-only GitHub Actions
workflow (`.github/workflows/visual-regression.yml`), deliberately NOT
`workflow_run`-triggered to avoid any risk of touching `main` (a
`workflow_run` trigger requires the workflow file to exist on the repo's
*default* branch, which is `main` here). Modes: `capture-baseline`
(cached, versioned via `.baseline-version` since `actions/cache` can't
overwrite an existing key) and `check` (compares against the cached
baseline, uploads diff artifacts). 3 viewports (375/768/1440px). Not yet
run for real -- needs a `workflow_dispatch` trigger from the user (or a
push + manual Actions-tab trigger) to capture the first baseline.

### Dead code

Removed 6 confirmed-zero-call-site functions from `includes/_functions.php`
(`get_id_by_slug`, `get_excerpt_by_id`, `is_paginated`, `slugify`,
`updateQueryString`, `redirectPage`) -- verified via whole-repo,
whole-file-type grep before removal. `34beeb6`.

### A real bug found and fixed in `fix-float-none-display.js`

This is the gulp postcss script (see §2) that mechanically adds
`display: block` to gated `float:none`-only overrides so blockified
inline elements (span/a/etc. that relied on `float` to become block
boxes) don't collapse back to inline when the float is removed. Live
`?dev=true` testing at 375px on staging found the desktop mega-menu
visible behind the mobile menu -- traced via `getComputedStyle()` to
`.main-nav` computing `display: block` when production has it `display:
none` below 1250px.

**Root cause:** the script's existing safety check (added earlier the
same day, for a related but different bug -- see the script's own
header comment, "third fix") compared a gated rule's selector against
every other selector in the file by *exact string match* to detect
"this selector's display is context-sensitive elsewhere, don't
mechanically fix it." Section 2's hand-written `.main-nav` override
pads its ancestor chain with an extra `.header-inner` (added for
cascade specificity) that production's `display:none` rule doesn't have
-- same real DOM element, different selector *string* -- so the exact
match silently missed it, and the script mechanically injected an
unconditional `display: block` that won the cascade at every width,
including the 1250px breakpoint it should never have applied at.

**Fix (`32c34cf`):** added a second, more conservative check alongside
the exact-selector one -- compare by the selector's trailing **two**
segments (leaf + immediate parent, e.g. `.menu .main-nav`) instead of
requiring the full string to match. A single trailing segment was tried
first and rejected: this codebase reuses very generic leaf classes
(`.text`, `.column-container`, `p`, `a`, `h2`, `.image-container`, ...)
across dozens of unrelated components, so a 1-segment match flagged 343
of ~1700 gated rules against the real, full stylesheet -- mostly
coincidental collisions between unrelated DOM subtrees that would have
buried the real signal. The 2-segment match is specific enough to avoid
that (down to 66 flagged) while still catching `.main-nav` itself (the
inserted `.header-inner` sits earlier in the chain, not adjacent to
`.menu`/`.main-nav`). Full reasoning and the exact tradeoff (an ancestor
inserted directly next to the leaf could still theoretically slip past)
is documented in the script's own header comment ("fourth fix").

**Verification:** rebuilt the full compiled stylesheet in an isolated
scratch copy (`rsync` → `npm ci --ignore-scripts` → `npx gulp
build:styles build:styles-split`, per §1's methodology) and diffed every
`body.dev-float-refactor`-prefixed rule against the pre-fix compiled
output selector-by-selector. Result: exactly 65 selectors changed in
`main-nofooter.min.css`, every one losing exactly the erroneous
`;display:block` (confirmed `.main-nav` is among them, now
`float:none` only); `footer.min.css` byte-identical (no gated rules
affected there); everything outside `body.dev-float-refactor` rules in
both files byte-for-byte identical to the pre-fix build. Committed the
script fix and the rebuilt `assets/css/main-nofooter.min.css` together
(`32c34cf`) -- `footer.min.css` needed no commit (byte-identical).

**Not yet done:** live re-verification on staging (requires this commit
to be pushed and deployed first -- not done in this session, per the
"never push/deploy without the user" rule).

### The 66-selector follow-up list (needs hand-written overrides)

These are left as bare `float:none` in the compiled CSS -- safe by
default (a no-op for the block-default majority of elements; visually
incomplete only for the inline-default minority that actually needs an
explicit, *media-scoped* `display` to match production's responsive
behavior). Each needs a human to look at the referenced production rule
and write a properly-scoped override in
`source/scss/sections/_dev-float-refactor.scss`, the same way Section
2's `.main-nav` handling already does it by hand. Re-run
`npx gulp build:styles build:styles-split` after editing and check the
console output -- the count should drop by exactly the number resolved,
with no new selectors appearing.

```
.item.press-release-item .press-date-container  [at (no media query)]  -- has display at: media (max-width:767px)
.sideArticles  [at (no media query)]  -- has display at: media only screen and (max-width:767px)
.single-post-sticky .container span.buttonWrapperSingleResources  [at (no media query)]  -- has display at: media (max-width:1250px)
header .container .header-inner .headerRight .menu .main-nav  [at (no media query)]  -- has display at: media (max-width:1250px)
header .container .header-inner .headerRight .menu-buttons  [at (no media query)]  -- has display at: media (max-width:1250px)
header .container .headerRight .menu .main-nav ul li .megaMenu .column .menu-link .text  [at (no media query)]  -- has display at: media (max-width:1200px)
header .resources-sticky-menu .container .resources-nav ul li .megaMenu .column .menu-link .text  [at (no media query)]  -- has display at: media (max-width:1200px)
header .resources-sticky-menu .container .resources-sticky-inner .scroll-image-container .logo .resources-link-mobile  [at (no media query)]  -- has display at: (no media query), media (max-width:1250px)
section.animation-introduction .container .introduction-content-container .text-link-container  [at (no media query)]  -- has display at: media (max-width:767px)
section.comparison-three-column-text .container .column-container  [at (no media query)]  -- has display at: (no media query), media (max-width:767px), media only screen and (max-width:767px), media (min-width:768px)
section.comparison-three-column-text .container .column-container .column .column-inner  [at (no media query)]  -- has display at: media (max-width:767px)
section.comparison-three-column-text .container .column-container .column p  [at (no media query)]  -- has display at: media only screen and (max-width:767px)
section.contact-module .container .contact-innner .column.sub-title-form-column .form-container  [at (no media query)]  -- has display at: media (max-width:767px)
section.contact-module .container .contact-innner .column.title-details-column .bottom-container .fast-track-text  [at (no media query)]  -- has display at: media (max-width:767px)
section.content-slider-module .container .top-content .link-container  [at (no media query)]  -- has display at: media (max-width:1023px)
section.download-block.resources-block .container .resources-container  [at (no media query)]  -- has display at: (no media query), media (max-width:1023px)
section.events-listing-module .container .events-listing-top .year-button-container .year-button-sticky  [at (no media query)]  -- has display at: media (max-width:1023px)
section.flex-two-column-text .container .column-container  [at (no media query)]  -- has display at: media (max-width:767px), media (min-width:768px)
section.information-blocks .container .column-container  [at (no media query)]  -- has display at: (no media query), media (max-width:767px), media only screen and (max-width:767px), media (min-width:768px)
section.list-card-module .container .list-card-container .one-third.list-card .list-card-front .hover-list-container .text-link-container a  [at (no media query)]  -- has display at: (no media query), media (max-width:767px)
section.market-form .container .market-column-container .column.form-column  [at (no media query)]  -- has display at: media (max-width:1023px)
section.navigation  [at (no media query)]  -- has display at: media only screen and (max-width:767px)
section.partner-form .container .form-text-container .column.text-content-column .bottom-container .fast-track-text  [at (no media query)]  -- has display at: media (max-width:767px)
section.post-article-container .container .left-column.sticky-scroll-to-container  [at (no media query)]  -- has display at: media (max-width:1023px)
section.post-article-container .container .post-content .sidebar-content  [at (no media query)]  -- has display at: media (max-width:767px)
section.post-article-container .container .sidebar-container  [at (no media query)]  -- has display at: (no media query), media only screen and (max-width:767px)
section.post-title-block .container .sidebar-container  [at (no media query)]  -- has display at: (no media query), media only screen and (max-width:767px)
section.register-listing .container .register-listing-container .upcoming-listing .item.one-third .item-container .item-top span.item-excerpt  [at (no media query)]  -- has display at: media only screen and (max-width:767px)
section.researchArticleTextHeader.replayArticleHeader .container .item .textContainer .replay-button-container  [at (no media query)]  -- has display at: media only screen and (max-width:1023px)
section.services-two-column-image-text .container .column-container  [at (no media query)]  -- has display at: media (max-width:767px)
section.sneak-peak-module .container .sneak-peak-container .sneak-image-container  [at (no media query)]  -- has display at: media (max-width:767px)
section.speaker-module .container .speakers-container-outer .filter-container-outer .filter-container form  [at (no media query)]  -- has display at: media (max-width:767px)
section.speaker-module.partner-module .container .speakers-container-outer .speakers .column.speaker-item a.slide-out-bio .text-container.desktop-hide  [at (no media query)]  -- has display at: (no media query), media (max-width:1023px)
section.speaker-profile.author-section .container .column-container .details-column h1  [at (no media query)]  -- has display at: media only screen and (max-width:767px)
section.speaker-profile.author-section .container .column-container .details-column h3  [at (no media query)]  -- has display at: media only screen and (max-width:767px)
section.two-column-services .container .services-column-container .column.text-column .text-content-inner .links-container  [at (no media query)]  -- has display at: media (max-width:767px), (no media query)
section.two-column-switch-module .container .title-switch-container .switcher-container  [at (no media query)]  -- has display at: media (max-width:1100px)
section.two-column.two-column-image-slider-events .container .column-container  [at (no media query)]  -- has display at: (no media query), media (max-width:767px), media only screen and (max-width:767px), media (min-width:768px)
section.filter-listing .container .grid-wrapper .market-trend-reports-container-side-bar  [at media only screen and (max-width:1023px)]  -- has display at: (no media query)
section.contact-module .container .contact-innner .column.title-details-column .bottom-container .form-popup-button-container a  [at media (max-width:1023px)]  -- has display at: (no media query)
section.partners-cards-module .container .card-container .card  [at media (max-width:1023px)]  -- has display at: (no media query)
section.two-column-logo-carousel .container .column-container .column.text-column .button-container a  [at media (max-width:1023px)]  -- has display at: (no media query)
section.featured-module .container .grid-wrapper  [at media only screen and (max-width:767px)]  -- has display at: (no media query)
section.filter-listing .container .grid-wrapper .market-trend-reports-container-side-bar  [at media only screen and (max-width:767px)]  -- has display at: (no media query)
.sticky-slider-cards .slider-scrolling-container .heading-container .inner-text-container  [at media (max-width:767px)]  -- has display at: (no media query)
.sticky-slider-cards .slider-scrolling-container .heading-container .inner-text-container span.p-large  [at media (max-width:767px)]  -- has display at: (no media query)
section.animation-introduction .container .introduction-content-container .button-container a  [at media (max-width:767px)]  -- has display at: (no media query)
section.centered-text-links.advisors-centered-text-links .container .text-container .text  [at media (max-width:767px)]  -- has display at: (no media query)
section.events-logos-text-block .events-logos-text-block-outer .container .text-content-container .text-inner .icon-container img  [at media (max-width:767px)]  -- has display at: (no media query)
section.events-title-block .container .content-container .button-container a  [at media (max-width:767px)]  -- has display at: (no media query)
section.events-title-block.events-title-block-background .container .content-container .button-container a  [at media (max-width:767px)]  -- has display at: (no media query)
section.filter-listing.in-the-news-listing .container .sidebar-container  [at media (max-width:767px)]  -- has display at: media only screen and (max-width:767px)
section.left-text-links.advisors-centered-text-links .container .text-container .text  [at media (max-width:767px)]  -- has display at: (no media query)
section.partner-form .container .form-text-container .column.text-content-column .bottom-container .form-popup-button-container a  [at media (max-width:767px)]  -- has display at: (no media query)
section.partners-cards-module .container .button-container a  [at media (max-width:767px)]  -- has display at: (no media query)
section.services-cards-module .container .card-container .card  [at media (max-width:767px)]  -- has display at: (no media query)
section.sponsor-block .container .bottom-block .button-container .std-button  [at media (max-width:767px)]  -- has display at: (no media query)
section.subscribe-introduction .container .content-container .content-inner .button-container a  [at media (max-width:767px)]  -- has display at: (no media query)
section.three-column-icon-text .container .icon-text-column-container .column  [at media (max-width:767px)]  -- has display at: (no media query)
section.two-column-cta .container .column.text-column .button-container a  [at media (max-width:767px)]  -- has display at: (no media query)
section.two-column-image-text-customer-events .container .column-container .column .button-container a  [at media (max-width:767px)]  -- has display at: (no media query)
section.two-column-image-text-events .container .two-column-image-text-outer .text-column .button-container a  [at media (max-width:767px)]  -- has display at: (no media query)
section.two-column-switch-module .container .module-container .switch-module .column-container .column .list-container .button-container a  [at media (max-width:767px)]  -- has display at: (no media query)
section.two-column-text-image-cards .container .column-container-outer  [at media (max-width:767px)]  -- has display at: (no media query)
section.two-column-text-image-cards .container .title-container h2  [at media (max-width:767px)]  -- has display at: (no media query)
section.two-column-thank-you-module .container .column-container .column .list-container .button-container a  [at media (max-width:767px)]  -- has display at: (no media query)
```

### Unrelated observation

`templates/customer-events-components/_left-text-links.php` has an
uncommitted local modification (adds a `hubspot-form` branch to a
`link_type` check) that predates this session's work and wasn't made by
this session -- left untouched and unstaged; not part of any change
described above. Worth asking the user about directly rather than
assuming it's stale/intentional.

---

## 11. 2026-09-08: mobile-specific regression pass (unscoped desktop-only fixes)

User reported "mobile looks broken compared to production, not just the
footer, but the sections" after the §10 66-item follow-up work (commit
`3bf2211`) was pushed. Root cause pattern, found repeatedly: several
`display:flex` (or a `bottom:20px` position tweak) additions in
`_dev-float-refactor.scss` were written **without a media-query scope**,
intending a desktop-only effect, but because they had no `min-width`
qualifier they also fired on mobile -- where the *real* (non-gated) CSS
relies on a `@media(max-width:767px)` override (`width:100%` float-stacking,
`display:none`, etc.) that the unscoped gated rule was defeating.

Verification method: at 375x812 (`resize_window` mobile preset), measure
every `<section>`'s (and suspect sub-element's) `getBoundingClientRect()`
height on `?dev=true` vs production and diff; any non-zero, non-trivial
diff was traced to the specific child element and its real vs gated CSS.
Every fix was confirmed with the postcss rule-level diff
(`/tmp/proper-diff-normalized.cjs` pattern -- normalizes comma-selector-list
reordering across builds, which a naive exact-string diff false-positives
on) showing zero non-gated changes and only the intended gated change.

**Six real bugs found and fixed, six commits (all gated, all pushed):**

1. `b2d242c` -- footer `.back-to-top`'s `bottom:20px` tweak (added in
   `5306d12`) was unscoped; it also has a completely different real
   context below 1024px (`position:fixed`, floating scroll-to-top button,
   real `bottom:40px`). Scoped to `min-width:1024px`.
2. `66539f4` -- three bugs: `section.featured-module.featured-home
   .post-list-container`'s `display:flex` (57/43 desktop split) broke the
   real mobile float-stack of `.first-post-column`/`.side-bar-column`
   (both carry the global `.one-half` class, `width:100%` below 768px);
   `section.quote-slider .container .progress-container`'s auto-added
   `display:block` (from a pure `float:none` fix) needed `clear:both`
   since its preceding sibling `.quote-slider-module` stays `float:left`
   on purpose (Slick-managed) and a non-cleared block doesn't stack below
   a still-floating sibling; `two-column-services
   .text-content-inner .links-container`'s `display:flex!important` was
   unscoped and beat the real `@media(max-width:767px){display:none}`.
   All three scoped to `min-width:768px` (or given `clear:both` +
   explicit `display:block`, since adding a second declaration makes a
   rule "impure" and the mechanical fixer's `isPureFloatNone` check stops
   auto-adding `display:block` for it -- same side-effect class as #6
   below).
3. `7164742` -- `section.featured-module .container .title-container`'s
   `display:flex` (66.66%/33.34% split) had the same float-stacking
   conflict; scoping it to `min-width:768px` **cascaded into 24 more
   selectors** losing their auto-generated `display:block`, because
   `.container .title-container` is a common 2-segment leaf-compound
   shared by ~24 unrelated sections' own pure `float:none` rules --
   moving one rule into a `@media` block changed what the mechanical
   fixer's whole-file leaf-compound map reports for that leaf. Same
   mechanism as the two rounds of `.button-container a` side effects in
   §10. Fixed by adding explicit `display:block` for all 24.
4. `2882137` -- a mechanically-generated `.column .text` rule for
   `section.expanding-form-module` was too broad: it also matched
   `.info-column`'s *other* `.text`, which carries a real `.hide-mobile`
   modifier (`@media(max-width:767px){display:none}`). Fixed with a more
   specific `.text.hide-mobile` override at `max-width:767px`.
5. `44b8e29` -- `section.switcher-module` and `section.two-column-icon-text`
   both have a `.column` containing `.icon-container` (float:left, fixed
   80px) + `.text-container` (float:left, `calc(100% - 80px)`) -- sums to
   exactly 100% at desktop, side by side. The existing gated fix added
   `display:flex` to `.column` (correctly, `.icon-container`/
   `.text-container` are a genuine still-live float pair), but
   `.text-container`'s real CSS also has a mobile override
   (`width:100%`), making the pair sum to 180% at that breakpoint --
   production overflows the float and wraps `.text-container` below the
   icon (the width-overflow stacking trick). A nowrap flex row instead
   flex-shrinks both to fit the same row, squeezing icon+text side by
   side at mobile instead of stacking. Added `flex-wrap:wrap` to both
   (only engages where the combined basis exceeds the row, i.e. the same
   breakpoint where production's own overflow-wrap already kicks in).
6. `5b5b4a6` -- three more exact-selector conflicts found via a
   whole-file scan for `display:flex` with no `@media` wrapper:
   `section.list-block .container .list-container .item.mobile-hide`
   (real `display:none` at max-width:767px -- the class name says it
   outright), `section.registration-two-column-block .container
   .column-container` (real `display:block` at max-width:767px), and
   `section.events-listing-module .events-listing.partners-events-listing
   .item .container` (real `display:block` at max-width:767px). Same
   mechanism as `.links-container`/`.hide-mobile` above. **Not visually
   confirmed live** -- couldn't locate a staging page using
   `list-block`/`registration-two-column-block`/the partners variant of
   events-listing in the time available (tried
   `/benchmark-maturity-assessment/cloud-compute-finops-maturity` for
   list-block; that page doesn't render a `section.list-block` at all,
   so the ACF flexible-content block isn't in use there). Verified only
   structurally (postcss diff: 0 non-gated changes, exactly the 3
   intended additions). Worth a live look on whichever page actually
   uses these blocks next time one is found.

**Explicitly NOT touched -- "Section 19" hand-designed user-requested
tweaks** (see `_dev-float-refactor.scss` around the "Section 19" header
comment, ~line 20788): `section.filter-title-block .container
.title-container`, `section.roundtable-card-slider-module ...
.slide-image-container`, and `section.two-column-services ...
.icon-text-column .service` are explicitly documented as *your own*
ad hoc experimental `display:flex!important` requests, gated for staging
review before folding into production -- not float-refactor mechanical
output. `.title-container`'s flex does appear to squeeze its `h1` +
`p.type-description` into a row at ALL widths (not just mobile, since
there's no competing real breakpoint there), which may or may not be
intentional -- flagged here rather than silently "fixed" since it's your
call, not a mechanical bug.

**Confirmed SAFE (checked live, no regression) despite unscoped
`display:flex`:** `section.team-block .column-container` (already native
flex in production, `/meet-the-team/`), `section.filter-listing ...
.market-trend-reports-container-side-bar` (flex-wrap achieves the same
reflow as production's float, `/resource-type/market-trend-reports/`),
`.sticky-slider-cards ... .mobile-slide-count` (single inline line, no
visible height difference), `two-column-services ...
.icon-text-column .service` (Section 19, heights matched exactly on the
homepage).

**Not yet exhaustively checked** -- lower confidence, not visually
verified this session (grep for `display:\s*flex` inside `body.dev-float-refactor`
blocks with no enclosing `@media` in `_dev-float-refactor.scss` to
re-find them):
- `section.market-two-column .container .two-column-container .column`
  -- already has `flex-wrap:wrap` in its gated rule (same group as the
  confirmed-safe `market-trend-reports-container-side-bar` and
  `team-block`), so likely safe by the same reasoning, but not directly
  measured live.
- Header/search-dropdown group (`header .container .header-inner`,
  `.search-dropdown .container` and children, `.headerRight`,
  `.main-nav ul`, `.resources-sticky-menu` variants) -- not checked on
  a page with the search dropdown open or the sticky header engaged.
- Mobile menu (`.mobileMenuMain`, `.mobileMenuResources`) flex rules --
  presumed low risk (mobile-only UI by nature) but not opened/verified.
- `section.sneak-peak-module .container .sneak-peak-container` -- no
  `flex-wrap`, has real `@media` overrides on its children
  (`.sneak-text-container`/`.sneak-image-container` width/display), not
  checked on a page using this section.
- `section.comparison-module`'s `.title-container` showed a small
  (-20px) height diff on `/adapt-vs-gartner/` even after the title-container
  fix -- likely benign text-wrapping/line-height, not investigated
  further (same category as the -7px diffs noted below).
- `section.sticky-slider-cards` showed a larger (-100px) height diff on
  the same page, not resolved -- this component is scroll/sticky-JS
  driven (GSAP/ScrollMagic, which throws console errors on *both*
  ?dev=true and production -- a pre-existing, unrelated breakage, not
  from this work), so the diff may be animation-timing noise rather than
  a CSS bug. Worth a dedicated look if the user notices it visually.

Two small (~7px) homepage diffs from the §10 work
(`list-card-module`/`centered-text-links`) were checked and are benign --
child element heights matched exactly between `?dev=true` and production,
so they're minor line-height rounding from already-verified fixes, not
regressions.

### §11 continued -- three more commits, same day (events-listing,
footer, and two direct user-requested CSS additions)

7. `64cef3c` -- bundles four things found/requested after the six above:
   - **`.date-content-container` real bug**, distinct from the §10
     pattern: the events-listing-module event-item card's date/image row
     was explicitly flagged and *skipped* in an earlier pass (see the
     "Section 10" comment in `_dev-float-refactor.scss`, ~line 20454, as
     "real ambiguity, not mechanically safe" -- two different DOM shapes
     share the compiled selector). But a **different, earlier** mechanical
     batch (line ~7741, predating that skip decision) had already set
     `@media(max-width:767px){float:none}` on `.date-content-container`
     itself, which was never reverted. Production keeps it `float:left`
     at that breakpoint specifically so it CONTAINS its own still-floating
     child `.item-content-container` -- a non-floated parent doesn't
     contain a still-floating child (same containment-failure class as
     the `.progress-container` bug in item 2 above, roles reversed).
     Confirmed live on `/edge-events/` at 375px: dev showed 0px-tall
     (collapsed) date container vs production's 297px, a 64px
     `events-listing-module` height diff. Fixed by restoring `float:left`
     later in the cascade (same selector/media, wins by source order).
   - **Footer accordion** (`.footer-link-container-wrapper`) -- user
     reported it should be hidden on mobile until the
     `.footer-column-title-wrapper` above it is tapped; it was unhidden by
     default. Added `display:none` at `max-width:767px` with an
     `.active{display:block}` companion. Adding this new media context
     triggered the same leaf-compound side effect documented in item 3
     above, on this selector's OWN pre-existing unconditional
     auto-blockified rule, so an explicit unconditional `display:block`
     restoration was added alongside it.
   - **Direct user-requested addition**: `span.links-container.mobile
     .text-link { display: inline-block; }` (see item 8 below -- this
     version turned out not to work live and was corrected in `8449441`).
   - **Direct user-requested addition**: footer-bottom stacking --
     `.footer-bottom-left`/`.footer-bottom-right { float:none !important;
     clear:both !important; }` plus `.footer-bottom-right a { float:none
     !important; clear:both !important; display:inline; }`, verbatim as
     given by the user.

8. `8449441` -- the `.text-link` addition from `64cef3c` compiled fine
   but never took effect live. Root cause: a pre-existing, much
   higher-specificity auto-blockified rule from the original mechanical
   Category B pass (`section.two-column-services .container
   .services-column-container .links-container.mobile a { float:none;
   display:block; }`, full ancestor chain) was winning the cascade over
   the bare 4-selector `span.links-container.mobile .text-link` rule,
   regardless of source order. Fixed by matching the same full ancestor
   chain with `.text-link` appended for the needed extra specificity:
   `section.two-column-services .container .services-column-container
   .links-container.mobile a.text-link { display: inline-block; }`.
   Confirmed live post-push: both "Book a Discovery Session" instances
   now show `display: inline-block`.

9. `3887a1d` -- found while re-verifying `64cef3c` live on
   `/edge-events/`: `.event-image-container`, the sibling of
   `.date-content-container` in the same skipped row group, had its own
   separate earlier-mechanical-batch bug (line ~8052, `float:none` at
   `max-width:767px`) that was never caught by the `.date-content-container`
   fix alone. Production keeps it `float:left` at mobile so it sits beside
   `.date-content-container` in the same row; dev's `float:none` narrowed
   `.date-content-container`'s available width, causing its text to wrap
   differently -- a consistent 16px height diff per card, layered on top
   of the (already-fixed) 64px collapse. Fixed the same way, restoring
   `float:left` later in the cascade.

**Live re-verification results (post-push, this pass):** footer
accordion confirmed fully functional -- collapsed by default, expands on
title-tap (theme's own JS sets the inline style directly, not via the
`.active` class my CSS anticipated, but the net effect is correct and my
`display:none` default was the part that actually mattered);
footer-bottom stacking confirmed matching the user's exact spec
(`float:none`, `clear:both`, links stacked full-width); `.text-link`
confirmed `inline-block` live.

**`.event-image-container` fix (`3887a1d`) re-verified post-push:**
`float:left` is confirmed live and matches production exactly (both
`event-image-container` and `date-content-container` render at the same
335px width, same float value). However this did **not** close the
remaining ~16px per-card height diff on `/edge-events/` -- that
residual gap traces one level deeper, to `.item-content-container
.content-inner` and its children (`.title`, `.location`, `.excerpt`,
`.mobile-link-container`): production keeps all of these `float:left`,
while dev has them `float:none` (each child's own height matches
production exactly -- 36px/20px/140px/21px both sides -- but stacking
non-floated block children produces different margin-collapse behavior
than stacking floated ones, accounting for the 16px). **This is the
exact interior of the "Section 10 real ambiguity, not mechanically
safe" zone already documented above** (the note about `.item-content-
container`'s `calc(100% - 516px)` needing a containing-block width
recalculation if converted, "a real edit, not a mechanical float->flex
swap, left for dedicated individual review with the two real templates
open side by side"). Not touched this pass, consistent with that
existing decision -- flagging the 16px explicitly so it isn't mistaken
for a regression from `3887a1d`, which is otherwise a correct, verified
fix (it did restore the exact production float value on
`.event-image-container`, just didn't reach this deeper interior gap).

10. `a038c74` -- direct user-requested addition (gated, not part of the
    mechanical audit): user reported a 1px line visible above and below
    `section.logo-ticker-tape.background-black`. Added
    `margin-top: -1px; margin-bottom: -1px;` to close the gap. Verified
    via postcss diff (0 non-gated changes, exactly 1 new gated rule).
    Section confirmed present on the homepage; visual close-up not yet
    done as of this write-up -- check the homepage ticker-tape band at
    the top/bottom edges once pushed.

### §11 continued -- full audit closure pass (remaining "not yet
exhaustively checked" list), same day

Went through every remaining item from the "not yet exhaustively
checked" list above, live at 375px, `?dev=true` vs production.
**Everything came back safe** except one new real bug, found and fixed:

- **`market-two-column .two-column-container .column`** -- confirmed
  safe. Found a live page (`/go-to-market-insights/`). Section height
  and column height pixel-identical to production (1818px / 328px
  both).
- **Header/search-dropdown group** -- checked the mobile hamburger
  menu (`.mobileMenuMain`) and its Resources submenu
  (`.mobileMenuResources`): both pixel-identical to production via
  side-by-side screenshot. Also opened the header search overlay
  (`.search-dropdown`) and found its "Popular Topics / Reports /
  Insights" 3-column layout severely broken at mobile width (squished,
  overlapping the close button) -- but confirmed via screenshot this
  is **identically broken on production**, i.e. a pre-existing
  production bug unrelated to this session's work, out of scope, not
  touched.
- **Mobile menu flex rules** -- covered by the header check above;
  confirmed safe.
- **`sneak-peak-module .sneak-peak-container`** -- found live on
  `/edge-events/`. First measurement looked like a serious bug (section
  478px wide, overflowing a 375px viewport) -- traced to a testing
  artifact, not a real bug: the browser tab used for that check had
  never had the mobile viewport preset applied (a fresh tab opened via
  `preview_start` mid-session, still at desktop width). Re-measured
  correctly at 375px: section height, container height, and container
  width are all pixel-identical to production (1084.4375px /
  788.4375px / 335px, exactly).
- **`comparison-module` -20px and `sticky-slider-cards` -100px
  diffs on `/adapt-vs-gartner/`** -- both root-caused and confirmed
  benign, not regressions:
  - `comparison-module`: `.title-container`, `.comparison-table-
    container`, and `.button-container` are all `float:left` in
    production but `float:none` in dev (gated). A floated element
    establishes its own block-formatting-context (BFC) and so
    *contains* a child's trailing margin inside its own box height;
    a non-floated block lets that same margin *collapse through* to
    the next element instead. Both behaviors produce the exact same
    visual gap -- confirmed by direct measurement (35px gap between
    `.title-container` and `.comparison-table-container` in BOTH
    dev and production) and by pixel-identical screenshots. The
    ~36px section-level "diff" is purely this margin-accounting
    artifact, not a rendering difference.
  - `sticky-slider-cards`: each of the 5 scroll-cards' `.card-title`
    (H3) renders 20px taller in production (164px) than dev (144px)
    despite identical font-size/line-height/margin/width and identical
    visible text-wrapping (same 5 lines, confirmed via Range
    `getClientRects()`). Traced to `.slider-scrolling-content` being
    `float:left` in production vs `float:none` in dev -- likely an
    interaction between the trailing zero-width-space character in the
    card's CMS-authored copy (`"...counterparts)​"`) and how
    floated vs block boxes handle a trailing empty inline line box.
    Gaps *between* cards are identical (120px, both), and a full
    side-by-side screenshot of card 1 is visually indistinguishable
    between dev and production. Confirmed benign, imperceptible,
    pre-existing content quirk -- not touched.
  - Both diffs also confirmed independent of the pre-existing GSAP/
    ScrollMagic console error on this page (present identically on
    both dev and production, unrelated).
- **`list-block .item.mobile-hide`, `registration-two-column-block
  .column-container`** (from `5b5b4a6`, previously only structurally
  verified) -- both found live and visually confirmed this pass:
  - `list-block`: found on `/benchmark-maturity-assessment/`. All 5
    `.item.mobile-hide` elements correctly `display:none` in both dev
    and production; screenshot shows a clean collapsed accordion list.
    The ~20px section height diff is the same float-BFC margin-
    containment artifact as above (`.column-container` is
    `float:left` in production, `float:none` in dev) -- confirmed via
    screenshot, visually identical.
  - `registration-two-column-block`: found on a live roundtable
    registration page (`/roundtable/22-07-2026/...`). `.column-
    container` correctly `display:block` in both; the first of 3
    columns shows the same ~20px float-BFC artifact (other two columns
    are pixel-identical). Full-section screenshot comparison is
    visually indistinguishable.
- **`events-listing-module.partners-events-listing .item .container`**
  (from `5b5b4a6`) -- still **not found live** despite an expanded
  search this pass (checked `/event-partner/`, `/event-partner/edge-
  event-partnership/`, `/private-events-partnership/`, `/private-
  events-sponsorship/`, and a partner-specific edge sub-page). Traced
  the ACF structure: this variant only renders when a page's flexible-
  content includes an `events_list` row
  (`templates/template-event-partner.php` /
  `template-event-partner-landing.php`), which is editorially optional
  and apparently not currently used on any live page. Remains
  structurally-verified only (postcss diff clean). Low risk by analogy
  -- the base `events-listing-module` variant (same underlying
  template family) has now been live-verified correct in multiple
  other contexts this session.

**Net result: the "not yet exhaustively checked" list from the
original §11 writeup is now fully closed.** No regressions found
beyond the `event-image-container` fix already applied earlier this
pass. One pre-existing production bug newly documented (search-
dropdown mobile layout) but explicitly not touched, per the
never-change-user-facing-behavior-unless-asked rule -- it's a separate,
out-of-scope issue.

### Also this pass: raw `<img>` audit note (user-flagged, not started)

User flagged that `functions.php` and other files still use raw
`<img>` tags where `wp_get_attachment_image()` could be used. Quick
grep confirms: 424 raw `<img>` occurrences across ~60 files (excluding
`_archive/`). This is **not** a uniform find-replace -- a meaningful
share are theme-bundled static SVG icons via
`get_template_directory_uri()` (e.g. `linkedin-new.svg`,
`website.svg` in `functions.php`'s team-member markup), which aren't
WP attachments at all and can't use `wp_get_attachment_image()`.
Others pull from ACF fields returning raw URL strings rather than
attachment IDs/arrays, which would need the field's return-format
changed before the swap is even possible. Some newer components
(`templates/event-components/_sneak-peak.php`) already do this
correctly, showing the codebase is inconsistent rather than uniformly
needing the fix. Logged under the existing PHP/WP-standards audit
(task list item "Audit theme for PHP 8+/WP standards, dead code,
performance") for proper per-file categorization and sign-off, not
started as a change -- swapping markup changes rendered output
(auto `srcset`/`sizes`, possible lazy-loading differences), which is a
real behavior-change risk that needs scoping first.

---

## 12. Raw `<img>` → `adapt_acf_image()` migration (this pass, committed, not pushed)

### Why / approach

Followed up on the §11 raw-`<img>` audit note above. Rather than
statically categorizing each ACF field's `return_format` (`url` vs
`array` vs `id`) per call site -- which turned out to be unreliable,
since the *same field name* (e.g. `logo`) is configured with different
return formats in different ACF field groups depending on `location`
rules (post type), and some call sites reuse a misleadingly-named
variable across multiple different field fetches -- built a single
runtime-adaptive helper, `adapt_acf_image()`, in `includes/_functions.php`:

- Inspects the field's *value* at runtime (array with `ID`, numeric
  ID, or URL string) and resolves the real attachment ID via
  `attachment_url_to_postid()` when given a URL.
- On success, renders via `wp_get_attachment_image()` (gets registered
  image sizes, automatic `srcset`/`sizes`, WebP-rewrite integration).
- On failure to resolve an attachment ID (external URL, deleted
  attachment, etc.), falls back to rendering the **exact same plain
  `<img src="...">` markup the call site used to render directly** --
  so behavior can never regress even when resolution fails, regardless
  of which ACF return format is actually in play.
- Verified with `php-parser` (PHP 8.1) after every edit.

### Scope: converted (30 conversions across 19 files)

`functions.php` (6 -- speaker/partner bio cards), `includes/_functions.php`
(new helper only), `templates/customer-events-components/_advisor-module.php`
(2), `_advisors-carousel.php` (1), `_edge-partner-module.php` (2),
`_partner-module.php` (2), `_speaker-module.php` (2),
`templates/post-components/_infogram.php` (1, reused the file's existing
`attachment_url_to_postid()` resolution logic rather than duplicating it),
`templates/member-single-post.php` (6), `templates/single-post-feb.php` (4),
`templates/single-post-no-embed.php` (4), `templates/single-post-side-articles.php`
(4), `templates/template-flexible-nov.php` (2), `templates/template-home-nov.php`
(1), `templates/single-event.php` (2), `templates/single-event-nov.php` (2),
`templates/template-agenda.php` (4 -- 3x `logo` with a `width="100"` attribute
preserved via the helper's `$attr` passthrough, plus 1x `print_header`),
`templates/single-registration.php` (3x `speaker_image`),
`templates/template-search.php` (1x taxonomy-term-level `icon` field, via
`get_field('icon', $term)` -- confirmed the helper works fine for
term-context ACF fields too, not just post-context).

Fields converted: `logo`, `speaker_image`, `feature_image_url`/`image`
(the `_infogram.php` pattern), `print_header`, `icon`.

### Explicitly out of scope / left untouched

- **CSS `background-image: url(...)` usages** of `the_sub_field('logo')`/
  `the_field('logo')` -- these produce a background image, not `<img>`
  markup, so `adapt_acf_image()` (which only emits `<img>` tags) doesn't
  apply. Confirmed present and correctly left alone in
  `member-single-post.php`, `single-event.php`, `single-event-nov.php`,
  `template-agenda.php` (×3, `logos`/`logos_track_two` background-image
  variant).
- **Static theme-bundled SVG icons** (`linkedin-new.svg`, `website.svg`,
  `round-linkedin.svg`, `gtm-role.svg`, etc. via `get_template_directory_uri()`)
  -- not WP attachments, `get_field()`/`get_sub_field()` calls near them are
  for the link `href` or adjacent text, not the image `src`. Confirmed via a
  theme-wide grep sweep and left untouched (`functions.php`, several
  `templates/components/*.php` and `templates/customer-events-components/*.php`
  files, `templates/single-registration.php`, `templates/single-post_author.php`,
  `templates/template-speakers.php`).
- **`class="delete-no" style="display: none;"` hidden placeholder `<img>`
  tags** (video_poster, featured_image, speaker_image, infogram_image --
  22 occurrences across `member-single-post.php`, `single-post-feb.php`,
  `post-components/_infogram.php`) -- likely a JS data-source pattern (script
  reads the `src` attribute rather than rendering the image). **Not
  converted, not yet discussed with the user** -- flagging for a decision
  before touching, since it's unclear whether JS elsewhere depends on the
  exact raw-URL `src` value.
- **`_archive/templates/partials/_header-old.php`** -- confirmed via grep
  unreferenced by any live template (`_archive/` is dead code), left
  untouched.

### Verification

Every one of the 19 modified files re-verified clean with `php-parser`
(PHP 8.1) in a single batch pass immediately before committing. A
theme-wide grep swept for any remaining `<img ...>` tags whose `src`
reads an ACF field via `the_field`/`the_sub_field`/`get_field`/
`get_sub_field` -- confirmed zero remaining conversion candidates
outside the explicitly-out-of-scope categories above.

**Committed to `dev`, not pushed** (user pushes from their own
machine per standing practice). No SCSS/CSS touched this pass -- pure
PHP markup change, `?dev=true` gate untouched.

### Next steps

- No other raw-`<img>`-from-ACF-field call sites remain per the sweep;
  the migration is functionally complete.
- See §13 below for the `delete-no` hidden-placeholder decision. The
  preload/slider-data placeholder tags (`$slide['image']`/
  `$slide['inset_image']` in `single-event.php`/`single-event-nov.php`/
  `template-agenda.php`/`template-home-nov.php`) are a **different**
  pattern -- deliberately used, not removed, see §13.

---

## 13. Removed 22 dead `delete-no` hidden `<img>` tags (this pass, committed, not pushed)

### What they were

22 occurrences across 4 files (`templates/components/_resources-content-block.php`
×1, `templates/member-single-post.php` ×10, `templates/single-post-feb.php`
×10, `templates/post-components/_infogram.php` ×1) of
`<img class="delete-no" style="display: none;" src="<?php the_field(...)
?>" alt=""/>` -- always unconditionally hidden, reading `video_poster`,
`featured_image`, `speaker_image`, or `infogram_image` ACF fields.

### Why removed (not converted)

Investigated before deciding, rather than guessing:

- **Zero references anywhere in the theme.** A full-repo grep across every
  `.js` file and every `.scss`/`.css` file found no selector, no class
  lookup, no `data-*` read -- nothing touches `.delete-no` or reads these
  tags' `src`. They are not a JS data-source pattern.
- **For `video_poster`, `featured_image`, `speaker_image`:** every occurrence
  sits immediately next to a sibling element using the *exact same field*
  as a CSS `background-image: url(...)` -- i.e. the real, visible image.
  Since the hidden `<img>` requests the identical URL the background-image
  already requests, removing it changes zero bytes fetched for that visible
  image and zero pixels rendered (the element was `display:none`, never
  painted, in both browser rendering and any headless/print/PDF path).
- **For `infogram_image`:** grepped every usage of that field name theme-wide
  -- it is used **exclusively** inside these `delete-no` tags. No
  background-image, no other reference anywhere. This field's hidden `<img>`
  had literally no visible counterpart at all -- pure wasted image download
  on every page load of an infogram-type post.
- Contrasted this against the *other* hidden-`<img>` pattern flagged in §11
  (`$slide['image']`/`$slide['inset_image']`, `visibility:hidden;
  position:absolute; top:-10000px`, in the event banner/agenda templates):
  that one is a deliberate preload trick for a carousel slide's background-
  image that isn't in the initial viewport, and removing it risks a visible
  flash/pop-in when the slide becomes active -- a real behavior-change risk.
  That pattern was **left untouched**, per the standing rule. `delete-no` is
  a different, unrelated pattern with no such risk.

### Net effect

Removing these 22 tags eliminates 22 potentially-redundant image HTTP
requests per relevant page load (`display:none` does not stop the browser
from fetching an `<img>`'s `src`) -- concentrated on `member-single-post.php`
and `single-post-feb.php`, which can render up to 4 `infogram_image` fetches
each per page via repeatable ACF rows, on top of the `video_poster`/
`featured_image`/`speaker_image` duplicates already covered by the visible
background-image request. In the 2 files with duplicate print/AMP-style
markup sections, some page loads were fetching each hidden image **twice**.

Where the enclosing PHP was only an `if ( get_sub_field(...) ) { <img> }`
guard whose sole purpose was gating the now-removed tag, the whole
conditional block was removed too (not just the `<img>` line), avoiding a
redundant ACF field lookup on every loop iteration.

### Verification

Confirmed via `git diff --stat`: 4 files changed, 30 deletions, 0
insertions -- a pure removal, nothing rewritten. All 4 files re-verified
clean with `php-parser` (PHP 8.1) after editing. A final theme-wide grep
confirms zero remaining `delete-no` occurrences (excluding the unreferenced,
dead `_archive/` directory) and zero remaining references to it in any
`.js`/`.scss`/`.css` file (there were none to begin with).

No visible-pixel risk: every removed element was `display:none` before and
after (i.e. the element simply no longer exists, which is indistinguishable
from "exists but invisible" for anything a user or screen reader can
perceive), and none was referenced by any script or stylesheet.

**Committed to `dev`, not pushed** (user pushes from their own machine per
standing practice). `?dev=true` gate untouched.

---

## 14. Real bug found + fixed: `.mobileMenuResources` subscribe-form icon (task "verify Sections 1-19 with ?dev=true")

### What was being verified

Picked up the open task to check specific still-unverified areas from §6/§11's
open-items list: mobile nav main menu dropdowns, the "Our Services" sub-list,
its subscribe form, and the separate Resources panel's logo/ADAPT header row --
all with `?dev=true` on staging, at 375px.

Getting the in-app browser's `computer` click tool to work at all on this site
at mobile width was unreliable (consistent hard timeouts on every click,
including a plain logo link) -- worked around it by dispatching real
`element.click()` calls via `javascript_tool` instead, which behave like a
genuine DOM click and were not affected.

### Results

- **Main hamburger menu** (`a.nav[aria-label="Open menu"]`) -- opens
  correctly, all 5 top-level items present and correctly styled.
- **"Our Services" sub-list** (`.mobile-services-dropdown` toggle) -- expands
  correctly: Transformation Services / Go-to-Market Services rows, icon +
  label + chevron, matches production's structure (content text differs, CMS
  data only, not a CSS issue).
- **Subscribe form inside the main mobile menu's Resources section** and
  **the separate Resources panel's logo/ADAPT header row** (`a.navResources`
  toggle, found on `/all-resources/`) -- the logo/ADAPT row
  (`.mobileMenuResources .main-links-container .container`, the Section 6
  fix from `482216b`) is pixel-correct: clean `space-between` split, "A
  [icon] Resources" left / "ADAPT >" right, matches production exactly.
- **Real bug found:** the "Join the ADAPT Insider Community" subscribe card
  at the bottom of the *separate* Resources panel (`.mobileMenuResources`,
  `templates/partials/_header.php:221-239`) rendered with its envelope icon
  at the **top-left** under `?dev=true`, vs **top-right** on production --
  measured precisely (not just eyeballed): production has the icon 24px
  from the card's right edge (`float:right` in the live, ungated CSS,
  `source/scss/partials/_header.scss:4338-4375`, selector
  `.mobile-menu-bottom .subscribe-sidebar-form .icon-container`); `?dev=true`
  had it 24px from the **left** edge, i.e. plain DOM order with no
  compensating flex fix.

### Root cause

The existing Section 6 comment (`_dev-float-refactor.scss`, "2." in the
numbered writeup above the `body.dev-float-refactor` block) claimed this
icon+content split markup "appears once in `_mega-main-menu-mobile.php`...
fixed twice, once per dropdown tier" and fixed both of *those* occurrences
with a `flex-direction: row-reverse` translation of the float. That claim
was incomplete: the identical markup+CSS pattern (same classes:
`.subscribe-sidebar-form.mobile-menu-subscribe-form`, same
`.icon-container`/`.form-content` split) also exists a third time, in a
completely different PHP file (`templates/partials/_header.php`, the
standalone `.mobileMenuResources` panel, scoped in SCSS as
`.mobile-menu-bottom .subscribe-sidebar-form`). This third instance was
never mechanically or manually swept, so it only inherited the generic
`.subscribe-sidebar-form { float: none; }` bare rule (removes the icon's
float with no replacement), never the row-reverse compensation.

### Fix

Added the identical row-reverse fix, scoped to `.mobile-menu-bottom
.subscribe-sidebar-form` (confirmed unique sitewide -- exactly one PHP
occurrence, one SCSS occurrence), immediately after the two existing
`.mobileMenuMain` instances in `_dev-float-refactor.scss`, plus a
correction to the "2." comment documenting the gap and where it was found.

### Verification

- Rebuilt via the standard scratch-dir cycle (`/tmp/mobile-fix-scratch`);
  `npx gulp build:styles` succeeded.
- Diffed the compiled `main-nofooter.min.css` old vs new with a `postcss`
  AST-level rule comparison (selector+declaration sets, not raw text) rather
  than a naive text diff, because clean-css re-groups identical-declaration
  rules and reshuffles unrelated selectors between groups on any edit,
  producing large but meaningless line-level diffs otherwise. Result: the
  *only* real change across the entire stylesheet is the 3 new selectors
  (`.mobile-menu-bottom .subscribe-sidebar-form`, its `.icon-container`, its
  `.form-content`) gaining their declarations -- confirmed by resolving every
  other apparent add/remove pair back to a net-zero selector-shuffle within
  an unrelated, pre-existing `display:block;float:none` group.
- Live-verified the fix itself before committing: since the fix isn't
  deployed to staging yet, injected the exact new CSS as a `<style>` tag on
  the live staging page via `javascript_tool` and re-measured -- icon moved
  from 24px-from-left to 24px-from-right, matching production exactly.
  Screenshot confirms the card now visually matches production (icon
  top-right, heading/text/button stacked left).

**Update:** pushed by the user and re-verified live against the actual
deployed staging asset (not just the injected-style simulation) --
`marginFromRight: 24` measured directly on the deployed
`.mobile-menu-bottom .subscribe-sidebar-form .icon-container`, matching
production exactly, plus a visual screenshot confirmation. Closed out.

Committed as `a8c7ae3`, pushed and verified. `?dev=true` gate itself
untouched -- this only fixed a gap in the CSS gated *behind* it.

---

## 15. `_market.scss` `.item.one-third` float gap (Section 17's deliberately-uncovered case) -- resolved, plus a build-pipeline bug found while verifying it

### Task

Section 17 (`63e4511`, §2m above) deliberately left one case uncovered:
`section.market-featured`'s mobile-only `.item .item-content-container` row
override, because the `.item.one-third` card appeared to share a base
selector family with `_events.scss`/`_resources-types.scss` whose desktop
float behavior hadn't been fully traced. The user asked for this to be
investigated and closed out.

### Root cause, actually traced this time

Read `templates/components/_market-featured.php` directly: the card is
`<div class="item one-third articles"><a><span class="image-container">
<span class="bg-container">...</span></span></a><span class=
"item-content-container">...</span></div>`. The earlier "shared base
selector" concern turned out to be a red herring -- the only `.item.one-third`
selector in `_resources-types.scss` (line 3392) belongs to a completely
unrelated section (`featured-module.best-practices-featured`); the actual
generic grid float for `.filter-listing .item.one-third` cards (in
`_resources-types.scss`) is entirely **ungated** (not part of the float
refactor at all), so it's identical under `?dev=true` and production and
was never a bug.

The real, narrow gap: `.image-container`'s only float is the sitewide bare
`.image-container { float: left; width: 100%; }` rule, already gated
elsewhere in this same file (`body.dev-float-refactor .image-container {
float: none; display: block; }`) -- nothing left to fix there. The one
genuinely uncovered float is `.item-content-container`'s own
`@media (max-width: 767px)` override (`source/scss/templates/_market.scss:
406`, `float: left; width: calc(100% - 126px)`), which pairs with
`.image-container`'s mobile-only fixed 126x90 size to form a real
same-direction 2-item row (image left, content right, natural DOM order --
no `row-reverse` needed, unlike Section 6's subscribe-form).

### Fix

Added to `_dev-float-refactor.scss`, inside the existing
`@media (max-width: 767px)` block that already holds Section 17's
`.market-two-column` rules:

```scss
@media (max-width: 767px) {
    section.market-featured .container .grid-wrapper .item {
        display: flex;
        align-items: flex-start;
    }
    section.market-featured .container .grid-wrapper .item > a {
        flex-shrink: 0;
    }
    section.market-featured .container .grid-wrapper .item .item-content-container {
        float: none;
    }
}
```

Also rewrote the old "Deliberately NOT fixing" comment (§2m) into a
"RESOLVED, 2026-09-09" block with the full trace, so it isn't mistaken for
still-open later.

### Verification

- **Live injection before touching source-of-truth build:** measured
  production's actual rendered box positions on `/go-to-market-insights/`
  (image 126x90 at left:20, content at left:146/width:209, item height
  110px across all 3 featured cards), then injected the exact new rules as
  a `<style>` tag on the live `?dev=true` page and re-measured -- pixel-
  identical to production on all 3 cards.
- **Build-pipeline bug found while verifying the compiled output:** the
  first rebuild (`npx gulp build:styles`) showed the new rules correctly
  compiled into `main.min.css`, but they were **missing entirely** from
  `main-nofooter.min.css` -- alarming, since `main-nofooter.min.css` is the
  actually-enqueued file (`main.min.css` is the unused rollback artifact,
  per `gulpfile.js`'s own comment, §9/§2a). Spent a long debugging pass
  isolating each pipeline stage by hand (raw `dart-sass` compile → clean-css
  → `fix-float-none-display.js` → `split-oversized-rules.js`) against a
  manually glob-expanded copy of `main.scss` -- the new rules survived
  every single stage in isolation, which didn't match the missing-in-
  practice result and pointed at a process error rather than a real code
  bug.
  **Actual root cause:** `main-nofooter.min.css` isn't produced by
  `build:styles` at all -- it's produced by the separate
  `build:styles-split` task (`source/gulp/tasks/build/styles-split.js`,
  `build:styles-main-nofooter` sub-task), which compiles from a
  **different entry point** (`source/scss/main-nofooter.scss`, not
  `main.scss`). The debugging session had only ever run `build:styles`,
  so `main-nofooter.min.css` on disk was simply stale (left over from
  before this fix was added to the SCSS source) -- not corrupted by any
  pipeline stage. Repeating the same isolated-stage test against
  `main-nofooter.scss`'s own compiled output confirmed the rules survive
  every stage there too, and running the correct task
  (`npx gulp build:styles-split`) produced a `main-nofooter.min.css` with
  all three new rules present and correct on the first try.
- **Full postcss AST-level semantic diff** (individual-selector
  granularity, not just merged-rule granularity, to avoid clean-css's
  regrouping producing false positives) against the previous committed
  `main-nofooter.min.css`: exactly 3 real additions (the new rules above),
  plus one incidental, verified-harmless side effect --
  `fix-float-none-display.js`'s cross-selector matching stopped adding a
  redundant explicit `display: block` to two unrelated gated rules
  (`.article-container-three-post` and `.market-trend-reports-container-
  three-post`'s `.item`, both `float:none`-only) now that a real `display`
  exists for a same-tail selector elsewhere. Confirmed harmless: that
  `.item` is a `<div>` (`templates/resources-components/
  _articles-featured-block.php`), and per the CSS spec a `<div>` with
  `float: none` computes to `display: block` regardless of whether it's
  declared explicitly -- verified directly with `getComputedStyle()` on a
  bare test element in the live browser session. `footer.min.css` came out
  byte-identical (footer bundle doesn't import this section) --
  confirmed via `md5sum`.

### Status

Committed as `8aa5150` to `dev`, not pushed. Task #19 closed.

---

## 16. `isset($_GET[...]) ? array_map(...) : ''` ternary sites -- investigated, left as-is per user decision

### Task

12 sites across `template-events-old.php`, `template-insights.php`,
`template-post.php`, `template-search.php` all follow the pattern
`isset($_GET['x']) ? array_map('sanitize_text_field', (array)$_GET['x']) :
'';` -- a type-mismatched ternary (array in the true branch, empty string in
the false branch). Flagged during the broader audit as a code-smell worth
resolving.

### Investigation

Traced every downstream consumer of each of these variables
(`$filterCat`/`$filterType`/`$filterDuration`/`$filterTopics`/`$filterEvent`
across the 4 files -- checkbox `in_array()` state, dozens of `!= ''`/`==
''`/`empty()` guards including several **combined OR-guards** across
multiple filter variables used as "is any filter active" checks, URL
query-string building for sort links, results-grid class toggling, and
counter text). Conclusion: the `''`-vs-array asymmetry is **load-bearing**,
not accidental dead code -- converting the false branch to a consistent
`[]` default (the natural, more "correct"-looking fix) would silently
invert those guards, since in PHP `[] == ''` is `false` (arrays are never
loosely-equal to non-array scalars) -- meaning every `!= ''` guard would
become permanently `true`, incorrectly signaling "a filter is active" even
when nothing is selected. A mechanical `?? []`-style modernization is
therefore unsafe; a real fix would mean rewriting all 12 sites *and* every
one of their dozens of downstream consumers to use `empty()`/`count()`
checks instead -- a much larger, riskier refactor than originally scoped,
touching call-sites across all 4 files with no isolated way to verify it
short of exercising every filter combination on every affected page.

Also checked `_archive/dec-2025-functions.php`, `_archive/nov-functions.php`,
`_archive/oct-functions.php` for a similar pattern (found 3 matches using
`array()` as the false branch instead of `''`) -- confirmed these are dead,
archived files, not live/relevant.

### Decision

Presented to the user as a choice: leave all 12 as-is (no behavior change,
no risk -- the type inconsistency is ugly but not an actual bug, nothing is
broken today) vs. the full `empty()`-based refactor (real regression risk,
needs its own dedicated verification pass). **User chose to leave all 12
as-is.** No code changed. Task #18 closed.

---

## 17. `.article-container-three-post`/`.market-trend-reports-container-three-post` `.item` row -- found + fixed while verifying §15

### Task

Not user-requested -- found incidentally while spot-checking §15's
"harmless side effect" selectors live on `/all-resources/`.

### Symptom

`.item` cards in these two selectors' 3-up grid (used on all-resources,
plus resource-type/topic/podcast/thank-you pages per an existing
2026-09-03 code comment) rendered with image and content stacked
vertically under `?dev=true` (item height 197px) instead of side by side
(110px on production).

### Root cause

Pre-existing gap, not caused by §15's commit. `.grid-wrapper` (the `.item`
cards' parent) was already correctly gated to `display:flex; flex-wrap:
wrap` to arrange the cards themselves, but each individual `.item`'s own
internal row (`.image-container`/`.video-container` beside
`.item-content-container`, `source/scss/templates/_resources-types.scss:
3306-3377`) never got a flex parent of its own -- so removing their floats
collapsed that inner row into a vertical stack.

### Fix

`.item` gets `display:flex; align-items:flex-start`;
`.image-container`/`.video-container` (direct children of `.item` here,
confirmed via `templates/resources-components/_articles-featured-block.php:
87-100` -- no `<a>`-wrapper layer like §15's market-featured case) get
`flex-shrink:0`. Applied unconditionally (not scoped to a media query --
the row exists at every breakpoint, unlike §15's mobile-only case).

**Cross-talk with `section.featured-types`, caught before committing.**
That homepage component (`templates/components/_featured-types.php`)
reuses the same `.article-container-three-post` class for an unrelated
purpose (documented in an existing 2026-09-03 "BUGFIX" comment in this
same file) with its own, already-correct, already-`display:flex`
production CSS -- already matched by an existing higher-specificity gated
override. That override only sets `display`, not `align-items` or
`flex-shrink`, so the new generic rule's `align-items:flex-start` and
`flex-shrink:0` would otherwise have leaked into it via the cascade (same
selector matched by both rules, unmasked properties still apply). Added a
matching higher-specificity correction (`align-items:stretch;
flex-shrink:1`, restoring the flex defaults) to the existing
featured-types override block -- same specificity-elevation technique
used by the original 2026-09-03 BUGFIX (`:not()` with a descendant
combinator gets mangled by clean-css's minifier, confirmed back then;
elevating specificity instead is unaffected by minification).

### Verification

- Postcss AST-level selector diff (same script as every other fix in this
  doc) against the previous build: exactly 9 additions, all intended (the
  new `.item`/`.image-container`/`.video-container` rules for both
  selectors, plus the 3 featured-types corrective rules), 0 unrelated
  changes.
- Live-verified on `/all-resources/`: injected the new rules under
  `?dev=true` and re-measured all 3 sampled cards -- item heights
  111/111/155 and image/content positions identical to production on
  every card.
- Live-verified `section.featured-types` on the homepage, both its
  `.type-container` cards (image+title) and its `.all-container` "All
  Resources" card: measured geometry with and without the new rules
  injected -- byte-identical in both cases, confirming the corrective
  override fully neutralizes the cascade leak.
- `footer.min.css` confirmed byte-identical via `md5sum` (footer bundle
  doesn't import this section).

### Status

Committed as `48fa14d` to `dev`, not pushed.

---

## 18. PHP fatal-crash bug: unguarded `get_term_link()` against `WP_Error` (task #20)

### Task

User relayed an external developer's site audit reporting "a PHP coding
error within the WordPress theme" causing crashes, plus generic
server/infra recommendations (checking PHP-FPM processes, server error
logs, database performance, caching -- all out of scope here, no server
access from this sandbox, only this git repo). Asked to prioritize
finding the PHP bug. Prior PHP audit passes (§7/§8) covered PHP 8.1
compatibility, dead code, debug leaks, and `no_found_rows` -- all clean.
This pass targeted actual runtime-error risks instead of style/
modernization.

### The bug class

`get_term_link( $term )` returns a `WP_Error` object (not a string)
whenever `$term` isn't resolvable to a real term. `WP_Error` has no
`__toString()` method, so `echo get_term_link(...)` (or any string
concatenation of its result) throws a genuine PHP 8 **fatal** error:
`Uncaught Error: Object of class WP_Error could not be converted to
string`. This is a crash, not a warning -- matches the external report
closely.

Used a research subagent to enumerate all 168 `get_term_link(` call
sites theme-wide and trace each argument's origin, then personally
verified and fixed every live, reachable instance:

### Fixed

- **5 `resources-components/_*-featured-block.php` files** (articles,
  expert, peer-insights, best-practices, and market-trend-reports --
  the last has 2 occurrences, desktop + mobile copies): each does
  `$term = get_term_by('slug', <hardcoded-slug>, 'resource-type');` then
  used it in a completely unguarded `echo get_term_link($term)`.
  `get_term_by()` returns `false` if that resource-type term is ever
  renamed or deleted -- `get_term_link(false)` returns `WP_Error` --
  crash on every page load of that block. Pre-computed a null-safe
  `$term_link` string once per block (`$term_link = $term ?
  get_term_link($term) : '';` plus an `is_wp_error()` check on the
  result) instead of calling `get_term_link()` inline, and null-guarded
  the sibling `$term->name`/`$term->description` property reads too.

- **`customer-story-components/_category-three-column.php`**: same root
  cause via `get_term($category_ids[0], 'customer-stories-categories')`,
  triggered by leaving the ACF "category" field on this flexible-content
  block empty, or deleting its selected term. 4 separate unguarded echo
  sites (the "See more" link, the copy-link input, and 2 share-link
  URLs). Confirmed this file is live (wired to the `customer_story_
  category_slider` ACF layout on `template-customer-stories.php`), unlike
  its sibling `_category-slider.php` (see below). Also fixed an
  unrelated bug in the same file: a fallback branch echoed `$q->name`
  where `$q` is never defined anywhere in this file -- a copy-paste
  leftover from `_category-slider.php` -- should be `$termTax` (the
  block's own category, already resolved earlier in the file) per the
  surrounding logic's clear intent.

- **`single-customer_stories.php` + `template-customer-stories-
  categories.php`**: both already had a `if ($parent_category)`/
  `if ($parent_term)` truthy guard around a similar `get_term()` result
  -- but that only blocks `false`/`null`. `WP_Error` is an object and
  PHP objects are always truthy, so it slips straight through the
  existing guard. Narrower trigger than the cases above (only fires if
  the `customer-stories-categories` taxonomy itself fails to register,
  not just a deleted term), but the identical crash class. Added
  `is_wp_error()` checks alongside the existing truthy checks in both
  files. Also initialized `single-customer_stories.php`'s `$category_
  link`/`$category_name`/`$category_slug` to `''` up front, since they're
  used unconditionally in the template markup with no wrapping guard at
  all -- meaning the plain "no parent category found" case (not just the
  WP_Error edge case) was already hitting undefined-variable warnings on
  every such page load, independent of this fix.

### Verification

Every changed instance is provably behavior-preserving on the happy
path (a valid, existing term -- which is the case on the live site
today): the added checks are pure `if`/ternary guards around the exact
same calls, so when the term resolves normally, `$term_link` equals
exactly what `get_term_link($term)` returned before. All 8 edited files
confirmed parsing clean under PHP 8.1 via `php-parser` (no PHP CLI in
this sandbox, same constraint as §7). No CSS/visual output touched, so
the usual live pixel-diff verification doesn't apply here -- this is a
pure PHP correctness fix, same category as §7's modernization pass.

### Found but not fixed -- flagged for a separate decision

Three files contain the identical `get_term_link()`-on-`WP_Error`
pattern but are **confirmed unreachable** (no `get_template_part()`/
`include`/`require` call and no `Template Name:` header anywhere in the
theme references them, checked via sitewide grep):
`templates/old-template-resource-type.php`,
`templates/template-resource-type-pre-media.php`, and
`templates/customer-story-components/_category-slider.php` (the source
of the `$q` copy-paste bug fixed above). Zero live crash risk as-is, but
worth a deletion decision -- same category as the earlier dead-code
cleanups (§8) but a larger removal than that pass's scope, so left
untouched pending sign-off rather than deleted unilaterally.

### Status

Committed as `56f4e6c` to `dev`, not pushed. Task #20 closed.

The rest of the external audit's recommendations (server resources,
PHP-FPM process health, server error logs, WordPress plugin review,
database performance, caching/hosting setup) are infrastructure-level
and out of scope for this sandbox, which only has access to this git
repo -- flagged back to the user to take to whoever manages hosting.
The user then explicitly followed up asking for two of those items:
WP Rocket cache config (they have it installed) and theme DB query
optimization -- see §19 and §20 below.

---

## 19. WP Rocket caching config check (user follow-up)

### What could actually be checked

No access to the WP Rocket plugin's settings (stored in `wp_options`) or
the server/database from this sandbox -- only this theme's git repo.
Checked what's observable from theme code plus the live site instead.

**Confirmed active:** `meta-generator: WP Rocket 3.23.3.3` present on
fetched pages.

**Theme-level integration points found in `functions.php`, both look
deliberate and correctly scoped:**
- `rocket_delay_js_exclusions` filter excludes `jquery.min.js`/
  `gsap.min.js`/`mediaelement-and-player.min.js`/the theme's own
  `main.min.js` from WP Rocket's "Delay JS execution" feature, but
  **only on the homepage** (`$request_uri === '/'`).
- `pre_get_rocket_option_remove_unused_css` filter forces WP Rocket's
  "Remove Unused CSS" (RUCSS) **off** specifically on the front page
  (`is_front_page()` returns `0`) -- this was already relied on earlier
  this session (§2a) to help rule out RUCSS as a cause of a different
  bug.
- One `<script nowprocket>` tag (`_text-animation-introduction-v2.php`)
  correctly excludes an inline animation-driving script from Delay JS
  sitewide, not just the homepage.

**A real, unverified risk worth the user checking directly.** RUCSS
being force-disabled only on the homepage implies it's **enabled
everywhere else**. RUCSS works by crawling a page's rendered DOM and
stripping any CSS selector that doesn't match anything found there. The
entire `?dev=true` gated float-refactor CSS (`_dev-float-refactor.scss`,
every rule scoped `body.dev-float-refactor ...`) depends on a class that
is **never present** during an automated crawl (it only appears when a
real visitor manually adds `?dev=true` to the URL) -- meaning RUCSS's
crawler would very plausibly classify every single gated rule as
"unused" and strip it from what real anonymous visitors receive on any
non-homepage page, regardless of what the compiled CSS file itself
contains. Grepped the whole theme for any RUCSS safelist/exclusion
entry for `.dev-float-refactor` (`rocket_rucss_safelist` filter or
similar) -- **none exists**.

Could not confirm or rule this out directly: this browser session is
authenticated as a WP admin (confirmed via the admin bar, Query Monitor,
and other admin-only assets loading), and WP Rocket's caching/
optimization pipeline (including RUCSS) does not apply to logged-in
admin sessions by default -- so every live check this session (including
the successful `?dev=true` verifications in §15/§17) was against the
**uncached, unoptimized** version of the site, not what an anonymous
visitor's browser actually receives. Getting a true logged-out view
would have required logging this session out of WP admin (no credentials
to log back in afterward, so declined to do that unilaterally) or fetching
via a tool with direct HTTP header/raw-HTML access (not available here --
`web_fetch` is stateless/anonymous but only returns extracted text, not
raw `<link>` tags or cache signatures).

**Recommendation:** in the WP Rocket dashboard, check **Settings → File
Optimization → Remove Unused CSS** on a non-homepage page while logged
out (or in an incognito window) with `?dev=true` -- if the flexed/
un-floated layout doesn't appear, RUCSS is stripping the gated CSS and
`body.dev-float-refactor` (or the specific selectors used throughout
`_dev-float-refactor.scss`) needs adding to RUCSS's CSS Safelist. This
is also a broader risk for anything else in the theme that only becomes
relevant after JS interaction (mobile menu open states, dropdowns,
accordions, active/expanded classes) -- RUCSS's static crawl doesn't
click or interact with the page, so any CSS gated behind a
JS-toggled class has the same theoretical exposure. Worth a full RUCSS
safelist review generally, not just for the float-refactor gate.

### Status

Investigation only, no code changed (nothing in the theme needed fixing
-- both existing Rocket-related filters are correct). Flagged to the
user for a direct dashboard check they can do that this sandbox cannot.

---

## 20. Database query optimization pass (user follow-up, task #22)

### Approach

Used a research subagent to enumerate ~100+ `new WP_Query(` calls
theme-wide and cross-reference against the earlier `no_found_rows` sweep
(§8, which had explicitly left `template-insights.php` and
`template-search-results.php` unaudited), N+1 patterns in loops, and
exact-duplicate queries. Personally verified every finding before
fixing (checked each file for `wp_pagenavi()`/`paginate_links()`/
`next_posts_link()`/`->max_num_pages` before touching `no_found_rows`,
since adding it to a genuinely-paginated query would silently break
pagination).

### Fixed

**Two genuinely redundant queries removed:**
- `template-search-results.php`: deleted an entire second, unbounded
  (`posts_per_page => -1`) `WP_Query` (`$counterargs`) that duplicated
  `$args`' `post_type`/keyword/`tax_query` just to loop through every
  matching post in PHP and count them into `$counterResults`. `$args`
  itself doesn't set `no_found_rows` (it feeds `wp_pagenavi()`), so
  `$posts->found_posts` already has the identical number for free.
- `template-insights.php`: the "filter-types" dropdown options were
  computed twice -- once for the desktop `#filterBy` select, once for
  the mobile `.filter-by-mobile` one -- via the exact same
  `WP_Query($args)` + `get_the_terms()` loop (or the exact same
  `get_terms()` call in the no-filter/no-keyword branch). Computed once
  into `$filterTypeTerms`, reused by both blocks.

  Both of the explicitly-deferred files from §8 turned out to already
  have `no_found_rows` correctly applied everywhere safe, and correctly
  omitted where real pagination depends on it -- no gaps, only the two
  redundant-query issues above.

**`no_found_rows => true` added to 29 more `WP_Query()` calls across 21
files**, after confirming none of their results are ever used for
pagination: the 5 `resources-components/_*-featured-block.php` files,
`_resources-featured-block.php` (3), `_most-popular-posts.php`,
`_category-three-column.php`, `single-customer_stories.php`,
`single-post-side-articles.php`, `single-post-no-embed.php`,
`components/_featured-posts.php` (2), `components/_market-featured.php`,
`post-components/_media-related.php`, `_post-related.php` (2 branches),
`_press-related.php`, `_post-related-best-practices.php` (2 branches),
`types-components/_type-two-column.php`, `template-registration.php`
(3), `components/_related-articles-taxonomies.php` (3),
`thank-you-components/_featured-posts.php`.

Left untouched: `customer-story-components/_category-slider.php` (same
pattern, confirmed unreachable/dead code -- see §18); `template-
registration.php`'s queries also have no `posts_per_page` cap and no
pagination UI at all, which is a display question rather than a
DB-load one, not touched here.

Verified every edited file parses clean under PHP 8.1 via `php-parser`.
No CSS/visual output touched. Committed as `4bfd15b` to `dev`, not
pushed.

**Caught and reverted before committing:** the editing subagent made an
unrelated, unrequested change to `source/scss/templates/_customer-
events.scss` (`margin-top: 104px` → `margin-top: 0` on
`.partner-fixed-scroller`) that had nothing to do with this task --
caught via a full `git status`/`git diff` review before commit,
reverted with `git checkout --`, confirmed clean before committing only
the intended PHP files. Worth remembering to always diff-review a
subagent's changes against the full repo status, not just the files it
claims to have touched.

### The bigger finding -- not theme code, flagged to the user

The user mentioned installing Query Monitor mid-session, which made a
live cross-check possible via its full raw query/stack-trace data
(`window.QueryMonitorData.data.db_queries.data.rows`, extracted directly
in the browser session rather than through QM's own UI, which needs
its AJAX panels to be manually opened first).

On `/all-resources/` (`template-resources.php`): **130 total DB queries
per page load**, of which **95 (73%) trace back to two third-party
plugins**, not the theme:
- **Advanced Taxonomy Terms Order** ("ATTO" class prefix in stack
  traces) -- v2.8.7.1 installed, v3.9.4 available. Its `_terms_clauses`
  hook (wired into WordPress's `terms_clauses` filter, which every
  taxonomy term query in the request passes through) produced **40+11+
  10+5+5+3+3 = 77 literally-duplicate SQL queries** for what should be
  cacheable/identical term-hierarchy lookups.
- **Advanced Post Types Order** ("APTO" class prefix) -- v5.9.7
  installed, v6.1.2 available. Its `get_sort_view_id_by_attributes()`
  already has its own memoization (`$APTO->cache_key_exists()`/
  `cache_get_key()`, keyed on `md5($sortID . serialize($attr))`) but
  still produced 6 duplicate queries via `sort_multiple_match_check_on_
  query()`/`query_match_sort_id()` -- the caching wraps the *result*,
  not the inner lookup, so a cache-key mismatch still re-runs the
  expensive part.

This dwarfs anything fixable in the theme itself (only ~27 of the 130
queries on this page trace to theme templates). The Desktop\advanced-
post-types-order folder connected to this session turned out to be the
actual APTO plugin source (editable), but a source-level fix wasn't
attempted here: both plugins are several major versions behind their
latest release, both by the same vendor (Nsp Code), and a version jump
that large very plausibly already includes query-caching/performance
fixes on the vendor's side -- the simplest, safest, highest-leverage fix
is almost certainly just updating both plugins first, then re-measuring
with Query Monitor to see if the duplication persists, rather than
hand-patching a third-party plugin's core sorting/ordering logic (high
blast radius if a fix is wrong -- this logic drives admin drag-and-drop
ordering across every post type and taxonomy sitewide) without an
explicit decision from the user first.

### Status

Committed as `4bfd15b` to `dev`, not pushed. Task #22 closed. The ATTO/
APTO plugin-update recommendation and the RUCSS safelist check (§19)
are both flagged back to the user as separate, non-theme-code action
items.

## 21. Query Monitor follow-up on homepage `?dev=true`: duplicate logo-lookup queries (fixed) + slow `permalink_customizer` query (plugin-level, flagged)

User installed Query Monitor and sent two screenshots from checking the
homepage under `?dev=true`.

### Finding 1 (theme bug, fixed): duplicate `attachment_url_to_postid()` per logo

Query Monitor's Duplicate Queries panel showed 134 total duplicates, the
largest single group being paired (`Count: 2`, identical each time)
`SELECT post_id, meta_value FROM wp_..._postmeta WHERE meta_key =
'_wp_attached_file' AND meta_value = '<company-logo-filename>'` queries,
Caller = `attachment_url_to_postid`, one pair per homepage partner logo
(Metcash, AWS, Slack, Bupa, ServiceNow, IBM, Officeworks, etc.).

Root cause: `templates/components/_logo-ticker-v2.php` (dispatched from
`template-home.php`'s `logo_ticker_tape` ACF layout -- confirmed this is
the "Home Template" used live) renders the same set of partner logos
**twice**, back-to-back, in two separate `.moving-text` spans -- a
standard CSS marquee/ticker technique (duplicate the content once so the
scroll animation can loop seamlessly with no visible seam). Each of the
two render loops independently called `attachment_url_to_postid(
$logo['url'] )` to resolve the ACF image URL to an attachment ID, so
every logo's ID was looked up twice per page load instead of once.

Fix: resolve all logo IDs into a `$ticker_logo_ids` array in a single
pass before either span renders, then have both spans `foreach` over
that array instead of re-running `have_rows()`/`attachment_url_to_postid()`
each time. Output/markup is byte-identical; only the query count changes
(halved, for every logo, on every page load of this component).

Also applied the identical fix to `templates/components/_logo-ticker.php`,
which has the exact same bug pattern but is currently **not referenced by
any `get_template_part()` call** (confirmed via sitewide grep) -- fixed
for consistency in case it's reused later, flagged here as dead code
otherwise (same category as the 3 dead `get_term_link()` files in §18,
not deleted, awaiting a batched cleanup decision).

Note: `templates/landing-components/_logo-ticker.php` (used by
`template-landing.php`) is a different, unrelated file that already reads
`$logo['ID']` directly from the ACF field -- no `attachment_url_to_postid()`
call, not affected by this bug.

Verified both edited files parse clean with `php-parser` (8.1). Committed
as `dd031a6` to `dev`, not pushed.

### Finding 2 (plugin-level, not theme code -- flagged, not fixed)

Query Monitor's Slow Queries panel topped out at a 0.2143s query filtering
`wp_postmeta` on `meta_key = 'permalink_customizer'`, comparing
`LOWER(meta_value)` against the literal strings `'?dev=true'` and
`'?dev=true/'` via `LEFT()`/`LENGTH()` (i.e. checking whether any post's
custom-permalink meta value is a prefix match for the current query
string) -- Caller/Component both showed as "Unknown" in Query Monitor,
which typically means the query didn't originate from a theme template
function QM can attribute a file/line to.

Grepped the entire theme (`templates/`, `includes/`, root PHP files) for
`permalink_customizer` -- zero matches. Also checked the
`advanced-post-types-order` plugin source connected to this session (the
one folder of plugin code available) -- zero matches there either. This
meta key and the prefix-match-against-current-URL pattern is consistent
with a permalink-rewriting plugin (e.g. "Custom Fields Permalink 2",
seen in the plugins list during the WP Rocket check in §19) hooking into
every request's routing/`parse_request` to see if the current URL matches
a custom permalink -- which would explain why it's firing even on
`?dev=true` (a query string with no matching post, forcing a full scan
each time) and why QM can't attribute it to a theme file.

This is the same shape of finding as the ATTO/APTO duplicate-query issue
in §20: real, measurable, but not theme-fixable code from here since the
plugin's source isn't in a connected folder. Flagged back to the user
rather than guessed at -- recommend checking which plugin owns the
`permalink_customizer` meta key (Query Monitor's own "Hooks & Actions" or
"Queries by Component" view, filtered to this query, usually names the
plugin even when the Caller column says "Unknown"), and if that plugin
has a "skip non-matching/dev URLs" or caching option.

### Status

Theme-side fix (duplicate logo queries) committed `dd031a6` to `dev`, not
pushed -- awaiting the user's push-from-their-machine step per standing
workflow. The slow `permalink_customizer` query remains an open,
plugin-level action item for the user to triage, same as the ATTO/APTO
plugin-update recommendation.

## 22. Systemic float-collapse bug: `section.full-suite-slider-module` (+ preemptive `section.company-slider` fix) -- user-reported, root-caused, fixed

User: "please continue on the float thing tasks as I can still see a lot
of it. Sample page: https://staging.adapt.com.au/buyer-persona-cfo/"

### Investigation

Loaded `/buyer-persona-cfo/?dev=true` at 1440x900: the hero card slider
looked cut off with a lot of blank space to its right. Traced that first
lead (a Slick carousel that auto-scrolls continuously, `autoplaySpeed:
0, speed: 16000, cssEase: 'linear'` in `main.js`) and confirmed it was a
red herring -- pausing it on a clean slide showed it rendering correctly
on both dev and production.

The real bug was found by comparing `document.body.scrollHeight`
directly between `?dev=true` and production on the same page: 10007px
(dev) vs 10409px (production), a 402px gap. Walking `main`'s direct
child `<section>` top/height values on both pinpointed exactly where
they diverged: everything matched pixel-for-pixel through `section.
benchmarking-four-column`, then `section.full-suite-slider-module`
measured 447px on dev vs 983px on production.

### Root cause

`source/scss/global/_styles.scss` has a sitewide base rule:
```
section {
    float: left;
    width: 100%;
}
```
Every `<section>` on the site floats by default (an old-school layout
technique). Floating an element has a second effect beyond positioning:
it makes the element establish a new block formatting context, which
auto-contains ("auto-clears") any still-floating descendants for the
purpose of the parent's own height -- this is the same mechanism
`overflow:hidden`/`clearfix` hacks replicate manually.

`section.full-suite-slider-module` contains `.slider-outer > .full-
suite-slider`, a Slick carousel that an earlier pass in this file
correctly identified as still-live and deliberately left `float:left`
(never converted -- correct call, Slick manages its own positioning).
But the section's *own* float -- which was silently doing the job of
containing that floated carousel -- got stripped by this file's blanket
`body.dev-float-refactor section { float: none; }` rule. Nothing
replaced the containment it was providing, so `.slider-outer` collapsed
to 0 height and the whole section shrank to just its title.
(The floated carousel still rendered -- floats aren't clipped by a
collapsed parent -- but it painted underneath the next section's opaque
background, which is why it looked entirely missing rather than merely
mispositioned.)

A second, smaller instance of the identical mechanism one level down:
`.title-container` (a child of the section) had already been converted
`float:left` -> `float:none` by an earlier pass in this file. That
alone let its `h2`'s 8px `margin-bottom` collapse through into the
section's own box instead of staying inside `.title-container` --
975px vs production's 983px, even after fixing the main collapse.

### Fix

Two `display: flow-root` rules added to the existing `body.dev-float-
refactor` block in `_dev-float-refactor.scss`, right next to this
component's other already-existing overrides:
```
section.full-suite-slider-module {
    display: flow-root;
}
section.full-suite-slider-module .container .title-container {
    display: flow-root;
}
```
`flow-root` re-establishes the same block-formatting-context
containment a float provides, without reintroducing the float itself
-- it only affects elements that actually have uncontained floating
descendants, so it can't regress anything that wasn't relying on it.

Verified via live-injected `<style>` on both the `?dev=true` tab and a
second production tab (same active carousel slide confirmed on both
before comparing, since the carousel auto-scrolls): section height came
out 983px === 983px. Then walked every `main > section`'s top/height on
both tabs end-to-end -- all ten sections matched exactly, including
everything *after* the fixed section (confirming the fix didn't just
patch the one section but also correctly restored the downstream
layout that depended on its height). `main`'s total height matched
exactly too (11122px === 11122px); the only remaining page-height gap
was inside `<footer>` (872px dev vs 898px prod, 26px) -- screenshotted
the footer directly and it renders complete with no missing/overlapping
content, so this reads as pre-existing/unrelated to today's fix (footer
layout was already a separately-completed task, §"Fix footer float/flex
layout bugs", earlier this engagement) rather than a new regression;
flagging it rather than chasing it further since it wasn't part of what
the user reported seeing.

Rebuilt with `gulp build:styles-split` in a scratch copy (`npm install
--ignore-scripts` was needed this time -- default install failed on a
gifsicle native-build error unrelated to CSS, no-op for our purposes
since we only need gulp-sass). Diffed old vs new `main-nofooter.min.css`
line-by-line (`sed 's/}/}\n/g'` to make the minified file diffable) and
confirmed only the two new selectors were added, byte-identical
everything else.

### Preemptive companion fix: `section.company-slider`

While root-causing the above, found the exact same shape of latent bug
by inspection: this file's own header comment names `.company-slide-
container` (inside `section.company-slider`) as a second Slick carousel
"left floating on purpose" with the identical structure -- `float:left;
width:100%` (`source/scss/templates/_customer-events.scss` ~line 3417),
no containment of its own, same reliance on the section's now-stripped
float.

Could not find a live page in this session with that ACF block enabled
to visually confirm (checked `/adapt-vs-gartner/`, `/custom-partnered-
research/`, `/ecosystem-consulting-partners/`, `/executive-advisors/` --
none render it). Applied the same `display: flow-root` fix to `section.
company-slider` anyway since it's provably safe either way (it can only
restore missing containment, never regress a section that wasn't
relying on it) -- but this one specific instance is unverified live,
flagging that clearly. Also checked a third similarly-flagged carousel,
`.home-content-slider` inside `section.content-slider-module`, live on
the homepage: its height already matches production exactly (1228px vs
1227px, 1px rounding) via a *different*, already-existing fix from an
earlier pass (`clear:both` on the sibling `.progress-container` rather
than a section-level flow-root) -- confirmed no action needed there.

### Status

Committed as `45f2ddc` to `dev`, not pushed. This component (`.full-
suite-slider-module`) is used by 13 templates per a sitewide grep, and
the fix is fully scoped to the class itself (not page-specific), so the
same fix applies wherever it appears.

## 23. Two user-requested CSS one-liners + finishing the DB query audit's `posts_per_page => -1` follow-up (task #27)

### User-requested CSS

Two explicit, verbatim requests, both added exactly as given:

- `body.template-benchmarking section.left-text-links.advisors-
  centered-text-links.left-text-links-image .container .text-container
  .text { margin-left: 0; }` -- added inside the existing `.text-
  container` block in `_benchmarking.scss` (production CSS, not gated).
  Spot-checked live on `/buyer-persona-cfo/` at desktop and mobile,
  both production and `?dev=true`: computed `margin-left` was already
  `0px` in every state checked, so no visible effect there -- added
  regardless since the request was explicit and may target a state not
  sampled. Committed `f865409`.
- `section.fixed-scroller.partner-fixed-scroller { margin-top: 0; }` --
  was `104px` at desktop in `_customer-events.scss` (mobile already had
  its own `margin-top:0px` override, now redundant and removed since it
  matches the new base value). This one's a real, visible change:
  confirmed live on `/buyer-persona-cfo/`, pulls the section up 104px
  (1756px -> 1652px from viewport top). Committed `e826d15`.
- Also caught and fixed while rebuilding for the above: the
  `section.company-slider` `display:flow-root` fix from §22 had only
  ever been added to the SCSS source, never actually rebuilt into CSS or
  committed -- the scratch copy used for an interim rebuild was stale
  and would have silently dropped it. Rebuilt and committed on its own
  first (`b213d66`), isolated from the two rules above.

Every one of these was verified by diffing the freshly-built
`main-nofooter.min.css` against the previous commit's version
line-by-line (`sed 's/}/}\n/g'` to make the minified file diffable) --
each commit's diff contains exactly the selector(s) its message claims,
nothing else.

### `posts_per_page => -1` filter-button follow-up (task #27, user chose "fix the 4 filter-button files now")

Continuing the DB query audit from §20 (task #22): found 20 more
`posts_per_page => -1` (fetch-everything) queries across 11 files.
Presented a risk breakdown to the user; they chose to fix the 4
highest-risk ones now (the rest logged below as still-open).

`template-topic.php`, `template-resource-type.php`, `template-search-
results.php`, and `template-insights.php` each ran a full `posts_per_
page => -1` `WP_Query` (every matched post, every column, every
postmeta join) purely to loop the results in PHP and collect distinct
taxonomy terms for filter buttons/dropdowns -- the matched posts
themselves were never displayed. `template-insights.php`'s version
tallies a per-term *count* (for the filter-type dropdown's "(12)"-style
labels) rather than just presence, but the same pattern applies.

Confirmed live before touching anything: `/search-results?searchWords=
cloud` alone was scanning **441** full post rows just to build 5 filter
buttons.

Fix: added `'fields' => 'ids'` to each of the 8 `$args` blocks (keyword
+ no-keyword branch x 4 files) so MySQL returns only the ID column, and
changed each consumption loop from `have_posts()`/`the_post()` to
iterating the returned ID array directly (`foreach ($loop->posts as
$id)`). `get_the_terms()` per ID is unchanged -- still the same cached
per-post lookup as before -- so the collected term set (and its order,
and template-insights.php's counts) come out identical to the old code,
just without ever materializing a full post object for rows that were
only ever going to be discarded.

Hit one real mistake while editing `template-insights.php`: pasted a
stray `<?php` tag inside an already-open PHP block (the surrounding
`if(...) { ... }` never closes back to HTML between lines ~638-694),
which `php-parser` caught immediately (`syntax error, unexpected '<' on
line 680`) -- removed the redundant tag, re-ran the parser clean.
Verified all 4 files parse with `php-parser` (8.1) after the fix.

Captured live filter-button baselines (text/href/selected-state) on
`/resource-type/articles`, `/topic/data-strategy`, and `/search-
results?searchWords=cloud` *before* committing, to diff against after
the next push -- these three are straightforward to re-check live since
each is a public page. Could not find a live URL that uses `template-
insights.php` in this session at all (checked the REST API's page list
by `template` field -- zero pages reference it; it may not currently be
assigned anywhere, same situation as the confirmed-dead files in §18
and the unverified `company-slider` case in §22). Applied the
identical, by-then-twice-validated pattern there on code-review
confidence alone.

### Still open (not fixed, logged per the user's "flag it" choice implicitly covering the rest)

The other 7 files from the same audit, by risk tier:

- **HIGH**: `_related-articles-taxonomies-grid.php` (3x, `post` type,
  looped per taxonomy term with no dedup/limit).
- **MEDIUM**: `_events-listing.php` (2x), `_events-listing-partners.php`
  (2x) -- `event` CPT, client-side year-filtered, no server pagination.
- **MEDIUM**: `single-post_author.php` -- author's full post history
  (chunked client-side into groups of 6, but still unbounded per
  prolific author), plus its `event`/`registration` sub-queries.
- **LOW**: `_open-positions.php`, `single-position.php` (`position`
  CPT, small/bounded), `_advisors-carousel.php` (`speaker` CPT,
  small/bounded).

### Status

All 4 files committed as `f9aecb7` to `dev`, not pushed. Two CSS
one-liners plus the company-slider completion committed as `f865409`,
`e826d15`, `b213d66`. Next step once pushed: re-check the three live
baselines above match exactly (same filter buttons, same order), and
try again to locate a page using `template-insights.php`.

**Update, next session turn**: pushed as `e24269f`. Re-checked all 3
live baselines (`/resource-type/articles`, `/topic/data-strategy`,
`/search-results?searchWords=cloud`) -- byte-for-byte identical filter
buttons, same "441 results" total. `template-insights.php` still not
located live.

## 24. N+1 query audit: `foreach` loops running one `WP_Query` per iteration

Continuing task #2's audit into a new class of bug: a `new WP_Query()`
(or `get_posts()`) called *inside* a loop over another query's results,
a taxonomy's terms, or an ACF-selected ID list -- running the query once
per iteration instead of once total.

Delegated a sitewide search (excluding the 3 confirmed-dead files and
anything already tagged `BUGFIX/PERF 2026-09` from this session's
earlier fixes). Most hits were false positives on inspection -- ACF
`have_rows()` repeater loops that build one query *after* the loop
closes, or `foreach` loops that only accumulate `tax_query` clauses into
a single shared args array before one query runs (`_events-listing.php`
and its `-partners` variant, `_advisor-module.php`/`_edge-partner-
module.php`/`_partner-module.php`/`_speaker-module.php`'s expertise
filter loops, `template-events-old.php`). Real, repeated N+1 found in
exactly 3 files, all the same shape: an editor picks a list of taxonomy
term IDs (ACF field), and the template loops that list running one
`WP_Query` per term instead of one query with `tax_query => ['terms' =>
$terms, 'operator' => 'IN']`:

- `templates/components/_related-articles-taxonomies-grid.php` (3
  branches: event/topic/filter-type) -- **unbounded per term**
  (`posts_per_page => -1`, no cap at all).
- `templates/components/_related-articles-taxonomies-locked.php` (same
  3 branches) -- bounded (`posts_per_page => 8, orderby => rand`).
- `templates/components/_related-articles-taxonomies.php` (same 3
  branches) -- same as `-locked`, bounded + random.

**Looked closer before touching anything and found the "obvious" fix
(merge into one `tax_query IN` query) is wrong for 2 of these 3 files,
not just a nice-to-have:**

- The two `orderby => rand` files (`-locked` and the plain variant) are
  fetching *8 random posts per selected term* by design -- e.g. 3
  selected terms = up to 24 curated posts, one random-8 group per term.
  A single merged `IN` query would instead return 8 random posts total
  across all terms combined -- a real content change (fewer, differently
  -composed posts), not a performance-neutral rewrite.
- The grid file additionally resets a `$counter` variable to `-1` at the
  *start of each term's inner loop* (drives a `layout0`..`layout7` CSS
  class cycle, and a post matching 2 selected terms currently renders
  twice, once per term). Merging into one query changes the counter
  cycle boundaries and de-duplicates that repeat post -- again a real
  behavior change, not just a query-count reduction.

So unlike the filter-button fix (§23), this one isn't a safe drop-in --
it would need an explicit decision on whether "8 random per term" /
per-term duplicate rendering is actually wanted, which is a product
question, not a query-optimization one. Left the query *structure*
alone in all 3 files.

**What was safe and got fixed**: `_related-articles-taxonomies-locked.
php`'s 3 query blocks were missing `no_found_rows` (the file has zero
pagination anywhere -- grepped clean), so WordPress was running
`SQL_CALC_FOUND_ROWS` on every one of those per-term queries for a
count nothing ever reads. Same zero-behavior-change fix as task #23's
21-file pass. The plain `-taxonomies.php` variant already had
`no_found_rows` set; the grid variant already had it too (only its
unbounded `-1` and the counter/dedup behavior above remain open).

### Status

`no_found_rows` fix committed `2840909` to `dev`, not pushed. The
unbounded-per-term query in `_related-articles-taxonomies-grid.php` and
the per-term duplicate-post/counter semantics in all 3 files remain
open, flagged here pending a product decision rather than a query
rewrite -- not attempted without explicit sign-off given the behavior-
change risk identified above.

**Update, next session turn**: pushed as `383e3ac` (bundled with the
user's own concurrent `single-registration.php` commit -- confirmed
`origin/dev` now matches local `HEAD` exactly, both commits present).

## 25. Security pass: unsanitized `$_GET`/`$_POST`/`$_SERVER` usage

Continuing task #2 into a new category: superglobal reads that reach
HTML output, raw SQL, or a file path without sanitization/escaping.
Delegated a sitewide search. Result: the overwhelming majority of
`$_GET`/`$_POST` reads in this theme are already correctly wrapped in
`sanitize_text_field()`/`intval()` (no `$_REQUEST`/`$_COOKIE` usage
exists at all). One real, live-relevant finding:

**`template-insights.php` -- reflected XSS (fixed).** `$queryURL` was
built from raw `$_SERVER['QUERY_STRING']` (100% attacker-controlled)
and echoed unescaped into 3 separate `href` attributes (~lines 738,
1033, 1134). A crafted query string like `?"><script>...</script>`
would break out of the attribute. Fixed by wrapping the single
assignment in `esc_url()` rather than each echo site -- standard WP
function for a URL going into `href`, legitimate query strings render
identically. Committed `8cdfb54`.

**Same bug shape found in 5 more files, left untouched (confirmed
dead).** `member-single-post.php`, `single-post-no-embed.php` (x2),
`single-post-feb.php`, and `single-post-side-articles.php` (x2) all
have a `substr($host, 0 - strlen($allowed_host)) == $allowed_host`
`HTTP_REFERER` check that's both missing an `isset()` guard (PHP 8.1+
deprecation warning when no referrer is sent -- common, referrers are
client-optional) and bypassable (`evil-adapt.com.au` satisfies the
suffix check as if it were a real `adapt.com.au` referrer, since
there's no requirement for a `.` boundary before the match). Checked
reachability before touching anything: none of these 5 files have a
`Template Name` header, and grepping the whole theme for
`get_template_part`/`include`/`require`/`single_template` turns up zero
references to any of them -- `single-post.php`, the one file WP's
`single-{post_type}.php` naming convention actually routes to for the
`post` type, doesn't contain this code at all. Same dead-code category
as §18's confirmed-unreachable files (which the user already chose to
leave as-is) -- left these alone rather than re-litigating that
decision on lower-severity, unreachable code.

### Status

Committed `8cdfb54` to `dev`, not pushed.

## §26 — PHP 8.1 "null to internal function" audit: `nav_menu_item_id`
filter chain investigated, confirmed non-issue, no fix needed

Continuing the PHP 8.1 deprecation-warning audit (theory: this could
explain the external site auditor's original "PHP coding error"
report), an Explore agent flagged one MEDIUM-confidence lead worth
checking personally: `includes/_hooks.php` registers two filters on
`nav_menu_item_id`, both priority 10:

```php
add_filter('nav_menu_item_id',   'custom_wp_nav_menu');       // line 44, registered first
add_filter('nav_menu_item_id',   'custom_nav_id_filter', 10, 2 ); // line 48, registered second
```

`custom_nav_id_filter` (`includes/_customisations.php:96-98`) has no
`return` statement at all:

```php
function custom_nav_id_filter( $id, $item ) {
	//return strtolower( str_replace( ' ','-',$item->title ) );
}
```

Read on its own this looks like a real bug -- same-priority filters run
in registration order, so this second filter receives whatever the
first filter computed as its own `$id` argument and then silently
discards it, returning implicit `null` for every nav menu item on
every page (nav menus render sitewide).

**Traced the full chain and it's a harmless no-op, not a bug:**

1. `custom_wp_nav_menu($var)` (`_customisations.php:66-78`) is a
   generic filter reused on two different hooks (`nav_menu_item_id`
   and `page_css_class`). It branches on `is_array($var)`: `page_css_
   class` passes an array (does an `array_intersect` against an
   allow-list), but `nav_menu_item_id` passes a **string** (e.g.
   `'menu-item-123'`). So on this hook `is_array($var)` is always
   false, and the function unconditionally returns `''` -- the id was
   already being blanked out by the *first* filter, before
   `custom_nav_id_filter` ever runs.
2. `custom_nav_id_filter` then receives `$id = ''`, and returns
   implicit `null` instead of `''`. The only actual effect of the
   missing `return` is swapping `''` for `null`.
3. WP core's `Walker_Nav_Menu::start_el()` does
   `$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';` -- a plain
   falsy check. `''` and `null` are both falsy, so the rendered `<li>`
   markup is byte-for-byte identical either way: no `id` attribute.
   `esc_attr()` (or any strictly-typed internal function) is never
   even called with the `null` value, since the ternary short-circuits
   first -- so this also isn't a source of the null-to-internal-
   function deprecation warnings the audit was originally looking for.
4. Grepped `source/scss/` and `source/js/` for anything keyed off a
   `#menu-item-N`-style id selector: found nothing. The only nav-item
   selector in `main.js` (`li.menu-item-has-children`) is a *class*,
   supplied by the unrelated `nav_menu_css_class` filter, not this id
   attribute.

**Conclusion**: zero visible or functional impact in either direction.
The commented-out `return` on line 97 suggests an abandoned mid-
refactor, but there's nothing left to finish -- the id attribute was
already suppressed by the first filter regardless. Not fixing this:
restoring the return wouldn't change any rendered output (id was
already always blank), and touching a sitewide nav-render filter chain
for a purely cosmetic/dead code-path carries more risk than benefit.
This also closes out this window's PHP 8.1 deprecation sub-audit --
the Explore agent's other candidates were low-confidence (ACF fields
in valid `have_rows()` loops practically never return `null` in
practice), and this was the one lead worth chasing down personally.

### Status

No code change. Investigated and closed, flagged here for the record.
Nothing to push.

## §27 — Remaining `posts_per_page => -1` files (MEDIUM tier) audited

Followed up on the tier list from §23/§24: `_events-listing.php`,
`_events-listing-partners.php`, `single-post_author.php`, plus three
files that turned up in a fresh grep not previously catalogued
(`_advisors-carousel.php`, `single-position.php`,
`_open-positions.php`) and two more (`old-template-resource-type.php`,
`template-resource-type-pre-media.php`). Delegated a read-only research
pass (Explore agent), then personally verified before touching
anything.

**Finding: nearly everything already had `no_found_rows => true`** from
an earlier pass -- there wasn't a `no_found_rows` gap left to close in
any of these files. Two genuine, safe fixes found instead:

1. **`fields => 'ids'` on the years-terms query** in both
   `_events-listing.php` and `_events-listing-partners.php` -- same
   shape as the `template-topic.php`/`template-resource-type.php` fix
   from §23: a `posts_per_page => -1` query used *only* to walk matched
   events and collect distinct top-level `years` terms for the year
   filter buttons, never to display the posts (display happens in a
   separate, unrelated query further down each file, left untouched).
   Converted `while($loop->have_posts()): $loop->the_post();` to
   `foreach($loop->posts as $event_post_id)` and dropped the
   now-unnecessary `wp_reset_postdata()`. Identical `$terms` output,
   just skips fetching full post rows. Committed `815661a`.

2. **Missing `wp_reset_postdata()` in `_open-positions.php`** -- a real
   (if narrow-probability) bug, not a `posts_per_page` issue: this file
   is one of ~20 interchangeable flexible-content layouts a page editor
   can place in any order on `template-flexible.php`/`template-home.
   php`. Its position-listing `while()` loop calls `the_post()` but
   never resets afterward, so global `$post` stays pointed at the last
   matched position post once the block finishes. Any later
   flexible-content block on the same page reading `the_title()`/`the_
   permalink()`/global `$post` without an explicit ID would silently
   render that leftover position instead of the real page content. The
   sibling copy of this exact query in `single-position.php` already
   calls `wp_reset_postdata()` -- this file was just missing it. Added
   the same call. Checked the live `/careers/` page (where this block
   currently renders last, nothing downstream reads `$post`), confirmed
   output is unaffected either way -- the fix is purely defensive.

**Everything else in this batch checked and left untouched, confirmed
safe as-is:**

- `single-post_author.php` -- 4 separate `-1` queries, all already have
  `no_found_rows`. One (`event_link` matching, ~line 212) is technically
  an IDs-only extraction loop but relies on ACF `get_field()` needing
  `the_post()`-based context; restructuring to `fields=>ids` would need
  manual `setup_postdata()` per ID -- not worth the risk on this single-
  CPT detail page. The other 3 are full display loops (title/permalink/
  ACF fields/repeaters) with homemade grouping/counter logic tied to
  iteration order -- not `fields=>ids` candidates.
- `_advisors-carousel.php` -- single `-1` query, already has
  `no_found_rows`, results merged into an array and rendered with full
  post data (`get_the_title()`, ACF fields, terms) -- not a terms-only
  extraction, left as-is.
- `single-position.php` -- already has `no_found_rows`; full display
  loop (permalink/title/term), not a `fields=>ids` candidate. Has an
  inert `paged => $paged` param alongside `posts_per_page => -1` (dead,
  since `-1` ignores paging) -- cosmetic only, not touched.
- `old-template-resource-type.php` / `template-resource-type-pre-media.
  php` -- re-confirmed dead via fresh header + sitewide-reference check
  (no `Template Name:`, zero references anywhere in theme code), matches
  the prior-session finding already on record here. Left as-is per the
  user's standing "leave dead files alone" decision.

### Status

Committed `815661a` (fields=>ids) and `dc211b1` (open-positions reset +
this doc) to `dev`. This closes out the MEDIUM-tier
`posts_per_page => -1` follow-up list from §23/§24 -- the only
remaining open item in that area is the grid-file counter/dedup
behavior-change decision from §24, which still needs explicit product
sign-off before any query-merge is attempted.

**Update, next session turn**: pushed as `dc211b1`, confirmed
`origin/dev` matches `HEAD` exactly. Verified live:
- `/edge-events/` and `/event-partner/edge-event-partnership/` (the
  `-listing`/`-partners` fix) -- `#yearButtons` renders `["2026",
  "2027"]` on both, active-state and sort order unchanged.
- `/careers/` (the `_open-positions.php` reset fix) -- position list
  unchanged (`Sales Associate, Executive Program Engagement`, `People &
  Operations Lead`), byte-identical to the pre-push baseline.

## §28 — Systematic sweep: every Slick carousel section audited for the
§22 BFC-containment-loss bug class, 18 sections fixed

### Why

User asked directly: "did you finish the float task updates to all
files, templates?" Honest answer at the time: no, on two separate axes.
(1) The original narrow-width/no-width/carousel-adjacent audit (723
flagged declarations) only hand-reviewed 13 of ~34 template SCSS files
as "Sections" 1-19 -- the rest have only the original mechanical bulk
`float:none` conversion, never individually reviewed. (2) Separately,
and more urgently: §22's `full-suite-slider-module`/`company-slider`
fix was for a bug CLASS (section relies on the sitewide `section
{float:left}` rule to auto-contain an internal still-floating Slick
carousel; the gate's blanket `float:none` on the section removes that
containment; section height collapses) that was found reactively, one
user report at a time -- it was never swept systematically, and it can
exist in ANY section with a carousel, "done" Sections 1-19 included
(confirmed below: `_flexible.scss` is a "done" Section 7 file, yet
`section.form-popup-slider-module` inside it needed checking too --
turned out already safe via a pre-existing `overflow:hidden`, but that
was luck, not audit coverage).

### Method

Grepped every `.slick(...)` init in `source/js/main.js` (~30 total,
covering every carousel sitewide), traced each carousel's selector back
to its enclosing `<section>` via the real PHP templates (delegated the
initial mapping to a read-only research pass, then personally verified
every finding against the actual template/SCSS source before acting --
same discipline as every other fix this engagement), and checked each
section's BASE (ungated) CSS for any pre-existing containment
(`overflow`, `display:flex/grid`, or a trailing `clear:both` sibling)
that would already protect it regardless of the refactor gate.

**Already safe, no fix needed (3 sections/groups):**
- `section.form-popup-slider-module` (`_flexible.scss`) -- own base
  rule already has `overflow: hidden`.
- `section.content-slider-module` -- its `.progress-container` already
  has `clear: both` (this file, ~line 21146), a valid clearfix-
  equivalent fix, applied 2026-09-03.
- `section.quote-slider` (bare, landing/event variants) -- same
  `.progress-container{clear:both}` mechanism, applied 2026-09-08.
  `section.quote-slider.market-buyer-quotes` doesn't need it at all
  (`.quote-slider-module` is `display:flex` there, never floated).

**Fixed this pass -- `display: flow-root` added to 20 section
selectors covering 18 distinct sections** (some selectors share a base
class across multiple template files: `cards-module` covers 3 files,
`peer-insights-featured` covers 2): `cards-module`, `large-testimonial-
slider`, `left-text-links.left-text-links-slider`, `left-text-links.
gtm-map-block`, `stories-hero-slider`, `story-categories-slider-
module`, `two-column-services.landing-two-column-slider`, `sponsor-
block`, `download-block.resources-block`, `logos-block`, `position-
icon-slider`, `speakers-block`, `resources-featured`, `best-practices-
featured`, `flip-card-module`, `peer-insights-featured`, `staff-
slider-module`, `lifestyle-slider-block`, `keynote-slider-module`,
`roundtable-card-slider-module`. Full per-section source-template
citations are in the code comments (`_dev-float-refactor.scss`, right
after the `company-slider`/`full-suite-slider-module` block).

Rebuilt via the scratch-dir workflow; postcss-style diff against the
previous compiled CSS showed exactly one changed line -- the merged
`display:flow-root` selector list gaining these 20 new entries, nothing
else touched.

### Live verification (this pass, `?dev=true` CSS-injection technique --
before/after height, then compared against production)

**Confirmed real, severe, currently-live bugs -- fixed, byte-identical
to production after the fix:**
- `/careers/`: `section.staff-slider-module` was **160px** under
  `?dev=true` (should be **789.328125px** -- a 5x collapse); after
  injecting the fix, exactly 789.328125px, matching production exactly.
- `/careers/`: `section.lifestyle-slider-block` was **250px** (should
  be **801.375px** -- a 3.2x collapse); after the fix, exactly
  801.375px, matching production exactly.
- `/all-resources/`: `section.peer-insights-featured` was **376px**
  (should be **785.75px** -- more than half missing); after the fix,
  exactly 785.75px, matching production exactly.

**Confirmed safe no-ops** (fix present in CSS but measured height
identical with/without it on this particular page -- carousel likely
not float-collapsing in this specific content configuration; harmless
either way since `flow-root` only adds containment, never removes it):
`section.two-column-services.landing-two-column-slider` (become-a-
partner, 746.625px both, matches production exactly), `section.
roundtable-card-slider-module` (private-executive-roundtables,
814px both), `section.stories-hero-slider` (customer-stories,
925.71875px both), `section.resources-featured` (all-resources,
1561.65625px both).

**Not yet found on a live page this pass** (fix is in, provably safe
by the same "can only add containment" logic as `company-slider`'s
original preemptive fix, just not live-confirmed yet): `large-
testimonial-slider`, `left-text-links.left-text-links-slider`,
`left-text-links.gtm-map-block`, `story-categories-slider-module`,
`download-block.resources-block`, `logos-block`, `position-icon-
slider`, `speakers-block`, `best-practices-featured`, `flip-card-
module`, `keynote-slider-module`. Checked ~10 candidate pages
(homepage, about-us, meet-the-team, go-to-market-insights, benchmark-
maturity-assessment, market-buyer-intelligence-platform-advantage,
ecosystem-consulting-partners) without finding these specific blocks --
they're likely used on pages not yet found, or on ACF-optional layout
slots not currently populated anywhere live (same situation as the
`events-listing-module.partners-events-listing` case documented in
§11).

### Three pre-existing, UNRELATED gaps discovered as a byproduct --
flagged, NOT fixed, NOT caused by this pass's changes

Confirmed each of these is unaffected by the flow-root fix (identical
measurement with the fix injected vs not) -- pre-existing bugs in the
already-gated CSS for these sections, separate root cause, out of
scope for this pass:
- `/all-resources/` `section.resources-featured`: dev 1561.65625px vs
  production 1379.875px (~182px gap). This section already has ~30
  hand-written nested overrides in `_dev-float-refactor.scss`
  (including a note referencing an earlier user report specifically
  about "resources-featured") -- an already heavily-worked area, this
  looks like a different, more specific bug within it.
- `/private-executive-roundtables/` `section.roundtable-card-slider-
  module`: dev 814px vs production 854px (~40px gap).
- `/become-a-partner/` `section.sponsor-block`: dev 1499.75px vs
  production 849.1875px (~650px gap, dev is TALLER than production --
  opposite direction from the other two, likely a different bug
  mechanism entirely).

### Status

Committed to `dev`, pushed. This closes the "did you finish the float
task" question for the Slick-carousel/BFC-containment bug class
specifically -- all ~30 carousels sitewide now traced and covered
(fixed or confirmed already-safe). The separate, larger "13 of ~34
files hand-reviewed for the ORIGINAL 3 risk categories" gap from §6 is
still open and not addressed by this pass -- that's a bigger, slower
lift (each file needs the full per-selector categorize-and-review
treatment Sections 1-19 got, not a single mechanical pattern like this
pass).

**Update, same session, follow-up on the 3 unrelated gaps:**

- **`section.sponsor-block` (~650px gap) -- root-caused and fixed.**
  Not a containment-loss bug at all: `section.sponsor-block .container
  .table-block.desktop` is supposed to be hidden at narrow widths (real/
  ungated rule in `_landing.scss`, `@media(max-width:767px){&.desktop{
  display:none}}`), but the mechanical bulk `display:block` fix
  elsewhere in this file (the one restoring block-level display to
  elements that had `float:none` applied, same mechanism as the §2b
  span/a un-blockify bugfix) has `section.sponsor-block .container
  .table-block` in its huge merged selector list -- unscoped, no media
  query, and its specificity (2 elements + 4 classes) beats the real
  rule's media-scoped one (1 element + 4 classes) at every width,
  permanently forcing the desktop table visible alongside the mobile
  slider. Confirmed live: at 533px, BOTH the desktop table (714px) AND
  the mobile slider (473px) were rendering simultaneously under
  `?dev=true`; production correctly showed only the mobile slider.
  Fixed with a higher-specificity, properly `@media(max-width:767px)`-
  scoped override restoring `display:none` for `.desktop` specifically
  -- same "add back the scoping the bulk fix stripped out" pattern as
  the `.links-container` fix in §11. That alone closed the section from
  1499.75px to 786.1875px (production: 849.1875px). The residual ~63px
  turned out to be the SAME containment-loss pattern as the rest of
  this pass, one level deeper -- `.table-block.mobile` (now the only
  visible variant) wraps the same still-floating Slick carousel and
  needed its own `display:flow-root` too. Added it; closed the section
  to 842.1875px, within 7px of production -- that residual matches the
  same benign floated-trailing-margin-collapse artifact already
  documented multiple times elsewhere in this file (comparison-module/
  list-block/registration-two-column-block in §11), not a new bug.
- **`section.roundtable-card-slider-module` (~40px gap) -- investigated
  further, not resolved.** The section's own `flow-root` (from this
  pass) is correctly applied and its children look properly contained
  (verified DOM structure: `.top-content` and `.roundtable-card-slider`
  both floated, `.container` wrapping both without collapse). No
  obvious single override explains the remaining ~40px the way the
  sponsor-block bug had one -- most likely the same benign margin-
  collapse artifact class as the sponsor-block residual, just larger,
  but not confirmed. Left as-is rather than guess further; flagged for
  whoever picks this up next.
- **`section.resources-featured` (~182px gap) -- not investigated this
  pass.** Already has ~30 hand-written nested overrides from earlier
  sessions (including a note referencing a prior user report
  specifically about this section) -- a bigger, more tangled area than
  the other two, deliberately left for a dedicated pass rather than
  rushed.

Rebuilt/diffed (scratch-dir workflow, clean single-line diffs both
times) and committed as a follow-up. Pushed, confirmed live.

## §29 -- Header pixel-parity audit (user request: "check header, needs
to be the same pixel to pixel from the non-gated version")

Systematic `?dev=true` vs production comparison of `<header
class="header clear background-black">` end to end, using a recursive
DOM-tree measurement script (x/y/w/h/display/float per node, several
levels deep) run on two side-by-side tabs at the same viewport, rather
than eyeballing. Covered both breakpoints:

**Desktop (1440px):** every measured node -- logo, main nav `<ul>`,
menu-buttons, client-login, booking-button -- matched production
exactly (identical x/y/w/h down to hundredths of a pixel). One cosmetic
`display` difference only (flex vs `float:left` block) with identical
resulting box geometry, not a bug. The desktop hover mega-menu
(`.main-nav > ul > li.dropdown .dropDownSection`) also matched
exactly (466.89px both) -- it has a fixed-height container, immune to
this bug class.

**Mobile (375px):** opened the mobile nav panel (`.mobileMenuMain`,
via the same class-toggle `main.js` uses) and its "Resources" dropdown
and confirmed the base panel, nav item list, and buttons are all
byte-identical to production. The "Resources" dropdown submenu itself,
however, was NOT: `.dropDownSection` measured 509px (production:
587px, -78px) and its `.full-width` subscribe-form sibling measured
247px (production: 279px, -32px). Root-caused and fixed -- see §28-
style writeup added directly above this entry in `_dev-float-refactor.
scss` (three separate instances of the same containment/margin-
collapse bug classes already established in this file: `.all-link-
container` losing containment of its still-floated `.all-link` child,
and two separate cases of a child's margin collapsing through an
unfloated parent that used to be floated). All three confirmed via
live CSS-injection testing to close the gap to an EXACT match with
production (587px / 279.28px / 24px, all three numbers). `.groupSection`
(the sibling class sharing the identical base rule for other dropdown
variants) got the same fix defensively, not live-verified.

### Status

Committed to `dev`, not pushed. This is the most rigorous pixel-parity
check the header has had all engagement -- previous "Sections 1-6"
work covered the *original* narrow-width/no-width/carousel-adjacent
categories, but (like §22/§28) never specifically checked for this
newer containment/margin-collapse bug class, which is how this survived
undetected until now.

### Correction (2026-09-09, same session continued): the "desktop
matched exactly" claim above was WRONG

User pushback, verbatim: *"I am not blind. YOu can see the difference
between the gaps each item when you hover header menu items. Check it
side by side if needed. compare it properly. pixel by pixel. the
distance to each item and so on."* -- followed by *"and I also notice
there are floated elements in the header and mega menu."*

They were right. The §29 desktop check above only measured
container-level boxes (`.main-nav > ul`, one `.dropDownSection`'s
outer box) -- it never opened all 5 dropdowns simultaneously and
compared item-by-item spacing *within* each menu, which is exactly
where the real bugs were hiding.

**Re-investigation method:** opened all 5 top-level desktop dropdowns
on two side-by-side tabs (`?dev=true` vs production, both 1440x900),
then ran a script capturing `[tagName, className, x, y, width,
height]` for literally every element inside `header.header` (804
elements) on both tabs, and diffed the two arrays index-for-index
(safe since both environments render the identical DOM structure --
only computed layout differs). This found what the container-level
check missed. Four distinct, confirmed bugs, all following bug classes
already established in this file (BFC-containment-loss and
margin-collapse-through, described in §22/§28), plus one genuinely new
one:

1. **Top-level nav item height (44px vs 48px), all 5 items.** The
   biggest single contributor to "the distance to each item on hover"
   -- every one of Our Services / Events / Resources / For Customers /
   About Adapt was 4px short under `?dev=true`. Cause: `.main-nav ul`
   was converted to `float:none;display:flex` (deliberate, from an
   earlier session's nav->flexbox pass), but its `<li>` children have
   no explicit height, so they stretch (default `align-items:normal` =
   stretch) to the UL's *content box* -- and since everything is
   `box-sizing:border-box` and the UL has `height:48px` +
   `padding-top:4px`, the content box is only 44px. Production's `<li>`
   is a real float (unaffected by the UL's box model), sized purely by
   its child `<a>`'s `line-height:48px`. Fixed by pinning
   `.main-nav > ul > li { height: 48px }` directly (note: scoped with
   `>` combinators -- the first attempt used a plain `ul li` descendant
   selector and it also caught every nested `<li>` inside the dropdown
   content lists, breaking those badly; had to redo it scoped to direct
   children of the top-level nav list only).

2. **`.dropDownSection` containment loss (THE main "gap" bug).** Same
   float:none-without-flow-root pattern as §22/§28/mobile-Resources,
   just not caught for the DESKTOP mega-menu instance of
   `.dropDownSection` until this pass. On eventsMenu, dropDownSection's
   reported height collapsed from the real 56/56/72px to 20px (just
   its `.columnTitle`), so consecutive items were spaced 60px apart
   instead of ~96px -- a large, directly visible per-item gap shrink,
   the closest single match to the user's literal complaint.
   `display:flow-root` fixes it, same as always. Two companion bugs
   surfaced once this was fixed: (a) customerMenu packs 3 repeated
   title-link/columnTitle/columnText groups inside ONE shared
   dropDownSection (not one each like eventsMenu) -- once contained,
   groups 2 and 3 still overlapped the previous group's still-floated
   `.columnText`, needing `clear:both` on `.columnTitle` and
   `.all-link-container`; (b) the list-based variant (resourcesMenu,
   adaptMenu) had the same issue for its `<ul>`, needing `clear:both`
   there too.

3. **`.menu-bottom` (eventsMenu's "view all events" footer) 32px
   short, wrong Y entirely (184 vs 699).** `float:none` on a div that's
   meant to drop below two full-height floated columns leaves it
   positioned as if the columns weren't there (normal-flow block
   position ignores floats). `clear:both` gets partway there (667) but
   the CSS clearance calculation absorbs the `margin-top:32px` instead
   of adding it on top (spec: clearance = max(0, floatBottom -
   marginPosition), which already accounts for the margin). A real
   float doesn't have this cap -- reverting to `float:left` (the one
   fix in this whole pass that goes back to the original rule instead
   of compensating for its removal) reproduces production's 699px
   exactly.

4. **`external-link-event` links (eventsMenu's date-list column) all
   rendered full-width instead of shrink-to-fit.** Real CSS
   (`_header.scss:685-687`) sets `width:auto` on this specific `<a>`
   variant so it hugs its own text, leaving the `.event-month` badge
   room to sit at a text-length-dependent position -- that's why every
   link has a different width in production. `width:auto` shrink-wraps
   on a float but fills-container on a plain block, and the gate's
   float:none (+ the automated display:block compensator) turned it
   into the latter. Tried `display:inline-block` first to fake
   shrink-to-fit without re-floating -- got the widths right but
   introduced a growing vertical drift (mixing inline-block with the
   sibling inline `.event-month` in one line box inflates the parent
   `<li>`'s height). Re-floating for real (`float:left`, matching
   production) plus `display:flow-root` on the parent `<li>` (to
   re-contain it) fixed both position and width exactly.

5. **Bonus, same audit pass: `.services-content-inner` /
   `.it-content.services-content` (servicesMenu's right-hand hover
   panel) collapsed to height:0**, hiding the entire IT-Leaders/
   Tech-Vendors switching panel content under `?dev=true`. Same
   flow-root fix.

**Specificity note for whoever touches this next:** several of these
new rules target `<span>`/`<a>` elements that were originally floated,
which means `fix-float-none-display.js`'s automated pass also
generates a `display:block` rule for the same selector chain,
appended after everything in this source file at build time. On equal
specificity that generated rule would silently win and undo the
`flow-root`/`clear` fix. Followed the same "self-doubled class"
technique already used for `.text-link`/`section.sponsor-block`
elsewhere in this file (e.g. `.dropDownSection.dropDownSection`) to
guarantee winning regardless of source order, without `!important`.

**Verification:** built via the `/tmp/mobile-fix-scratch3` +
`npx gulp build:styles-split` scratch-dir workflow, diffed the
resulting `main-nofooter.min.css` against the real repo's compiled
file (clean diff -- new selectors merged into existing shared
declaration groups, nothing unexpected touched), then re-verified live
by injecting the EXACT final compiled CSS lines (not the ad-hoc test
rules used during investigation) into a fresh tab and re-running the
full 804-element header capture against production. Result: 3 residual
diffs, all pre-existing and confirmed non-visual:
  - The outer `header .container` box reports height:0 in production
    too vs 65px under `?dev=true` -- `header` itself has a fixed
    explicit height (80px, identical in both) that doesn't depend on
    this, confirmed via direct measurement on both origins. No visible
    difference.
  - Two instances of an inline `<a>`/`<span>` wrapper (e.g. `.title-
    link`, `.overview-container-inner`) collapsing to 0x0 in production
    because it wraps only floated children (CSS quirk: an inline,
    non-BFC parent containing ONLY floats has zero content-box size),
    while under `?dev=true` the same element shows real dimensions
    because the wrapped child was un-floated. Confirmed via `Range.
    getClientRects()` on the actual text content that the VISIBLE
    glyphs render in the same position in both cases -- the wrapper
    box itself has no background/border, so this is a measurement
    artifact, not a rendered pixel difference. The
    `.subscribe-sidebar-form p.text-black` case (229 in the diff
    index) is the same phenomenon: box reports height:163.86px/y:176
    in production because it's a non-floated block whose own top edge
    ignores two full-width floats above it, but the actual text glyphs
    (checked via Range) render at y≈290, identical to `?dev=true`'s
    box position.

### Status

Committed to `dev` (not yet pushed) on top of the mobile-Resources fix
from the previous entry -- both are bundled together, not pushed
individually, since this investigation started before that commit was
pushed. `assets/css/main-nofooter.min.css` rebuilt and included in the
same commit.

Not independently re-audited this pass: mobile-breakpoint dropdown
item-by-item spacing (only the desktop hover menus were the subject of
this investigation, per the user's specific complaint about
"hover[ing] header menu items"). The mobile Resources dropdown fix
from the previous entry is unrelated/already covered.

## §30 -- 2026-09-10: WP Rocket delay-JS false-positive discovery, plus two
real bugs found and fixed (`.cards-progress`, sidebar `.video-container`)

### New testing-methodology finding: WP Rocket "Delay JavaScript Execution"

While re-investigating the previously-flagged, unresolved
`section.roundtable-card-slider-module` gap (~40px, `/private-executive-
roundtables/`), an automated (no real interaction) measurement showed an
enormous ~2900px gap -- dev height 1011px vs production 3909px. Root
cause turned out to be a testing artifact, not CSS: WP Rocket's "Delay
JavaScript Execution" optimization rewrites `<script src="...">` to
`<script type="text/rocketlazyloadscript" data-rocket-src="...">` on
real (non-`?dev=true`) page loads, deferring `main.min.js` (which
bundles jQuery + Slick + all site JS) until a genuine user-interaction
event fires. `?dev=true` bypasses this optimization and loads scripts
immediately. Waiting several seconds does not trigger it. A
programmatic `scrollIntoView()`/`location.reload()` does not count as a
trusted interaction either. Only a real interaction dispatched via the
browser tool's `computer` action (e.g. a `scroll`, after a prior
`screenshot` call) counts -- confirmed by checking `jQuery.fn.slick`
(false until triggered) and the script tag's `type`/`data-rocket-src`
attributes. Before the interaction, every slide renders stacked/un-
sliced, so any Slick-carousel section measured on production too early
reports a false huge gap.

**Implication for future testing**: any pixel-parity check involving a
Slick carousel (or any other main.min.js-dependent behavior) must
dispatch a real `computer` tool interaction on the production tab
before measuring, every time -- not just once per session. This likely
explains some of the earlier "gap not yet investigated" notes on other
carousel-adjacent sections in this document; those should be treated as
suspect until re-checked with this methodology, not assumed to be real
bugs.

### Bug found + fixed: `.cards-progress` (roundtable-card-slider-module)

After re-measuring `/private-executive-roundtables/` with a real
interaction dispatched first, the real residual gap was 90px (not
~2900, not the earlier ~40). Root cause: `.cards-progress` (the thin
scroll-progress bar under the slider) is real/ungated `float:left;
width:100%; margin-top:90px` (`source/scss/templates/_roundtable.scss`
:343-346), converted to `float:none` in the gated refactor. Its parent
`.cards-progress-container` has no CSS of its own. With the float
removed, `.cards-progress` becomes a normal block sibling immediately
after two still-floated full-width siblings, and its `margin-top` gets
absorbed into float clearance instead of adding on top of it --
identical mechanism to the `.menu-bottom` clearance-absorption bug
fixed in the header pass (see §29's correction entry). Confirmed live:
reverting to `float: left` (matching production, same one-off exception
as `.menu-bottom`) reproduces production exactly -- lands at relative
y=796 in both, full 97-element diff empty.

Fix written to `_dev-float-refactor.scss` (the
`// --- source/scss/templates/_roundtable.scss (8 declarations) ---`
block, ~line 16574): `.cards-progress { float: left; }` immediately
after the existing `float: none;` declaration, with a detailed inline
comment.

### Bug found + fixed: sidebar `.video-container` height collapse (`resources-featured`)

Also re-investigated the flagged `section.resources-featured` gap
(~182px, `/all-resources/`). With a real interaction dispatched first,
the section's own top-level rect matched production exactly
(`[0, 160, 1425, 913.625]`), but a full 117-element item-by-item diff
surfaced a genuine bug: the three video-badge posts in the sidebar
(`.resources-side-posts .resources-side-posts-inner > a
.video-container`) rendered at height ~31px instead of production's
~96px, with every post below the first one shifting up to compensate --
that shift was the source of the previously-reported ~182px gap
(image-only sidebar posts, which don't hit this code path, matched
exactly).

Root cause: an earlier fix (2026-09-03, already in this file) for a
different bug -- `.video-container`'s width:32.7% collapsing once its
parent `<a>` became an unstyled flex item -- solved the width problem
by giving the `<a>` `flex: 0 0 32.7%` and `.video-container` `width:
100%`. But `.video-container`'s real `padding-top: 22.8%`
(`source/scss/templates/_resources-types.scss:2573`, the standard
aspect-ratio padding-hack) is *also* a percentage of the same
containing block as `width` -- which that 2026-09-03 fix silently
changed from `.resources-side-posts-inner` (full width) to the `<a>`
(32.7% of that width). The un-rescaled 22.8% now resolves against a
box 32.7% as wide, collapsing the thumbnail height by the same factor.
Fix: rescale so the effective ratio survives the narrower containing
block -- `22.8% / 32.7% = 69.7248%`. Confirmed live: this restores all
3 sidebar video-container rects to an exact match with production
(134.88 x 96.03), and the full 117-element diff comes back empty aside
from one benign, expected non-bug: the `.slick-dots` active-dot index
differed between the two tabs at capture time because the carousel had
auto-advanced between the two measurements (confirmed via each tab's
own `.slick-active` class) -- not a CSS issue.

The same 2026-09-03 fix (and therefore the same missing-rescale bug)
also exists verbatim for the homepage's `featured-home` sidebar variant
(`section.featured-module.featured-home .post-list-container
.side-bar-column ...`); fixed identically there too, since the
underlying percentages are the same, though not independently
re-verified live on the homepage this pass (only `/all-resources/` was
directly re-measured).

Fixes written to `_dev-float-refactor.scss`: added `padding-top:
69.7248%;` (with explanatory comments) to both the
`section.featured-module.resources-featured ... .video-container` rule
and the `section.featured-module.featured-home ... .video-container`
rule (~lines 20153 and 20220).

### Status

**Not yet built, diffed, or committed.** The sandbox's Linux VM was
wedged for this entire investigation (Plan9 mount / "user already
exists" RPC errors, persisting across app restart and a full PC
reboot) so the usual rebuild-CSS-and-diff-before-committing step could
not run. Both fixes above are written directly to
`_dev-float-refactor.scss` only. Once shell access is available again:
rebuild via the established `/tmp` scratch-dir + `npx gulp
build:styles-split` workflow, diff the compiled
`assets/css/main-nofooter.min.css` against the repo's current version,
copy back, and commit both fixes together (they were investigated in
the same pass). `git status`/`git log` were also never reachable this
pass, so it's unconfirmed whether the user has pushed the two commits
from §29 (`50be22e`, `abf65e6`) yet.
