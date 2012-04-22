
var Anyx = Anyx || {};

Anyx.Profile = Anyx.Profile || {};

Anyx.Profile.Workspace = Backbone.Router.extend({

	routes: {
		'solved'	: 'solved',
		'created'	: 'created',
		'settings'	: 'settings'
	},

	solved: function() {
		console.log( 'sol' );
	},

	created: function() {
		console.log( 'created' );
	},

	settings: function() {
	}
});


