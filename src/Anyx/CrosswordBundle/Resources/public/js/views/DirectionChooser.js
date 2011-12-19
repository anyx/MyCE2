/**
 * 
 */
Crossword.View.DirectionChooser = Crossword.View.extend({

	/**
	 *
	 */
	events: {
		'click a'	: 'changeDirection'
	},

	/**
	 * Init default direction
	 */
	initialize	: function( options ) {
	
		var horizontal = false;
		
		if ( 'horizontal' in options ) {
			horizontal = options.horizontal;
		}
		
		this.setDirection( horizontal );
	},
	
	/**
	 *
	 */
	changeDirection : function() {
		this.setDirection( !this.getDirection() );
		return false;
	},
	
	/**
	 *
	 */
	setDirection	: function( isHorizontal ) {
		var links = this.$('a');
		links
			.removeClass( 'active' )
			.filter('a[rel=' + (isHorizontal ? 'horizontal' : 'vertical') + ']')
			.addClass('active');
	},
	
	/**
	 * 
	 */
	getDirection	: function() {
		var links = this.$('a');
		var direction = null;
		
		links.each(function(){
			var link = $(this);
			if( link.hasClass('active') ) {
				direction = link.attr('rel') == 'horizontal';
			}
		});
		
		if ( direction === null ) {
			throw new Error( 'Direction is not set' );
		}
		return direction;
	}
});