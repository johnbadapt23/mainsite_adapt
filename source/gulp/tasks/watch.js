var gulp = require('gulp');
var watch = require('gulp-watch');

var path = require('../paths.js');

gulp.task('watch', function(){
    // watch([path.watch.fonts], function(event, cb) {
    //     gulp.start('build:fonts');
    // });
    // watch([path.watch.icons], function(event, cb) {
    //     gulp.start('build:icons');
    // });
    watch([path.watch.favicon], function(event, cb) {
        gulp.start('build:favicons');
    });
    watch([path.watch.images], function(event, cb) {
        gulp.start('build:images');
    });
    watch([path.watch.style], function(event, cb) {
        gulp.start('build:styles');
    });
    watch([path.watch.scripts], function(event, cb) {
        gulp.start('build:scripts');
    });
    watch([path.watch.html], function(event, cb) {
        gulp.start('build:html');
    });
    // watch([path.watch.php], function(event, cb) {
    //     gulp.start('build:php');
    // });
});
