/**
 *
 */
Crossword.Model = Crossword.Model || {};

/**
 * Word
 */
Crossword.Model.Word = Crossword.Model.extend({

	/**
	 *
	 */
	defaults	: {
		text			: null,
		definition		: null,
		horizontal		: true,
		position		: new Crossword.Model.Point({
			x : 0,
			y : 0
		})
	},
	
	/**
	 *
	 */
	validate	: {
		text	: {
			required	: true,
			minlength	: 3,
			maxlength	: 25
		},
		definition	: {
			required	: true,
			minlength	: 8,
			maxlength	: 50
		}
	},

	/**
	 * Text to lower case
	 */
	set		: function( attributes, options ) {
		if ( typeof attributes == 'object' && 'text' in attributes && _.isString( attributes.text ) ) {
			attributes.text = attributes.text.toLowerCase();
		}
		Backbone.Model.prototype.set.call(this, attributes, options);
	},
	
	/**
	 * @return boolean
	 */
	isHorizontal	: function() {
		return this.get( 'horizontal' );
	},
	
	/**
	 * @return int
	 */
	getLength	: function() {
		return this.get('text').length;
	},

	/**
	 * @return {Point}
	 */
	getStartPoint	: function() {
		return this.get('position');
	},
	
	/**
	 * @return {Point}
	 */
	getEndPoint		: function() {
		
		var position = this.get('position');

		if ( this.isHorizontal() ) {
			return new Crossword.Model.Point({
						x	: position.get('x') + parseInt( this.getLength() - 1 ),
						y	: position.get('y')
				});
		} else {
			return new Crossword.Model.Point({
						x	: position.get('x'),
						y	: position.get('y') + parseInt( this.getLength() - 1 )
				});
		}
	},
	
	/**
	 *
	 */
	getBlock	: function() {
		return {
			start	: this.getStartPoint(),
			end		: this.getEndPoint()
		}
	},
	
	/**
	 * @return string|null
	 */
	getLetter	: function( point ) {

		var position = this.get('position');
		var actualCoord = this.isHorizontal() ? 'x' : 'y';
		var otherCoord = actualCoord == 'x' ? 'y' : 'x';

		if ( position.get( otherCoord ) == point.get( otherCoord ) ) {
			return this.get('text')[point.get( actualCoord ) - position.get( actualCoord )]; 
		}
		
		return null;
	}
});
