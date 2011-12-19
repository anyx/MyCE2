Crossword.View.WordForm = Crossword.View.extend({
	
	events	: {
		//'change ' + options.selectors.word			: 'buildWord'
		//'keyup '  + options.selectors.word			: 'buildWord',
		//'change ' + options.selectors.definition	: 'buildWord',
		//'keyup '  + options.selectors.definition	: 'buildWord'
	},
	
	/**
	 * 
	 */
	initialize	: function( options ) {
		
		//widgets
		this.getContainer().setWidgets({
			wordInput			: this.$( options.selectors.word, this.el),
			definitionInput		: this.$( options.selectors.definition, this.el ),
			directionChooser	: new Crossword.View.DirectionChooser({
				el : options.selectors.directionChooser
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
	}
});

