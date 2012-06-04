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
        
        var crosswordId = $(event.currentTarget).data('id');
        var crossword = this.model.get( crosswordId );
        
        var modalElement = $( this.options.selectors.modalWindow, this.el);

        modalElement
            .find( this.options.selectors.crosswordName )
                .text( crossword.get('title') );
        
        modalElement
            .find( this.options.selectors.closeButton )
                .click(function() {
                    modalElement.modal('hide');
                    return false;
                });

        var _this = this;
        var router = this.options.router;
        var deleteRoute = this.options.deleteRoute;
        
        modalElement
            .find( this.options.selectors.removeButton )
                .click(function() {
                    $.ajax({
                       url      : router.generate( deleteRoute, {id : crosswordId} ),
                       type     : 'delete',
                       dataType : 'json',
                       success  : function( data, textStatus, jqXHR ) {
                           if ( 'success' in data && data.success == true ) {
                              _this.model.fetch();
                              modalElement.modal('hide');
                           } else {
                               //error
                           }
                       },
                       error    : function() {
                           
                       }
                    });
                    return false;
                });
      
        modalElement.modal();
        
        return false;
    }
});