# Duplicate template/component audit

Read-only investigation — no files changed. Scope: PHP templates, PHP component partials, and a lighter pass over SCSS template partials, per your answers (audit-first, all three layers).

## Headline finding

Most of what looks like "duplicate components" is actually **accumulated duplicate template files from past redesigns/campaigns** that were saved under new filenames instead of replacing the original — not components being *reused*. Reuse (the same partial included from many templates) is working as intended and isn't a problem. The problem is old, near-identical full copies sitting unused alongside the current one.

One of these was already half-fixed by a previous pass: `template-home.php`'s own header comment says it was renamed from a name collision with `template-home-nov.php` ("both previously declared the identical 'Home Template' name... No functional change"). The same class of bug still exists uncorrected elsewhere (see below).

---

## 1. PHP top-level templates — two real bug-level collisions, plus a dead-file cluster

### A. Two live wp-admin "Template Name" collisions (same fix pattern as the home-template one already applied)

WordPress shows the page-template dropdown by `Template Name:` header text, not filename. Two files sharing a name makes it impossible for an editor to tell them apart when assigning a page template, and it's ambiguous which one WP actually loads for existing assignments.

- **`template-flexible.php`** (4.2KB) and **`template-flexible-nov.php`** (41KB) both declare `Template Name: Flexible Template`. Content is very different (the "-nov" one has ~15 more ACF flexible-content block types wired in) — this is not a byte-duplicate, it's two different templates fighting over one name. Needs a rename (same fix as `template-home-nov.php` got), and someone needs to confirm in wp-admin which one existing pages are actually using before renaming either.
- **`template-thank-you.php`** (1041 bytes) and **`template-thankyou.php`** (1041 bytes) are **byte-identical** (only whitespace differs) and both declare `Template Name: Thank You Template`. This one's a true duplicate — genuinely safe to delete one of them, but only after confirming in wp-admin that no live page is assigned to the one you'd delete (file-based deletion doesn't update the page's `_wp_page_template` meta value, so a page pointed at the deleted file would 404 or silently fall back).

### B. Byte-identical pair with different names (not a collision, just redundant)

- **`single-resource.php`** and **`single-resources.php`** — 912 bytes each, **100% identical content**, different `Template Name`s ("Resources Template" vs "Resources Landing"). Both tiny stub files. Low risk to consolidate, same "confirm live assignment first" caveat.

### C. The single-post dead-file cluster (the biggest chunk of duplication, most likely orphaned)

