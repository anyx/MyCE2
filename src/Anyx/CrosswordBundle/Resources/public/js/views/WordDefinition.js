/**
 * 
 */
Crossword.View.WordDefinition = Crossword.View.extend({

	initialize	: function() {
		this.render({
			definition	: this.options.definition,
			number		: this.options.number
		});
	}
});
