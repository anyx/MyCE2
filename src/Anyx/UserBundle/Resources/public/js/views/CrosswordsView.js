/**
 *
 */
Anyx.View.Crosswords = Anyx.View.Collection.extend({
    
    events  : {
        'click .i-remove-crossword' : 'removeCrossword',
        'click .i-more-link'        : 'extendCollection'
    },
    
    /**
     * 
     */
    removeCrossword : function( event ) {

        var _this = this;
        var router = this.options.router;
        var deleteRoute = this.options.deleteRoute;

        var crosswordId = $(event.currentTarget).data('id');
        var crossword = this.model.get( crosswordId );

        var questionText = Anyx.Utils.Translator.translate('removingConfirm') + ' <em>&laquo;' + crossword.get('title') +'&raquo;</em>?';

        var buttons = {};
        
        var removeCallback = function(modal) {
            $.ajax({
                url      : router.generate( deleteRoute, {id : crosswordId} ),
                type     : 'delete',
                dataType : 'json',
                success  : function( data, textStatus, jqXHR ) {
                    if ( 'success' in data && data.success == true ) {
                        _this.model.fetch();
                        modal.closeModal();
                    } else {
                       $.modal.alert();
                    }
                },
                error    : function() {
                    modal.closeModal();
                }
            });
        };
        buttons[Anyx.Utils.Translator.translate('remove')] =  {
                                classes     : 'red-gradient',
                                click       : removeCallback
        }
        
        buttons[Anyx.Utils.Translator.translate('cancel')] =  {
                                classes     : 'glossy',
                                click       : function(modal) {modal.closeModal();}
        };        

       $.modal(
            {
                title: Anyx.Utils.Translator.translate('removing'),
                content: questionText,
                buttons: buttons
            }
        );
        return false;
    }
});