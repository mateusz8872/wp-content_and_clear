//--------------------------------------------------------------------//
// Global helper functions
//--------------------------------------------------------------------//

/**
 * Matches polyfill.
 *
 * @since 1.0.0
 */
if ( ! Element.prototype.matches ) {
	Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
}

/**
 * Closest polyfill.
 *
 * @since 1.0.0
 */
if ( ! Element.prototype.closest ) {
	Element.prototype.closest = function( s ) {
		var el = this;

		do {
			if ( el.matches( s ) ) {
				return el;
			}
			el = el.parentElement || el.parentNode;
		} while ( null !== el && 1 === el.nodeType );

		return null;
	};
}

/**
 * Foreach polyfill.
 *
 * @since 1.1.0
 */
if ( window.NodeList && ! NodeList.prototype.forEach ) {
	NodeList.prototype.forEach = Array.prototype.forEach;
}

/**
 * Element.prototype.classList for IE8/9, Safari.
 *
 * @since 1.10
 */
( function() {

	// Helpers.
	var trim = function( s ) {
		return s.replace( /^\s+|\s+$/g, '' );
	},
		regExp = function( name ) {
			return new RegExp( '(^|\\s+)' + name + '(\\s+|$)' );
		},
		forEach = function( list, fn, scope ) {
			for ( var i = 0; i < list.length; i++ ) {
				fn.call( scope, list[i]);
			}
		};

	// Class list object with basic methods.
	function ClassList( element ) {
		this.element = element;
	}

	ClassList.prototype = {
		add: function() {
			forEach(
				arguments,
				function( name ) {
					if ( ! this.contains( name ) ) {
						this.element.className = trim( this.element.className + ' ' + name );
					}
				},
				this
			);
		},
		remove: function() {
			forEach(
				arguments,
				function( name ) {
					this.element.className = trim( this.element.className.replace( regExp( name ), ' ' ) );
				},
				this
			);
		},
		toggle: function( name ) {
			return this.contains( name ) ? ( this.remove( name ), false ) : ( this.add( name ), true );
		},
		contains: function( name ) {
			return regExp( name ).test( this.element.className );
		},
		item: function( i ) {
			return this.element.className.split( /\s+/ )[i] || null;
		},

		// bonus
		replace: function( oldName, newName ) {
			this.remove( oldName ), this.add( newName );
		}
	};

	// IE8/9, Safari
	// Remove this if statements to override native classList.
	if ( ! ( 'classList' in Element.prototype ) ) {

		// Use this if statement to override native classList that does not have for example replace() method.
		// See browser compatibility: https://developer.mozilla.org/en-US/docs/Web/API/Element/classList#Browser_compatibility.
		// if (!('classList' in Element.prototype) ||
		//     !('classList' in Element.prototype && Element.prototype.classList.replace)) {
		Object.defineProperty( Element.prototype, 'classList', {
			get: function() {
				return new ClassList( this );
			}
		});
	}

	// For others replace() support.
	if ( window.DOMTokenList && ! DOMTokenList.prototype.replace ) {
		DOMTokenList.prototype.replace = ClassList.prototype.replace;
	}
}() );

/**
 * Index polyfill.
 *
 * @since 1.0.0
 */
var hesterGetIndex = function( el ) {
	var i = 0;

	while ( ( el = el.previousElementSibling ) ) {
		i++;
	}

	return i;
};

/**
 * Slide Up animation.
 *
 * @since 1.0.0
 *
 * @param  {[type]} target   Element to slide.
 * @param  {Number} duration Animation duration.
 */
var hesterSlideUp = ( target, duration = 500 ) => {
	target.style.transitionProperty = 'height, margin, padding';
	target.style.transitionDuration = duration + 'ms';
	target.style.boxSizing = 'border-box';
	target.style.height = target.offsetHeight + 'px';
	target.offsetHeight;
	target.style.overflow = 'hidden';
	target.style.height = 0;
	target.style.paddingTop = 0;
	target.style.paddingBottom = 0;
	target.style.marginTop = 0;
	target.style.marginBottom = 0;
	window.setTimeout( () => {
		target.style.display = null;
		target.style.removeProperty( 'height' );
		target.style.removeProperty( 'padding-top' );
		target.style.removeProperty( 'padding-bottom' );
		target.style.removeProperty( 'margin-top' );
		target.style.removeProperty( 'margin-bottom' );
		target.style.removeProperty( 'overflow' );
		target.style.removeProperty( 'transition-duration' );
		target.style.removeProperty( 'transition-property' );
	}, duration );
};

/**
 * Slide Down animation.
 *
 * @since 1.0.0
 *
 * @param  {[type]} target   Element to slide.
 * @param  {Number} duration Animation duration.
 */
var hesterSlideDown = ( target, duration = 500 ) => {
	target.style.removeProperty( 'display' );
	let display = window.getComputedStyle( target ).display;

	if ( 'none' === display ) {
		display = 'block';
	}

	target.style.display = display;
	let height = target.offsetHeight;
	target.style.overflow = 'hidden';
	target.style.height = 0;
	target.style.paddingTop = 0;
	target.style.paddingBottom = 0;
	target.style.marginTop = 0;
	target.style.marginBottom = 0;
	target.offsetHeight;
	target.style.boxSizing = 'border-box';
	target.style.transitionProperty = 'height, margin, padding';
	target.style.transitionDuration = duration + 'ms';
	target.style.height = height + 'px';
	target.style.removeProperty( 'padding-top' );
	target.style.removeProperty( 'padding-bottom' );
	target.style.removeProperty( 'margin-top' );
	target.style.removeProperty( 'margin-bottom' );
	window.setTimeout( () => {
		target.style.removeProperty( 'height' );
		target.style.removeProperty( 'overflow' );
		target.style.removeProperty( 'transition-duration' );
		target.style.removeProperty( 'transition-property' );
	}, duration );
};

