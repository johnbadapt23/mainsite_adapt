var gulp = require('gulp');
var concat = require("gulp-concat");
var rename = require('gulp-rename');
var fileinclude = require('gulp-file-include');
var uglify = require('gulp-uglify');
var browserSync = require("browser-sync");
var reload = browserSync.reload;

var path = require('../../paths.js');
var error = require('../../error.js');

gulp.task('build:scripts', function () {
    gulp.src(path.src.scripts)
        //.pipe(rigger())
        .pipe(fileinclude({
            prefix: '@@',
            basepath: '@file'
        }))
        .on('error',error.handler)
        .pipe(uglify())
        .on('error',error.handler)
        .pipe(concat('main.min.js'))
        .pipe(gulp.dest(path.build.scripts))
        .pipe(reload({stream: true}));
});
