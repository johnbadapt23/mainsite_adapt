var gulp = require('gulp');
var php = require('gulp-connect-php');

// Was assigned to "path" but used below as "config" -- a pre-existing typo
// that meant every config.* reference here was actually undefined.
var config = require('../../config.js');

gulp.task('serve:php', function (done) {
    php.server({
        base: config.serve.base,
        port: config.serve.port,
        keepalive: true
    });
    done();
});
