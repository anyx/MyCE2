Constructor.View.Word = Anyx.View.extend({

	initialize	: function() {
		this.render();
	},

	/**
	 * 
	 */
	render	: function() {
		
		if ( !(this.model instanceof Crossword.Model.Word) ) {
			return false;
		}
		
		if ( this.model.isHorizontal() ) {
			this._renderHorizontal( this.model );
		} else {
			this._renderVertical( this.model );
		}
		
		this.$( this.getElement() ).data('view', this);
	},

	/**
	 *
	 */
	getElement		: function() {
		this.wordElement = this.wordElement || this._createElement();
		return this.wordElement;
	},
	
	/** 
	 *
	 */
	getWord			: function() {
		return this.model;
	},

	/**
	 *
	 */
	setWord			: function( word ) {
		this.model = word;
		this.render();
	},

	/**
	 * 
	 */
	_renderVertical		: function( word ) {
		var tbody = this.getElement().children( 'tbody' ).empty();
		for ( var i = 0; i < word.getLength(); i++ ) {
			tbody
				.append( '<tr><td><div>'+word.get('text').charAt(i)+'</div></td></tr>' );
		}	
	},
	
	/**
	 * 
	 */
	_renderHorizontal	: function( word ) {
		var tbody = this.getElement().children( 'tbody' ).empty();
		tbody.append( $( '<tr />' ) );
		
		for ( var i = 0; i < word.getLength(); i++ ) {
			tbody.children( 'tr' ).append( '<td><div>'+word.get('text').charAt(i)+'</div></td>' );
		}
	},
	
	/**
	 *
	 */
	_createElement	: function() {
		 return this.$( '<table />' ).addClass( this.getClass() ).append( '<tbody />' );
	},
	
	/**
	 * 
	 */
	setBorderColor	: function( color ) {
		this.getElement().find( 'td' ).css( 'border-color', color );
	},

	/**
	 *
	 */
	getClass		: function() {
		if ( _.isUndefined( Constructor.View.Word._class ) ) {
			Constructor.View.Word._class = 'word';
		}
		return Constructor.View.Word._class;
	}
});

Constructor.View.Word.setClass = function(className) {
    Constructor.View.Word._class = className;
};