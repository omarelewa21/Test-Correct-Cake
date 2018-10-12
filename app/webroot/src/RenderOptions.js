/****************************************************************
 * Paint.RenderOptions
 * An Path object
 *
 * @constructor
 * @param {Object} options
 ****************************************************************/

Paint.RenderOptions = function(options) {
	options = options || {};
	options.line = options.line || {};
	
	/****************************************************************
	 * Paint.RenderOptions.render
	 * Render the path
	 *
	 * @constructor
	 * @param {Object} canvas
	 * @param {Object} context
	 ****************************************************************/
	
	this.render = function(canvas, context) {
		context.fillStyle		= options.fill				|| '#000';
		context.lineCap			= options.line.cap			|| 'butt';
		context.lineDashOffset	= options.line.dashOffset	|| 0;
		context.lineJoin		= options.line.join			|| 'miter';
		context.strokeStyle		= options.line.style		|| '#000';
		context.lineWidth		= options.line.width		|| 0;
		
		// Fill
		if(
			options.fill &&
			options.fill !== 'transparent'
		) context.fill();
		
		// Stroke
		if(
			options.line.style &&
			options.line.style !== 'transparent' &&
			options.line.width > 0
		) {
			context.setLineDash(options.line.dash || []);
			context.stroke();
		}
	};
};