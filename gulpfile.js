// SETTINGS
var fs = require('fs');
var requireDir = require('require-dir')('./source/gulp/tasks', { recurse: true });
var settings = JSON.parse(fs.readFileSync('./package.json', 'utf8'));
var config = require('./source/gulp/config.js');
var path = require('./source/gulp/paths.js');

// INCLUDES
var gulp = require('gulp');

// TASKS
// gulp3's array-dependency shorthand (gulp.task('name', [deps])) ran all
// listed tasks in PARALLEL with no ordering guarantee. gulp.parallel() below
// preserves that exact semantics (gulp4 removed the array shorthand and
// gulp.start(), so this file would otherwise fail to even load).
gulp.task('__start', gulp.parallel(
    config.serve.task,
    'watch'
));

// 'build:packages' (ran "bower install") was removed: Bower has been a dead,
// unmaintained package manager for years, and every vendor library it would
// have fetched is already committed directly under source/components/ --
// bower.json's own install.path ("source/vendor") didn't even match where
// anything actually lives, confirming it hadn't done anything useful in a
// long time. Archived bower.json/.bowerrc/the task file itself.
gulp.task('_build', gulp.parallel(
    'build:fonts',
    'build:icons',
    'build:images',
    'build:scripts',
    // Produces the actually-enqueued split CSS (main-nofooter.min.css +
    // footer.min.css, see source/gulp/tasks/build/styles-split.js and
    // functions.php's my_enqueue_scripts()). 'build:styles' (main.min.css,
    // an unused rollback artifact -- functions.php never enqueues it) was
    // dropped from here 2026-09-15: it added a real ~1.2min of serial
    // build time to every CI deploy for a file nothing reads, and the
    // float-refactor pipeline it was a safety net for has since been
    // stress-tested across 25+ sitewide fixes without needing it. The
    // task itself is untouched (source/gulp/tasks/build/styles.js) and
    // still wired into 'watch' for local dev live-reload -- only dropped
    // from the production build list. Run `npx gulp build:styles`
    // directly if main.min.css is ever needed again.
    'build:styles-split',
    'build:php'
));
