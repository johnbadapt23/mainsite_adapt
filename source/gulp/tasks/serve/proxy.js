var gulp = require('gulp');
var browserSync = require("browser-sync");

var config = require('../../config.js');

gulp.task('serve:proxy', function () {
    browserSync({
        proxy: config.serve.url,
        host: config.serve.host,
        port: config.serve.port,
        open: config.serve.open,
        notify: false,
        tunnel: config.serve.tunnel,
        logPrefix: config.serve.log
    });
});
