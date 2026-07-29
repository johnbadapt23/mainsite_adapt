var gulp = require('gulp');
var browserSync = require("browser-sync");

// Was assigned to "path" but referenced as "config" below (pre-existing bug,
// meaning this specific task never actually ran under gulp3 either since
// "config" was undefined) — fixed the name so it matches what's used.
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
