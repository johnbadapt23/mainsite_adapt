# Adapt Theme

Custom WordPress theme ("Adapt 2022") powering the ADAPT Research & Advisory site. This is a from-scratch theme (not built on a starter framework) with its own SCSS/JS build pipeline, ACF-driven flexible-content templates, and a GitHub Actions deploy workflow.

## Requirements

- **WordPress**, with these plugins active (the theme calls their functions directly and will throw errors/silently no-op without them):
  - **Advanced Custom Fields (ACF)** — every template's content blocks (`get_field()`, `have_rows()`, `acf_add_options_page()`) depend on it.
  - **Yoast SEO** — `functions.php` hooks into `wpseo_*` filters.
  - **WP Rocket** — `functions.php` hooks into `rocket_delay_js_exclusions` to control which scripts WP Rocket's delay-JS feature skips. The theme works without it, but that filter is a no-op if Rocket isn't installed.
- **Node.js 20** and **npm** — for the SCSS/JS build (see below). Not required on the production server; only for building locally or in CI.
- **PHP** with `allow_url_fopen` not required for anything theme-specific.

## Quick start (local development)

```bash
npm install
npx gulp build:styles build:styles-split build:scripts
```

That compiles `source/scss/main.scss` → `assets/css/main.min.css` (kept as an unused rollback artifact -- see the footer CSS split note below), `source/scss/main-nofooter.scss` + `source/scss/footer-only.scss` → `assets/css/main-nofooter.min.css` + `assets/css/footer.min.css` (the two files actually enqueued by `functions.php`), and `source/js/main.js` (+ vendor libraries) → `assets/js/main.min.js`. Point a local WordPress install's `wp-content/themes/` at this folder and activate the theme.

For active development with live rebuild-on-save:
```bash
npx gulp
```
This runs `watch`, which recompiles styles/scripts/images/icons/fonts automatically as you edit `source/`.

Other individual build tasks: `build:images`, `build:icons`, `build:fonts`, `build:favicons`, `build:php`, `build:html`. Run `npx gulp _build` to run all of them at once.

## Project structure

```
functions.php, header.php, footer.php   Theme bootstrap (see includes/ for hook registration)
includes/                               Split-out functionality: _hooks.php (all add_action/add_filter
                                         wiring), _setup.php, _customisations.php, _functions.php,
                                         _widgets.php, _shortcodes.php, _menu.php, _head.php
templates/                              336 PHP template parts, loaded via get_template_part().
                                         Page templates (template-*.php) dispatch to
                                         templates/components|*-components/ via ACF flexible-content
                                         (have_rows('content_blocks') + get_row_layout() branches).
source/                                 Everything the build pipeline compiles FROM. Never edit
                                         assets/ directly -- it's overwritten on every build.
  scss/                                 global/ (base styles, shared across all pages), partials/
                                         (header/footer), templates/ (one file per page template),
                                         libraries/ (third-party component overrides), sections/
  js/main.js                            All theme JS, assembled via gulp-file-include (@@include(...))
  components/                           Third-party JS/CSS libraries, vendored directly (no package
                                         manager -- these are committed as-is)
  gulp/                                 The gulp4 task definitions (see Build system below)
assets/                                 BUILD OUTPUT (main-nofooter.min.css + footer.min.css --
                                         the enqueued split CSS, see Build system below --
                                         main.min.css, main.min.js, images, fonts, icons).
                                         Generated -- don't hand-edit.
_archive/                               Retired/dead files kept for reference, not loaded by anything
                                         (old functions.php snapshots, unused legacy templates, the
                                         old bower.json/gulp-install packages task from before the
                                         theme's dependency manager was dropped).
.github/workflows/deploy.yml            CI/CD: build + SFTP deploy on push to the configured
                                         deploy branch (see Deployment below).
```

## Build system

Gulp 4, dart-sass (via `gulp-sass`), all dependencies on actively-maintained, CommonJS-compatible versions. A few things worth knowing if you're touching `source/gulp/`:

