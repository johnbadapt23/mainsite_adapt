var gulp = require('gulp');
var php = require('gulp-connect-php');

// Was assigned to "path" but referenced as "config" below (pre-existing bug).
var config = require('../../config.js');

gulp.task('serve:php', function (done) {
    php.server({
        base: config.serve.base,
        port: config.serve.port,
        keepalive: true
    });
    done();
});
