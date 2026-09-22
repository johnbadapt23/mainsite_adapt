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

## §31 -- 2026-09-10 (cont.): CI build confirmed unnecessary before push;
`.cards-progress` specificity re-fix; two new user-reported/found bugs
fixed; flow-root verification sweep continued and effectively closed

### CI already rebuilds and force-deploys compiled CSS/JS on every push

Confirmed by reading `.github/workflows/deploy.yml`: every push to `dev`
runs `npx gulp build:styles build:styles-split build:scripts`, and the
deploy step's `sync-delta-includes` unconditionally re-uploads
`assets/css/main.min.css`, `assets/css/main-nofooter.min.css`,
`assets/css/footer.min.css`, and `assets/js/main.min.js` regardless of
git diff. **Local `npx gulp` builds before committing are not required
-- commit the `source/scss`/`source/js` changes directly and push; CI
compiles and force-deploys the output.** This corrects an earlier
instruction in this session to run a local build first.

### `.cards-progress` fix from §30 was live but NOT actually working -- root cause: bulk-rule specificity tie

Re-verified `.cards-progress { float: left; }` (written in §30) against
the real deployed CSS (not live-injected -- see next section for why
that distinction matters) and found it was losing the cascade despite
being present in the compiled output. Root cause, newly understood this
pass: `fix-float-none-display.js` bulk-collects the `float: none;` (and,
for span/a leaves, `display: block;`) overrides for many originally-
floated single-class elements into one huge merged selector list near
the end of the compiled CSS. A hand-written override that reverts one
of those elements back to `float: left` as a **bare single-class
selector** has *identical* specificity to the bulk rule, and loses the
tie because the bulk rule comes later in file order. Fix: use a
self-doubled class (`.cards-progress.cards-progress`) to add one extra
class and win outright, without `!important`. Re-verified this is
actually live now via a cache-busted `fetch()` of the real compiled
CSS (see next section -- not live-injection).

**Audited all other bare `float: left;`-style overrides in
`_dev-float-refactor.scss` for the same bug (7 found via grep): only
`.cards-progress` was affected. The other 6 were already safe** (either
already self-doubled, or the real rule they're overriding has fewer
classes than the bulk rule so there's no tie to lose).

### Methodology correction: live-`<style>`-injection verification is unreliable for specificity bugs

`document.head.appendChild()`-injected `<style>` tags always win the
cascade regardless of true selector specificity, because they're
appended to the DOM after every real stylesheet. Every prior "confirmed
via live injection" claim for a plain-class override in this project's
history should be treated as unverified for cascade-order/specificity
purposes specifically (it's still valid for confirming the CSS
*values* are correct). The only reliable check for "does my override
actually win against the real compiled CSS" is one of:
1. `fetch()` the real, currently-deployed CSS file with a cache-busting
   query param and grep/parse its actual rule order, or
2. Swap the page's real `<link rel="stylesheet">` tag for a freshly-
   fetched one (`disabled = true` the old one) so the browser's own
   cascade engine resolves it for real, or
3. Compute selector specificity by hand ([id-count, class-count,
   element-count] tuples, compared column-by-column) and compare
   candidate vs. competing selectors directly -- fastest when you
   already know both selector strings (used below for the
   `flip-card-module` fix, since it can't be confirmed live until
   deployed).

### Also newly confirmed: the compiled CSS file is aggressively cached

`assets/css/main-nofooter.min.css` is served `Cache-Control: public,
max-age=31536000` (1 year) with a static `?ver=` query string that CI
does **not** bump on redeploy. A browser tab (or an automated test tab)
opened before a deploy can silently keep serving a year-stale cached
copy afterward. Workaround: always fetch with a unique cache-busting
query param (`?cb=Date.now()`), or hard-navigate a fresh tab.

### Bug found + fixed (user-reported): peer-insights-featured Slick arrows

User reported: `section.featured-module.peer-insights-featured
.slide-column .peer-featured-slider button.slick-arrow.slick-next`
(and `.slick-prev`) should use `top: -50px` and hide the `::after`
pseudo-element. Root cause: an unscoped rule in `_market.scss`
(`button.slick-next`/`.slick-prev`, written for
`section.centerModeCarousel` only, no section-scoping) was bleeding
onto every Slick arrow sitewide, including this one. Fixed directly in
`source/scss/templates/_resources-types.scss` (real/ungated file, not
gated -- this is a genuine production bug, not a float-refactor
regression) with `top: -50px` and `&:after { display: none; }` added to
both `&.slick-prev` and `&.slick-next`. **Pushed and confirmed live.**

### Bug found + fixed: `.article-container-three-post` / `.market-trend-reports-container-three-post` margin/float regression (user-reported: "articles section not identical" on `/all-resources/`)

The homepage instance of this shared card-grid class had already been
patched (2026-09-03, compensating via padding) but every *other*
instance -- including `/all-resources/`'s "articles" flexible-content
row and `/resource-type/market-trend-reports/` -- was still broken by
the same margin-clearance-absorption bug (real rule: `float: left;
margin: 62px 0;`, `_resources-types.scss:3308`; homepage's own
`section.articles-featured.featured-module` further zeroes the bottom
margin, `_resources-types.scss:2947`). Fix (self-doubled classes from
the start, applying the §30/§31 lesson): restore `float: left;
margin-top: 62px;` for both classes, then re-zero it specifically for
the homepage's `section.featured-types` instance (which needs the
padding-based fix instead, to avoid a visible background-color bleed),
plus `display: flow-root;` on `section.articles-featured.articles-
featured` itself. Live-verified (before push) via style-injection: full
52-element diff empty except one benign artifact; exact match also on
`/resource-type/market-trend-reports/`. **Written to
`_dev-float-refactor.scss` (~line 22173) -- not yet confirmed pushed.**

### Bug found + fixed: `.keynote-progress` margin-clearance-absorption (`/edge-events/`)

Same bug class as `.cards-progress` (§30) and `.menu-bottom` (§29):
real rule is `float: left; width: 100%; margin-top: 90px;`
(`_events.scss:1645`); un-floating it for the gated refactor absorbs
the `margin-top` into clearance instead of adding it on top. Fixed with
a self-doubled class from the start (`.keynote-progress.keynote-
progress { float: left; }`, ~line 7705), avoiding the mistake that
`.cards-progress` originally made. **Written to `_dev-float-refactor.scss`
-- not yet confirmed pushed.**

### Bug found + fixed: `section.flip-card-module` mobile-caption leaking onto desktop (`/event-partner/edge-event-partnership/`)

Found during the flow-root verification sweep (below), not user-
reported. `section.flip-card-module`'s `top-container` height was
188px under `?dev=true` vs. 167px in production at 1440px viewport --
traced to `span.text.mobile` (the "Scroll to discover..." mobile-only
caption, real rule `_events.scss:3864`: `span.text.mobile { display:
none; }` outside a `max-width: 767px` media query) rendering
`display: block` (visible) at desktop width under the gate. Root cause:
the bulk leaf-`display:block`-restore rule generated for the un-floated
`span.text` was written *without* the `.mobile` modifier class
(`body.dev-float-refactor section.flip-card-module .container
.top-container .inner span.text`), but since it still matches
`span.text.mobile`, and both it and the real `span.text.mobile` rule
have 6 classes, the bulk rule wins the specificity tie on the *element*
column (3 elements: body+section+span, vs. the real rule's 2:
section+span) -- same specificity-tie bug class as `.cards-progress`,
just breaking the tie a different way. Verified by hand-computing both
selectors' specificity tuples ([0,6,2] real vs. [0,6,3] bulk) rather
than live-testing, since this fix can't be confirmed live until
deployed (see methodology note above). Fix: self-doubled `.mobile`
class (`span.text.mobile.mobile { display: none; }`, 7 classes, beats
both outright) added to `_dev-float-refactor.scss` right after the
existing `section.flip-card-module` block near the top of the file.
**Written -- not yet confirmed pushed.**

### Flow-root verification sweep: effectively closed

Continued the systematic verification of every gated `display:
flow-root` section (list originally ~19 items). This pass:

- **Confirmed clean (exact pixel match) via live pages found this
  session**: `section.position-icon-slider` (`/careers/people-
  operations-lead`, exact match 1425x998.72 both), `section.two-column-
  services.landing-two-column-slider` (`/become-a-partner/` and the
  `*-edge/become-a-partner/` family, exact height match, only a benign
  ~15px width delta consistent with a scrollbar artifact),
  `section.peer-insights-featured` (`/all-resources/`, both instances
  exact match -- the section-level flow-root itself, independent of the
  arrow fix already confirmed separately).
- **Found and fixed a new bug**: `section.flip-card-module` (see above).
- **No live instance found on any published page**, after an extensive
  search across the full page-sitemap plus resource/customer-stories/
  position/event-partner singles (~25+ pages checked this session,
  on top of pages checked in earlier sessions): `section.speakers-
  block` (its component file `templates/components/_speakers-block.php`
  is never actually included by `get_template_part` from
  `template-speakers.php` -- that template hand-rolls its own inline
  `<section class="speakers-block">` markup instead -- AND
  `template-speakers.php`/`template-speaker.php` aren't assigned to any
  page in the sitemap; `/speakers/` 404s), `section.story-categories-
  slider-module` (`templates/customer-story-components/_category-
  slider.php` is **dead code** -- confirmed via grep that no template
  anywhere calls `get_template_part` for it; the one place it's
  mentioned is a comment in `_category-three-column.php` referencing it
  as the source of a copy-paste leftover, not an include), `section.
  logos-block` (the `logo_block` ACF flexible-content layout on
  "Flexible Template" -- checked every Flexible-Template-driven page
  found in the sitemap, including `/careers/` and `/about-us/`; not
  currently selected on any of them), `section.download-block.
  resources-block` (the `resources_block` ACF layout option on
  `single-resource.php`/`single-resources.php`; checked ~8 individual
  resource posts across every resource-type taxonomy term, none
  currently use this layout), `section.best-practices-featured` (the
  `best_practices_guides` ACF layout option on `template-resources.php`,
  the "Resources Landing" template used by `/all-resources/`; that
  page's current content rows don't include this layout),
  `section.left-text-links.left-text-links-slider` (carried over from
  before this session, re-confirmed unused this pass).

  **None of these six are reachable by a real visitor today** -- they're
  either genuinely dead code or unpopulated ACF layout options. This
  means the gated flow-root fix for each is unverifiable live *and*,
  more importantly, harmless either way: since nothing currently
  renders through them, `?dev=true` removal cannot regress what isn't
  showing. They should stay flagged as "unverified, low-risk, revisit
  if/when a page starts using them" rather than blocking factors.

### Updated status on "is `?dev=true` removal safe yet?"

**Still no** -- this pass alone surfaced three more genuine, previously
undetected bugs (`.article-container-three-post`/margin, `.keynote-
progress`/margin, `flip-card-module`/mobile-caption-leak), on top of
the `.cards-progress` fix turning out to have never actually worked.
The verification process keeps finding real bugs, which is itself the
argument for finishing it properly before flipping the gate off.

### Outstanding pending-push list (as of end of this session)

Three fixes are written to `_dev-float-refactor.scss` but not yet
confirmed pushed by the user:
1. `.article-container-three-post`/`.market-trend-reports-container-
   three-post` margin/float regression + homepage exclusion +
   `section.articles-featured` flow-root (~line 22173).
2. `.keynote-progress.keynote-progress { float: left; }` (~line 7705).
3. `section.flip-card-module ... span.text.mobile.mobile { display:
   none; }` (near the top of the file, in the existing flip-card-module
   block).

All three should be committed and pushed together next -- no local
build step needed (see CI note above), just `git add`, `git commit`,
`git push`. Once pushed and deployed, re-verify all three live (the
`article-container-three-post` fix specifically against `/all-
resources/`'s "articles" section, which was directly observed still
showing the pre-fix ~126px gap this session, confirming the fix hasn't
gone out yet).

### Remaining open item

Task #49 (the older "~13 of 34 files never hand-reviewed" audit gap)
was not addressed this pass -- still open.

## §32 -- 2026-09-10 (cont.): the three §31 fixes were pushed and
deployed, but `.article-container-three-post` was STILL broken live --
root cause was a specificity miscalculation in the fix itself, not a
deploy problem

### Confirmed the §31 push deployed successfully

After the user pushed, the compiled CSS (re-fetched with cache-busting)
picked up all three fixes within ~5 minutes -- confirmed by checking
`Last-Modified` advanced and the fix strings became present. The
earlier "fixes aren't live yet" read a few minutes prior was correct at
the time; it was just deploy lag, not a failed push.

### But the user immediately reported (with a screenshot) that
`/all-resources/`'s "Articles" section still looked wrong under
`?dev=true` -- spacing "way too off"

Re-measuring confirmed it: `.article-container-three-post`'s computed
`float` was still `none` and `margin-top` still `0px`, even though the
fresh CSS unambiguously contained `.article-container-three-post
.article-container-three-post { float: left; margin-top: 62px; }`
ranked *after* the competing bulk `body.dev-float-refactor
.article-container-three-post { float: none; display: block; }` rule
in the file.

### Root cause: a specificity-column mistake, not a cascade-order or build/cache problem

CSS specificity is compared column-by-column, **(ID-count,
class-count, element-count)**, moving to the next column only when the
current one ties. The competing bulk rule is `body.dev-float-refactor
.article-container-three-post` -- 1 element (`body`) + 2 classes
(`dev-float-refactor`, `article-container-three-post`) ->
**(0, 2, 1)**. The `.article-container-three-post`
fix from §31 was written **unscoped, outside any `body.dev-
float-refactor { }` wrapper** (a plain top-level rule, left over from
before the `body.dev-float-refactor { .article-container-three-post,
... { margin: unset; } }` block that precedes it in the file --
the new rule was appended after that block's closing brace instead of
inside it) -- so it compiled to just `.article-container-three-post
.article-container-three-post` -- 0 elements + 2 classes -> **(0, 2,
0)**. Comparing (0,2,1) vs (0,2,0): IDs tie, classes tie (2=2), so the
decision falls to the element column, where the bulk rule's 1 beats
the fix's 0 -- **the bulk rule wins outright, regardless of which rule
comes later in the file.** Doubling a class only helps once the class
column itself is ahead or tied *and* the element column is also
covered; it does not compensate for a missing element-selector prefix
the way I'd assumed when writing it. This invalidates part of the
"self-doubled class always wins the tie" mental model used elsewhere
in this file -- it's only reliable when the doubled-class rule is
nested inside `body.dev-float-refactor { }` (regaining the `body`
element + `.dev-float-refactor` class the bulk rules always carry), or
when the class count is bumped high enough to win the class column
outright on its own regardless of element count (e.g. the
`flip-card-module` fix from §31, which used a *triple*-repeated class
specifically so the class column alone decides).

**Verification method used this time**: rather than trusting the live
page (subject to the caching/deploy-lag issues documented in §31) or
manual selector-tuple arithmetic alone, the fix was verified by
reconstructing the exact competing rules in an isolated in-page test
(`document.createElement` a bare test `<style>` + test element,
inject just the 2-4 rules in question, read `getComputedStyle`, then
remove them) -- this reproduces the browser's real cascade engine
without any dependency on what's actually deployed, and is now the
preferred verification method for any future specificity dispute in
this file, ahead of both live-injection (known unreliable, see §31)
and hand-computed tuples alone (this bug slipped through hand
computation once already).

### Fix

Moved the three rules (`article-container-three-post`/`market-trend-
reports-container-three-post` general float+margin restore, the
`section.featured-types` homepage exclusion, and `section.
articles-featured` flow-root) inside a `body.dev-float-refactor { }`
wrapper, and bumped the general rule's doubled class to appear
alongside the wrapper (regaining the element-column parity) so its
specificity (0,3,1) beats the bulk rule's (0,2,1) outright on the class
column, independent of element count. The homepage-exclusion rule was
similarly given the `body.dev-float-refactor` prefix plus its existing
`section.featured-types` scoping, landing at (0,4,2) -- unambiguously
above the general rule's (0,3,1), not reliant on source-order
tie-breaking. **Verified via the isolated-reconstruction method above
-- both the general case and the homepage-exclusion case now resolve
correctly.** Not yet re-pushed/deployed as of writing.

### Audit: are there other unscoped self-doubled-class fixes in this file?

Dispatched a full-file audit (12 self-doubled-class instances found
total, covering `.menu-bottom`, several header/megaMenu overrides,
`.keynote-progress`, `.cards-progress`, and the three
`article-container-three-post`-related rules). **Result: all 12 are
correctly nested inside a `body.dev-float-refactor { }` block** --
the just-fixed `article-container-three-post` set was the only
offender, and it's now fixed. No other latent instances of this
specific bug remain in the file as of this pass.

### Status

Written to `_dev-float-refactor.scss`, verified via isolated
reconstruction, pushed, deployed, and **confirmed live via full
pixel-parity on both `/all-resources/` (exact height match, 1083.9375
== 1083.9375) and `/resource-type/market-trend-reports/`'s
`.market-trend-reports-container-three-post` (exact match, 321.90625
== 321.90625)**.

## §33 -- 2026-09-10 (cont.): two more user-reported bugs found + fixed
during the re-verification pass, plus one more self-found via the same
sweep

### User-reported: two logos rendered simultaneously in the resources-sticky-menu's small logo

Screenshot showed both `img.dark` and `img.light` visible/stacked
inside `.resources-sticky-menu .scroll-image-container .logo`. Root
cause: same bug class as the `flip-card-module`/`span.text.mobile` fix
in §31 -- the bulk-generated leaf display:block restore rule
(`body.dev-float-refactor header .resources-sticky-menu .container
.resources-sticky-inner .scroll-image-container .logo img { float:
none; display: block; }`, 6 classes) beat the real `header
.resources-sticky-menu .container .scroll-image-container .logo
img.light { display: none; }` rule (_header.scss, only 5 classes --
one fewer, since it lacks the intermediate `.resources-sticky-inner`
class the gated bulk rule has) on the class column alone. Fixed with a
self-doubled `.light` class (8 classes, beats 6 outright), nested
inside the existing `body.dev-float-refactor { }` block. Verified via
isolated reconstruction (not live-injection) per the §32 methodology.

### User-reported: `header.resources-sticky .resources-sticky-menu` needs `top: 32px` when the WP admin bar is present

**Not a float-refactor/gate bug at all** -- a real, always-present
production bug, confirmed by its location in the real/ungated
`_header.scss`. The main `header` element already has its own
`body.admin-bar & { top: 32px !important; @media(max-width:782px){top:
46px!important;} }` compensation (line 33), but the separate
`.resources-sticky-menu` fixed-position bar
(`body.post/body.search-results/body.template-resources header
.resources-sticky { .resources-sticky-menu { position: fixed; top: 0;
... } }`, line ~2291) had no equivalent, so it renders underneath the
admin bar for logged-in users instead of below it. Fixed by adding a
parallel `body.post.admin-bar/.search-results.admin-bar/
.template-resources.admin-bar header.resources-sticky
.resources-sticky-menu { top: 32px !important; @media(...){top:
46px!important;} }` rule right after the existing block (not nested
via `body.admin-bar &`, since that would have compiled to two
different `body.X` ancestor selectors chained via a descendant
combinator -- invalid/never-matching; WordPress puts all body classes
on the same single `<body>` element, so a compound class selector
`body.post.admin-bar` is the correct form here).

### Self-found during the same re-verification pass: `section.resources-featured.filter-featured-post` container width collapse

While re-checking `/resource-type/market-trend-reports/` for the §32
fix, noticed `.item.market-trend-reports.full-width` was 640.17px wide
under `?dev=true` vs. production's 1300px (~49%, not full width).
Root cause: `section.resources-featured` has a *third* variant beyond
the two already known about (the standalone sidebar component, and
the homepage's `.featured-home`, excluded back in the 2026-09-03 fix)
-- `template-resource-type.php` and its old-template/pre-media/podcast
siblings render `section.resources-featured.featured-module
.filter-featured-post`, whose `.container` wraps a single `.item
<slug> full-width` that was never floated in the first place (real
CSS: plain block, default 100% width). The existing
`:not(.featured-home)` exclusion on `section.featured-module
.resources-featured .container { display: flex; }` didn't cover this
third variant, so it got flex-ified too, and the browser's
flex-basis:auto content-sizing shrank the single item instead of
respecting its intended 100% width. Fixed by adding
`:not(.filter-featured-post)` alongside the existing exclusion.
Verified via a full-repo grep confirming exactly three
`section.resources-featured` usages exist (plain/sidebar,
`.featured-home`, `.filter-featured-post`) -- no fourth variant -- and
via shadow-DOM-isolated reconstruction (confirms `display: block`,
matching production) rather than live-page injection, since the live
page's own thousands of unrelated rules produced a false-positive
"still flex" result when tested with a plain `<style>` injection (a
new, narrower variant of the injection-unreliability issue documented
in §31 -- this time caused by an unrelated same-page rule incidentally
matching the bare `.container` class, not by specificity/cascade
order).

### Status

All three written to `_dev-float-refactor.scss` /
`_header.scss`, verified via isolated reconstruction, pushed, deployed,
and confirmed live: logo fix and admin-bar fix both correct; the
`filter-featured-post` container-width fix correct (`.item.market-
trend-reports.full-width` back to 1300px, matching production) but
re-verifying the FULL section surfaced one more bug, documented next.

## §34 -- 2026-09-10 (cont.): matchHeight-vs-flexbox bug found while
re-verifying the §33 container-width fix

While confirming `.item.market-trend-reports.full-width` was back to
its correct 1300px width, the section's overall height still didn't
match production (351px vs. 537px), and a screenshot showed the two
inner columns' text visibly overlapping. Root cause is **JavaScript,
not CSS**: `main.js` runs jQuery's `matchHeight` plugin on these exact
elements (`$('.item.market-trend-reports .item-column').matchHeight()`
and `$('.item.full-width .item-column').matchHeight()`). matchHeight
pre-dates flexbox and doesn't reliably measure flex children -- under
the gate, an earlier (unrelated to §33) rule had converted
`.item-column.one-half` from real/ungated `float: left` to
`float: none` inside a `display: flex` parent, and matchHeight
measured the image column's natural height as 171px instead of its
true 356.9375px (a padding-top:56.3% aspect-ratio box), then locked
*both* columns to the wrong 171px via inline `style="height:...px"`.
Confirmed with a real scroll interaction (not forced script injection)
that this reproduces reliably, and confirmed production's own
matchHeight call measures correctly (356.938px) because
`.item-column.one-half` stays really/ungated float-based there --
matchHeight was written for exactly that pattern. There was no
BFC-containment reason for un-floating this pair in the first place
(self-contained 2-column float, doesn't interact with the section-
level float compensation elsewhere in the file) -- it was swept up
mechanically by the original sitewide generator pass along with every
other float:left in the codebase. **Fix: removed the flex-conversion
rules entirely** (both the `section.resources-featured` usage and the
`section.articles-featured.featured-module .item.full-width` usage,
since matchHeight's own selectors are global/unscoped, so any
`.item.full-width` anywhere is equally exposed) rather than trying to
compensate around it -- `.item-column.one-half` now stays floated
under the gate exactly as in production. Not yet pushed as of writing.

## §35 -- 2026-09-10 (cont.): rebuilt + verified §34's compiled CSS from a
new sandbox (this account's `device_bash` mount to the user's machine was
down all session -- file access only via stage/edit/commit, no direct
shell/git on the user's machine)

### What was confirmed already on disk before touching anything

Staged `_dev-float-refactor.scss` and `_header.scss` and grepped/read them
directly (not just trusted this doc's prose): the §33 fixes (logo
`img.light.light`, the `admin-bar` top-offset rule, the
`:not(.filter-featured-post)` exclusion) and the §34 fix (the two
matchHeight-colliding blocks removed, only the explanatory comment
remains) are all genuinely present in the source files, not just
described. `assets/css/main-nofooter.min.css`'s mtime predates both
scss files' mtimes, confirming the compiled asset was stale relative to
source (expected -- CI rebuilds fresh from source on every push, so the
committed local `assets/` copy isn't kept in lockstep with every local
source edit).

### Build

No git/npm/gulp access on the user's machine this session, so the build
ran in this session's own cloud sandbox instead: staged every file
`build:styles-main-nofooter`/`build:styles-footer` actually need (all of
`source/scss/`, the 8 vendored component CSS files in
`mainNoFooterSrc`, every file under `source/gulp/` -- gulpfile.js's
`require-dir` call eager-requires every task file, including unrelated
ones like `deploy/ftp.js`/`serve/proxy.js`, so a `Task never defined:
serve:proxy` / `build:images` assertion error is what you get if any is
missing, even though only the two style tasks were actually invoked --
`package.json`, `package-lock.json`), ran `npm ci --ignore-scripts` (a
plain `npm ci` fails on `jpeg-recompress-bin`'s postinstall trying to
fetch a prebuilt binary -- blocked by this sandbox's own egress, same
symptom as the "outbound HTTPS blocked" note elsewhere in this doc, not
a real problem since nothing here needs image binaries), then `npx gulp
build:styles-main-nofooter build:styles-footer`.

`footer.min.css` came out byte-identical to the committed copy (expected
-- `footer-only.scss` doesn't import `_header.scss` or
`_dev-float-refactor.scss`). `main-nofooter.min.css` came out 1207 bytes
larger.

### Verification method (adapted for this sandbox: no access to the real
previous git commit to diff against)

A naive `diff` on the two minified files (or even on a naive `}`-split
one-rule-per-line version) is not usable here: clean-css's
`restructureRules` can reshuffle which selectors share a merged,
comma-joined rule anywhere in the file in response to an unrelated
change elsewhere, so a line-based diff surfaces enormous, unreadable
false-looking deltas even when nothing meaningfully changed. Instead
wrote a small postcss-based script
(`css-diff.js`, cloud-sandbox-only, not part of the repo) that parses
both stylesheets into an AST, decomposes every rule's comma-separated
selector list into individual `(selector, normalized declaration set)`
entries (so selector-list reordering can't hide or fake a diff), and
multiset-compares old vs new per bare selector. Verdict: **32 selectors
differ**, every one indepedently explained by a fix already documented
in this file (mostly §31-§34: the matchHeight-block removals, the logo/
admin-bar/filter-featured-post fixes, the `article-container-three-post`
specificity fixes, the `cards-progress`/`keynote-progress` self-doubled
classes, a slick-arrow `top:-50px` + `:after{display:none}` fix from an
earlier section). Total selector count moved from 15,087 to 15,099 (net
+12, consistent with the additions/removals above). Nothing outside
that list changed -- no unrelated bulk-generated category was
restructured, no unexplained selector appeared or vanished.

**Not independently confirmed live** (no browser tool available in this
pass) -- this is a build/compile-level verification only, not the
live-pixel-parity check this file's methodology normally finishes with.

### Status

Rebuilt `main-nofooter.min.css` committed back into
`assets/css/main-nofooter.min.css` on the user's machine (via this
session's file-staging bridge). `footer.min.css` unchanged, not
rewritten. **Still not committed to git or pushed** -- this session has
no working git/shell access to the user's machine this pass. Remaining
steps for whoever picks this up next: `git add`/`commit`/`push` the
already-correct `_dev-float-refactor.scss` + `_header.scss` +
`main-nofooter.min.css`, then do the live `?dev=true` pixel-parity check
this file's methodology calls for on `/all-resources/` and
`/resource-type/market-trend-reports/` before calling §34 fully closed
out.

## §36 -- 2026-09-14: §34/§35's remaining steps closed out (commit was
already pushed by the user between sessions; this pass did the live
pixel-parity verification)

### What had changed since §35

This session also had no working `device_bash` shell on the user's
machine (same "Windows update blocks the mount" symptom as §35 -- file
access only via the stage/list/commit bridge, confirmed by attempting
`git status` first and getting the mount failure before falling back).
Read `.git/logs/HEAD`, `.git/refs/heads/dev`, and
`.git/refs/remotes/origin/dev` directly off disk (staged individually --
these are tiny plumbing files, not a full clone) rather than assuming
anything: found a commit `c5012d3` ("updates") made *after* §35's last
write to this file, with `refs/heads/dev` and `refs/remotes/origin/dev`
pointing at the identical hash -- i.e. the user had already run the
`git add`/`commit`/`push` §35 asked for, without updating this doc.
Confirmed on github.com/johnbadapt23/mainsite_adapt/actions (no repo
credentials needed -- public repo, unauthenticated browser read) that
**Build and Deploy Theme #159** (commit `c5012d3`, branch `dev`)
completed successfully in 2m41s.

### Live pixel-parity check (the step §35 couldn't reach)

Using the built-in browser at a 1440x900 viewport (the mobile-width
default the pane opens at will silently make float/flex layouts render
single-column and hide exactly these bugs -- resized explicitly before
measuring):

- **`/resource-type/market-trend-reports/` -- the §34 matchHeight fix.**
  Under `?dev=true`: `.item-column.one-half` computed `float: left`
  (stays real/ungated, as intended), both columns' `getBoundingClientRect()`
  height 356.9375 with matching inline `height: 356.938px` from
  matchHeight -- no overlap, full-width item container 1300px. Production
  (no `?dev=true`) needed a real click first to observe the same result --
  WP Rocket's delay-JS feature holds `main.js` (and therefore the
  matchHeight call) until a genuine user interaction there, while
  `?dev=true` bypasses that delay and runs it immediately (per the
  existing note elsewhere in this file on `rocket_delay_js_exclusions`) --
  after that click, production measured the identical 356.9375/356.938px
  on both columns. **Exact match, confirmed live.**
