Backbone.actAs = Backbone.actAs || {};
Backbone.actAs.Locatable = (function(){

	var actAsLocatable_Locator = ( function(){

		var resources = {};

		return {

			addResource: function( id, resource ){
				id = String(id);
				if( typeof this.locateByID(id) != 'undefined' ) throw new Error('Trying to register not locatable resource as locatable');
				resources[id] = resource;
			},

			removeResource: function( id ){
				resources[id] = undefined;
			},

			locateByID: function( id ){
				return resources[id];
			},

			getResources: function(){
				return resources;
			}

		};

	} )();

	return {

		getLocatableID: function(){
			if( !this._locatableID ){
				this._locatableID = _.uniqueId('locatable');
				this.getLocator().addResource( this._locatableID, this );
			};
			return this._locatableID;
		},

		getLocator: function(){
			return actAsLocatable_Locator;
		},

		locateByID: function(id){
			return this.getLocator().locateByID( id );
		}

	}

})();

Backbone.actAs.LocatableCollection = _.extend({

	loadByLocatableID: function(elements){
		_(elements).each(function(elem){
			if( this.locateByID(elem) ){
				this.add( this.locateByID(elem) );
			};
		}, this);
		return this;
	}

},Backbone.actAs.Locatable);