var gulp = require('gulp');
var concat = require("gulp-concat");
var rename = require('gulp-rename');
var sass = require('gulp-sass');
var sassGlob = require('gulp-sass-glob');
var prefixer = require('gulp-autoprefixer');
var cssmin = require('gulp-minify-css');
var csso = require('gulp-csso');
var browserSync = require("browser-sync");
var reload = browserSync.reload;

var path = require('../../paths.js');
var error = require('../../error.js');

gulp.task('build:styles', function () {
    gulp.src(path.src.styles)
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
