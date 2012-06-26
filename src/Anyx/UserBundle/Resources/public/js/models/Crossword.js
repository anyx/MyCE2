/**
 * 
 */
Anyx.Model = Anyx.Model || {};

/**
 * 
 */
Anyx.Model.Crossword = Backbone.Model.extend({
	/**
	 *
	 */
	isValid		: function() {
		return this._performValidation( this.attributes, {} );
	}
});