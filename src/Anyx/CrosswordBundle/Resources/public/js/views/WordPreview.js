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
		this.getCurrentView().setWord( word );
		this.setWordPosition( this.getCurrentView() );
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
	getCurrentView	: function() {
		if ( this._currentView == null ) {
			this._currentView = new Crossword.View.Word;
			this._currentView.getElement().appendTo( this.el );
		}
		
		return this._currentView;
	},
	
	/**
	 * 
	 */
	setWordPosition	: function( wordView ) {
		
		var wordWidth = this.$( this._currentView.getElement() ).width();
		var wordHeight = this.$( this._currentView.getElement() ).height();
		
		var previewBox = this.$( this.el );
		var previewWidth = previewBox.width();
		var previewHeight = previewBox.height();
		
		this._currentView.getElement().css({
			left	: Math.floor( previewWidth - wordWidth ) / 2 + 'px',
			top		: Math.floor( previewHeight - wordHeight ) / 2 + 'px'
		});
	}
});