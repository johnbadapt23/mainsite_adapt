var gulp = require('gulp');
var concat = require("gulp-concat");
var rename = require('gulp-rename');
// gulp-sass 3.x used node-sass, which is abandoned upstream and no longer
// compiles on modern Node (only ships prebuilt binaries for very old Node
// versions). Swapped to gulp-sass 6.x + dart-sass ("sass" package), which is
// pure JS/no native compile step and actively maintained. Same options below
// (outputStyle/sourceMap) are supported identically by both engines.
var sass = require('gulp-sass')(require('sass'));
// gulp-sass-glob (npm) has a path-joining bug that silently made every
// glob-style @import in main.scss expand to nothing. See
// source/gulp/sass-glob.js for the fix.
var sassGlob = require('../../sass-glob.js');
var prefixer = require('gulp-autoprefixer');
// gulp-minify-css is deprecated upstream (points people at gulp-clean-css);
// swapped in directly, same no-args call, same minification role in the pipe.
var cssmin = require('gulp-clean-css');
var csso = require('gulp-csso');
var browserSync = require("browser-sync");
var reload = browserSync.reload;

var path = require('../../paths.js');
var error = require('../../error.js');

gulp.task('build:styles', function () {
    // gulp4 requires a task to return its stream to know when it's done.
    return gulp.src(path.src.styles)
        .pipe(prefixer())
        .pipe(sassGlob())
        .pipe(sass({
            outputStyle: 'compressed',
            sourceMap: false,
            errLogToConsole: true
        }))
        .on('error',error.handler)
        // clean-css's level 2 "restructureRules" merges rules that share a
        // selector (several of the big new template files reopen the same
        // top-level selector -- body.customer_stories, body.template-evr,
        // section.webinar-speaker-block, etc. -- multiple times across the
        // file), correctly resolving the CSS cascade first so only the
        // property value that actually wins survives. Verified against the
        // real compiled output before enabling this: e.g.
        // "body.customer_stories{overflow-x:initial}" followed later by
        // "body.customer_stories{overflow-x:hidden}" collapsed to just the
        // second rule -- the first was always being overridden anyway, so
        // nothing about what actually renders changes, it just stops
        // shipping the dead declaration. This is clean-css's own documented
        // "safe for production" optimization tier, not an experimental flag.
        .pipe(cssmin({ level: { 1: { all: true }, 2: { all: true, restructureRules: true } } }))
        //.pipe(csso())
        .pipe(concat('main.min.css'))
        //.pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(path.build.styles))
        .pipe(reload({stream: true}));
});
