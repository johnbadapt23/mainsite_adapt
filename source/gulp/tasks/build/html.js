var gulp = require('gulp');
var fileinclude = require('gulp-file-include');
var rename = require('gulp-rename');
var browserSync = require("browser-sync");
var reload = browserSync.reload;

var path = require('../../paths.js');

gulp.task('build:html', function () {
    gulp.src(path.src.html)
        .pipe(fileinclude({
            prefix: '@@',
            basepath: './source/templates/'
        }))
        .pipe(gulp.dest(path.build.html))
        .pipe(reload({stream: true}));
});
