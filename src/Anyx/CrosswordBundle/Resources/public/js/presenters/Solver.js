/**
 * 
 */
Crossword.Presenter = Crossword.Presenter || {};

Crossword.Presenter.Solver = Backbone.Presenter.extend({

	initialize	: function(){
		if ( _.isEmpty( this.options.words ) ) {
			throw new Error('Words is missing');
		}
		
		_.each( this.options.words, function( word ) {
			this.addWord( word );
		}, this);
	},
	
	addWord		: function( word ) {
		
		var solverWord = new Crossword.View.SolverWord({
			word		: word,
			el			: $( '<div class="word-container"></div>'),
			cellSize	: this.options.cellSize,
			template	: this.options.templates.word
		});

		console.log( solverWord.el );
		$( this.options.el ).append( solverWord.el );
	}
});