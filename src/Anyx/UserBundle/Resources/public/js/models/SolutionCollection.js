
Anyx.Collection = Anyx.Collection || {};

Anyx.Collection.Solution = Backbone.Collection.extend({

	model: Anyx.Model.Solution,

	initialize : function( models, options ) {
		this.url = options.url;
	}

});