var gulp = require('gulp');
var fontgen = require("gulp-fontgen");
var browserSync = require("browser-sync");
var reload = browserSync.reload;

var path = require('../../paths.js');

gulp.task('build:fonts', function() {
    // gulp4 requires a task to return its stream to know when it's done.
    return gulp.src(path.src.fonts)
        .pipe(fontgen({
            dest: path.build.fonts,
            css_fontpath: '../fonts/'
        }))
        .pipe(reload({stream: true}));
});
