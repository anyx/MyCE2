/**
 * 
 */
Crossword.View.WordPreview = Crossword.View.extend({
	/**
	 * 
	 */
	_currentView	: null,
	
	/**
	 * 
	 */
	showWord		: function( word ) {
		this._getCurrentView().setWord( word );
		console.log( this._getCurrentView().getElement(), word.get('text') );
	},
	
	/**
	 * 
	 */
	clear			: function() {
		this.$( this.el ).empty();
		this._currentView = null
	},
	
	/**
	 * 
	 */
	_getCurrentView	: function() {
		if ( this._currentView == null ) {
			this._currentView = new Crossword.View.Word;
			this._currentView.getElement().appendTo( this.el );
		}
		
		return this._currentView;
	}
});