None of these 7 files has a `Template Name:` header, and none is referenced anywhere in the codebase (checked every PHP file for `template_include`, `locate_template`, and direct filename references — zero hits). WordPress's automatic template hierarchy for the `post` post type only ever auto-loads a file literally named `single-post.php`; the rest can't be reached by any routing mechanism this repo contains. That strongly suggests they're dead, not actively swapped in by some condition I'm missing — but I can't fully confirm "dead" without checking wp-admin's actual page/post template assignments, which this sandbox has no network path to (tried the REST API route a previous session apparently used successfully — `staging.adapt.com.au` isn't reachable from here right now).

Content-similarity between them (normalized-whitespace diff ratio):

| Pair | Similarity |
|---|---|
| `single-post-no-embed.php` ↔ `single-post-side-articles.php` | **96.5%** |
| `single-post-feb.php` ↔ `single-post-side-articles.php` | 81.5% |
| `single-post-feb.php` ↔ `single-post-no-embed.php` | 79.7% |
| `single-post.php` ↔ `august-single-post.php` | 79.1% |
| `single-post-feb.php` ↔ `member-single-post.php` | 34.5% |

So there are really two clusters: **{single-post-feb, single-post-no-embed, single-post-side-articles}** (three near-copies of each other, 80–96% similar) and **{single-post.php, august-single-post.php}** (a second, separate near-copy pair). `single-post_author.php` and `member-single-post.php` are genuinely distinct in content (low similarity to everything), so they're likely legitimate separate variants, not copy-paste drift.

This matches the standing rule from earlier sessions on this project: confirmed-dead PHP template files get left alone (not deleted), only CSS/JS tied to them gets cleaned up. I'd treat this cluster the same way unless you specifically want me to chase down live-assignment confirmation first (would need wp-admin access or a reachable REST endpoint) and then actually remove the confirmed-dead ones.

---

## 2. PHP component partials (`templates/components/`, 86 files) — real near-duplicates, most of them still wired in

Checked pairwise similarity across all 86, then checked how many *other* templates actually `get_template_part()` each one (a low/zero count is the "probably safe to consolidate" signal):

| Pair | Similarity | Live references (each side) |
|---|---|---|
| `_video-block-three-columns.php` ↔ `_video-block-two-columns.php` | 98.4% | 3 / 3 — both live, near-identical, look like they should be one parameterized component |
| `_related-articles-taxonomies-locked.php` ↔ `_related-articles-taxonomies.php` | 96.1% | 2 / 3 — both live |
| `_download-block-three-columns.php` ↔ `_download-block-two-columns.php` | 94.6% | 3 / 3 — both live |
| `_agenda-item-roundtable.php` ↔ `_agenda-item.php` | 92.3% | **0 / 0 — both look orphaned** |
| `_related-articles-taxonomies-locked.php` ↔ `_related-articles-taxonomies-old.php` | 91.7% | 2 / **0** — the "-old" one looks dead |
| `_counter-block.php` ↔ `_repeatable-counter-block.php` | 90.0% | 8 / 8 — both heavily live |
| `_related-articles-grid-block-march.php` ↔ `_related-articles-grid-block.php` | 89.3% | **0** / 6 — the "-march" one looks dead |
| `_related-articles-taxonomies-old.php` ↔ `_related-articles-taxonomies.php` | 88.3% | 0 / 3 |
| `_download-block-two-columns.php` / `_download-block-three-columns.php` ↔ `_download-block.php` | 87.0% | 3 / 3 (all three live, near-identical) |
| `_resources-block.php` ↔ `may_resources-block.php` | 86.7% | 3 / **0 — dead** (also note the odd `may_` filename prefix, not the theme's naming convention) |
| `_logo-ticker-v2.php` ↔ `_logo-ticker.php` | 84.1% | 1 / 1 — both live |
| `_two-column-text-home.php` ↔ `_two-column-text.php` | 76.6% | **0** / 1 — the "-home" one looks dead |

**Likely-dead component files** (0 references anywhere else, high similarity to a live sibling that clearly replaced them): `_related-articles-taxonomies-old.php`, `_related-articles-grid-block-march.php`, `_related-articles-grid-block-old.php`, `may_resources-block.php`, `_two-column-text-home.php`, `_agenda-item-roundtable.php`, `_agenda-item.php` (both sides of this last pair show 0 references, worth double-checking before assuming either is dead — possibly reached some other way I didn't grep for).

**Live near-duplicate pairs worth actually merging into one parameterized component** (both sides genuinely in use, so this is the safer, higher-value merge target — no risk of breaking a page-template assignment, just consolidating two component files into one with a variant flag): the `-two-columns`/`-three-columns` video and download block pairs, and `_counter-block`/`_repeatable-counter-block`.

---

## 3. SCSS template partials — mostly expected repetition, not a real duplication problem

Ran the same cross-file check on `source/scss/templates/*.scss`. The result is a long list of class names (`.column-container`, `.image-container`, `.text-column`, etc.) appearing in a dozen-plus files — but this is architectural, not accidental: each template's SCSS file scopes styling to its own PHP component's markup, and a lot of ACF flexible-content block types share the same visual shape (two-column image/text, icon grids, slider modules) across different templates by design, each with its own small styling differences. Chasing this into a shared mixin/utility layer would be a much bigger, riskier refactor (touching every template's compiled CSS at once) for comparatively low payoff versus the PHP-level findings above, and it isn't the kind of thing the site would actually break from being left alone. I'd deprioritize this layer unless you want it specifically.

---

## Recommended next step

None of this has been changed. Suggest tackling in this order, each as its own reviewable step:

1. Fix the two `Template Name:` collisions (flexible, thank-you) — the thank-you one is byte-identical so it's the easiest first move; the flexible one needs a look at which pages are actually assigned before touching either file.
2. Merge the three genuinely-live near-duplicate component pairs (video/download two-vs-three-column, counter-block) into single parameterized components.
3. Only after you (or I, with wp-admin/REST access) can confirm live template assignments: clean up the confirmed-dead single-post cluster and the ~7 likely-dead component files listed above.

Let me know which of these you want me to actually start on, and whether you can get me wp-admin or REST API reachability for the live-assignment checks — that would let me turn "likely dead" into "confirmed dead" for the whole list.
