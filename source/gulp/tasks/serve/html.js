var gulp = require('gulp');
var browserSync = require("browser-sync");

// Was assigned to "path" but used below as "config" -- a pre-existing typo
// that meant every config.* reference here was actually undefined.
var config = require('../../config.js');

gulp.task('serve:html', function (done) {
    browserSync({
        server: {
            baseDir: config.serve.base
        },
        tunnel: config.serve.tunnel,
        host: config.serve.host,
        port: config.serve.port,
        logPrefix: config.serve.log
    });
    done();
});