/**
 * MoveTo - A lightweight scroll animation javascript library without any dependency.
 * Version 1.8.3 (21-07-2019 00:32)
 * Licensed under MIT
 * Copyright 2019 Hasan Aydoğdu <hsnaydd@gmail.com>
 */
var hesterScrollTo = ( function() {

	/**
	 * Defaults
	 * @type {object}
	 */
	var defaults = {
		tolerance: 0,
		duration: 800,
		easing: 'easeOutQuart',
		container: window,
		callback: function callback() { }
	};

	/**
	 * easeOutQuart Easing Function
	 * @param  {number} t - current time
	 * @param  {number} b - start value
	 * @param  {number} c - change in value
	 * @param  {number} d - duration
	 * @return {number} - calculated value
	 */

	function easeOutQuart( t, b, c, d ) {
		t /= d;
		t--;
		return -c * ( t * t * t * t - 1 ) + b;
	}

	/**
	 * Merge two object
	 *
	 * @param  {object} obj1
	 * @param  {object} obj2
	 * @return {object} merged object
	 */
	function mergeObject( obj1, obj2 ) {
		var obj3 = {};
		Object.keys( obj1 ).forEach( function( propertyName ) {
			obj3[propertyName] = obj1[propertyName];
		});
		Object.keys( obj2 ).forEach( function( propertyName ) {
			obj3[propertyName] = obj2[propertyName];
		});
		return obj3;
	}

	/**
	 * Converts camel case to kebab case
	 * @param  {string} val the value to be converted
	 * @return {string} the converted value
	 */
	function kebabCase( val ) {
		return val.replace( /([A-Z])/g, function( $1 ) {
			return '-' + $1.toLowerCase();
		});
	}

	/**
	 * Count a number of item scrolled top
	 * @param  {Window|HTMLElement} container
	 * @return {number}
	 */
	function countScrollTop( container ) {
		if ( container instanceof HTMLElement ) {
			return container.scrollTop;
		}

		return container.pageYOffset;
	}

	/**
	 * hesterScrollTo Constructor
	 * @param {object} options Options
	 * @param {object} easeFunctions Custom ease functions
	 */
	function hesterScrollTo() {
		var options = 0 < arguments.length && arguments[0] !== undefined ? arguments[0] : {};
		var easeFunctions = 1 < arguments.length && arguments[1] !== undefined ? arguments[1] : {};
		this.options = mergeObject( defaults, options );
		this.easeFunctions = mergeObject(
			{
				easeOutQuart: easeOutQuart
			},
			easeFunctions
		);
	}

	/**
	 * Register a dom element as trigger
	 * @param  {HTMLElement} dom Dom trigger element
	 * @param  {function} callback Callback function
	 * @return {function|void} unregister function
	 */
	hesterScrollTo.prototype.registerTrigger = function( dom, callback ) {
		var _this = this;

		if ( ! dom ) {
			return;
		}

		var href = dom.getAttribute( 'href' ) || dom.getAttribute( 'data-target' ); // The element to be scrolled

		var target = href && '#' !== href ? document.getElementById( href.substring( 1 ) ) : document.body;
		var options = mergeObject( this.options, _getOptionsFromTriggerDom( dom, this.options ) );

		if ( 'function' === typeof callback ) {
			options.callback = callback;
		}

		var listener = function listener( e ) {
			e.preventDefault();

			_this.move( target, options );
		};

		dom.addEventListener( 'click', listener, false );
		return function() {
			return dom.removeEventListener( 'click', listener, false );
		};
	};

	/**
	 * Move
	 * Scrolls to given element by using easeOutQuart function
	 * @param  {HTMLElement|number} target Target element to be scrolled or target position
	 * @param  {object} options Custom options
	 */
	hesterScrollTo.prototype.move = function( target ) {
		var _this2 = this;

		var options = 1 < arguments.length && arguments[1] !== undefined ? arguments[1] : {};

		if ( 0 !== target && ! target ) {
			return;
		}

		options = mergeObject( this.options, options );
		var distance = 'number' === typeof target ? target : target.getBoundingClientRect().top;
		var from = countScrollTop( options.container );
		var startTime = null;
		var lastYOffset;
		distance -= options.tolerance; // rAF loop

		var loop = function loop( currentTime ) {
			var currentYOffset = countScrollTop( _this2.options.container );

			if ( ! startTime ) {

				// To starts time from 1, we subtracted 1 from current time
				// If time starts from 1 The first loop will not do anything,
				// because easing value will be zero
				startTime = currentTime - 1;
			}

			var timeElapsed = currentTime - startTime;

			if ( lastYOffset ) {
				if ( ( 0 < distance && lastYOffset > currentYOffset ) || ( 0 > distance && lastYOffset < currentYOffset ) ) {
					return options.callback( target );
				}
			}

			lastYOffset = currentYOffset;

			var val = _this2.easeFunctions[options.easing]( timeElapsed, from, distance, options.duration );

			options.container.scroll( 0, val );

			if ( timeElapsed < options.duration ) {
				window.requestAnimationFrame( loop );
			} else {
				options.container.scroll( 0, distance + from );
				options.callback( target );
			}
		};

		window.requestAnimationFrame( loop );
	};

	/**
	 * Adds custom ease function
	 * @param {string}   name Ease function name
	 * @param {function} fn   Ease function
	 */
	hesterScrollTo.prototype.addEaseFunction = function( name, fn ) {
		this.easeFunctions[name] = fn;
	};

	/**
	 * Returns options which created from trigger dom element
	 * @param  {HTMLElement} dom Trigger dom element
	 * @param  {object} options The instance's options
	 * @return {object} The options which created from trigger dom element
	 */
	function _getOptionsFromTriggerDom( dom, options ) {
		var domOptions = {};
		Object.keys( options ).forEach( function( key ) {
			var value = dom.getAttribute( 'data-mt-'.concat( kebabCase( key ) ) );

			if ( value ) {
				domOptions[key] = isNaN( value ) ? value : parseInt( value, 10 );
			}
		});
		return domOptions;
	}

	return hesterScrollTo;
}() );

