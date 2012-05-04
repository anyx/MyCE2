/**
 *
 */
Anyx.View.Collection = Anyx.View.extend({

    events      : {
        'click .i-more-link'    : 'extendCollection'
    },
    
    extendCollection : function() {
        this.model.extend();
        return false;
    },
});