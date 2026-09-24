#!/usr/bin/env node
/**
 * Critical-CSS generation script (SESSION-HANDOFF.md S111-S114).
 *
 * Self-discovering, no WordPress-side trigger needed: queries the site's
 * own WP REST API for every published page, filters to the 17 templates
 * identified in S110 as using ACF's flexible content_blocks field (the
 * only templates whose above-the-fold markup isn't fixed by the template
 * itself), and generates critical CSS for each via `critical` (Puppeteer-
 * based) at two viewports. Run on a schedule by
 * .github/workflows/critical-css.yml -- there are ~42 matching pages on
 * this site today (checked 2026-09-24), so a full regen every run is
 * simpler and safer than tracking incremental state, and it means an ACF
 * Options-page change (sitewide header/footer fields) is picked up
 * automatically on the next run with no separate detection logic.
 *
 * Deliberately isolated in its own tools/critical-css/package.json so this
 * script's Puppeteer/Chromium dependency is never pulled in by the theme's
 * main package.json / deploy.yml's npm ci step -- only
 * .github/workflows/critical-css.yml installs and runs this.
 */

const fs = require('fs');
const path = require('path');
const https = require('https');
const critical = require('critical');

const THEME_ROOT = path.resolve(__dirname, '..', '..');
const OUTPUT_DIR = path.join(THEME_ROOT, 'assets', 'critical');

const VIEWPORTS = [
    { width: 375, height: 667 },  // mobile
    { width: 1920, height: 1080 }, // desktop
];

// The 17 templates confirmed in S110 to dispatch through ACF's
// have_rows('content_blocks') -- kept in sync by hand with
// adapt_flexible_content_templates() removed from functions.php in S114
// (that PHP copy no longer exists; this is now the only copy, since
// nothing in WordPress needs the list anymore).
const FLEXIBLE_TEMPLATES = [
    'templates/template-edge-events.php',
    'templates/template-event-partner-landing.php',
    'templates/template-event-partner.php',
    'templates/template-executive-roundtables.php',
    'templates/template-flexible-nov.php',
    'templates/template-flexible.php',
    'templates/template-home-nov.php',
    'templates/template-home.php',
    'templates/template-landing.php',
    'templates/template-market-buyer.php',
    'templates/template-market.php',
    'templates/template-resource.php',
    'templates/template-services.php',
    'templates/template-subscribe.php',
    'templates/template-thank-you-new.php',
    'templates/template-thank-you.php',
    'templates/template-thankyou.php',
];

function fetchJson(url) {
    return new Promise((resolve, reject) => {
        https.get(url, { headers: { 'User-Agent': 'adapt-critical-css-generator' } }, (res) => {
            if (res.statusCode < 200 || res.statusCode >= 300) {
                reject(new Error(`GET ${url} -> HTTP ${res.statusCode}`));
                res.resume();
                return;
            }
            let body = '';
            res.on('data', (chunk) => { body += chunk; });
            res.on('end', () => {
                try {
                    resolve(JSON.parse(body));
                } catch (err) {
                    reject(new Error(`GET ${url} -> invalid JSON: ${err.message}`));
                }
            });
        }).on('error', reject);
    });
}

async function discoverPages(baseUrl) {
    const url = `${baseUrl.replace(/\/$/, '')}/wp-json/wp/v2/pages?per_page=100&status=publish&_fields=id,link,template`;
    const pages = await fetchJson(url);
    if (!Array.isArray(pages)) {
        throw new Error(`Unexpected WP REST API response from ${url}: ${JSON.stringify(pages).slice(0, 200)}`);
    }
    return pages
        .filter((p) => FLEXIBLE_TEMPLATES.includes(p.template))
        .map((p) => ({ post_id: p.id, url: p.link }));
}

async function generateOne(postId, url) {
    if (!postId || !url) {
        throw new Error(`generateOne: missing postId (${postId}) or url (${url})`);
    }
    const { css } = await critical.generate({
        url,
        inline: false,
        dimensions: VIEWPORTS,
        penthouse: {
            timeout: 60000,
        },
    });
    fs.mkdirSync(OUTPUT_DIR, { recursive: true });
    const outPath = path.join(OUTPUT_DIR, `${postId}.css`);
    fs.writeFileSync(outPath, css, 'utf8');
    console.log(`Wrote ${outPath} (${css.length} bytes) from ${url}`);
}

async function main() {
    const baseUrlArgIndex = process.argv.indexOf('--base-url');
    const payloadArgIndex = process.argv.indexOf('--payload');

    let items;
    if (payloadArgIndex !== -1 && process.argv[payloadArgIndex + 1]) {
        // Manual override for testing a specific page or two without a full
        // site crawl -- see the workflow_dispatch input in
        // .github/workflows/critical-css.yml.
        try {
            items = JSON.parse(process.argv[payloadArgIndex + 1]);
        } catch (err) {
            console.error('Failed to parse --payload as JSON:', err.message);
            process.exit(1);
        }
    } else if (baseUrlArgIndex !== -1 && process.argv[baseUrlArgIndex + 1]) {
        console.log(`Discovering flexible-content-template pages via ${process.argv[baseUrlArgIndex + 1]}/wp-json/...`);
        items = await discoverPages(process.argv[baseUrlArgIndex + 1]);
        console.log(`Found ${items.length} page(s) to generate.`);
    } else {
        console.error('Usage: node generate.js --base-url https://staging.adapt.com.au');
        console.error('   or: node generate.js --payload \'[{"post_id":123,"url":"https://staging.adapt.com.au/some-page/"}]\'');
        process.exit(1);
    }

    if (!Array.isArray(items) || items.length === 0) {
        console.error('No pages to generate critical CSS for.');
        process.exit(1);
    }

    let failures = 0;
    for (const item of items) {
        try {
            await generateOne(item.post_id, item.url);
        } catch (err) {
            failures++;
            // Deliberately continue rather than abort the whole batch on one
            // page's failure -- a single bad URL (deleted page, timeout)
            // should not block every other page's critical CSS from being
            // generated. Non-zero exit at the end still signals the run had
            // problems, for CI to surface.
            console.error(`FAILED for post_id=${item.post_id} url=${item.url}:`, err.message);
        }
    }

    if (failures > 0) {
        console.error(`${failures} of ${items.length} page(s) failed.`);
        process.exit(1);
    }
}

main();
