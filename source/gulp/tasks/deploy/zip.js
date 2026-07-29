var gulp = require('gulp');
var zip = require('gulp-zip');

var path = require('../../paths.js');

gulp.task('deploy:zip', function() {
    gulp.src(path.deploy.files)
        .pipe(zip(path.deploy.archive))
        .pipe(gulp.dest(path.deploy.folder));
});
