/**
 *
 */
Crossword.View.Grid = Crossword.View.extend({

	/**
	 *
	 */
	initialize	: function() {
		this.getContainer().setWidget( 'grid', this );
		this.render();
	},
	
	/**
	 * 
	 */
	render		: function() {
		
		var table = this.$('<table />');
		
		for ( var i = 0; i < this.options.rows; i++) {
			var tr = this.$('<tr />');
			for ( var j = 0; j < this.options.cols; j++) {
				tr.append( '<td><div></div></td>' );
			}
			tr.appendTo( table );
		}
		
		table.appendTo( this.el );
		
		this._initDroppable();
		
		return this;
	},
	
	/**
	 * 
	 */
	getCellSize		: function() {
		return this.$( this.el ).find('td:eq(0)').width() + 3;
	},
	
	/**
	 * 
	 */
	getWords		: function() {
		return this.options.words;
	},
	
	/**
	 *
	 */
	_initDroppable	: function () {
		
		/*
		$( 'div.word-preview' ).droppable({
			accept		: '.word-view',
			drop: function( event, ui ) {
				ui.draggable.eq(0)
				.appendTo( $('div.word-preview') )
				.css({
					'left'	: '0px',
					'top'	: '0px',
					'position' 	: 'static'
				});
			}
		});
		*/
		var _this = this;
	   
		var crosswordStartPoint = this.$( this.el ).offset();
		
		$( this.el ).droppable({
			accept		: '.word-table',
			activeClass	: 'droppable-active',
			hoverClass	: 'droppable-hover',
			drop: function( event, ui ) {
				
				var wordView = ui.draggable.eq(0).data('view');
	
				var x = Math.ceil( ( event.originalEvent.pageX - crosswordStartPoint.left ) / _this.getCellSize() ) - 1;
				var y = Math.ceil( ( event.originalEvent.pageY - crosswordStartPoint.top )  / _this.getCellSize() ) - 1;
				
				var wordModel = wordView.getWord();
				
				var activeCell = _this.getCell( x, y );
				
				if ( activeCell != false && _this.getWords().addWord( wordModel ) ) {
					//adding
				} else {
                    //remove
					$( wordView ).appendTo( $('div.word-preview') ).css({
						'left'		: '0px',
						'top'		: '0px',
						'position' 	: 'relative'
					});
				}
				
				return false;
			}
		});
		
		$( 'body' ).droppable({
			accept	: '.word-table',
			drop	: function( event, ui ) {
				var  wordElement = ui.draggable.eq(0);
				_this.getWords().remove( wordElement.data('view') );
				wordElement.remove();
			}
		});
	},

	/**
	 * 
	 */
	getCell		: function( x, y ) {
		
        var row = this.$( this.el ).find( 'tr:eq(' + y +')' );
        if ( row.length > 0 ) {
            return row.find( 'td:eq(' + x + ') div' );
        }
        return false;
	}
});

