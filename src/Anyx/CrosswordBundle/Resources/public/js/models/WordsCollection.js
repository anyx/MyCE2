/**
 *
 */
Crossword.Model = Crossword.Model || {};

/**
 * 
 */
Crossword.Model.WordsCollection = Backbone.Collection.extend({

	/**
	 *
	 */
	model	: Crossword.Model.Word,
	
	/**
	 * 
	 */
	addWord		: function( word ) {

		if ( this.canAddWord( word ) ) {
			this.add( word );
			return true;
		}
		
		return false;
	},
	
	/**
	 *
	 */
	getWords	: function() {
		return this.models;
	},
	
	/**
	 * @param word
	 */
	canAddWord	: function( word ) {
		
		var parrallelWords = this.getWordsByDirection( word.isHorizontal() );
		var perpendicularWords = this.getWordsByDirection( !word.isHorizontal() );
		
		//параллельные блоки
		var parrallelContainerBlock = word.getBlock();
			
		var perpendicularCoord = word.isHorizontal() ? 'y' : 'x';
		var directionCoord = perpendicularCoord == 'x' ? 'y' : 'x';

		parrallelContainerBlock.start[perpendicularCoord]--;
		parrallelContainerBlock.end[perpendicularCoord]++;

		var foundWords = this.getWordsInBlock( parrallelWords, parrallelContainerBlock ); 
		if ( foundWords.length > 0 ) {
			return false;
		}
		//крайние точки
		var startNeighbour = word.getStartPoint();
		startNeighbour[directionCoord]--;
		
		var endNeighbour = word.getEndPoint();
		endNeighbour[perpendicularCoord]++;
		
		var wordInPoint = false;
		_.each( this.getWords(), function( anotherWord ) {
			wordInPoint = wordInPoint || this.isWordInPoint( anotherWord, startNeighbour ) || this.isWordInPoint( anotherWord, endNeighbour );
		}, this);
		
		if ( wordInPoint ) {
			return false;
		}
		
		//окончания перпендикулярных слов в соседних блоках
		var underBlock = word.getBlock();
		underBlock.start[perpendicularCoord]--;
		underBlock.end[perpendicularCoord]--;

		var largerBlock = word.getBlock();
		largerBlock.start[perpendicularCoord]++;
		largerBlock.end[perpendicularCoord]++;

		foundWords = this.getWordsInBlock( parrallelWords, underBlock );
		foundWords = foundWords.concat( this.getWordsInBlock( parrallelWords, largerBlock ) );

		foundWords = foundWords.concat( this.getWordsByBeginningBlock( perpendicularWords, largerBlock ) );
		foundWords = foundWords.concat( this.getWordsByEndingBlock( perpendicularWords, underBlock ) );

		if ( foundWords.length > 0 ) {
			return false;
		}

		return this.checkPerpendicularIntersections( word );
	},
	
	/**
	 *
	 */
	getWordsInBlock	: function( words, block ) {
		words = words || this.getWords();

		var result = [];

		for( var i = block.start.x; i <= block.end.x; i++ ) {
			for( var j = block.start.y; j <= block.end.y; j++ ) {
				_.each( words, function( word ) {
					if ( this.isWordInPoint( word, {
						x : i, 
						y : j
					}) && result.indexOf( word ) == -1 ) {
						result[result.length] = word;
					}
				}, this);
			}
		}
		return result;
	},
	
	/**
	 *
	 */
	isWordInPoint	: function( word, point ) {
		
		var position = word.getStartPoint();
		
		var result = false;
		
		if ( word.isHorizontal() && position.x <= point.x && ( position.x + word.getLength() - 1 ) >= point.x && position.y == point.y ) {
			result = true;
		}

		if ( !word.isHorizontal() && position.y <= point.y && ( position.y + word.getLength() - 1 ) >= point.y && position.x == point.x ) {
			result = true;
		}
		
		return result;
	},
	
	/**
	 * 
	 */
	getWordsByDirection : function( isHorizontal ) {
		var words = [];
		_.each( this.models, function( word ) {
			if ( word.isHorizontal() == isHorizontal ) {
				words[words.length] = word;
			}
		}, this );
		return words;
	},

	/**
	 *
	 */
	getWordsByBeginningBlock	: function( words, block ) {
		return this.getWordsByEdgeInBlock( words, block, 1 );
	},

	/**
	 *
	 */
	getWordsByEndingBlock	: function( words, block ) {
		return this.getWordsByEdgeInBlock( words, block, -1 );
	},
	
	/**
	 *
	 */
	getWordsByEdgeInBlock : function( words, block, mode ) {

		var mode	= mode || 0;
		var isHorizontal = block.start.y == block.end.y;
		
		var foundWords = [];

		var directionCoord = isHorizontal ? 'x' : 'y';
		var perpendicularCoord = directionCoord == 'x' ? 'y' : 'x';

		_.each( words, function( word ) {

			var points = [];
			switch( mode ) {
				case 1	: points = [ word.getStartPoint() ]; break;
				case -1 : points = [ word.getEndPoint() ]; break;
				case 0	: points = [ word.getStartPoint(), word.getEndPoint() ]; break;	
			}

			_.each(points, function( position ) {
				if ( position[directionCoord] >= block.start[directionCoord] &&
					position[directionCoord] <= block.end[directionCoord] &&
					position[perpendicularCoord] == block.start[perpendicularCoord]
				) {
					foundWords[foundWords.length] = word;
				}
			});
			
		}, this );

		return foundWords;
	},
	
	/**
	 * Проверка корректного пересечения слова с перпедикулярными
	 */
	checkPerpendicularIntersections : function( word ) {
		
		var perpendicularWords = this.getWordsByDirection( !word.isHorizontal(), true );
		
		var block = word.getBlock();
		
		for( var i = 0; i < perpendicularWords.length; i++ ) {

			var intersection = this._getLinesIntersection(
				block.start,
				block.end,
				perpendicularWords[i].getStartPoint(),
				perpendicularWords[i].getEndPoint()
			);

			if ( intersection != null ) {
				var wordLetter = word.getLetter( intersection );
				
				var intersectedWords = this.getWordsInBlock( perpendicularWords, {
					start	: intersection,
					end		: intersection
				});

				if ( intersectedWords.length == 1 ) {
					var intersectLetter = intersectedWords[0].getLetter( intersection );
					
					if ( wordLetter !== intersectLetter ) {
						return false;
					}
				}
			}
		}
		return true;		
	},
	
	/**
	 * 
	 * @param a1
	 * @param a2
	 * @param b1
	 * @param b2
	 */
	_getLinesIntersection	: function( a1, a2, b1, b2 ) {

		var result = null;

		var da = ( a1.x - b1.x ) * ( b2.y - b1.y ) - ( a1.y - b1.y ) * ( b2.x - b1.x );
		var db = ( a1.x - a2.x ) * ( a1.y - b1.y ) - ( a1.y - a2.y ) * ( a1.x - b1.x );
		var d  = ( a1.x - a2.x ) * ( b2.y - b1.y ) - ( a1.y - a2.y ) * ( b2.x - b1.x );

		if ( Math.abs( d ) > 0,001 ) {
			var ta = da / d;
			var tb = db / d;

			if ( ( 0 <= ta ) && ( ta <= 1 ) && ( 0 <= tb ) && ( tb <= 1 ) ) {
				result = {
					x	: a1.x + ta * ( a2.x - a1.x ),
					y	: a1.y + ta * ( a2.y - a1.y )
				}; 
			}
		}

		return result;
	}
});