var gulp = require('gulp');

var path = require('../paths.js');

// gulp4 removed gulp.start() entirely, and ships its own file watcher
// (backed by chokidar, same as the old gulp-watch plugin used), so the
// gulp-watch dependency is no longer needed here.
gulp.task('watch', function(done) {
    gulp.watch(path.watch.fonts, gulp.series('build:fonts'));
    gulp.watch(path.watch.icons, gulp.series('build:icons'));
    gulp.watch(path.watch.favicon, gulp.series('build:favicons'));
    gulp.watch(path.watch.images, gulp.series('build:images'));
    gulp.watch(path.watch.style, gulp.series('build:styles'));
    gulp.watch(path.watch.scripts, gulp.series('build:scripts'));
    gulp.watch(path.watch.html, gulp.series('build:html'));
    // gulp.watch(path.watch.php, gulp.series('build:php'));
    done();
});
