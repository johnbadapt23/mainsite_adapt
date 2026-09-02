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

- **Section 6 (not started):** remaining mobile-menu floats that aren't
  part of the back/close header pattern — icon-containers, `.logo-tile`,
  `.overview-image`, `.all-link`, `.subscribe-sidebar-form .icon-container`,
  and the `.mobileMenuResources .main-links-container` logo/adapt-link
  split (source/scss/partials/_header.scss lines ~2863, 2891, 2901, 2955,
  3066, 3129, 3180, 3387, 3415, 3622-3647).
- Beyond `_header.scss`: the original audit flagged **260 "narrow width" +
  167 "no width" + 296 "carousel-adjacent" = 723 declarations** needing
  individual review. Sections 1–5 covered a meaningful chunk of
  `_header.scss`'s share of that, but **no other file** (`_customer-
  events.scss`, `_events.scss`, `_flexible.scss`, `_registrations.scss`,
  `_resources-types.scss`, `_post.scss`, etc.) has been touched yet in the
  hand-designed phase.
- **Not yet confirmed by the user:** no in-thread confirmation has been
  received that Sections 1–5 actually render correctly live with
  `?dev=true`. Test before continuing further, or accept the risk knowingly.

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
- One clean, zero-risk, always-true optimization identified but not yet
  implemented: `partials/_footer.scss` (20KB source) is structurally
  guaranteed to never be above-the-fold on any page (WordPress footer,
  always rendered last) — could be split into a deferred/lazy-loaded
  stylesheet with no viewport-matching risk at all, unlike everything else
  discussed above. Small win, but a genuinely safe one if picked up later.

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

1. Get user confirmation that Sections 1–5 of the float refactor render
   correctly with `?dev=true` before starting Section 6.
2. Section 6: remaining `_header.scss` mobile-menu floats (see §2).
3. Extend the float audit beyond `_header.scss` to the other 30+ flagged
   SCSS files (see §2).
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
8. `template-insights.php` / `template-search-results.php` -- a careful,
   dedicated pass to add `no_found_rows` where safe (see §8) -- these
   share `$args` across multiple `WP_Query()` calls in some branches, so
   the quick per-file sweep deliberately skipped them.
