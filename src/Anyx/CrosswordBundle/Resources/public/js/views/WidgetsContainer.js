/**
 *
 */
var Anyx = Anyx || {};

/**
 *
 */
Anyx.View = Anyx.View || {};

/**
 *
 */
Anyx.View.WidgetsContainer = function ( options ) {

	/**
	 *
	 */
	var _options = options || {
			widgets	: {}
	};

	/**
	 *
	 */
	var _widgets = {};
	
	/**
	 *
	 */
	var _builders = {};
	
	/**
	 *
	 */
	this.setOptions = function( options ) {
		_options = $.extend( true, _options, options ) ;
	}
	
	/**
	 *
	 */
	this.getWidget = function( name ) {
	
		if ( !(name in _widgets) ) {
			
			if ( typeof( _options.widgets[name] ) == 'object' && 'element' in _options.widgets[name] ) {
				_widgets[name] = $( _options.widgets[name].element )
			}

			if ( name in _builders ) {
				_widgets[name] = _builders[name]( _widgets[name], _options.widgets[name].options );
			}
		}
		
		if ( typeof( _widgets[name] ) == 'undefined' ) {
			throw Error( 'Widget "' + name + '" not found' );
		}
		
		return _widgets[name];
	}

	/**
	 *
	 */
	this.setWidget = function( widgetName, widget ) {
		_widgets[widgetName] = widget;
	}
	
	/**
	 *
	 */
	this.setWidgets = function( widgets ) {
		for( var widget in widgets ) {
			this.setWidget(widget, widgets[widget]);
		}
	}

	/**
	 *
	 */
	this.hasWidget = function( name ) {
		return name in _widgets;
	}
	
	/**
	 * 
	 */
	this.getWidgets = function() {
		return _widgets;
	}

	/**
	 *
	 */
	this.clear = function() {
		_widgets = {};
		_options = {};
	}

	/**
	 *
	 */
	this.registerBuilder = function( widgetName, builder ) {
		_builders[widgetName] = builder;
	}

	/**
	 * 
	 */
	this.initAll = function() {
		for( var widget in _options.widgets ) {
			this.getWidget(widget);
		}
	}

	/**
	 *
	 */
	this.init = function( widgets ) {
		for ( var i = 0; i < widgets.length; i++ ) {
			this.getWidget( widgets[i] );
		}
	}
	
	/**
	 * 
	 */
	this.removeWidget = function( widget ) {
		if ( widget in _widgets ) {
			delete _widgets[widget];
		}
		return true;
	}
}