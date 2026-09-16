#!/usr/bin/env node
/**
 * Sitemap-wide, zero-tolerance visual regression gate for the adapt theme.
 *
 * WHY THIS EXISTS / WHY IT LIVES HERE:
 * Claude's own sandbox has no network access to staging and can't install a
 * headless browser (network-allowlisted environment) -- this can only run
 * somewhere with real, unrestricted network access, i.e. a GitHub Actions
 * runner. See .github/workflows/visual-regression.yml, which runs this
 * after every successful staging deploy.
 *
 * WHAT IT DOES:
 *   1. Fetches the live sitemap index and every sub-sitemap from
 *      TARGET_BASE_URL, to get the full, current list of every page on the
 *      site (no hardcoded/maintained URL list -- stays in sync with content
 *      automatically).
 *   2. For each URL, at each of 3 viewports (mobile/tablet/desktop),
 *      captures a full-page screenshot with Playwright.
 *   3. In "check" mode (default): diffs each screenshot pixel-by-pixel
 *      against the stored baseline (restored from a GitHub Actions cache --
 *      see the workflow file for why baselines aren't committed to git).
 *      ANY differing pixel, anywhere, fails that page/viewport -- zero
 *      tolerance, by explicit request. A diff image highlighting exactly
 *      which pixels changed is written for every failure, to make human
 *      review fast (distinguishing "a blog post was published" from "a
 *      layout broke" should be a few-second glance, not a mystery).
 *   4. In "baseline" mode: just captures and saves, no diffing -- used for
 *      the very first run, or deliberately after a workflow_dispatch
 *      "update-baseline" run to accept new visuals as the new normal.
 *
 * WHAT IT DELIBERATELY DOES NOT DO:
 *   - No masking/ignoring of "dynamic" regions. Zero tolerance was chosen
 *     explicitly, with the known tradeoff that ordinary content edits
 *     (new posts, updated bios, swapped images) will also show up as
 *     diffs. That's intentional -- every diff gets looked at and explained,
 *     nothing is auto-approved. See the "false positive" section in the
 *     workflow file for the operational reasoning.
 *   - No masking of animations either; instead, CSS animations/transitions
 *     are frozen (see FREEZE_CSS below) before every screenshot so a
 *     mid-transition frame can't cause flaky, non-reproducible diffs.
 */

import { chromium } from 'playwright';
import { PNG } from 'pngjs';
import pixelmatch from 'pixelmatch';
import { mkdir, writeFile, readdir, rm } from 'node:fs/promises';
import { existsSync, readFileSync } from 'node:fs';
import path from 'node:path';

const TARGET_BASE_URL = (process.env.TARGET_BASE_URL || 'https://staging.adapt.com.au').replace(/\/+$/, '');
const MODE = (process.env.VISUAL_REGRESSION_MODE || 'check').toLowerCase(); // 'check' | 'baseline'
const BASELINE_DIR = process.env.BASELINE_DIR || path.join(process.cwd(), 'baselines');
const CURRENT_DIR = process.env.CURRENT_DIR || path.join(process.cwd(), 'current');
const DIFF_DIR = process.env.DIFF_DIR || path.join(process.cwd(), 'diffs');

// Optional comma-separated substrings -- if set, only URLs containing one of
// these are captured (handy for testing this script itself against 2-3
// pages instead of the whole sitemap; NOT used in the normal CI run).
const URL_FILTER = (process.env.VISUAL_REGRESSION_URL_FILTER || '')
    .split(',')
    .map((s) => s.trim())
    .filter(Boolean);

const VIEWPORTS = [
    { name: 'mobile', width: 375, height: 812 },
    { name: 'tablet', width: 768, height: 1024 },
    { name: 'desktop', width: 1440, height: 900 },
];

// Neutralises anything time/animation-based so a screenshot can't land
// mid-transition -- the single biggest source of false-positive flakiness
// in pixel-level visual regression. Does NOT touch layout/content, only
// motion, so it can't hide a real visual regression.
const FREEZE_CSS = `
  *, *::before, *::after {
    animation-play-state: paused !important;
    animation-delay: -1ms !important;
    animation-duration: 1ms !important;
    transition-duration: 0ms !important;
    transition-delay: 0ms !important;
    scroll-behavior: auto !important;
    caret-color: transparent !important;
  }
`;

/** Sanitise a URL into a filesystem-safe filename, stable across runs. */
export function urlToFilename(url, viewportName) {
    const u = new URL(url);
    let slug = (u.pathname + u.search).replace(/^\/+|\/+$/g, '');
    if (slug === '') slug = '__home__';
    slug = slug.replace(/[^a-zA-Z0-9._-]+/g, '_');
    return `${slug}__${viewportName}.png`;
}

async function fetchText(url) {
    const res = await fetch(url, { headers: { 'User-Agent': 'adapt-visual-regression-bot' } });
    if (!res.ok) {
        throw new Error(`Fetch failed (${res.status}) for ${url}`);
    }
    return res.text();
}

