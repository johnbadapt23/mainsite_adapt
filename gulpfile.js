// SETTINGS
var fs = require('fs');
var requireDir = require('require-dir')('./source/gulp/tasks', { recurse: true });
var settings = JSON.parse(fs.readFileSync('./package.json', 'utf8'));
var config = require('./source/gulp/config.js');
var path = require('./source/gulp/paths.js');

// INCLUDES
var gulp = require('gulp');

// TASKS
gulp.task('__start', [
    config.serve.task,
    'watch'
]);

gulp.task('_build', [
    'build:packages',
    'build:fonts',
    'build:icons',
    'build:images',
    'build:scripts',
    'build:styles',
    'build:php'
]);