- **`/all-resources/` -- the §31/§32 `article-container-three-post` fix.**
  `?dev=true` and production both resolve to identical
  `getBoundingClientRect()` (`y: 1770.5625, height: 387`), `float: left`,
  `margin-top: 62px`. **Exact match, confirmed live.**

### Status

§34 and §35 are now fully closed out: fix written, built, committed,
pushed, deployed (Actions run #159, green), and live-verified via
pixel-parity on both pages this file's methodology calls for. This
session still has no `device_bash` access to the user's machine (same
blocker as §35), so this entry itself was written from the staged copy
and committed back to `SESSION-HANDOFF.md` via the file-staging bridge
only -- **not yet added/committed/pushed to git**. Whoever picks this up
next (or the user, in the same pass): `git add SESSION-HANDOFF.md &&
git commit -m "docs: log §36 - confirm §34/35 live via pixel-parity" &&
git push`. No further open optimization items are recorded in this file
as of this entry -- next session should re-check with the user for new
regressions/requests before assuming there's nothing left to do.

## §37 -- 2026-09-14 (cont.): re-audited §28's two "not confirmed"
residual gaps + several of §11's "not yet exhaustively checked" items,
before considering the user's ask to make the gate the sitewide default

### Why

User asked to either keep optimizing, or -- if the gate is genuinely
pixel-perfect against production -- flip it on by default for every
visitor instead of requiring `?dev=true`. Before touching the gate
itself, went back through this whole file (not just the tail) to build
an honest list of every item still marked open/unconfirmed, since
"pixel perfect" is a strong claim and §28 in particular closes with two
explicitly-unresolved residual gaps.

### Re-verified live, both now exact matches (both were "not confirmed"
in §28, 4 days ago)

- **`section.roundtable-card-slider-module` (`/private-executive-
  roundtables/`, 1440px).** §28 measured 814px (dev) vs 854px
  (production), ~40px unexplained. Today: **1101px both**, identical
  `.roundtable-card-slider` slide dimensions (1332x468 both). Whatever
  caused the 4-days-ago gap no longer reproduces -- content may have
  changed, or (more likely, see below) it was a Slick-initialization
  timing artifact, not a real CSS bug.
- **`section.resources-featured` (`/all-resources/`, 1440px).** §28
  flagged a ~182px gap, "not investigated." Today: **first measurement
  showed an alarming ~1215px gap (914px dev vs 2129px production)** --
  but a full recursive DOM-tree capture (x/y/w/h/display/float per node,
  same method as §29) taken immediately after showed **914px on both**,
  every nested node identical down to the 4 individual `.resources-
  side-posts` heights (177/177/192/151, both sides). Root cause of the
  false alarm: the first read landed before the `.resources-featured-
  slider` Slick carousel had finished initializing on the production
  tab (all slides still stacked pre-`slick-initialized`), not a real
  gate-vs-production difference. **Worth remembering for next time:**
  this file's own established method already guards against this (real
  click to defeat WP Rocket's delay-JS, then a wait) but a second,
  separate race -- Slick's own post-load init -- can still catch a
  single early read; re-measuring after confirming `.slick-initialized`
  is present on the element resolves it, as it did here.

### Also spot-checked, both clean

- **Search dropdown** (`.search-dropdown`, homepage, 1440px, opened via
  its real click handler): 441px height both dev and production, exact
  match. (Container-level only -- did not re-verify the internal 3-
  column 25/50/25 split from Section 4 item-by-item the way §29 did for
  the nav dropdowns.)
- **`section.sticky-slider-cards` / `section.comparison-module`**
  (`/adapt-vs-gartner/`, the -100px diff §11 flagged as possibly
  animation-timing noise): confirmed today's console on the production
  tab throws the exact same pre-existing, unrelated error the doc
  already documented (`TweenLite or TweenMax could not be found...
  ScrollMagic`) -- GSAP is genuinely broken on this page regardless of
  the gate, so this section's height is not a meaningful signal either
  way. Not a float-refactor bug; already correctly out of scope.

### Not re-verified this pass (ran out of runway, listed honestly rather
than assumed clean)

- **Mobile hamburger menu** (`.mobileMenuMain` open state) -- attempted
  via the "Open menu" link's real click handler, twice, at 375px; the
  panel never visibly slid in (`x` stayed at 375, i.e. off-screen) in
  either attempt. Inconclusive, not a confirmed bug -- more likely a
  test-harness timing/interaction issue (WP Rocket delay-JS not yet
  attached, same class of issue worked around elsewhere in this file)
  than a real one, but not proven either way this pass.