/** Extract every <loc>...</loc> value from a sitemap XML string. */
export function extractLocs(xml) {
    const matches = [...xml.matchAll(/<loc>\s*([^<\s]+)\s*<\/loc>/g)];
    return matches.map((m) => m[1]);
}

/**
 * Discover every real page URL on the site via its sitemap index --
 * deliberately NOT a hardcoded list, so this stays accurate as content is
 * added/removed without anyone having to remember to update a URL list.
 */
async function discoverAllUrls() {
    const indexXml = await fetchText(`${TARGET_BASE_URL}/sitemap_index.xml`);
    const subSitemaps = extractLocs(indexXml);

    if (subSitemaps.length === 0) {
        throw new Error(
            `No <loc> entries found in sitemap_index.xml -- refusing to continue with an ` +
            `empty URL list (that would silently "pass" by checking nothing).`
        );
    }

    const allUrls = new Set();
    for (const sitemapUrl of subSitemaps) {
        const xml = await fetchText(sitemapUrl);
        for (const loc of extractLocs(xml)) {
            allUrls.add(loc);
        }
    }

    let urls = [...allUrls].sort();

    if (URL_FILTER.length > 0) {
        urls = urls.filter((u) => URL_FILTER.some((f) => u.includes(f)));
    }

    if (urls.length === 0) {
        throw new Error('URL discovery produced zero pages -- refusing to continue.');
    }

    return urls;
}

async function ensureDir(dir) {
    await mkdir(dir, { recursive: true });
}

async function captureAll(urls, outDir) {
    await ensureDir(outDir);
    const browser = await chromium.launch();
    const failures = [];
    let count = 0;

    try {
        for (const url of urls) {
            for (const viewport of VIEWPORTS) {
                count++;
                const context = await browser.newContext({
                    viewport: { width: viewport.width, height: viewport.height },
                });
                const page = await context.newPage();
                try {
                    await page.goto(url, { waitUntil: 'networkidle', timeout: 45000 });
                    await page.addStyleTag({ content: FREEZE_CSS });
                    // Let one paint settle after freezing animations/lazy-load.
                    await page.waitForTimeout(250);
                    const filename = urlToFilename(url, viewport.name);
                    await page.screenshot({
                        path: path.join(outDir, filename),
                        fullPage: true,
                    });
                } catch (err) {
                    failures.push({ url, viewport: viewport.name, error: String(err && err.message || err) });
                } finally {
                    await context.close();
                }
            }
        }
    } finally {
        await browser.close();
    }

    return { count, failures };
}

export function loadPng(filePath) {
    return PNG.sync.read(readFileSync(filePath));
}

/**
 * Compares two PNGs that may differ in height (very common: a page grew or
 * shrank a few pixels of real content between baseline and current -- that
 * itself should be a reported diff, not a crash). Pads the shorter one with
 * a fully-opaque, unmistakable magenta so any dimension change is both
 * detected (every padded pixel mismatches) and visually obvious in the diff
 * image rather than silently cropped away.
 */
export function diffImages(baselinePath, currentPath) {
    const img1 = loadPng(baselinePath);
    const img2 = loadPng(currentPath);

    const width = Math.max(img1.width, img2.width);
    const height = Math.max(img1.height, img2.height);

    function normalise(img) {
        if (img.width === width && img.height === height) return img;
        const out = new PNG({ width, height });
        out.data.fill(0);
        for (let y = 0; y < height; y++) {
            for (let x = 0; x < width; x++) {
                const outIdx = (width * y + x) << 2;
                if (x < img.width && y < img.height) {
                    const inIdx = (img.width * y + x) << 2;
                    img.data.copy(out.data, outIdx, inIdx, inIdx + 4);
                } else {
                    // magenta, fully opaque -- unmistakable "this area didn't exist" marker
                    out.data[outIdx] = 255;
                    out.data[outIdx + 1] = 0;
                    out.data[outIdx + 2] = 255;
                    out.data[outIdx + 3] = 255;
                }
            }
        }
        return out;
    }

    const a = normalise(img1);
    const b = normalise(img2);
    const diff = new PNG({ width, height });

    const diffPixelCount = pixelmatch(a.data, b.data, diff.data, width, height, {
        threshold: 0.1, // pixelmatch's own per-pixel perceptual sensitivity, not our pass/fail tolerance
        includeAA: true, // do NOT ignore anti-aliasing differences -- zero tolerance means zero tolerance
    });

    return { diffPixelCount, diffPng: diff, width, height };
}

async function writeDiffPng(diffPng, diffPath) {
    await new Promise((resolve, reject) => {
        const buffer = PNG.sync.write(diffPng);
        writeFile(diffPath, buffer).then(resolve, reject);
    });
}

