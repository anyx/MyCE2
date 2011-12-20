Crossword.View.WordForm = Crossword.View.extend({
	
	/**
	 * 
	 */
	initialize	: function( options ) {
		
		//widgets
		this.getContainer().setWidgets({
			wordInput			: this.$( options.selectors.word ),
			definitionInput		: this.$( options.selectors.definition ),
			directionChooser	: new Crossword.View.DirectionChooser({
				el : options.selectors.directionChooser
			}),
			wordPreview			: new Crossword.View.WordPreview({
				el	: options.selectors.wordPreview
			})
		});
		
		//events
		this.getInput('wordInput')
			.add( this.getInput('definitionInput') )
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
				
		this.$( this.getInput('directionChooser').el )
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
	getInput : function( code ) {
		return this.getContainer().getWidget( code );
	},
	
	/**
	 * 
	 */
	buildWord	: function() {
		
		var word = new Crossword.Model.Word({
			text			: this.getInput( 'wordInput' ).val(),
			definition		: this.getInput( 'definitionInput' ).val(),
			horizontal		: this.getInput( 'directionChooser' ).getDirection()
		});
		if ( word.isValid() ) {
			this.getInput( 'wordPreview' ).showWord( word );
		} else {
			this.getInput( 'wordPreview' ).clear();
		}
	}
});

