#!/usr/bin/env node
/**
 * Critical-CSS generation script (SESSION-HANDOFF.md S111/S112).
 *
 * Reads a JSON payload of {post_id, url} pairs (or a --regen-all flag plus
 * a --base-url to crawl the flexible-content templates' published pages
 * via the WP REST API), runs `critical` against each URL at two viewports,
 * and writes the merged result to assets/critical/{post_id}.css in the
 * theme root (two levels up from this file).
 *
 * Deliberately isolated in its own tools/critical-css/package.json so this
 * script's Puppeteer/Chromium dependency is never pulled in by the theme's
 * main package.json / deploy.yml's npm ci step -- only
 * .github/workflows/critical-css.yml installs and runs this.
 *
 * NOT yet wired into a live trigger. See SESSION-HANDOFF.md S112 for the
 * remaining pieces (the WordPress save_post hook and the GitHub PAT it
 * needs) before this can run automatically.
 */

const fs = require('fs');
const path = require('path');
const critical = require('critical');

const THEME_ROOT = path.resolve(__dirname, '..', '..');
const OUTPUT_DIR = path.join(THEME_ROOT, 'assets', 'critical');

const VIEWPORTS = [
    { width: 375, height: 667 },  // mobile
    { width: 1920, height: 1080 }, // desktop
];

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
    const payloadArgIndex = process.argv.indexOf('--payload');
    if (payloadArgIndex === -1 || !process.argv[payloadArgIndex + 1]) {
        console.error('Usage: node generate.js --payload \'[{"post_id":123,"url":"https://staging.adapt.com.au/some-page/"}]\'');
        process.exit(1);
    }

    let items;
    try {
        items = JSON.parse(process.argv[payloadArgIndex + 1]);
    } catch (err) {
        console.error('Failed to parse --payload as JSON:', err.message);
        process.exit(1);
    }

    if (!Array.isArray(items) || items.length === 0) {
        console.error('--payload must be a non-empty JSON array of {post_id, url} objects');
        process.exit(1);
    }

    let failures = 0;
    for (const item of items) {
        try {
            await generateOne(item.post_id, item.url);
        } catch (err) {
            failures++;
            // Deliberately continue rather than abort the whole batch on one
            // page's failure -- a single bad URL (deleted page, 404, timeout)
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
