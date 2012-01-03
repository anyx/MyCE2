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
	
		var horizontal = true;
		
		if ( 'horizontal' in options ) {
			horizontal = options.horizontal;
		}
		
		this.setDirection( horizontal );
	},
	
	/**
	 *
	 */
	changeDirection : function( event ) {
		
		var isHorizontal = this.$( event.currentTarget ).attr('rel') == 'horizontal';
		
		if ( isHorizontal != this.getDirection() ) {
			this.setDirection( !this.getDirection() );
		}
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
			
		this.$( this.el ).trigger( 'changeDirection', {horizontal : isHorizontal} );
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