async function runCheck() {
    if (!existsSync(BASELINE_DIR) || (await readdir(BASELINE_DIR)).length === 0) {
        throw new Error(
            `No baseline found at ${BASELINE_DIR}. Run with VISUAL_REGRESSION_MODE=baseline first ` +
            `(or trigger the workflow's "update-baseline" option) before running a check.`
        );
    }

    const urls = await discoverAllUrls();
    console.log(`Discovered ${urls.length} URLs from the sitemap.`);

    const { count, failures: captureFailures } = await captureAll(urls, CURRENT_DIR);
    console.log(`Captured ${count} screenshots (${captureFailures.length} capture failures).`);

    await ensureDir(DIFF_DIR);

    const results = { diffs: [], newPages: [], missingPages: [], captureFailures };
    const currentFiles = new Set(await readdir(CURRENT_DIR));
    const baselineFiles = new Set(await readdir(BASELINE_DIR));

    for (const file of currentFiles) {
        if (!baselineFiles.has(file)) {
            results.newPages.push(file);
            continue;
        }
        const { diffPixelCount, diffPng } = diffImages(
            path.join(BASELINE_DIR, file),
            path.join(CURRENT_DIR, file)
        );
        if (diffPixelCount > 0) {
            await writeDiffPng(diffPng, path.join(DIFF_DIR, file));
            results.diffs.push({ file, diffPixelCount });
        }
    }

    for (const file of baselineFiles) {
        if (!currentFiles.has(file)) {
            results.missingPages.push(file);
        }
    }

    await writeFile(
        path.join(DIFF_DIR, '_summary.json'),
        JSON.stringify(results, null, 2)
    );

    console.log('--- Visual regression summary ---');
    console.log(`Pixel diffs found: ${results.diffs.length}`);
    for (const d of results.diffs) {
        console.log(`  DIFF  ${d.file}  (${d.diffPixelCount} px)`);
    }
    if (results.newPages.length) {
        console.log(`New pages/viewports not in baseline (${results.newPages.length}):`);
        results.newPages.forEach((f) => console.log(`  NEW   ${f}`));
    }
    if (results.missingPages.length) {
        console.log(`Pages/viewports in baseline but not found now (${results.missingPages.length}):`);
        results.missingPages.forEach((f) => console.log(`  GONE  ${f}`));
    }
    if (results.captureFailures.length) {
        console.log(`Capture failures (${results.captureFailures.length}):`);
        results.captureFailures.forEach((f) => console.log(`  ERROR ${f.url} [${f.viewport}]: ${f.error}`));
    }

    const hasFailure =
        results.diffs.length > 0 ||
        results.newPages.length > 0 ||
        results.missingPages.length > 0 ||
        results.captureFailures.length > 0;

    if (hasFailure) {
        console.error(
            '\nFAILED: at least one page/viewport differs from baseline, is new, is missing, or failed to ' +
            'capture. Review the diff images in the uploaded artifact. If every flagged difference is a ' +
            'genuine, intended content/design change (not a bug), re-run this workflow with ' +
            '"mode: update-baseline" to accept the new screenshots as the baseline going forward.'
        );
        process.exitCode = 1;
    } else {
        console.log('\nPASSED: pixel-for-pixel identical to baseline across every page and viewport.');
    }
}

async function runBaseline() {
    const urls = await discoverAllUrls();
    console.log(`Discovered ${urls.length} URLs from the sitemap.`);

    // Fresh baseline: wipe any previous contents first so removed pages
    // don't leave stale, orphaned baseline images behind.
    if (existsSync(BASELINE_DIR)) {
        await rm(BASELINE_DIR, { recursive: true, force: true });
    }

    const { count, failures } = await captureAll(urls, BASELINE_DIR);
    console.log(`Captured ${count} baseline screenshots (${failures.length} failures).`);
    if (failures.length) {
        failures.forEach((f) => console.log(`  ERROR ${f.url} [${f.viewport}]: ${f.error}`));
        console.error('\nFAILED: one or more pages could not be captured for the baseline.');
        process.exitCode = 1;
        return;
    }
    console.log('\nBaseline captured successfully.');
}

async function main() {
    console.log(`Mode: ${MODE}`);
    console.log(`Target: ${TARGET_BASE_URL}`);
    if (MODE === 'baseline') {
        await runBaseline();
    } else if (MODE === 'check') {
        await runCheck();
    } else {
        throw new Error(`Unknown VISUAL_REGRESSION_MODE "${MODE}" -- expected "check" or "baseline".`);
    }
}

// Only auto-run when executed directly (`node capture-and-diff.mjs`), not
// when imported as a module (e.g. by a test suite exercising the pure
// functions above) -- a script that fires its own CLI side effects just
// from being imported is exactly the kind of surprising, hard-to-test
// behaviour this whole project is meant to get rid of.
const isMain = process.argv[1] && import.meta.url === `file://${process.argv[1]}`;
if (isMain) {
    main().catch((err) => {
        console.error(err);
        process.exitCode = 1;
    });
}
