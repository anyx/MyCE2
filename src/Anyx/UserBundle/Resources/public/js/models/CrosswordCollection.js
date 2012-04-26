
Anyx.Collection = Anyx.Collection || {};

Anyx.Collection.Crossword = Backbone.Collection.extend({

	model: Anyx.Model.Crossword,

	initialize : function( options ) {
		this.url = options.url;
	}

});