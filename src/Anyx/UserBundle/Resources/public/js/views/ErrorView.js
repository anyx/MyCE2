/**
 *
 */
Anyx.View.Error = Anyx.View.extend({

    _timer  : null,

    events  : {
        'click .i-close-button' : 'closeAlert'
    },

    initialize  : function() {
        $( 'body' ).append( $( this.el ) );
    },

    closeAlert  : function() {
        $( this.el ).children().modal('hide');
    },

    show    : function( message ) {
        this.render({message : message});
        $( this.el ).children().modal({
            backdrop    : false
        });
    }
});