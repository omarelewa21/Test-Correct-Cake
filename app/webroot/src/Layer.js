/****************************************************************
 * Paint.Layer
 * Create a new layer
 *
 * @constructor
 * @param {Object} options
 ****************************************************************/

Paint.Layer = function(options) {
	if(!options) options = {};
	
	var canvas = document.createElement('canvas'),
		context = canvas.getContext('2d'),
		children = [];
	
	this.enabled = true;
	
	
	/****************************************************************
	 * Paint.Layer.add
	 * Add child to layer
	 *
	 * @param {Object} child
	 ****************************************************************/
	
	this.add = function(child) {
		children.push(child);
	};
	
	
	/****************************************************************
	 * Paint.Layer.remove
	 * Remove child from layer
	 *
	 * @param {Object} child
	 ****************************************************************/
	
	this.remove = function(child) {
		var idx;
		
		if((idx = children.indexOf(child)) > -1) {
			children.splice(idx, 1);
		}
	};
	
	
	/****************************************************************
	 * Paint.Layer.clear
	 * Clear layer
	 ****************************************************************/
	
	this.clear = function() {
		children.length = 0;
	};
	
	
	/****************************************************************
	 * Paint.Layer.render
	 * Renders children
	 ****************************************************************/
	
	this.render = function(pcanvas, pcontext) {
		if(!this.enabled) return;
		
		var i;
		
		// Reset canvas size
		canvas.height = pcanvas ? pcanvas.height : options.height;
		canvas.width = pcanvas ? pcanvas.width : options.width;
		
		// Clear canvas
		context.clearRect(0, 0, canvas.width, canvas.height);
		
		// Draw children
		for(i = 0; i < children.length; i++) {
			children[i].render(canvas, context);
		}
		
		// Draw to parent
		if(pcontext) {
			pcontext.drawImage(canvas, 0, 0);
		}
	};
	
	
	/****************************************************************
	 * Paint.Layer.getEnabledChildren
	 * Returns a list of layers that are enabled
	 ****************************************************************/
	
	this.getEnabledChildren = function() {
		return children.filter(function(l) { return l.enabled; });
	};
	
	
	/****************************************************************
	 * Paint.Layer.getDisabledChildren
	 * Returns a list of layers that are disabled
	 ****************************************************************/
	
	this.getDisabledChildren = function() {
		return children.filter(function(l) { return !l.enabled; });
	};
	
	
	/****************************************************************
	 * Paint.Layer.getCanvas
	 * Returns canvas element
	 ****************************************************************/
	
	this.getCanvas = function() {
		return canvas;
	};
	
	
	/****************************************************************
	 * Paint.Layer.getContext
	 * Returns context
	 ****************************************************************/
	
	this.getContext = function() {
		return context;
	};
	
	
	/****************************************************************
	 * Paint.Layer.getChildren
	 * Returns context
	 ****************************************************************/
	
	this.getChildren = function() {
		return children;
	};
};