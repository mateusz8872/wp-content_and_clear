;(function($) {

 	"use strict";

	wp.customize.controlConstructor['hester-widget'] = wp.customize.Control.extend({

		ready: function() {

			'use strict';

			var control = this;

			control.widget_count = control.container.find( '.widget' ).length;
			control.setupSortable();

			// Expand widget content on header click
			control.container.on( 'click', '.hester-widget-container .widget-top', function(){
				$(this).closest( '.widget' ).toggleClass( 'hester-expanded' ).find( '.widget-inside' ).slideToggle( 200 );
			});
			
			// Minimize widget content when clicked on Done
			control.container.on( 'click', '.hester-widget-container .widget-control-close', function() {
				$(this).closest( '.widget' ).toggleClass( 'hester-expanded' ).find( '.widget-inside' ).slideToggle( 200 );
			});

			// Show available widgets
			control.container.on( 'click', '.hester-add-widget', function(e) {
				e.preventDefault();
				control.updateList();
			});

		 	control.container.on( 'change paste keyup', 'input, textarea, select', function(e){
		 		control.update();
		 	});

		 	control.container.on( 'click', '.widget-control-remove', function(){
		 		$(this).closest( '.widget' ).remove();
		 		control.update();
		 		control.updateList();
		 	});

		 	control.container.on( 'click', '.hester-widget-edit-nav', function(){
		 		wp.customize.control( 'nav_menu_locations[' + $(this).closest( '.hester-widget-nav-container' ).data( 'menu-location' ) + ']' ).focus();
		 		control.close();
		 	});

		 	// Close the panel if the URL in the preview changes
			wp.customize.previewer.bind( 'url', this.close );

			$( control.container ).find( '.hester-widget-nav-container' ).each( function(){

				var $this = $(this);
				control.bindMenuLocation( $this );
			});
		},

		bindMenuLocation: function( $container ) {
			var menu_location = $container.data('menu-location');

			// Bind menu location setting
			wp.customize( 'nav_menu_locations[' + menu_location + ']', function( value ) {
				value.bind( function( newval ) {
					
					if ( newval ) {
						var menu_name = wp.customize.control( 'nav_menu_locations[' + menu_location + ']' ).container.find('option:selected').html();
					
						$container.addClass( 'hester-widget-nav-has-menu' )
							.find( '.hester-widget-nav-name' )
							.html( menu_name );
					} else {
						$container.removeClass('hester-widget-nav-has-menu');
					}

				});
			});
		},

		// Changes visibility of available widgets
		updateList: function(){

			var widget,
				self = this,
				widgets = self.params.widgets;

			// Filter which widgets are available.
			if ( widgets ) {

				// Hide all widgets.
				$( '#hester-available-widgets-list .hester-widget' ).hide().removeClass('disabled');

				// Display allowed widgets.
				$.each( widgets, function( index, el ) {

					widget = $( '#hester-available-widgets-list #hester-widget-tpl-hester_customizer_widget_' + index );

					widget.show();

					if ( el.hasOwnProperty( 'max_uses' ) && el.max_uses > 0 && el.max_uses <= $(self.container).find('.hester-widget-container [data-widget-type="' + index + '"]').length ) {
						widget.addClass('disabled');
					}
				});
			} else {
				// Show all widgets
				$( '#hester-available-widgets-list .hester-widget' ).show();
			}
		},

		addWidget: function( widget_id_base ) {
			var widget_html,
				widget_uuid;

			widget_uuid = this.setting.id + '-' + this.widget_count;

			// Get widget form
			widget_html = $.trim( $(this.container).find( '.hester-widget-tpl-' + widget_id_base ).html() );
			widget_html = widget_html.replace( /<[^<>]+>/g, function( m ) {
				return m.replace( /__i__|%i%/g, widget_uuid );
			} );

			// Append new widget.
			var $widget = $( widget_html ).appendTo( this.container.find( '.hester-widget-container' ) );
			
			// Increase widget count.
			this.widget_count++;

			// Expand the widget and focus first setting.
			$widget.find( '.widget-top' ).trigger( 'click' );

			this.update();

			if ( $widget.find( '.hester-widget-nav-container' ).length ) {
				this.bindMenuLocation( $widget.find( '.hester-widget-nav-container' ) );
			}
		},

		close: function() {
			$( 'body' ).removeClass( 'hester-adding-widget' );
		},

		update: function() {

			// Get all widgets in the area
			var widgets = this.container.find( '.hester-widget-container .widget' );
			var inputs, widgetobj, new_value = [], option, checked, $widget;

			if ( widgets.length ) {

				// Get from each widfget
				_.each( widgets, function( widget ){

					$widget   = $( widget );
					widgetobj = {};
					widgetobj.classname = $widget.data( 'widget-base' );
					widgetobj.type = $widget.data( 'widget-type' );
					widgetobj.values = {};

					inputs = $widget.find( 'input, textarea, select' );

					_.each( inputs, function( input ){

						option = $( input ).attr('data-option-name');

						// Save values.
						if ( typeof option !== typeof undefined && option !== false) {
							widgetobj.values[ $(input).attr('data-option-name') ] = $(input).val();
						}
					});

					_.each( $widget.find( '.buttonset' ), function( buttonset ){

						// Save location if exist.
						checked = $( buttonset ).find( 'input[type="radio"]:checked');
							
						// Save values.
						if ( typeof checked !== typeof undefined && checked !== false) {
							widgetobj.values[ checked.data('option-name') ] = checked.val();
						}
					});

					new_value.push( widgetobj );
				});

				this.setting.set( new_value );
			} else {
				this.setting.set( false );
			}
		},

		setupSortable: function() {

			var self = this;

			$( this.container ).find( '.hester-widget-container' ).sortable({
				items: '> .widget',
				handle: '.widget-top',
				intersect: 'pointer',
				axis: 'y',
				update: function() {
					self.update();
				}
			});
		}
	});


 	$(document).ready( function(){

 		var control;

	 	$( '.wp-full-overlay' ).on( 'click', '.hester-add-widget, .hester-close-widgets-panel', function(e) {
	 		e.preventDefault();

	 		$( 'body' ).toggleClass( 'hester-adding-widget' );

	 		if ( $( this ).data( 'location-title' ) ) {
	 			control = wp.customize.control( $(this).data('control') );
	 			$( '#hester-available-widgets' ).attr( 'data-control', control.params.id ).find( '.hester-widget-caption' ).find( 'h3' ).html( $(this).data( 'location-title' ) );
	 		}
	 	});

	 	$( '.wp-full-overlay' ).on( 'click', '.customize-section-back', function(e) {
	 		$( 'body' ).removeClass( 'hester-adding-widget' );
	 		$( '#hester-available-widgets' ).removeAttr( 'data-control' );
	 	});

		// Add widget to widget control.
	 	$( '#hester-available-widgets' ).on( 'click', '.hester-widget', function(e) {

	 		// Get active control.
			control = wp.customize.control( $( '#hester-available-widgets' ).attr('data-control') );

	 		var widget_id = $( this ).data( 'widget-id' );
	 		var widget_form = control.addWidget( widget_id );
	 		
	 		control.close();
	 	});
	});
 	
})(jQuery);