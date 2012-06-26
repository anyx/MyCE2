/**
 *
 */

Constructor.Presenter = Crossword.Presenter || {};

/**
 *
 */
Constructor.Presenter.Editor = Backbone.Presenter.extend({
	
	/**
	 * 
	 */
	initialize		: function() {
		//
		this.registerWidget( 'grid', new Constructor.View.Grid({
				el		: $( this.options.selectors.grid ),
				rows	: 25,
				cols	: 25
		}));

        Constructor.View.Word.setClass( this.options.classes.wordTable );
		
        var wordsCollection = this.createWordsCollection( this.options.words );
        
		this.renderWords( wordsCollection );
		//
		this.registerWidget( 'wordForm', new Constructor.View.WordForm({
				el			: $( this.options.selectors.form ),
				selectors	: this.options.selectors.formSelectors,
                messages    : this.options.messages
		}));
		
		//
		this.registerWidget( 'saveButton', this.options.selectors.saveButton );

		this.getWidget( 'wordForm').bind( 'create', this.initWordDraggable, this );
		
		
		this.initEvents();
	},

    /**
     *
     */
    createWordsCollection   : function( words ) {
        var collection = this.getWidget('grid').getWords();
        collection.reset(words);
        return collection;
    },

	/**
	 *
	 */
	initEvents	: function() {
		this.initGridDroppable();
		this.initPreviewDroppable();
		this.initBodyDroppable();
		this.initSaveButton();
	},
	
	/**
	 *
	 */
	inGrid		: function( wordView ) {
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
		
		var activeCell = 0;
		
		wordView.getElement().draggable({
			cursor		: 'move',
			zIndex		: 10,
			appendTo	: grid.el,
			revert		: 'invalid',
			start		: function( event, ui ) {
				
				if ( _this.inGrid( wordView ) ) {
					grid.getWords().remove( wordView.getWord() );
				}
				
				//calc word cell position
				var wordPosition = wordView.getElement().offset();
				var cellPosition = 0;
				
				if ( wordView.model.isHorizontal() ) {
					cellPosition = ( event.originalEvent.pageX - wordPosition.left );
				} else {
					cellPosition = ( event.originalEvent.pageY - wordPosition.top );
				}
				 
				activeCell = Math.floor( cellPosition / cellSize );
				
				ui.helper.appendTo( grid.el ).css( 'position', 'absolute' );
			},
			drag		: function( event, ui ) {
				
				var x = Math.ceil( ( event.originalEvent.pageX - crosswordStartPoint.left ) / cellSize ) - 1;
				var y = Math.ceil( ( event.originalEvent.pageY - crosswordStartPoint.top ) / cellSize ) - 1;
				
				if ( wordView.model.isHorizontal() ) {
					x -= activeCell;
				} else {
					y -= activeCell;
				}
				
				wordView.model.set({
					position : {
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
				
				var wordModel = wordView.getWord();
				
				var activeCell = gridView.getCell( x, y );
				
				if ( activeCell != false && gridView.getWords().addWord( wordModel ) ) {
					//adding
					_this.getWidget( 'wordForm' ).clear();
                    _this.getWidget( 'wordPreview' ).clear();
                    _this.getWidget( 'statusBar' ).showMessage(_this.options.messages.wordAdded);
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
                _this.getWidget( 'wordPreview' ).clear();
                _this.getWidget( 'statusBar' ).showMessage(_this.options.messages.wordAdded);
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
                    //view instead model
					_this.getWidget( 'grid' ).getWords().remove( wordView.model );
					_this.getWidget( 'wordForm' ).bindWord( wordView );
				} else {
				}
				
				wordElement.remove();
			}
		});
	},

	/**
	 *
	 */
	initSaveButton	: function() {
		
		var _this = this;
		
		$( this.getWidget('saveButton').el ).on(
			'click',
			function( event ) {
				_this.saveWords();
			}
		);
	},
	
	/**
	 *
	 */
	saveWords		: function() {
		
		var _this = this;
		
		var savePath = Routing.generate(
							'constructor_save',
							{
								id : this.options.id
							}
		);
		
		$.ajax(
			savePath,
			{
				type        : 'POST',
                dataType    : 'json',
				data : {
					words : this.getWidget('grid').getWords().getData()
				},
				success: function( data ) {
                    if ( _.isObject(data) && data.success == true ) {
                        _this.getWidget('statusBar').showMessage( _this.options.messages.saveSuccessfully, true );
                        _this.clearWordViews();
                        console.log( 'success', data.words);
                        _this.renderWords( _this.createWordsCollection( data.words ) );
                    } else {
                        _this.getWidget('statusBar').showError( _this.options.messages.saveError, true );
                    }
				}
			}
		);
	},
	
	/**
	 *
	 */
	renderWord : function( wordView ) {

		var cellSize = this.getWidget('grid').getCellSize(); 

		if ( !this.inGrid( wordView ) ) {
			$( this.getWidget('grid').el ).append( wordView.getElement() );
			this.initWordDraggable( wordView );
		}

		var position = wordView.model.get('position');
		$( wordView.getElement() ).css({
			left	: position.x * cellSize,
			top		: position.y * cellSize
		});
	},
	
	/**
	 * 
	 */
	renderWords : function( wordsCollection ) {
		
		if ( _.isEmpty( wordsCollection ) ) {
			return;
		}

        this.getWidget('grid').getWords().reset( wordsCollection.models );
		this.clearWordViews();
        
		wordsCollection.each( function( wordModel ) {
			var wordView = new Constructor.View.Word({
				model	: wordModel
			});
			this.initWordDraggable( wordView );
			this.renderWord( wordView );
		}, this );
	},
	
	/**
	 *
	 */
	clearWordViews	: function() {
		$( this.getWidget( 'grid' ).el ).find( '.' + this.options.classes.wordTable ).remove();
	}
});