/**
 *
 */

Crossword.Presenter = Crossword.Presenter || {};

/**
 *
 */
Crossword.Presenter.Editor = Backbone.Presenter.extend({
	
	/**
	 * 
	 */
	initialize		: function( options ) {
		//
		this.registerWidget( 'grid', new Crossword.View.Grid({
				words	: new Crossword.Model.WordsCollection,
				el		: $( options.selectors.grid ),
				rows	: 25,
				cols	: 25
		}));
		
		//
		this.registerWidget( 'wordForm', new Crossword.View.WordForm({
				el			: $( options.selectors.form ),
				selectors	: {
					word				: options.selectors.word,
					definition			: options.selectors.definition,
					directionChooser	: options.selectors.directionChooser,
					wordPreview			: options.selectors.wordPreview
				}
		}));
		
		this.getWidget( 'wordForm').bind('create', this.initWordDraggable, this );
		
		Crossword.View.Word.setClass( this.options.classes.wordTable );
		
		this.initEvents();
	},

	/**
	 *
	 */
	initEvents	: function() {
		this.initGridDroppable();
		this.initPreviewDroppable();
		this.initBodyDroppable();
	},
	
	/**
	 *
	 */
	inGrid				: function( wordView ) {
		return wordView.getElement().parent().get(0) == this.getWidget( 'grid' ).el.get(0);	
	},
	
	/**
	 * 
	 */
	initWordDraggable	: function( wordView ) {
		
		var _this = this;
		
		var grid = this.getWidget( 'grid' );
	   
		var crosswordStartPoint = $( grid.el ).offset();
	   
		var cellSize = grid.getCellSize(); 
		
		var left, top;
		
		wordView.getElement().draggable({
			cursor		: 'move',
			zIndex		: 10,
			appendTo	: grid.el,
			revert		: 'invalid',
			start		: function( event, ui ) {
				
				if ( _this.inGrid( wordView ) ) {
					grid.getWords().remove( wordView.getWord() );
				}
				
				ui.helper.appendTo( grid.el ).css( 'position', 'absolute' );
			},
			drag		: function( event, ui ) {
				
				var x = Math.ceil( ( event.originalEvent.pageX - crosswordStartPoint.left ) / cellSize ) - 1;
				var y = Math.ceil( ( event.originalEvent.pageY - crosswordStartPoint.top ) / cellSize ) - 1; 
				wordView.model.set({
					'position' : {
						x : x,
						y : y	   
				}});

				if ( !grid.getWords().canAddWord( wordView.model ) ) {
					wordView.setBorderColor( '#c00' );
				} else {
					wordView.setBorderColor( '#000' );
				}

				if ( _this.inGrid( wordView ) ) {
					
					left = x * cellSize;
					top = y * cellSize;
					
					ui.position.left = left;
					ui.position.top = top;
				}
			}
		});		
	},
	
	/**
	 * 
	 */
	initGridDroppable	: function() {
		var gridView = this.getWidget('grid');
		var crosswordStartPoint = $( gridView.el ).offset();
		var _this = this;
		
		$( gridView.el ).droppable({
			accept		: '.' + this.options.classes.wordTable,
			activeClass	: 'droppable-active',
			hoverClass	: 'droppable-hover',
			drop: function( event, ui ) {
				
				var wordView = ui.draggable.eq(0).data('view');
	
				var x = Math.ceil( ( event.originalEvent.pageX - crosswordStartPoint.left ) / gridView.getCellSize() ) - 1;
				var y = Math.ceil( ( event.originalEvent.pageY - crosswordStartPoint.top )  / gridView.getCellSize() ) - 1;
				
				console.log( 'drop', x, y );
				
				var wordModel = wordView.getWord();
				
				var activeCell = gridView.getCell( x, y );
				
				if ( activeCell != false && gridView.getWords().addWord( wordModel ) ) {
					//adding
					_this.getWidget( 'wordForm' ).clear();
				} else {
					_this.getWidget( 'wordForm' ).bindWord( wordView );
					wordView.getElement().remove();
				}
				
				return false;
			}
		});
	},
	
	/**
	 * 
	 */
	initBodyDroppable	: function() {
		
		var _this = this;
		$( 'body' ).not( this.getWidget( 'wordPreview' ).el ).droppable({
			accept	: '.' + this.options.classes.wordTable,
			drop	: function( event, ui ) {
				var  wordElement = ui.draggable.eq(0);
				var wordView = wordElement.data( 'view' );
				_this.getWidget( 'grid' ).getWords().remove( wordView.model );
				_this.getWidget( 'wordForm' ).clear();
				wordElement.remove();
			}
		});
	},
	
	/**
	 * 
	 */
	initPreviewDroppable	: function() {
		var _this = this;
		
		$( this.getWidget( 'wordPreview' ).el ).droppable({
			accept	: '.' + this.options.classes.wordTable,
			drop	: function( event, ui ) {
				var wordElement = ui.draggable.eq(0);
				var wordView = wordElement.data( 'view' );
				
				if( _this.inGrid( wordView ) ) {
					_this.getWidget( 'grid' ).getWords().remove( wordView.model );
					_this.getWidget( 'wordForm' ).bindWord( wordView );
				} else {
					
				}
				
				wordElement.remove();
			}
		});
	}
});