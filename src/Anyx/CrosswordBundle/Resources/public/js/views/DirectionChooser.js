/**
 * 
 */
Constructor.View.DirectionChooser = Anyx.View.extend({

	/**
	 *
	 */
	events: {
		'click button'	: 'changeDirection'
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
		
		var buttons = this.$('button');
		
		buttons
			.removeClass( 'active' )
			.filter('button[rel=' + (isHorizontal ? 'horizontal' : 'vertical') + ']')
			.addClass('active');
		
		this.trigger( 'changeDirection', {horizontal : isHorizontal} );
	},
	
	/**
	 * 
	 */
	getDirection	: function() {
		var links = this.$('button');
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