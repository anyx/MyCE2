/**
 * 
 */
Constructor.View.StatusBar = Anyx.View.extend({

	events		: {
		'mouseover'	: 'showHint'
	},

    classesMap  : {
        'info'  : 'blue-gradient',
        'error' : 'red-gradient'
    },

	_timer		: 0,
    
    defaultStatus : 'info',
    
    _translations : {},
    
	/**
	 *
	 */
	initialize	: function( options ) {
		this.options.timeout = options.timeout || 3000;
        this._showMessage( options.defaultText, this.defaultStatus );
        if ( 'translations' in options ) {
            this._translations = options.translations;
        }
	},

	/**
	 * 
	 */
	showMessage	: function( text, forceShowHint ) {
		return this._showMessage( this._prepareMessage(text), 'info', forceShowHint );
	},

	/**
	 * 
	 */
	showError	: function( text, forceShowHint ) {
		return this._showMessage( this._prepareMessage(text), 'error', forceShowHint );
	},

	/**
	 * 
	 */
	showLoader	: function( text, forceShowHint ) {
		return this._showMessage( this._prepareMessage(text), 'error', forceShowHint );
	},

	/**
	 * 
	 */
	showLoading	: function() {
		
	},
	
	/**
	 * 
	 */
	clear		: function() {
		clearTimeout( this._timer );
	},

	/**
	 * 
	 */
	showHint	: function() {
		
		var hintElement = this.$( this.options.hintElement );
		
		hintElement.show();
		
		clearInterval( this._timer );
		
		this._timer = setInterval( function(){
			hintElement.fadeOut();
		}, this.options.timeout )
	},

    /**
     *
     */
    translate   : function(text) {
        if ( text in this._translations ) {
            return this._translations[text];
        }
        return text;
    },
    
	/**
	 * 
	 */
	_showMessage	: function( text, type, forceShowHint ) {
		var forceShowHint = forceShowHint || false;
		
		this.clear();
		
		this.render({text: this.translate(text), status: type});
		
		if ( forceShowHint ) {
			this.showHint();
		}
	},
    
    _prepareMessage : function( text ) {
        var message = '';
        if ( _.isArray(text) ) {
            _.each(text, function( messageElement ){
                message = message + this.translate( messageElement ) + '<br />';
            }, this)
        } else {
            message = text;
        }
        
        return message;
    }
});