Crossword.View.Word = Crossword.View.extend({

	/**
	 * 
	 */
	render	: function() {
		
		if ( !(this.model instanceof Crossword.Model.Word) ) {
			throw new Error( 'Model object is incorrect' );
		}
		
		if ( this.model.isHorizontal() ) {
			this._renderHorizontal( this.model );
		} else {
			this._renderVertical( this.model );
		}
		
		this.$( this.getElement() ).data('view', this);
		
		this._initDraggable();
	},

	/**
	 *
	 */
	getElement		: function() {
		
		this.wordElement = this.wordElement || this.$( '<table />' ).addClass( 'word-table' ).append( '<tbody />' );
		
		return this.wordElement;
	},

	/**
	 *
	 */
	getWord			: function() {
		return this.options.model;
	},

	/**
	 *
	 */
	inGrid			: function() {
		return this.getElement().parent().get(0) == this.getContainer().getWidget( 'grid' ).el.get(0);
	},
	/**
	 * 
	 */
	_renderVertical		: function( word ) {
		for ( var i = 0; i < word.getLength(); i++ ) {
			this.getElement().children( 'tbody' )
				.append( '<tr><td>'+word.get('text').charAt(i)+'</td></tr>' );
		}	
	},
	
	/**
	 * 
	 */
	_renderHorizontal	: function( word ) {
		this.getElement().children( 'tbody' ).append( $( '<tr />' ) );
		
		for ( var i = 0; i < word.getLength(); i++ ) {
			this.getElement().children( 'tbody' )
				.children( 'tr' ).append( '<td>'+word.get('text').charAt(i)+'</td>' );
		}
	},
	
	/**
	 * 
	 */
	_initDraggable		: function() {
		
		var _this = this;
		
		var grid = this.getContainer().getWidget( 'grid' );
	   
		var crosswordStartPoint = this.$( grid.el ).offset();
	   
		var cellSize = grid.getCellSize(); 
		
		var left, top;
		
		this.$( this.getElement() ).draggable({
			cursor: 'move',
			zIndex: 10,
			appendTo: grid.el,
			revert: 'invalid',
			start: function( event, ui ) {
				
				if ( _this.inGrid() ) {
					grid.getWords().remove( _this.getWord() );
				}
				
				ui.helper.appendTo( grid.el ).css( 'position', 'absolute' );
			},
			drag: function( event, ui ) {
				
				var x = Math.ceil( ( event.originalEvent.pageX - crosswordStartPoint.left ) / cellSize ) - 1;
				var y = Math.ceil( ( event.originalEvent.pageY - crosswordStartPoint.top ) / cellSize ) - 1; 
			   
				_this.model.set({
					'point' : new Crossword.Model.Point({
						x : x,
						y : y	   
					})}
				);

				if ( !grid.getWords().canAddWord( _this.model ) ) {
					_this.setBorderColor( '#c00' );
				} else {
					_this.setBorderColor( '#000' );
				}

				if ( _this.inGrid() ) {
					
					left = x * cellSize;
					top = y * cellSize;
					
					ui.position.left = left;
					ui.position.top = top;
				} 
				/*
				else {
					left = - ( context.word_preview_point.left - ( x * cellSize + crosswordStartPoint.left ) );
					top = y * cellSize;
				}
			   */
			}
		});
	},
	
	/**
	 * 
	 */
	setBorderColor	: function( color ) {
		this.$( this.el ).find( 'td' ).css( 'color', color );
	}
});
