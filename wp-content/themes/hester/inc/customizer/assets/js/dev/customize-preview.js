/**
 * Update Customizer settings live.
 *
 * @since 1.0.0
 */
( function( $ ) {
	'use strict';

	// Declare variables
	var api = wp.customize,
		$body = $( 'body' ),
		$head = $( 'head' ),
		$style_tag,
		$link_tag,
		hester_visibility_classes = 'hester-hide-mobile hester-hide-tablet hester-hide-mobile-tablet',
		hester_style_tag_collection = [],
		hester_link_tag_collection = [];

	/**
	 * Helper function to get style tag with id.
	 */
	function hester_get_style_tag( id ) {
		if ( hester_style_tag_collection[id]) {
			return hester_style_tag_collection[id];
		}

		$style_tag = $( 'head' ).find( '#hester-dynamic-' + id );

		if ( ! $style_tag.length ) {
			$( 'head' ).append( '<style id="hester-dynamic-' + id + '" type="text/css" href="#"></style>' );
			$style_tag = $( 'head' ).find( '#hester-dynamic-' + id );
		}

		hester_style_tag_collection[id] = $style_tag;

		return $style_tag;
	}

	/**
	 * Helper function to get link tag with id.
	 */
	function hester_get_link_tag( id, url ) {
		if ( hester_link_tag_collection[id]) {
			return hester_link_tag_collection[id];
		}

		$link_tag = $( 'head' ).find( '#hester-dynamic-link-' + id );

		if ( ! $link_tag.length ) {
			$( 'head' ).append( '<link id="hester-dynamic-' + id + '" type="text/css" rel="stylesheet" href="' + url + '"/>' );
			$link_tag = $( 'head' ).find( '#hester-dynamic-link-' + id );
		} else {
			$link_tag.attr( 'href', url );
		}

		hester_link_tag_collection[id] = $link_tag;

		return $link_tag;
	}

	/*
	 * Helper function to print visibility classes.
	 */
	function hester_print_visibility_classes( $element, newval ) {
		if ( ! $element.length ) {
			return;
		}

		$element.removeClass( hester_visibility_classes );

		if ( 'all' !== newval ) {
			$element.addClass( 'hester-' + newval );
		}
	}

	/*
	 * Helper function to convert hex to rgba.
	 */
	function hester_hex2rgba( hex, opacity ) {
		if ( 'rgba' === hex.substring( 0, 4 ) ) {
			return hex;
		}

		// Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF").
		var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;

		hex = hex.replace( shorthandRegex, function( m, r, g, b ) {
			return r + r + g + g + b + b;
		});

		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec( hex );

		if ( opacity ) {
			if ( 1 < opacity ) {
				opacity = 1;
			}

			opacity = ',' + opacity;
		}

		if ( result ) {
			return 'rgba(' + parseInt( result[1], 16 ) + ',' + parseInt( result[2], 16 ) + ',' + parseInt( result[3], 16 ) + opacity + ')';
		}

		return false;
	}

	/**
	 * Helper function to lighten or darken the provided hex color.
	 */
	function hester_luminance( hex, percent ) {

		// Convert RGB color to HEX.
		if ( hex.includes( 'rgb' ) ) {
			hex = hester_rgba2hex( hex );
		}

		// Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF").
		var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;

		hex = hex.replace( shorthandRegex, function( m, r, g, b ) {
			return r + r + g + g + b + b;
		});

		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec( hex );

		var isColor = /^#[0-9A-F]{6}$/i.test( hex );

		if ( ! isColor ) {
			return hex;
		}

		var from, to;

		for ( var i = 1; 3 >= i; i++ ) {
			result[i] = parseInt( result[i], 16 );
			from = 0 > percent ? 0 : result[i];
			to = 0 > percent ? result[i] : 255;
			result[i] = result[i] + Math.ceil( ( to - from ) * percent );
		}

		result = '#' + hester_dec2hex( result[1]) + hester_dec2hex( result[2]) + hester_dec2hex( result[3]);

		return result;
	}

	/**
	 * Convert dec to hex.
	 */
	function hester_dec2hex( c ) {
		var hex = c.toString( 16 );
		return 1 == hex.length ? '0' + hex : hex;
	}

	/**
	 * Convert rgb to hex.
	 */
	function hester_rgba2hex( c ) {
		var a, x;

		a = c.split( '(' )[1].split( ')' )[0].trim();
		a = a.split( ',' );

		var result = '';

		for ( var i = 0; 3 > i; i++ ) {
			x = parseInt( a[i]).toString( 16 );
			result += 1 === x.length ? '0' + x : x;
		}

		if ( result ) {
			return '#' + result;
		}

		return false;
	}

	/**
	 * Check if is light color.
	 */
	function hester_is_light_color( color = '' ) {
		var r, g, b, brightness;

		if ( color.match( /^rgb/ ) ) {
			color = color.match( /^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/ );
			r = color[1];
			g = color[2];
			b = color[3];
		} else {
			color = +( '0x' + color.slice( 1 ).replace( 5 > color.length && /./g, '$&$&' ) );
			r = color >> 16;
			g = ( color >> 8 ) & 255;
			b = color & 255;
		}

		brightness = ( r * 299 + g * 587 + b * 114 ) / 1000;

		return 137 < brightness;
	}

	/**
	 * Detect if we should use a light or dark color on a background color.
	 */
	function hester_light_or_dark( color, dark = '#000000', light = '#FFFFFF' ) {
		return hester_is_light_color( color ) ? dark : light;
	}

	/**
	 * Spacing field CSS.
	 */
	function hester_spacing_field_css( selector, property, setting, responsive ) {
		if ( ! Array.isArray( setting ) && 'object' !== typeof setting ) {
			return;
		}

		// Set up unit.
		var unit = 'px',
			css = '';

		if ( 'unit' in setting ) {
			unit = setting.unit;
		}

		var before = '',
			after = '';

		Object.keys( setting ).forEach( function( index, el ) {
			if ( 'unit' === index ) {
				return;
			}

			if ( responsive ) {
				if ( 'tablet' === index ) {
					before = '@media only screen and (max-width: 768px) {';
					after = '}';
				} else if ( 'mobile' === index ) {
					before = '@media only screen and (max-width: 480px) {';
					after = '}';
				} else {
					before = '';
					after = '';
				}

				css += before + selector + '{';

				Object.keys( setting[index]).forEach( function( position ) {
					if ( 'border' === property ) {
						position += '-width';
					}

					if ( setting[index][position]) {
						css += property + '-' + position + ': ' + setting[index][position] + unit + ';';
					}
				});

				css += '}' + after;
			} else {
				if ( 'border' === property ) {
					index += '-width';
				}

				css += property + '-' + index + ': ' + setting[index] + unit + ';';
			}
		});

		if ( ! responsive ) {
			css = selector + '{' + css + '}';
		}

		return css;
	}

	/**
	 * Range field CSS.
	 */
	function hester_range_field_css( selector, property, setting, responsive, unit ) {
		var css = '',
			before = '',
			after = '';

		if ( responsive && ( Array.isArray( setting ) || 'object' === typeof setting ) ) {
			Object.keys( setting ).forEach( function( index, el ) {
				if ( setting[index]) {
					if ( 'tablet' === index ) {
						before = '@media only screen and (max-width: 768px) {';
						after = '}';
					} else if ( 'mobile' === index ) {
						before = '@media only screen and (max-width: 480px) {';
						after = '}';
					} else if ( 'desktop' === index ) {
						before = '';
						after = '';
					} else {
						return;
					}

					css += before + selector + '{' + property + ': ' + setting[index] + unit + '; }' + after;
				}
			});
		}

		if ( ! responsive ) {
			if ( setting.value ) {
				setting = setting.value;
			} else {
				setting = 0;
			}

			css = selector + '{' + property + ': ' + setting + unit + '; }';
		}

		return css;
	}

	/**
	 * Typography field CSS.
	 */
	function hester_typography_field_css( selector, setting ) {
		var css = '';

		css += selector + '{';

		if ( 'default' === setting['font-family']) {
			css += 'font-family: ' + hester_customizer_preview.default_system_font + ';';
		} else if ( setting['font-family'] in hester_customizer_preview.fonts.standard_fonts.fonts ) {
			css += 'font-family: ' + hester_customizer_preview.fonts.standard_fonts.fonts[setting['font-family']].fallback + ';';
		} else if ( 'inherit' !== setting['font-family']) {
			css += 'font-family: "' + setting['font-family'] + '";';
		}

		css += 'font-weight:' + setting['font-weight'] + ';';
		css += 'font-style:' + setting['font-style'] + ';';
		css += 'text-transform:' + setting['text-transform'] + ';';

		if ( 'text-decoration' in setting ) {
			css += 'text-decoration:' + setting['text-decoration'] + ';';
		}

		if ( 'letter-spacing' in setting ) {
			css += 'letter-spacing:' + setting['letter-spacing'] + setting['letter-spacing-unit'] + ';';
		}

		if ( 'line-height-desktop' in setting ) {
			css += 'line-height:' + setting['line-height-desktop'] + ';';
		}

		if ( 'font-size-desktop' in setting && 'font-size-unit' in setting ) {
			css += 'font-size:' + setting['font-size-desktop'] + setting['font-size-unit'] + ';';
		}

		css += '}';

		if ( 'font-size-tablet' in setting && setting['font-size-tablet']) {
			css += '@media only screen and (max-width: 768px) {' + selector + '{' + 'font-size: ' + setting['font-size-tablet'] + setting['font-size-unit'] + ';' + '}' + '}';
		}

		if ( 'line-height-tablet' in setting && setting['line-height-tablet']) {
			css += '@media only screen and (max-width: 768px) {' + selector + '{' + 'line-height:' + setting['line-height-tablet'] + ';' + '}' + '}';
		}

		if ( 'font-size-mobile' in setting && setting['font-size-mobile']) {
			css += '@media only screen and (max-width: 480px) {' + selector + '{' + 'font-size: ' + setting['font-size-mobile'] + setting['font-size-unit'] + ';' + '}' + '}';
		}

		if ( 'line-height-mobile' in setting && setting['line-height-mobile']) {
			css += '@media only screen and (max-width: 480px) {' + selector + '{' + 'line-height:' + setting['line-height-mobile'] + ';' + '}' + '}';
		}

		return css;
	}

	/**
	 * Load google font.
	 */
	function hester_enqueue_google_font( font ) {
		if ( hester_customizer_preview.fonts.google_fonts.fonts[font]) {
			var id = 'google-font-' + font.trim().toLowerCase().replace( ' ', '-' );
			var url = hester_customizer_preview.google_fonts_url + '/css?family=' + font + ':' + hester_customizer_preview.google_font_weights;

			var tag = hester_get_link_tag( id, url );
		}
	}

	/**
	 * Design Options field CSS.
	 */
	function hester_design_options_css( selector, setting, type ) {
		var css = '',
			before = '',
			after = '';

		if ( 'background' === type ) {
			var bg_type = setting['background-type'];

			css += selector + '{';

			if ( 'color' === bg_type ) {
				setting['background-color'] = setting['background-color'] ? setting['background-color'] : 'inherit';
				css += 'background: ' + setting['background-color'] + ';';
			} else if ( 'gradient' === bg_type ) {
				css += 'background: ' + setting['gradient-color-1'] + ';';

				if ( 'linear' === setting['gradient-type']) {
					css +=
						'background: -webkit-linear-gradient(' +
						setting['gradient-linear-angle'] +
						'deg, ' +
						setting['gradient-color-1'] +
						' ' +
						setting['gradient-color-1-location'] +
						'%, ' +
						setting['gradient-color-2'] +
						' ' +
						setting['gradient-color-2-location'] +
						'%);' +
						'background: -o-linear-gradient(' +
						setting['gradient-linear-angle'] +
						'deg, ' +
						setting['gradient-color-1'] +
						' ' +
						setting['gradient-color-1-location'] +
						'%, ' +
						setting['gradient-color-2'] +
						' ' +
						setting['gradient-color-2-location'] +
						'%);' +
						'background: linear-gradient(' +
						setting['gradient-linear-angle'] +
						'deg, ' +
						setting['gradient-color-1'] +
						' ' +
						setting['gradient-color-1-location'] +
						'%, ' +
						setting['gradient-color-2'] +
						' ' +
						setting['gradient-color-2-location'] +
						'%);';
				} else if ( 'radial' === setting['gradient-type']) {
					css +=
						'background: -webkit-radial-gradient(' +
						setting['gradient-position'] +
						', circle, ' +
						setting['gradient-color-1'] +
						' ' +
						setting['gradient-color-1-location'] +
						'%, ' +
						setting['gradient-color-2'] +
						' ' +
						setting['gradient-color-2-location'] +
						'%);' +
						'background: -o-radial-gradient(' +
						setting['gradient-position'] +
						', circle, ' +
						setting['gradient-color-1'] +
						' ' +
						setting['gradient-color-1-location'] +
						'%, ' +
						setting['gradient-color-2'] +
						' ' +
						setting['gradient-color-2-location'] +
						'%);' +
						'background: radial-gradient(circle at ' +
						setting['gradient-position'] +
						', ' +
						setting['gradient-color-1'] +
						' ' +
						setting['gradient-color-1-location'] +
						'%, ' +
						setting['gradient-color-2'] +
						' ' +
						setting['gradient-color-2-location'] +
						'%);';
				}
			} else if ( 'image' === bg_type ) {
				css +=
					'' +
					'background-image: url(' +
					setting['background-image'] +
					');' +
					'background-size: ' +
					setting['background-size'] +
					';' +
					'background-attachment: ' +
					setting['background-attachment'] +
					';' +
					'background-position: ' +
					setting['background-position-x'] +
					'% ' +
					setting['background-position-y'] +
					'%;' +
					'background-repeat: ' +
					setting['background-repeat'] +
					';';
			}

			css += '}';

			// Background image color overlay.
			if ( 'image' === bg_type && setting['background-color-overlay'] && setting['background-image']) {
				css += selector + '::after { background-color: ' + setting['background-color-overlay'] + '; }';
			} else {
				css += selector + '::after { background-color: initial; }';
			}
		} else if ( 'color' === type ) {
			setting['text-color'] = setting['text-color'] ? setting['text-color'] : 'inherit';
			setting['link-color'] = setting['link-color'] ? setting['link-color'] : 'inherit';
			setting['link-hover-color'] = setting['link-hover-color'] ? setting['link-hover-color'] : 'inherit';

			css += selector + ' { color: ' + setting['text-color'] + '; }';
			css += selector + ' a { color: ' + setting['link-color'] + '; }';
			css += selector + ' a:hover { color: ' + setting['link-hover-color'] + ' !important; }';
		} else if ( 'border' === type ) {
			setting['border-color'] = setting['border-color'] ? setting['border-color'] : 'inherit';
			setting['border-style'] = setting['border-style'] ? setting['border-style'] : 'solid';
			setting['border-left-width'] = setting['border-left-width'] ? setting['border-left-width'] : 0;
			setting['border-top-width'] = setting['border-top-width'] ? setting['border-top-width'] : 0;
			setting['border-right-width'] = setting['border-right-width'] ? setting['border-right-width'] : 0;
			setting['border-bottom-width'] = setting['border-bottom-width'] ? setting['border-bottom-width'] : 0;

			css += selector + '{';
			css += 'border-color: ' + setting['border-color'] + ';';
			css += 'border-style: ' + setting['border-style'] + ';';
			css += 'border-left-width: ' + setting['border-left-width'] + 'px;';
			css += 'border-top-width: ' + setting['border-top-width'] + 'px;';
			css += 'border-right-width: ' + setting['border-right-width'] + 'px;';
			css += 'border-bottom-width: ' + setting['border-bottom-width'] + 'px;';
			css += '}';
		} else if ( 'separator_color' === type ) {
			css += selector + ':after{ background-color: ' + setting['separator-color'] + '; }';
		}

		return css;
	}

	/**
	 * Logo max height.
	 */
	api( 'hester_logo_max_height', function( value ) {
		value.bind( function( newval ) {
			var $logo = $( '.hester-logo' );

			if ( ! $logo.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_logo_max_height' );
			var style_css = '';

			style_css += hester_range_field_css( '.hester-logo img', 'max-height', newval, true, 'px' );
			style_css += hester_range_field_css( '.hester-logo img.hester-svg-logo', 'height', newval, true, 'px' );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Logo text font size.
	 */
	api( 'hester_logo_text_font_size', function( value ) {
		value.bind( function( newval ) {
			var $logo = $( '#hester-header .hester-logo .site-title' );

			if ( ! $logo.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_logo_text_font_size' );
			var style_css = '';

			style_css += hester_range_field_css( '#hester-header .hester-logo .site-title', 'font-size', newval, true, newval.unit );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Logo margin.
	 */
	api( 'hester_logo_margin', function( value ) {
		value.bind( function( newval ) {
			var $logo = $( '.hester-logo' );

			if ( ! $logo.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_logo_margin' );

			var style_css = hester_spacing_field_css( '.hester-logo .logo-inner', 'margin', newval, true );
			$style_tag.html( style_css );
		});
	});

	/**
	 * Tagline.
	 */
	api( 'blogdescription', function( value ) {
		value.bind( function( newval ) {
			if ( $( '.hester-logo' ).find( '.site-description' ).length ) {
				$( '.hester-logo' ).find( '.site-description' ).html( newval );
			}
		});
	});

	/**
	 * Site Title.
	 */
	api( 'blogname', function( value ) {
		value.bind( function( newval ) {
			if ( $( '.hester-logo' ).find( '.site-title' ).length ) {
				$( '.hester-logo' ).find( '.site-title' ).find( 'a' ).html( newval );
			}
		});
	});

	/**
	 * Site Layout.
	 */
	api( 'hester_site_layout', function( value ) {
		value.bind( function( newval ) {
			$body.removeClass( function( index, className ) {
				return ( className.match( /(^|\s)hester-layout__\S+/g ) || []).join( ' ' );
			});

			$body.addClass( 'hester-layout__' + newval );
		});
	});

	/**
	 * Sticky Sidebar.
	 */
	api( 'hester_sidebar_sticky', function( value ) {
		value.bind( function( newval ) {
			$body.removeClass( function( index, className ) {
				return ( className.match( /(^|\s)hester-sticky-\S+/g ) || []).join( ' ' );
			});

			if ( newval ) {
				$body.addClass( 'hester-sticky-' + newval );
			}
		});
	});

	/**
	 * Sidebar width.
	 */
	api( 'hester_sidebar_width', function( value ) {
		value.bind( function( newval ) {
			var $sidebar = $( '#secondary' );

			if ( ! $sidebar.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_sidebar_width' );
			var style_css = '#secondary { width: ' + newval.value + '%; }';
			style_css += 'body:not(.hester-no-sidebar) #primary { ' + 'max-width: ' + ( 100 - parseInt( newval.value ) ) + '%;' + '};';

			$style_tag.html( style_css );
		});
	});

	/**
	 * Sidebar style.
	 */
	api( 'hester_sidebar_style', function( value ) {
		value.bind( function( newval ) {
			$body.removeClass( function( index, className ) {
				return ( className.match( /(^|\s)hester-sidebar-style-\S+/g ) || []).join( ' ' );
			});

			$body.addClass( 'hester-sidebar-style-' + newval );
		});
	});

	/**
	 * Responsive sidebar position.
	 */
	api( 'hester_sidebar_responsive_position', function( value ) {
		value.bind( function( newval ) {
			$body.removeClass( function( index, className ) {
				return ( className.match( /(^|\s)hester-sidebar-r__\S+/g ) || []).join( ' ' );
			});

			if ( newval ) {
				$body.addClass( 'hester-sidebar-r__' + newval );
			}
		});
	});

	/**
	 * Featured Image Position (Horizontal Blog layout)
	 */
	api( 'hester_blog_image_position', function( value ) {
		value.bind( function( newval ) {
			$( '.hester-blog-entry-wrapper' ).removeClass( function( index, className ) {
				return ( className.match( /(^|\s)hester-thumb-\S+/g ) || []).join( ' ' );
			});

			$( '.hester-blog-entry-wrapper' ).addClass( 'hester-thumb-' + newval );
		});
	});

	/**
	 * Single page - title in header alignment.
	 */
	api( 'hester_single_title_alignment', function( value ) {
		value.bind( function( newval ) {
			$body.removeClass( function( index, className ) {
				return ( className.match( /(^|\s)hester-page-title-align-\S+/g ) || []).join( ' ' );
			});

			$body.addClass( 'hester-page-title-align-' + newval );
		});
	});

	/**
	 * Single Page title spacing.
	 */
	api( 'hester_single_title_spacing', function( value ) {
		value.bind( function( newval ) {
			var $page_header = $( '.page-header' );

			if ( ! $page_header.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_single_title_spacing' );

			var style_css = hester_spacing_field_css( '.hester-single-title-in-page-header #page .page-header .hester-page-header-wrapper', 'padding', newval, true );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Single post narrow container width.
	 */
	api( 'hester_single_narrow_container_width', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_single_narrow_container_width' );
			var style_css = '';

			style_css +=
				'.single-post.narrow-content .entry-content > :not([class*="align"]):not([class*="gallery"]):not(.wp-block-image):not(.quote-inner):not(.quote-post-bg), ' +
				'.single-post.narrow-content .mce-content-body:not([class*="page-template-full-width"]) > :not([class*="align"]):not([data-wpview-type*="gallery"]):not(blockquote):not(.mceTemp), ' +
				'.single-post.narrow-content .entry-footer, ' +
				'.single-post.narrow-content .post-nav, ' +
				'.single-post.narrow-content .entry-content > .alignwide, ' +
				'.single-post.narrow-content p.has-background:not(.alignfull):not(.alignwide)' +
				'.single-post.narrow-content #hester-comments-toggle, ' +
				'.single-post.narrow-content #comments, ' +
				'.single-post.narrow-content .entry-content .aligncenter, ' +
				'.single-post.narrow-content .hester-narrow-element, ' +
				'.single-post.narrow-content.hester-single-title-in-content .entry-header, ' +
				'.single-post.narrow-content.hester-single-title-in-content .entry-meta, ' +
				'.single-post.narrow-content.hester-single-title-in-content .post-category, ' +
				'.single-post.narrow-content.hester-no-sidebar .hester-page-header-wrapper, ' +
				'.single-post.narrow-content.hester-no-sidebar .hester-breadcrumbs > .hester-container > nav {' +
				'max-width: ' +
				parseInt( newval.value ) +
				'px; margin-left: auto; margin-right: auto; ' +
				'}';

			style_css += '.single-post.narrow-content .author-box, ' + '.single-post.narrow-content .entry-content > .alignwide { ' + 'max-width: ' + ( parseInt( newval.value ) + 70 ) + 'px;' + '}';

			$style_tag.html( style_css );
		});
	});

	/**
	 * Single post content font size.
	 */
	api( 'hester_single_content_font_size', function( value ) {
		value.bind( function( newval ) {
			var $content = $( '.single-post' );

			if ( ! $content.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_single_content_font_size' );
			var style_css = '';

			style_css += hester_range_field_css( '.single-post .entry-content', 'font-size', newval, true, newval.unit );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Header container width.
	 */
	api( 'hester_header_container_width', function( value ) {
		value.bind( function( newval ) {
			var $header = $( '#hester-header' );

			if ( ! $header.length ) {
				return;
			}

			if ( 'full-width' === newval ) {
				$header.addClass( 'hester-container__wide' );
			} else {
				$header.removeClass( 'hester-container__wide' );
			}
		});
	});

	/**
	 * Main navigation disply breakpoint.
	 */
	api( 'hester_main_nav_mobile_breakpoint', function( value ) {
		value.bind( function( newval ) {
			var $nav = $( '#hester-header-inner .hester-nav' );

			if ( ! $nav.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_main_nav_mobile_breakpoint' );
			var style_css = '';

			style_css += '@media screen and (min-width: ' + parseInt( newval ) + 'px) {#hester-header-inner .hester-nav {display:flex} .hester-mobile-nav,.hester-mobile-toggen,#hester-header-inner .hester-nav .menu-item-has-children>a > .hester-icon,#hester-header-inner .hester-nav .page_item_has_children>a > .hester-icon {display:none;} }';
			style_css += '@media screen and (max-width: ' + parseInt( newval ) + 'px) {#hester-header-inner .hester-nav {display:none} .hester-mobile-nav,.hester-mobile-toggen {display:inline-flex;} }';

			$style_tag.html( style_css );
		});
	});

	/**
	 * Mobile Menu Button Label.
	 */
	api( 'hester_main_nav_mobile_label', function( value ) {
		value.bind( function( newval ) {
			if ( $( '.hester-hamburger-hester-primary-nav' ).find( '.hamburger-label' ).length ) {
				$( '.hester-hamburger-hester-primary-nav' ).find( '.hamburger-label' ).html( newval );
			}
		});
	});

	/**
	 * Main Nav Font color.
	 */
	api( 'hester_main_nav_font_color', function( value ) {
		value.bind( function( newval ) {
			var $navigation = $( '#hester-header-inner .hester-nav' );

			if ( ! $navigation.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_main_nav_font_color' );
			var style_css = '';

			// Link color.
			newval['link-color'] = newval['link-color'] ? newval['link-color'] : 'inherit';
			style_css += '#hester-header-inner .hester-nav > ul > li > a { color: ' + newval['link-color'] + '; }';

			// Link hover color.
			newval['link-hover-color'] = newval['link-hover-color'] ? newval['link-hover-color'] : api.value( 'hester_accent_color' )();
			style_css +=
				'#hester-header-inner .hester-nav > ul > li > a:hover, ' +
				'#hester-header-inner .hester-nav > ul > li.menu-item-has-children:hover > a, ' +
				'#hester-header-inner .hester-nav > ul > li.current-menu-item > a, ' +
				'#hester-header-inner .hester-nav > ul > li.current-menu-ancestor > a ' +
				'#hester-header-inner .hester-nav > ul > li.page_item_has_children:hover > a, ' +
				'#hester-header-inner .hester-nav > ul > li.current_page_item > a, ' +
				'#hester-header-inner .hester-nav > ul > li.current_page_ancestor > a ' +
				'{ color: ' +
				newval['link-hover-color'] +
				'; }';

			$style_tag.html( style_css );
		});
	});

	/**
	 * Main Nav Background.
	 */
	api( 'hester_main_nav_background', function( value ) {
		value.bind( function( newval ) {
			var $navigation = $( '.hester-header-layout-3 .hester-nav-container' );

			if ( ! $navigation.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_main_nav_background' );
			var style_css = hester_design_options_css( '.hester-header-layout-3 .hester-nav-container', newval, 'background' );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Main Nav Border.
	 */
	api( 'hester_main_nav_border', function( value ) {
		value.bind( function( newval ) {
			var $navigation = $( '.hester-header-layout-3 .hester-nav-container' );

			if ( ! $navigation.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_main_nav_border' );
			var style_css = hester_design_options_css( '.hester-header-layout-3 .hester-nav-container', newval, 'border' );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Main Nav font size.
	 */
	api( 'hester_main_nav_font_size', function( value ) {
		value.bind( function( newval ) {
			var $nav = $( '#hester-header-inner' );

			if ( ! $nav.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_main_nav_font_size' );
			var style_css = '';

			style_css += hester_range_field_css( '.hester-nav.hester-header-element, .hester-header-layout-1 .hester-header-widgets, .hester-header-layout-2 .hester-header-widgets', 'font-size', newval, false, newval.unit );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Top Bar container width.
	 */
	api( 'hester_top_bar_container_width', function( value ) {
		value.bind( function( newval ) {
			var $topbar = $( '#hester-topbar' );

			if ( ! $topbar.length ) {
				return;
			}

			if ( 'full-width' === newval ) {
				$topbar.addClass( 'hester-container__wide' );
			} else {
				$topbar.removeClass( 'hester-container__wide' );
			}
		});
	});

	/**
	 * Top Bar visibility.
	 */
	api( 'hester_top_bar_visibility', function( value ) {
		value.bind( function( newval ) {
			var $topbar = $( '#hester-topbar' );

			hester_print_visibility_classes( $topbar, newval );
		});
	});

	/**
	 * Top Bar widgets separator.
	 */
	api( 'hester_top_bar_widgets_separator', function( value ) {
		value.bind( function( newval ) {
			$body.removeClass( function( index, className ) {
				return ( className.match( /(^|\s)hester-topbar__separators-\S+/g ) || []).join( ' ' );
			});

			$body.addClass( 'hester-topbar__separators-' + newval );
		});
	});

	/**
	 * Top Bar background.
	 */
	api( 'hester_top_bar_background', function( value ) {
		value.bind( function( newval ) {
			var $topbar = $( '#hester-topbar' );

			if ( ! $topbar.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_top_bar_background' );
			var style_css = hester_design_options_css( '#hester-topbar', newval, 'background' );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Top Bar color.
	 */
	api( 'hester_top_bar_text_color', function( value ) {
		value.bind( function( newval ) {
			var $topbar = $( '#hester-topbar' );

			if ( ! $topbar.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_top_bar_text_color' );
			var style_css = '';

			newval['text-color'] = newval['text-color'] ? newval['text-color'] : 'inherit';
			newval['link-color'] = newval['link-color'] ? newval['link-color'] : 'inherit';
			newval['link-hover-color'] = newval['link-hover-color'] ? newval['link-hover-color'] : 'inherit';

			// Text color.
			style_css += '#hester-topbar { color: ' + newval['text-color'] + '; }';

			// Link color.
			style_css += '.hester-topbar-widget__text a, ' + '.hester-topbar-widget .hester-nav > ul > li > a, ' + '.hester-topbar-widget__socials .hester-social-nav > ul > li > a, ' + '#hester-topbar .hester-topbar-widget__text .hester-icon { color: ' + newval['link-color'] + '; }';

			// Link hover color.
			style_css +=
				'#hester-topbar .hester-nav > ul > li > a:hover, ' +
				'#hester-topbar .hester-nav > ul > li.menu-item-has-children:hover > a,  ' +
				'#hester-topbar .hester-nav > ul > li.current-menu-item > a, ' +
				'#hester-topbar .hester-nav > ul > li.current-menu-ancestor > a, ' +
				'#hester-topbar .hester-topbar-widget__text a:hover, ' +
				'#hester-topbar .hester-social-nav > ul > li > a .hester-icon.bottom-icon { color: ' +
				newval['link-hover-color'] +
				'; }';

			$style_tag.html( style_css );
		});
	});

	/**
	 * Top Bar border.
	 */
	api( 'hester_top_bar_border', function( value ) {
		value.bind( function( newval ) {
			var $topbar = $( '#hester-topbar' );

			if ( ! $topbar.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_top_bar_border' );
			var style_css = hester_design_options_css( '#hester-topbar', newval, 'border' );

			style_css += hester_design_options_css( '#hester-topbar .hester-topbar-widget', newval, 'separator_color' );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Header menu item hover animation.
	 */
	api( 'hester_main_nav_hover_animation', function( value ) {
		value.bind( function( newval ) {
			$body.removeClass( function( index, className ) {
				return ( className.match( /(^|\s)hester-menu-animation-\S+/g ) || []).join( ' ' );
			});

			$body.addClass( 'hester-menu-animation-' + newval );
		});
	});

	/**
	 * Header widgets separator.
	 */
	api( 'hester_header_widgets_separator', function( value ) {
		value.bind( function( newval ) {
			$body.removeClass( function( index, className ) {
				return ( className.match( /(^|\s)hester-header__separators-\S+/g ) || []).join( ' ' );
			});

			$body.addClass( 'hester-header__separators-' + newval );
		});
	});

	/**
	 * Header background.
	 */
	api( 'hester_header_background', function( value ) {
		value.bind( function( newval ) {
			var $header = $( '#hester-header-inner' );

			if ( ! $header.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_header_background' );
			var style_css = hester_design_options_css( '#hester-header-inner', newval, 'background' );

			if ( 'color' === newval['background-type'] && newval['background-color']) {
				style_css += '.hester-header-widget__cart .hester-cart .hester-cart-count { border: 2px solid ' + newval['background-color'] + '; }';
			} else {
				style_css += '.hester-header-widget__cart .hester-cart .hester-cart-count { border: none; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Header font color.
	 */
	api( 'hester_header_text_color', function( value ) {
		value.bind( function( newval ) {
			var $header = $( '#hester-header' );

			if ( ! $header.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_header_text_color' );
			var style_css = '';

			// Text color.
			style_css += '.hester-logo .site-description { color: ' + newval['text-color'] + '; }';

			// Link color.
			if ( newval['link-color']) {
				style_css += '#hester-header, ' + '.hester-header-widgets a:not(.hester-btn), ' + '.hester-logo a,' + '.hester-hamburger { color: ' + newval['link-color'] + '; }';
				style_css += '.hamburger-inner,' + '.hamburger-inner::before,' + '.hamburger-inner::after { background-color: ' + newval['link-color'] + '; }';
			}

			// Link hover color.
			if ( newval['link-hover-color']) {
				style_css +=
					'.hester-header-widgets a:not(.hester-btn):hover, ' +
					'#hester-header-inner .hester-header-widgets .hester-active,' +
					'.hester-logo .site-title a:hover, ' +
					'.hester-hamburger:hover .hamburger-label, ' +
					'.is-mobile-menu-active .hester-hamburger .hamburger-label,' +
					'#hester-header-inner .hester-nav > ul > li > a:hover,' +
					'#hester-header-inner .hester-nav > ul > li.menu-item-has-children:hover > a,' +
					'#hester-header-inner .hester-nav > ul > li.current-menu-item > a,' +
					'#hester-header-inner .hester-nav > ul > li.current-menu-ancestor > a,' +
					'#hester-header-inner .hester-nav > ul > li.page_item_has_children:hover > a,' +
					'#hester-header-inner .hester-nav > ul > li.current_page_item > a,' +
					'#hester-header-inner .hester-nav > ul > li.current_page_ancestor > a { color: ' +
					newval['link-hover-color'] +
					'; }';

				style_css +=
					'.hester-hamburger:hover .hamburger-inner,' +
					'.hester-hamburger:hover .hamburger-inner::before,' +
					'.hester-hamburger:hover .hamburger-inner::after,' +
					'.is-mobile-menu-active .hester-hamburger .hamburger-inner,' +
					'.is-mobile-menu-active .hester-hamburger .hamburger-inner::before,' +
					'.is-mobile-menu-active .hester-hamburger .hamburger-inner::after { background-color: ' +
					newval['link-hover-color'] +
					'; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Header border.
	 */
	api( 'hester_header_border', function( value ) {
		value.bind( function( newval ) {
			var $header = $( '#hester-header-inner' );

			if ( ! $header.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_header_border' );
			var style_css = hester_design_options_css( '#hester-header-inner', newval, 'border' );

			// Separator color.
			newval['separator-color'] = newval['separator-color'] ? newval['separator-color'] : 'inherit';
			style_css += '.hester-header-widget:after { background-color: ' + newval['separator-color'] + '; }';

			$style_tag.html( style_css );
		});
	});

	/**
	 * Hero container width.
	 */
	api( 'hester_hero_hover_slider_container', function( value ) {
		value.bind( function( newval ) {
			var $hero_container = $( '#hero .hester-hero-container' );

			if ( ! $hero_container.length ) {
				return;
			}

			if ( 'full-width' === newval ) {
				$hero_container.addClass( 'hester-container__wide' );
			} else {
				$hero_container.removeClass( 'hester-container__wide' );
			}
		});
	});

	/**
	 * Hero overlay style.
	 */
	api( 'hester_hero_hover_slider_overlay', function( value ) {
		value.bind( function( newval ) {
			var $hero = $( '#hero .hester-hover-slider' );

			if ( ! $hero.length ) {
				return;
			}

			$hero
				.removeClass( function( index, className ) {
					return ( className.match( /(^|\s)slider-overlay-\S+/g ) || []).join( ' ' );
				})
				.addClass( 'slider-overlay-' + newval );
		});
	});

	/**
	 * Hero height.
	 */
	api( 'hester_hero_hover_slider_height', function( value ) {
		value.bind( function( newval ) {
			var $hero = $( '#hero' );

			if ( ! $hero.length ) {
				return;
			}

			$hero.find( '.hover-slide-item' ).css( 'height', newval.value + 'px' );
		});
	});

	/**
	 * Hero visibility.
	 */
	api( 'hester_hero_visibility', function( value ) {
		value.bind( function( newval ) {
			hester_print_visibility_classes( $( '#hero' ), newval );
		});
	});

	/**
	 * Custom input style.
	 */
	api( 'hester_custom_input_style', function( value ) {
		value.bind( function( newval ) {
			if ( newval ) {
				$body.addClass( 'hester-input-supported' );
			} else {
				$body.removeClass( 'hester-input-supported' );
			}
		});
	});

	/**
	 * Pre Footer Call to Action Enable.
	 */
	api( 'hester_enable_pre_footer_cta', function( value ) {
		value.bind( function( newval ) {
			if ( newval ) {
				$body.addClass( 'hester-pre-footer-cta-style-' + api.value( 'hester_pre_footer_cta_style' )() );
			} else {
				$body.removeClass( function( index, className ) {
					return ( className.match( /(^|\s)hester-pre-footer-cta-style-\S+/g ) || []).join( ' ' );
				});
			}
		});
	});

	/**
	 * Pre Footer Call to Action visibility.
	 */
	api( 'hester_pre_footer_cta_visibility', function( value ) {
		value.bind( function( newval ) {
			var $cta = $( '.hester-pre-footer-cta' );

			if ( ! $cta.length ) {
				return;
			}

			hester_print_visibility_classes( $cta, newval );
		});
	});

	/**
	 * Pre Footer Call to Action Text.
	 */
	api( 'hester_pre_footer_cta_text', function( value ) {
		value.bind( function( newval ) {
			var $cta = $( '#hester-pre-footer .hester-pre-footer-cta' );

			if ( ! $cta.length ) {
				return;
			}

			$cta.find( 'p.h3' ).html( newval );
		});
	});

	/**
	 * Pre Footer Call to Action Style.
	 */
	api( 'hester_pre_footer_cta_style', function( value ) {
		value.bind( function( newval ) {
			$body
				.removeClass( function( index, className ) {
					return ( className.match( /(^|\s)hester-pre-footer-cta-style-\S+/g ) || []).join( ' ' );
				})
				.addClass( 'hester-pre-footer-cta-style-' + api.value( 'hester_pre_footer_cta_style' )() );
		});
	});

	/**
	 * Pre Footer Call to Action Button Text.
	 */
	api( 'hester_pre_footer_cta_btn_text', function( value ) {
		value.bind( function( newval ) {
			var $cta = $( '#hester-pre-footer .hester-pre-footer-cta' );

			if ( ! $cta.length ) {
				return;
			}

			if ( newval ) {
				$cta.find( 'a' ).css( 'display', 'inline-flex' ).html( newval );
			} else {
				$cta.find( 'a' ).css( 'display', 'none' ).html( '' );
			}
		});
	});

	/**
	 * Pre Footer Call to Action Background.
	 */
	api( 'hester_pre_footer_cta_background', function( value ) {
		value.bind( function( newval ) {
			var $cta = $( '#hester-pre-footer .hester-pre-footer-cta' );

			if ( ! $cta.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_pre_footer_cta_background' );
			var style_css = '';

			if ( 'color' === newval['background-type']) {
				style_css += hester_design_options_css( '.hester-pre-footer-cta-style-1 #hester-pre-footer .hester-flex-row::before, .hester-pre-footer-cta-style-2 #hester-pre-footer::before', newval, 'background' );
				style_css += '.hester-pre-footer-cta-style-1 #hester-pre-footer .hester-flex-row::after,' + '.hester-pre-footer-cta-style-2 #hester-pre-footer::after' + '{ background-image: none; }';
			} else {
				style_css += hester_design_options_css( '.hester-pre-footer-cta-style-1 #hester-pre-footer .hester-flex-row::after', newval, 'background' );
				style_css += hester_design_options_css( '.hester-pre-footer-cta-style-2 #hester-pre-footer::after', newval, 'background' );
			}

			if ( 'image' === newval['background-type'] && newval['background-color-overlay'] && newval['background-image']) {
				style_css += '.hester-pre-footer-cta-style-1 #hester-pre-footer .hester-flex-row::before,' + '.hester-pre-footer-cta-style-2 #hester-pre-footer::before' + '{ background-color: ' + newval['background-color-overlay'] + '; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Pre Footer Call to Action Text Color.
	 */
	api( 'hester_pre_footer_cta_text_color', function( value ) {
		value.bind( function( newval ) {
			var $cta = $( '#hester-pre-footer .hester-pre-footer-cta' );

			if ( ! $cta.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_pre_footer_cta_text_color' );
			var style_css = '';

			style_css += hester_design_options_css( '#hester-pre-footer .h2', newval, 'color' );
			style_css += hester_design_options_css( '#hester-pre-footer .h3', newval, 'color' );
			style_css += hester_design_options_css( '#hester-pre-footer .h4', newval, 'color' );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Pre Footer Call to Action Border.
	 */
	api( 'hester_pre_footer_cta_border', function( value ) {
		value.bind( function( newval ) {
			var $cta = $( '#hester-pre-footer .hester-pre-footer-cta' );

			if ( ! $cta.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_pre_footer_cta_border' );
			var style_css = hester_design_options_css( '.hester-pre-footer-cta-style-1 #hester-pre-footer .hester-flex-row::before, .hester-pre-footer-cta-style-2 #hester-pre-footer::before', newval, 'border' );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Pre Footer CTA font size.
	 */
	api( 'hester_pre_footer_cta_font_size', function( value ) {
		value.bind( function( newval ) {
			var $cta = $( '#hester-pre-footer .hester-pre-footer-cta' );

			if ( ! $cta.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_pre_footer_cta_font_size' );
			var style_css = hester_range_field_css( '#hester-pre-footer .h3', 'font-size', newval, true, newval.unit );

			$style_tag.html( style_css );
		});
	});

	/**
	 * WooCommerce sale badge text.
	 */
	api( 'hester_product_sale_badge_text', function( value ) {
		value.bind( function( newval ) {
			var $badge = $( '.woocommerce ul.products li.product .onsale, .woocommerce span.onsale' ).not( '.sold-out' );

			if ( ! $badge.length ) {
				return;
			}

			$badge.html( newval );
		});
	});

	/**
	 * Accent color.
	 */
	api( 'hester_accent_color', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_accent_color' );
			var style_css;

			// Colors.
			style_css =
				':root { ' +
				'--hester-primary: ' + newval + ';' +
				'--hester-primary_15: ' + hester_luminance( newval, 0.15 ) + ';' +
				'--hester-primary_09: ' + hester_hex2rgba( newval, 0.09 ) + ';' +
				'--hester-primary_04: ' + hester_hex2rgba( newval, 0.04 ) + ';' +
				'}';

			// Gradient.
			style_css +=
				'.hester-pre-footer-cta-style-1 #hester-pre-footer .hester-flex-row::before,' +
				'.hester-pre-footer-cta-style-2 #hester-pre-footer::before { ' +
				'background: linear-gradient(to right, ' +
				hester_hex2rgba( newval, 0.9 ) +
				' 0%, ' +
				hester_hex2rgba( newval, 0.82 ) +
				' 35%, ' +
				hester_hex2rgba( newval, 0.4 ) +
				' 100% );' +
				'-webkit-gradient(linear, left top, right top, from(' +
				hester_hex2rgba( newval, 0.9 ) +
				'), color-stop(35%, ' +
				hester_hex2rgba( newval, 0.82 ) +
				'), to(' +
				hester_hex2rgba( newval, 0.4 ) +
				')); }';

			$style_tag.html( style_css );
		});
	});

	/**
	 * Content background color.
	 */
	api( 'hester_boxed_content_background_color', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_boxed_content_background_color' );
			var style_css = '';

			if ( newval ) {
				style_css =
					'.hester-layout__boxed #page, ' +
					'.hester-layout__boxed-separated #content, ' +
					'.hester-layout__boxed-separated.hester-sidebar-style-3 #secondary .hester-widget, ' +
					'.hester-layout__boxed-separated.hester-sidebar-style-3 .elementor-widget-sidebar .hester-widget, ' +
					'.hester-layout__boxed-separated.blog .hester-article, ' +
					'.hester-layout__boxed-separated.search-results .hester-article, ' +
					'.hester-layout__boxed-separated.category .hester-article { background-color: ' +
					newval +
					'; }';

				style_css += '@media screen and (max-width: 960px) { ' + '.hester-layout__boxed-separated #page { background-color: ' + newval + '; }' + '}';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Content text color.
	 */
	api( 'hester_content_text_color', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_content_text_color' );
			var style_css = '';

			if ( newval ) {
				style_css =
					'body { ' +
					'color: ' +
					newval +
					';' +
					'}' +
					':root { ' +
					'--hester-secondary_38: ' +
					newval +
					';' +
					'}' +
					'.comment-form .comment-notes, ' +
					'#comments .no-comments, ' +
					'#page .wp-caption .wp-caption-text,' +
					'#comments .comment-meta,' +
					'.comments-closed,' +
					'.entry-meta,' +
					'.hester-entry cite,' +
					'legend,' +
					'.hester-page-header-description,' +
					'.page-links em,' +
					'.site-content .page-links em,' +
					'.single .entry-footer .last-updated,' +
					'.single .post-nav .post-nav-title,' +
					'#main .widget_recent_comments span,' +
					'#main .widget_recent_entries span,' +
					'#main .widget_calendar table > caption,' +
					'.post-thumb-caption, ' +
					'.wp-block-image figcaption, ' +
					'.hester-cart-item .hester-x,' +
					'.woocommerce form.login .lost_password a,' +
					'.woocommerce form.register .lost_password a,' +
					'.woocommerce a.remove,' +
					'#add_payment_method .cart-collaterals .cart_totals .woocommerce-shipping-destination, ' +
					'.woocommerce-cart .cart-collaterals .cart_totals .woocommerce-shipping-destination, ' +
					'.woocommerce-checkout .cart-collaterals .cart_totals .woocommerce-shipping-destination,' +
					'.woocommerce ul.products li.product .hester-loop-product__category-wrap a,' +
					'.woocommerce ul.products li.product .hester-loop-product__category-wrap,' +
					'.woocommerce .woocommerce-checkout-review-order table.shop_table thead th,' +
					'#add_payment_method #payment div.payment_box, ' +
					'.woocommerce-cart #payment div.payment_box, ' +
					'.woocommerce-checkout #payment div.payment_box,' +
					'#add_payment_method #payment ul.payment_methods .about_paypal, ' +
					'.woocommerce-cart #payment ul.payment_methods .about_paypal, ' +
					'.woocommerce-checkout #payment ul.payment_methods .about_paypal,' +
					'.woocommerce table dl,' +
					'.woocommerce table .wc-item-meta,' +
					'.widget.woocommerce .reviewer,' +
					'.woocommerce.widget_shopping_cart .cart_list li a.remove:before,' +
					'.woocommerce .widget_shopping_cart .cart_list li a.remove:before,' +
					'.woocommerce .widget_shopping_cart .cart_list li .quantity, ' +
					'.woocommerce.widget_shopping_cart .cart_list li .quantity,' +
					'.woocommerce div.product .woocommerce-product-rating .woocommerce-review-link,' +
					'.woocommerce div.product .woocommerce-tabs table.shop_attributes td,' +
					'.woocommerce div.product .product_meta > span span:not(.hester-woo-meta-title), ' +
					'.woocommerce div.product .product_meta > span a,' +
					'.woocommerce .star-rating::before,' +
					'.woocommerce div.product #reviews #comments ol.commentlist li .comment-text p.meta,' +
					'.ywar_review_count,' +
					'.woocommerce .add_to_cart_inline del, ' +
					'.woocommerce div.product p.price del, ' +
					'.woocommerce div.product span.price del { color: ' +
					hester_hex2rgba( newval, 0.75 ) +
					'; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Content link hover color.
	 */
	api( 'hester_content_link_hover_color', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_content_link_hover_color' );
			var style_css = '';

			if ( newval ) {

				// Content link hover.
				style_css +=
					'.content-area a:not(.hester-btn):not(.wp-block-button__link):hover, ' +
					'.hester-woo-before-shop select.custom-select-loaded:hover ~ #hester-orderby, ' +
					'#add_payment_method #payment ul.payment_methods .about_paypal:hover, ' +
					'.woocommerce-cart #payment ul.payment_methods .about_paypal:hover, ' +
					'.woocommerce-checkout #payment ul.payment_methods .about_paypal:hover, ' +
					'.hester-breadcrumbs a:hover, ' +
					'.woocommerce div.product .woocommerce-product-rating .woocommerce-review-link:hover, ' +
					'.woocommerce ul.products li.product .meta-wrap .woocommerce-loop-product__link:hover, ' +
					'.woocommerce ul.products li.product .hester-loop-product__category-wrap a:hover { ' +
					'color: ' +
					newval +
					';' +
					'}';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Content text color.
	 */
	api( 'hester_headings_color', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_headings_color' );
			var style_css = '';

			if ( newval ) {
				style_css = 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .hester-logo .site-title, .error-404 .page-header h1 { ' + 'color: ' + newval + ';' + '} :root { ' + '--hester-secondary: ' + newval + ';' + '}';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Scroll Top visibility.
	 */
	api( 'hester_scroll_top_visibility', function( value ) {
		value.bind( function( newval ) {
			hester_print_visibility_classes( $( '#hester-scroll-top' ), newval );
		});
	});

	/**
	 * Page Preloader visibility.
	 */
	api( 'hester_preloader_visibility', function( value ) {
		value.bind( function( newval ) {
			hester_print_visibility_classes( $( '#hester-preloader' ), newval );
		});
	});

	/**
	 * Footer visibility.
	 */
	api( 'hester_footer_visibility', function( value ) {
		value.bind( function( newval ) {
			hester_print_visibility_classes( $( '#hester-footer' ), newval );
		});
	});

	/**
	 * Footer Widget Heading Style Enable.
	 */
	 api( 'hester_footer_widget_heading_style', function( value ) {
		value.bind( function( newval ) {
			$body
				.removeClass( function( index, className ) {
					return ( className.match( /(^|\s)is-footer-heading-init-s\S+/g ) || []).join( ' ' );
				})
				.addClass( 'is-footer-heading-init-s' + api.value( 'hester_footer_widget_heading_style' )() );
		});
	});

	/**
	 * Footer background.
	 */
	api( 'hester_footer_background', function( value ) {
		value.bind( function( newval ) {
			var $footer = $( '#colophon' );

			if ( ! $footer.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_footer_background' );
			var style_css = hester_design_options_css( '#colophon', newval, 'background' );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Footer font color.
	 */
	api( 'hester_footer_text_color', function( value ) {
		var $footer = $( '#hester-footer' ),
			copyright_separator_color,
			style_css;

		value.bind( function( newval ) {
			if ( ! $footer.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_footer_text_color' );

			style_css = '';

			newval['text-color'] = newval['text-color'] ? newval['text-color'] : 'inherit';
			newval['link-color'] = newval['link-color'] ? newval['link-color'] : 'inherit';
			newval['link-hover-color'] = newval['link-hover-color'] ? newval['link-hover-color'] : 'inherit';
			newval['widget-title-color'] = newval['widget-title-color'] ? newval['widget-title-color'] : 'inherit';

			// Text color.
			style_css += '#colophon { color: ' + newval['text-color'] + '; }';

			// Link color.
			style_css += '#colophon a { color: ' + newval['link-color'] + '; }';

			// Link hover color.
			style_css += '#colophon a:hover, #colophon li.current_page_item > a, #colophon .hester-social-nav > ul > li > a .hester-icon.bottom-icon ' + '{ color: ' + newval['link-hover-color'] + '; }';

			// Widget title color.
			style_css += '#colophon .widget-title { color: ' + newval['widget-title-color'] + '; }';

			// Copyright separator color.
			copyright_separator_color = hester_light_or_dark( newval['text-color'], 'rgba(255,255,255,0.1)', 'rgba(0,0,0,0.1)' );

			// copyright_separator_color = hester_luminance( newval['text-color'], 0.8 );

			style_css += '#hester-copyright.contained-separator > .hester-container:before { background-color: ' + copyright_separator_color + '; }';
			style_css += '#hester-copyright.fw-separator { border-top-color: ' + copyright_separator_color + '; }';

			$style_tag.html( style_css );
		});
	});

	/**
	 * Footer border.
	 */
	api( 'hester_footer_border', function( value ) {
		value.bind( function( newval ) {
			var $footer = $( '#hester-footer' );

			if ( ! $footer.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_footer_border' );
			var style_css = '';

			if ( newval['border-top-width']) {
				style_css += '#colophon { ' + 'border-top-width: ' + newval['border-top-width'] + 'px;' + 'border-top-style: ' + newval['border-style'] + ';' + 'border-top-color: ' + newval['border-color'] + ';' + '}';
			}

			if ( newval['border-bottom-width']) {
				style_css += '#colophon { ' + 'border-bottom-width: ' + newval['border-bottom-width'] + 'px;' + 'border-bottom-style: ' + newval['border-style'] + ';' + 'border-bottom-color: ' + newval['border-color'] + ';' + '}';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Copyright layout.
	 */
	api( 'hester_copyright_layout', function( value ) {
		value.bind( function( newval ) {
			$body.removeClass( function( index, className ) {
				return ( className.match( /(^|\s)hester-copyright-layout-\S+/g ) || []).join( ' ' );
			});

			$body.addClass( 'hester-copyright-' + newval );
		});
	});

	/**
	 * Copyright separator.
	 */
	api( 'hester_copyright_separator', function( value ) {
		value.bind( function( newval ) {
			var $copyright = $( '#hester-copyright' );

			if ( ! $copyright.length ) {
				return;
			}

			$copyright.removeClass( 'fw-separator contained-separator' );

			if ( 'none' !== newval ) {
				$copyright.addClass( newval );
			}
		});
	});

	/**
	 * Copyright visibility.
	 */
	api( 'hester_copyright_visibility', function( value ) {
		value.bind( function( newval ) {
			hester_print_visibility_classes( $( '#hester-copyright' ), newval );
		});
	});

	/**
	 * Copyright background.
	 */
	api( 'hester_copyright_background', function( value ) {
		value.bind( function( newval ) {
			var $copyright = $( '#hester-copyright' );

			if ( ! $copyright.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_copyright_background' );
			var style_css = hester_design_options_css( '#hester-copyright', newval, 'background' );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Copyright text color.
	 */
	api( 'hester_copyright_text_color', function( value ) {
		value.bind( function( newval ) {
			var $copyright = $( '#hester-copyright' );

			if ( ! $copyright.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_copyright_text_color' );
			var style_css = '';

			newval['text-color'] = newval['text-color'] ? newval['text-color'] : 'inherit';
			newval['link-color'] = newval['link-color'] ? newval['link-color'] : 'inherit';
			newval['link-hover-color'] = newval['link-hover-color'] ? newval['link-hover-color'] : 'inherit';

			// Text color.
			style_css += '#hester-copyright { color: ' + newval['text-color'] + '; }';

			// Link color.
			style_css += '#hester-copyright a { color: ' + newval['link-color'] + '; }';

			// Link hover color.
			style_css +=
				'#hester-copyright a:hover, #hester-copyright .hester-social-nav > ul > li > a .hester-icon.bottom-icon, #hester-copyright li.current_page_item > a, #hester-copyright .hester-nav > ul > li.current-menu-item > a, #hester-copyright .hester-nav > ul > li.current-menu-ancestor > a #hester-copyright .hester-nav > ul > li:hover > a, #hester-copyright .hester-social-nav > ul > li > a .hester-icon.bottom-icon { color: ' +
				newval['link-hover-color'] +
				'; }';

			$style_tag.html( style_css );
		});
	});

	/**
	 * Container width.
	 */
	api( 'hester_container_width', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_container_width' );
			var style_css;

			style_css = '.hester-container,' + '.alignfull > div { ' + 'max-width: ' + newval.value + 'px;' + '}';

			style_css +=
				'.hester-layout__boxed #page, .hester-layout__boxed.hester-sticky-header.hester-is-mobile #hester-header-inner, ' +
				'.hester-layout__boxed.hester-sticky-header:not(.hester-header-layout-3) #hester-header-inner, ' +
				'.hester-layout__boxed.hester-sticky-header:not(.hester-is-mobile).hester-header-layout-3 #hester-header-inner .hester-nav-container > .hester-container { max-width: ' +
				( parseInt( newval.value ) + 100 ) +
				'px; }';

			$style_tag.html( style_css );
		});
	});

	/**
	 * Transparent Header Logo max height.
	 */
	api( 'hester_tsp_logo_max_height', function( value ) {
		value.bind( function( newval ) {
			var $logo = $( '.hester-tsp-header .hester-logo' );

			if ( ! $logo.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_tsp_logo_max_height' );
			var style_css = '';

			style_css += hester_range_field_css( '.hester-tsp-header .hester-logo img', 'max-height', newval, true, 'px' );
			style_css += hester_range_field_css( '.hester-tsp-header .hester-logo img.hester-svg-logo', 'height', newval, true, 'px' );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Transparent Header Logo margin.
	 */
	api( 'hester_tsp_logo_margin', function( value ) {
		value.bind( function( newval ) {
			var $logo = $( '.hester-tsp-header .hester-logo' );

			if ( ! $logo.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_tsp_logo_margin' );

			var style_css = hester_spacing_field_css( '.hester-tsp-header .hester-logo .logo-inner', 'margin', newval, true );
			$style_tag.html( style_css );
		});
	});

	/**
	 * Transparent header - Main Header & Topbar background.
	 */
	api( 'hester_tsp_header_background', function( value ) {
		value.bind( function( newval ) {
			var $tsp_header = $( '.hester-tsp-header' );

			if ( ! $tsp_header.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_tsp_header_background' );

			var style_css = '';
			style_css += hester_design_options_css( '.hester-tsp-header #hester-header-inner', newval, 'background' );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Transparent header - Main Header & Topbar font color.
	 */
	api( 'hester_tsp_header_font_color', function( value ) {
		value.bind( function( newval ) {
			var $tsp_header = $( '.hester-tsp-header' );

			if ( ! $tsp_header.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_tsp_header_font_color' );

			var style_css = '';

			newval['text-color'] = newval['text-color'] ? newval['text-color'] : 'inherit';
			newval['link-color'] = newval['link-color'] ? newval['link-color'] : 'inherit';
			newval['link-hover-color'] = newval['link-hover-color'] ? newval['link-hover-color'] : 'inherit';

			/** Header **/

			// Text color.
			style_css += '.hester-tsp-header .hester-logo .site-description { color: ' + newval['text-color'] + '; }';

			// Link color.
			if ( newval['link-color']) {
				style_css += '.hester-tsp-header #hester-header, ' + '.hester-tsp-header .hester-header-widgets a:not(.hester-btn), ' + '.hester-tsp-header .hester-logo a,' + '.hester-tsp-header .hester-hamburger, ' + '.hester-tsp-header #hester-header-inner .hester-nav > ul > li > a { color: ' + newval['link-color'] + '; }';
				style_css += '.hester-tsp-header .hamburger-inner,' + '.hester-tsp-header .hamburger-inner::before,' + '.hester-tsp-header .hamburger-inner::after { background-color: ' + newval['link-color'] + '; }';
			}

			// Link hover color.
			if ( newval['link-hover-color']) {
				style_css +=
					'.hester-tsp-header .hester-header-widgets a:not(.hester-btn):hover, ' +
					'.hester-tsp-header #hester-header-inner .hester-header-widgets .hester-active,' +
					'.hester-tsp-header .hester-logo .site-title a:hover, ' +
					'.hester-tsp-header .hester-hamburger:hover .hamburger-label, ' +
					'.is-mobile-menu-active .hester-tsp-header .hester-hamburger .hamburger-label,' +
					'.hester-tsp-header.using-keyboard .site-title a:focus,' +
					'.hester-tsp-header.using-keyboard .hester-header-widgets a:not(.hester-btn):focus,' +
					'.hester-tsp-header #hester-header-inner .hester-nav > ul > li.hovered > a,' +
					'.hester-tsp-header #hester-header-inner .hester-nav > ul > li > a:hover,' +
					'.hester-tsp-header #hester-header-inner .hester-nav > ul > li.menu-item-has-children:hover > a,' +
					'.hester-tsp-header #hester-header-inner .hester-nav > ul > li.current-menu-item > a,' +
					'.hester-tsp-header #hester-header-inner .hester-nav > ul > li.current-menu-ancestor > a,' +
					'.hester-tsp-header #hester-header-inner .hester-nav > ul > li.page_item_has_children:hover > a,' +
					'.hester-tsp-header #hester-header-inner .hester-nav > ul > li.current_page_item > a,' +
					'.hester-tsp-header #hester-header-inner .hester-nav > ul > li.current_page_ancestor > a { color: ' +
					newval['link-hover-color'] +
					'; }';

				style_css +=
					'.hester-tsp-header .hester-hamburger:hover .hamburger-inner,' +
					'.hester-tsp-header .hester-hamburger:hover .hamburger-inner::before,' +
					'.hester-tsp-header .hester-hamburger:hover .hamburger-inner::after,' +
					'.is-mobile-menu-active .hester-tsp-header .hester-hamburger .hamburger-inner,' +
					'.is-mobile-menu-active .hester-tsp-header .hester-hamburger .hamburger-inner::before,' +
					'.is-mobile-menu-active .hester-tsp-header .hester-hamburger .hamburger-inner::after { background-color: ' +
					newval['link-hover-color'] +
					'; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Transparent header - Main Header & Topbar border.
	 */
	api( 'hester_tsp_header_border', function( value ) {
		value.bind( function( newval ) {
			var $tsp_header = $( '.hester-tsp-header' );

			if ( ! $tsp_header.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_tsp_header_border' );

			var style_css = '';

			style_css += hester_design_options_css( '.hester-tsp-header #hester-header-inner', newval, 'border' );

			// Separator color.
			newval['separator-color'] = newval['separator-color'] ? newval['separator-color'] : 'inherit';
			style_css += '.hester-tsp-header .hester-header-widget:after { background-color: ' + newval['separator-color'] + '; }';

			$style_tag.html( style_css );
		});
	});

	/**
	 * Page Header layout.
	 */
	api( 'hester_page_header_alignment', function( value ) {
		value.bind( function( newval ) {
			if ( $body.hasClass( 'single-post' ) ) {
				return;
			}

			$body.removeClass( function( index, className ) {
				return ( className.match( /(^|\s)hester-page-title-align-\S+/g ) || []).join( ' ' );
			});

			$body.addClass( 'hester-page-title-align-' + newval );
		});
	});

	/**
	 * Page Header spacing.
	 */
	api( 'hester_page_header_spacing', function( value ) {
		value.bind( function( newval ) {
			var $page_header = $( '.page-header' );

			if ( ! $page_header.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_page_header_spacing' );

			var style_css = hester_spacing_field_css( '.hester-page-title-align-left .page-header.hester-has-page-title, .hester-page-title-align-right .page-header.hester-has-page-title, .hester-page-title-align-center .page-header .hester-page-header-wrapper', 'padding', newval, true );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Page Header background.
	 */
	api( 'hester_page_header_background', function( value ) {
		value.bind( function( newval ) {
			var $page_header = $( '.page-header' );

			if ( ! $page_header.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_page_header_background' );

			var style_css = '';
			style_css += hester_design_options_css( '.page-header', newval, 'background' );
			style_css += hester_design_options_css( '.hester-tsp-header:not(.hester-tsp-absolute) #masthead', newval, 'background' );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Header Text color.
	 */
	api( 'hester_page_header_text_color', function( value ) {
		value.bind( function( newval ) {
			var $page_header = $( '.page-header' );

			if ( ! $page_header.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_page_header_text_color' );
			var style_css = '';

			newval['text-color'] = newval['text-color'] ? newval['text-color'] : 'inherit';
			newval['link-color'] = newval['link-color'] ? newval['link-color'] : 'inherit';
			newval['link-hover-color'] = newval['link-hover-color'] ? newval['link-hover-color'] : 'inherit';

			// Text color.
			style_css += '.page-header .page-title { color: ' + newval['text-color'] + '; }';
			style_css += '.page-header .hester-page-header-description' + '{ color: ' + hester_hex2rgba( newval['text-color'], 0.75 ) + '}';

			// Link color.
			style_css += '.page-header .hester-breadcrumbs a' + '{ color: ' + newval['link-color'] + '; }';

			style_css += '.page-header .hester-breadcrumbs span,' + '.page-header .breadcrumb-trail .trail-items li::after, .page-header .hester-breadcrumbs .separator' + '{ color: ' + hester_hex2rgba( newval['link-color'], 0.75 ) + '}';

			$style_tag.html( style_css );
		});
	});

	/**
	 * Page Header border.
	 */
	api( 'hester_page_header_border', function( value ) {
		value.bind( function( newval ) {
			var $page_header = $( '.page-header' );

			if ( ! $page_header.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_page_header_border' );
			var style_css = hester_design_options_css( '.page-header', newval, 'border' );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Breadcrumbs alignment.
	 */
	api( 'hester_breadcrumbs_alignment', function( value ) {
		value.bind( function( newval ) {
			var $breadcrumbs = $( '#main > .hester-breadcrumbs > .hester-container' );

			if ( ! $breadcrumbs.length ) {
				return;
			}

			$breadcrumbs.removeClass( function( index, className ) {
				return ( className.match( /(^|\s)hester-text-align\S+/g ) || []).join( ' ' );
			});

			$breadcrumbs.addClass( 'hester-text-align-' + newval );
		});
	});

	/**
	 * Breadcrumbs spacing.
	 */
	api( 'hester_breadcrumbs_spacing', function( value ) {
		value.bind( function( newval ) {
			var $breadcrumbs = $( '.hester-breadcrumbs' );

			if ( ! $breadcrumbs.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_breadcrumbs_spacing' );

			var style_css = hester_spacing_field_css( '.hester-breadcrumbs', 'padding', newval, true );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Breadcrumbs Background.
	 */
	api( 'hester_breadcrumbs_background', function( value ) {
		value.bind( function( newval ) {
			var $breadcrumbs = $( '.hester-breadcrumbs' );

			if ( ! $breadcrumbs.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_breadcrumbs_background' );
			var style_css = hester_design_options_css( '.hester-breadcrumbs', newval, 'background' );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Breadcrumbs Text Color.
	 */
	api( 'hester_breadcrumbs_text_color', function( value ) {
		value.bind( function( newval ) {
			var $breadcrumbs = $( '.hester-breadcrumbs' );

			if ( ! $breadcrumbs.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_breadcrumbs_text_color' );
			var style_css = hester_design_options_css( '.hester-breadcrumbs', newval, 'color' );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Breadcrumbs Border.
	 */
	api( 'hester_breadcrumbs_border', function( value ) {
		value.bind( function( newval ) {
			var $breadcrumbs = $( '.hester-breadcrumbs' );

			if ( ! $breadcrumbs.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_breadcrumbs_border' );
			var style_css = hester_design_options_css( '.hester-breadcrumbs', newval, 'border' );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Base HTML font size.
	 */
	api( 'hester_html_base_font_size', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_html_base_font_size' );
			var style_css = hester_range_field_css( 'html', 'font-size', newval, true, '%' );
			$style_tag.html( style_css );
		});
	});

	/**
	 * Font smoothing.
	 */
	api( 'hester_font_smoothing', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_font_smoothing' );

			if ( newval ) {
				$style_tag.html( '*,' + '*::before,' + '*::after {' + '-moz-osx-font-smoothing: grayscale;' + '-webkit-font-smoothing: antialiased;' + '}' );
			} else {
				$style_tag.html( '*,' + '*::before,' + '*::after {' + '-moz-osx-font-smoothing: auto;' + '-webkit-font-smoothing: auto;' + '}' );
			}

			$style_tag = hester_get_style_tag( 'hester_html_base_font_size' );
			var style_css = hester_range_field_css( 'html', 'font-size', newval, true, '%' );
			$style_tag.html( style_css );
		});
	});

	/**
	 * Body font.
	 */
	api( 'hester_body_font', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_body_font' );
			var style_css = hester_typography_field_css( 'body', newval );

			hester_enqueue_google_font( newval['font-family']);

			$style_tag.html( style_css );
		});
	});

	/**
	 * Headings font.
	 */
	api( 'hester_headings_font', function( value ) {
		var style_css, selector;
		value.bind( function( newval ) {
			selector = 'h1, .h1, .hester-logo .site-title, .page-header h1.page-title';
			selector += ', h2, .h2, .woocommerce div.product h1.product_title';
			selector += ', h3, .h3, .woocommerce #reviews #comments h2';
			selector += ', h4, .h4, .woocommerce .cart_totals h2, .woocommerce .cross-sells > h4, .woocommerce #reviews #respond .comment-reply-title';
			selector += ', h5, h6, .h5, .h6';

			style_css = hester_typography_field_css( selector, newval );

			hester_enqueue_google_font( newval['font-family']);

			$style_tag = hester_get_style_tag( 'hester_headings_font' );
			$style_tag.html( style_css );
		});
	});

	/**
	 * Heading 1 font.
	 */
	api( 'hester_h1_font', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_h1_font' );

			var style_css = hester_typography_field_css( 'h1, .h1, .hester-logo .site-title, .page-header h1.page-title', newval );

			hester_enqueue_google_font( newval['font-family']);

			$style_tag.html( style_css );
		});
	});

	/**
	 * Heading 2 font.
	 */
	api( 'hester_h2_font', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_h2_font' );

			var style_css = hester_typography_field_css( 'h2, .h2, .woocommerce div.product h1.product_title', newval );

			hester_enqueue_google_font( newval['font-family']);

			$style_tag.html( style_css );
		});
	});

	/**
	 * Heading 3 font.
	 */
	api( 'hester_h3_font', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_h3_font' );

			var style_css = hester_typography_field_css( 'h3, .h3, .woocommerce #reviews #comments h2', newval );

			hester_enqueue_google_font( newval['font-family']);

			$style_tag.html( style_css );
		});
	});

	/**
	 * Heading 4 font.
	 */
	api( 'hester_h4_font', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_h4_font' );

			var style_css = hester_typography_field_css( 'h4, .h4, .woocommerce .cart_totals h2, .woocommerce .cross-sells > h4, .woocommerce #reviews #respond .comment-reply-title', newval );

			hester_enqueue_google_font( newval['font-family']);

			$style_tag.html( style_css );
		});
	});

	/**
	 * Heading 5 font.
	 */
	api( 'hester_h5_font', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_h5_font' );
			var style_css = hester_typography_field_css( 'h5, .h5', newval );

			hester_enqueue_google_font( newval['font-family']);

			$style_tag.html( style_css );
		});
	});

	/**
	 * Heading 6 font.
	 */
	api( 'hester_h6_font', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_h6_font' );
			var style_css = hester_typography_field_css( 'h6, .h6', newval );

			hester_enqueue_google_font( newval['font-family']);

			$style_tag.html( style_css );
		});
	});

	/**
	 * Heading emphasized font.
	 */
	api( 'hester_heading_em_font', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_heading_em_font' );
			var style_css = hester_typography_field_css( 'h1 em, h2 em, h3 em, h4 em, h5 em, h6 em, .h1 em, .h2 em, .h3 em, .h4 em, .h5 em, .h6 em, .hester-logo .site-title em, .error-404 .page-header h1 em', newval );

			hester_enqueue_google_font( newval['font-family']);

			$style_tag.html( style_css );
		});
	});

	/**
	 * Sidebar widget title font size.
	 */
	api( 'hester_sidebar_widget_title_font_size', function( value ) {
		value.bind( function( newval ) {
			var $widget_title = $( '#main .widget-title' );

			if ( ! $widget_title.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_sidebar_widget_title_font_size' );
			var style_css = '';

			style_css += hester_range_field_css( '#main .widget-title', 'font-size', newval, true, newval.unit );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Footer widget title font size.
	 */
	api( 'hester_footer_widget_title_font_size', function( value ) {
		value.bind( function( newval ) {
			var $widget_title = $( '#colophon .widget-title' );

			if ( ! $widget_title.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_footer_widget_title_font_size' );
			var style_css = '';

			style_css += hester_range_field_css( '#colophon .widget-title', 'font-size', newval, true, newval.unit );

			$style_tag.html( style_css );
		});
	});

	/**
	 * Page title font size.
	 */
	api( 'hester_page_header_font_size', function( value ) {
		value.bind( function( newval ) {
			var $page_title = $( '.page-header .page-title' );

			if ( ! $page_title.length ) {
				return;
			}

			$style_tag = hester_get_style_tag( 'hester_page_header_font_size' );
			var style_css = '';

			style_css += hester_range_field_css( '#page .page-header .page-title', 'font-size', newval, true, newval.unit );

			$style_tag.html( style_css );
		});
	});

	var $btn_selectors =
		'.hester-btn, ' +
		'body:not(.wp-customizer) input[type=submit], ' +
		'.site-main .woocommerce #respond input#submit, ' +
		'.site-main .woocommerce a.button, ' +
		'.site-main .woocommerce button.button, ' +
		'.site-main .woocommerce input.button, ' +
		'.woocommerce ul.products li.product .added_to_cart, ' +
		'.woocommerce ul.products li.product .button, ' +
		'.woocommerce div.product form.cart .button, ' +
		'.woocommerce #review_form #respond .form-submit input, ' +
		'#infinite-handle span';

	var $btn_hover_selectors =
		'.hester-btn:hover, ' +
		'.hester-btn:focus, ' +
		'body:not(.wp-customizer) input[type=submit]:hover, ' +
		'body:not(.wp-customizer) input[type=submit]:focus, ' +
		'.site-main .woocommerce #respond input#submit:hover, ' +
		'.site-main .woocommerce #respond input#submit:focus, ' +
		'.site-main .woocommerce a.button:hover, ' +
		'.site-main .woocommerce a.button:focus, ' +
		'.site-main .woocommerce button.button:hover, ' +
		'.site-main .woocommerce button.button:focus, ' +
		'.site-main .woocommerce input.button:hover, ' +
		'.site-main .woocommerce input.button:focus, ' +
		'.woocommerce ul.products li.product .added_to_cart:hover, ' +
		'.woocommerce ul.products li.product .added_to_cart:focus, ' +
		'.woocommerce ul.products li.product .button:hover, ' +
		'.woocommerce ul.products li.product .button:focus, ' +
		'.woocommerce div.product form.cart .button:hover, ' +
		'.woocommerce div.product form.cart .button:focus, ' +
		'.woocommerce #review_form #respond .form-submit input:hover, ' +
		'.woocommerce #review_form #respond .form-submit input:focus, ' +
		'#infinite-handle span:hover';

	/**
	 * Primary button background color.
	 */
	api( 'hester_primary_button_bg_color', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_primary_button_bg_color' );
			var style_css = '';

			if ( newval ) {
				style_css = $btn_selectors + '{ background-color: ' + newval + '; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Primary button hover background color.
	 */
	api( 'hester_primary_button_hover_bg_color', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_primary_button_hover_bg_color' );
			var style_css = '';

			if ( newval ) {
				style_css = $btn_hover_selectors + ' { background-color: ' + newval + '; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Primary button text color.
	 */
	api( 'hester_primary_button_text_color', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_primary_button_text_color' );
			var style_css = '';

			if ( newval ) {
				style_css = $btn_selectors + ' { color: ' + newval + '; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Primary button hover text color.
	 */
	api( 'hester_primary_button_hover_text_color', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_primary_button_hover_text_color' );
			var style_css = '';

			if ( newval ) {
				style_css = $btn_hover_selectors + ' { color: ' + newval + '; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Primary button border width.
	 */
	api( 'hester_primary_button_border_width', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_primary_button_border_width' );
			var style_css = '';

			if ( newval ) {
				style_css = $btn_selectors + ' { border-width: ' + newval.value + 'rem; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Primary button border radius.
	 */
	api( 'hester_primary_button_border_radius', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_primary_button_border_radius' );
			var style_css = '';

			if ( newval ) {
				style_css = $btn_selectors + ' { ' + 'border-top-left-radius: ' + newval['top-left'] + 'rem;' + 'border-top-right-radius: ' + newval['top-right'] + 'rem;' + 'border-bottom-left-radius: ' + newval['bottom-left'] + 'rem;' + 'border-bottom-right-radius: ' + newval['bottom-right'] + 'rem; }';

				console.log( style_css );
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Primary button border color.
	 */
	api( 'hester_primary_button_border_color', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_primary_button_border_color' );
			var style_css = '';

			if ( newval ) {
				style_css = $btn_selectors + ' { border-color: ' + newval + '; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Primary button hover border color.
	 */
	api( 'hester_primary_button_hover_border_color', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_primary_button_hover_border_color' );
			var style_css = '';

			if ( newval ) {
				style_css = $btn_hover_selectors + ' { border-color: ' + newval + '; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Primary button typography.
	 */
	api( 'hester_primary_button_typography', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_primary_button_typography' );
			var style_css = hester_typography_field_css( $btn_selectors, newval );

			hester_enqueue_google_font( newval['font-family']);

			$style_tag.html( style_css );
		});
	});

	// Secondary button.
	var $btn_sec_selectors = '.btn-secondary, .hester-btn.btn-secondary';

	var $btn_sec_hover_selectors = '.btn-secondary:hover, ' + '.btn-secondary:focus, ' + '.hester-btn.btn-secondary:hover, ' + '.hester-btn.btn-secondary:focus';

	/**
	 * Secondary button background color.
	 */
	api( 'hester_secondary_button_bg_color', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_secondary_button_bg_color' );
			var style_css = '';

			if ( newval ) {
				style_css = $btn_sec_selectors + '{ background-color: ' + newval + '; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Secondary button hover background color.
	 */
	api( 'hester_secondary_button_hover_bg_color', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_secondary_button_hover_bg_color' );
			var style_css = '';

			if ( newval ) {
				style_css = $btn_sec_hover_selectors + '{ background-color: ' + newval + '; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Secondary button text color.
	 */
	api( 'hester_secondary_button_text_color', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_secondary_button_text_color' );
			var style_css = '';

			if ( newval ) {
				style_css = $btn_sec_selectors + '{ color: ' + newval + '; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Secondary button hover text color.
	 */
	api( 'hester_secondary_button_hover_text_color', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_secondary_button_hover_text_color' );
			var style_css = '';

			if ( newval ) {
				style_css = $btn_sec_hover_selectors + '{ color: ' + newval + '; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Secondary button border width.
	 */
	api( 'hester_secondary_button_border_width', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_secondary_button_border_width' );
			var style_css = '';

			if ( newval ) {
				style_css = $btn_sec_selectors + ' { border-width: ' + newval.value + 'rem; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Secondary button border radius.
	 */
	api( 'hester_secondary_button_border_radius', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_secondary_button_border_radius' );
			var style_css = '';

			if ( newval ) {
				style_css = $btn_sec_selectors + ' { ' + 'border-top-left-radius: ' + newval['top-left'] + 'rem;' + 'border-top-right-radius: ' + newval['top-right'] + 'rem;' + 'border-bottom-left-radius: ' + newval['bottom-left'] + 'rem;' + 'border-bottom-right-radius: ' + newval['bottom-right'] + 'rem; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Secondary button border color.
	 */
	api( 'hester_secondary_button_border_color', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_secondary_button_border_color' );
			var style_css = '';

			if ( newval ) {
				style_css = $btn_sec_selectors + ' { border-color: ' + newval + '; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Secondary button hover border color.
	 */
	api( 'hester_secondary_button_hover_border_color', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_secondary_button_hover_border_color' );
			var style_css = '';

			if ( newval ) {
				style_css = $btn_sec_hover_selectors + ' { border-color: ' + newval + '; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Secondary button typography.
	 */
	api( 'hester_secondary_button_typography', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_secondary_button_typography' );
			var style_css = hester_typography_field_css( $btn_sec_selectors, newval );

			hester_enqueue_google_font( newval['font-family']);

			$style_tag.html( style_css );
		});
	});

	// Text button.
	var $btn_text_selectors = '.hester-btn.btn-text-1, .btn-text-1';

	var $btn_text_hover_selectors = '.hester-btn.btn-text-1:hover, .hester-btn.btn-text-1:focus, .btn-text-1:hover, .btn-text-1:focus';

	/**
	 * Text button text color.
	 */
	api( 'hester_text_button_text_color', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_text_button_text_color' );
			var style_css = '';

			if ( newval ) {
				style_css = $btn_text_selectors + '{ color: ' + newval + '; }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Text button hover text color.
	 */
	api( 'hester_text_button_hover_text_color', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_text_button_hover_text_color' );
			var style_css = '';

			if ( newval ) {
				style_css = $btn_text_hover_selectors + '{ color: ' + newval + '; }';
				style_css += '.hester-btn.btn-text-1 > span::before { background-color: ' + newval + ' }';
			}

			$style_tag.html( style_css );
		});
	});

	/**
	 * Text button typography.
	 */
	api( 'hester_text_button_typography', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_text_button_typography' );
			var style_css = hester_typography_field_css( $btn_text_selectors, newval );

			hester_enqueue_google_font( newval['font-family']);

			$style_tag.html( style_css );
		});
	});

	/**
	 * Section Heading Style Enable.
	 */
	api( 'hester_section_heading_style', function( value ) {
		value.bind( function( newval ) {
			$body
				.removeClass( function( index, className ) {
					return ( className.match( /(^|\s)is-section-heading-init-s\S+/g ) || []).join( ' ' );
				})
				.addClass( 'is-section-heading-init-s' + api.value( 'hester_section_heading_style' )() );
		});
	});

	/**
	 * Slider Heading Title font.
	 */
	api( 'hester_slider_title_font', function( value ) {
		value.bind( function( newval ) {
			$style_tag = hester_get_style_tag( 'hester_slider_title_font' );
			var style_css = hester_typography_field_css( '.starter__slider .starter__slider-title', newval );

			hester_enqueue_google_font( newval['font-family']);

			$style_tag.html( style_css );
		});
	});

	/**
	 *  Common Section Style
	 */
	var home_design_sections = hester_customizer_preview.home_design_sections;
	home_design_sections.map( function( section ) {

		/**
		 * Section spacing
		 */
		api( 'hester_' + section + '_section_spacing', function( value ) {
			value.bind( function( newval ) {

				var $section = $( '.hester_section_' + section );

				if ( ! $section.length ) {
					return;
				}

				$style_tag = hester_get_style_tag( 'hester_' + section + '_section_spacing' );

				var style_css = hester_spacing_field_css( '.hester_section_' + section + ' .hester_bg', 'padding', newval, true );
				$style_tag.html( style_css );
			});
		});

		/**
		 * Section container width.
		 */
		api( 'hester_' + section + '_container_width', function( value ) {
			value.bind( function( newval ) {
				var $section = $( '.hester_section_' + section );

				if ( ! $section.length ) {
					return;
				}

				if ( 'full-width' === newval ) {
					$section.find( '.hester-container' ).addClass( 'hester-container__wide' );
				} else {
					$section.find( '.hester-container' ).removeClass( 'hester-container__wide' );
				}
			});
		});

		/**
		 * Section Background.
		 */
		api( 'hester_' + section + '_background', function( value ) {
			value.bind( function( newval ) {
				var features = $( '.hester_section_' + section );

				if ( ! features.length ) {
					return;
				}

				$style_tag = hester_get_style_tag( 'hester_' + section + '_background' );
				var style_css = '';

				if ( 'color' === newval['background-type']) {
					style_css += hester_design_options_css( '.hester_section_' + section + ' .hester_bg::before', newval, 'background' );
					style_css += '.hester_section_' + section + ' .hester_bg::after' + '{ background-image: none; }';
				} else {
					style_css += hester_design_options_css( '.hester_section_' + section + ' .hester_bg::after', newval, 'background' );
					style_css += '.hester_section_' + section + ' .hester_bg::before' + '{ background-color: transparent; }';
				}

				if ( 'image' === newval['background-type'] && newval['background-color-overlay'] && newval['background-image']) {
					style_css += '.hester_section_' + section + ' .hester_bg::before' + '{ background-color: ' + newval['background-color-overlay'] + '; }';
				}

				$style_tag.html( style_css );
			});
		});


		/**
		 * Section Font color.
		 */
		 api( 'hester_' + section + '_text_color', function( value ) {
			value.bind( function( newval ) {
				var features = $( '.hester_section_' + section );

				if ( ! features.length ) {
					return;
				}

				$style_tag = hester_get_style_tag( 'hester_' + section + '_text_color' );

				var style_css = hester_design_options_css( '.hester_section_' + section + ' .hester_bg', newval, 'color' );
				console.log( newval );
				if ( '' != newval ) {
					style_css += hester_design_options_css( '.hester_section_' + section + ' .hester_bg .starter__heading-title .title', {
						'text-color': 'inherit',
						'link-color': 'inherit',
						'link-hover-color': 'inherit'
					}, 'color' );
				}

				$style_tag.html( style_css );
			});
		});

	});

	/**
	 * This handles the customizer live actions
	 */
	 var home_sections = hester_customizer_preview.home_sections;
	$.hesterCustomizeLive = {
		'init': function() {
			this.liveTextReplace();
			this.liveShowHideSection();
			this.handleShowHideShortcut();
		},

		/**
         * This function handle the action when a user change a simple html input.
         * It target the class and then replace the inside of it with the new value.
         */
		'liveTextReplace': function() {
			var textToReplace = [];
			home_sections.forEach( function( section ) {
				textToReplace.push({ controlName: 'hester_' + section + '_sub_heading', selector: '.hester_section_' + section + ' .sub-title', isHtml: true });
				textToReplace.push({ controlName: 'hester_' + section + '_heading', selector: '.hester_section_' + section + ' .h2.title', isHtml: true });
				textToReplace.push({ controlName: 'hester_' + section + '_description', selector: '.hester_section_' + section + ' .starter__heading-title .description', isHtml: true });
			});

			textToReplace.forEach( function( item ) {
				wp.customize(
					item.controlName, function( value ) {
						value.bind(
							function( newval ) {
								if ( 'undefined' !== typeof item.isHtml ) {
									$( item.selector ).html( newval );
								} else {
									$( item.selector ).text( newval );
								}
							}
						);
					}
				);
			});
        },

		/**
		 * This function handle the action when a user clicks on show/hide customizer control.
		 * It toggles the section and then refresh animations.
		 */
		'liveShowHideSection': function() {
			var showHideControls = {
				'hester_enable_slider': '#hester-slider'
			};
			home_sections.forEach( function( section ) {
				showHideControls['hester_enable_' + section] = '.hester_section_' + section;
			});

			Object.keys( showHideControls ).forEach( function( key ) {
				api( key, function( value ) {
					value.bind( function( newval ) {
						$( showHideControls[key]).toggle();
					}
					);
				}
				);
			});
		},

		/**
		 * This function triggers click on show/hide control when user clicks on one of their custom shortcut.
		 */
		'handleShowHideShortcut': function() {
			var classesToLook =	home_sections.map( function( section ) {
				return 'hester_enable_' + section;
			});
			classesToLook.forEach( function( element ) {
				$( '.customize-partial-edit-shortcut-' + element ).on( 'click', function() {
                    api.preview.send( 'hester-customize-focus-control', element );
					api.preview.send( 'hester-customize-disable-section', element );
                });
			});
		}
	};


	// Selective refresh.
	if ( api.selectiveRefresh ) {

		// Bind partial content rendered event.
		api.selectiveRefresh.bind( 'partial-content-rendered', function( placement ) {

			// Hero Hover Slider.
			if ( 'hester_hero_hover_slider_post_number' === placement.partial.id || 'hester_hero_hover_slider_elements' === placement.partial.id ) {
				document.querySelectorAll( placement.partial.params.selector ).forEach( ( item ) => {
					hesterHoverSlider( item );
				});

				// Force refresh height.
				api( 'hester_hero_hover_slider_height', function( newval ) {
					newval.callbacks.fireWith( newval, [ newval.get() ]);
				});
			}

			// Preloader style.
			if ( 'hester_preloader_style' === placement.partial.id ) {
				$body.removeClass( 'hester-loaded' );

				setTimeout( function() {
					window.hester.preloader();
				}, 300 );
			}
		});
	}

	// Custom Customizer Preview class (attached to the Customize API)
	api.hesterCustomizerPreview = {

		// Init
		init: function() {
			var self = this; // Store a reference to "this"
			var previewBody = self.preview.body;

			previewBody.on( 'click', '.hester-set-widget', function() {
				self.preview.send( 'set-footer-widget', $( this ).data( 'sidebar-id' ) );
			});
		}
	};

	/**
	 * Capture the instance of the Preview since it is private (this has changed in WordPress 4.0)
	 *
	 * @see https://github.com/WordPress/WordPress/blob/5cab03ab29e6172a8473eb601203c9d3d8802f17/wp-admin/js/customize-controls.js#L1013
	 */
	var hesterOldPreview = api.Preview;
	api.Preview = hesterOldPreview.extend({
		initialize: function( params, options ) {

			// Store a reference to the Preview
			api.hesterCustomizerPreview.preview = this;

			// Call the old Preview's initialize function
			hesterOldPreview.prototype.initialize.call( this, params, options );
		}
	});

	// Document ready
	$( function() {

		// Initialize our Preview
		api.hesterCustomizerPreview.init();
		$.hesterCustomizeLive.init();
	});
}( jQuery ) );
