/**
 * 
 */
var Crossword = {}

/**
 * 
 */
Crossword.Model = Backbone.Model.extend({
	/**
	 *
	 */
	isValid		: function() {
		return this._performValidation( this.attributes, {} );
	}
});