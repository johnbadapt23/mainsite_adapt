// gulp-watch + gulp.start() were gulp3-era: gulp4 removed gulp.start()
// entirely (calling it throws) and ships its own native gulp.watch(), so
// this is rewritten on gulp4's built-in watcher/gulp.series() instead of the
// external gulp-watch package.
var gulp = require('gulp');
var path = require('../paths.js');

gulp.task('watch', function(done) {
    gulp.watch(path.watch.fonts, gulp.series('build:fonts'));
    gulp.watch(path.watch.icons, gulp.series('build:icons'));
    gulp.watch(path.watch.favicon, gulp.series('build:favicons'));
    gulp.watch(path.watch.images, gulp.series('build:images'));
    gulp.watch(path.watch.style, gulp.series('build:styles'));
    gulp.watch(path.watch.scripts, gulp.series('build:scripts'));
    gulp.watch(path.watch.html, gulp.series('build:html'));
    done();
});
