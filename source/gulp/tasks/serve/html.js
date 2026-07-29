var gulp = require('gulp');
var browserSync = require("browser-sync");

var path = require('../../config.js');

gulp.task('serve:html', function () {
    browserSync({
        server: {
            baseDir: config.serve.base
        },
        tunnel: config.serve.tunnel,
        host: config.serve.host,
        port: config.serve.port,
        logPrefix: config.serve.log
    });
});
