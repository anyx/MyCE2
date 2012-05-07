/**
 * 
 */
Constructor.View.StatusBar = Anyx.View.extend({

	events		: {
		'mouseover .icon'	: 'showHint'
	},

	_timer		: 0,
    
	/**
	 *
	 */
	initialize	: function( options ) {
		this.options.timeout = options.timeout || 3000;
        this.render({text: '', status: null});
	},

	/**
	 * 
	 */
	showMessage	: function( text, forceShowHint ) {
		return this._showMessage( text, 'info', forceShowHint );
	},

	/**
	 * 
	 */
	showError	: function( text, forceShowHint ) {
		return this._showMessage( text, 'important', forceShowHint );
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
	_showMessage	: function( text, type, forceShowHint ) {
		var forceShowHint = forceShowHint || false;
		
		this.clear();
		
		this.render({text: text, status: type});
		
		if ( forceShowHint ) {
			this.showHint();
		}
	}
});