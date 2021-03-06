/**
 * Word tests
 */
describe("Word model", function() {

	/**
	 *
	 */
	it('Word\'s data access', function() {

		var text = 'Tstword';
		var word = new Crossword.Model.Word({
			text			: text,
			definition		: 'test word description'
		});

		expect( word.get('text') ).toEqual( text.toLowerCase() );

		expect( word.isHorizontal() ).toEqual( true );
		word.set( {'horizontal' : false} );
		expect( word.isHorizontal() ).toEqual( false );
	});

	/**
	 * 
	 */
	it('Word dimensions', function(){

		var word = new Crossword.Model.Word({
			text		: 'word',
			definition	: 'test definition'
		});

		expect( word.getLength() ).toEqual(4);

		word.set({
			position	: {
				x	: 1,
				y	: 2
			},
			horizontal : false
		});
		
		//search letters
		var point = {
			x	: 1,
			y	: 3
		};
		expect( word.getLetter( point ) ).toEqual( 'o' );
		
		point.y	= 5;
		expect( word.getLetter( point ) ).toEqual( 'd' );

		point.x	= 5;
		expect( word.getLetter( point ) ).toEqual( null );

		//end point
		var endPoint = word.getEndPoint();
		point = {
			x	: 1,
			y	: 5
		};
		
		expect( endPoint.x == point.x && endPoint.y == point.y ).toEqual(true);
		
		expect( word.getLength() ).toEqual( 4 );
	});
	
	/**
	 * 
	 */
	it( 'Word validation', function(){
		
		var word = new Crossword.Model.Word();
		expect( word.isValid() ).toEqual( false );

		var validText = 'text';
		var validDefinition = 'test word description';
		
		word.set({
			text			: validText,
			definition		: validDefinition,
			horizontal		: false
		});
		
		word.set({
			text	: 'tt'
		});
		
		expect( word.get('text') ).toEqual( validText );
		expect( word.get('definition') ).toEqual( validDefinition );
	});
});