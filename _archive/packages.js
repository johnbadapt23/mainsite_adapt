var gulp = require('gulp');
var install = require("gulp-install");

gulp.task('build:packages', function() {
    return gulp.src(['./bower.json', './package.json'])
        .pipe(install());
});
