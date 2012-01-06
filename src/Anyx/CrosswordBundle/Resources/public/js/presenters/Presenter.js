
/**
 *
 */
Backbone.Presenter = function( options ) {
	this._configure( options );
	this.initialize();
};

_.extend(Backbone.Presenter.prototype, Backbone.Events, {

	/**
	 *
	 */
	initialize		: function( options ) {},

	/**
	 *
	 */
	_configure		: function( options ) {
		this.options = options || {};
	},

	/**
	 *
	 */
	registerWidget	: function( widgetName, widget ) {
		
		if ( _.isFunction( widget ) ) {//register builder
			return this.getContainer().registerBuilder(widgetName, widget );
		}
		
		if ( _.isString( widget ) ) {
			return this.getContainer().setWidget(widgetName, new Backbone.View({
				el	: widget
			}));
		}
		
		if ( _.isObject( widget ) /*&& widget instanceof Backbone.View*/ ) {
			return this.setWidget(widgetName, widget );
		}
		
		throw new Error( 'Can\'t register widget "' + widgetName + "'" );
	},
	
	/**
	 *
	 */
	registerWidgets	: function( widgets ) {
		for( var widget in widgets ) {
			this.registerWidget(widget, widgets[widget]);
		}
	},

	/**
	 *
	 */
	setWidget		: function( widgetName, widget ) {
		return this.getContainer().setWidget(widgetName, widget );
	},

	/**
	 *
	 */
	getWidget		: function( widgetName ) {
		return this.getContainer().getWidget( widgetName );
	}
});


/**
 * Add extend method
 */
Backbone.Presenter.extend = Backbone.Model.extend;

/**
 *
 */
Backbone.Presenter.prototype.setContainer = function( container ) {
	Backbone.Presenter._container = container;
}

/**
 * 
 */
Backbone.Presenter.prototype.getContainer = function() {
	Backbone.Presenter._container = Backbone.Presenter._container || new Backbone.View.Container;
	return Backbone.Presenter._container;
}
