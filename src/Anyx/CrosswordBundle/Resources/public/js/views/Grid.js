/**
 *
 */
Constructor.View.Grid = Anyx.View.extend({

	/**
	 *
	 */
	initialize	: function() {
		this.render();
	},
	
	/**
	 * 
	 */
	render		: function() {
		
		var table = this.$('<table />');
		
		for ( var i = 0; i < this.options.rows; i++) {
			var tr = this.$('<tr />');
			for ( var j = 0; j < this.options.cols; j++) {
				tr.append( '<td><div></div></td>' );
			}
			tr.appendTo( table );
		}
		
		table.appendTo( this.el );
		
		return this;
	},
	
	/**
	 * 
	 */
	getCellSize		: function() {
		return this.$( this.el ).find('tr:eq(0)').height();
	},
	
	/**
	 * 
	 */
	getWords		: function() {
		return this.options.words;
	},
	
	/**
	 * 
	 */
	getCell		: function( x, y ) {
		
        var row = this.$( this.el ).find( 'tr:eq(' + y +')' );
        if ( row.length > 0 ) {
            return row.find( 'td:eq(' + x + ') div' );
        }
        return false;
	}
});

