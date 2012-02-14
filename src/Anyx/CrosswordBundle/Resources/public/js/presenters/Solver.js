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

	words   : [],

	inputs	: {},

	solution   : {},

	lastDirection : null,

	/**
	 *
	 * @param lastDirection
	 */
	saveLastDirection : function( lastDirection ) {
		this.lastDirection = lastDirection;
	},

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
		/**
		 * tmp
		 */
		var _this = this;

		$( this.options.selectors.saveButton ).click(function(){
			$.ajax(
				_this.options.savePath,
				{
					type        : 'POST',
					dataType    : 'json',
					data        : {
						solution    : _this.getSolution()
					},
					success     : function( data ) {
						console.log('success', data);

					},
					error      : function() {
						console.log('error', arguments);

					}
				}
			)
			console.log( 'w', _this.getSolution() );
		});
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

		this.words[this.words.length] = word;
		this.solution[word.id] = '';
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
		var previousDirection = this.lastDirection;

		if ( previousDirection != null && previousDirection != typeDirection ) {

			var nextPos = _.clone( nextPosition );
			nextPos[previousDirection]++;
			var nextInput = this.getInputByPosition( nextPos );
			if ( !_.isEmpty( nextInput ) ) {
				nextInput.focus();
				return true;
			};
		}

		nextPosition[typeDirection]++;

		var nextInput = this.getInputByPosition( nextPosition );
		if ( !_.isEmpty( nextInput ) ) {
			nextInput.focus();
		}

		this.saveLastDirection( typeDirection );
	},

	/**
	 *
	 */
	getInputByPosition	: function( position ) {
		if ( position.x in this.inputs ) {
			return this.inputs[position.x][position.y];
		}
	},

	/**
	 *
	 */
	getSolution    : function() {
		_.each( this.words, function( word ) {
			var directionCoordinate = word.horizontal ? 'x' : 'y';
			var point = _.clone( word.position );
			this.solution[word.id] = '';
			for ( var i = 0; i < word.length; i++ ) {
				this.solution[word.id] += $( this.getInputByPosition(point) ).val();
				point[directionCoordinate]++;
			}
		}, this);
		return this.solution;
	}
});