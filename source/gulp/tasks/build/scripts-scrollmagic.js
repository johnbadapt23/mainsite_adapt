var gulp = require('gulp');
var concat = require("gulp-concat");
var fileinclude = require('gulp-file-include');
var uglify = require('gulp-uglify');
var browserSync = require("browser-sync");
var reload = browserSync.reload;

var path = require('../../paths.js');
var error = require('../../error.js');

// ScrollMagic + its GSAP animation plugin, split out of build:scripts
// (2026-09-15). Previously bundled unconditionally into every page's
// main.min.js, even though the only code in main.js that touches them
// (the fixed-scroller / sticky-slider-cards blocks, both guarded behind
// `$('.fixed-scroller-inner').length` / `.scrolling-container.length`
// element checks) can only ever render on the 8 templates gated by
// functions.php's adapt_page_needs_gsap() -- the same templates GSAP/
// ScrollTrigger are already conditionally CDN-loaded on (see
// my_enqueue_scripts()). animation.gsap.js checks for a global TweenLite/
// TweenMax at parse time and console.errors if it's missing, which fired on
// every single one of the other ~52 pages since GSAP was never loaded there
// for it to find -- harmless (nothing ever actually broke; the animation
// code itself is properly element-gated) but it was pure console noise plus
// ~30KB of dead-on-arrival JS parsed on pages that never touch it, the same
// class of waste GSAP's own CDN gating and the lottie-player gating already
// fixed elsewhere in this file.
//
// Built into its own scrollmagic.min.js here, enqueued conditionally
// alongside gsap-js/scrolltrigger-js in functions.php's my_enqueue_scripts()
// -- see adapt_page_needs_gsap() there for the exact template list this now
// ships to. Wired into the '_build' parallel task list (gulpfile.js) and
// into the CI deploy workflow's "Compile CSS and JS" step, same as
// build:styles-split.
gulp.task('build:scripts-scrollmagic', function () {
    return gulp.src(path.src.scriptsScrollmagic)
        .pipe(fileinclude({
            prefix: '@@',
            basepath: '@file'
        }))
        .on('error', error.handler)
        .pipe(uglify())
        .on('error', error.handler)
        .pipe(concat('scrollmagic.min.js'))
        .pipe(gulp.dest(path.build.scripts))
        .pipe(reload({ stream: true }));
});
