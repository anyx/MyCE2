
Crossword.View.SolverWord = Crossword.View.extend({
	
	
	/**
	 * 
	 */
	initialize		: function() {
		this.render( {word : this.options.word });
		
		this.$(this.el).css({
			left	: this.options.word.position.x * this.options.cellSize,
			top		: this.options.word.position.y * this.options.cellSize
		})
	}
});

