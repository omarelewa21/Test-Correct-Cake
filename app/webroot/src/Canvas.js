/****************************************************************
 * Paint.Canvas
 * Initialise a new canvas
 *
 * @constructor
 * @param {Object} options
 ****************************************************************/

Paint.Canvas = function(options) {
	Paint.Layer.call(this, options);
	
	var _this = this,
		canvas = this.getCanvas(),
		context = this.getContext(),
		grid = new Paint.Layer(),
		events = {};
	
	
	/****************************************************************
	 * Paint.Canvas.undo
	 * Undo last draw
	 ****************************************************************/
	
	this.undo = function() {
		this.getEnabledChildren().pop().enabled = false;
		this.render();
	};
	
	
	/****************************************************************
	 * Paint.Canvas.redo
	 * Redo last draw
	 ****************************************************************/
	
	this.redo = function() {
		this.getDisabledChildren().shift().enabled = true;
		this.render();
	};
	
	
	/****************************************************************
	 * Paint.Canvas.showGrid
	 * Show grid
	 * 
	 * @param {Object} divisions
	 * @param {String} color
	 * @param {Number} width
	 ****************************************************************/

	this.showGrid = function(divisions, color, width) {

		var x, y, path,
			
			xd = canvas.width / (divisions.x + 1),
			yd = canvas.height / (divisions.y + 1),
			
			style = {
				line: {
					style: color || 'rgba(0, 0, 0, 0.25)',
					width: width || 1
				}
			};
		
		grid.enabled = true;
		grid.clear();
		
		for(x = 1; x < divisions.x + 1; x++) {
			path = new Paint.Path(style);
			path.add(new Paint.Point(xd * x, 0));
			path.add(new Paint.Point(xd * x, canvas.height));
			
			grid.add(path);
		}
		
		for(y = 1; y < divisions.y + 1; y++) {
			path = new Paint.Path(style);
			path.add(new Paint.Point(0, yd * y));
			path.add(new Paint.Point(canvas.width, yd * y));
			
			grid.add(path);
		}
		
		this.render();
	};
	
	
	/****************************************************************
	 * Paint.Canvas.hideGrid
	 * Hides grid
	 ****************************************************************/
	
	this.hideGrid = function() {
		grid.enabled = false;
	};
	
	
	/****************************************************************
	 * Paint.Canvas.on
	 * Add event handler
	 ****************************************************************/
	
	this.on = function(event, handler) {
		// Split events and handle them seperately
		if(event.indexOf(' ') >= 0) {
			event.split(' ').forEach(function(e) {
				_this.on(e, handler);
			});
			
			return;
		}
		
		// Add even if it did not exist
		if(!events[event]) {
			events[event] = [];
			
			canvas.addEventListener(event, function(e) {
				var i = events[event].length;
				while(i--) events[event][i].call(this, e);
			});
		}
		
		events[event].push(handler);
	};
	
	
	/****************************************************************
	 * Initialisation
	 ****************************************************************/
	
	this.add(grid);
	this.render();
};