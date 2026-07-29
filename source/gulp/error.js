var gutil = require( 'gulp-util' );

module.exports = {
	handler: function( error ) {
		console.log( 'Error: ' +  error.toString());
		gutil.beep(  );
		this.emit( 'end' );
	}
};
