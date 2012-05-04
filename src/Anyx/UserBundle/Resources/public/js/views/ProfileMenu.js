/**
 *
 */
Anyx.View.ProfileMenu = Anyx.View.extend({

    links   : [],
    
    activeLink  : null,

    events  : {
        'click li' : 'showTab'
    },

    showTab : function( event ) {
        var route = $( event.currentTarget ).attr('rel');
    },

    setActiveLink   : function( action ) {
        this.activeLink = action
    },
    
    setLinks        : function( links ) {
        this.links = links;
    },
    
    show        : function(){
        this.render({
            links       : this.links,
            activeLink  : this.activeLink
        })
    }
    
});