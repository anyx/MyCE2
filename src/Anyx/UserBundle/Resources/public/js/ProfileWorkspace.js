
var Anyx = Anyx || {};

Anyx.Profile = Anyx.Profile || {};

Anyx.Profile.Workspace = Backbone.Router.extend({

	document : null,
	
	routes	: {
		solved		: 'solved',
		created		: 'created',
		settings	: 'settings'
	},
	
	initialize	: function( options ) {
		this.document = options.document;
	},

	defaultRoute: function( actions ){
		alert(actions);
	},

	solved: function( skip ) {
		var skip = skip || 0;
		
		var collection = new Anyx.Collection.Crossword({
			url : this.document.location.pathname + '/solved-crosswords/' + skip
		});
		
		collection.fetch();
		
		//console.log( collection );
	},

	created: function() {
	},

	settings: function() {
	}
});


