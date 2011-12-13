/**
 * Word tests
 */
describe("Word model", function() {

	/**
	 *
	 */
	it('Word\'s data access', function() {

		var text = 'Tstword';
		var word = new Word({
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

		var word = new Word({
			text		: 'word',
			definition	: 'test definition'
		});

		expect( word.getLength() ).toEqual(4);

		word.set({
			position	: new Point({
				x	: 1,
				y	: 2
			}),
			horizontal : false
		})
		//search letters
		var point = new Point({
			x	: 1,
			y	: 3
		});
		expect( word.getLetter( point ) ).toEqual( 'o' );

		point.set({
			y	: 5
		});
		expect( word.getLetter( point ) ).toEqual( 'd' );

		point.set({
			x	: 5
		});

		expect( word.getLetter( point ) ).toEqual( null );
		//end point
		var endPoint = word.getEndPoint();
		point.set({
			x	: 1,
			y	: 5
		});
		
		expect( endPoint.isEqual( point ) ).toEqual(true);
		
		expect( word.getLength() ).toEqual( 4 );
	})
});