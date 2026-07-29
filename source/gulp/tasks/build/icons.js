var gulp = require('gulp');
var rename = require('gulp-rename');
var iconfont = require('gulp-iconfont');
var iconfontCss = require('gulp-iconfont-css');

var path = require('../../paths.js');
var timestamp = Math.round(Date.now()/1000);

// This task has two dependent steps: the icon font files + icons.css get
// generated first, then icons.css is renamed into a scss partial that
// main.scss imports. gulp3 fired both gulp.src() chains without waiting for
// the first to finish and without signaling completion at all; gulp4 requires
// a task to signal when it's done, so the second step is now explicitly
// chained to start only after the first one finishes writing its files.
gulp.task('build:icons', function(done) {
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
        .on('end', function() {
            gulp.src(path.build.fonts + 'icons.css')
                .pipe(rename('_icons.scss'))
                .pipe(gulp.dest('source/scss/global/'))
                .on('end', done)
                .on('error', done);
        })
        .on('error', done);
});
