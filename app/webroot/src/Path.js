/****************************************************************
 * Paint.Path
 * An Path object
 *
 * @constructor
 * @param {Object} points
 * @param {Object} options
 ****************************************************************/

Paint.Path = function(options) {
	var points = [],
		renderOptions = new Paint.RenderOptions(options);
	
	if(!options) options = {};
	this.position = options.position || new Paint.Point;
	this.rotation = 0;
	
	
	/****************************************************************
	 * Paint.Image.add
	 * Adds a point to the path
	 ****************************************************************/
	
	this.add = function(point) {
		points.push(point);
	};
	
	
	/****************************************************************
	 * Paint.Image.remove
	 * Removes a point from the path
	 ****************************************************************/
	
	this.remove = function(point) {
		this.removeAt(points.indexOf(point));
	};
	
	
	/****************************************************************
	 * Paint.Image.removeLast
	 * Removes last point from the path
	 ****************************************************************/
	
	this.removeLast = function() {
		points.unshift();
	};
	
	
	/****************************************************************
	 * Paint.Image.removeAt
	 * Removes specific point from the path
	 ****************************************************************/
	
	this.removeAt = function(n) {
		points.splice(n, 1);
	};
	
	
	
	/****************************************************************
	 * Paint.Image.render
	 * Render the image
	 *
	 * @param {Object} canvas
	 * @param {Object} context
	 ****************************************************************/
	
	this.render = function(canvas, context) {
		var i;
		
		context.beginPath();
		
		context.translate(this.position.x, this.position.y);
		context.rotate(this.rotation);
		
		for(i = 0; i < points.length; i++) {
			if(i === 0) context.moveTo(
				points[i].x,
				points[i].y
			);
			
			if(points[i] instanceof Paint.Point) {
				context.lineTo(
					points[i].x,
					points[i].y
				);
			} else if(points[i] instanceof Paint.BezierCurve) {
				context.bezierCurveTo(
					points[i].cp1.x,
					points[i].cp1.y,
					
					points[i].cp2.x,
					points[i].cp2.y,
					
					points[i].point.x,
					points[i].point.y);
			}
		}
		
		if(options.close) {
			context.closePath();
		}
		
		renderOptions.render(canvas, context);
		context.setTransform(1, 0, 0, 1, 0, 0);
	};
};

Paint.Path.getCircle = function(scale, options) {
	var offset = (4 / 3) * Math.tan(Math.PI / 8),
		path = new Paint.Path(options);
	
	if(typeof scale === 'number') {
		scale = new Paint.Point(scale, scale);
	}
	
	// Right
	path.add(new Paint.Point(scale.x, 0));
	
	// Bottom
	path.add(new Paint.BezierCurve(
		new Paint.Point(scale.x, offset * scale.y),
		new Paint.Point(offset * scale.x,  scale.y),
		new Paint.Point(0, scale.y)
	));
	
	// Left
	path.add(new Paint.BezierCurve(
		new Paint.Point(-offset * scale.x, scale.y),
		new Paint.Point(-scale.x, offset * scale.y),
		new Paint.Point(-scale.x, 0)
	));
	
	// Top
	path.add(new Paint.BezierCurve(
		new Paint.Point(-scale.x, -offset * scale.y),
		new Paint.Point(-offset * scale.x,  -scale.y),
		new Paint.Point(0, -scale.y)
	));
	
	// Close
	path.add(new Paint.BezierCurve(
		new Paint.Point(offset * scale.x, -scale.y),
		new Paint.Point(scale.x, -offset * scale.y),
		new Paint.Point(scale.x, 0)
	));
	
	return path;
};

Paint.Path.getRectangle = function(scale, options) {
	var path = new Paint.Path(options);
	
	if(typeof scale === 'number') {
		scale = new Paint.Point(scale, scale);
	}
	
	path.add(new Paint.Point(-0.5 * scale.x, -0.5 * scale.y));
	path.add(new Paint.Point( 0.5 * scale.x, -0.5 * scale.y));
	path.add(new Paint.Point( 0.5 * scale.x,  0.5 * scale.y));
	path.add(new Paint.Point(-0.5 * scale.x,  0.5 * scale.y));
	path.add(new Paint.Point(-0.5 * scale.x, -0.5 * scale.y));
	
	return path;
};

Paint.Path.getTriangle = function(scale, options) {
	var path = new Paint.Path(options);
	
	if(typeof scale === 'number') {
		scale = new Paint.Point(scale, scale);
	}
	
	path.add(new Paint.Point(0, -1 * scale.y));
	path.add(new Paint.Point(-0.5 * scale.x, 0));
	path.add(new Paint.Point( 0.5 * scale.x, 0));
	path.add(new Paint.Point(0, -1 * scale.y));
	
	return path;
};