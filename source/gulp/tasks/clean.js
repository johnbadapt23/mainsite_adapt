var gulp = require('gulp');
var del = require('del');

var path = require('../paths.js');

gulp.task('_clean', function(){
    del(path.build.base + '**/*');
});
