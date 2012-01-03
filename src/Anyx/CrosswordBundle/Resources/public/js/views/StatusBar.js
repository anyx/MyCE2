/**
 * 
 */
Crossword.View.StatusBar = Crossword.View.extend({

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
	
	_showMessage	: function( text, type ) {
		this.clear();
		this.render({text: text, status: type});
		/*
		this._timer = setInterval( function(){
			this.$( '.i-status-text' ).fadeOut();
		}, this.options.timeout )
		*/
	},
	
	_timer		: 0
});