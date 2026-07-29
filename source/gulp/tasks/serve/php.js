var gulp = require('gulp');
var php = require('gulp-connect-php');

var path = require('../../config.js');

gulp.task('serve:php', function () {
    php.server({
        base: config.serve.base,
        port: config.serve.port,
        keepalive: true
    });
});