/**
 * Get all of an element's parent elements up the DOM tree
 *
 * @since 1.0.0
 *
 * @param  {Node}   elem     The element.
 * @param  {String} selector Selector to match against [optional].
 * @return {Array}           The parent elements.
 */
var hesterGetParents = ( elem, selector ) => {

	// Element.matches() polyfill.
	if ( ! Element.prototype.matches ) {
		Element.prototype.matches =
			Element.prototype.matchesSelector ||
			Element.prototype.mozMatchesSelector ||
			Element.prototype.msMatchesSelector ||
			Element.prototype.oMatchesSelector ||
			Element.prototype.webkitMatchesSelector ||
			function( s ) {
				var matches = ( this.document || this.ownerDocument ).querySelectorAll( s ),
					i = matches.length;
				while ( 0 <= --i && matches.item( i ) !== this ) { }
				return -1 < i;
			};
	}

	// Setup parents array.
	var parents = [];

	// Get matching parent elements.
	for ( ; elem && elem !== document; elem = elem.parentNode ) {

		// Add matching parents to array.
		if ( selector ) {
			if ( elem.matches( selector ) ) {
				parents.push( elem );
			}
		} else {
			parents.push( elem );
		}
	}
	return parents;
};

// CustomEvent() constructor functionality in Internet Explorer 9 and higher.
( function() {
	if ( 'function' === typeof window.CustomEvent ) {
		return false;
	}

	function CustomEvent( event, params ) {
		params = params || { bubbles: false, cancelable: false, detail: undefined };
		var evt = document.createEvent( 'CustomEvent' );
		evt.initCustomEvent( event, params.bubbles, params.cancelable, params.detail );
		return evt;
	}

	CustomEvent.prototype = window.Event.prototype;
	window.CustomEvent = CustomEvent;
}() );

/**
 * Trigger custom JS Event.
 *
 * @since 1.0.0
 *
 * @link https://developer.mozilla.org/en-US/docs/Web/API/CustomEvent
 * @param {Node} el Dom Node element on which the event is to be triggered.
 * @param {Node} typeArg A DOMString representing the name of the event.
 * @param {String} A CustomEventInit dictionary, having the following fields:
 *			"detail", optional and defaulting to null, of type any, that is an event-dependent value associated with the event.
 */
var hesterTriggerEvent = function( el, typeArg ) {
	var customEventInit = 2 < arguments.length && arguments[2] !== undefined ? arguments[2] : {};

	var event = new CustomEvent( typeArg, customEventInit );
	el.dispatchEvent( event );
};

