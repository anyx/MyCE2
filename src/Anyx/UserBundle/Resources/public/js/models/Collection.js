
Anyx = Anyx || {};

Anyx.Collection = Backbone.Collection.extend({

    success     : null,
    
    error       : null,
    
    _isAll       : false,
    
	initialize  : function( models, options ) {
		this.url = options.url;
        this.success = options.success;
        this.error = options.error;
	},

    load       : function( options ) {
        var options = options || {};
        var fetchOptions = _.extend( options, {
                add     : true,
				data	: {
					skip : this.models.length
				},
				success	: this.success,
                error   : this.error
        });
        
        return this.fetch(fetchOptions);
    },
    
    extend      : function() {
        this.load();
    },

    isAll       : function() {
        return this._isAll == true;
    },
    
    setIsAll    : function( isAll ) {
        this._isAll = isAll;
    }
})