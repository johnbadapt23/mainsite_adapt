var gulp = require('gulp');
var deploy = require( 'gulp-deploy-git' );

var path = require('../../paths.js');

gulp.task('deploy:git', function() {
    gulp.src('**/*')
        .pipe(deploy({
            repository: path.deploy.repository
        }));
});
