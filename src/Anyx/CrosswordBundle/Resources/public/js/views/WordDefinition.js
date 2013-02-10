/**
 * 
 */
Anyx.View.WordDefinition = Anyx.View.extend({
    
    tagName : 'li',
    
    events  :   {
        'mouseover'  : 'highlightWord',
        'mouseout'   : 'dehighlightWords',
        'click'      : 'focusOnWord'
    },

	initialize	: function() {
		this.render({
			definition	: this.options.definition,
			number		: this.options.number
		});
	},
    
    getSolver: function() {
        return this.options.solver;
    },
    
    highlightWord: function() {
        _.each(this.getSolver().getWordsViews(), function(word) {
            word.highlight('#fff');
        });

        this.options.word.highlight('#07DDE5');
    },
    
    dehighlightWords: function() {
        _.each(this.getSolver().getWordsViews(), function(word) {
            word.highlight('#fff');
        });
    },
    
    focusOnWord: function() {
        this.options.word.getSolverInputs().get(0).focus();
    }
});

