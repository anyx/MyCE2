
var Anyx = Anyx || {};

Anyx.Profile = Anyx.Profile || {};

Anyx.Profile.Workspace = Backbone.Router.extend({

	document    : null,
	
    menu        : null,
    
	defaultAction : 'solved',

    collections : {},

    options     : {},
    
	initialize	: function( options ) {
        
        this.options = options;
		
        this.document = options.document;
        
        if ( options.views ) {
            for( var action in options.views ) {
                this.registerView( action, options.views[action] );
            }
        }
        
        this.menu = options.menuView;
        this.menu.show();
        
        this.menu.setLinks( this.getAvailableRoutes() );
	},

    views	: {},
	
	routes	: {
        "/*"                : "defaultRoute",
		"/*action/:param"   : "defaultRoute"
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

    /**
     *
     */
    _showCollection : function( type, url, view ) {
        var _this = this;
        var collection = new Anyx.Collection[type]([], {
            url     : url,
            success : function( collection, response ) {
                collection.trigger('reset', collection, response);
            },
            error   : function( error ) {
                _this.showError();
            }
        });

        view.model = collection;

        collection.bind('reset', function( collection, response ) {
            if ( response.length == 0 ) {
                collection.setIsAll( true );
            }

            view.render({
                collection	: collection
            });
        });
        
        view.bind('afterRender', function( view ) {
            view.el.appendTo( this.el.find('.tabs-content') );
        }, this.menu );
        
        collection.load();
    },

    /**
     *
     */
    registerView    : function( code, view ) {
        this.views[code] = view;
    },

    /**
     *
     */
	getAvailableRoutes	: function() {
        return this.options.routeNames;
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
    showError   : function( error ) {
        var message = this.translate( error || 'internalError');

        if ( _.isEmpty( this.options.errorView ) ) {
            alert( message );
        } else {
            this.options.errorView.show( message );
        }
    },
    
	/**
	 * 
	 */
	defaultRoute: function( action, params ) {
		var action = action || this.defaultAction;
		this.callAction( action, params, this.getActionView( action ) );
	},
    
    /**
     * 
     */
    translate   : function( message ) {
        var translatedMessage = message;
        if ( message in this.options.messages ) {
            translatedMessage = this.options.messages[message];
        }
        
        return translatedMessage;
    }
});