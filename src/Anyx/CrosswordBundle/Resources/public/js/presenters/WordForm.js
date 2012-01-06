Crossword.View.WordForm = Backbone.Presenter.extend({
	
	/**
	 * 
	 */
	initialize	: function( options ) {
		//widgets
		this.registerWidgets({
			wordInput			: this.options.selectors.word,
			definitionInput		: this.options.selectors.definition,
			directionChooser	: new Crossword.View.DirectionChooser({
				el : this.options.selectors.directionChooser
			}),
			
			wordPreview			: new Crossword.View.WordPreview({
				el	: this.options.selectors.wordPreview
			}),
			
			statusBar			: new Crossword.View.StatusBar({
				el			: this.options.selectors.statusBar,
				template	: 't-status-bar'
			})
		});
		
		//events
		$( this.getWidget('wordInput').el )
			.add( $( this.getWidget('definitionInput').el ) )
			.on({
					change	: function( event ) {
						event.data.view.buildWord();
					},
					keyup	: function( event ) {
						event.data.view.buildWord();
					}
				},
				{
					view : this
				}
			);
				
		$( this.getWidget('directionChooser').el )
			.on({
					changeDirection : function( event, data ) {
						event.data.view.buildWord();
					}
				},
				{
					view : this
				}
			);
	},
	
	/**
	 * 
	 */
	buildWord	: function() {
		
		var word = new Crossword.Model.Word({
			text			: $( this.getWidget( 'wordInput' ).el ).val(),
			definition		: $( this.getWidget( 'definitionInput' ).el ).val(),
			horizontal		: this.getWidget( 'directionChooser' ).getDirection()
		});
		
		if ( word.isValid() ) {
			this.getWidget( 'wordPreview' ).showWord( word );
			this.trigger( 'create', this.getWidget( 'wordPreview' ).getCurrentView() );
			this.getWidget( 'statusBar').showMessage( 'Valid' );
			
		} else {
			this.getWidget( 'wordPreview' ).clear();
			this.getWidget( 'statusBar').showError( 'Invalid' );
		}
	},
	
	/**
	 * 
	 */
	bindWord	: function( wordView ) {
		this.clear();
		this._setValues( wordView.model.attributes );
		this.getWidget( 'wordPreview' ).showWord( wordView.model );
	},
	
	/**
	 *
	 */
	_setValues	: function( values ) {
		$( this.getWidget( 'wordInput' ).el ).val( values.text );
		$( this.getWidget( 'definitionInput' ).el ).val( values.definition );
		this.getWidget( 'directionChooser' ).setDirection( values.horizontal );
	},
	
	/**
	 * 
	 */
	clear		: function() {
		this._setValues({
			text		:	'',
			definition	:	'',
			horizontal	: true,
			id			: null
		});
	}
});