// Main
( function() {

	//--------------------------------------------------------------------//
	// Variable caching
	//--------------------------------------------------------------------//

	var hesterScrollButton = document.querySelector( '#hester-scroll-top' );
	var pageWrapper = document.getElementById( 'page' );

	//--------------------------------------------------------------------//
	// Local helper functions
	//--------------------------------------------------------------------//

	/**
	 * Submenu overflow helper
	 *
	 * @since 1.0.0
	 */
	var hesterSmartSubmenus = () => {
		if ( document.body.classList.contains( 'hester-is-mobile' ) ) {
			return;
		}

		var el, elPosRight, elPosLeft, winRight;

		winRight = window.innerWidth;

		document.querySelectorAll( '.sub-menu' ).forEach( ( item ) => {

			// Set item to be visible so we can grab offsets
			item.style.visibility = 'visible';

			// Left offset
			const rect = item.getBoundingClientRect();
			elPosLeft = rect.left + window.pageXOffset;

			// Right offset
			elPosRight = elPosLeft + rect.width;

			// Remove styles
			item.removeAttribute( 'style' );

			// Decide where to open
			if ( elPosRight > winRight ) {
				item.closest( 'li' ).classList.add( 'opens-left' );
			} else if ( 0 > elPosLeft ) {
				item.closest( 'li' ).classList.add( 'opens-right' );
			}
		});
	};

	/**
	 * Debounce functions for better performance
	 * (c) 2018 Chris Ferdinandi, MIT License, https://gomakethings.com
	 *
	 * @since 1.0.0
	 *
	 * @param  {Function} fn The function to debounce
	 */
	var hesterDebounce = ( fn ) => {

		// Setup a timer
		var timeout;

		// Return a function to run debounced
		return function() {

			// Setup the arguments
			var context = this;
			var args = arguments;

			// If there's a timer, cancel it
			if ( timeout ) {
				window.cancelAnimationFrame( timeout );
			}

			// Setup the new requestAnimationFrame()
			timeout = window.requestAnimationFrame( function() {
				fn.apply( this, args );
			});
		};
	};

	/**
	 * Handles Scroll to Top button click
	 *
	 * @since 1.0.0
	 */
	var hesterScrollTopButton = () => {
		if ( null === hesterScrollButton ) {
			return;
		}

		if ( 450 < window.pageYOffset || 450 < document.documentElement.scrollTop ) {
			hesterScrollButton.classList.add( 'hester-visible' );
		} else {
			hesterScrollButton.classList.remove( 'hester-visible' );
		}
	};

	/**
	 * Handles Sticky Header functionality.
	 *
	 * @since 1.0.0
	 */
	var hesterStickyHeader = () => {

		// Check if sticky is enabled.
		if ( ! hester_vars['sticky-header'].enabled ) {
			return;
		}

		var header = document.getElementById( 'hester-header' );
		var headerInner = document.getElementById( 'hester-header-inner' );
		var wpadminbar = document.getElementById( 'wpadminbar' );

		// Check for header layout 3.
		if ( document.body.classList.contains( 'hester-header-layout-3' ) ) {
			header = document.querySelector( '#hester-header .hester-nav-container' );
			headerInner = document.querySelector( '#hester-header .hester-nav-container .hester-container' );
		}

		// Mobile nav active.
		if ( window.outerWidth <= hester_vars['responsive-breakpoint']) {
			var header = document.getElementById( 'hester-header' );
			var headerInner = document.getElementById( 'hester-header-inner' );
		}

		// Check if elements exist.
		if ( null === header || null === headerInner ) {
			return;
		}

		// Calculate the initial sticky position.
		var stickyPosition = header.getBoundingClientRect().bottom;
		var sticky = 0 >= stickyPosition - tolerance;
		var tolerance;
		var stickyPlaceholder;

		// Check if there is a top bar.
		if ( null === wpadminbar ) {
			tolerance = 0;
		} else if ( 600 >= window.outerWidth ) {
			tolerance = 0;
		} else {
			tolerance = wpadminbar.getBoundingClientRect().height;
		}

		var checkPosition = function() {

			if ( null === wpadminbar ) {
				tolerance = 0;
			} else if ( 600 >= window.outerWidth ) {
				tolerance = 0;
			} else {
				tolerance = wpadminbar.getBoundingClientRect().height;
			}


			stickyPosition = header.getBoundingClientRect().bottom;
			sticky = 0 >= stickyPosition - tolerance;

			maybeStickHeader();
		};

		var maybeStickHeader = function() {
			let hideOn = hester_vars['sticky-header']['hide_on'];

			// Desktop.
			if ( hideOn.includes( 'desktop' ) && 992 <= window.innerWidth ) {
				sticky = false;
			}

			// Tablet.
			if ( hideOn.includes( 'tablet' ) && 481 <= window.innerWidth && 992 > window.innerWidth ) {
				sticky = false;
			}

			// Mobile.
			if ( hideOn.includes( 'mobile' ) && 481 > window.innerWidth ) {
				sticky = false;
			}

			if ( sticky ) {
				if ( ! document.body.classList.contains( 'hester-sticky-header' ) ) {
					stickyPlaceholder = document.createElement( 'div' );
					stickyPlaceholder.setAttribute( 'id', 'hester-sticky-placeholder' );

					stickyPlaceholder.style.height = headerInner.getBoundingClientRect().height + 'px';
					header.appendChild( stickyPlaceholder );

					document.body.classList.add( 'hester-sticky-header' );

					// Add sticky header offset variable.
					document.body.style.setProperty( '--hester-sticky-h-offset', header.offsetHeight + 20 + 'px' );
				}
			} else {
				if ( document.body.classList.contains( 'hester-sticky-header' ) ) {
					document.body.classList.remove( 'hester-sticky-header' );
					document.getElementById( 'hester-sticky-placeholder' ).remove();
				}

				// Remove sticky header offset variable.
				document.body.style.removeProperty( '--hester-sticky-h-offset' );
			}
		};

		// Debounce scroll.
		if ( 'true' !== header.getAttribute( 'data-scroll-listener' ) ) {
			window.addEventListener( 'scroll', function() {
				hesterDebounce( checkPosition() );
			});

			header.setAttribute( 'data-scroll-listener', 'true' );
		}

		// Debounce resize.
		if ( 'true' !== header.getAttribute( 'data-resize-listener' ) ) {
			window.addEventListener( 'resize', function() {
				hesterDebounce( checkPosition() );
			});

			header.setAttribute( 'data-resize-listener', 'true' );
		}

		// Trigger scroll.
		hesterTriggerEvent( window, 'scroll' );
	};

	/**
	 * Handles smooth scrolling of elements that have 'hester-smooth-scroll' class.
	 *
	 * @since 1.0.0
	 */
	var hesterSmoothScroll = () => {
		const scrollTo = new hesterScrollTo({
			tolerance: null === document.getElementById( 'wpadminbar' ) ? 0 : document.getElementById( 'wpadminbar' ).getBoundingClientRect().height
		});

		const scrollTriggers = document.getElementsByClassName( 'hester-smooth-scroll' );

		for ( var i = 0; i < scrollTriggers.length; i++ ) {
			scrollTo.registerTrigger( scrollTriggers[i]);
		}
	};

	/**
	 * Menu accessibility.
	 *
	 * @since 1.0.0
	 */
	var hesterMenuAccessibility = () => {
		if ( ! document.body.classList.contains( 'hester-menu-accessibility' ) ) {
			return;
		}

		document.querySelectorAll( '.hester-nav' ).forEach( ( menu ) => {

			// aria-haspopup
			menu.querySelectorAll( 'ul' ).forEach( ( subMenu ) => {
				subMenu.parentNode.setAttribute( 'aria-haspopup', 'true' );
			});

			// Dropdown visibility on focus
			menu.querySelectorAll( 'a' ).forEach( ( link ) => {
				link.addEventListener( 'focus', hesterMenuFocus, true );
				link.addEventListener( 'blur', hesterMenuFocus, true );
			});
		});
	};

	/**
	 * Helper function that toggles .hovered on focused/blurred menu items.
	 *
	 * @since 1.0.0
	 */
	function hesterMenuFocus() {
		var self = this;

		// Move up until we find .hester-nav
		while ( ! self.classList.contains( 'hester-nav' ) ) {
			if ( 'li' === self.tagName.toLowerCase() ) {
				if ( ! self.classList.contains( 'hovered' ) ) {
					self.classList.add( 'hovered' );
				} else {
					self.classList.remove( 'hovered' );
				}
			}

			self = self.parentElement;
		}
	}

	/**
	 * Helps with accessibility for keyboard only users.
	 *
	 * @since 1.0.0
	 */
	var hesterKeyboardFocus = () => {
		document.body.addEventListener( 'keydown', function( e ) {
			document.body.classList.add( 'using-keyboard' );
		});

		document.body.addEventListener( 'mousedown', function( e ) {
			document.body.classList.remove( 'using-keyboard' );
		});
	};

	/**
	 * Calculates screen width without scrollbars.
	 *
	 * @since 1.0.0
	 */
	var hesterCalcScreenWidth = () => {
		document.body.style.setProperty( '--hester-screen-width', document.body.clientWidth + 'px' );
	};

	/**
	 * Adds visibility delay on navigation submenus.
	 *
	 * @since 1.0.0
	 */
	var hesterDropdownDelay = () => {
		var hoverTimer = null;

		document.querySelectorAll( '.hester-nav .menu-item-has-children' ).forEach( ( item ) => {
			item.addEventListener( 'mouseenter', function() {
				document.querySelectorAll( '.menu-item-has-children' ).forEach( ( subitem ) => {
					subitem.classList.remove( 'hovered' );
				});
			});
		});

		document.querySelectorAll( '.hester-nav .menu-item-has-children' ).forEach( ( item ) => {
			item.addEventListener( 'mouseleave', function() {
				item.classList.add( 'hovered' );

				if ( null !== hoverTimer ) {
					clearTimeout( hoverTimer );
					hoverTimer = null;
				}

				hoverTimer = setTimeout( () => {
					item.classList.remove( 'hovered' );

					item.querySelectorAll( '.menu-item-has-children' ).forEach( ( childItem ) => {
						childItem.classList.remove( 'hovered' );
					});
				}, 700 );
			});
		});
	};

	/**
	 * Adds visibility delay for cart widget dropdown.
	 *
	 * @since 1.0.0
	 */
	var hesterCartDropdownDelay = () => {
		var hoverTimer = null;

		document.querySelectorAll( '.hester-header-widget__cart .hester-widget-wrapper' ).forEach( ( item ) => {
			item.addEventListener( 'mouseenter', function() {
				item.classList.remove( 'dropdown-visible' );
			});
		});

		document.querySelectorAll( '.hester-header-widget__cart .hester-widget-wrapper' ).forEach( ( item ) => {
			item.addEventListener( 'mouseleave', function() {
				item.classList.add( 'dropdown-visible' );

				if ( null !== hoverTimer ) {
					clearTimeout( hoverTimer );
					hoverTimer = null;
				}

				hoverTimer = setTimeout( () => {
					item.classList.remove( 'dropdown-visible' );
				}, 700 );
			});
		});
	};

	/**
	 * Handles header search widget functionality.
	 *
	 * @since 1.0.0
	 */
	var hesterHeaderSearch = () => {
		var searchButton = document.querySelectorAll( '.hester-search' );

		if ( 0 === searchButton.length ) {
			return;
		}

		searchButton.forEach( ( item ) => {
			item.addEventListener( 'click', ( e ) => {
				e.preventDefault();

				if ( item.classList.contains( 'hester-active' ) ) {
					close_search( item );
				} else {
					show_search( item );
				}
			});
		});

		document.querySelectorAll( '.hester-search-close' ).forEach( item => item.addEventListener( 'click', function( e ) {
			e.preventDefault();
			if ( ! item.classList.contains( 'hester-active' ) ) {
				close_search( document.querySelector( '.hester-search' ) );
				document.querySelector( '.hester-search' ).focus();
			}
		}) );

		// Show search.
		var show_search = function( item ) {

			var hestersearch = document.querySelectorAll( '.hester-search-form' );
			hestersearch.forEach( function( hestersearch ) {
				if ( ! hestersearch ) {
					return false;
				}
				document.addEventListener( 'keydown', function( e ) {
					var firstEl, selectors, elements, lastEl, tabKey, shiftKey;
					selectors = 'input, a, button';
					elements = hestersearch.querySelectorAll( selectors );
					lastEl = elements[elements.length - 1];
					firstEl = elements[0];
					tabKey = 9 === e.keyCode;
					shiftKey = e.shiftKey;
					if ( ! shiftKey && tabKey && lastEl === document.activeElement ) {
						e.preventDefault();
						firstEl.focus();
					}
					if ( shiftKey && tabKey && firstEl === document.activeElement ) {
						e.preventDefault();
						lastEl.focus();
					}
				});
			});

			// Make search visible
			document.body.classList.add( 'hester-search-visible' );

			setTimeout( function() {

				// Highlight the search icon
				item.classList.add( 'hester-active' );

				// Focus the input
				if ( null !== item.nextElementSibling && null !== item.nextElementSibling.querySelector( 'input' ) ) {
					item.nextElementSibling.querySelector( 'input' ).focus();
					item.nextElementSibling.querySelector( 'input' ).select();
				}
			}, 100 );

			// Attach the ESC listener
			document.addEventListener( 'keydown', esc_close_search );

			// Attach the outside click listener
			pageWrapper.addEventListener( 'click', outside_close_search );
		};

		// Close search
		var close_search = function( item ) {

			// Animate out
			document.body.classList.remove( 'hester-search-visible' );

			// Unhighlight the search icon
			item.classList.remove( 'hester-active' );

			// Unhook the ESC listener
			document.removeEventListener( 'keydown', esc_close_search );

			// Unhook the click listener
			pageWrapper.removeEventListener( 'click', outside_close_search );
		};

		// Esc support to close search
		var esc_close_search = function( e ) {
			if ( 27 == e.keyCode ) {
				document.querySelectorAll( '.hester-search' ).forEach( ( item ) => {
					close_search( item );
				});
			}
		};

		// Close search when clicked anywhere outside the search box
		var outside_close_search = function( e ) {
			if ( null === e.target.closest( '.hester-search-container' ) && null === e.target.closest( '.hester-search' ) ) {
				document.querySelectorAll( '.hester-search' ).forEach( ( item ) => {
					close_search( item );
				});
			}
		};
	};

	/**
	 * Handles mobile menu functionality.
	 *
	 * @since 1.0.0
	 */
	var hesterMobileMenu = () => {
		var page = pageWrapper,
			nav = document.querySelector( '#hester-header-inner .hester-nav' ),
			current;

		document.querySelectorAll( '.hester-mobile-nav > button' ).forEach( ( item ) => {
			item.addEventListener(
				'click',
				function( e ) {
					e.preventDefault();

					if ( document.body.parentNode.classList.contains( 'is-mobile-menu-active' ) ) {
						close_menu();
					} else {
						show_menu();
					}
				},
				false
			);
		});

		// Helper functions.
		var show_menu = function( e ) {

			var hesternav = document.querySelectorAll( '.site-header' );
			if ( document.body.classList.contains( 'hester-is-mobile' ) ) {
				hesternav.forEach( function( hesternav ) {
					if ( ! hesternav ) {
						return false;
					}
					document.addEventListener( 'keydown', function( e ) {
						var firstEl, selectors, elements, lastEl, tabKey, shiftKey, closeIcon;
						selectors = '.hester-nav a, .hester-nav button';
						elements = hesternav.querySelectorAll( selectors );
						lastEl = elements[elements.length - 1];
						firstEl = elements[0];
						tabKey = 9 === e.keyCode;
						shiftKey = e.shiftKey;
						closeIcon = document.querySelector( '.hester-hamburger-hester-primary-nav' );
						if ( ! shiftKey && tabKey && lastEl === document.activeElement ) {
							e.preventDefault();
							closeIcon.focus();
						}
						if ( shiftKey && tabKey && firstEl === document.activeElement ) {
							e.preventDefault();
							closeIcon.focus();
						}

						// If there are no elements in the menu, don't move the focus
						if ( tabKey && firstEl === lastEl ) {
							e.preventDefault();
						}
					});
				});
			}

			// Add the active class.
			document.body.parentNode.classList.add( 'is-mobile-menu-active' );

			// Hook the ESC listener
			document.addEventListener( 'keyup', esc_close_menu );

			// Hook the click listener
			if ( null !== page ) {
				page.addEventListener( 'click', outside_close_menu );
			}

			// Hook the click listener for submenu toggle.
			document.querySelectorAll( '#hester-header .hester-nav' ).forEach( ( item ) => {
				item.addEventListener( 'click', submenu_toggle );
			});

			// Slide down the menu.
			hesterSlideDown( nav, 350 );
		};

		var close_menu = function( e ) {

			// Remove the active class.
			document.body.parentNode.classList.remove( 'is-mobile-menu-active' );

			// Unhook the ESC listener
			document.removeEventListener( 'keyup', esc_close_menu );

			// Unhook the click listener
			if ( null !== page ) {
				page.removeEventListener( 'click', outside_close_menu );
			}

			// Close submenus
			document.querySelectorAll( '#hester-header .hester-nav > ul > .hester-open' ).forEach( ( item ) => {
				submenu_display_toggle( item );
			});

			nav.querySelectorAll( '.hovered' ).forEach( ( li ) => {
				li.classList.remove( 'hovered' );
			});

			if ( document.body.classList.contains( 'hester-is-mobile' ) ) {

				// Unhook the click listener for submenu toggle
				document.querySelectorAll( '#hester-header .hester-nav' ).forEach( ( item ) => {
					item.removeEventListener( 'click', submenu_toggle );
				});

				// Slide up the menu
				hesterSlideUp( nav, 250 );
			} else {
				nav.style.display = null;
			}
		};

		var outside_close_menu = function( e ) {
			if ( null === e.target.closest( '.hester-hamburger' ) && null === e.target.closest( '.site-navigation' ) ) {
				close_menu();
			}
		};

		var esc_close_menu = function( e ) {
			if ( 27 == e.keyCode ) {
				close_menu();
			}
		};

		var submenu_toggle = function( e ) {
			if ( e.target.parentElement.querySelectorAll( '.sub-menu' ).length ) {
				e.preventDefault();
				if( e.target.type === 'button' ) {
					submenu_display_toggle( e.target.parentElement );
				}
			}
		};

		// Show or hide the sub menu.
		var submenu_display_toggle = ( current ) => {
			if ( current.classList.contains( 'hester-open' ) ) {
				current.classList.remove( 'hester-open' );
				current.querySelectorAll( '.sub-menu' ).forEach( ( submenu ) => {

					//submenu.style.display = null;
					hesterSlideUp( submenu, 350 );
				});

				// Close all submenus automatically. 
				current.querySelectorAll( 'li' ).forEach( ( item ) => {
					item.parentElement.classList.remove( 'hester-open' );
					item.nextElementSibling.querySelectorAll( '.sub-menu' ).forEach( ( submenu ) => {

						//submenu.style.display = null;
						hesterSlideUp( submenu, 350 );
					});
				});
			} else {
				current.querySelectorAll( '.sub-menu' ).forEach( ( submenu ) => {

					// Target first level elements only.
					if ( current === submenu.parentElement ) {

						//submenu.style.display = 'block';
						hesterSlideDown( submenu, 350 );
					}
				});

				current.classList.add( 'hester-open' );
			}
		};

		// Create custom event for closing mobile menu.
		document.addEventListener( 'hester-close-mobile-menu', close_menu );
	};

	/**
	 * Hester preloader.
	 *
	 * @since 1.0.0
	 */
	var hesterPreloader = ( timeout = 0 ) => {
		var preloader = document.getElementById( 'hester-preloader' );

		if ( null === preloader ) {
			return;
		}

		var delay = 250;

		var hide_preloader = () => {
			if ( document.body.classList.contains( 'hester-loaded' ) ) {
				return;
			}

			// Start fade out animation.
			document.body.classList.add( 'hester-loading' );

			setTimeout( function() {

				// Fade out animation completed - set display none
				document.body.classList.replace( 'hester-loading', 'hester-loaded' );

				// Dispatch event when preloader is done
				hesterTriggerEvent( document.body, 'hester-preloader-done' );
			}, delay );
		};

		// Set timeout or hide immediately
		if ( 0 < timeout ) {
			setTimeout( function() {
				hide_preloader();
			}, timeout );
		} else {
			hide_preloader();
		}

		return false;
	};

	/**
	 * Handles comments toggle functionality.
	 *
	 * @since 1.0.0
	 */
	var hesterToggleComments = () => {
		if ( ! document.body.classList.contains( 'hester-has-comments-toggle' ) ) {
			return;
		}

		if ( null == document.getElementById( 'hester-comments-toggle' ) ) {
			return;
		}

		var toggleComments = ( e ) => {
			if ( 'undefined' !== typeof e ) {
				e.preventDefault();
			}

			if ( document.body.classList.contains( 'comments-visible' ) ) {
				document.body.classList.remove( 'comments-visible' );
				document.getElementById( 'hester-comments-toggle' ).querySelector( 'span' ).innerHTML = hester_vars.strings.comments_toggle_show;
			} else {
				document.body.classList.add( 'comments-visible' );
				document.getElementById( 'hester-comments-toggle' ).querySelector( 'span' ).innerHTML = hester_vars.strings.comments_toggle_hide;
			}
		};

		if ( null !== document.getElementById( 'hester-comments-toggle' ) && ( -1 !== location.href.indexOf( '#comment' ) || -1 !== location.href.indexOf( 'respond' ) ) ) {
			toggleComments();
		}

		document.getElementById( 'hester-comments-toggle' ).addEventListener( 'click', toggleComments );
	};

	/**
	 * Handles toggling and smooth scrolling when clicked on "Comments" link
	 *
	 * @since 1.0.0
	 */
	var hesterCommentsClick = () => {
		var commentsLink = document.querySelector( '.single .comments-link' );

		if ( null === commentsLink ) {
			return;
		}

		commentsLink.addEventListener( 'click', function( e ) {

			// Show comments if hidden under a toggle
			if ( document.body.classList.contains( 'hester-has-comments-toggle' ) && ! document.body.classList.contains( 'comments-visible' ) ) {
				document.getElementById( 'hester-comments-toggle' ).click();
			}
		});
	};

	/**
	 * Removes inline styles on menus on resize.
	 *
	 * @since 1.1.0
	 */
	var hesterCheckMobileMenu = () => {

		// Update body class if mobile breakpoint is reached.
		if ( window.innerWidth <= hester_vars['responsive-breakpoint']) {
			document.body.classList.add( 'hester-is-mobile' );
		} else {
			if ( document.body.classList.contains( 'hester-is-mobile' ) ) {
				document.body.classList.remove( 'hester-is-mobile' );
				hesterTriggerEvent( document, 'hester-close-mobile-menu' );
			}
		}
	};

	//--------------------------------------------------------------------//
	// Events
	//--------------------------------------------------------------------//

	// DOM ready
	document.addEventListener( 'DOMContentLoaded', function() {
		hesterPreloader( 5000 );
		hesterMenuAccessibility();
		hesterKeyboardFocus();
		hesterScrollTopButton();
		hesterSmoothScroll();
		hesterDropdownDelay();
		hesterToggleComments();
		hesterHeaderSearch();
		hesterMobileMenu();
		hesterCheckMobileMenu();
		hesterSmartSubmenus();
		hesterCommentsClick();
		hesterCartDropdownDelay();
		hesterStickyHeader();
		hesterCalcScreenWidth();
	});

	// Window load
	window.addEventListener( 'load', function() {
		hesterPreloader();
	});

	// Scroll
	window.addEventListener( 'scroll', function() {
		hesterDebounce( hesterScrollTopButton() );
	});

	// Resize
	window.addEventListener( 'resize', function() {
		hesterDebounce( hesterSmartSubmenus() );
		hesterDebounce( hesterCheckMobileMenu() );
		hesterDebounce( hesterCalcScreenWidth() );
	});

	// Hester ready
	hesterTriggerEvent( document.body, 'hester-ready' );

	//--------------------------------------------------------------------//
	// Global
	//--------------------------------------------------------------------//

	window.hester = window.hester || {};

	// Make these function global.
	window.hester.preloader = hesterPreloader;
	window.hester.stickyHeader = hesterStickyHeader;

	window.App = {};
	App.config = {
		headroom: {
			enabled: true,
			options: {
				classes: {
					initial: 'headroom',
					pinned: 'is-pinned',
					unpinned: 'is-unpinned',
					top: 'is-top',
					notTop: 'is-not-top',
					bottom: 'is-bottom',
					notBottom: 'is-not-bottom',
					frozen: 'is-frozen'
				}
			}
		},
		ajax: {
			enabled: true
		},
		cursorFollower: {
			enabled: true,
			disableBreakpoint: '992'
		}
	};
	App.html = document.querySelector( 'html' );
	App.body = document.querySelector( 'body' );
	window.onload = function() {

		if ( App.config.cursorFollower.enabled ) {
			Cursor.init();
		}

	};
	const Cursor = ( function() {

		const cursor = document.querySelector( '.hester-js-cursor' );
		let follower;
		let label;
		let icon;

		let clientX;
		let clientY;
		let cursorWidth;
		let cursorHeight;
		let cursorTriggers;
		let state;

		function variables() {

			follower = cursor.querySelector( '.hester-js-follower' );
			label = cursor.querySelector( '.hester-js-label' );
			icon = cursor.querySelector( '.hester-js-icon' );

			clientX = -100;
			clientY = -100;
			cursorWidth = cursor.offsetWidth / 2;
			cursorHeight = cursor.offsetHeight / 2;
			cursorTriggers;
			state = false;

		}

		function init() {

			if ( ! cursor ) {
				return;
			}

			variables();
			state = true;
			cursor.classList.add( 'is-enabled' );

			document.addEventListener( 'mousedown', e => {
				cursor.classList.add( 'is-mouse-down' );
			});

			document.addEventListener( 'mouseup', e => {
				cursor.classList.remove( 'is-mouse-down' );
			});

			document.addEventListener( 'mousemove', ( event ) => {
				clientX = event.clientX;
				clientY = event.clientY;
			});

			const render = () => {
				cursor.style.transform = `translate(${clientX - cursorWidth}px, ${clientY - cursorHeight}px)`;
				requestAnimationFrame( render );
			};

			requestAnimationFrame( render );

			update();
			breakpoint();

		}

		function enterHandler({ target }) {

			cursor.classList.add( 'is-active' );

			if ( target.getAttribute( 'data-cursor-label' ) ) {
				App.body.classList.add( 'is-cursor-active' );
				cursor.classList.add( 'has-label' );
				label.innerHTML = target.getAttribute( 'data-cursor-label' );
			}

			if ( target.getAttribute( 'data-cursor-icon' ) ) {
				App.body.classList.add( 'is-cursor-active' );
				cursor.classList.add( 'has-icon' );
				const iconAttr = target.getAttribute( 'data-cursor-icon' );
			}

		}

		function leaveHandler({ target }) {

			App.body.classList.remove( 'is-cursor-active' );
			cursor.classList.remove( 'is-active' );
			cursor.classList.remove( 'has-label' );
			cursor.classList.remove( 'has-icon' );
			label.innerHTML = '';
			icon.innerHTML = '';

		}

		function update() {

			if ( ! cursor ) {
				return;
			}

			cursorTriggers = document.querySelectorAll([
				'button',
				'a',
				'input',
				'[data-cursor]',
				'[data-cursor-label]',
				'[data-cursor-icon]',
				'textarea'
			]);

			cursorTriggers.forEach( el => {
				el.addEventListener( 'mouseenter', enterHandler );
				el.addEventListener( 'mouseleave', leaveHandler );
			});

		}

		function clear() {

			if ( ! cursor ) {
				return;
			}

			cursorTriggers.forEach( el => {
				el.removeEventListener( 'mouseenter', enterHandler );
				el.removeEventListener( 'mouseleave', leaveHandler );
			});

		}

		function hide() {

			if ( ! cursor ) {
				return;
			}
			cursor.classList.add( 'is-hidden' );

		}

		function show() {

			if ( ! cursor ) {
				return;
			}
			cursor.classList.remove( 'is-hidden' );

		}

		function breakpoint() {

			if ( ! state ) {
				return;
			}
			if ( ! App.config.cursorFollower.disableBreakpoint ) {
				return;
			}

			let width = ( 0 < window.innerWidth ) ? window.innerWidth : screen.width;

			if ( width < App.config.cursorFollower.disableBreakpoint ) {
				state = false;
				cursor.classList.remove( 'is-enabled' );
				clear();
			} else {
				state = true;
				cursor.classList.add( 'is-enabled' );
				update();
			}

			window.addEventListener( 'resize', () => {
				let width = ( 0 < window.innerWidth ) ? window.innerWidth : screen.width;

				if ( width < App.config.cursorFollower.disableBreakpoint ) {
					state = false;
					cursor.classList.remove( 'is-enabled' );
					clear();
				} else {
					state = true;
					cursor.classList.add( 'is-enabled' );
					update();
				}
			});

		}

		return {
			init: init,
			update: update,
			clear: clear,
			hide: hide,
			show: show
		};

	}() );
	if ( document.body.classList.contains( 'is-parallax-footer' ) ) {
		var body = document.querySelector( '.is-parallax-footer' );
		initializeParallaxFooter(
			document.getElementById( 'main' ),
			document.getElementById( 'colophon' )
		);
		function initializeParallaxFooter( mainElement, footerElement ) {
			footerElement.style.left = '0';
			footerElement.style.right = '0';
			footerElement.style.zIndex = '-1';
			updateParallaxFooter( mainElement, footerElement );
			window.addEventListener( 'resize', function() {
				updateParallaxFooter( mainElement, footerElement );
			});
			window.addEventListener( 'scroll', function() {
				updateParallaxFooter( mainElement, footerElement );
			});
		}
		function updateParallaxFooter( mainElement, footerElement ) {
			if ( isViewportSmallerThanFooter( footerElement ) ) {
				footerElement.style.bottom = '';
				footerElement.style.top = '0';
			} else {
				footerElement.style.top = '';
				footerElement.style.bottom = '0';
			}
			if ( window.scrollY > getBottomY( mainElement ) ) {
				footerElement.style.position = 'static';
				body.style.marginBottom = '0';
			} else {
				body.style.marginBottom = footerElement.offsetHeight + 'px';
				footerElement.style.position = 'fixed';
			}
		}
		function isViewportSmallerThanFooter( footerElement ) {
			return window.innerHeight < footerElement.offsetHeight;
		}
		function getBottomY( element ) {
			return element.offsetTop + element.offsetHeight;
		}
	}
}() );
