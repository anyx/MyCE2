/**
 *
 */
Anyx.View.Crosswords = Anyx.View.Collection.extend({
    
    events  : {
        'click .i-remove-crossword'    : 'removeCrossword'
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

        var questionText = this.options.messages.removingConfirm + ' <em>&laquo;' + crossword.get('title') +'&raquo;</em>?';

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
                        //error
                    }
                },
                error    : function() {

                }
            });
        };
        buttons[this.options.messages.remove] =  {
                                classes     : 'red-gradient',
                                click       : removeCallback
        }
        
        buttons[this.options.messages.cancel] =  {
                                classes     : 'glossy',
                                click       : function(modal) { modal.closeModal(); }
        };        

        var dialog = $.modal(
                        {
                            title: this.options.messages.removing,
                            content: questionText,
                            buttons: buttons
                        }
        );

        return false;
        

        
        return false;
    }
});