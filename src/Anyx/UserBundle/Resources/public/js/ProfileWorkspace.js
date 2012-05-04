
var Anyx = Anyx || {};

Anyx.Profile = Anyx.Profile || {};

Anyx.Profile.Workspace = Backbone.Router.extend({

	document : null,
	
    menu    : null,
    
	defaultAction : 'solved',

    collections : {},

	initialize	: function( options ) {
		this.document = options.document;

        if ( options.views ) {
            for( var action in options.views ) {
                this.registerView( action, options.views[action] );
            }
        }
        
        this.menu = options.menuView;
        this.menu.setLinks( this.getAvailableRoutes() );
        this.menu.show();
	},

    views	: {},
	
	routes	: {
		"/*action/:param": "defaultRoute"
	},

	actions	: {
		solved	: function( params, view ) {
            this._showCollection(
                'Solution',
                this.document.location.pathname + 'solved-crosswords/',
                view
            );
		},
        
        created : function( params, view ) {
            this._showCollection(
                'Crossword',
                this.document.location.pathname + 'created-crosswords/',
                view
            );
        },
        
        settings : function( params, view ) {
            
        }
	},

    _showCollection : function( type, url, view ) {
        var collection = new Anyx.Collection[type]([], {
            url     : url,
            success : function( collection, response ) {

                if ( response.length == 0 ) {
                    collection.setIsAll( true );
                }

                view.render({
                    collection	: collection
                });
            },
            error   : function( error ) {
                alert( error );
            }
        });

        view.model = collection;

        collection.load();
    },

    registerView    : function( code, view ) {
        this.views[code] = view;
    },

	getAvailableRoutes	: function() {
		return {
            'solved'    : 'solved',
            'created'   : 'created',
            'settings'  : 'settings'
        }
	},
	
	/**
	 *
	 */
	getActionView	: function( action ) {
		if ( !( action in this.views ) ) {
			throw new Error( 'View for action "' + action + '" not found' );
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
		
        this.menu.setActiveLink( action );
        this.menu.show();
        
        for( var viewCode in this.views ) {
            var el = this.views[viewCode].el;
            if ( el.length > 0 ) {
                el.hide();
            }
        }
        
        var currentViewContainer = view.el;
        if ( currentViewContainer.length > 0 ) {
            currentViewContainer.show();
        }

		return this.actions[action].call(this, params, view );
	},
	
	/**
	 * 
	 */
	defaultRoute: function( action, params ) {
		var action = action || this.defaultAction;
		this.callAction( action, params, this.getActionView( action ) );
	},

	created: function() {
	},

	settings: function() {
	}
});


