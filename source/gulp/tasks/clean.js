var gulp = require('gulp');
var del = require('del');

var path = require('../paths.js');

gulp.task('_clean', function(){
    // del() returns a promise; gulp4 needs it returned to know when the task is done.
    return del(path.build.base + '**/*');
});
