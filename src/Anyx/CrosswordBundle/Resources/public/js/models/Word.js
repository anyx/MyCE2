/**
 * Word
 */
var Word = Backbone.Model.extend({
	//
	defaults	: {
		text			: null,
		definition		: null,
		horizontal		: true,
		position		: new Point({
			x : 0,
			y : 0
		})
	},

	/**
	 * Text to lower case
	 */
	set	: function( attributes, options ) {
		if ( typeof attributes == 'object' && 'text' in attributes ) {
			attributes.text = attributes.text.toLowerCase();
		}
		Backbone.Model.prototype.set.call(this, attributes, options);
	},
	
	/**
	 * @param object attributes
	 * @return String
	 */
	validate	: function( attributes ) {
		
		if ( 'text' in attributes && $.trim( attributes.text ).length == 0 ) {
			return 'Text not found';
		};

		if ( 'definition' in attributes && $.trim( attributes.definition ).length == 0 ) {
			return 'Definition not found';
		};
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
			return new Point({
						x	: position.get('x') + parseInt( this.getLength() - 1 ),
						y	: position.get('y')
				});
		} else {
			return new Point({
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
