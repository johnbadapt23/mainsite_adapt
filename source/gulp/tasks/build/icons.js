var gulp = require('gulp');
var rename = require('gulp-rename');
var iconfont = require('gulp-iconfont');
var iconfontCss = require('gulp-iconfont-css');

var path = require('../../paths.js');
var timestamp = Math.round(Date.now()/1000);

gulp.task('build:icons', function(done) {
    // The second gulp.src() below reads icons.css written by the first
    // stream, so it has to wait for that stream to actually finish (gulp3
    // didn't enforce this and the two streams just raced); the 'finish'
    // listener on gulp.dest() sequences them correctly under gulp4.
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
        .pipe(gulp.dest(path.build.fonts))
        .on('finish', function() {
            gulp.src(path.build.fonts + 'icons.css')
                .pipe(rename('_icons.scss'))
                .pipe(gulp.dest('source/scss/global/'))
                .on('end', done);
        });
});
