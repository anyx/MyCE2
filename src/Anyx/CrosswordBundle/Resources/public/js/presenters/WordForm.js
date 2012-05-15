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
		
		//events
		$( this.getWidget('wordInput').el )
			.add( $( this.getWidget('definitionInput').el ) )
			.on({
					change	: function( event ) {
						event.data.view.buildWord();
					},
					keyup	: function( event ) {
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
                _.each( elementErrors, function(errorMessage){
                    messages[messages.length] = element + '_' + errorMessage;
                });
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

