# Session summary — extended autonomous sweep (2026-09-15 / 2026-09-16)

All changes are committed to your device on `dev`. Nothing pushed or touched on `main` — as always, that's your call.

## Round 3 of the ScrollMagic fix — Round 2's fix didn't actually work, found the real cause (needs push + deploy + cache clear, again)

After the last deploy + cache clear, I re-checked `/ecosystem-consulting-partners/` on a real cached load and the console error was **still firing**. Looked at the raw page HTML directly (not just the DOM) and found the Round 2 fix (`script_loader_tag` stripping `defer`/`async`) had no effect at all: `gsap-js`, `scrolltrigger-js`, and `main-js` were still rendering with `data-rocket-defer defer` in the actual response HTML.

**Why it didn't work:** WP Rocket's "Load JavaScript deferred" feature doesn't add the `defer` attribute through WordPress's `script_loader_tag` filter chain — it's applied afterward, by WP Rocket's own HTML output-buffer rewriting (same architecture as its Delay JS feature). No `script_loader_tag` filter, however late its priority, can intercept that — WP Rocket's rewrite happens *after* that filter has already finished.

**The actual fix:** WP Rocket exposes a dedicated filter for exactly this — `rocket_exclude_defer_js` — confirmed by reading WP Rocket's own official helper plugin source on GitHub (`wp-media/wp-rocket-helpers`). It's the direct Load-JS-deferred equivalent of the `rocket_delay_js_exclusions` filter already used elsewhere in this file, and takes the same kind of array (src-URL path fragments, not script handles). Replaced the broken `script_loader_tag` filter with this one, excluding `gsap.min.js`, `ScrollTrigger.min.js`, `scrollmagic.min.js`, and `main.min.js` together on the 8 GSAP-gated templates — so WP Rocket itself skips adding `defer` to any of them, rather than me trying to undo its output after the fact.

Committed to your device, PHP syntax-checked clean. Needs the same cycle again: push, deploy, then a WP Rocket cache clear, then I'll do a full re-verification (real cached page load, checking both the console and the raw response HTML this time, not just the live DOM).

## Round 2 of the ScrollMagic fix (superseded by Round 3 above — did not work as deployed)

After you cleared the cache, I re-checked with fresh page loads (no `?nocache` bypass) and the delay-JS fix from round 1 partly worked — `gsap-js`/`scrolltrigger-js`/`scrollmagic-js`/`main-js` are no longer being fully delayed — but the console error was still firing. Found a second, related bug: WP Rocket's separate "Load JavaScript deferred" optimization adds a `defer` attribute to `gsap-js`/`scrolltrigger-js`/`main-js` but not to `scrollmagic-js` (same unexplained asymmetry as before — `scrollmagic-js` is the one brand-new script handle in the mix). A `defer`red script only runs after the whole page finishes parsing; a plain script runs immediately, the instant the parser reaches it — so `scrollmagic-js` was still running before GSAP existed, regardless of its position in the HTML.

Rather than keep chasing why WP Rocket treats this one script differently (I don't have wp-admin access to inspect its settings directly, and this is the second time it's singled out the same file), I stopped depending on WP Rocket's per-script classification altogether: added a `script_loader_tag` filter that strips `defer`/`async` from all 4 scripts on the 8 GSAP-gated pages, forcing plain synchronous `<script src>` tags that execute strictly in document order — which already matches the real dependency chain. This should be immune to whatever WP Rocket decides to do with new scripts in the future.

Committed to your device, PHP syntax-checked clean. Needs the same cycle as before: push, deploy, then a WP Rocket cache clear, then I'll do a full re-verification.

## Newest: fixed a real regression from the ScrollMagic split above (needs push + deploy to verify)

After you pushed/deployed the ScrollMagic bundle split, I went looking for more optimizations and found my own fix had a bug — worth understanding since it's a good lesson on how I verify things.

**What went wrong:** my earlier "verified working" checks all used `?nocache…`-style query strings to test. Those bypass WP Rocket's page cache entirely, serving a live, non-optimized render — which meant I was never actually testing what a real visitor sees. On an actual cached page load (no query string), WP Rocket's "Delay JavaScript Execution" feature was delaying `gsap-js`/`scrolltrigger-js`/`main-js` until the visitor's first interaction (scroll/click/etc.), but leaving my new `scrollmagic-js` completely undelayed — so it ran immediately, before GSAP existed on the page at all. Confirmed live on `/ecosystem-consulting-partners/` and `/adapt-vs-gartner/`: `scrollmagic.min.js` loaded as a real script while `gsap-js`/`scrolltrigger-js`/`main-js` sat as inert `text/rocketlazyloadscript` placeholders.

