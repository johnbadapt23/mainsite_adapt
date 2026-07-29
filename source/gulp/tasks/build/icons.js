var gulp = require('gulp');
var rename = require('gulp-rename');
var iconfont = require('gulp-iconfont');
var iconfontCss = require('gulp-iconfont-css');

var path = require('../../paths.js');
var timestamp = Math.round(Date.now()/1000);

gulp.task('build:icons', function() {
    gulp.src(path.src.icons)
        .pipe(iconfontCss({
          fontName: 'icons',
          targetPath: 'icons.css',
          fontPath: '../fonts/'
        }))
        .pipe(iconfont({
            fontName: 'icons',
            appendUnicode: true,
            formats: ['ttf', 'eot', 'woff', 'woff2', 'svg'],
            timestamp: timestamp,
        }))
        .pipe(gulp.dest(path.build.fonts));

    gulp.src(path.build.fonts + 'icons.css')
        .pipe(rename('_icons.scss'))
        .pipe(gulp.dest('source/scss/global/'));
});
