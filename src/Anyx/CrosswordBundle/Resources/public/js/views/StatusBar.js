/**
 * 
 */
Crossword.View.StatusBar = Crossword.View.extend({

	events		: {
		'mouseover .icon'	: 'showHint'
	},

	/**
	 *
	 */
	initialize	: function( options ) {
		this.options.timeout = options.timeout || 3000;
	},

	/**
	 * 
	 */
	showMessage	: function( text ) {
		return this._showMessage( text, 'info' );
	},

	/**
	 * 
	 */
	showError	: function( text ) {
		return this._showMessage( text, 'error' );
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
	
	showHint	: function() {
		
		var hintElement = this.$( this.options.hintElement );
		
		hintElement.show();
		
		clearInterval( this._timer );
		
		this._timer = setInterval( function(){
			hintElement.fadeOut();
		}, this.options.timeout )
	},
	
	_showMessage	: function( text, type ) {
		this.clear();
		this.render({text: text, status: type});
	},
	
	_timer		: 0
});