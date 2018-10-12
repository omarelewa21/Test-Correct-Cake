/****************************************************************
 * Paint.Image
 * An Image object
 *
 * @constructor
 * @param {Object} image
 * @param {Object} options
 ****************************************************************/

Paint.Image = function(image, options) {
	if(!options) options = {};
	
	this.position = options.position || new Paint.Point;
	this.size = options.size || new Paint.Point(image.width || 0, image.height || 0);
	
	
	/****************************************************************
	 * Paint.Image.render
	 * Render the image
	 *
	 * @param {Object} canvas
	 * @param {Object} context
	 ****************************************************************/
	
	this.render = function(canvas, context) {
		context.drawImage(
			image,
			this.position.x,	this.position.y,
			this.size.x,		this.size.y);
	};
};