// Dev-gated footer-CSS-split build (2026-09-02). See main-nofooter.scss /
// footer-only.scss for the rationale. These two tasks are purely additive:
// they produce two NEW files (main-nofooter.min.css, footer.min.css)
// alongside the existing main.min.css from build:styles, which is
// untouched. Nothing here changes what build:styles produces or how it's
// enqueued by default -- functions.php only switches to these files when
// visiting with ?dev=true. Not part of the '_build' parallel task list by
// default; run explicitly with `gulp build:styles-split` (or wired into a
// deploy step later once the split is confirmed and promoted).
var gulp = require('gulp');
var sass = require('gulp-sass')(require('sass'));
var sassGlob = require('../../sass-glob.js');
var prefixer = require('gulp-autoprefixer');
var cssmin = require('gulp-clean-css');
var concat = require('gulp-concat');
var browserSync = require('browser-sync');
var reload = browserSync.reload;

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
        .pipe(concat('footer.min.css'))
        .pipe(gulp.dest('assets/css/'))
        .pipe(reload({ stream: true }));
});

gulp.task('build:styles-split', gulp.parallel(
    'build:styles-main-nofooter',
    'build:styles-footer'
));
