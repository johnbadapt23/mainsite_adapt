var gulp = require('gulp');
var concat = require("gulp-concat");
var rename = require('gulp-rename');
// gulp-sass 3.x used node-sass, which is abandoned upstream and no longer
// compiles on modern Node (only ships prebuilt binaries for very old Node
// versions). Swapped to gulp-sass 5.x + dart-sass ("sass" package), which is
// pure JS/no native compile step and actively maintained. Same options below
// (outputStyle/sourceMap) are supported identically by both engines.
var sass = require('gulp-sass')(require('sass'));
// gulp-sass-glob (npm) has a path-joining bug that silently made every
// glob-style @import in main.scss (libraries/partials/templates/sections)
// expand to nothing. See source/gulp/sass-glob.js for details and the fix.
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
    // gulp4 requires a task to return its stream (or a promise, or call a
    // callback) to know when it's done; gulp3 didn't enforce this.
    return gulp.src(path.src.styles)
        .pipe(prefixer())
        .pipe(sassGlob())
        .pipe(sass({
            outputStyle: 'compressed',
            sourceMap: false,
            errLogToConsole: true
        }))
        .on('error',error.handler)
        .pipe(cssmin())
        //.pipe(csso())
        .pipe(concat('main.min.css'))
        //.pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(path.build.styles))
        .pipe(reload({stream: true}));
});
