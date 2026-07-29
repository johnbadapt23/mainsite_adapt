var gulp = require('gulp');
var inject = require('gulp-inject');
var concat = require("gulp-concat");
var rename = require('gulp-rename');
var browserSync = require("browser-sync");
var reload = browserSync.reload;

var path = require('../../paths.js');

gulp.task('build:php', function () {
    return gulp.src(path.src.php)
        .pipe(reload({stream: true}));
});
