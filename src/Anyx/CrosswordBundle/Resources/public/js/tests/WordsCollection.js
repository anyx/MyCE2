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
			position	: {
				x	: 1,
				y	: 1
			}
		});
		
		expect( this.collection.addWord( word ) ).toEqual(true);
		expect( this.collection.length ).toEqual( 1 );

		expect( this.collection.getWordsByDirection( true ).length ).toEqual( 1 );
		expect( this.collection.getWordsByDirection( false ).length ).toEqual( 0 );
		//add vertical word
		this.collection.addWord(new Crossword.Model.Word({
			text		: 'word1',
			horizontal	: false,
			position	: {
				x	: 10,
				y	: 10
			}
		}));
		expect( this.collection.getWordsByDirection( true ).length ).toEqual( 1 );
		expect( this.collection.getWordsByDirection( false ).length ).toEqual( 1 );
	});
	
	/**
	 *
	 */
	it ( 'Words selections', function(){
		
		var word1 = new Crossword.Model.Word({
			text		: 'text',
			horizontal	: true,
			position	: {
				x	: 2,
				y	: 2
			}
		});
		expect( this.collection.addWord( word1 ) ).toEqual(true);

		expect( this.collection.isWordInPoint( word1, {x : 1, y : 2} ) ).toEqual(false);
		expect( this.collection.isWordInPoint( word1, {x : 2, y : 2} ) ).toEqual(true);
		expect( this.collection.isWordInPoint( word1, {x : 3, y : 2} ) ).toEqual(true);
		expect( this.collection.isWordInPoint( word1, {x : 4, y : 2} ) ).toEqual(true);
		expect( this.collection.isWordInPoint( word1, {x : 5, y : 2} ) ).toEqual(true);
		expect( this.collection.isWordInPoint( word1, {x : 6, y : 2} ) ).toEqual(false);

		var word2 = new Crossword.Model.Word({
			text		: 'text2',
			horizontal	: true,
			position	: {
				x	: 10,
				y	: 10
			}
		});
		expect( this.collection.addWord( word2 ) ).toEqual(true);

		var block = {
				start	: {
					x	: 1,
					y	: 1
				},
				end		: {
					x	: 2,
					y	: 10
				}
		};

		var selection = this.collection.getWordsInBlock(
								this.collection.getWords(),
								block
		);
		expect( selection.length ).toEqual( 1 );
		
		block.start.x = 5;
		block.start.y = 2;
		selection = this.collection.getWordsInBlock(
								this.collection.getWords(),
								block
		);
		expect( selection.length ).toEqual( 0 );
		
		block = {
			start	: {
				x	: 5,
				y	: 2
			},
			end		: {
				x	: 5,
				y	: 6
			}
		}
		selection = this.collection.getWordsByEndingBlock( this.collection.getWords(), block );
		expect( selection.length ).toEqual( 1, 'Word not found by ending block' );
	});
	
	/**
	 * 
	 */
	it( 'Adding inresection words', function(){

		var word1 = new Crossword.Model.Word({
			text		: 'world',
			horizontal	: true,
			position	: {
				x	: 2,
				y	: 6
			}
		});
		expect( this.collection.addWord( word1 ) ).toEqual(true);
		//некорректная позиция
		var word2 = new Crossword.Model.Word({
			text		: 'hello',
			horizontal	: false,
			position	: {
				x	: 5,
				y	: 6
			}
		});
		expect( this.collection.addWord( word2 ) ).toEqual(false);
		//корректная позиция
		word2.set({
			position : {
				x	: 5,
				y	: 3
			}
		});
		expect( this.collection.addWord( word2 ) ).toEqual(true);
		
		//соседний блок
		var word3 = new Crossword.Model.Word({
			text		: 'newb',
			horizontal	: false,
			position	: {
				x	: 7,
				y	: 6
			}
		});
		expect( this.collection.addWord( word3 ) ).toEqual( false, 'neighbour' );

		var word4 = new Crossword.Model.Word({
			text		: 'bottom',
			horizontal	: false,
			position	: {
				x	: 3,
				y	: 7
			}
		});
		expect( this.collection.addWord( word4 ) ).toEqual( false, 'edge point' );

		var word5 = new Crossword.Model.Word({
			text		: 'bottomb',
			horizontal	: true,
			position	: {
				x	: 3,
				y	: 8
			}
		});
		expect( this.collection.addWord( word5 ) ).toEqual( false, 'edge point 2' );

		var word6 = new Crossword.Model.Word({
			text		: 'bottomh',
			horizontal	: true,
			position	: {
				x	: 3,
				y	: 2
			}
		});
		expect( this.collection.addWord( word6 ) ).toEqual( false, 'edge point 3' );

		var word7 = new Crossword.Model.Word({
			text		: 'angle',
			horizontal	: true,
			position	: {
				x	: 6,
				y	: 2
			}
		});
		expect( this.collection.addWord( word7 ) ).toEqual( true, 'angle point' );
	});
});