/**
 * 
 */
Crossword.Presenter = Crossword.Presenter || {};

Crossword.Presenter.Solver = Backbone.Presenter.extend({

	specialKeys : {
			8 	: 'backspace',
			37	: 'left',
			38	: 'up',
			39	: 'right',
			40	: 'down',
			35	: 'end',
			36	: 'home',
			46	: 'del'
	},
	
	inputs	: {},
	
	/**
	 *
	 */
	getLocator	: function() {
		return Backbone.actAs.Locatable.getLocator();
	},
	
	/**
	 *
	 */
	initialize	: function(){
		if ( _.isEmpty( this.options.words ) ) {
			throw new Error('Words is missing');
		}
		
		_.each( this.options.words, function( word ) {
			this.addWord( word );
		}, this);
		
		this.initKeysEvents();
	},
	
	/**
	 * 
	 */
	getHorizontalPanel	: function() {
		if ( _.isEmpty( this.horizontalPanel ) ) {
			this.horizontalPanel = $( this.options.selectors.horizontalPanel );
		}
		return this.horizontalPanel;
	},

	/**
	 * 
	 */
	getVerticalPanel	: function() {
		if ( _.isEmpty( this.verticalPanel ) ) {
			this.verticalPanel = $( this.options.selectors.verticalPanel );
		}
		return this.verticalPanel;
	},
	
	/**
	 * 
	 */
	addWord		: function( word ) {
		var solverWord = new Crossword.View.SolverWord({
			model		: word,
			cellSize	: this.options.cellSize,
			template	: this.options.templates.word
		});

		$( this.options.el ).append( solverWord.el );
		//add word view in registry
		this.getLocator().addResource( solverWord.cid, solverWord );

		this.test = solverWord.el;
		solverWord.el.data( 'view-cid', solverWord.cid );
		this.mapInputs( solverWord.getInputs() );
		
		//definition
		var wordDefintion = new Crossword.View.WordDefinition({
			definition	: word.definition,
			number		: word.number,
			wordCid		: solverWord.cid,
			template	: this.options.templates.definition
		});
		
		if ( word.horizontal ) {
			this.getHorizontalPanel().append( wordDefintion.el );
		} else {
			this.getVerticalPanel().append( wordDefintion.el );
		}
	},
	
	/**
	 *
	 */
	mapInputs	: function( inputs ) {
		_.each( inputs, function( input ) {
			var position = $( input ).data('position');
			if ( _.isEmpty( this.inputs[position.x] ) ) {
				this.inputs[position.x] = {};
			}
			this.inputs[position.x][position.y] = input;
		}, this);
	},
	
	/**
	 * 
	 */
	initKeysEvents	: function() {
		
		var _this = this;
		
		this.options.el.find('INPUT').on(
			'keypress',
			function( event ) {
				
				var charCode = event.charCode;
				var keyCode = event.keyCode;
				
				if ( _this._moveCursor( keyCode, $( this ) ) ) {
					return false;
				}

				if ( _this._setLetter( charCode, $( this ) ) ) {
					return false;
				}

				return false;
			}
		);
	},
	/**
	 *
	 */
	getWordByPosition	: function ( point ) {
		return this.options.el.find( 'INPUT[data-position-x=' + point.x + ']' )
			.filter('[data-position-y=' + point.y + ']')
			.get(0);
	},
	/**
	 *
	 */
	isAllowCharCode	: function( code ) {
		
		if ( code >= 1040 /*А*/ && code <= 1103 /*я*/  ) {
			return true;
		}

		if ( code == 1025 || code == 1105 /*ё*/  ) {
			return true;
		}

		if ( code >= 65 /*A*/ && code <= 90 /*Z*/  ) {
			return true;
		}

		if ( code >= 97 /*a*/ && code <= 122 /*z*/  ) {
			return true;
		}
	
		return false;
	},
	
	/**
	 * 
	 */
	_moveCursor	: function( keyCode, activeInput ) {
		var result = false;
		
		var position = _.clone( activeInput.data('position') );

		if ( keyCode in this.specialKeys ) {
			switch( this.specialKeys[keyCode] ) {
				case 'backspace' :
					activeInput.val( '' );
					position.x -= 1;
					break;
				case 'left'	:
						position.x -= 1;
					break;
				case 'right':
						position.x += 1;
					break;
				case 'down'	:
						position.y += 1;
					break;		
				case 'up'	:
						position.y -= 1;
					break;		
				case 'end'	:break;
				case 'home' :break;
				case 'del'	:
					activeInput.val( '' );
					break;
			}

			var nextInput = this.getInputByPosition( position );
			if ( !_.isEmpty( nextInput ) ) {
				nextInput.focus();
			}
			
			result = true;
		}
		
		return result;
	},
	
	_setLetter	: function( charCode, activeInput ) {
		
		var letter = String.fromCharCode( charCode );

		var currentViewCid = activeInput.closest('DIV').eq(0).data( 'view-cid' );
		var currentView = this.getLocator().locateByID( currentViewCid );

		if ( !this.isAllowCharCode( charCode ) ) {
			return false;
		}
		
		activeInput.val( letter );
		
		var nextPosition = _.clone( activeInput.data('position') );
		var typeDirection = currentView.model.horizontal ? 'x' : 'y';
		nextPosition[typeDirection]++;
		
		var nextInput = this.getInputByPosition( nextPosition );
		if ( !_.isEmpty( nextInput ) ) {
			nextInput.focus();
		}
	},
	
	/**
	 * 
	 */
	getInputByPosition	: function( position ) {
		if ( position.x in this.inputs ) {
			return this.inputs[position.x][position.y];
		}
	}
});