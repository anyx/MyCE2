/**
 * 
 */
var Point = Backbone.Model.extend({
	defaults	:	{
		x	: 0,
		y	: 0
	},
	isEqual	: function( point ) {
		return this.get( 'x' ) == point.get( 'x' ) && this.get( 'y' ) == point.get( 'y' );
	}
})