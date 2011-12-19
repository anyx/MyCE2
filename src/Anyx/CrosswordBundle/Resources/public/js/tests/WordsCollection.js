/**
 * Crossword tests
 */

/**
 * Word tests
 */
describe('Words collection model', function() {
	
	/**
	 *
	 */
	beforeEach(function() {
		this.collection = new Crossword.Model.WordsCollection();
	});
	
	/**
	 * 
	 */
	it( 'Adding words', function() {
		
		var word = new Crossword.Model.Word({
			text		: 'word',
			horizontal	: true,
			position	: new Crossword.Model.Point({
				x	: 1,
				y	: 1
			})
		});
		
		expect( this.collection.addWord( word ) ).toEqual(true);
		expect( this.collection.length ).toEqual( 1 );

		expect( this.collection.getWordsByDirection( true ).length ).toEqual( 1 );
		expect( this.collection.getWordsByDirection( false ).length ).toEqual( 0 );
		//add vertical word
		this.collection.addWord(new Crossword.Model.Word({
			text		: 'word1',
			horizontal	: false,
			position	: new Crossword.Model.Point({
				x	: 10,
				y	: 10
			})
		}));
		expect( this.collection.getWordsByDirection( true ).length ).toEqual( 1 );
		expect( this.collection.getWordsByDirection( false ).length ).toEqual( 1 );
	});
	
	/**
	 * 
	 */
	it( 'Adding inresection words', function(){

		var word1 = new Crossword.Model.Word({
			text		: 'world',
			horizontal	: true,
			position	: new Crossword.Model.Point({
				x	: 2,
				y	: 6
			})
		});
		expect( this.collection.addWord( word1 ) ).toEqual(true);
		//некорректная позиция
		var word2 = new Crossword.Model.Word({
			text		: 'hello',
			horizontal	: false,
			position	: new Crossword.Model.Point({
				x	: 5,
				y	: 6
			})
		});
		expect( this.collection.addWord( word2 ) ).toEqual(false);
		//корректная позиция
		word2.set({
			position : new Crossword.Model.Point({
				x	: 5,
				y	: 3
			})
		});
		expect( this.collection.addWord( word2 ) ).toEqual(true);
	});
});