This isn't just cosmetic. `animation.gsap.js`'s GSAP-version detection runs exactly once, at parse time, and permanently closures over whatever it finds — so loading it before GSAP exists risks it never being wired up correctly, even after GSAP loads later. Console showed the exact `"TweenLite or TweenMax could not be found"` error my original fix was meant to eliminate — just relocated to the 8 pages where it's actually supposed to work, which is worse than where it started.

**The fix:** extended the existing `rocket_delay_js_exclusions` filter in `functions.php` (which already exempts a handful of scripts from Delay JS on the homepage, for what was presumably the same class of problem) to also exempt `gsap.min.js`, `ScrollTrigger.min.js`, `scrollmagic.min.js`, and `main.min.js` together on the 8 `adapt_page_needs_gsap()` templates — guaranteeing they always execute in their real enqueue order, no Delay-JS race possible. PHP syntax-checked clean, committed to your device.

**Needs from you:** push this + deploy, same as before. One extra wrinkle: WP Rocket caches full page HTML and doesn't regenerate already-cached pages on their own — while checking this I found some pages (`/custom-partnered-research/`, `/executive-advisors/`, `/buyer-persona-cio/`) are still serving a stale cache from *before* the ScrollMagic split even deployed (older `main.min.js` with everything still bundled together, no `scrollmagic.min.js` at all) — so they're incidentally fine for now but would hit the same bug once their cache eventually regenerates. After deploying this fix, it'd be worth clearing WP Rocket's full cache in wp-admin so every page picks up the corrected script order immediately rather than waiting on natural cache expiry. I'll do a full re-verification (real cached loads, no `?nocache` bypass this time) once you've pushed, deployed, and cleared the cache.

## Also found, not fixed (informational — outside the theme's code)

While sweeping pages for console errors I found a recurring, harmless-but-wasteful 404 on at least two pages: `/edgeplus-app/` and `/private-executive-roundtables/` each fire one `Failed to load resource: 404` for a malformed URL like `.../Edge-App.png.webp%201439w` (a space-encoded `%20` glued between `.webp` and a srcset width descriptor like `1439w`). The image still displays correctly — the browser separately fetches the real `.webp` file fine — so this is purely a wasted request, not a visual bug.

Root cause looks like the **Imagify** plugin's "Next-Gen format" delivery, which rewrites `<img>` tags into `<picture>` elements with WebP `<source>`s at the HTML-output level — it's not anything in the theme's own code (grepped the whole repo for `srcset`/`webp`-related logic; the only theme-owned WebP code is `adapt_webp_poster_url()` for `<video poster>`, which is unrelated and fine). Since this is third-party plugin output-rewriting, not theme markup, I didn't attempt a fix — patching around another plugin's HTML rewriting from theme code would mean an output-buffer regex hook running on every page, which is a bigger, riskier piece of surgery than this minor waste justifies. Worth a look at Imagify's settings/version on staging if you want it gone, but I'd leave it alone otherwise.

## New this update: ScrollMagic bundle split (implemented, needs your review before push)

While looking for more optimizations after the last deploy, I found `main.min.js` was unconditionally shipping ScrollMagic + its GSAP plugin (and a completely unused debug overlay, `debug.addIndicators.js`) to **every page on the site**, even though the animation code that uses them only ever runs on the 8 templates gated by `functions.php`'s `adapt_page_needs_gsap()` — the same templates GSAP itself is already conditionally CDN-loaded on. Confirmed via console: `"TweenLite or TweenMax could not be found"` fired 30+ times on every non-GSAP page load — harmless (the real animation code is properly guarded behind element-presence checks, so nothing visibly broke) but pure console noise plus dead JS parsed on ~52 of 60 pages.

I flagged this and you asked me to implement it. Changes made, mirroring the existing `adapt_page_needs_gsap()` pattern exactly:

- **`source/gulp/paths.js`**: removed `ScrollMagic.js`, `animation.gsap.js`, and `debug.addIndicators.js` from the main `scripts` bundle. Added a new `scriptsScrollmagic` array with just the two files actually used (`debug.addIndicators.js` is dropped entirely — grepped `main.js` and confirmed `.addIndicators(` is never called anywhere, so it was 23KB of pure dead code even before this).
- **`source/gulp/tasks/build/scripts-scrollmagic.js`** (new): builds those two files into `assets/js/scrollmagic.min.js`, same pipeline (fileinclude → uglify → concat) as `build:scripts`. Built and verified successfully in my sandbox — output is 25.3KB, `TweenLite`/`TweenMax`/`TimelineMax` references intact.
- **`gulpfile.js`**: added `build:scripts-scrollmagic` to the `_build` parallel task list.
- **`functions.php`**: inside the existing `if ( adapt_page_needs_gsap() )` block, added a conditional `wp_enqueue_script('scrollmagic-js', ...)` depending on `gsap-js`/`scrolltrigger-js` (guarantees load order). Also updated `main-js`'s dependency array to conditionally include `scrollmagic-js` when on a GSAP template — this is actually *more* robust than the pre-existing setup, which relied on implicit enqueue-call ordering between `gsap-js` and `main-js` rather than an explicit dependency.
- **`assets/js/scrollmagic.min.js`** (new, committed): initial built copy so git tracks the path and `filemtime()` in `functions.php` doesn't fail before CI's first rebuild.
- **`.github/workflows/deploy.yml`** (protected — delivered separately, needs your manual paste-in like last time): added `build:scripts-scrollmagic` to the CI compile step, and `assets/js/scrollmagic.min.js` to `sync-delta-includes` so CI's regenerated copy actually gets force-deployed on every push (same reasoning as the other compiled assets already in that list).
- PHP syntax-checked `functions.php` clean (`php -l`).

**Not yet verified live** — this needs a push + deploy before I can confirm on staging that the 8 GSAP templates (`template-benchmarking`, `template-comparison`, `template-customer-events`, `template-ecosystem-advisors`, `template-ecosystem-consulting`, `template-edge-consulting`, `template-evr`, `template-partnered-research`) still animate correctly and that the console errors are gone everywhere else. Once you push `dev` and deploy.yml is applied, I'll check all 8 GSAP templates for working scroll animations plus a sample of non-GSAP pages for a clean console, same rigor as the CSS fixes below.