- **`source/gulp/sass-glob.js`** is a small local replacement for the `gulp-sass-glob` npm package, which has a path-joining bug that silently makes glob-style `@import` statements (e.g. `@import "templates/**/*.scss";` in `main.scss`) resolve to nothing. Don't reinstall the npm package in its place.
- **`gulpfile.js`** eager-loads every file under `source/gulp/tasks/` via `require-dir`, so every task file's top-level `require()`s run on *any* gulp invocation, even one that only asks for a single task. Keep that in mind before adding a new `require()` at the top of a task file — if the package needs a system binary (like `gulp-fontgen` needing `fontforge`) or isn't in `package.json`, it'll break every gulp command, not just its own task.
- **`gulp-clean-css`** runs with `level: 2` + `restructureRules` enabled in `build:styles` (and `build:styles-split`) — this merges CSS rules that reopen the same selector in multiple places (common when several people edit the same SCSS file over time) after correctly resolving the cascade, so it only drops declarations that were already being overridden. Don't downgrade this without checking output size first.
- **`build:styles-split`** (`source/gulp/tasks/build/styles-split.js`) compiles `main-nofooter.scss` + `footer-only.scss` — everything except `partials/_footer.scss`, and just that partial, respectively — into the two files `functions.php` actually enqueues (`main-nofooter.min.css` deferred-loading `footer.min.css` via the same preload+onload pattern used for `wp-pagenavi`'s CSS, since the footer is never above-the-fold on any page). `build:styles`/`main.min.css` (the old single-bundle build) is kept building alongside it as an unused rollback artifact, not wired into anything. Not part of the `_build` parallel task list by default — CI invokes it explicitly (see Deployment below).

## Deployment (CI/CD)

`.github/workflows/deploy.yml` runs on every push to whichever branch is configured in its `on.push.branches` list:
1. `npm install`
2. `npx gulp build:styles build:styles-split build:scripts` (compiles fresh CSS/JS from `source/`)
3. Deploys the built theme over SFTP — everything except `.git`, `.github`, `node_modules`, `source/`, `_archive/`, and the build tooling files (`package.json`, `gulpfile.js`, etc.)

Configuration lives in a GitHub **environment** referenced by the workflow's `environment:` key (Settings → Environments), which supplies:

| Name | Type | Purpose |
|---|---|---|
| `STAGING_HOST` | variable | Server hostname/IP to deploy to |
| `STAGING_PORT` | variable | SFTP port |
| `STAGING_REMOTE_PATH` | variable | Absolute path on the server to deploy the theme into |
| `STAGING_USERNAME` | secret | SFTP username |
| `STAGING_PASS` | secret | SFTP password |

`STAGING_REMOTE_PATH` can point at either the live theme folder or a separate parallel folder (e.g. a `-test`/`-optimize` copy) so a build can be tested/activated independently in wp-admin before it replaces what's live — update the environment's values to change target server, port, or path; no workflow file changes needed.

## Working with external content updates

If someone outside this repo sends over an updated copy of theme content (new SCSS, JS, images), **only pull in the specific `source/scss/`, `source/js/`, and `source/images/` files that actually changed** — don't wholesale-replace the entire `source/` folder. `source/gulp/` (the build tooling) and `source/components/` (vendored libraries) don't change from content updates, and copying an old snapshot of them back in will silently undo build-system fixes. If a wholesale folder replacement does happen, diff `source/gulp/` against the previous commit before pushing to catch it.

## Known quirks

- **`main.scss`/`main.js` are monolithic** — every page ships the same CSS/JS bundle regardless of which template it uses. There's no per-template code-splitting. Worth knowing if bundle size becomes a concern as more templates are added.
- **`gulp-fontgen`** (used by `build:fonts`) shells out to the system `fontforge` binary. It's not installed by default on most CI runners (see the `Install system dependencies` step in `deploy.yml`) or a fresh dev machine (`brew install fontforge` / `apt-get install fontforge`).
- Two homepage templates currently exist — `template-home.php` and `template-home-nov.php` — kept distinctly named in the template picker (`Home Template` vs `Home Template (Nov)`) so they don't get confused; only one should be assigned as the live homepage at a time.
