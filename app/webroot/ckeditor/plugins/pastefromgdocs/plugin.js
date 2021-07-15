/**
 * START OF POLYFILL
 * These functions are used by this version of the pastetools plugin, 
 * but were not part of the CKEDITOR 4.9.2 API, so they had to be defined elsewhere.
 */

CKEDITOR.tools.array.some = ( array, func ) => {
	return array.some(func);
}

CKEDITOR.tools.array.find = ( array, func ) => {
	return array.find(func);
}

CKEDITOR.tools.object.keys = ( object ) => {
	return Object.keys(object);
}

/**
 * The following code was taken directly from the CKEDITOR4 source code:
 * https://github.com/ckeditor/ckeditor4/blob/cae20318d46745cc46c811da4e7d68b38ca32449/core/tools.js#L2689-L2909
 */ 
CKEDITOR.tools.style.border = CKEDITOR.tools.createClass( {

	/**
	 * Creates a new instance of the border style.
	 * @constructor
	 * @param {Object} [props] Style-related properties.
	 * @param {String} [props.color] Border color.
	 * @param {String} [props.style] Border style.
	 * @param {String} [props.width] Border width.
	 */
	$: function( props ) {
		props = props || {};

		/**
		 * Represents the value of the CSS `width` property.
		 *
		 * @property {String} [width]
		 */
		this.width = props.width;

		/**
		 * Represents the value of the CSS `style` property.
		 *
		 * @property {String} [style]
		 */
		this.style = props.style;

		/**
		 * Represents the value of the CSS `color` property.
		 *
		 * @property {String} [color]
		 */
		this.color = props.color;

		this._.normalize();
	},

	_: {
		normalizeMap: {
			color: [
				[ /windowtext/g, 'black' ]
			]
		},

		normalize: function() {
			for ( var propName in this._.normalizeMap ) {
				var val = this[ propName ];

				if ( val ) {
					this[ propName ] = CKEDITOR.tools.array.reduce( this._.normalizeMap[ propName ], function( cur, rule ) {
						return cur.replace( rule[ 0 ], rule[ 1 ] );
					}, val );
				}
			}
		}
	},

	proto: {
		toString: function() {
			return CKEDITOR.tools.array.filter( [ this.width, this.style, this.color ], function( item ) {
				return !!item;
			} ).join( ' ' );
		}
	},

	statics: {
		fromCssRule: function( value ) {
			var props = {},
				input = value.split( /\s+/g ),
				parseColor = CKEDITOR.tools.style.parse._findColor( value );

			if ( parseColor.length ) {
				props.color = parseColor[ 0 ];
			}

			CKEDITOR.tools.array.forEach( input, function( val ) {
				if ( !props.style ) {
					if ( CKEDITOR.tools.indexOf( CKEDITOR.tools.style.parse._borderStyle, val ) !== -1 ) {
						props.style = val;
						return;
					}
				}

				if ( !props.width ) {
					if ( CKEDITOR.tools.style.parse._widthRegExp.test( val ) ) {
						props.width = val;
						return;
					}
				}

			} );

			return new CKEDITOR.tools.style.border( props );
		},
		splitCssValues: function( styles, fallback ) {
			var types = [ 'width', 'style', 'color' ],
				sides = [ 'top', 'right', 'bottom', 'left' ];

			fallback = fallback || {};

			var stylesMap = CKEDITOR.tools.array.reduce( types, function( cur, type ) {
				var style = styles[ 'border-' + type ] || fallback[ type ];

				cur[ type ] = style ? CKEDITOR.tools.style.parse.sideShorthand( style ) : null;

				return cur;
			}, {} );

			return CKEDITOR.tools.array.reduce( sides, function( cur, side ) {
				var map = {};

				for ( var style in stylesMap ) {
					// Prefer property with greater specificity e.g
					// `border-top-color` over `border-color`.
					var sideProperty = styles[ 'border-' + side + '-' + style ];
					if ( sideProperty ) {
						map[ style ] = sideProperty;
					} else {
						map[ style ] = stylesMap[ style ] && stylesMap[ style ][ side ];
					}
				}

				cur[ 'border-' + side ] = new CKEDITOR.tools.style.border( map );

				return cur;
			}, {} );
		}
	}
} );

/**
 * END OF POLYFILL
 */

//<---------------------------- NORMAL SOURCE CODE STARTING HERE ---------------------->
/**
 * @license Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

/**
 * @fileOverview This plugin handles pasting content from Google Docs.
 */

( function() {
	CKEDITOR.plugins.add( 'pastefromgdocs', {
		requires: 'pastetools',

		init: function( editor ) {
			var pasteToolsPath = CKEDITOR.plugins.getPath( 'pastetools' ),
				path = this.path;

			editor.pasteTools.register( {
				filters: [
					CKEDITOR.getUrl( pasteToolsPath + 'filter/common.js' ),
					CKEDITOR.getUrl(  path + 'filter/default.js' )
				],

				canHandle: function( evt ) {
					var detectGDocsRegex = /id=(\"|\')?docs\-internal\-guid\-/;

					return detectGDocsRegex.test( evt.data.dataValue );
				},

				handle: function( evt, next ) {
					var data = evt.data,
						gDocsHtml = CKEDITOR.plugins.pastetools.getClipboardData( data, 'text/html' );

					// Do not apply the paste filter to data filtered by the the Google Docs filter (https://dev.ckeditor.com/ticket/13093).
					data.dontFilter = true;
					data.dataValue = CKEDITOR.pasteFilters.gdocs( gDocsHtml, editor );

					if ( editor.config.forcePasteAsPlainText === true ) {
						// If `config.forcePasteAsPlainText` is set to `true`, force plain text even on Google Docs content (#1013).
						data.type = 'text';
					}

					next();
				}
			} );
		}

	} );
} )();