One more thing worth noting: local shell access to your machine went down partway through this session (a Windows update from Sep 8 is blocking it — Anthropic's tracking it, Claude Code itself is unaffected). I couldn't run `git status`/`gulp` directly on your machine as a result, so I built and verified the new bundle in my own cloud sandbox instead. Everything above was still verified — just via a different path than usual.

## Critical methodology fix (read this first)

I caught a real bug in my own testing process partway through this session: the browser pane I've been using defaults to an odd ~443px-wide viewport that doesn't match any real breakpoint (not mobile 375px, tablet 768px, or desktop 1440px). Two fixes made earlier in this session were verified at that bad width before I caught it:

- **`.two-column-image-text-outer` fix was wrong as originally committed.** It forced `display:flex` unconditionally. Production only uses flex at ≥768px — below that it's a real `float:left` block layout. The original fix would have squeezed mobile columns into a broken flex row. **Corrected**: now scoped to `@media (max-width: 767px)` using `display:flow-root`, verified against production at 375/768/1440px.
- The `/all-resources/` `.post-container.grid-wrapper` fix and `/edgeplus-app/` `.bottom-container` fix turned out to be safe as unconditional rules (no flex/block split for those components) — re-verified at all three breakpoints, no change needed.

From that point on, every fix below was verified at 375px, 768px, and 1440px against production before being committed, not just at the pane's ambient width.

## Fixes made this session (all verified live against production, all in `dev`, not pushed)

1. **`/about-us/` `.two-column-image-text-outer`** (and its `.text-only` variant on `/private-events-partnership/`) — mobile-only collapse (<768px), fixed with media-scoped `flow-root`.
2. **`/all-resources/` `.post-container.grid-wrapper`** — collapses at all widths, unconditional `flow-root`, exact match with production at 375/768/1440.
3. **`/edgeplus-app/` `span.bottom-container`** — collapses at all widths, unconditional `flow-root`, exact match at 204px/212px/346px.
4. **`/private-events-sponsorship/` `.buttons-container`** (collapses at all widths) and **`.video-column.image-column`** (collapses only <768px) — both under `section.two-column-services.landing-video-intro`.
5. **`/event-partner/edge-event-partnership/` `span.tags-container`** (event tag pills, `section.events-listing-module`) — collapses at all widths.
6. **`/analyst-presentations/become-a-partner/` `.video-column`** (no `.image-column` class — a second variant of fix #4 that the earlier selector didn't cover) and **`.video-quote-container`** (`section.video-quote-block`, collapses only <768px, matches the flex/block split pattern from fix #1).
7. **`/get-in-touch/` `.contact-image-container.desktop`/`.mobile`** (`section.contact-module`) — user-reported bug: both the desktop and mobile image variants were showing simultaneously instead of toggling. Root cause: the component's own breakpoint is **1023/1024px**, not the 767/768px used elsewhere in this file — found by binary search against production. Fixed with two media-scoped `display:none` rules (desktop hidden ≤1023px, mobile hidden ≥1024px), each exceeding the competing selector's specificity via doubled class tokens.

Every fix uses `display: flow-root` (never re-floating, never forcing flex) with a selector specificity that clearly exceeds the competing doubled-class override, so each one wins on specificity rather than relying on cascade order — same defensive pattern documented throughout the file for the `.sidebar-container` cascade-reordering lesson from earlier in this engagement.

## False positives ruled out this session (no fix needed)

- `.slider-outer` (0-height) appears on every `section.full-suite-slider-module` page (benchmark-maturity-assessment, market-buyer-intelligence-platform-advantage, ecosystem-consulting-partners, custom-partnered-research, gtm-services-technology-vendors) — confirmed identical on production, not a regression.
- A stray `<p>` inside `.hsfc-RichText` (HubSpot embedded form) on `/adapt-vs-gartner/` — confirmed identical on production, third-party widget quirk, unrelated to the float refactor.

## Page sweep coverage

Checked this session (375px + 1440px, some also at 768px where a fix was needed): `/cookies/`, `/content-usage-policy/`, `/website-terms-of-use/`, `/research-advisory-terms/`, `/privacy-policy/`, `/edgeplus-app/`, `/data-edge-keynote-preview/`, `/get-in-touch/`, `/customer-stories/`, `/event-partner/`, `/private-events-partnership/`, `/private-events-sponsorship/`, `/edge-events/`, `/benchmark-maturity-assessment/` (+ one variant), `/market-buyer-intelligence-platform-advantage/`, `/ecosystem-consulting-partners/`, `/custom-partnered-research/`, `/adapt-vs-gartner/`, `/analyst-presentations/` (+ `become-a-partner/` variant), `/gtm-services-technology-vendors/`, `/event-partner/edge-event-partnership/`.

This completes the 50-URL `page-sitemap.xml` sweep.

## CI build optimization (deployed and verified)

Found and removed a dead build step: `build:styles` (produces `assets/css/main.min.css`) was running on every CI deploy, but `functions.php` never enqueues that file — confirmed via `wp_enqueue_style` audit, only `main-nofooter.min.css` and `footer.min.css` are actually used. Since gulp's CLI runs multiple task names in series (not parallel like `gulp.parallel()`), this was adding a real ~1.2min of pure serial wall-clock time to every deploy for a file nothing reads.

Changes:
- `gulpfile.js`: removed `'build:styles'` from the `_build` parallel task list (committed to your device directly). The task itself is untouched and still wired into `watch` for local dev live-reload.
- `.github/workflows/deploy.yml`: this file is protected from remote writes, so I delivered the fully patched version to you directly and you applied it manually. Changes: the "Compile CSS and JS" step now runs `npx gulp build:styles-split build:scripts` (dropped `build:styles`), and `sync-delta-includes` no longer force-includes the now-stale `main.min.css`.

**Verified deployed and working**: GitHub Actions run #189 (commit `c726ffa`) completed successfully in **1m42s**, versus the typical 2m20s–2m45s baseline — confirms the optimization is live and correctly applied.

## User-reported bug fix (deployed and verified)

`/get-in-touch/` `.contact-image-container.desktop`/`.mobile` — see fix #7 above. Verified pixel-exact against production post-deploy at both breakpoints:
- 1023px: mobile image shown (193.1875px), desktop hidden.
- 1024px: desktop image shown (294.390625px), mobile hidden.

## Final pipeline sanity check (post-optimization)

Re-verified `/all-resources/` after deploy #189 to confirm removing `build:styles` from CI didn't break the actual build pipeline: `main-nofooter.min.css` and `footer.min.css` both load correctly (no 404s, correct cache-busted `?ver=` query strings), and the `.post-container.grid-wrapper` fix from earlier in this session is still live and correct (`display: flow-root`, real rendered height 265.75px, not collapsed). No regressions from the optimization.

## Loose end carried over from before this session (unresolved, flagging again)

The `.sidebar-container` dead-code removal from earlier in this engagement reappeared on disk at one point mid-session without me re-adding it, then disappeared again on a later re-stage — consistent with another/parallel session also editing this same file concurrently. Current committed state does **not** contain those dead rules. Worth a quick look on your end if you see anything unexpected in `git diff` around that area.

## Status: Round 3 fix above needs push + deploy + cache clear + re-verification. Everything else in this summary is deployed and verified live on staging.
