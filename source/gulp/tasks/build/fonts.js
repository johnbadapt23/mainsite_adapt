var gulp = require('gulp');
var browserSync = require("browser-sync");
var reload = browserSync.reload;

var path = require('../../paths.js');

// gulp-fontgen is required lazily, inside the task, rather than at the top
// of this file. gulpfile.js eager-loads every task file up front (via
// require-dir with recurse:true) just to register task names, regardless of
// which task actually gets run -- and gulp-fontgen throws at require-time
// (not task-run-time) if the system "fontforge" binary isn't installed. That
// used to mean *any* gulp invocation (e.g. CI running only build:styles /
// build:scripts) needed fontforge installed just to load this file. Deferring
// the require means it's only evaluated -- and only needs fontforge -- when
// build:fonts is actually invoked.
gulp.task('build:fonts', function() {
    var fontgen = require("gulp-fontgen");
    // gulp4 requires a task to return its stream to know when it's done.
    return gulp.src(path.src.fonts)
        .pipe(fontgen({
            dest: path.build.fonts,
            css_fontpath: '../fonts/'
        }))
        .pipe(reload({stream: true}));
});
