
var Crossword = Crossword || {};


Crossword.View = Backbone.View.extend({
	
	/**
	 * 
	 */
	render		: function( vars ) {
		
		if ( _.isEmpty( this.options.template ) ) {
			throw new Error( 'Template not found' );
		}
		
		var content = _.template( $( '#' + this.options.template ).html(), vars );
		
		this.$( this.el ).html( content );
	}
});
