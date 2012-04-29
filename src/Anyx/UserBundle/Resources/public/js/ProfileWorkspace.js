
var Anyx = Anyx || {};

Anyx.Profile = Anyx.Profile || {};

Anyx.Profile.Workspace = Backbone.Router.extend({

	document : null,
	
	views	: {},
	
	routes	: {
		solved		: 'solved',
		created		: 'created',
		settings	: 'settings'
	},
	
	initialize	: function( options ) {
		this.document = options.document;
		this.views = options.views;
	},

	defaultRoute: function( actions ){
		alert(actions);
	},

	solved: function( skip ) {
		
		var skip = skip || 0;
		var _this = this;
		var solutions = new Anyx.Collection.Solution([], {
			url : this.document.location.pathname + '/solved-crosswords/'
		});
		
		solutions.fetch({
			data: {
				page : skip
			},
			success: function( solutions ) {
				_this.views.solved.render({
					solutions	: solutions.models
				});
			}
		});
		
	},

	created: function() {
	},

	settings: function() {
	}
});


