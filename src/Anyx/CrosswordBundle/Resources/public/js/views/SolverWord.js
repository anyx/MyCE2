
Crossword.View.SolverWord = Anyx.View.extend({

	/**
	 * 
	 */
	initialize		: function() {
		this.render({word : this.model});
		this.el = this.$(this.el).children().eq(0);

		this.model.position = {
			x	: parseInt( this.model.position.x ),
			y	: parseInt( this.model.position.y )
		} 

		this.el.css({
			left	: this.model.position.x * this.options.cellSize,
			top		: this.model.position.y * this.options.cellSize
		});
		this.setLettersPositions();
	},
	
	/**
	 *
	 */
	setLettersPositions	: function() {
		var directionCoord = this.model.horizontal ? 'x' : 'y';
		var point = _.clone(this.model.position);
		this.$('INPUT').each(function(){
			$(this).data('position', _.clone( point ) );
			point[directionCoord]++;
		});
	},
	
	/**
	 *
	 */
	getInputs	: function() {
		return this.$('INPUT');
	}
});

