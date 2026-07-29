var gulp = require('gulp');
var ftp = require( 'vinyl-ftp' );

var path = require('../../paths.js');

gulp.task('deploy:ftp', function() {
    var conn = ftp.create(path.deploy.ftp);
    gulp.src( path.deploy.files, {
            base: path.deploy.base,
            buffer: false
        })
        .pipe( conn.dest( path.deploy.ftp.directory ) )
});
