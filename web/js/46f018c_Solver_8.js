/**
 * 
 */
Crossword.View.Solver = Backbone.View.extend({

	initialize	: function(){
		if ( _.isEmpty( this.options.words ) ) {
			throw new Error('Words is missing');
		}
	}
});