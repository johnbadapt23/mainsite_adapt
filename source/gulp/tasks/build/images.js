var gulp = require('gulp');
var rename = require('gulp-rename');
var image = require('gulp-image');
var tinypng = require('gulp-tinypng');
var pngquant = require('imagemin-pngquant');
var browserSync = require("browser-sync");
var reload = browserSync.reload;

var path = require('../../paths.js');

gulp.task('build:images', function () {
    gulp.src(path.src.images)
        .pipe(image({
          pngquant: true,
          optipng: false,
          zopflipng: true,
          advpng: true,
          jpegRecompress: false,
          jpegoptim: true,
          mozjpeg: true,
          gifsicle: true,
          svgo: true
        }))
        .pipe(gulp.dest(path.build.images))
        .pipe(reload({stream: true}));
});
