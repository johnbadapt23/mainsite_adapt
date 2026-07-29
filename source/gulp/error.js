// gulp-util is deprecated upstream (the gulp team's own guidance is to stop
// using it entirely). It was only used here for gutil.beep(), which just
// writes the terminal bell character -- replaced with that directly.
module.exports = {
	handler: function( error ) {
		console.log( 'Error: ' +  error.toString());
		process.stdout.write( '\x07' );
		this.emit( 'end' );
	}
};
