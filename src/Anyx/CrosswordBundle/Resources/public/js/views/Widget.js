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
Anyx.View.Widget = function() {
	
	/**
	 *
	 */
	this._container = null;
}

/**
 * 
 */
Anyx.View.Widget.prototype.setContainer = function( container ) {
	Anyx.ViewWidget._container = container;
}

/**
 * 
 */
Anyx.View.Widget.prototype.getContainer = function() {
	Anyx.View.Widget._container = Anyx.View.Widget._container || new Anyx.View.WidgetsContainer;
	return Anyx.View.Widget._container;
}