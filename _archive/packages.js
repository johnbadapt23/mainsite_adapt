var gulp = require('gulp');
var install = require("gulp-install");

gulp.task('build:packages', function() {
    gulp.src(['./bower.json', './package.json'])
        .pipe(install());
});
