# Formcrafts embeds on production (adapt.com.au)

Scan date: 30 September 2026
Method: every URL in the production XML sitemaps (1,252 URLs across all post types and taxonomies) was fetched and the raw HTML searched for `formcrafts`. The sitewide header and footer subscribe form was excluded so page-specific embeds stand out.

Caveat: this covers URLs listed in the sitemaps and server-rendered HTML only. Pages excluded from the sitemap (noindex, drafts, private) or forms injected purely by client-side JS would not be caught.

## 1. Sitewide embed (every page)

| Form ID | Where | Type |
|---|---|---|
| `asgtfbr` | Header "Subscribe" button + footer `subscribe-form-container` | Popup (JS, `fc.js`) |

Template-level repeats of the same form:

- Single resource posts: extra "Join the Community" CTA buttons opening `asgtfbr`.
- Customer story singles: a second copy of the `asgtfbr` JS embed code in the page body (duplicate `_fo.push` and `<div id='mcafs'>`). Worth de-duplicating.

## 2. Pages with page-specific Formcrafts forms (38)

### Partner and sponsorship pages (popup, JS embed)

| Page | Form ID(s) |
|---|---|
| https://adapt.com.au/become-a-partner/ | umhpxfy |
| https://adapt.com.au/cio-edge/become-a-partner/ | qpseutb |
| https://adapt.com.au/data-edge/become-a-partner/ | uryyzxe |
| https://adapt.com.au/ccdc-edge/become-a-partner/ | jnbdkcy |
| https://adapt.com.au/security-edge/become-a-partner/ | sptxsjd |
| https://adapt.com.au/people-edge/become-a-partner | efnwjyk |
| https://adapt.com.au/event-partner/ | uhfjcey |
| https://adapt.com.au/become-an-event-partner-sample | uhfjcey |
| https://adapt.com.au/private-events-sponsorship/ | qfdugsw |
| https://adapt.com.au/private-events-partnership/ | enkcyav |
| https://adapt.com.au/ecosystem-consulting-partners/ | huwmnnq, uerpayj |

### Other landing pages

| Page | Form ID(s) | Type |
|---|---|---|
| https://adapt.com.au/custom-partnered-research/ | rbrguab, zktbjjj, mkwdejz, qspdbnm, harpjcq, mwcepbv, qvhrpuj, fgpwafc, pqmkttf, vvusbrt, cafxfdu (11 forms) | Popup (JS) + inline iframe |
| https://adapt.com.au/go-to-market-insights/ | qajvuve | Popup (JS) |
| https://adapt.com.au/executive-advisors/ | uerpayj | Popup (JS) |
| https://adapt.com.au/analyst-presentations | uerpayj, edddkxq | Popup (JS) |
| https://adapt.com.au/customer-stories/ | axjmhev | Inline iframe |

### Customer story singles (inline iframe, form `yxfskpd`, 22 pages)

Same form embedded in the body (`.form-column .form-embed` iframe), so likely set at template or ACF field level.

- https://adapt.com.au/customer-stories/adapts-strategic-insights-into-the-anz-it-market-is-unparalleled
- https://adapt.com.au/customer-stories/logicalis-australia-elevates-go-to-market-strategies-and-internal-events-with-adapts-analysts
- https://adapt.com.au/customer-stories/kyndryls-decision-to-partner-with-adapt-a-game-changer-for-their-cloud-and-infrastructure-services
- https://adapt.com.au/customer-stories/adapt-brings-the-right-people-in-the-room-for-amazon-web-services
- https://adapt.com.au/customer-stories/adapt-is-walkmes-golden-standard-for-partnerships
- https://adapt.com.au/customer-stories/gwi-finds-and-closes-new-business-with-adapt
- https://adapt.com.au/customer-stories/adapt-client-success-story-interactive
- https://adapt.com.au/customer-stories/how-akamai-technologies-is-enhancing-their-market-position-with-adapt
- https://adapt.com.au/customer-stories/turning-strategy-into-action-with-trusted-advice
- https://adapt.com.au/customer-stories/clarity-and-direction-for-our-it-operating-model
- https://adapt.com.au/customer-stories/powerful-data-to-drive-a-future-ready-workforce
- https://adapt.com.au/customer-stories/research-that-elevated-our-board-presentation
- https://adapt.com.au/customer-stories/pathfindr-accelerates-growth-pipeline-through-adapt-edge-events
- https://adapt.com.au/customer-stories/how-tanium-builds-pipeline-and-expands-strategic-reach-through-adapts-edge-events-and-roundtables
- https://adapt.com.au/customer-stories/rethinking-business-priorities-with-adapts-IT-maturity-benchmark
- https://adapt.com.au/customer-stories/george-weston-foods-leverages-adapts-research-to-benchmark-and-learn-from-peers
- https://adapt.com.au/customer-stories/canberra-data-centres-strengthens-market-leadership-with-adapts-edge-events
- https://adapt.com.au/customer-stories/skillsoft-solidifies-event-success-and-market-alignment-with-adapts-analysts
- https://adapt.com.au/customer-stories/pendo-returns-to-cio-edge-for-stronger-buyer-access-and-better-event-roi
- https://adapt.com.au/customer-stories/island-builds-enterprise-awareness-and-pipeline-through-adapt-edge-sponsorship
- https://adapt.com.au/customer-stories/brennan-builds-pipeline-and-sharper-local-strategy-through-adapt-edge
- https://adapt.com.au/customer-stories/fujitsu-builds-trusted-security-partnerships-through-adapt-edge-events

## 3. Side findings from the crawl (to verify)

- 23 sitemap URLs return HTTP 500 on production (repeatable across retries), yet still render a page. Examples: `/category/insights/`, `/category/portal-preview/`, `/resources-2-2/` to `/resources-2-5/`, and 16 single resource posts such as `/resources/how-cfos-are-rising-to-shape-enterprise-transformation-in-2025`. None had page-specific Formcrafts embeds.
- 2 sitemap URLs return 404: `/resources/peer-insights/cloud-infrastructure/australias-ai-infrastructure-future-depends-on-power-policy-and-community-trust-says-infrastructure-masons-founder` and `/resources/logicalis-australia-elevates-go-to-market-strategies-and-internal-events-with-adapts-analysts`.
- Several `/expertise/` and `/years/` term archives returned 500 or 503 during the bulk pass, then loaded on retry, which suggests rate limiting or slow queries under load.
- Sitemap hygiene: test terms (`/edge-partner-categories/test-capability/`, `/second-capability/`), `/become-an-event-partner-sample`, an attachment page (`/adapt-ccdc-edge-day-1-dinner-low-res-15/`) and `/years/2027/` are all listed publicly.
- `formcrafts.com` is dns-prefetched sitewide; `fc.js` loads on every page because of the header subscribe popup. Lazy-loading it on first click would help Lighthouse performance.
