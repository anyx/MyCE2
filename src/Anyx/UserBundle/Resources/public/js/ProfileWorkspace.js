
var Anyx = Anyx || {};

Anyx.Profile = Anyx.Profile || {};

Anyx.Profile.Workspace = Backbone.Router.extend({

	document : null,
	
    solutions: null,
    
	defaultAction : 'solved',
	
	actions	: {

		solved	: function( params, view ) {
			var skip = skip || 0;
            
            this.solutions = this.solutions || new Anyx.Collection.Solution([], {
				url : this.document.location.pathname + '/solved-crosswords/'
			});

			this.solutions.fetch({
                add     : true,
				data	: {
					skip : this.solutions.length
				},
				success	: function( solutions ) {
					view.render({
						solutions	: solutions.models
					});
				},
				error	: function() {
				}
			});
		},
        
        moresolved  : function( params, view ) {
            
        },
        created : function( params, view ) {
            
        }
	},
	
	views	: {},
	
	routes	: {
		"/*action/:param": "defaultRoute"
	},
	
	getAvailableRoutes	: function() {
		return ['solved', 'created', 'settings']
	},
	
	initialize	: function( options ) {
		this.document = options.document;
		this.views = options.views;
	},
	
	/**
	 *
	 */
	getActionView	: function( action ) {
		if ( !( action in this.views ) ) {
			//throw new Error( 'View for action "' + action + '" not found' );
		}
		
		return this.views[action];
	},
	
	/**
	 *
	 */
	callAction	: function( action, params, view ) {
		if ( !( action in this.actions ) ) {
			throw new Error( 'Action "' + action + '" not found' );
		}
		
		return this.actions[action].call(this, params, view );
	},
	
	/**
	 * 
	 */
	defaultRoute: function( action, params ) {
		var action = action || this.defaultAction;
		return this.callAction( action, params, this.getActionView( action ) );
	},

	created: function() {
	},

	settings: function() {
	}
});


