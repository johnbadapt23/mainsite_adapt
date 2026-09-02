// Footer-CSS-split build (2026-09-02, promoted from a ?dev=true-gated test
// to the default -- see main-nofooter.scss / footer-only.scss for the
// rationale and functions.php's my_enqueue_scripts() for the enqueue side).
// Produces the two files actually enqueued (main-nofooter.min.css,
// footer.min.css). build:styles still runs too and still produces
// main.min.css, kept around as an unused rollback artifact -- nothing
// here touches it. Wired into the '_build' parallel task list (gulpfile.js)
// and into both CI deploy workflows' "Compile CSS and JS" step.
var gulp = require('gulp');
var sass = require('gulp-sass')(require('sass'));
var sassGlob = require('../../sass-glob.js');
var prefixer = require('gulp-autoprefixer');
var cssmin = require('gulp-clean-css');
var concat = require('gulp-concat');
var browserSync = require('browser-sync');
var reload = browserSync.reload;
// See source/gulp/split-oversized-rules.js -- clean-css's merging below can
// combine well over a thousand selectors sharing one declaration (this file
// is the actual source of the gated float-refactor CSS that hits this) into
// a single rule, which browsers have been confirmed (live bisection on
// staging) to silently stop applying somewhere past ~1,000-1,200 selectors.
// Runs after cssmin, only splitting the rare oversized rule into several
// smaller ones with identical declarations -- never changes which selector
// gets which value.
var splitOversizedRules = require('../../split-oversized-rules.js');
// See source/gulp/fix-float-none-display.js: many gated float:none
// overrides target <span>/<a>/other inline-by-default tags that relied on
// the float itself to blockify them -- neutralising the float without
// also setting display:block silently breaks their layout. Runs BEFORE
// splitOversizedRules since it can fragment a previously-uniform merged
// rule into two (needs display:block / doesn't), which may then need
// splitting again.
var fixFloatNoneDisplay = require('../../fix-float-none-display.js');

var error = require('../../error.js');

// Same vendor CSS list as path.src.styles in paths.js, with
// source/scss/main.scss swapped for the footer-less entry point. Kept
// separate from paths.js/path.src.styles on purpose -- this is a
// dev-gated, not-yet-promoted build, not part of the shared build config
// other tasks (watch.js, etc.) read from.
var mainNoFooterSrc = [
    'source/components/aos/dist/aos.css',
    'source/components/magnific-popup/dist/magnific-popup.css',
    'source/components/select2/dist/css/select2.css',
    'source/components/perfect-scrollbar/css/perfect-scrollbar.css',
    'source/components/jquery.scrollbar-master/jquery.scrollbar.css',
    'source/components/slick-carousel/slick/slick.css',
    'source/components/slick-carousel/slick/slick-theme.css',
    'source/components/hover/css/hover-min.css',
    'source/scss/main-nofooter.scss',
];

gulp.task('build:styles-main-nofooter', function () {
    return gulp.src(mainNoFooterSrc)
        .pipe(prefixer())
        .pipe(sassGlob())
        .pipe(sass({
            outputStyle: 'compressed',
            sourceMap: false,
            errLogToConsole: true
        }))
        .on('error', error.handler)
        .pipe(cssmin({ level: { 1: { all: true }, 2: { all: true, restructureRules: true } } }))
        .pipe(fixFloatNoneDisplay())
        .pipe(splitOversizedRules())
        .pipe(concat('main-nofooter.min.css'))
        .pipe(gulp.dest('assets/css/'))
        .pipe(reload({ stream: true }));
});

gulp.task('build:styles-footer', function () {
    return gulp.src('source/scss/footer-only.scss')
        .pipe(prefixer())
        .pipe(sassGlob())
        .pipe(sass({
            outputStyle: 'compressed',
            sourceMap: false,
            errLogToConsole: true
        }))
        .on('error', error.handler)
        .pipe(cssmin({ level: { 1: { all: true }, 2: { all: true, restructureRules: true } } }))
        .pipe(fixFloatNoneDisplay())
        .pipe(splitOversizedRules())
        .pipe(concat('footer.min.css'))
        .pipe(gulp.dest('assets/css/'))
        .pipe(reload({ stream: true }));
});

gulp.task('build:styles-split', gulp.parallel(
    'build:styles-main-nofooter',
    'build:styles-footer'
));