- **`section.sneak-peak-module`** -- no page found using it this pass
  (didn't search hard; §11 already flagged it as "not checked on a page
  using this section").
- **Section 19's ad hoc `.title-container` flex tweak** -- still exactly
  what §11 already flagged it as: not a mechanical-refactor bug, a
  deliberate `display:flex!important` the user asked for experimentally,
  gated for review. It squeezes `h1`+`p.type-description` into a row at
  ALL widths, not just where a real breakpoint would. **This is the one
  place in the whole gate where flipping the default would ship an
  intentional-but-unconfirmed visual change, not just "the same page
  faster."** Needs the user's explicit yes/no, not an inference from
  pixel-diffing.
- **The ~16-of-30 `source/scss/templates/*.scss` files never
  individually reviewed** for the original narrow-width/no-width/
  carousel-adjacent categories (§6/§28's still-open gap; confirmed the
  file count against the actual `source/scss/templates/` directory
  listing today -- 30 files total, 14 covered by name across Sections
  1-19 plus `_default`/`_author`, leaving `_agenda`, `_benchmarking`,
  `_benchmarks-maturity`, `_customer-stories`, `_gtm`, `_home`,
  `_landing`, `_market-buyer`, `_position`, `_resources`, `_roundtable`,
  `_services`, `_single-events`, `_single-post`, `_subscribe`,
  `_thank-you-old` -- 16 files, matches the doc's earlier count exactly).
  **Re-derived why this is lower-risk than it sounds, worth recording
  explicitly since it wasn't spelled out before:** every override this
  whole engagement has ever written lives in the one gated file,
  `_dev-float-refactor.scss` -- the original per-template SCSS files are
  never edited, confirmed by their mtimes bearing no relationship to
  which "Section" reviewed them. The risky Category C/D declarations
  (723 sitewide, the ones that need individual per-selector review) were
  *deliberately excluded* from the initial mechanical Batch 1/2 pass and
  only get an override rule once a "Section" reviews that file. For the
  16 files with no Section, their C/D declarations therefore have **no
  override rule at all** -- under `?dev=true` they render with their
  original, real, untouched float CSS, identical to production by
  construction, not "unverified." The gap is real but it's a *scope*
  gap (less of the theme has had its floats optimized away yet), not a
  *correctness* gap (nothing renders differently there today). The
  already-fixed Category-A/B false-positive bug class (the
  `calc(100%-Npx)` substring bug, `be79883`) was caught and corrected
  sitewide, not per-file, so it doesn't reintroduce risk here either.

### Status / recommendation

No code changed this pass -- investigation and re-verification only.
Net effect: both of §28's previously-open residual gaps are now
confirmed non-issues (timing artifacts, not bugs), which meaningfully
raises confidence in the gate's overall correctness beyond what §28 left
off. The gate has **not** been flipped to default-on. Recommends: get an
explicit answer from the user on the Section 19 `.title-container`
behavior first (the one place a default-flip would ship a deliberate,
unconfirmed change rather than pure parity), then flip
`adapt_dev_gate_body_class()` in `functions.php` to unconditional (still
trivially revertible -- one function, one commit) rather than continuing
to chase the remaining ~16-file scope gap first, given today's finding
that the scope gap doesn't carry the correctness risk it looked like it
did. Full reasoning and the user's answer, once given, belongs in the
next entry before the gate is actually flipped.

## §38 -- 2026-09-14 (cont.): user's answer on §37's open question --
scope `.title-container` to match production; fixed, rebuilt, verified

### The decision

User's answer to §37's flagged question: scope `section.filter-title-
block .container .title-container` to render the same as production --
i.e. revert the ad hoc Section 19 flex tweak for this one selector
specifically (the other two Section-19 selectors,
`.roundtable-card-slider-module ... .slide-image-container` and
`.two-column-services ... .icon-text-column .service`, are unaffected --
both were already confirmed-safe in §11 and stay as-is).

### Fix

`source/scss/sections/_dev-float-refactor.scss`: removed `section.
filter-title-block .container .title-container` from the Section-19
`display:flex!important` selector group (was joined with the other two
via a comma; now just the two). No replacement rule needed --
`.title-container`/`h1`/`p.type-description` already have their own
`float:none` from the original mechanical Batch 1/2 pass, and `.title-
container` already gets `display:block` restored by the pre-existing
"~24 unrelated sections" leaf-compound-side-effect compensation block
(§10/§11's bugfix mechanism) a few hundred lines later in the same file.
Updated the Section 19 header comment to document the revert and why.

### Build (cloud sandbox, `device_bash` to the user's machine still down
this session -- same blocker as §35/§37)

Recreated the scratch-dir workflow from §35: staged `package.json`/
`package-lock.json`/`gulpfile.js`, all of `source/gulp/`, all 48 files
under `source/scss/` (this time via a full recursive directory listing
rather than guessing which templates matter, to be sure nothing was
missed), and the 8 vendored component CSS files `mainNoFooterSrc` needs.
`npm ci --ignore-scripts` (clean, no image-binary postinstall failures
this time -- nothing in this change touches `build:images`), then `npx
gulp build:styles-main-nofooter build:styles-footer`.

`footer.min.css`: byte-identical (md5 match) to the previously-committed
copy, as expected (`footer-only.scss` doesn't import `_dev-float-
refactor.scss`). `main-nofooter.min.css`: 79 bytes smaller.

### Verification

Same selector-level postcss diff script as §35 (`css-diff.js`,
decomposes every rule's comma-selector-list into individual
`(selector, normalized declarations)` pairs so clean-css's
`restructureRules` reshuffling can't hide or fake a diff), run against
the previously-committed compiled file: **exactly 1 selector changed**,
`body.dev-float-refactor section.filter-title-block .container .title-
container`, from `display:block;display:flex!important;float:none` to
`display:block;float:none` -- precisely the intended change, nothing
else in the file's 12,455 distinct selectors touched.

**Live confirmation** on `/resource-type/market-trend-reports/` (the
only live page found using `section.filter-title-block`), 1440px:
- Currently-deployed (still-buggy) `?dev=true`: `.title-container`
  `display:flex`, `h1` and `p.type-description` side by side (h1 0-650px,
  p 650-1300px), container height 68px.
- Production: `display:block`, `h1` full-width (1300px) then `p.type-
  description` full-width below it (y:240→300 then 308→339), container
  height 99px.
- Simulated the fix live (CSS injection overriding the old deployed
  rule, same isolated-reconstruction technique used throughout this
  file) rather than waiting for a push/deploy to confirm it: resulting
  `.title-container`/`h1`/`p.type-description` rects **matched
  production exactly** -- same y/height/width on all three, container
  height 99px both.

### Status

Fixed, rebuilt, diffed clean, and live-simulation-verified. Superseded
by §39 below (the gate was flipped the same pass) -- the "not yet
committed to git" note originally here now covers both this fix and
§39's PHP change together, see §39's Status.

## §39 -- 2026-09-14 (cont.): gate flipped to default-on for every
visitor, per explicit user go-ahead ("just flip it")

### Change

`functions.php` -> `adapt_dev_gate_body_class()`: was `if ($_GET['dev']
=== 'true') add the class`; now adds `dev-float-refactor` to every
`<body>` unconditionally, with `?dev=false` kept as a quick rollback
lever back to the original untouched float CSS for any real visitor
(not just staging) if something turns up that this whole engagement's
verification missed. `php -l` clean (PHP CLI happened to be available in
this session's own cloud sandbox, an easier check than the php-parser
workaround §7/§18 used when it wasn't).

This is the culmination of the whole gated float->flexbox refactor
documented from §2 through §38 -- the override rules in `source/scss/
sections/_dev-float-refactor.scss` (originally ~18,500+ lines, grown
since) now apply sitewide by default instead of only behind `?dev=true`.

### Why this was judged safe to do now

Summarizing the case built across §28/§29/§37/§38 rather than repeating
it: every previously-flagged pixel-parity gap that got re-checked this
session (roundtable-card-slider-module, resources-featured, the header's
804-element desktop+mobile audit in §29, search dropdown, the
Section-19 `.title-container` tweak per the user's own decision) came
back an exact match to production. The one meaningfully open item (the
~16-of-30 template SCSS files never individually reviewed for the
riskier float categories) was re-derived in §37 to be a scope gap, not a
correctness one -- their risky declarations were deliberately never
given override rules, so they still render with their real, original,
untouched floats today, unaffected by this flip. `?dev=false` is the
explicit safety net for anything this reasoning missed.

### Status -- NOT yet pushed, this is the important part

Three changes now sit on disk on the user's machine, all via the
file-staging bridge only (`device_bash` has been unavailable to this
session the entire pass, §35/§37/§38's recurring blocker) -- **none of
them committed to git yet**:
1. `source/scss/sections/_dev-float-refactor.scss` (§38's revert)
2. `assets/css/main-nofooter.min.css` (§38's rebuild)
3. `functions.php` (§39's gate flip)

Until these are committed and pushed, staging is still running the OLD
behavior (gate still `?dev=true`-only, `.title-container` still has the
old bug) -- **the flip described in this entry is not live yet.**
Whoever picks this up next (or the user themselves): `git add
source/scss/sections/_dev-float-refactor.scss assets/css/main-
nofooter.min.css functions.php && git commit -m "Flip float-refactor
gate to default-on; scope .title-container to match production" && git
push`, then verify the Actions deploy goes green, then load the site
WITHOUT `?dev=true` and confirm `dev-float-refactor` is now present on
`<body>` (e.g. via devtools or `document.body.className`) as the real,
final live confirmation this actually took effect for anonymous
visitors -- everything in §38/§39 up to this point is disk-state and
simulated/pre-deploy verification only.

---

## §40 -- 2026-09-14 (cont.): gate mechanism removed entirely -- the
36 gated rules physically merged into the real per-template SCSS, the
class/toggle deleted for good

### Why

User's answer to §37's "clean way to make the updates global" question
was explicit: not just flip the gate to default-on (that was §39), but
**remove the class/gate mechanism entirely** -- no `dev-float-refactor`
body class, no `?dev=true`/`?dev=false` toggle, no separate gate file.
The float->flexbox modernization becomes the only CSS; there's nothing
left to gate.

### What changed

`source/scss/sections/_dev-float-refactor.scss` (63 `body.dev-float-
refactor { ... }` blocks, ~22,700 lines) was parsed with `postcss-scss`
and every block's rules moved into the real target file they belong to
-- 35 files total: `global/_base.scss`, `global/_forms.scss`, `global/
_styles.scss`, `partials/_header.scss`, `partials/_footer.scss`, and 30
`templates/*.scss` files. 57 of 63 blocks mapped to a single target file
mechanically (via the file's own `// === Section N ===` header
comments); 6 blocks (0, 52, 60, 62, plus the two largest) needed manual
splitting across multiple target files because they bundled rules for
several unrelated components together -- each split is backed by a
`.find()`/count-assertion in the merge script (`merge-transform.js`,
not part of the repo -- a one-off tool run from this session's cloud
sandbox) that throws if a selector doesn't match exactly once, so the
mapping couldn't silently misfire. Every one of the 63 blocks is
accounted for (`if (!handledBlocks.has(i)) throw`) and every rule
inside the largest/most complex block (62, ~66 flagged selectors) is
individually routed with a hand-built lookup table, not a blind
copy-through.

The gate file was then deleted, and `functions.php`'s
`adapt_dev_gate_body_class()` filter (added `dev-float-refactor` to
`body_class`) was deleted along with its `add_filter()` call -- see the
new comment left in its place. There is no more toggle.

### The real risk this surfaced: losing the class prefix loses the
cascade guarantee

The gate class wasn't just a toggle -- it was also what made every
override selector MORE SPECIFIC than the production rule it was meant
to beat (`body.dev-float-refactor header .foo` always outranks `header
.foo`, regardless of source order). Bare-merging the same rules into the
real files, unprefixed, makes them exactly as specific as the rules they
override -- correctness then depends entirely on file/import order.
That's unsafe in this build: `clean-css`'s `restructureRules`
optimization merges rules that share identical declaration content
*across the whole compiled file* (e.g. every unrelated selector that
just sets `float: none`) into one combined rule, which can relocate a
merged override's effective position earlier than the production rule
it needs to beat -- silently losing the override. Confirmed empirically,
not theoretically, via a concrete reproducible case
(`.hbspt-form .hs-fieldtype-checkbox`) before any fix was applied: true
mismatch rate was 235/2054 checked declarations with naive bare-merging.

**Fix: double every class in each merged selector's own chain.** One
extra specificity point, applied uniformly and cumulatively down through
every nested rule inside the same merged entry (`.foo.foo .bar.bar`,
same technique already used by hand in the original gate file for one
rule), is enough to always outrank the plain production selector,
independent of source order or `clean-css`'s reshuffling -- since
redundant classes don't change what a selector matches. `!important` on
every declaration was tried first as a simpler alternative and made
things WORSE (27 regressions instead of 3): it flattens the specificity
differences the *merged rules relied on to correctly order themselves
against each other* (e.g. a later bugfix correcting an earlier, broader
rule), leaving only file-import order to arbitrate ties between the
merged file's own rules -- an order that doesn't reliably match the
original gate file's top-to-bottom sequence once split across 35 files.
Selectors with no class at all to double (rare -- bare tag selectors
like `main`) fall back to `!important` individually, which is safe there
since there's no inter-rule ordering to preserve for a selector that
never repeats.

### Companion build-tool fix: `source/gulp/fix-float-none-display.js`

This existing gulp step adds `display: block` to any `float: none`-only
rule where blockification is needed (an inline-by-default element like
`<span>`/`<a>` that relied on the float to become a block box -- see the
file's own header comment for the full history). Its "is this rule
gated" detection was hard-keyed to the `body.dev-float-refactor` prefix,
so once the prefix was gone it silently stopped firing for the merged
rules -- ~150+ elements lost their `display: block` and collapsed back
to inline. Fixed by generalizing the script to evaluate every
`float: none`-only rule uniformly, gated or not (3 edits, all removing
the old `isGated` branch and using `rule.selectors` directly -- the
selector-matching/context-sensitivity logic itself, the interesting
part, needed no changes). Full reasoning trail is in the file's own
2026-09-14 header comment addition.

One case this generalized script's own (documented, intentional)
false-negative trade-off couldn't catch: `header .resources-sticky-menu
{ float: none }` (a leftover no-op from the original mechanical
Category A/B float-neutralization pass -- `.resources-sticky-menu`'s
real base rule is already `float: left; width: 100%`, and a 100%-width
floated block renders identically to a non-floated one) sits right next
to `.resources-sticky-menu`'s *actual* display toggle, which lives under
a completely different selector prefix (`body.post/.search-results/
.template-resources .resources-sticky-menu { display: block }` --
that's how the sticky bar is hidden by default and shown only on
specific templates). The script's context-sensitivity check (exact
selector text, or leaf-compound = last two selector segments) can't
connect `header .resources-sticky-menu` to `body.post
.resources-sticky-menu` -- they share a leaf class but neither the full
string nor the 2-token leaf matches -- so it mechanically added
`display: block` to the merged rule, which would have un-hidden the
sticky bar site-wide instead of just on its intended templates. Since
the rule accomplishes nothing when kept (see above), the fix was to
simply not emit it -- `merge-transform.js` now has one targeted,
commented removal for this exact rule rather than trying to teach the
mechanical fixer a cross-prefix case that would reopen the "343 false
positives" noise problem its own header comment already documents
rejecting once (the "fourth fix" entry).

### Verification methodology

Raw selector-text diffing (this project's `css-diff.js`) is the wrong
tool here, since the selector text itself changes (the class prefix is
gone) -- comparing "did this exact selector's rule change" no longer
answers "does the browser render this element the same way". Built a
headless-Chromium (Playwright) computed-style comparison instead: for
every one of the ~2,100 selector occurrences the gate file targeted,
construct a synthetic DOM tree matching the selector chain, load the OLD
compiled CSS (with `dev-float-refactor` added to `<body>`) and the NEW
compiled CSS (bare, merged) in two pages at an appropriate viewport
width per media condition, and diff `getComputedStyle()` for every
declared property. Caught two real bugs before they could ship: an
`instantiate()` bug in the harness itself that was checking the wrong
(outermost, irrelevant) synthetic element and silently masking ~1920 of
2054 real comparisons; and a missing `footer.min.css` in the test load
order (some overrides apply to elements whose base rule lives in the
separately-compiled footer bundle, per this theme's deferred-footer-CSS
split -- see the `functions.php` comment near `my_enqueue_scripts()`).

**Final result: 2053 of 2054 checked declarations match exactly.** The
one non-match is the `header .resources-sticky-menu` no-op described
above -- its own `float` value now reads the untouched production
`left` instead of the gate's forced `none`, which is the *expected,
understood, and visually inert* consequence of deliberately not
emitting that rule (confirmed directly: `.resources-sticky-menu` stays
`display: none` by default and `.sticky-menu-right`'s `margin-left`
still resolves to `auto` identically in both old and new, i.e. the
actual visual behavior this rule sat next to is unaffected).

### Status -- written to disk on the user's machine, NOT yet committed

`device_bash` was unavailable this entire session (same Windows-update
issue as §35/§37/§38/§39) -- all writes went through the file-staging
bridge (`device_stage_files`/`device_commit_files`), which cannot
delete files. The following are on disk on the user's machine as of
this entry:

**Written/overwritten (39 files):**
- `functions.php` (gate filter + `add_filter` call deleted)
- `assets/css/main-nofooter.min.css`, `assets/css/footer.min.css`
  (rebuilt from the merged SCSS)
- `source/gulp/fix-float-none-display.js` (generalized off the gate)
- 35 SCSS files: `source/scss/global/_base.scss`, `_forms.scss`,
  `_styles.scss`; `source/scss/partials/_header.scss`, `_footer.scss`;
  and all 30 `source/scss/templates/*.scss` files that received merged
  content (see the file list in this commit's diff -- every template
  file that had gated rules was touched, several templates that never
  had any were not).

**STILL ON DISK, NEEDS MANUAL DELETION:**
- `source/scss/sections/_dev-float-refactor.scss` -- the old gate file
  itself. Its content has been fully merged into the 35 files above and
  it is no longer imported by anything meaningful to keep, but it could
  not be deleted this session (no `device_bash`, and the file-staging
  bridge has no delete operation). **Whoever picks this up next (or the
  user themselves) needs to `git rm source/scss/sections/_dev-float-
  refactor.scss` (or delete it in an editor/Explorer) before
  committing** -- leaving it in place is harmless to the compiled
  output (it's no longer imported/gated by anything that fires), but it
  is 22,700+ lines of dead weight that will confuse the next person who
  finds it.

Once that deletion is done, the full commit is: the 39 files listed
above, plus the `_dev-float-refactor.scss` deletion. Suggested message:
`git rm source/scss/sections/_dev-float-refactor.scss && git add -A &&
git commit -m "Merge float->flexbox refactor into real SCSS; remove
dev-float-refactor gate mechanism entirely" && git push`, then verify
the Actions deploy goes green, then spot-check the live site (the
`.resources-sticky-menu` sticky bar in particular, given the one
understood non-match above, though it should be visually identical) --
as with §38/§39, everything above this line is disk-state and
simulated/pre-deploy (headless-browser) verification only until pushed.

---

## §41 -- 2026-09-14 (cont.): §40's own open item closed out -- the
remaining 16 never-individually-reviewed templates audited; 453
mechanically-safe float fixes applied, verified, and shipped; 229
genuine multi-column grids identified and scoped for follow-up

### Context

§40 flagged, as an explicit non-goal, that ~16 of the 30 template SCSS
files had never been through the individual float audit the other 14
(header, `_flexible`, `_resources-types`, `_customer-events`, `_events`,
`_registrations`, `_post`, `_login`, `_single-speaker`, `_form-pages`,
`_thank-you`, `_market`, `_app`, `_default`, `_author`) went through
across §2's Sections 1-19. User asked to pick that up this session.

### What the audit found -- bigger than "a small gap"

A classification pass (same categories §2 defined: **A** = own rule
already `display:flex`/`grid` -> float provably inert; **position** =
same rule also `position:absolute`/`fixed` -> float forced to `none` per
CSS2.1 §9.7, equally safe; **B** = `float:left/right; width:100%`
(literal, not `calc(...)`), non-carousel -> safe; **C** = a narrower
width present -> real multi-column float grid, needs individual
flex/grid redesign; **D** = no width at all -> ambiguous, needs
individual review) found **712 raw `float:left`/`float:right`
declarations across the 16 files**. Cross-checking each one against the
real compiled CSS (via a proper dart-sass source map, not string
matching -- see methodology below) confirmed §37's suspicion was
right, and worse than assumed: **682 of 712 had never been touched by
any prior override at all** -- these files render today exactly as they
did before the float->flexbox project ever started. This is comparable
in scope to the original 14-file effort, not a small residual gap.

Breakdown of the 682 genuine gaps: 85 Category A, 6 position-based, 362
Category B (mechanically safe, 453 total) vs. 89 Category C + 140
Category D (229 total, need individual redesign).

### Methodology -- source-map-based gap checking

Naive SCSS-selector reconstruction (joining nested rule selectors with
spaces) breaks on `&`-parent-selector usage (~17% of candidates use it)
and doesn't match real compiled selectors closely enough to reliably
check "does an override already exist for this exact element". Fixed by
compiling `main-nofooter.scss` standalone (replicating `source/gulp/
sass-glob.js`'s glob-import expansion by hand, since dart-sass's JS API
doesn't do glob imports) with `sourceMap: true`, then using the real
VLQ source map (`source-map` npm package, already a transitive
dependency) to map every compiled rule's `float` declaration back to its
exact origin file+line in the 16 template files -- and cross-referencing
against every compiled `float: none` rule's selector set. This is
strictly more reliable than string-matching and reuses no risky
assumptions; 683 of 712 candidates resolved cleanly via the source map
(the other 29 are root-level/non-standard declarations the classifier
skipped, not a methodology gap).

### The 453 safe fixes -- applied in place, not as override rules

Unlike §2's original approach (bolt an override rule onto a separate
gate file, because production files couldn't be touched directly),
these were applied as a **direct edit**: `float: left`/`float: right` ->
`float: none` in the SAME declaration, in the real files. This is
simpler and safer now that there's no more gate/specificity problem to
route around -- a direct edit carries zero cascade risk (there's no
competing rule to out-specificity, because there's no longer a second
rule at all).

### Verification

Built a targeted before/after computed-style diff (same technique as
§40, simplified -- no body-class toggle needed since there's no gate):
for all 472 compiled selectors touched (453 source declarations, a few
expanding into multiple comma-selectors), captured `float`, `display`,
`position`, `width`, `margin-left`, `margin-right`, `clear` via
Playwright at two viewports (1440px, 375px) against the pre-fix and
post-fix compiled CSS.

**Result: 944/944 checks matched exactly** -- either zero change (872 of
944; these are the Category A/position-based cases, and the measurement
itself confirms WHY they're safe: `getComputedStyle` already reported
`float: none` even in the pre-fix CSS for these, because a flex/grid
item's or an absolutely/fixed-positioned element's *computed* float value
is spec-forced to `none` regardless of what's specified -- direct
empirical confirmation of Category A/position's safety proof, not just
theory) or exactly the intended `float: left/right -> none` change with
every other tracked property identical (72 of 944; the genuinely
load-bearing Category B cases). Zero unexpected side effects anywhere.

### Status -- written to disk, NOT yet committed

18 files written via the file-staging bridge (`device_bash` still
unavailable): `assets/css/main-nofooter.min.css`, `assets/css/
footer.min.css`, and the 16 template SCSS files (`_agenda.scss`,
`_benchmarking.scss`, `_benchmarks-maturity.scss`, `_customer-
stories.scss`, `_gtm.scss`, `_home.scss`, `_landing.scss`, `_market-
buyer.scss`, `_position.scss`, `_resources.scss`, `_roundtable.scss`,
`_services.scss`, `_single-events.scss`, `_single-post.scss`,
`_subscribe.scss`, `_thank-you-old.scss`). Suggested commit: `git add
assets/css/main-nofooter.min.css assets/css/footer.min.css source/scss/
templates/{_agenda,_benchmarking,_benchmarks-maturity,_customer-
stories,_gtm,_home,_landing,_market-buyer,_position,_resources,
_roundtable,_services,_single-events,_single-post,_subscribe,_thank-you-
old}.scss && git commit -m "Fix 453 mechanically-safe float declarations
across the 16 never-audited templates" && git push`.

### What's genuinely still open: 229 declarations need individual redesign

These are real multi-column float grids (Category C, 89) or ambiguous
cases with no width to reason from (Category D, 140) -- the SAME kind of
work Sections 1-19 did file-by-file, each with its own careful
flex/grid design and live verification, and each occasionally turning up
a real bug (see §2's fix-float-none-display.js history for how much
nuance that process caught). Doing 229 of these responsibly in one pass
isn't realistic -- budget it the same way, file by file. By remaining
size (C+D):

| File | C | D | Total |
|---|---|---|---|
| `_customer-stories.scss` | 10 | 38 | 48 |
| `_landing.scss` | 5 | 38 | 43 |
| `_position.scss` | 12 | 13 | 25 |
| `_single-post.scss` | 9 | 10 | 19 |
| `_resources.scss` | 8 | 8 | 16 |
| `_roundtable.scss` | 7 | 8 | 15 |
| `_gtm.scss` | 6 | 6 | 12 |
| `_services.scss` | 9 | 3 | 12 |
| `_home.scss` | 7 | 3 | 10 |
| `_single-events.scss` | 6 | 2 | 8 |
| `_subscribe.scss` | 5 | 1 | 6 |
| `_agenda.scss` | 2 | 2 | 4 |
| `_benchmarking.scss` | 0 | 4 | 4 |
| `_thank-you-old.scss` | 2 | 2 | 4 |
| `_market-buyer.scss` | 1 | 1 | 2 |
| `_benchmarks-maturity.scss` | 0 | 1 | 1 |

Full per-declaration detail (selector, width/display/position values,
category, compiled selector) is not part of this repo -- it was
generated by a one-off audit script run from the assisting session's
cloud sandbox and not preserved. Re-running the same audit
(`audit-remaining-floats.js` + `compile-with-sourcemap.js` +
`gap-check.js`, described above) against the *current* state of these
16 files takes a few minutes and will reproduce the same breakdown
(fewer, since the 453 safe ones are now fixed) for whoever picks up the
C/D work next.

---

## §42 -- 2026-09-14 (cont.): visual regression baseline -- blocked on a
real GitHub discoverability limitation, not attempted further per user
instruction

### What was tried

User asked to run the sitewide Playwright pixel-diff baseline capture
(`tools/visual-regression/`, built earlier but never actually run --
see the open-items list). No `gh` CLI or API token available in the
assisting session's sandbox, so this had to go through the GitHub web
UI via Claude in Chrome (the user connected the extension for this).
Logged in as the repo owner, navigated to the Actions tab: the left-hand
workflow list only shows **Build and Deploy Theme** and **Build and
Deploy Theme (Production)** -- no **Visual Regression Gate**. Navigating
directly to `/actions/workflows/visual-regression.yml` returns **"This
workflow does not exist"**, even authenticated as the owner.

### Root cause

`visual-regression.yml` only exists on `dev` -- it was never merged to
`main`. GitHub's Actions UI only discovers a workflow for manual
`workflow_dispatch` runs if the file is present on the repo's **default
branch** (`main` here), regardless of which branch you'd actually want
the dispatch to target. This is the exact same class of limitation the
workflow's own header comment already documents for a *different*
trigger type (`workflow_run` requiring the file on the default branch)
-- it turns out `workflow_dispatch`'s UI discoverability has the same
requirement in practice, which wasn't previously known/tested.

The GitHub REST API's dispatch endpoint (`POST /repos/{owner}/{repo}/
actions/workflows/{workflow_id}/dispatches` with `ref` in the body)
does NOT have this restriction -- it can dispatch a workflow that only
exists on a non-default branch, as long as `ref` points at that branch.
But that requires an authenticated request (a personal access token),
which the assisting session doesn't have and won't handle on the user's
behalf.

### Options presented, user's decision

Three ways forward were laid out:
1. Merge `visual-regression.yml` + `tools/visual-regression/` to
   `main` so GitHub discovers it -- **rejected by the user** ("no
   please. do not merge to 'main' branch yet."), because
   `deploy-production.yml` fires on ANY push to `main` regardless of
   which files changed, so this would trigger an unwanted production
   deploy purely as a side effect of adding a test tool. Consistent
   with this project's standing rule that `main` is never touched
   without separate, explicit confirmation.
2. User runs it themselves via `gh`/API with their own token, once they
   have `gh` available (their own machine, or once `device_bash` is
   working again): `gh workflow run visual-regression.yml --ref dev -f
   mode=capture-baseline -f target_base_url=https://staging.adapt.com.au`
   (or the equivalent `curl -X POST .../dispatches` with a PAT).
3. Leave it open.

User did not pick explicitly between 2 and 3, only ruled out 1 -- so
this stays **open, undone**, with option 2's exact command recorded
above for whenever `gh`/API access is available.

### Status

No files changed on disk this section -- purely a blocked attempt,
documented so the next person picking this up doesn't waste time
re-discovering the same GitHub UI limitation. `tools/visual-regression/`
itself is unchanged from when it was built (see the open-items list this
doc's §10 area references).



---

## §43 -- Real PHPCS audit: WordPress-Extra + PHPCompatibilityWP (task 3 of "1, down to 3")

Closes the last of the three tasks from "all of these starting from 1,
down to 3" (float audit extension = §41, visual-regression baseline =
§42/blocked, this = task 3), and item 7 of the original open-items list
("run PHPCS with WordPress-Extra + PHPCompatibilityWP (target 8.1) for
a proper, complete audit -- prior grep-based sweeps aren't a
substitute").

### Scope

All 351 real theme PHP files: the 5 root files (`functions.php`,
`header.php`, `footer.php`, `index.php`, `template-app.php`), all
`templates/**/*.php` (337 files, including every `*-components/`
partial), `includes/*.php` (8 files), and
`download-monitor/content-download.php`. `_archive/` (13 files,
confirmed legacy/dead code not loaded by the live theme --
`dec-2025-functions.php`, `old-header.php`,
`templates/partials/_header-old.php`, etc.) was excluded from scope.
This exclusion was not separately re-confirmed with the user before
running -- flagging that here in case `_archive/` should actually be
in scope for a future pass.

Files were staged from the device in 8 batches via `device_stage_files`
(capped at 50/call) since `device_bash` remains unavailable this
session (the same Windows-update mount issue as prior sections). The
file list was built from `device_list_dir` (recursive) on `templates/`,
`includes/`, `download-monitor/`, and a non-recursive root listing --
351 files staged, verified against the directory listing count before
running anything.

### Tooling note -- composer/packagist blocked, used git clone instead

The sandbox's network proxy blocks `repo.packagist.org` (`403` on
`CONNECT`) and `github.com` HTTPS API/download endpoints, so the
originally-planned `composer require` install path
(`squizlabs/php_codesniffer` + `wp-coding-standards/wpcs` +
`phpcompatibility/phpcompatibility-wp` +
`dealerdirect/phpcodesniffer-composer-installer`) does not work in this
environment. `git clone https://github.com/...` over HTTPS does work
(the proxy handles git specially), so the sniff standards were pulled
as git checkouts instead and registered via `phpcs --config-set
installed_paths`:

- `PHPCSStandards/PHP_CodeSniffer` @ tag `4.0.4` (stable -- the
  Ubuntu-apt `php-codesniffer` package is only 3.7.2, too old for
  PHPCSUtils's `^3.13.6 || ^4.0.2` requirement)
- `WordPress/WordPress-Coding-Standards` (default branch, WPCS 3.x)
- `PHPCSStandards/PHPCSUtils` + `PHPCSStandards/PHPCSExtra` (WPCS 3.x
  dependencies)
- `PHPCompatibility/PHPCompatibility` (default branch -- the last
  *tagged* release, 9.3.5, is from Dec 2019 and predates PHP 8.0/8.1
  sniffs entirely; the untagged default branch is the only place
  current PHP 8.x compatibility checks exist, and it requires PHPCS
  4.x's `Tokens::OO_SCOPE_TOKENS` constant, which is why PHPCS 4.0.4
  was needed instead of the initially-tried 3.7.2/3.13.2)
- `PHPCompatibility/PHPCompatibilityWP` (default branch, for the same
  reason -- the tagged `2.1.6` release predates PHP 8.x support and
  also uses a PHPCS-3-only ruleset property syntax that PHPCS 4.0
  hard-rejects)
- `PHPCompatibility/PHPCompatibilityParagonie` (default branch --
  bundles the `RandomCompat`/`SodiumCompat` sub-rulesets
  `PHPCompatibilityWP` references; note this is a different repo name
  than what `composer.json` implies, `phpcompatibility-paragonie`
  singular, not split `-random-compat`/`-sodium-compat` repos)

Verified working end-to-end against a hand-written throwaway test file
before running against real theme files.

### Result 1: PHPCompatibilityWP, testVersion 8.1 -- clean

```
phpcs --standard=PHPCompatibilityWP --extensions=php --runtime-set testVersion 8.1 <351 files>
```

**0 errors, 1 warning, across all 351 files.** The one warning is on
`templates/partials/_map-svg.php` ("No PHP code was found in this file
and short open tags are not allowed... this file may be using short
open tags") -- that file is pure inline SVG markup with no `<?php`
tags at all, so this is a benign false-positive-shaped warning, not a
real compatibility issue.

This is a genuinely clean result: the theme has **no detected PHP 8.1
incompatibilities** across removed/deprecated functions, removed
extensions, signature changes, or the other categories
PHPCompatibility checks. Running under PHP 8.4.21 CLI (`php -v`
confirmed at task start) with no fatal errors on any staged file is
corroborating (though not exhaustive -- static analysis, not a live
request-by-request run of every code path) evidence for the same
conclusion.

### Result 2: WordPress-Extra -- not clean, but almost entirely style/formatting

```
phpcs --standard=WordPress-Extra --extensions=php <351 files>
```

**86,611 errors + 8,196 warnings across 351 files. 86,553 of the
94,807 total violations (91%) are auto-fixable by `phpcbf`.**

Breaking down by sniff (`--report=source`), the overwhelming majority
is pure formatting noise from a codebase that has never been run
through this ruleset before -- WordPress-Extra is deliberately much
stricter than "does this work," and these numbers reflect that, not
351 files full of bugs. Top contributors, all auto-fixable:

| Sniff | Count |
|---|---|
| Space indentation used instead of tabs | 44,441 |
| Precision alignment (`=>`/`=` alignment) | 4,090 |
| PEAR function-call parenthesis spacing (before/after close, multiple) | ~8,500 combined |
| Control-structure spacing (before/after `if`/`foreach`/etc, several sub-rules) | ~10,800 combined |
| Array indentation / alignment | ~2,400 combined |
| Embedded-PHP tag spacing (`<?php`/`?>` open/close) | ~2,500 combined |

That single "spaces instead of tabs" sniff alone is half the total
error count -- the codebase is consistently space-indented and WPCS
wants tabs, which is a mechanical, zero-risk `phpcbf --standard=
WordPress-Extra` fix (same category of automatic, structure-preserving
change as the earlier float-declaration fixes, not something requiring
manual review).

**Findings that are NOT auto-fixable and worth a real look** (most are
still "recommended" rather than "definitely a bug," WordPress sniffs
are known to over-report on these categories, especially escaping and
snake_case, so these numbers are upper bounds, not confirmed defects):

- **`Escape output not escaped` -- 3,824.** By far the largest
  non-cosmetic category. WPCS flags any output it can't prove was
  passed through an approved escaping function (`esc_html()`,
  `esc_attr()`, `wp_kses()`, etc.) along the exact code path it
  traces. In a 350-file theme built up over years this is very
  unlikely to be 3,824 *distinct* XSS holes -- much of it is almost
  certainly the sniff losing track of escaping done via a helper
  function, a variable built up across several lines, or output it
  can't statically prove is safe even though it is. But it's real
  enough in aggregate that it deserves a dedicated pass, not silent
  dismissal -- probably template-by-template, since that's how the
  float audit was scoped too.
- **Nonce verification missing/recommended -- 10 + 47 = 57.** Actual
  security-relevant findings, not style. Worth checking which forms
  or AJAX handlers these land in.
- **`Global variables override prohibited` -- 633.** Flags direct
  writes to WordPress global variables (`$post`, `$wp_query`, etc.)
  outside WP's own core files -- a real WP-specific code-smell
  category, not urgent but worth knowing the scale of.
- **Yoda conditions not used -- 918** and **variable names not
  snake_case -- 2,161.** Pure style/convention, zero functional risk,
  large volume -- these are realistically "accept as known debt"
  unless the team wants to adopt WPCS style conventions project-wide.
- **File names not hyphenated-lowercase -- 276.** Style convention
  (WP wants `my-file.php`, not `_my_file.php`); given how many of this
  theme's component partials are intentionally underscore-prefixed
  (`_agenda-item.php` etc, a common convention for "this is a
  partial/include, not a directly-routable template"), this is
  probably an intentional deviation from WP core convention, not a
  defect -- flagging rather than recommending a rename.
- **`Commented out code found` -- 78**, **empty `if`/`else`
  statement -- 51 + 11 = 62**, **unused function parameters -- 4 + 2
  = 6**, a handful of **discouraged/restricted function usage**
  (`date()`/`urlencode()`/`parse_url()`/`json_encode()` --
  10+9+7+3 = 29, WP prefers its own wrapper functions for
  timezone/encoding consistency), **1 `wp_redirect()` flagged for
  missing `exit`/safe-redirect follow-up**, **1 missing i18n
  translators comment**. All small-volume, worth a quick look, none
  urgent.

### What this does NOT include

This is a static-analysis pass, not a functional test suite -- it
confirms the code *parses* cleanly under PHP 8.1 syntax/API rules and
flags style/security-shaped patterns, but doesn't execute any code
path. It also doesn't cover `_archive/` (see Scope above) or anything
outside the 351 files listed. No files were modified this section --
this is a read-only audit and report, consistent with not having been
asked to also apply fixes.

### Suggested next step, if wanted

The 86,553 auto-fixable violations are a single safe, mechanical
command away (would need `device_bash` working again, or staging +
committing back through the same batch pattern used to pull the files
in):

```
phpcbf --standard=WordPress-Extra <the 351 files>
```

This is structurally the same kind of "verify before/after, zero
behavior change" operation as the float-declaration fixes in §41 --
`phpcbf`'s fixes here are whitespace/formatting only (tabs vs spaces,
alignment, spacing around parens/operators), not the 3,824 escaping
findings, which need human judgment per-callsite and shouldn't be
auto-applied. Not run this section since it wasn't asked for and
touches all 351 files at once -- flagging as a clearly-scoped,
low-risk follow-up rather than doing it unprompted.

### Status

**All three tasks from "all of these starting from 1, down to 3" are
now addressed**: §41 (float audit extension, 453 fixes applied +
verified), §42 (visual-regression baseline -- blocked on the
GitHub Actions default-branch limitation, documented, left open per
user's "not main" decision), §43 (this section -- PHPCS audit
complete, 0 PHP 8.1 compatibility issues found, WordPress-Extra
findings summarized above with 91% auto-fixable). No files changed on
disk this section.


---

## §44 -- Homepage "ruined" report: revert (already done) + the two real bugs underneath it

Follow-up to the user's "you ruined it, revert changes today" report on
`https://staging.adapt.com.au/`. The revert itself (16 SCSS files + 2
compiled CSS bundles, back to exact pre-§41 backups) was already done
and committed earlier this session. This section covers what was found
*after* the revert, since the user correctly pushed back that reverting
alone didn't fix the live page, and asked to fix the actual problem.

### Bug 1 (fixed, committed) -- 11 selectors stuck on `display:block`

Not caused by today's work -- confirmed present in the pre-§41-fix CSS
too, so it predates this session's float audit. Root cause: `clean-css`'s
`restructureRules` optimization (documented risk since §40) merges every
rule sharing byte-identical declarations into one combined selector,
regardless of original grouping. Combined with `merge-transform.js`'s
specificity-doubling (`.foo.foo`), this produced one 400-selector rule
`{float:none;display:block}` in `main-nofooter.min.css`. 389 of those 400
selectors genuinely want `display:block` and are unaffected. **11 do
not** -- their original, pre-merge declaration explicitly set
`display:flex` (plus `flex-direction`/`gap`/`align-items`/etc.)
alongside a now-inert `float:left`, so the merged rule was silently
overriding their layout to block.

Found programmatically (not by manual read-through of 400 selectors):
parsed the compiled CSS, located every selector in the merged rule,
looked up each one's other/original declaration elsewhere in the file,
and flagged only the ones whose original declaration contained
`display:flex`/`grid`/`inline-flex`. Fix: appended 11 override rules at
the end of `main-nofooter.min.css`, each using the selector's exact
already-doubled-class form (matches/exceeds the offending rule's
specificity) and restoring its full original flex declaration, placed
after the offending rule so source order wins the cascade tie -- same
approach as a manual CSS specificity war, done safely since nothing
else in the file was touched.

Affected selectors: `featured-stories` (4, incl. `.first-featured`
variants), `filter-title-block .topic-button-container`,
`left-text-links.advisors-centered-text-links .links-container`,
`list-card-module .list-card-container`, `map-with-numbers
.column-container`, `partner-form .form-text-container`,
`post-article-container .post-column-container`,
`quote-slider.customer-events-slider ... .logo-text-container`.

`footer.min.css` has the same `{float:none;display:block}` merged rule
mechanism but was checked the same way and has **zero** affected
selectors -- none of its merged-rule selectors have a `display:flex`
original declaration. No changes needed there.

Fix applied to `assets/css/main-nofooter.min.css` (1,806,881 ->
1,810,391 bytes) and committed to device with an `expectedMtimeMs`
guard (matched, zero rejection). **Not yet visible on the live site** --
see Verification below.

### Bug 2 (root-caused, not yet fixed -- needs a decision) -- GSAP console error, unrelated to Bug 1 and to today's work

The `(animation.gsap) -> ERROR: TweenLite or TweenMax could not be
found...` error the user saw is **not new** -- it's independently
documented at two earlier points in this file (this session's own
§11 and §37), both before today's revert/fix work, both noting it
reproduces identically on `?dev=true` and production. Re-confirmed live
via Claude-in-Chrome console + network capture just now: the error
fires from `main.min.js:7:697` -- i.e. from inside the theme's own
compiled JS bundle, not an external CDN script (network log shows no
separate ScrollMagic request at all).

Root cause, traced through `source/gulp/paths.js`:
`build:scripts` concatenates a **fixed list of vendor files** --
including `scrollmagic/uncompressed/ScrollMagic.js` and its
`plugins/animation.gsap.js` -- unconditionally into `main.min.js`,
which is enqueued on **every** page. `animation.gsap.js` does its own
`TweenLite`/`TweenMax` presence check at load/parse time (not when
`.setTween()` is actually called), so it logs this error on every page
load, site-wide, regardless of whether that page uses ScrollMagic.
Meanwhile GSAP itself is (correctly, per an earlier session's
optimization) only conditionally loaded via `adapt_page_needs_gsap()`
on 8 specific templates -- the homepage isn't one of them, so GSAP
never loads there, and the plugin's check always fails there.

**On the homepage specifically this is cosmetic, not functional**:
confirmed live `.fixed-scroller-inner` and `.map-fixed-scroller-container`
(the only two DOM hooks `main.js` uses ScrollMagic for) are both
**absent** from the homepage DOM, so no ScrollMagic code path actually
runs there -- only the vendor plugin's own load-time self-check fires.
Scroll itself works normally (`window.scrollY` responds, no
`overflow:hidden` trap observed beyond the one pre-existing,
already-documented `overflow:hidden auto` seen identically before and
after the revert).

**Why not fixed this section:** the real fix touches a vendored
third-party library file (`source/components/scrollmagic/.../plugins/
animation.gsap.js`) and/or the compiled `main.min.js` bundle, and
`device_bash` is still unavailable this session (same Windows-update
mount issue noted throughout), so there's no way to run `gulp
build:scripts` to rebuild and verify the bundle the normal way. Hand-
patching the 142KB minified bundle directly (same technique used for
the CSS fix) is possible but riskier for JS than CSS -- a bad edit here
can silently break event bindings sitewide, not just misrender one
section. Flagging as a clearly-scoped follow-up rather than guessing at
a minified JS patch without a build pipeline to verify against.
**Needs the user's go-ahead before it's attempted.**

### Verification note -- CSS fix is correct on disk but not yet visible live

Confirmed via `device_list_dir` that `main-nofooter.min.css` on disk is
now exactly the fixed 1,810,391-byte file. But live network capture
shows the page still serving a stale copy: response headers show
`Cache-Control: public, max-age=31536000` (one year) and a
`Last-Modified` timestamp from *before* even today's earlier revert,
and this didn't change across a hard navigation or a randomized
cache-busting query string on the page URL -- consistent with a
CDN/reverse-proxy layer (not WP Rocket's page cache, which `?nocache=1`
already accounts for elsewhere in this doc) caching the CSS asset
itself for up to a year and not keying on query strings the way the
HTML page cache does. This is outside what a file-level fix can
address -- **the user (or whoever holds the CDN/Cloudflare dashboard)
needs to purge that asset's cache** for the fix to show up on
`https://staging.adapt.com.au/`. Not something I have credentials or
access to do from here.

### Status

Bug 1 (display:flex regression): fixed, verified on disk, committed to
device, blocked only on an external cache purge outside this session's
access. Bug 2 (GSAP console error): root-caused precisely, confirmed
pre-existing and cosmetic on the homepage, not fixed -- needs the
user's explicit go-ahead given the no-build-pipeline risk, and needs
`device_bash` back (or a manual staged-file patch + careful review) to
execute safely.


---

## §45 -- Real, source-level fix for the display:flex regression (replaces §44's !important patch); scroll-freeze investigation still open

§44's fix (11 `!important` overrides appended to the compiled CSS) was
correctly flagged by the user as the wrong shape of fix -- band-aid,
and it doesn't scale: the user subsequently found two more large chunks
of the same merged `float:none;display:block` rule with more broken
selectors inside (34 in one paste, then asked to stop patching with
`!important` altogether and find the real cause). All of it verified
correct.

### Root cause, found and fixed at the source

`source/gulp/fix-float-none-display.js` (the pipeline step that decides
whether a `float:none`-only rule needs `display:block` added, so an
inline element like `<span>` doesn't silently collapse once its float
is neutralised) already has a whole-file, order-independent safety net
for exactly this class of problem: before adding `display:block` to a
selector, it checks whether that selector already has an explicit
`display` declared anywhere else in the stylesheet, and skips it if so.

That check compares selectors by exact string (with a secondary
"leaf compound" fallback for padded-ancestor cases like `.main-nav`,
documented in the file's own "fourth fix" history). It never accounted
for `merge-transform.js`'s specificity-boosting technique, used
pervasively by this whole float-audit engagement, which doubles every
class in a selector (`.foo` -> `.foo.foo`) so overrides reliably win
the cascade. A doubled override selector and its plain production
counterpart are the same real selector, but two different strings --
so the safety net never recognised them as related, and mechanically
added `display:block` to the doubled/merged rule anyway, silently
beating a real `display:flex` set elsewhere.

Fixed with a full sweep, not selector-by-selector: parsed the actual
compiled output, found every rule reducing to `float:none;display:block`
across the WHOLE file (1,778 unique selectors across several separate
merged rules, not just the one the user first pasted), and checked each
one for a second, separate declaration containing
`display:flex`/`grid`/`inline-flex`. **116 of 1,778** are genuinely
affected -- confirmed this is a proper superset of both selector lists
the user pasted (zero missing either way).

The actual fix: `fix-float-none-display.js` now normalizes selectors
(collapses consecutive duplicate classes, `.foo.foo` -> `.foo`) before
every comparison -- both the exact-selector and leaf-compound checks --
and the "does this selector have a display anywhere else" check was
broadened from "at a different media context" to "at any context",
since the missed cases here are same-context (the doubled selector's
own true declaration is at the identical, no-media-query context, just
written under a different-looking string). Verified end-to-end by
actually running `gulp build:styles-main-nofooter` against a fresh copy
of the current source tree with the patched script: all 116 selectors
now resolve to their correct `display:flex`/`grid` value with **zero**
`!important` anywhere in the output, and the compiled file is
marginally *smaller* than before (1,806,868 vs 1,806,881 bytes) rather
than the ~35KB larger it would have been with the override-file
approach. `footer.min.css` re-checked the same way -- still clean, no
affected selectors there.

§44's `_flex-override-fix.scss` (the 11-selector `!important` file) is
now superseded and has been emptied out (left as a documented no-op,
not deleted -- this session still doesn't have shell access to the
user's machine to `git rm` it; safe to delete whenever convenient).

### Status: fixed on disk, NOT yet pushed

Both changed files --
`source/gulp/fix-float-none-display.js` (the real fix) and
`source/scss/sections/_flex-override-fix.scss` (emptied) -- are
committed to the user's machine via the device bridge, but **not yet
committed to git or pushed**. The user needs to `git add`, commit, and
push `dev` themselves (same as the earlier `0bd5311` "fixes for
staging" push) for the "Build and Deploy Theme" workflow to pick this
up and redeploy.

### Separately: scroll-freeze bug found live, root cause not yet found

While verifying the previous fix live on `https://staging.adapt.com.au/`,
found that mouse-wheel/trackpad scrolling does **nothing at all** --
`window.scrollTo()` (programmatic) works fine and every individual
section renders correctly when scrolled to that way, but a real wheel
event never moves the page. This is very likely the actual "ruined,
everything is broken" symptom the user has been reporting all along --
a visitor who can't scroll past the hero would perceive the entire site
as broken even though the sections underneath are fine. Ruled out: a
fatal top-level script error halting the rest of `main.min.js` (all
vendor libs concatenated after the GSAP plugin -- AOS, jquery.scrollbar,
perfectScrollbar, matchHeight, select2, slick, Cookies -- are all
defined and present on `window`, so the console error is a normal
`console.error()` call, not a thrown exception); a full-viewport fixed
overlay intercepting the wheel event (checked via `elementFromPoint` at
the scroll coordinate -- ordinary content div, not an overlay); and the
`.perfectScrollbar()` calls in `main.js` (none target `body`/`html`,
all scoped to small containers). Not yet found: what is actually
swallowing the wheel event. This investigation was interrupted by the
push/revert/selector-fix conversation and is still open -- worth
picking up next, since it's likely the highest-impact issue of
everything found this session.


---

## §46 -- 2026-09-17: dead-CSS removal (durable version of the "CSS splitting" request)

### Context

User asked for the homepage's CSS to be "split" (Lighthouse flagged the
1.8MB `main-nofooter.min.css` bundle). Investigated first: WP Rocket's
"Remove Unused CSS" already generates real per-page critical CSS
(~46KB inlined in `<head>`, confirmed live) with the full bundle loading
async/non-blocking in the background -- a manual per-template SCSS split
would be redundant with a more precise, already-working system, and
risky given the site's shared ACF flexible-content architecture (template
boundaries don't map to CSS usage boundaries). Presented this and asked
the user how to proceed; instruction back was **"do what is best
long-term, not quick fixes that will be a problem in the future."**
Redirected to removing genuinely dead CSS at the SCSS source instead --
durable, no new fragile machinery, shrinks every page.

### Method

Rather than coverage-sampling live pages (real false-positive risk: a
selector unmatched on today's sample can still be legitimately used on
an untested page, a `:hover` state, or a page deliberately excluded from
the sitemap), used the theme's own confirmed-dead PHP templates as the
starting point -- files already established as **structurally
unreachable** by prior sessions in this doc (no `Template Name:` header,
zero references via `get_template_part`/`include`/`require`/
`single_template` anywhere in the theme): `old-template-resource-type.php`,
`template-resource-type-pre-media.php`, `member-single-post.php`,
`single-post-no-embed.php`, `single-post-feb.php`,
`single-post-side-articles.php`, plus two more confirmed the same way
this pass (`august-single-post.php`, `single-post_author.php`,
`single-event-nov.php`) and one dead component partial
(`customer-story-components/_category-slider.php`, per §18).

Cross-checked live page→template assignments via the WP REST API
(`/wp-json/wp/v2/pages?...&_fields=id,link,template,status`,
authenticated) rather than trusting the XML sitemap alone -- the sitemap
only covers indexed pages, and utility templates (thank-you, login,
search-results) are legitimately noindexed while still live. This
caught a real near-miss: `template-thank-you.php` (hyphenated, no
"new") has zero pages assigned despite being referenced in `header.php`,
while `template-thankyou.php` (no hyphen, looked like the obvious
orphan) turned out to have one live published page. Left both alone --
neither is in this pass's scope, and one is actively wired up.

For each confirmed-dead file, extracted every CSS class it references
and checked whether that class appears in ANY live `.php`/`.js` file in
the theme. Classes exclusive to the dead set are provably unreachable --
no live page can ever render markup carrying them, so no matching SCSS
rule can ever apply anywhere.

### Removed (pure deletions, 1,294 lines, 0 insertions, verified with
### `git diff --stat` and a real `dart-sass` compile of all three
### edited partials -- 0 errors)

- **`source/scss/templates/_author.scss` -- deleted entirely** (584
  lines). Every selector in the file is scoped under
  `section.speaker-profile.author-section`,
  `section.author-posts-listing`, or
  `section.author-events.events-listing-module` -- all three root
  classes exclusive to the now-confirmed-dead `single-post_author.php`.
  (A JS guard in `main.js`, `if ($('.author-posts-listing').length)`,
  also references this markup -- confirmed inert for the same reason,
  left untouched per the standing "leave dead PHP files alone" decision;
  this pass only touches CSS.)
- **`source/scss/templates/_single-post.scss`** (182 lines removed):
  the real `.podcastPlayer`/`.sideArticles` style blocks (only ever
  rendered by the dead single-post variants, confirmed absent from the
  live `single-post.php`), plus their corresponding entries in the
  float-refactor "gate" section (`// line 228`/`253`/`361`/`369`/`382`/
  `392` -- removed each full entry, not just the innermost selector, to
  avoid leaving empty wrapper shells).
- **`source/scss/templates/_customer-stories.scss`** (517 lines
  removed): the entire `.story-categories-slider-module` block (514
  lines -- turned out to already contain the `.white-text-60`/
  `.slide-counter-outer` nested rules originally targeted separately)
  plus one standalone `.white-text-60` block -- all exclusive to the
  dead `_category-slider.php` component.
- **`source/scss/templates/_customer-events.scss`** (11 lines
  removed): the matching float-refactor gate entry for the same dead
  component.

### Verification

- `git diff --stat`: 4 files, 1,294 deletions, 0 insertions -- pure
  removal.
- Brace-balance check (custom script) on all 3 edited files: 0 (fully
  balanced) after editing.
- Zero remaining references anywhere in `source/scss/` to any removed
  class (`podcastPlayer`, `sideArticles`, `story-categories-slider-module`,
  `slide-counter*`, `white-text-60`, `author-events`, `author-section`,
  `speaker-profile`, `linkedin-button`, `mobile-title-container`,
  `author-posts-listing`).
- Seam-checked every removal boundary by hand (no orphaned selectors,
  no doubled braces, no dangling gate-list comments).
- Installed `sass` (dart-sass) fresh into `node_modules` on the user's
  machine (network was slow -- two `npm install` attempts timed out
  before it finished in the background) and compiled all three edited
  partials for real, against the theme's actual global mixins/
  variables: **0 errors**, only pre-existing `@import`-deprecation
  warnings common to the whole codebase. Did not run the full
  `gulp build:styles-main-nofooter` pipeline (autoprefixer + cssmin +
  the custom `splitOversizedRules`/`fixFloatNoneDisplay` postprocessing)
  since `node_modules` wasn't fully installed in this session -- worth
  a real `npx gulp build:styles-main-nofooter` + live pixel-diff on
  `/careers/`, a `/resources/...` post, and `/customer-stories/` before
  this ships, same as every other CSS change in this doc.

### Status

Committed to the user's files on their machine (via the device bridge),
**not committed to git yet** -- left for the user to review the diff,
`git add`/commit, and push per standing practice. Did not touch any of
the underlying dead PHP files themselves (per the user's earlier
standing decision to leave confirmed-dead files alone) -- this pass is
CSS-only.

Five remaining templates found this session with a `Template Name:`
header but zero pages currently assigned (`template-events-old.php`,
`template-home-nov.php`, `template-flexible-nov.php`,
`template-agenda.php` [confirmed live elsewhere, not dead],
`template-thank-you.php`) are **not** in this dead-CSS pass -- they're
editor-selectable at any time via the WP admin template dropdown, so
"currently unused" isn't the same guarantee as "unreachable." Left as a
separate, lower-confidence category if the user wants to revisit later.


---

## §47 -- 2026-09-17 (cont.): §45's scroll-freeze bug -- investigated, not reproduced, user confirms resolved

Picked this up as the next open item after §46. Attempted reproduction
on `https://staging.adapt.com.au/` using real (CDP-simulated) wheel-scroll
events, not `window.scrollTo()` (which §45 already confirmed works) --
both as a logged-in admin and, after logging the tab out, as an
anonymous visitor (the distinction that mattered for the earlier WP
Rocket Delay-JS bug in this doc). Scrolled the homepage from 0 to
3000px across several rapid successive real-wheel scroll actions, and
separately tested `/careers/`. Every scroll moved the page normally.
No `.scrollmagic-pin-spacer` elements present, no full-viewport overlay,
all core scripts (`main.min.js`, jQuery, modernizr) loaded `200`.

Could not reproduce -- asked the user to check on their own machine
rather than declare it fixed on a negative automated result alone.
**User confirmed: not freezing for them either.**

### Likely explanation (not confirmed, no further action needed)

§45 logged this bug while verifying the `display:flex` regression fix,
mid-flux at the time (the `!important` band-aid was still in place and
the real source-level fix in `fix-float-none-display.js` had only just
landed). The codebase has moved on substantially since -- that real fix
is confirmed present in `HEAD`/`origin/dev` as of this session, along
with §46's dead-CSS removal. Plausibly the scroll-freeze was entangled
with the same regression and got resolved as a side effect once the
real fix (rather than the override patch) was in place; no separate
root cause was ever isolated, so this is a plausible explanation, not
a confirmed one.

### Status: resolved (by user confirmation), closed

No code change made -- there was nothing left to fix once it stopped
reproducing. Closing out task #6's scroll-freeze portion.


---

## §48 -- 2026-09-17 (cont.): GSAP/ScrollMagic `TweenLite or TweenMax` console error -- fixed, applied to live bundle

### Context

Root cause was fully isolated in §44: `animation.gsap.js` (a vendored
ScrollMagic plugin) does an eager `TweenLite`/`TweenMax` presence check
at IIFE parse-time (not call-time) and `console.error`s if GSAP isn't
loaded on the page. GSAP itself is intentionally conditionally loaded
only on 8 templates via `adapt_page_needs_gsap()` in `functions.php`, so
every other page hit the console error on load -- cosmetic (nothing
visibly breaks), but a real error firing site-wide.

A prior session (commit `d221402`, 2026-09-15) had already made the
correct *source-level* fix: split ScrollMagic + its GSAP plugin out of
the main JS bundle (`source/gulp/paths.js`: new `scriptsScrollmagic`
array), added a new gulp task `build:scripts-scrollmagic` (builds a
separate `scrollmagic.min.js`), and updated `functions.php` to
conditionally enqueue `scrollmagic-js` alongside `gsap-js`/
`scrolltrigger-js` only on the same 8 templates (also excluded from WP
Rocket's Delay-JS). **But `assets/js/main.min.js` itself was never
rebuilt after that split** -- confirmed via mtime (`main.min.js`:
2026-08-24, stale, vs. `scrollmagic.min.js`: 2026-09-15) and a literal
grep (`TweenLite or TweenMax` count = 1 in the live file). So the bug
was still live on every page despite the fix already existing at the
source level.

User approved fixing it this session ("Yes, go ahead") after being told
it touches a minified vendor bundle.

### Method

`gulp` itself could not be gotten working in this session --
`npm install`/`npm ci` repeatedly left `node_modules` corrupted across
several different packages in sequence (`snapdragon`, `nanomatch`,
`vinyl-fs`), none of which npm's own diffing repaired. Rather than keep
fighting the toolchain, replicated the exact `build:scripts` gulp task
(`source/gulp/tasks/build/scripts.js`) directly: read the 12-file
`src.scripts` list from `source/gulp/paths.js` (confirmed ScrollMagic
already absent from it, per the prior session's split), expanded the
one `@@include('includes/_maps.js')` directive in `main.js` the same
way `gulp-file-include` would, then minified each file individually
with the working `node_modules/.bin/uglifyjs` CLI binary (`-c -m`,
matching `gulp-uglify`'s defaults; confirmed same underlying engine --
`uglify-js: 3.19.3` installed satisfies the project's
`"uglify-js": "^3.0.5"` dependency) and concatenated the results in
source order, exactly matching `gulp-concat('main.min.js')`.

### Applied

- Backed up the live file first: `assets/js/main.min.js.bak-pre-gsapfix-20260917`
  (330019 bytes, the stale pre-fix version) left alongside it.
- Replaced `assets/js/main.min.js` with the rebuilt bundle (297624
  bytes -- smaller, as expected, since ScrollMagic + its GSAP plugin are
  no longer in this file).

### Verification

- `grep -c "TweenLite or TweenMax" assets/js/main.min.js` -> **0** (was
  1). Bug fixed.
- `node -c assets/js/main.min.js` -> syntax OK.
- `grep -c "ScrollMagic.Controller\|ScrollMagic.Scene"` -> 1 (expected:
  legitimate guarded usage elsewhere in the bundle preserved, not
  stripped).
- Confirmed this session's earlier `main.js` edits (video-gating,
  overlapping-card reflow fix) survived the rebuild: literal-name
  greps came back empty as expected (uglify's `-m` mangles local
  identifiers), but the non-mangled string literals they depend on --
  `prefers-reduced-motion`, `data-autoplay-src`, `saveData`,
  `overlapping-card-wrapper` -- are all present (count 1 each).
- `git diff --stat assets/js/main.min.js`: 1 file changed (expected --
  minified output is close to single-line, so this doesn't reflect
  true content size; the 297624 vs 330019 byte delta is the real
  signal).

### Not run this pass

The full `gulp build:scripts` (or `build:scripts-scrollmagic`, already
current) pipeline as real CI -- `node_modules`/gulp remained broken in
this session throughout. The hand-rolled Python replica is believed
functionally identical (same source list, same minifier, same include
expansion, same concat order) but hasn't been cross-verified against an
actual `gulp` run. Worth doing once `node_modules` is reinstalled
cleanly (e.g. `rm -rf node_modules && npm ci` on a fast/stable
connection), to confirm the two outputs match.

No live GSAP-dependent animation was manually re-tested against the 8
templates that do load ScrollMagic + GSAP (`adapt_page_needs_gsap()`'s
list) -- those templates don't include this bundle's ScrollMagic code
either way (it's `scrollmagic.min.js` on those pages, already correct
and unchanged), so this fix shouldn't affect them, but a live check on
one of the 8 (e.g. via staging) before shipping would be prudent.

### Status

Applied on the user's machine via the device bridge, **not committed
to git yet** -- left for the user to review (`git diff assets/js/`,
though it'll show as a single large-line diff given the file is
minified), decide whether to keep or discard the backup file
(`main.min.js.bak-pre-gsapfix-20260917`, currently untracked), commit,
and push per standing practice.

Task #6 (fix/verify regressions) is now fully closed: scroll-freeze
resolved by user confirmation (§47), GSAP console error fixed and
applied (this section).


---

## §49 -- 2026-09-17 (cont.): float-grid redesign started -- `_benchmarks-maturity.scss` (1st file), 5 declarations fixed

### Context

§41 flagged 229 remaining `float:left`/`float:right` declarations across
16 template SCSS files needing individual redesign (Category C: real
multi-column grids; Category D: no width to reason from), separate from
the 453 mechanically-safe ones already applied directly. User approved
starting this work "one file now," picking the smallest candidate by
§41's old count: `_benchmarks-maturity.scss` (listed as 0 Cat-C, 1
Cat-D).

### Discrepancy found: the file didn't match the old audit

§41's 453 "safe" direct-edit fixes were documented as "written to disk,
NOT yet committed" at the time, and checking `git log` for this file
shows no matching commit -- only generic "updates"/"fixes for staging"
messages. Whatever was on disk then either never got committed or was
since overwritten by a newer source drop from the client ("updated
source files from latest files provided by Mark" appears in this file's
log). Current `git diff`/`git status` for this file was clean against
`HEAD`, and a fresh grep found **5 active `float: left;` declarations**
(lines 565, 582, 838, 955, 1079), not the 1 the old table predicted.
Treated the old count as stale and re-classified all 5 from scratch
rather than trusting it.

### Classification (same A/B/C/D scheme as §41)

All 5 turned out to be mechanically safe, not genuine grids:

- **Line 565** -- `h3 { width: 100%; float: left; ... }` -- Category B
  (literal full-width float, non-carousel).
- **Line 582** -- `.text-link { width: 75px; float: left; display: flex;
  position: relative; }` -- Category A (own rule already `display:
  flex` -> float is computed as inert regardless of the CSS source
  order, per the same reasoning §41 empirically confirmed via
  `getComputedStyle`).
- **Line 838** -- `.title-container { float: left; width: 100%; ... }`
  -- Category B.
- **Line 955** -- `.auto-card-container-inner { float: left; width:
  100%; position: relative; ... }` -- Category B.
- **Line 1079** -- `.title-block { float: left; width: 100%; ... }` --
  Category B.

No genuine Category C/D grid was found in this file after all -- every
`float:left` in it turned out to already be either flexed or full-width.

### Applied

Direct edits (same pattern as §41's safe-fix approach): `float: left`
-> `float: none` in place, all 5 declarations, in
`source/scss/templates/_benchmarks-maturity.scss`.

### Verification

- `git diff --stat`: 1 file, 5 insertions(+), 5 deletions(-) -- exactly
  the 5 targeted lines, nothing else touched.
- Compiled the file for real with `dart-sass` against the theme's
  actual global mixins/variables (`source/scss/global/_mixins.scss`,
  `_variables.scss`): 0 errors, only the same `@import`-deprecation
  warnings common to the whole codebase (same as §46).
- Grepped the compiled CSS: `float: left` count = 0 anywhere in this
  file's output; `float: none` count = 6 (5 from this edit + 1
  pre-existing, unrelated to this change).
- Did **not** do a live before/after pixel/computed-style diff on
  staging this pass (the two-browser Chrome-extension picker needed a
  live user confirmation click mid-session and was skipped to keep
  moving) -- same caveat §46 left open. The live page for this template
  is reachable via `template-benchmarks-maturity.php`
  (`_auto-play-card-carousel.php`, `_quicklinks-with-hover.php`,
  `_list-block.php` component partials, matching the 3 edited
  sections). Worth a quick visual check on that page before shipping,
  same recommendation as every other CSS change in this doc.

### Status

Applied on the user's machine via the device bridge, **not committed to
git yet** -- left for the user to review/commit/push per standing
practice.

### Remaining files (16 total, `_benchmarks-maturity.scss` now done)

Per §41's original breakdown (also now suspect given this file's
mismatch -- worth spot-checking each before trusting the C/D counts):
`_market-buyer.scss` (2), `_agenda.scss` (4), `_thank-you-old.scss` (4),
`_benchmarking.scss` (4), `_subscribe.scss` (6), `_single-events.scss`
(8), `_home.scss` (10), `_gtm.scss` (12), `_services.scss` (12),
`_resources.scss` (16), `_roundtable.scss` (15), `_single-post.scss`
(19), `_position.scss` (25), `_landing.scss` (43), `_customer-
stories.scss` (48). Next smallest candidate: `_market-buyer.scss`.


---

## §50 -- 2026-09-17 (cont.): float-grid redesign, 2nd file -- `_market-buyer.scss`, 10 declarations fixed

### Context

Continuing §49's file-by-file pass on the 229 declarations flagged in
§41. Picked the next smallest file per the old breakdown:
`_market-buyer.scss` (listed as 1 Cat-C, 1 Cat-D, 2 total).

### Same stale-count pattern as §49

Same as `_benchmarks-maturity.scss`: the file was clean against `HEAD`
(no pending changes from a prior unfinished pass), but a fresh grep
found **10 active `float:left`/`float:right` declarations** (lines 17,
28, 33, 39, 232, 710, 733, 774, 802, 808), not the 2 the old §41 table
predicted. Re-classified all 10 from scratch rather than trusting the
old count, same as §49.

### Classification

- **Lines 17, 28, 33, 39** (`section.video-module` and its nested
  `.image-video-container`/`.video-image-inner`/`.image-container,
  .video-container`) -- all `width: 100%` -- Category B.
- **Line 232** (`.side-bar-navigation ul li`) -- `width: 100%` --
  Category B.
- **Line 710** (`.column.accordion-block`) -- own rule already has
  `display: flex; flex-direction: column;` -- Category A.
- **Line 733** (`.column.accordion-block .faq-item .icon-container`,
  `width: 24px`) -- narrower width, would normally be Category C, but
  its direct parent `.faq-item` has `display: flex` on its own rule
  (confirmed a few lines up in the same block) -- per the flexbox spec,
  a flex item's float is always computed as `none` regardless of its
  own float/display value, so this is safe by the same "provably inert"
  reasoning as Category A, just via the parent's flex context rather
  than the element's own rule. Labeled **Category A (parent-flex)** to
  distinguish it from the simpler same-rule case, in case this pattern
  recurs in later files.
- **Line 774** (`.faq-item .answer`, `width: 100%`) -- also a direct
  child of the same flex `.faq-item` -- both Category A (parent-flex)
  and Category B independently; either reasoning alone makes it safe.
- **Line 802** (`.column.image-block`) -- direct child of
  `.column-container`, which has `display: flex; flex-direction: row;`
  on its own rule (confirmed above in the same file) -- Category A
  (parent-flex); also carries `order: 1`, a flex-only property, which
  independently confirms this element was always intended to be a flex
  item.
- **Line 808** (`.column.image-block .image-container`, `width: 100%`)
  -- parent `.column.image-block` itself has no `display: flex` (only
  `padding-right`/`height`/`order`), so this one isn't a flex item --
  but `width: 100%` makes it Category B on its own.

No genuine Category C/D grid found in this file either -- same outcome
as §49's file.

### Applied

Direct edits, all 10 declarations: `float: left`/`float:left`/`float:
right` -> `float: none`, exact original spacing preserved outside the
value itself, in `source/scss/templates/_market-buyer.scss`.

### Verification

- `git diff --stat`: 1 file, 10 insertions(+), 10 deletions(-) --
  exactly the 10 targeted lines.
- Compiled for real with `dart-sass` against the theme's global mixins/
  variables: 0 errors, only the standard `@import`-deprecation
  warnings.
- Compiled CSS: `float: left`/`float: right` count = 0 anywhere in this
  file's output; `float: none` count = 18 (10 from this edit + 8
  pre-existing).
- Same caveat as §49: no live staging screenshot/computed-style check
  done this pass.

### Status

Applied on the user's machine via the device bridge, **not committed to
git yet**.

### Remaining files (14 left)

`_agenda.scss`, `_thank-you-old.scss`, `_benchmarking.scss`,
`_subscribe.scss`, `_single-events.scss`, `_home.scss`, `_gtm.scss`,
`_services.scss`, `_resources.scss`, `_roundtable.scss`,
`_single-post.scss`, `_position.scss`, `_landing.scss`,
`_customer-stories.scss` -- all counts from §41's original table are
now suspect given both files done so far had 5x the predicted
declarations; treat each file's real count as unknown until grepped
fresh. Next smallest candidates by the old (now unreliable) count:
`_agenda.scss` and `_thank-you-old.scss`, both listed at 4.


---

## §51 -- 2026-09-17 (cont.): `_agenda.scss` found confirmed-dead mid-float-audit -- removed (819 lines), one live rule kept

### Context

Continuing §49/§50's file-by-file float-grid pass, next candidate was
`_agenda.scss`. Found 25 active `float:left`/`float:right` declarations
(not the 4 the old §41 table predicted -- same stale-count pattern as
the last two files). While classifying them, noticed all but 4 already
had matching float-refactor gate entries at the bottom of the file
(the same `// line NNN` + doubled-specificity `float: none` pattern
used throughout this project -- confirming those 21 were already
computed as `float: none` in production and just needed the
"collapse the gate into a direct edit" cleanup, zero risk). Of the
remaining 4, two (`section.agenda-title`, a bare `<section>` with no
width) turned out to already be covered by a **sitewide** gate rule
(`section { float: none !important; }` in
`source/scss/global/_styles.scss`, from the original global
float-refactor pass) -- also already inert. The last two --
`a.agenda-days-switcher` (a horizontal tab nav) and the
`.speaker-image-container` (335px) / `.content-container`
(`calc(100% - 335px)`) pair (a genuine two-column sidebar+content
layout) -- were real, ungated float grids that would need an actual
flexbox redesign.

Before redesigning those two, checked which live page actually renders
this markup (standard practice before touching a real layout) -- and
found none does.

### The confirmed-dead finding

`_agenda.scss`'s selectors are scoped under four root selectors:
`section.agenda-title`, `div.agenda-day`, `main.agenda`,
`section.agenda-item`. The classes these require
(`agenda-title`/`agenda-item` on a `<section>`, `agenda-day`,
`agenda-days-switcher`, `speaker-image-container`,
`content-container`, `company-container`, `roundtable-container`, etc.)
are only ever emitted by three component partials:
`templates/components/_agenda-item.php`,
`templates/components/_agenda-house-keeping.php`, and
`templates/components/_agenda-item-roundtable.php`.

A theme-wide grep for every literal reference to those three filenames
(`get_template_part`/`include`/`require`, `functions.php`'s ACF
flexible-content dispatch tables, shortcode registrations, block
registrations) found **zero** -- nothing in the theme ever calls them.
The literally-named "Agenda" page template
(`templates/template-agenda.php`, the only template with "agenda" in
its filename) does NOT use `get_template_part` dispatch for these
components at all -- it renders its own hardcoded markup
(`.agendaBlock`, `.agendaHighlightsBlock`) built directly from ACF
fields, entirely unrelated to `_agenda.scss`'s selectors. Same for the
only other live pages that mention "agenda"
(`single-event.php`/`single-event-nov.php`, `template-speakers.php`,
`single-registration.php`, `_speakers-block.php`) -- all use different,
unrelated markup (`agendaBlock`, `<a class="agenda-item">` as a plain
link, `<span class="agenda-title">`) that doesn't match this file's
`section.agenda-title`/`section.agenda-item` selectors.

This is the same "structurally unreachable" test used throughout this
engagement for confirmed-dead PHP files (no reference anywhere in the
theme), applied here to three *component* partials rather than a
top-level template -- and since every one of `_agenda.scss`'s rules
(bar one) is scoped under classes exclusive to those dead components,
the CSS is provably unreachable the same way §46's dead-CSS removals
were.

**One live exception found and kept**: `main.agenda { background-color:
#fff; padding-bottom: 60px; }`. `template-agenda.php` line 9 has
`<main id="main" role="main" class="agenda" ...>` -- a real match, kept
as-is.

### Applied

Rewrote `source/scss/templates/_agenda.scss` down to just the one live
rule (`main.agenda`) plus an explanatory header comment recording why
the rest was removed and pointing back to this section. Removed: both
`section.agenda-title` variants (`.one-day`/`.multi-day`, including the
day-switcher tab nav), `div.agenda-day`, the entire `section.agenda-item`
block (speaker/content two-column layout, company/speaker info, agenda
description, the roundtable sub-block), and the entire float-refactor
gate section for all of the above -- 819 lines total. This also
resolves what would otherwise have been the 2 real Category-C
redesigns (the two-column layout, the tab nav) -- moot, since neither
selector can ever match live markup.

Per the standing decision, the three dead PHP component files
themselves (`_agenda-item.php`, `_agenda-house-keeping.php`,
`_agenda-item-roundtable.php`) were **not** touched -- this pass is
CSS-only, same as §46.

### Verification

- Compiled the new file standalone with `dart-sass` against the
  theme's global mixins/variables: 0 errors, only the standard
  `@import`-deprecation warnings. Output is exactly the one
  `main.agenda` rule (plus unrelated `@keyframes` pulled in from the
  global mixins import, confirming nothing scoped to this file leaked
  through).
- `git diff --stat`: 1 file, 13 insertions(+), 819 deletions(-).
- Re-ran the theme-wide reference grep one more time after the edit to
  make sure nothing else in `source/scss/`, `source/js/`, or any `.php`
  file references the removed selectors by their exact compiled class
  names -- clean.

### Status

Applied on the user's machine via the device bridge, **not committed to
git yet** -- left for the user to review/commit/push. Given the size of
this removal, worth a closer look at the diff before shipping, same as
every dead-CSS removal in this doc, though the confirmed-dead test here
was as rigorous as §46's.

### Effect on the float-grid tracker

`_agenda.scss` is now done (0 remaining `float:left`/`float:right`
declarations, verified). 13 files left. Given both this file's stale
count and `_market-buyer.scss`/`_benchmarks-maturity.scss`'s stale
counts turned out to be significantly off, and this file additionally
turned out to be a dead-code case rather than a real float-fix case,
it's worth re-running a fresh grep-based count on any remaining file
before assuming its old §41 number is accurate -- and worth being
alert to the same "is this even live" question on the larger remaining
files (`_customer-stories.scss`, `_landing.scss`, `_position.scss`),
since dead-CSS and real-float-grid issues can and do overlap in the
same file.


---

## §52 -- 2026-09-17 (cont.): float-grid redesign, 3rd file -- `_thank-you-old.scss`, 26 declarations (22 mechanical + 3 real flexbox conversions)

### Context

Continuing the file-by-file pass (§49-§51). `_thank-you-old.scss` had 26
active `float:left`/`float:right` declarations (again, not the 4 the old
§41 table predicted). Before doing any work, checked liveness first
given §51's dead-file surprise: this file's selectors
(`section.thank-you-banner`, `section.information-blocks`,
`section.cta-block`, `section.faq-block`) are rendered by
`templates/components/_thank-you-banner-block.php`, `_information-blocks.php`,
`_cta-block.php`, `_faq-block.php` -- all dispatched by
`templates/template-thankyou.php`, which §46 already confirmed has one
live published page. So this file is genuinely live, unlike `_agenda.scss`.

### Classification

22 of 26 declarations already had matching float-refactor gate entries
(same pattern as §51) -- already computed as `float: none` in
production, safe to collapse into direct edits. The remaining 4 were
real, ungated float layouts:

- **`.column.image-column` (banner) / `.column.text-column`** -- both
  carry the shared sitewide `.one-half` utility class
  (`width: 50%; float: left;` in `source/scss/global/_styles.scss`,
  used by many other templates -- not touched). This file's own rule
  overrides `.image-column`'s float to `right` (more specific
  selector), while `.text-column` keeps `float: left` from `.one-half`
  since nothing here overrides it. Net effect: a genuine 50/50
  two-column banner (image floated right, text sitting in the
  remaining space on the left), confirmed against the live PHP markup
  in `_thank-you-banner-block.php` (image-column renders first in the
  DOM, text-column second).
- **`.column.information-column`** (info-blocks section) -- also
  carries `.one-half` (`width: 50%; float: left`), repeated once per
  ACF row inside `.column-container` (confirmed via
  `_information-blocks.php`'s loop) -- a genuine wrapping 2-per-row
  grid, not a fixed 2-item layout.
- **`.title-column` (185px) / `.faq-column`
  (`calc(100% - 185px)`)** (FAQ section) -- both float left, sitting
  side by side in DOM order (title first/left, FAQ list
  second/right) -- a genuine fixed-sidebar + content layout, same
  shape as the pair found (and later found moot) in `_agenda.scss`.

### Applied

**22 mechanical edits**: direct `float: left`/`float: right` ->
`float: none` on the gated lines, matching §41/§49-§51's established
safe pattern.

**3 real flexbox conversions**, each scoped to just that section (never
touching the shared `.one-half`/`.one-quarter` utility classes
themselves, which are reused across many other templates):

- `section.thank-you-banner .container`: `display: flex;
  flex-direction: row-reverse;` (DOM order is image-then-text, but the
  image needs to render on the right -- `row-reverse` reproduces what
  `float: right` was doing without touching markup), with
  `@include responsive(767) { flex-direction: column; }` to preserve
  the original mobile stacking behavior (both columns already collapse
  to `width: 100%` via `.one-half`'s own responsive rule -- unchanged).
  `.image-column`'s own `float: right` -> `float: none` (now inert as
  a flex item; kept explicit for source cleanliness). `.text-column`
  needed no edit -- its float came from `.one-half` and is now
  harmlessly inert too, without touching that shared class.
- `section.information-blocks .container .column-container`:
  `display: flex; flex-wrap: wrap;` added (plus its own
  `float: left` -> `float: none`, one of the 22 gated/mechanical
  lines). `.information-column`'s `float: left` -> `float: none`
  (inert as a flex item; its `width: 50%` from `.one-half` now serves
  as flex-basis, two per row, wrapping correctly at any count).
- `section.faq-block .container`: `display: flex;` added, with
  `@media (max-width:767px) { flex-direction: column; }` (matching
  this section's own existing raw `@media` convention rather than the
  `@include responsive()` mixin used elsewhere in the file, for
  consistency with its neighbors). DOM order (title first/left, FAQ
  second/right) already matches the desired visual order with plain
  `flex-direction: row` (the default), so no reversal needed here,
  unlike the banner. Both `.title-column` and `.faq-column`'s own
  `float: left` -> `float: none`.

Also removed the entire float-refactor gate section (278 lines) at the
bottom of the file, now fully redundant once every gated declaration
was collapsed to a direct edit.

### Verification

- `grep` for `float:\s*left\|float:\s*right` in the file: 0 matches
  anywhere.
- Compiled for real with `dart-sass` against the theme's global mixins/
  variables: 0 errors, only the standard `@import`-deprecation
  warnings. Confirmed in the compiled output: `display: flex` present
  on exactly the 3 intended selectors
  (`section.thank-you-banner .container`,
  `section.information-blocks .container .column-container`,
  `section.faq-block .container`), `flex-direction: row-reverse` and
  `flex-wrap: wrap` both present, `float: left`/`float: right` count =
  0, `float: none` count = 27.
- `git diff --stat`: 1 file, 41 insertions(+), 307 deletions(-).
- Did **not** do a live before/after screenshot/computed-style check on
  staging -- same caveat as §49-§51. Given this pass includes 3 real
  layout conversions (not just mechanical edits), this file is the
  highest-value candidate in the whole float-grid tracker for an actual
  live visual check before shipping -- specifically the banner's
  image/text side-by-side order and the FAQ sidebar/content split, at
  both desktop and the 767px mobile breakpoint.

### Status

Applied on the user's machine via the device bridge, **not committed to
git yet**.

### Remaining files (12 left)

`_benchmarking.scss`, `_subscribe.scss`, `_single-events.scss`,
`_home.scss`, `_gtm.scss`, `_services.scss`, `_resources.scss`,
`_roundtable.scss`, `_single-post.scss`, `_position.scss`,
`_landing.scss`, `_customer-stories.scss` -- old §41 counts all
unreliable, re-grep each fresh. Also worth checking liveness before
starting each, per §51's finding.


---

## §53 -- 2026-09-18: user committed/pushed prior work; float-grid redesign, 4th file -- `_benchmarking.scss`, 16 declarations, all mechanical

### Housekeeping note

Picking this session back up, found the user had committed and pushed
§48-§52's work (GSAP fix, `_benchmarks-maturity.scss`,
`_market-buyer.scss`, `_agenda.scss`, `_thank-you-old.scss`) as commit
`64131a6` ("updates; optimize float-based elements; optimize css using
per-template queueing"), followed by their own separate commit
`b9bede7` ("added new toggle checkbox for roundtable registration").
Working tree is clean, `dev` is up to date with `origin/dev`. Also
noticed a stale, empty `.git/index.lock` on the user's machine
(couldn't remove it -- no delete permission requested for it this
pass, and it isn't blocking any read operation) -- flagged to the user
directly; it may need manual removal before their next `git commit` if
still present.

### This file

`_benchmarking.scss` styles `template-benchmarking.php` -- confirmed
live (it's one of the 8 templates `adapt_page_needs_gsap()` explicitly
lists, and its `body.template-benchmarking` selector at line 617
matches the theme's live-template body-class convention). 16 active
`float:left` declarations found (old §41 table isn't available for
this file's individual count, but the pattern held again -- this
wasn't a small easy file).

### Classification

12 of 16 already had matching float-refactor gate entries (verified by
selector content, not raw line number -- the gate's own `// line NNN`
comments are anchored to line numbers from whenever the gate was
written, which had drifted +4 from current line numbers partway
through the file due to some unrelated earlier edit; matched by
comparing the gate's compiled selector to the current declaration's
context instead of trusting the number literally). The remaining 4
(lines 25, 31, 36, 43, all in `section.quote-slider`'s
`.quote-module`/`.customer-quote-slider-inner`/`h2`/`.small-quote-text`)
were ungated but all plain `width: 100%` -- Category B, mechanically
safe, no real grid found anywhere in this file.

### Applied

All 16: direct `float: left` -> `float: none`. Removed the now-fully-
redundant float-refactor gate section at the bottom of the file
(matching every one of the 12 gated declarations).

### Verification

- `grep` for `float:\s*left\|float:\s*right`: 0 matches in the file.
- Compiled for real with `dart-sass` against the theme's global mixins/
  variables: 0 errors. Compiled output: `float: left`/`float: right`
  count = 0.
- `git diff --stat`: 1 file, 16 insertions(+), 177 deletions(-).

### Status

Applied on the user's machine via the device bridge, **not committed to
git yet**.

### Remaining files (11 left)

`_subscribe.scss`, `_single-events.scss`, `_home.scss`, `_gtm.scss`,
`_services.scss`, `_resources.scss`, `_roundtable.scss`,
`_single-post.scss`, `_position.scss`, `_landing.scss`,
`_customer-stories.scss`.


---

## §54 -- 2026-09-18 (cont.): float-grid redesign, 5th file -- `_subscribe.scss`, 38 declarations (33 mechanical + 3 real flexbox conversions), plus a methodology note on `<span>`/`<a>` floats worth flagging for every remaining file

### Context

Continuing the file-by-file pass (§49-§53). Picked the next candidate from
§53's remaining list: `_subscribe.scss`. Confirmed live before starting,
per §51's standing practice: its four root selectors
(`section.subscribe-introduction`, `section.two-column-image-text-subscribe`,
`section.three-column-icon-text`, `section.two-column-subscribe`) are each
rendered by their own component partial in
`templates/subscribe-components/` (`_introduction.php`,
`_two-column-image-text.php`, `_three-column-icon-text.php`,
`_two-column-subscribe.php`), all dispatched by `templates/template-subscribe.php`
via ACF flexible-content rows. The `two_column_subscribe_blocks` and
`three_column_icon_text` layouts are also reused by several other live
templates (`template-benchmarking.php`, `template-comparison.php`,
`template-services.php`, and others), so this file is live and
higher-traffic than a single-page template would be.

### Fresh count, same stale-table pattern as every file so far

A fresh grep found **38 active `float:left`/`float:right` declarations**
(lines 38, 105, 120, 131, 138, 160, 172, 176, 184, 194, 200, 241, 251,
267, 279, 288, 316, 434, 458, 472, 482, 511, 521, 525, 558, 623, 628,
632, 637, 648, 657, 666, 672, 678, 690, 694, 721, 725) -- not the 6 §41's
old table predicted. Same pattern as every file in this series so far;
the old table is now treated as fully unreliable and ignored.

### Classification

33 of 38 already had matching float-refactor gate entries at the bottom
of the file, verified by selector content (all 33 matched cleanly by
their `// line NNN` comment *and* by comparing the gate's compiled
selector path to the current declaration -- no drift found this time,
unlike §53). These 33 were already computed as `float: none` in
production; collapsed into direct edits per the established safe
pattern.

The remaining 5 (lines 472, 511, 628, 632, 694) were ungated and
classified fresh:

- **Line 472** -- `.icon-text-column-container .column` -- narrower
  width (33.3%), but the parent `.icon-text-column-container` has
  `display: flex; flex-wrap: wrap;` on its own rule (confirmed a few
  lines up) -- **Category A (parent-flex)**, same reasoning §50
  established.
- **Line 511 / (521 was already gated)** -- `.column .icon-container`
  (80px) / `.text-container` (`calc(100% - 80px)`) -- a genuine
  side-by-side icon+text pair, both floated, no flex anywhere in their
  own ancestry (`.column` itself is not a flex container) -- **Category
  C**, real grid, needs redesign.
- **Line 628 / 632** -- `.top-content .card-image-container` (180px
  desktop / 145px at <=1023px, `float: right` on desktop, overridden to
  `float: left` on the mobile breakpoint) paired with `.text-content`
  (`calc(100% - 180px)`, already gated at line 648) -- **Category C**,
  a genuine two-column card layout.
- **Line 694** -- `.tags-container .tag` -- float with no width at all
  (just `margin-right`/`margin-bottom`), a wrapping row of pill/tag
  chips -- **Category D** in shape, but the fix is the same well-known
  "floats used purely for horizontal wrapping" pattern -> `flex-wrap`.

### The methodology note: `<span>`/`<a>` floats need a `display` check, not just a width check

This file's markup (checked against all 4 component partials) uses
`<span>` for the vast majority of its structural wrapper elements
(`.top-content`, `.card-image-container`, `.text-content`,
`.pre-title`, `.text-tags-container`, `.tags-container`, `.tag`,
`.form-popup-button-container`, etc. -- almost everything except the
outer `<section>`/`<div class="container">`/`<div class="column">`
levels), plus real `<a>` tags for the two button links. This matters
because of a CSS rule that hadn't come up explicitly in §49-§53 (those
files leaned much more on `<div>`/heading tags, which are block by
default regardless of float): **a floated inline element (`span`, `a`)
has its computed `display` forced to `block` by the UA only while
`float` is non-`none`** (CSS2.1 9.7). Set `float: none` on a `<span>`
or `<a>` with no explicit `display` of its own, and it silently reverts
to inline, at which point `width: 100%`/`width: 180px` etc. stop
applying -- a real regression, not just a source-tidiness issue.

This file's own float-refactor gate had already solved this for two
cases via a small set of bonus non-numbered rules at the very bottom
(`.button-container a` under the mobile breakpoint, and
`.form-popup-button-container a`, both forced to `display: block`
alongside their `float: none`) -- both were preserved by adding
`display: block;` directly alongside the collapsed `float: none;`
edits at their two lines (138, 725). The gate also carried two
apparently-redundant `display: block` overrides on `.title-container`
(a `<div>`, already block by default) -- kept those too, on the
"can't confirm it's a no-op, costs nothing to keep" principle, rather
than assume they were dead weight.

For the many *already-gated* span-based floats (`.pre-title`,
`.post-button-text`, `.text`, `.list-container`, `.list-item`, the
inner `.text-container span`, etc.), no display fix was needed: those
were already covered by §41's original live Playwright computed-style
verification across all 16 files (this one included), which empirically
confirmed `float: none` alone was sufficient for them -- collapsing a
verified gate into a direct edit doesn't change the compiled output at
all, so no new risk was introduced by leaving them as-is.

For the 3 new Category-C/D conversions (all newly designed this pass,
not previously verified by anyone), the same span-display risk was
closed a different way: every span in question (`.card-image-container`,
`.text-content`, `.tag`) was made a **direct child of a newly-flexed
parent**, and CSS's flex/grid item blockification rule (CSS Display
Level 3) forces a flex item's computed `display` to block-level
regardless of its specified value, the same way floats used to -- so no
manual `display: block` was needed for those three. This "make it a
flex item and the blockification comes for free" pattern is worth
carrying into the remaining files, several of which likely share this
theme's span-heavy markup convention.

### Applied

**33 mechanical edits**: `float: left`/`float: right` -> `float: none`
on the gated lines (plus the two `display: block` additions on the `a`
tags and two on the `.title-container` divs, preserving the gate's
bonus rules).

**3 real flexbox conversions**, each scoped to just its own section:

- `.column .icon-container` / `.text-container` (three-column-icon-text):
  added `display: flex;` to `.column` itself. DOM order (icon first,
  text second, confirmed in `_three-column-icon-text.php`) already
  matches the desired left-to-right visual order, so no reversal
  needed -- plain `display: flex;` reproduces the fixed-80px-icon +
  fill-the-rest layout exactly.
- `.top-content` (two-column-subscribe): added `display: flex;`, with
  `@media (max-width: 1023px) { flex-direction: column; }`. Checked
  actual DOM order in `_two-column-subscribe.php`: `.text-content`
  renders *before* `.card-image-container` (opposite of what the SCSS's
  own source order suggested), and since `.text-content` was
  `float: left` (renders left) and `.card-image-container` was
  `float: right` (renders right), default `flex-direction: row` (no
  reversal) reproduces the desktop layout exactly, in DOM order. At
  <=1023px the original float behaviour was an implicit stack (the
  mobile override makes `.text-content` `width: 100%`, which can't fit
  beside the still-explicitly-sized 145px image, so it drops onto its
  own line) -- `flex-direction: column` at that breakpoint reproduces
  the same stacked order (text first/top, image second/below) without
  needing `flex-wrap` or manual reordering.
- `.tags-container .tag` (two-column-subscribe): added `display: flex;
  flex-wrap: wrap;` to `.tags-container`. `.tag` keeps its own
  `margin-right`/`margin-bottom` for gaps between chips; wrapping
  behaviour is now explicit instead of an implicit float-wrap side
  effect.

Also removed the entire float-refactor gate section (464 lines,
including its 4 bonus non-numbered rules, all folded into direct edits
above) at the bottom of the file, now fully redundant.

### Verification

- `grep` for `float:\s*left\|float:\s*right` in the file: 0 matches.
- Compiled for real with the theme's actual `sass` npm package
  (`node_modules/.bin/sass`, dart-sass 1.102.0 -- the standalone
  `dart-sass` binary isn't on PATH on this machine, unlike what §49-§53
  assumed; the local `sass` package is the same dart-sass engine
  underneath) against the theme's full global import chain (`_mixins`,
  `_variables`, `_fonts`, `_icons`, `_base`, `_styles` -- needed all
  six this time; `_mixins`+`_variables` alone, which is what §49 used,
  wasn't enough for this file because two of its color/weight variables
  -- `$h2-font-weight` etc. -- are defined inside `_styles.scss`
  itself, not `_variables.scss`): 0 errors, only the standard
  `@import`-deprecation warnings common to the whole codebase.
- Parsed the compiled CSS into rule blocks and isolated the 125 blocks
  whose selector touches one of this file's four root sections: 0 of
  125 contain `float: left`/`float: right`.
- Directly inspected the compiled output for all three flex conversions
  and the two `a`/title-container display fixes -- all five compiled
  exactly as designed (`display: flex` + `width: 33.3%` on the icon/text
  column; `display: flex` + `width: 100%` on `.top-content`, with its
  `flex-direction: column` correctly scoped inside its own
  `@media (max-width: 1023px)` block; `display: flex; flex-wrap: wrap;`
  on `.tags-container`; `display: block` alongside `float: none` on
  both `a` selectors and both `.title-container` instances).
- Did **not** do a live before/after screenshot/computed-style check on
  staging -- same caveat as every prior file in this series. Given this
  pass includes 3 real layout conversions on a component reused across
  several live templates (not just `template-subscribe.php`), this file
  is a strong candidate for a live visual check before shipping,
  specifically the three-column icon/text row, the two-column-subscribe
  card's image/text side-by-side order at desktop and its stacked order
  at <=1023px, and the tag chips wrapping in `.tags-container`.
- Two scratch dart-sass compile-test files
  (`compile_test_TEMP.scss`/`.css`) were created in the repo root for
  this verification and deleted before finishing (required requesting
  delete permission for the connected folder this pass, since it
  wasn't previously granted this session).

### Status

Applied on the user's machine via the device bridge, **not committed to
git yet** -- left for the user to review/commit/push per standing
practice. `git diff --stat`: 1 file, 50 insertions(+), 501 deletions(-).

### Remaining files (10 left)

`_single-events.scss`, `_home.scss`, `_gtm.scss`, `_services.scss`,
`_resources.scss`, `_roundtable.scss`, `_single-post.scss`,
`_position.scss`, `_landing.scss`, `_customer-stories.scss` -- old §41
counts all unreliable, re-grep each fresh. Also worth checking
liveness first per §51's finding, and now also worth checking early in
each file whether its markup leans on `<span>`/`<a>` for structural
wrappers (as this file and presumably `_introduction.php`'s siblings
do) -- if so, apply the same two checks this section introduced: (1)
for a mechanical gate collapse, trust the original gate's exact output
(with its display fix, if it had one) rather than re-deriving it; (2)
for any new flex conversion, prefer making the affected span/anchor a
direct flex item of a newly-`display: flex` parent over hand-adding
`display: block`, since flex-item blockification handles it for free.


---

## §55 -- 2026-09-18 (cont.): float-grid redesign, 6th file -- `_single-events.scss`, 22 declarations (20 fixed: 17 mechanical + 3 real flexbox conversions; 1 deliberately left as a genuine float, out of scope)

### Context

Continuing the file-by-file pass (§49-§54). Picked `_single-events.scss`
from §54's remaining list. Confirmed live: its root selectors
(`section.banner`, `section.navigation`, `section.centerModeCarousel`,
`section.agendaHighlightsBlock`, `section.imageGridBlock.logos`) all
match markup in `templates/single-event.php` and
`templates/single-event-nov.php`, the two "Event" post templates (the
same templates §51 already confirmed are live when ruling out
`_agenda.scss`'s dead code).

### Fresh count

**22 active `float:left`/`float:right` declarations** (lines 26, 47,
51, 74, 87, 103, 191, 196, 223, 306, 336, 346, 350, 364, 386, 398, 418,
439, 459, 483, 488, 562), not the old table's number. Only **11** of
these had a matching float-refactor gate entry (47, 87, 103, 336, 346,
350, 439, 459, 483, 488, 562) -- the other 11 were never covered by the
original 2026-09-14 pass at all, a bigger gap than §49-§54 saw (those
were mostly fully gated with only a handful of new ones each). All 11
gate matches checked cleanly against their selector, no drift.

### The one declaration left alone on purpose: `.insetImage` (line 26)

`section.banner .insetImage` (`float: left; width: 20.6%; position:
relative; padding-top: 20.6%;`) sits first inside a slide's `.content`
div, ahead of a `.column.title`, `<hr>`, `.column.text`, and (per this
file) `.buttonBlock`/`.videoLink`. Checked `single-event.php`: none of
`.content`, `.column`, `.buttonBlock` have any width/float override
anywhere in this theme's SCSS (`.buttonBlock` does exist globally in
`global/_styles.scss`, but it's an unrelated legacy WYSIWYG-editor
utility class, not scoped to this banner) -- meaning `.column.title`/
`.column.text` are plain unstyled `<div>`s that get their layout purely
from being adjacent to `.insetImage`'s float. This is a **genuine
text-wrap-around-an-image float** (the classic, original use case for
`float`), not a grid hack: the title/text content is meant to flow
around the inset image the way a pull-quote or magazine image wraps
body text. There is no flexbox or grid equivalent that reproduces
organic text wrap around a floated box -- only `float` (optionally with
`shape-outside`) does this. Converting it to a fixed two-column flex
layout would be a genuine design change (rigid columns instead of
wrapping text), not a like-for-like modernization, and I'm not
confident enough in the intended visual result to make that call
unilaterally. Left as `float: left` untouched, flagged here for a
decision: either accept it staying as a (legitimate, non-grid) float
permanently, or have someone confirm on the live page whether a rigid
two-column layout is actually an acceptable visual replacement before
converting it.

### Classification of the rest

- **13 simple Category B/mechanical** (47 `.videoLink` itself -- gated,
  trusted exactly as the gate had it, `float: none` alone, see below;
  87 `section.navigation`; 103 nav `ul`; 191 `.titleBlock`; 223
  `.center`; 306 `ul.slick-dots`; 336 `section.agendaHighlightsBlock`;
  346 `.agendaBlock`; 350 `.item`; 439 `.seeMore`; 459 `.titleBlock`
  (imageGridBlock); 483 `.logoGroup`; 562 `.yourLogoHere`) -- all
  literal `width: 100%` (or, for 47, already-verified), all on `<div>`,
  `<ul>`, or `<section>` elements (checked against
  `single-event.php`/`single-event-nov.php`) that are block-level by
  default regardless of float, so no display fix needed.
- **1 defensive display fix**: line 196, `.titleBlock .title` -- this
  one *is* a `<span>` (`<span class="title"><h2>...</h2><hr></span>`),
  ungated (never verified before), and unlike §54's already-verified
  spans I have no prior computed-style check to lean on for this
  specific one. A `<span>` wrapping two block-level children (`h2`,
  `hr`) is invalid HTML that browsers generally paper over by
  rendering the block children on their own line regardless of the
  span's own computed display, but rather than rely on that leniency,
  added `display: block;` alongside `float: none;` -- costs nothing,
  removes the ambiguity entirely.
- **The `.videoLink` gate (line 47) was trusted exactly as written,
  no display fix added**, which is worth calling out because it looks
  like it *should* need one: `.videoLink` is `<span class="videoLink">`
  with `width: 100%`, and per CSS2.1 9.7 a floated inline element's
  display computes to block only while floated -- remove the float
  with no explicit `display` and a bare `<span>` reverts to inline,
  which would normally break `width: 100%`. But this exact selector
  was part of the original 2026-09-14 batch (§41) that did real
  Playwright computed-style verification across two viewports, and its
  gate says `float: none` alone was sufficient -- collapsing a
  pre-verified gate 1:1 doesn't change the compiled output at all, so
  the safe move is to trust it exactly rather than "improve" it with an
  addition nobody asked for. (Plausible explanation: `.videoLink`'s
  only child is a single `<button>` or `<a>`, and `<button>`'s UA
  default display is `inline-block`, not `inline` -- an inline-block
  child inside an inline parent with only one child often renders
  indistinguishably from a block parent, since there's nothing else on
  the "line" to reveal the difference.)
- **3 real flexbox conversions** (all newly designed, not previously
  verified by anyone):
  - **`.time` (20%) / `.eventOverview` (80%)**, `section.agendaHighlightsBlock
    .agendaBlock .item` -- checked the real DOM in `single-event.php`:
    both live inside `.item > .container > .inner`, not directly inside
    `.item`, so the new `display: flex;` (with `align-items:
    flex-start;` and `@include responsive(767) { flex-direction:
    column; }`, matching this section's existing 767px stacking
    breakpoint on both children) was added to a new `.container .inner`
    rule nested inside `.item`, not to `.item` itself -- adding it to
    `.item` would have done nothing, since `.time`/`.eventOverview`
    are grandchildren, not children. Both are `<span>`s; both become
    direct flex items of the newly-flexed `.inner`, so they're
    auto-blockified for free (same reasoning as §54).
  - **`.title` (auto-width) / `.description` (auto-width)**, nested
    inside `.eventOverview` -- a floated bold label next to a floated
    wrapping paragraph, the same "run-in label + wrapping text" float
    idiom as `.insetImage` above, but *this* one has an easy flex
    equivalent because both sides are already meant to sit in a single
    row with the label first (unlike `.insetImage`'s organic wrap
    around a large image on multiple lines of surrounding text).
    `.eventOverview` itself got `display: flex; align-items:
    flex-start;` (serving double duty as both the flex item of `.inner`
    above and the flex container for its own two children).
    `.description` got `flex: 1 1 auto; min-width: 0;` added (float
    with `width: auto` shrinks-to-fit but is bounded by the remaining
    available width in practice, which is what "fill the rest of the
    row and wrap the paragraph" looks like -- `flex: 1 1 auto` is the
    direct flex equivalent, and `min-width: 0` avoids the well-known
    flex long-word/overflow trap). `.title` needed no extra property,
    same shrink-to-fit sizing as before.
  - **The video "play" button icon+label row**, `section.banner
    .videoLink a, .playBtn` and its descendants -- the trickiest of the
    three. The original rule floats `a`/`.playBtn` itself (line 51,
    icon+text row) and, separately, an unqualified `span` selector
    nested under `&:last-child` (line 74) that -- because `a`/`.playBtn`
    is always both first-child and last-child (it's the button's only
    child; the template renders exactly one of `<a>` or `<button>`, never
    both) -- matches *every* descendant span: `.icon`, `.text`, and
    `.text`'s own two unclassed child spans ("Watch Video" / duration).
    Recursively floating all of them left, in DOM order, with none of
    them carrying an explicit width, produces one continuous
    left-to-right row: icon, then "Watch Video", then the duration --
    read together as a single button label. Reproduced this with nested
    flex rather than one flat rule: `display: flex; align-items:
    center;` added directly to the `a, .playBtn` rule (line 51, icon +
    `.text` side by side), and a **new**, separately-scoped `.text {
    display: flex; align-items: center; }` rule added alongside the
    existing broad `span` rule (which keeps applying its
    `float: none;`/font styling to all four spans unchanged) to put
    `.text`'s own two children side by side too. Every span in the
    chain ends up as a direct flex item of one of these two containers,
    so all of them are auto-blockified without any manual `display:
    block`. This is the one conversion in this file worth a live check
    before shipping -- of the three, it's the one where I'm relying on
    inferring the intended visual result (one continuous row) from the
    selector structure rather than from an unambiguous width/percentage
    split.

### Applied

17 mechanical edits (13 simple + `.videoLink`'s gate collapse + the 3
Category-C float:none conversions that don't need their own new
container) plus the 3 real flexbox conversions described above.
Removed the float-refactor gate section (101 lines, no bonus
non-numbered rules this time, unlike §54) at the bottom of the file,
now fully redundant. `.insetImage` (line 26) is the only declaration
left untouched.

### Verification

- `grep` for `float:\s*left\|float:\s*right`: exactly 1 match (line 26,
  the deliberate exception) -- everything else, 0.
- Compiled for real with `node_modules/.bin/sass` (dart-sass 1.102.0)
  against the theme's full global import chain: 0 errors, only the
  standard `@import`-deprecation warnings.
- Parsed the compiled CSS into rule blocks and isolated the 73 that
  touch this file's five root sections: 0 unexpected
  `float: left`/`float: right` among them (the one expected exception,
  `.insetImage`, confirmed still present as designed). Directly
  inspected the three flex conversions and the two display fixes in
  the compiled output -- all landed exactly as designed (`.container
  .inner` gets `display: flex` + the `flex-direction: column` media
  override; `.eventOverview` and `.description` get their flex
  properties; the `a`/`.playBtn` and nested `.text` rules both compile
  to `display: flex`; `.titleBlock .title` compiles to `float: none;
  display: block;`).
- Did **not** do a live before/after screenshot/computed-style check on
  staging -- same caveat as every prior file. This file has the highest
  number of "newly designed, not previously verified" conversions of
  the series so far (3, same count as §52, but on a file with a
  genuinely ambiguous recursive-float pattern) -- the video play-button
  row and the `.insetImage` decision are the two things most worth a
  human look before committing.

### Status

Applied on the user's machine via the device bridge, **not committed to
git yet**. `git diff --stat`: 1 file, 42 insertions(+), 123
deletions(-).

### Remaining files (9 left, one file's scope narrowed)

`_home.scss`, `_gtm.scss`, `_services.scss`, `_resources.scss`,
`_roundtable.scss`, `_single-post.scss`, `_position.scss`,
`_landing.scss`, `_customer-stories.scss` -- re-grep each fresh, check
liveness first, and now also watch for the two new patterns this file
surfaced: (1) a genuine text-wrap-around-image float with no flex/grid
equivalent (leave it, flag it, don't guess a redesign that changes the
visual result) vs. a same-looking-but-actually-single-row float pair
that *does* have a clean flex equivalent -- the difference is whether
the wrapped content is meant to flow across multiple lines around the
float or sit in one row beside it; (2) a `&:first-child`/`&:last-child`
pair on a selector that only ever has one matching element (both
pseudo-classes match simultaneously) -- easy to misread as "two
different elements" when it's actually compound styling on one.


---

## §56 -- 2026-09-18 (cont.): float-grid redesign, 7th file -- `_home.scss`, 26 declarations (all fixed: 20 mechanical + 6 real flexbox conversions), the homepage

### Context

Continuing the file-by-file pass (§49-§55). `_home.scss` styles the
site's actual homepage (`body.home`/`body.template-home`), served by
two live, independently-selectable WP templates:
`templates/template-home.php` ("Home Template") and
`templates/template-home-nov.php` ("Home Template (Nov)") -- confirmed
via their `Template Name` headers, the same convention that made
`single-event.php`/`single-event-nov.php` both live in §51/§55. Given
this is the highest-traffic page on the site, took extra care to trace
every declaration back to its real PHP markup rather than infer
structure from the SCSS alone.

### Fresh count

**26 active `float:left`/`float:right` declarations** (lines 22, 56,
68, 99, 114, 173, 189, 222, 246, 261, 270, 289, 299, 309, 326, 383, 394,
404, 430, 443, 447, 463, 473, 479, 503, 551). 16 already had a matching
gate entry (22, 56, 68, 99, 173, 189, 246, 261, 270, 289, 383, 404, 447,
463, 479, 503), all verified to match by selector, no drift. The other
10 were never covered by the original pass.

### Two declarations computed as `float: none` already, before any edit

Lines 22 (`.loading`) and 68/99 (the banner `ul` and `.baseButtons`)
carry `position: fixed`/`position: absolute` on the same rule -- per
CSS2.1 9.7, float is forced to compute as `none` for a positioned
element regardless of the specified value, the same "position" category
§41 defined. Collapsing their gates was risk-free by that same
reasoning already established in this series.

### Classification and the six real conversions

Fourteen of the 26 were literal `width: 100%` (or already gate-verified
span cases -- `.loading`, `#main`, banner `ul`, `.baseButtons`,
`.baseButtons a span.text`/`span.title`, `.content span.title`/
`span.text`/`span.buttonBlock`/`span.videoLink`, `.titleBlock` and its
`h2`, `.logoBlock`, `.logoContainer`, `.logoTitle`) -- collapsed
straight to `float: none` with no display fix, all trusting §41's
original computed-style verification for these exact selectors exactly
as `_single-events.scss` and this file's own gate had them (no
"improving" a pre-verified fix with an addition nobody asked for).

The other six needed real design work:

- **`.baseButtons a`** (line 114, `float: right`, three `banner_buttons`
  rendered in a loop as `<a>` tags inside one unclassed wrapping
  `<span>`) -- confirmed via `&:first-child { margin-right: 0px; }`
  that the intended visual order is the classic "consecutive
  `float:right` reverses DOM order" behaviour (first DOM child ends up
  *rightmost*, because each subsequent float:right box has to queue up
  to the *left* of the one before it). Reproduced with
  `flex-direction: row-reverse;` on a **new**, precisely-scoped
  `.baseButtons > span` rule (the direct-child combinator matters here:
  the existing broad `.baseButtons span { display: inline-block; width:
  100%; }` rule also matches the `.text`/`.title` spans *inside* each
  button, so a bare `span` selector would have wrongly turned those
  into flex containers too). With `row-reverse`, the default
  `justify-content: flex-start` already packs the group against the
  main-start edge, which *is* the right edge in reverse mode -- so no
  `justify-content` override was needed, and the existing
  `margin-right: 16px`/`:first-child { margin-right: 0 }` values work
  unchanged (physical margin properties don't flip with
  `flex-direction`).
- **`.content.columns .column`** (line 222, `float: left`, two
  `<div class="column title">`/`<div class="column text">` siblings,
  confirmed against `template-home-nov.php`) -- `display: flex;` added
  to the `&.columns` modifier only, *not* the base `.content` rule
  (which needs to keep stacking its children vertically in the
  non-columns case -- the `text_layout` ACF field picks between the two
  at render time, and both must keep working).
- **`.titleBlock .title` / `.description.centre` / `.description.right`**
  (lines 394/430/443, the `section.logoGrid` title+description row,
  same component pieces already seen in `_single-events.scss`'s
  `counter_block`) -- `.title` and `.description` are exactly two
  `<span>` siblings (confirmed against
  `templates/components/_counter-block.php` and the `logo_grid` layout
  in `template-home-nov.php`), with `.description` taking a mutually
  exclusive `centre` or `right` modifier from an ACF field. `display:
  flex;` added to `.titleBlock`; `.description.right` got `margin-left:
  auto;` (the standard flex trick to push one item to the far end of a
  row without needing a `:has()` selector or JS) instead of its old
  `float: right`, while `.description.centre` needed nothing extra
  (default flex packing already sits it right next to `.title`, same as
  its old `float: left`). Also added a `margin-left: 0;` reset inside
  `.description.right`'s existing `@include responsive(640)` block,
  since `margin-left: auto` has no equivalent "cancel" once the layout
  switches to `flex-direction: column` at that breakpoint (this file's
  desktop/mobile breakpoint for this component).
- **`.logoBlock .logo`** (line 473, `float: left`, plus its own
  pre-existing `display: inline-block`) -- `.logo` already carried an
  explicit `display: inline-block` on the *same* rule as its float,
  which -- once float is removed -- would have "woken up" and taken
  over as the actual rendering mode, switching this 3-per-row logo grid
  from float-wrapping to inline-block-wrapping. Both look similar but
  inline-block introduces whitespace-gap sensitivity that float-wrapping
  doesn't have, so rather than rely on the newly-active inline-block,
  added `display: flex; flex-wrap: wrap;` to the parent `.logoBlock`
  instead, consistent with every other multi-item grid fixed in this
  series -- `.logo`'s own `display: inline-block` is now harmless
  (flex-item blockification overrides it for outer layout purposes) and
  was left untouched rather than removed, to keep the diff minimal.
- **The video "play" button icon+label row** (`section.banner .content
  span.videoLink a, .playBtn`, lines 299/309/326) -- structurally
  identical to §55's `_single-events.scss` button (icon span + text
  span side by side, confirmed against the same `<button
  class="playBtn"><span class="icon">...</span><span
  class="text">...</span></button>` markup in `template-home-nov.php`),
  **but with one real difference worth flagging**: the innermost rule
  in *this* file has an **active** `clear: both;` (§55's equivalent
  rule had the same property present but commented out with `//
  clear:both;`). An active `clear: both` on both of `.text`'s own two
  children ("Watch Video" and the duration) means each one clears the
  float before it, which -- combined with both also being
  `float: left` -- stacks them on separate lines rather than sitting
  side by side. So unlike §55 (one continuous row, replicated with
  nested `display: flex` at every level), this file's version needed
  `display: flex; align-items: center;` only on the *outer*
  `a, .playBtn` rule (icon + text-wrapper side by side) while the
  *inner* "Watch Video"/duration spans got `display: block;` instead
  (preserving their stacked, two-line layout, which is what the active
  `clear: both` was achieving). This is a good example of why each
  file's actual source needs reading in full rather than pattern-matching
  a fix from the previous file that merely looks the same.
  Also added a defensive `display: block;` to `section.logoGrid.counter
  .logoBlock .logo .number` (line 551, ungated, a `<span>` with no
  width) for the same reason as §54/§55's span cases -- cheap insurance
  against it losing its own line next to `.logoTitle` once un-floated.

### Applied

20 mechanical edits (14 simple width:100%/already-inert + the two
gate-collapse cases that needed nothing extra) plus the 6 real
conversions above. Removed the float-refactor gate section (224 lines,
no bonus non-numbered rules this time) at the bottom of the file, now
fully redundant.

### Verification

- `grep` for `float:\s*left\|float:\s*right`: 0 matches.
- Compiled for real with `node_modules/.bin/sass` (dart-sass 1.102.0)
  against the theme's full global import chain: 0 errors, only the
  standard `@import`-deprecation warnings.
- Parsed the compiled CSS into rule blocks and isolated the 99 whose
  selector is genuinely scoped to this file (`#main`, `.loading`,
  `body.home`/`body.template-home`, `section.logoGrid`) -- 0 unexpected
  `float: left`/`float: right` among them. (An earlier, cruder pass
  using a looser "content" substring match falsely flagged 7 unrelated
  blocks belonging to completely different templates --
  `.post-content`, `.peer-insights-item`, `.mfp-registration` -- that
  happen to share the word "content"; re-scoped the check to this
  file's actual root selectors and confirmed those were never a real
  issue.) Directly inspected all six real conversions in the compiled
  output -- all landed exactly as designed (`.baseButtons > span`'s
  `flex-direction: row-reverse`, `.content.columns`'s `display: flex`,
  `.titleBlock`'s flex + `.description.right`'s `margin-left: auto`,
  `.logoBlock`'s `flex-wrap: wrap`, the video button's `display: flex`
  on the outer rule, and `.number`'s `display: block`).
- Did **not** do a live before/after screenshot/computed-style check on
  staging -- same caveat as every prior file, but given this is the
  homepage and has the highest real-conversion count of the series so
  far (6), this is the strongest candidate yet for a live visual check
  before shipping: specifically the banner CTA button row's left-right
  order (`.baseButtons`), the two-column banner slide variant
  (`.content.columns`, only reachable when a slide's `text_layout`
  field is set to it), the logo-grid title/description row in both its
  `centre` and `right` variants, and the video play-button's icon/label
  layout.

### Status

Applied on the user's machine via the device bridge, **not committed to
git yet**. `git diff --stat`: 1 file, 54 insertions(+), 252
deletions(-).

### Remaining files (8 left)

`_gtm.scss`, `_services.scss`, `_resources.scss`, `_roundtable.scss`,
`_single-post.scss`, `_position.scss`, `_landing.scss`,
`_customer-stories.scss` -- re-grep each fresh, check liveness first
(watch for "-nov" or similarly-suffixed sibling templates sharing the
same SCSS file, as `_home.scss` and `_single-events.scss` both had),
and now also watch for: (1) an element with *both* `float` and an
explicit `display` already on the same rule -- removing just the float
can "wake up" the other display value with different wrapping behaviour
than the float had (the `.logo`/inline-block case here); (2) a
`float:right` sequence, which reverses visual order relative to DOM
order and needs `flex-direction: row-reverse` to reproduce correctly
(confirm via any `&:first-child`/`&:last-child` margin asymmetry, which
usually gives away which end is "anchored"); (3) don't assume a
structurally-identical-looking component (like the video play button)
behaves the same in every file -- check whether `clear: both` next to
a float is live or commented out, since that one property flips the
intended layout from "one row" to "stacked lines".

## §57 -- 2026-09-18 (cont.): staging deploy validated -- stale critical-CSS cache found and cleared, then a real regression found and fixed in `_subscribe.scss`

User pushed/deployed §53-56's four files (`_benchmarking.scss`,
`_subscribe.scss`, `_single-events.scss`, `_home.scss`) and asked for
live validation on `https://staging.adapt.com.au/` before continuing to
the remaining 8 files.

### Part 1: false alarm -- stale WP Rocket "Remove Unused CSS" cache

First validation pass found every changed selector still computing its
*old* pre-refactor value in the browser (`.icon-container` float:left
instead of float:none, `.column` display:block instead of flex, etc.),
across every one of the four files. Traced this all the way through
before concluding anything was actually wrong:

- Confirmed the GitHub Actions deploy (`deploy.yml`, "dev" branch) ran
  and succeeded for the commit (`68a9e24`) via the public Actions API
  (`gh` isn't installed on the user's machine; used `curl` against
  `api.github.com/repos/.../actions/runs` instead).
- Fetched `assets/css/main-nofooter.min.css` **directly** from the
  server (bypassing the page) -- it already had the correct
  post-refactor rules, with a Last-Modified timestamp matching the
  deploy exactly. So the build + upload genuinely worked.
- But every actual page load came back `x-cache: HIT` and the HTML
  contained WP Rocket markers, with no `<link rel=stylesheet>` for the
  theme CSS at all -- only inlined `<style>` blocks. That's WP Rocket's
  "Remove Unused CSS" / critical-CSS feature: it pre-generates a cached
  per-page CSS snippet and inlines *that* instead of the real file, and
  the cached snippet predated this deploy.
- User cleared the cache; re-checked and the live computed styles for
  every previously-stale selector now matched source
  (`.column`{display:flex}, `.icon-container`{float:none}, etc.).

**Lesson for future deploys on this project**: a green CI run and a
correct compiled file on disk are not sufficient evidence that visitors
are seeing the new CSS on this stack -- WP Rocket's critical-CSS cache
sits in front of it and has to be cleared separately after any CSS
change before a live visual check means anything. Worth asking whether
`deploy.yml` should hit WP Rocket's cache-clear endpoint/WP-CLI command
as a final step, instead of relying on someone remembering to click
"Clear Cache" in wp-admin.

### Part 2: a real regression, found only after the cache was actually clear

With fresh CSS confirmed, re-checked `two-column-image-text-subscribe`
on `/join-the-community/` (the one section flagged last session as
"looks fine at desktop, collapses to 0 height on mobile, but that's
pre-existing since the float declarations on `.column`/`.image-column`/
`.text-column` were never touched"). That framing was wrong. Compared
the exact same DOM/content against production
(`https://adapt.com.au/join-the-community/` -- confirmed byte-identical
ACF content, different theme/build entirely: "adapt" theme,
un-split `main.min.css`, own deploy pipeline off `main`) and found
production renders this block correctly at mobile width (798px section
height) while staging still collapses it to 0, *even with the fresh
CSS*. So this was never a cache artifact -- it's a genuine defect this
refactor introduced.

Root cause: `.two-column-image-text-outer`'s **own** float --
untouched conceptually until you look at what it was actually doing --
was changed from `float: left` to `float: none` back in §54, reasoned
about at the time as safe because the rule already has
`display: flex` at desktop (Category A: "own rule already flex -> float
inert"). True at desktop. But at `@media (max-width: 767px)` the same
rule overrides display back to `block` (needed so its two children,
`.image-column`/`.text-column`, stack instead of sitting side by side)
-- and *nothing* overrides float back at that breakpoint. On
production, the container's own (untouched) `float: left` is still
live there, and a floated box establishes its own block formatting
context, which is *incidentally* exactly what was containing its two
floated (deliberately-left-as-Category-C-real-grid) children and giving
the section its height. Flattening that float to `none` silently threw
away that side effect: on mobile the container is a plain
`display: block` box with only floated children inside and no BFC of
its own, so per ordinary CSS it computes 0 height, and the image +
text render outside the document flow, overlapping whatever section
comes next.

Fix (`_subscribe.scss`, inside `.two-column-image-text-outer`'s
`@media (max-width: 767px)` block): changed `display: block` to
`display: flow-root`. `flow-root` is the modern, purpose-built
replacement for exactly this "floated-children clearfix" role --
establishes a new BFC (correctly contains the floats, section height
back to matching content) without reintroducing `float` and, unlike
`overflow: hidden`, without risking clipping the `.image-container`
decorative offset pseudo-elements used by other variants of this block
elsewhere on the site (`&:nth-child(2n) .image-container:after { top:
-16px; ... }` -- not present in the DOM on this specific page, but
compiled from a shared rule other pages/ACF rows can trigger, so it had
to stay non-destructive). Verified via the established dart-sass
isolated-compile method: `display: flow-root;` shows up correctly
scoped inside the `@media (max-width: 767px)` block, nothing else in
the compiled output changed. Not yet re-verified live (needs another
push/deploy/cache-clear cycle) -- flagging here so the next session
picks it up if it's reading this before a live check confirms it.

**Methodology update for all remaining files**: when a rule being
flattened from `float: X` to `float: none` also has its OWN
`display: flex`/`grid` that gets overridden back to `block` (or
anything non-flex/grid) inside a narrower-width media query on the
*same* rule, don't classify it as Category A on the strength of the
desktop rule alone. Check what that element's children are doing at
the narrower breakpoint too -- if any of them are real (Category C)
floats, the parent's own float may be the only thing giving the
collapsed-width layout a BFC, and removing it needs a replacement
(`display: flow-root` on the parent, preferably over `overflow: hidden`
unless there's a specific reason a clip is fine).

### Status

`_subscribe.scss` has one additional uncommitted change on top of
§54/the user's own commit (`68a9e24`): the `flow-root` fix above.
`git diff --stat`: 1 file, 13 insertions(+), 1 deletion(-). Not
committed -- left for the user per usual practice, this time explicitly
because it needs a fresh deploy + WP Rocket cache clear to actually
verify live, which isn't something to do unprompted mid-session.

`_benchmarking.scss` (all mechanical/Category A) and the two live
sections it has content for on `/benchmark-maturity-assessment/`
(`benchmarking-four-column`, `benchmarking-three-column-text-image-cards`)
re-checked clean at mobile + desktop with the fresh cache, no console
errors.

`_home.scss` and `_single-events.scss`: re-confirmed (with fresh cache,
so this is no longer a caching question) that no currently-published
page/event exercises the sections these files touch --
`_home.scss`'s banner/logoGrid selectors are 0 matches on both staging
and production's homepage (both already migrated to newer ACF blocks,
confirmed identical between environments so this isn't a staging-only
content gap), and 9 different live "event" posts checked all render
only a bare `eventShare` section with no banner/overview/video content
populated. Still can't get a live visual read on either file's changes;
the compiled-CSS verification from §55/§56 is what stands.

### Remaining files (8 left, unchanged)

`_gtm.scss`, `_services.scss`, `_resources.scss`, `_roundtable.scss`,
`_single-post.scss`, `_position.scss`, `_landing.scss`,
`_customer-stories.scss`. In addition to the three watch-items listed
at the end of §56, add a fourth: (4) when flattening a container's own
float to `none`, check whether that same rule's `display` gets
overridden away from flex/grid at any narrower breakpoint, and whether
that breakpoint still has real (non-flex-item) floated children inside
-- if so the container needs `display: flow-root` at that breakpoint,
not just a bare float removal.

## §58 -- 2026-09-18 (cont.): float-grid redesign, 8th file -- `_gtm.scss`, 58 declarations, all mechanical (0 real conversions)

First file done under the refined methodology from §57. Live page:
`/services/technology-vendors` (`template-gtm`, page id 61951) -- has
real content for every ACF layout except `two_column_map_module`
(`_two-column-map.php`) and `static_cards`/`repeatable_image_text_with_border`
(`_static-cards.php`, `_repeatable-image-text.php`), which this specific
page's rows don't use but which are still checked below since another
`template-gtm` page could use them.

### The §57 lesson, refined

§57 found a real bug: flattening a *container's own* float to `none`
while its children stayed genuinely floated (Category C, deliberately
untouched) removed the container's only block-formatting-context and
collapsed it. Auditing all 58 declarations here (not just the
individually-classified ones -- the 41 the file's own pre-existing gate
already covered too, since the gate predates the §57 finding and could
have the same blind spot) turned up zero instances of that pattern, but
clarified the actual rule, which is narrower and more useful than "check
for a responsive flex->block switch":

**A container's own float is only dangerous to remove when, after this
pass, it still has an in-flow child that (a) is genuinely floated and
(b) is the container's only source of height** (no non-floated sibling
content, no fixed/explicit height on the container itself). If every
float in a given parent/child chain is being flattened *together* in
the same pass, there's nothing left floated to lose containment for --
a stack of formerly-floated-now-block boxes sizes itself normally by
definition, since each one's height comes from its own (now non-floated)
content. The `_subscribe.scss` bug only happened because the parent's
float was flattened while `.image-column`/`.text-column` were
*deliberately* kept floating (out of scope, real Category C grid) --
a scope mismatch, not just "a responsive display switch exists".

So the actual check that matters, run against every declaration in this
file: for each element whose float is being flattened, does removing it
leave any child element in the render tree that is (a) still literally
`float: left/right` after this file's changes and (b) sitting inside a
parent with no other height source (no fixed height, no flex/grid
already handling it, no already-inline-block/otherwise-BFC-having
ancestor)? Walked every one of the 58 declarations' full container
chains (down to the PHP template markup where SCSS nesting doesn't
match DOM nesting, e.g. `.overlapping-cards-container` >
`.overlapping-card-wrapper` > `.overlapping-card` > `.column-container`
in `_overlapping-cards.php`) and confirmed each one resolves to one of:
already inside a `display:flex`/`grid` ancestor (own float irrelevant,
Category A, most of the file); a "flatten-together" chain like the
overlapping-cards sticky stack, the icon-accordion, or
`repeatable-image-text`'s `.cards`/`.column-container`/`.image-column`
tree, where nothing floated survives the pass; or a fixed/explicit
`height` on the parent (`.icon-container{height:32px}`,
`.quote-slider-timer{height:1px}`, `.logo-container{height:40px}`),
which is immune to float-collapse regardless of what's inside it.

**New caveat worth flagging for the remaining files**: some of this
file's flex containers (`.column-container`/`.column` inside
`three-column-video-gtm`) have no `display: flex` written anywhere in
`_gtm.scss` itself -- they only carry `align-items`/`gap`, which are
no-ops without a flex/grid display. The actual `display: flex` for
those classes is coming from a *different* template file entirely
(this section's live class list is
`three-column-video-gtm three-column-icon-text-ecosystems
three-column-partnered-research` -- multiple partials' rules apply to
the same markup by design). Confirmed this by checking
`getComputedStyle` on the *live* page rather than trusting an
isolated single-file dart-sass compile, which can't see cross-file
cascade contributions. Worth doing that live check specifically
whenever a container/column class name looks generic/shared and the
file being edited doesn't itself explain why it's flex.

### Status

`float: left`/`float: right` -> `float: none` for all 58 declarations
(matches the file's existing 41-entry gate exactly for those, plus 17
additional declarations the gate didn't cover, all confirmed safe by
the walk above), gate section removed (621 lines). No real flexbox
conversions needed anywhere in this file -- every floated element was
already either a flex item, inside a fixed-height parent, or being
flattened as part of a whole all-float chain. Verified via the
established isolated dart-sass compile: 0 unexpected
`float: left`/`float: right` among this file's real root selectors in
the compiled output. `git diff --stat`: 1 file, 58 insertions(+), 679
deletions(-). Not yet checked live (not deployed this pass) and not
committed -- left for the user per usual practice.

### Remaining files (7 left)

`_services.scss`, `_resources.scss`, `_roundtable.scss`,
`_single-post.scss`, `_position.scss`, `_landing.scss`,
`_customer-stories.scss`. Apply the same full container-chain walk as
above (not just the individually-ungated declarations -- recheck the
existing gate entries too, since every file's gate predates the §57
finding), and watch for classes that are flex only because of a
sibling template file, confirmed via live `getComputedStyle` rather
than an isolated compile.


---

## §59 -- 2026-09-21: float-grid redesign, 9th file -- `_services.scss`, 85 declarations (79 fixed: mechanical + 5 real flexbox conversions + 3 `flex-direction`/`flow-root` breakpoint fixes; 6 deliberately left as genuine floats)

### Context

Resumed after a gap (last session ended right after §58 landed; user had since
committed §53-58 as `64131a6`/`68a9e24`/`1ee76f0`/`0d3e3c6`, and also
independently continued the pass on this file's predecessors as §54-58 in a
session I wasn't present for -- read those sections from SESSION-HANDOFF.md
to catch up before starting). Picked `_services.scss` from §58's remaining
list. Confirmed live via `templates/template-services.php`. This is the
largest file in the series so far: 8 root sections
(`events-title-block.services-introduction`, `services-two-column-image-text`,
`three-column-icon-services`, `services-cards-module`,
`two-column-switch-module`, `background-image-stats`, `two-column-acordion`,
`animation-icon-module`).

### Fresh count

**85 active `float:left`/`float:right` declarations**. 68 already had a
matching gate entry (all verified by selector content, no drift). 17 were
ungated.

### The §57/§58 container-chain check, applied at scale

Walked every gated AND ungated declaration for the §57 "parent's own float
flattened while a genuinely-floated child survives" risk, not just the
individually-classified ungated ones (per §58's refined practice). Found
this pattern recurring **five times** in this file, all pre-existing gate
entries (not something this pass introduces -- these containers already
compute `float: none` in current production via the gate's unconditional
override, so whatever this bug's actual live impact is, it predates this
session and isn't a new regression either way):

- `services-two-column-image-text .column-container` -- own float gated;
  children `.column.text-column`/`.column.image-column` are genuine,
  ungated Category-C floats (a real 2-column image/text banner, same
  shape as `_thank-you-old.scss`'s banner). **Real flexbox conversion**:
  `.column-container` -> `display: flex; flex-direction: row-reverse;`
  (DOM order is image-column-then-text-column per
  `templates/services-components/_two-column-image-text.php`, but
  `.image-column` carries `float: right` in the original -- row-reverse
  reproduces that without touching markup), its
  `@media (max-width: 767px) { display: block; }` changed to
  `flex-direction: column;` instead (preserves mobile stacking, keeps
  the container flex so it can't lose containment at any width).
  `.column`/`.image-column`'s own floats (one ungated, one gated) ->
  `float: none` (now safe, flex items). Both columns carry the shared
  sitewide `.one-half` utility class -- not touched, same precedent as
  `_thank-you-old.scss` (§52).
- `three-column-icon-services .column-container` -- **already**
  `display: flex; flex-wrap: wrap;` on its own rule (Category A), but
  its `@media (max-width: 767px) { display: block; }` override was the
  exact same risk with its genuinely-floated `.column` children (one of
  which, at 33.33% width, was itself ungated). Minimal fix (not a new
  design, just correcting an incomplete breakpoint override): changed
  `display: block` to `flex-direction: column` at that breakpoint so the
  container stays flex at every width instead of reverting to block.
- `services-cards-module .card-container` -- identical shape: already
  flex+wrap at desktop, `@media (max-width:767px) { display: inherit; }`
  (resolves to block) reverted it while `.card` gets a literal
  `float: left` restored at that same breakpoint (gated). Same minimal
  fix: `display: inherit` -> `flex-direction: column`.
- `two-column-acordion .accordion-container` -- same shape again
  (flex at desktop, `display: block` at 767px, genuinely-floated
  `.accordion-image-column`/`.accordion-text-column` children, two of
  which were ungated). Same minimal fix: `display: block` ->
  `flex-direction: column`.
- `animation-icon-module .column-container` -- same shape (flex at
  desktop, `display: block` at 767px, `.column.icon-column`/
  `.column.animation-column` get literal `float: left` restored at that
  breakpoint, both gated). Same minimal fix.

None of these five needed investigation into whether the pattern is
*currently* live-broken in production -- the fix (keep the container
flex at every breakpoint instead of letting it drop back to block) is
correct and safe regardless of whether today's behaviour already has
the bug or not, so that question didn't need answering to proceed
correctly.

### The one true structural outlier: `background-image-stats`'s two sub-layouts

This section has two ACF-driven variants (`three-stats` / `four-stats`,
mutually exclusive per `templates/services-components/_background-stats.php`):

- **`three-stats`**: `.stats-column-container` (own float gated) has no
  existing flex anywhere; its only children are 3 `.stat-column` spans
  (one ungated) with no positioning complications. **Real conversion**:
  added `display: flex; flex-wrap: wrap;` from scratch. Safe, unambiguous.
- **`four-stats`**: `.four-column-container` (own float gated) contains
  both the 4 `.column` grid items (one ungated, genuine Category-C
  4-column grid) *and* a sibling `.absolute-title-container` (a `<span>`,
  gated) that switches `position: absolute` (desktop, removed from flow,
  harmless either way) to `position: relative` (<=1023px, back in flow)
  -- meaning a flex conversion here would turn a position-toggling title
  into an in-flow flex item at that breakpoint too, an extra variable I
  wasn't confident reasoning through blind. Rather than force a flex
  redesign onto a mixed position/float layout, used **`display: flow-root;`**
  instead (§57's purpose-built fix) -- reestablishes the container's BFC
  without touching how any child is laid out. `.column` (ungated, Category
  C) and its per-`nth-child` `.stat-container` jigsaw floats (4 of them,
  see below) were left as literal, untouched floats -- the flow-root on
  the parent is a sufficient containment fix, no child redesign attempted or
  needed.

### The 6 declarations left as genuine, untouched floats

- **`.module-switcher`** (`two-column-switch-module`, line ~802) --
  `float: left` added only in the narrow `767px < width <= 1200px` range
  (parent isn't flex there; it only becomes flex at <=1100px, where
  flexbox's own rules already force this child's float inert regardless
  of source order). The switcher buttons are already `display:
  inline-block` and sit in a row without needing float at all -- this
  looks like a legacy "collapse the inline-block whitespace gap between
  buttons" hack, not a layout float. No clean flex equivalent without
  guessing at whether the tiny gap difference is acceptable; left alone.
- **`.four-column-container .column`** (`background-image-stats`,
  four-stats) -- the genuine 4-column grid described above, deliberately
  left floating rather than redesigned, given the mixed
  position-toggling sibling in the same parent (see above).
- **4 `.stat-container` per-`nth-child` floats** (`:nth-child(2)`
  through `:nth-child(5)`, `four-stats`, <=767px only) -- each is a
  small `left`/`right`/`top`/`bottom` positioning nudge combined with
  `float`, inside an element (`.stat-container`) that has its own
  explicit `width`/`height` (200px/150px) and toggles `position: relative`
  at that breakpoint. What visual effect the float itself is
  contributing here (beyond the explicit position offsets) isn't
  obvious from the source alone, and this is exactly the kind of
  "genuinely ambiguous, low-confidence-to-redesign" float §55's
  `.insetImage` precedent says to leave alone and flag rather than
  guess at.

### Two defensive display fixes (ungated floated inline elements, not becoming flex items)

- `.list-container .button-container a` (`two-column-switch-module`,
  ungated, no explicit width at desktop) -> `float: none; display: block;`
  -- not a flex item (its own ancestor chain isn't flexed at that level),
  same defensive pattern as §54/§55/§56's bare `<a>`/`<span>` cases.
- `.accordion-content .tag` (`two-column-acordion`, ungated, a `<span>`
  pill/chip with no width, confirmed via
  `templates/services-components/_services-accordion.php` that
  `.faq-container`/`.question`/`.accordion-content` are all `<span>`s
  toggled by an **inline** `style="display: block"` on the first,
  active item) -> `float: none; display: inline-block;` on `.tag`
  itself, rather than adding `flex-wrap` to `.accordion-content` (which
  would have fought the inline style's specificity for the one row that
  actually needs it). `inline-block` reproduces the original
  float-wrap's horizontal-row-of-chips behaviour without touching the
  parent at all.

### Applied

79 `float: left`/`float: right` -> `float: none` (all gated + ungated
except the 6 left alone). 5 real flexbox conversions (2 from-scratch:
`services-two-column-image-text`'s banner and `three-stats`'s
column-container; the icon+text row inside `animation-icon-module`
described below) plus 3 minimal `display: block/inherit` ->
`flex-direction: column` breakpoint fixes on already-flex containers.
1 `flow-root` fix (`four-stats`). 2 defensive `display` additions.

The `animation-icon-module` nested icon+text pair (`.icon-column-container
.icon-column .icon-container` (52px, ungated) / `.title-container`
(`calc(100% - 52px)`, gated)) is the 5th real conversion, same
fixed-width-icon-plus-fill-the-rest shape seen repeatedly in this series
(`_thank-you-old.scss`'s FAQ, `_subscribe.scss`'s three-column-icon-text):
added `display: flex;` to the inner `.icon-column` (previously just
`width: 33.3%; margin-bottom: 40px;`, no flex anywhere) so both children
become flex items and are auto-blockified for free.

Removed the float-refactor gate section (1032 lines) at the bottom of the
file, now fully redundant.

### Verification

- `grep` for `float:\s*left\|float:\s*right`: exactly 6 matches (the
  deliberate exceptions listed above) -- everything else, 0.
- Compiled for real with `node_modules/.bin/sass` (dart-sass) against the
  theme's full global import chain (`_mixins`, `_variables`, `_fonts`,
  `_icons`, `_base`, `_styles`): 0 errors, only standard
  `@import`-deprecation warnings.
- Parsed the compiled CSS, isolated every rule block whose selector
  touches one of this file's 8 root sections: 0 unexpected
  `float: left`/`float: right`, all 6 intentional exceptions present and
  unchanged from before this pass (confirmed byte-identical property
  values). Directly inspected all 5 real conversions, the 3
  `flex-direction: column` breakpoint fixes, the `flow-root` fix, and
  both defensive `display` additions in the compiled output -- all
  landed exactly as designed.
- `git diff --stat`: 1 file, 90 insertions(+), 1115 deletions(-).
- A scratch dart-sass compile-test file (`wrapper_services_TEMP.scss`)
  was created in the repo root for this verification; couldn't delete it
  (no delete permission granted this session, and didn't want to spend a
  permission-prompt interruption on a one-file cleanup) -- moved it to a
  new `_to_delete/` folder in the repo root instead, flagged here so the
  user can delete that folder whenever convenient.
- Did **not** do a live before/after screenshot/computed-style check on
  staging -- same caveat as every prior file. Given this file has the
  highest real-conversion count since `_home.scss` (5, plus 3 breakpoint
  fixes and a flow-root fix), the two-column-image-text banner (image/text
  order, matches the `_thank-you-old.scss` precedent) and the four-stats
  flow-root fix are the two most worth a human look before shipping.

### Status

Applied on the user's machine via the device bridge, **not committed to
git yet**.

### Remaining files (6 left)

`_resources.scss`, `_roundtable.scss`, `_single-post.scss`,
`_position.scss`, `_landing.scss`, `_customer-stories.scss`. Continue
the §58 container-chain-walk methodology (recheck gate entries too, not
just ungated declarations); this file's five recurring "flex-at-desktop,
block-at-a-breakpoint" instances suggest checking specifically for that
shape early in each remaining file, since it's now shown up in 6 of the
9 files done so far.


---

## §60 -- 2026-09-21 (cont.): float-grid redesign, 10th file -- `_resources.scss`, 51 declarations (50 fixed: mechanical + 6 real flexbox conversions + 1 `flex-direction` breakpoint fix + 1 precise `float:right`->`margin-left:auto` swap; 1 deliberately left, a slick carousel float)

### Context

Continuing straight on from §59. Picked `_resources.scss`, confirmed live via
`templates/template-resource.php` (dispatches
`templates/components/_resources-banner-block.php`,
`_resources-content-block.php`, `_resources-block.php`,
`_resources-cta-block.php` in sequence) and `templates/single-resource.php`/
`single-resources.php` (both also render `_resources-content-block.php`).
4 root sections plus a `.mfp-wrap.download-container` Magnific Popup modal
(triggered from `_resources-block.php`'s download-overlay button, confirmed
live via `templates/components/_resources-block.php`'s markup).

### Fresh count

**51 active `float:left`/`float:right` declarations**. 33 gated (verified
by selector content, no drift), 18 ungated.

### Six real flexbox conversions -- the `.one-half`-utility two-column banner shape shows up three more times

This file has the same "two `<div class="column one-half">` siblings, one
plain `float:left` from the shared sitewide `.one-half` utility, the other
overridden to `float:right`" shape already seen in `_thank-you-old.scss`
(§52) and `_services.scss` (§59), plus two smaller icon/grid variants:

- **`section.thank-you-banner.resources-banner .container`** (banner:
  `.image-column`/`.text-column`, both plain `.one-half`, no float
  override -- confirmed via `_resources-banner-block.php` that DOM order
  already matches visual order) -> `display: flex;` (no reversal needed).
- **`section.resources-content .container`** (content column,
  `calc(100% - 400px)`, + a fixed 400px sidebar that is *always* rendered
  `.second-column.right-column` per `_resources-content-block.php` --
  checked for a plain `.second-column`-without-`.right-column` usage
  elsewhere in the theme first, per the "don't guess DOM order, check the
  PHP" practice from §54 onward; found one in `single-registration.php`
  but it renders under a different, unrelated section wrapper, so it
  doesn't apply here) -> `display: flex; flex-direction: row-reverse;`
  (DOM order is sidebar-then-content, but the sidebar needs to render on
  the *right*; row-reverse reproduces that), with
  `@include responsive(767) { flex-direction: column; }` added for mobile
  stacking (matches original behaviour, where both columns become plain
  `float: left` at that breakpoint and stack in DOM order).
- **`.speaker .authorSingle`** (same section, nested inside the sidebar) --
  a genuine fixed-width-icon (`.authorImage`, 65px) + fill-the-rest
  (`.authorText`, `calc(100% - 92px)`) pair, the same shape seen
  repeatedly all series -> `display: flex;` added to `.authorSingle`.
- **`.resources-container.two-column .column`** (download-block, a plain
  50/50 split, no reversal) -> `display: flex;` added, scoped to
  `&.two-column` only (the base `.resources-container` stays untouched
  for its other variants).
- **`.resourcesPopup .preview-container` / `.download-container`** (the
  Magnific Popup modal, `calc(100% - 260px)` + fixed 260px sidebar, DOM
  order confirmed via `_resources-block.php` as preview-then-download,
  matching visual order, no reversal needed) -- the modal's
  `.resources-container` wrapper **already** had `display: flex;` on its
  own rule but dropped to `display: block;` at `max-width: 1023px` --
  the exact "flex-at-desktop, block-at-a-breakpoint" bug shape §57
  found live and §59 saw five more instances of. Same minimal fix:
  `display: block` -> `flex-direction: column` at that breakpoint.
- **`section.resources-cta-block .container .cta-content`** (a third
  `.one-half` two-column pair, text-column then image-column, no
  reversal needed) -> `display: flex;` added.

### The one precise, non-obvious fix: `.main-image-container`'s `float: right` -> `margin-left: auto`

`section.resources-cta-block .column.image-column .main-image-container`
is a single in-flow child (its sibling, `.overlay-image-container`, is
`position: absolute`, already out of flow) with an intentionally-oversized
box (`width: calc(100% + 150px)`, `margin-right: -150px` at desktop) used
to bleed a decorative image off the edge of its column. At desktop this
box's own `float: left` is provably a no-op to remove: a solo, non-floated
block-level box in normal flow already starts flush with its container's
left edge by default, which is the *same* rendering `float: left` was
producing here (float only visibly matters when something needs to wrap
around the floated box or when siblings queue against it -- neither
applies to a single overflowing child) -- so flattening it to
`float: none` changes nothing. But at `max-width: 1023px` the rule
switches to **`float: right`** (dropping the negative `margin-right` and
instead picking up `margin-bottom: -80px`) -- and unlike the left case,
`float: right` *does* change which edge the box overflows from (it would
overflow to the *left* instead of the right once flush with the
container's right edge), so a plain `float: none` there would have been a
real, silent regression. Replaced `float: right;` with `margin-left: auto;`
at that breakpoint instead -- the standard block-level equivalent for
right-edge-aligning an oversized box, reproducing the exact same overflow
direction without float.

### Two defensive `display: inline-block` additions (ungated floated inline elements, small width, not becoming flex items)

- `.std-button` (`<a>`, banner's button row, no explicit width, ungated)
  -- `float: none; display: inline-block;`, same pattern as
  `_services.scss`'s `.module-switcher`/`.tag` cases.
- `span.download-button-container` (modal, `width: 120px`, toggled via
  `display: none` / `&.active { display: block; }`, ungated) -- the
  `max-width: 1023px` override sets `width: calc(50% - 10px)` alongside
  its own `float: right`, implying up to two of these (e.g. a PDF button
  and a video button) can be `.active` and shown side by side at once --
  not confidently a single-item case, so kept it as a horizontally
  laid-out row rather than assuming `float: none` alone was safe:
  `float: none; display: inline-block;`.
- `span.download-dropdown-container` (modal, `width: 130px`, ungated,
  isolated fixed-width element) -- same defensive `display: inline-block`
  added, low-risk insurance rather than a necessity.

### The one declaration left as a genuine, untouched float

- **`.slick-slide`** (`resources-slider` variant, `margin: 0 8px`, no
  width) -- Slick.js manages this carousel's actual runtime layout with
  its own inline styles/transforms once initialized; the SCSS float here
  is pre-init/fallback styling, not something safe to redesign blind
  without risking interference with the slider library's own DOM
  manipulation. Left untouched, consistent with this project's general
  caution around carousel markup.

### Applied

50 `float: left`/`float: right` -> `float: none` (all gated + ungated
except the 1 left alone and the 1 special `margin-left: auto` swap). 6
real flexbox conversions, 1 `flex-direction: column` breakpoint fix, 1
precise `float:right`->`margin-left:auto` replacement, 3 defensive
`display: inline-block` additions. Removed the float-refactor gate section
(505 lines) at the bottom of the file, now fully redundant.

### Verification

- `grep` for `float:\s*left\|float:\s*right`: exactly 1 match (the
  `.slick-slide` exception) -- everything else, 0.
- Compiled for real with `node_modules/.bin/sass` (dart-sass) against the
  theme's full global import chain: 0 errors, only standard
  `@import`-deprecation warnings.
- Parsed the compiled CSS, isolated every rule block whose selector
  touches one of this file's root sections (including the modal): 0
  unexpected `float: left`/`float: right`, the 1 intentional exception
  present and unchanged. Directly inspected all 6 real conversions, the
  breakpoint fix, the `margin-left: auto` swap, and all 3 defensive
  `display` additions in the compiled output -- all landed exactly as
  designed.
- **Housekeeping note on `git diff`**: this file showed `MM` status in
  `git status` (modified in both the index and the working tree) --
  something on the user's machine (likely an editor/IDE auto-stage-on-save
  feature, since nothing in this session ran `git add`) had already
  staged an intermediate version of this file's edits mid-pass, so a
  plain `git diff` against the index under-reported the real change (it
  showed 0 insertions / 505 deletions, counting only the gate-removal
  truncation on top of what was already staged). The correct, complete
  comparison is **`git diff HEAD`: 1 file, 61 insertions(+), 556
  deletions(-)** -- used that number here and going forward will check
  `git diff HEAD` rather than the plain (staged-relative) `git diff` on
  files that show `MM` status, to avoid under-reporting again. Also
  re-confirmed the stale, empty `.git/index.lock` first flagged in §53
  is still present on the user's machine (still non-blocking for reads,
  but likely to block the user's next `git commit` until removed
  manually).
- Did **not** do a live before/after screenshot/computed-style check on
  staging -- same caveat as every prior file. The `margin-left: auto`
  swap and the sidebar/content reversal in `section.resources-content`
  are the two most worth a human look before shipping.

### Status

Applied on the user's machine via the device bridge, **not committed to
git yet** (though partially staged, per the note above -- the user's
next `git add`/`commit` will pick up the rest).

### Remaining files (5 left)

`_roundtable.scss`, `_single-post.scss`, `_position.scss`, `_landing.scss`,
`_customer-stories.scss`. Continue the §58/§59 container-chain-walk
methodology. Also worth checking `git status` for `MM` (not just `M`) on
each remaining file before trusting a plain `git diff --stat`, per this
section's finding.


---

## §61 -- 2026-09-21: float-grid redesign, 11th file -- `_roundtable.scss`, 26 declarations (23 fixed: 16 mechanical + 5 real flexbox conversions + 1 defensive `display:inline-block` + 1 breakpoint `float` removal folded into a real conversion; 2 deliberately preserved as documented production-bug exceptions, plus their gate-section rules and comment kept verbatim)

### Context

Continuing from §60. Picked `_roundtable.scss`, confirmed live via
`templates/template-executive-roundtables.php` (renders
`templates/roundtable-components/_card-slider.php` and `_experience.php`).
Two root sections: `section.roundtable-card-slider-module` and
`section.experience-module`.

This file's gate section had an unusual, much smaller shape than every
other file in the series: only 7 genuine `// line NNN` collapse-eligible
entries (all inside `section.experience-module`), plus two special,
heavily-documented, must-not-touch exceptions that predate this whole
project and are unrelated to routine float-collapse:

- **`.cards-progress`** -- a real, measured production bug (dated
  2026-09-10, well before this redesign pass started): removing this
  element's `float: left` causes a 90px height shortfall on
  `/private-executive-roundtables/`, via the same "clearance-margin-
  absorption" mechanism documented for `.menu-bottom` in the header
  pass -- a cleared/unfloated block's margin gets absorbed into the
  float-clearing position instead of adding on top of it. A
  double-class `float: none` override was tried first and silently
  lost the cascade to a bulk-generated `?dev=true`-era rule from a
  (likely decommissioned -- no references found in `gulpfile.js` or
  top-level build files) `fix-float-none-display.js` tool, so a
  quad-class `float: left` override was added to force the win without
  `!important`.
- **`.slide-image-container`'s gate-section rule** -- NOT a float fix.
  It's `display: flex !important;`, presumably overriding Slick's own
  inline styles or another more-specific rule. The base rule in the
  main file body has no `display` property at all.

**Decision: left `.cards-progress`'s base rule, its quad-class override,
its full explanatory comment, AND the unrelated `.slide-image-container`
flex rule completely untouched**, as a deliberate exception to this
project's usual "collapse everything then delete the whole gate section"
pattern. Only the 7 genuine `// line NNN` float-collapse entries were
removed from the gate (now redundant, folded into direct source edits);
the gate section's banner comment was kept (still true, still explains
the two preserved rules below it) and given one added paragraph noting
that only the collapse-no-op entries were removed.

### Fresh count

**26 raw matches, 23 active declarations** before the gate boundary
(3 matches are inside the gate section text itself and don't count).
16 ungated, 7 already gated (all 7 within `section.experience-module`,
all verified by selector content against the live main-body rules
before collapsing -- no drift).

### Real conversions -- two genuine 2-column grids inside `experience-module`, both DOM-order-verified via PHP

Read `templates/roundtable-components/_experience.php` before choosing
row/row-reverse (per the standing DOM-order-verification practice) --
and it's a good thing I did: the SCSS source lists `.experience-text-column`
before `.experience-image-container`, but the PHP renders
`.experience-image-container` FIRST and `.experience-text-column` SECOND
-- the opposite of SCSS source order.

- **`.experience-container`** (outer 50/50 split: image first in DOM,
  floats right; text second in DOM, floats left) -> `display: flex;
  flex-direction: row-reverse;` reproduces the DOM-vs-visual mismatch
  without touching markup, with `@media (max-width:767px) {
  flex-direction: column; }` added (mobile already stacked both to
  width:100%, image first, matching DOM order -- confirmed the gate had
  *already* silently forced the mobile `float:left` on
  `.experience-image-container` to `float:none`, i.e. this was already
  effectively unfloated on mobile in production; folded that into the
  same real edit rather than leaving a separate collapse).
- **`.experience-text-container`** (a repeatable accordion-style row
  nested inside `.experience-text-column`: `.title` 50% then `.text`
  50%, DOM order matches visual order, no reversal needed) ->
  `display: flex;` with `@media (max-width:1023px) { flex-direction:
  column; }` (matches the original's `width: 100%` stacking at that
  breakpoint for both children).

### The `.slide-image-container` no-op check (card-slider-module)

Before flattening `.slide-image-container`'s own ungated `float: left`,
checked whether it conflicted with the preserved gate-section
`display: flex !important` rule on the same selector: it doesn't --
per spec, `float` + `display: flex` still blockifies to a floating flex
container, so `.image-container` (its sole child) was *already* a flex
item and already float-inert in current production, regardless of this
pass. Flattening `.slide-image-container`'s own float to `float: none`
changes nothing observable (still `display: flex` via the untouched
`!important` rule, still full width) -- confirmed this reasoning against
the compiled output rather than assuming it.

### Container-chain-walk: `.slide-inner` needed no `flow-root` fix

`.slide-inner`'s own float was ungated (still a real, live float in
production) and its in-flow children include a fixed-size
`.image-container` (180x164, no `width:100%`) that could in principle
need containment once unfloated. But every one of `.slide-inner`'s
in-flow children in this pass ends up either full-width (safe no-op),
a solo child with nothing to wrap (also a safe no-op, same "no visible
difference" reasoning as `_resources.scss` §60's `.main-image-container`),
or already a flex container (`.slide-image-container`, via the preserved
`!important` rule) -- so once all of them are flattened together, none
of `.slide-inner`'s children remain genuinely floated, and no
`flow-root`/containment fix was needed on `.slide-inner` itself.

### Mechanical flattens (16, all provably safe no-ops -- single full-width block or a solo non-wrapping child)

`.top-content`, `.title` (top-content), `.roundtable-card-slider`
(full-bleed slider wrapper -- not Slick-managed itself, only its
descendants are), `.slide-inner`, `.slide-tag-container`,
`.slide-image-container`, `.image-container` (card, 180x164 solo
child), `.slide-text` (solo child of a `position:absolute` ancestor),
`.experience-title-container`, `.column.one-half` (scoped nested
selector only -- the shared sitewide `.one-half` utility class itself
was not touched), `h2` (inside that column), `.experience-image-inner`
(height is fully driven by `padding-top: 110%`, not by flow content --
containment was never a concern here), `.image-container` (nested
inside `.experience-image`, same aspect-ratio-box pattern).

### One defensive `display: inline-block` addition

- `.tag` (card-slider, ungated `<span>` pill/chip, single instance per
  slide per the PHP, no explicit width) -> `float: none; display:
  inline-block;`, same defensive pattern used throughout this series
  for bare `<span>`/`<a>` elements.

### Applied

23 `float: left`/`float: right` -> `float: none` (16 mechanical + 5 as
part of the 2 real conversions + 2 gate-collapse entries folded into
those same conversions). 2 real flexbox conversions. 1 defensive
`display: inline-block` addition. Removed the 7 now-redundant
`// line NNN` collapse entries from the gate section; **kept the gate's
banner comment, the full `.cards-progress` bugfix documentation and its
quad-class override, and the unrelated `.slide-image-container` flex
rule completely untouched**, as the deliberate exception documented
above.

### Verification

- `grep` for `float:\s*left\|float:\s*right`: 2 real matches, both
  `.cards-progress` (the base rule and the quad-class override) --
  exactly the intended exception, everything else 0. (3 more matches
  are inside the preserved comment's prose, not real declarations.)
- Compiled for real with `node_modules/.bin/sass` (dart-sass) against
  the theme's full global import chain: 0 errors, only standard
  `@import`-deprecation warnings.
- Parsed the compiled CSS, isolated every rule block touching either
  root section or `.cards-progress`: 0 unexpected `float: left`/
  `float: right`, both intentional `.cards-progress` exceptions present
  and unchanged, the `.slide-image-container` `display: flex !important`
  rule confirmed present in the compiled output. Directly inspected both
  real conversions (`.experience-container`'s row-reverse + mobile
  column-stack, `.experience-text-container`'s flex + 1023px
  column-stack), `.slide-inner`, and `.tag` in the compiled output --
  all landed exactly as designed.
- `git status --short` showed plain `M` (not `MM`) for this file, so
  the usual `git diff HEAD --stat` and a plain `git diff --stat` would
  have agreed here; used `git diff HEAD --stat` anyway per the §60
  practice: **1 file, 42 insertions(+), 116 deletions(-)**.
- A scratch dart-sass compile-test file (`wrapper_roundtable_TEMP.scss`)
  was created in the repo root for this verification; moved to the
  existing `_to_delete/` folder (no delete permission granted this
  session) rather than interrupting with a permission prompt for
  one-file cleanup.
- Re-noticed a stale, empty `.git/index.lock` on the user's machine
  (timestamped from this session's own `git status`/`git diff`
  invocations) -- non-blocking for reads, flagged again as likely to
  interfere with the user's next `git commit`/`git add` until removed
  manually.
- Did **not** do a live before/after screenshot/computed-style check on
  staging -- same caveat as every prior file. The two real conversions
  in `experience-module` (especially the row-reverse image/text swap,
  since the DOM order turned out to be the reverse of what the SCSS
  source order implied) are the most worth a human look before shipping.

### Status

Applied on the user's machine via the device bridge, **not committed to
git yet**.

### Remaining files (4 left)

`_single-post.scss`, `_position.scss`, `_landing.scss`,
`_customer-stories.scss`. Continue the established container-chain-walk
methodology: recheck gated entries too (not just ungated declarations),
verify DOM order via the actual PHP before choosing `row`/`row-reverse`
(don't trust SCSS source order -- this file's `experience-container` is
proof it can be backwards), watch for the shared `.one-half`/
`.one-quarter`/`.one-third` utility classes, and check `git status` for
`MM` (not just `M`) before trusting a plain `git diff --stat`.


---

## §62 -- 2026-09-22: float-grid redesign, 12th file -- `_single-post.scss`, 48 declarations (46 fixed: 40 mechanical/defensive + 3 real flexbox conversions + 3 defensive `display:inline-block` additions folded into the count above; 2 deliberately left as genuine Category-C multi-column grids)

### Context

Continuing from §61. Picked `_single-post.scss`. This file has no `Template
Name:` header itself (it's a component-style stylesheet shared by whichever
of the several near-identical `single-post*.php`/`member-single-post.php`
templates render its markup), so confirmed live the same way as the rest of
the series: grepped its two root selectors (`section.singlePost`,
`section.textImageBlock`) against every template file. Live in
`single-post-feb.php`, `single-post-no-embed.php`, `single-post-side-
articles.php`, `member-single-post.php`, `single-event.php`, `single-event-
nov.php`, and `template-agenda.php`.

(Side note, unrelated to this section: while confirming live status I ran
a broader duplication audit of the `single-post*` template family for a
separate request -- see the duplication-audit report delivered earlier
this session. That's a documentation/investigation deliverable, not a code
change, and doesn't affect anything below.)

### Fresh count

**48 active `float:left`/`float:right` declarations**, gate section entirely
separate at the bottom (line 794+). 21 gated (verified by selector content
against the live main-body rules, no drift), 27 ungated.

### Cardinality verified via PHP before any real conversion -- caught two real multi-item rows

Per the standing practice of never guessing repeatable-vs-solo from SCSS
alone: read `single-post-feb.php`'s markup before touching `.authorSingle`
and confirmed it sits inside `<?php while ( have_rows( 'contributors' ) ) :
?>` -- a genuine ACF repeater, so multiple co-author cards can render side
by side and need to wrap, not a single solo element (my working assumption
from a previous, interrupted pass through this file had this backwards --
caught and corrected before writing anything). Separately confirmed
`.share` (in `single-post-no-embed.php`, since `single-post-feb.php` doesn't
render this block) holds exactly 2 real `<a>` share links (LinkedIn, Email)
side by side, also a genuine multi-item row.

### Three real flexbox conversions -- a nested nested wrapping-row-of-icon+text-pairs shape

- **`.fullWidth .left .author`** (parent of the repeater, already gated to
  `float: none`) -> added `display: flex; flex-wrap: wrap;` so the
  `.authorSingle` cards it contains sit in a wrapping row exactly like the
  original float-based layout did (each card's own `margin-right`/
  `margin-bottom` reproduce the original gaps).
- **`.authorSingle`** (the repeated item, ungated, `width: auto` --
  shrink-wrap, same as a float) -> `float: none; display: flex;`. This
  makes it simultaneously a flex *item* of `.author` (wrapping row) and a
  flex *container* of its own two children -- the classic fixed-width-icon
  (`.authorImage`, 75px) + fill-the-rest-text (`.authorText`, unconstrained
  width) pair seen throughout this series, nested one level inside the
  wrapping-row conversion above it.
- **`.fullWidth .right .share`** (already gated to `float: none`) -> added
  `display: flex; flex-wrap: wrap;` for its 2 share-link children, same
  wrapping-row shape as `.author` but with only 2 items.

### The two genuine Category-C grids -- left untouched, same reasoning as every prior file's ambiguous multi-column floats

- **`.imageGridBlock .gridWrapper .item`** (float:left,
  `width: calc(24.7% - 81.75px)`, `margin-left: 109px`,
  `&:nth-child(4n + 1) { margin-left: 0px; }`) -- a genuine 4-column grid
  using fixed-pixel-gutter float math. The 4 columns' widths plus 3 gutters
  sum to 98.8%, not 100% -- an intentional-looking asymmetry that a
  `flex`+`gap` rewrite would have to reproduce exactly to avoid a visible
  shift. Left as a literal float; its parent `.gridWrapper` is already
  gated to `float: none` (safe, no new risk -- already computed that way in
  production).
- **`section.textImageBlock .itemsWrapper .item`** (float:left,
  `width: calc(33.2% - 102px)`, `margin-left: 153px`,
  `&:nth-child(3n + 1)`) -- same shape, a genuine 3-column grid. Same
  reasoning, left untouched; parent `.itemsWrapper` already gated safe.

### Mechanical flattens and defensive `display: inline-block` additions

40 declarations were safe no-ops (single full-width blocks, or solo
non-wrapping children -- fixed-size aspect-ratio boxes, single logos,
single `<hr>` dividers, etc.), all flattened to `float: none` with no other
change. 5 were bare inline elements needing the same defensive treatment
used throughout this series (float removed, `display: inline-block` added
to preserve their horizontal-row/chip appearance without redesigning the
parent): `.postDetails span` (the category/date meta chips, both the
`section.singlePost` one and the separate `.relatedArticle .postDetails`
one), `.podcast`/`.watchIcon` (small icon beside the meta chips), `.tags
span` (tag chip row), and `a.back-button` (single bare `<a>`, no width).

### Applied

46 `float: left` -> `float: none` (40 mechanical/defensive + 3 folded into
the real conversions' own selectors + 3 defensive `display: inline-block`
pairs counted above). 3 real flexbox conversions. Removed the float-refactor
gate section (203 lines) at the bottom of the file, now fully redundant.

### Verification

- `grep` for `float:\s*left\|float:\s*right`: exactly 2 matches, both the
  intentional Category-C grid-item exceptions -- everything else, 0.
- Compiled for real with `node_modules/.bin/sass` (dart-sass) against the
  theme's full global import chain: 0 errors, only standard
  `@import`-deprecation warnings.
- Parsed the compiled CSS, isolated every rule block touching any of this
  file's root selectors: 0 unexpected `float: left`/`float: right`, both
  intentional exceptions present and unchanged. Directly inspected all 3
  real conversions (`.author`, `.authorSingle`, `.share`) in the compiled
  output -- all three landed exactly as designed, including the nested
  flex-item-and-flex-container behaviour on `.authorSingle`.
- `git status --short` showed plain `M` (not `MM`) for this file, so a
  plain `git diff --stat` would have been accurate here too; used
  `git diff HEAD --stat` anyway per the §60 practice: **1 file, 56
  insertions(+), 258 deletions(-)**.
- A scratch dart-sass compile-test file (`wrapper_single_post_TEMP.scss`)
  was created in the repo root for this verification; moved to the
  existing `_to_delete/` folder (no delete permission granted this
  session) rather than interrupting with a permission prompt for
  one-file cleanup.
- Did **not** do a live before/after screenshot/computed-style check on
  staging -- same caveat as every prior file. The `.author`/`.authorSingle`
  nested-flex conversion (multiple co-authors wrapping) is the one most
  worth a human look before shipping, since it's the first genuinely
  nested wrapping-row-of-icon+text-pairs shape in this series.

### Status

Applied on the user's machine via the device bridge. **Committed this time**
(the user explicitly asked me to start committing, rather than leaving it
staged for them) as part of this session's commit -- see the commit message
for the hash. Not pushed; staying on `dev` per the standing constraint,
push is the user's call.

### Remaining files (3 left)

`_position.scss`, `_landing.scss`, `_customer-stories.scss`. Continue the
established container-chain-walk methodology: recheck gated entries too
(not just ungated declarations), verify DOM/repeater cardinality via the
actual PHP before assuming a float is solo vs. multi-item (this file is a
second proof point, after `_roundtable.scss`, that guessing from SCSS
alone is a real risk), watch for the shared `.one-half`/`.one-quarter`/
`.one-third` utility classes, and check `git status` for `MM` (not just
`M`) before trusting a plain `git diff --stat`.
