// Local drop-in replacement for the "gulp-sass-glob" npm package (0.0.8),
// which this project used to expand glob-style @import statements in
// main.scss (e.g. `@import "templates/**/*.scss";`) into a real list of
// individual @import lines before handing the file to the Sass compiler.
//
// gulp-sass-glob has a real bug: it resolves each glob with
//   glob.sync(file.base + globName)
// using plain string concatenation instead of path.join(). gulp.src() sets
// file.base to a directory path WITHOUT a trailing slash (e.g.
// ".../source/scss"), so "file.base + 'templates/**/*.scss'" produces
// ".../source/scsstemplates/**/*.scss" -- a path with no separator, which
// never matches anything. Every glob-style @import in main.scss (libraries,
// partials, templates, sections) silently expanded to nothing as a result,
// and the compiled CSS was missing every template/partial-specific style.
// Confirmed by testing directly against this project's real main.scss.
//
// This is otherwise an exact copy of the original package's logic, with
// path.join() used for the one line that was broken.
var path = require('path');
var fs = require('fs');
var through = require('through2');
var glob = require('glob');

function gulpSassGlobbing() {
  function process(filename, isSass) {
    if (fs.statSync(filename).isDirectory() || !path.extname(filename).match(/\.sass|\.scss/i)) {
      return '';
    }

    filename = filename.replace(/\\/g, '/');

    return '@import "' + filename + '"' + (isSass ? '' : ';') + '\n';
  }

  function transform(file, env, callback) {
    var contents = file.contents.toString('utf-8');
    var reg = /@import\s+["']([^"']*\*[^"']*)["']/;
    var isSass = path.extname(file.path) === '.sass';

    var result;

    while ((result = reg.exec(contents)) !== null) {
      var sub = result[0];
      var globName = result[1];

      // The fix: path.join instead of "file.base + globName".
      var files = glob.sync(path.join(file.base, globName));
      var replaceString = '';

      files.forEach(function (filename) {
        if (filename !== file.path) {
          replaceString += process(filename, isSass);
        }
      });

      contents = contents.replace(sub, replaceString);
    }

    file.contents = Buffer.from(contents);
    callback(null, file);
  }

  return through.obj(transform);
}

module.exports = gulpSassGlobbing;
