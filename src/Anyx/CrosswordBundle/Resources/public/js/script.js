/**
 *
 * '||''|.                            '||
 *  ||   ||    ....  .... ...   ....   ||    ...   ... ...  ... ..
 *  ||    || .|...||  '|.  |  .|...||  ||  .|  '|.  ||'  ||  ||' ''
 *  ||    || ||        '|.|   ||       ||  ||   ||  ||    |  ||
 * .||...|'   '|...'    '|     '|...' .||.  '|..|'  ||...'  .||.
 *                                                  ||
 * --------------- By Display:inline ------------- '''' -----------
 *
 * Landing page setup
 *
 * Structural good practices from the article from Addy Osmani 'Essential jQuery plugin patterns'
 * @url http://coding.smashingmagazine.com/2011/10/11/essential-jquery-plugin-patterns/
 */

/*
 * The semi-colon before the function invocation is a safety
 * net against concatenated scripts and/or other plugins
 * that are not closed properly.
 */
;(function($, window, document, undefined)
{
	/*
	 * undefined is used here as the undefined global variable in ECMAScript 3 and 4 is mutable (i.e. it can
	 * be changed by someone else). undefined isn't really being passed in so we can ensure that its value is
	 * truly undefined. In ES5, undefined can no longer be modified.
	 */

	/*
	 * window and document are passed through as local variables rather than as globals, because this (slightly)
	 * quickens the resolution process and can be more efficiently minified.
	 */

		// Objects cache
	var win = $(window),
		doc = $(document),
		bod = $(document.body),

		// Devices
		topLayer = $('.i-top-layer'),
		middleLayer = $('.i-middle-layer'),
		backLayer = $('.i-back-layer')

		// Initial position
		devicesPos = {
			topLayer:       parseInt(topLayer.css('left'), 10),
			middleLayer:	parseInt(middleLayer.css('left'), 10),
			backLayer:      parseInt(backLayer.css('left'), 10)
		}

		// Maximum move
		devicesMoves = {
			topLayer:       60,
			middleLayer:	50,
			backLayer:      20
		},

	// Devices parallax on mouse move
	doc.on('mousemove', function(event)
	{
			// Screen width
		var width = win.width(),

			// Position relative to screen center
			pos = (event.pageX-(width/2))/width;

		// Set positions
		topLayer.css('left', Math.round(devicesPos.topLayer+(devicesMoves.topLayer*pos))+'px');
		middleLayer.css('left', Math.round(devicesPos.middleLayer+(devicesMoves.middleLayer*pos))+'px');
		backLayer.css('left', Math.round(devicesPos.backLayer+(devicesMoves.backLayer*pos))+'px');
	});

	// Smooth scrolling
	doc.on('click', 'a', function(event)
	{
		var link = $(this).attr('href'),
			target;

		// Only for hashtags
		if (link.indexOf('#') < 0)
		{
			return;
		}

		// Target
		target = $('#'+link.split('#')[1]);
		if (target.length === 0)
		{
			return;
		}

		// Stop normal scroll
		event.preventDefault();

		// Scroll
		$('html, body').animate({
			scrollTop: target.offset().top
		});
	});

})(this.jQuery, window, document);