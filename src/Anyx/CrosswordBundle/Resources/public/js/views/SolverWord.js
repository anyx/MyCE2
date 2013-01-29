
Anyx.View.SolverWord = Anyx.View.extend({

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
    getSolver       : function() {
        return this.options.solver;
    },

    /**
     *
     */
    getSolverInputs       : function() {
        var word = this.model;
        var directionCoordinate = word.horizontal ? 'x' : 'y';
        var point = _.clone(word.position);
        var inputs = [];

        for (var i = 0; i < word.length; i++) {
            inputs[inputs.length] = this.getSolver().getInputByPosition(point);
            point[directionCoordinate]++;
        }

        return $(inputs);
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
	},
    
    highlight   : function(color) {
        this.getSolverInputs().css({
            backgroundColor : color
        });
    }
});

