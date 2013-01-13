/**
 *
 */
Constructor.View.WordForm = Backbone.Presenter.extend({
	
	/**
	 * 
	 */
	initialize	: function( options ) {
		//widgets
		this.registerWidgets({
			wordInput			: this.options.selectors.word,
			definitionInput		: this.options.selectors.definition,
			directionChooser	: new Constructor.View.DirectionChooser({
				el : this.options.selectors.directionChooser
			}),
			
			wordPreview			: new Constructor.View.WordPreview({
				el	: this.options.selectors.wordPreview
			}),
			
			statusBar		: new Constructor.View.StatusBar({
				el			: this.options.selectors.statusBar,
				hintElement	: this.options.selectors.statusText,
				template	: 't-status-bar',
                defaultText : this.options.messages.defaultStatus,
                translations: this.options.messages
			})
		});
		
        var _this = this;
		//events
		$( this.getWidget('wordInput').el )
			.add( $( this.getWidget('definitionInput').el ) )
			.on({
					change	: function( event ) {
						event.data.view.buildWord();
					},
					keyup	: function( event ) {
                        var charCode = event.which || event.keyCode;
						event.data.view.buildWord();
					}
				},
				{
					view : this
				}
			);
				
		this.getWidget('directionChooser')
			.bind('changeDirection', function( event, data ) {
					this.buildWord();
			}, this);
	},
	
	/**
	 * 
	 */
	buildWord	: function() {
		
		var word = new Crossword.Model.Word({
			text			: $( this.getWidget( 'wordInput' ).el ).val(),
			definition		: $( this.getWidget( 'definitionInput' ).el ).val(),
			horizontal		: this.getWidget( 'directionChooser' ).getDirection()
		});
		
        word.bind('error', function( model, errors ) {
            
            var messages = [];
            _.each(errors, function( elementErrors, element ) {
                var error = elementErrors[0];
                messages[messages.length] = element + '_' + error;
            });
            
            this.getWidget('statusBar').showError( messages );
        }, this);

        if ( word.isValid() ) {
			this.getWidget( 'wordPreview' ).showWord( word );
			this.trigger( 'create', this.getWidget( 'wordPreview' ).getCurrentView() );
			this.getWidget( 'statusBar').showMessage( 'word_valid' );
		}
	},
	
	/**
	 * 
	 */
	bindWord	: function( wordView ) {
		this.clear();
		this._setValues( wordView.model.attributes );
		this.getWidget( 'wordPreview' ).showWord( wordView.model );
	},
	
	/**
	 *
	 */
	_setValues	: function( values ) {
		$( this.getWidget( 'wordInput' ).el ).val( values.text );
		$( this.getWidget( 'definitionInput' ).el ).val( values.definition );
		this.getWidget( 'directionChooser' ).setDirection( values.horizontal );
	},
	
    _checkWord  : function() {
        
    },
    
    /**
	 *
	 */
	isAllowCharCode	: function(code) {

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
	clear		: function() {
		this._setValues({
			text		:	'',
			definition	:	'',
			horizontal	: true,
			id			: null
		});
        this.buildWord();
	}
});

