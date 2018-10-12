/****************************************************************
 * Paint.BezierCurve
 * Create a new point that draws a bezier line
 *
 * @constructor
 * @param {Number} x
 * @param {Number} y
 ****************************************************************/

Paint.BezierCurve = function(cp1, cp2, p) {
	this.cp1 = cp1 || new Paint.Point;
	this.cp2 = cp2 || new Paint.Point;
	this.point = p || new Paint.Point;
};