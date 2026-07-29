// Local drop-in replacement for the "gulp-sass-glob" npm package. That
// package has a path-joining bug -- it builds glob paths as
// `file.base + globName` (plain string concatenation, no separator), which
// silently made every glob-style @import in main.scss (libraries/partials/
// templates/sections) expand to zero files. This version uses path.join()
// instead, which is the only real change from upstream's approach.
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
