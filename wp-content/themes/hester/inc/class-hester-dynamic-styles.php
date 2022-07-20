<?php

/**
 * Dynamically generate CSS code.
 * The code depends on options set in the Highend Options and Post/Page metaboxes.
 *
 * If possible, write the dynamically generated code into a .css file, otherwise return the code. The file is refreshed on each modification of metaboxes & theme options.
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.0.0
 */

/**
 * Do not allow direct script access.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Hester_Dynamic_Styles' ) ) :
	/**
	 * Dynamically generate CSS code.
	 */
	class Hester_Dynamic_Styles {

		/**
		 * Singleton instance of the class.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private static $instance;

		/**
		 * URI for Dynamic CSS file.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private $dynamic_css_uri;

		/**
		 * Path for Dynamic CSS file.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private $dynamic_css_path;

		/**
		 * Main Hester_Dynamic_Styles Instance.
		 *
		 * @since 1.0.0
		 * @return Hester_Dynamic_Styles
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Hester_Dynamic_Styles ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			$upload_dir = wp_upload_dir();

			$this->dynamic_css_uri  = trailingslashit( set_url_scheme( $upload_dir['baseurl'] ) ) . 'hester/';
			$this->dynamic_css_path = trailingslashit( set_url_scheme( $upload_dir['basedir'] ) ) . 'hester/';

			if ( ! is_customize_preview() && wp_is_writable( trailingslashit( $upload_dir['basedir'] ) ) ) {
				add_action( 'hester_enqueue_scripts', array( $this, 'enqueue_dynamic_style' ), 20 );
			} else {
				add_action( 'hester_enqueue_scripts', array( $this, 'print_dynamic_style' ), 99 );
			}

			// Include button styles.
			add_filter( 'hester_dynamic_styles', array( $this, 'get_button_styles' ), 6 );

			// Remove Customizer Custom CSS from wp_head, we will include it in our dynamic file.
			if ( ! is_customize_preview() ) {
				remove_action( 'wp_head', 'wp_custom_css_cb', 101 );
			}

			// Generate new styles on Customizer Save action.
			add_action( 'customize_save_after', array( $this, 'update_dynamic_file' ) );

			// Generate new styles on theme activation.
			add_action( 'after_switch_theme', array( $this, 'update_dynamic_file' ) );

			// Delete the css stye on theme deactivation.
			add_action( 'switch_theme', array( $this, 'delete_dynamic_file' ) );

			// Generate initial dynamic css.
			add_action( 'init', array( $this, 'init' ) );
		}

		/**
		 * Init.
		 *
		 * @since 1.0.0
		 */
		public function init() {

			// Ensure we have dynamic stylesheet generated.
			if ( false === get_transient( 'hester_has_dynamic_css' ) ) {
				$this->update_dynamic_file();
			}
		}

		/**
		 * Enqueues dynamic styles file.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_dynamic_style() {

			$exists = file_exists( $this->dynamic_css_path . 'dynamic-styles.css' );
			// Generate the file if it's missing.
			if ( ! $exists ) {
				$exists = $this->update_dynamic_file();
			}

			// Enqueue the file if available.
			if ( $exists ) {
				wp_enqueue_style(
					'hester-dynamic-styles',
					$this->dynamic_css_uri . 'dynamic-styles.css',
					false,
					filemtime( $this->dynamic_css_path . 'dynamic-styles.css' ),
					'all'
				);
			}
		}

		/**
		 * Prints inline dynamic styles if writing to file is not possible.
		 *
		 * @since 1.0.0
		 */
		public function print_dynamic_style() {
			$dynamic_css = $this->get_css();
			wp_add_inline_style( 'hester-styles', $dynamic_css );
		}

		/**
		 * Generates dynamic CSS code, minifies it and cleans cache.
		 *
		 * @param  boolean $custom_css - should we include the wp_get_custom_css.
		 * @return string, minifed code
		 * @since  1.0.0
		 */
		public function get_css( $custom_css = false ) {

			// Refresh options.
			hester()->options->refresh();

			// Delete google fonts enqueue transients.
			delete_transient( 'hester_google_fonts_enqueue' );

			// Add our theme custom CSS.
			$css = '';

			// Accent color.
			$accent_color = hester_option( 'accent_color' );

			$css .= '
				:root {
					--hester-primary: ' . $accent_color . ';
					--hester-primary_15: ' . hester_luminance( $accent_color, .15 ) . ';
					--hester-primary_09: ' . hester_hex2rgba( $accent_color, .09 ) . ';
					--hester-primary_04: ' . hester_hex2rgba( $accent_color, .04 ) . ';
				}
			';

			$header_layout_3_additional_css = '';

			if ( 'layout-3' === hester_option( 'header_layout' ) || is_customize_preview() ) {
				$header_layout_3_additional_css = '

					.hester-header-layout-3 .hester-logo-container > .hester-container {
						flex-wrap: wrap;
					}

					.hester-header-layout-3 .hester-logo-container .hester-logo > .logo-inner {
						align-items: flex-start;
					}
					
					.hester-header-layout-3 .hester-logo-container .hester-logo {
						order: 0;
						align-items: flex-start;
						flex-basis: auto;
						margin-left: 0;
					}

					.hester-header-layout-3 .hester-logo-container .hester-header-element {
						flex-basis: auto;
					}

					.hester-header-layout-3 .hester-logo-container .hester-mobile-nav {
						order: 5;
					}

				';
			}

			/**
			 * Top Bar.
			 */

			// Background.
			$css .= $this->get_design_options_field_css( '#hester-topbar', 'top_bar_background', 'background' );

			// Border.
			$css .= $this->get_design_options_field_css( '#hester-topbar', 'top_bar_border', 'border' );
			$css .= $this->get_design_options_field_css( '.hester-topbar-widget', 'top_bar_border', 'separator_color' );

			// Top Bar colors.
			$topbar_color = hester_option( 'top_bar_text_color' );

			// Top Bar text color.
			if ( isset( $topbar_color['text-color'] ) && $topbar_color['text-color'] ) {
				$css .= '#hester-topbar { color: ' . $topbar_color['text-color'] . '; }';
			}

			// Top Bar link color.
			if ( isset( $topbar_color['link-color'] ) && $topbar_color['link-color'] ) {
				$css .= '
					.hester-topbar-widget__text a,
					.hester-topbar-widget .hester-nav > ul > li > a,
					.hester-topbar-widget__socials .hester-social-nav > ul > li > a,
					#hester-topbar .hester-topbar-widget__text .hester-icon { 
						color: ' . $topbar_color['link-color'] . '; }
				';
			}

			// Top Bar link hover color.
			if ( isset( $topbar_color['link-hover-color'] ) && $topbar_color['link-hover-color'] ) {
				$css .= '
					#hester-topbar .hester-nav > ul > li > a:hover,
					#hester-topbar .hester-nav > ul > li.menu-item-has-children:hover > a,
					#hester-topbar .hester-nav > ul > li.current-menu-item > a,
					#hester-topbar .hester-nav > ul > li.current-menu-ancestor > a,
					#hester-topbar .hester-topbar-widget__text a:hover,
					#hester-topbar .hester-social-nav > ul > li > a .hester-icon.bottom-icon { 
						color: ' . $topbar_color['link-hover-color'] . '; }
				';
			}

			/**
			 * Header.
			 */

			// Background.
			$css .= $this->get_design_options_field_css( '#hester-header-inner', 'header_background', 'background' );

			// Font colors.
			$header_color = hester_option( 'header_text_color' );

			// Header text color.
			if ( isset( $header_color['text-color'] ) && $header_color['text-color'] ) {
				$css .= '.hester-logo .site-description { color: ' . $header_color['text-color'] . '; }';
			}

			// Header link color.
			if ( isset( $header_color['link-color'] ) && $header_color['link-color'] ) {
				$css .= '
					#hester-header,
					.hester-header-widgets a:not(.hester-btn),
					.hester-logo a,
					.hester-hamburger { 
						color: ' . $header_color['link-color'] . '; }
				';
			}

			// Header link hover color.
			if ( isset( $header_color['link-hover-color'] ) && $header_color['link-hover-color'] ) {
				$css .= '
					.hester-header-widgets a:not(.hester-btn):hover, 
					#hester-header-inner .hester-header-widgets .hester-active,
					.hester-logo .site-title a:hover, 
					.hester-hamburger:hover, 
					.is-mobile-menu-active .hester-hamburger,
					#hester-header-inner .hester-nav > ul > li > a:hover,
					#hester-header-inner .hester-nav > ul > li.menu-item-has-children:hover > a,
					#hester-header-inner .hester-nav > ul > li.current-menu-item > a,
					#hester-header-inner .hester-nav > ul > li.current-menu-ancestor > a,
					#hester-header-inner .hester-nav > ul > li.page_item_has_children:hover > a,
					#hester-header-inner .hester-nav > ul > li.current_page_item > a,
					#hester-header-inner .hester-nav > ul > li.current_page_ancestor > a {
						color: ' . $header_color['link-hover-color'] . ';
					}
					.hester-menu-animation-squareborder:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li > a:hover,
					.hester-menu-animation-squareborder:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li.menu-item-has-children:hover > a,
					.hester-menu-animation-squareborder:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li.current-menu-item > a,
					.hester-menu-animation-squareborder:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li.current-menu-ancestor > a,
					.hester-menu-animation-squareborder:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li.page_item_has_children:hover > a,
					.hester-menu-animation-squareborder:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li.current_page_item > a,
					.hester-menu-animation-squareborder:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li.current_page_ancestor > a {
						color: ' . $header_color['link-hover-color'] . ';
						border-color: ' . $header_color['link-hover-color'] . ';
					}
				';
			}

			// Header border.
			$css .= $this->get_design_options_field_css( '#hester-header-inner', 'header_border', 'border' );

			// Header separator color.
			$css .= $this->get_design_options_field_css( '.hester-header-widget', 'header_border', 'separator_color' );

			// Main navigation breakpoint.
			$css .= '
				@media screen and (max-width: ' . intval( hester_option( 'main_nav_mobile_breakpoint' ) ) . 'px) {

					#hester-header-inner .hester-nav {
						display: none;
						color: #000;
					}
					.hester-mobile-toggen,
					.hester-mobile-nav {
						display: inline-flex;
					}

					#hester-header-inner {
						position: relative;
					}

					#hester-header-inner .hester-nav > ul > li > a {
						color: inherit;
					}

					#hester-header-inner .hester-nav-container {
						position: static;
						border: none;
					}

					#hester-header-inner .site-navigation {
						display: none;
						position: absolute;
						top: 100%;
						width: 100%;
						left: 0;
						right: 0;
						margin: -1px 0 0;
						background: #FFF;
						border-top: 1px solid #eaeaea;
						box-shadow: 0 15px 25px -10px  rgba(50, 52, 54, 0.125);
						z-index: 999;
						font-size: 1.7rem;
						padding: 0;
					}

					#hester-header-inner .site-navigation > ul {
						overflow-y: auto;
						max-height: 25.5rem;
						display: block;
					}

					#hester-header-inner .site-navigation > ul > li > a {
						padding: 0 !important;
					}

					#hester-header-inner .site-navigation > ul li {
						display: block;
						width: 100%;
						padding: 0;
						margin: 0;
						margin-left: 0 !important;
					}

					#hester-header-inner .site-navigation > ul .sub-menu {
						position: static;
						display: none;
						border: none;
						box-shadow: none;
						border: 0;
						opacity: 1;
						visibility: visible;
						font-size: 1.7rem;
						transform: none;
						background: #f8f8f8;
						pointer-events: all;
						min-width: initial;
						left: 0;
						padding: 0;
						margin: 0;
						border-radius: 0;
						line-height: inherit;
					}

					#hester-header-inner .site-navigation > ul .sub-menu > li > a > span {
						padding-left: 50px !important;
					}

					#hester-header-inner .site-navigation > ul .sub-menu .sub-menu > li > a > span {
						padding-left: 70px !important;
					}

					#hester-header-inner .site-navigation > ul .sub-menu a > span {
						padding: 10px 30px 10px 50px;
					}

					#hester-header-inner .site-navigation > ul a {
						padding: 0;
						position: relative;
						background: none;
					}

					#hester-header-inner .site-navigation > ul li {
						border-bottom: 1px solid #eaeaea;
					}

					#hester-header-inner .site-navigation > ul a > span {
						padding: 10px 30px !important;
						width: 100%;
						display: block;
					}

					#hester-header-inner .site-navigation > ul a > span::after,
					#hester-header-inner .site-navigation > ul a > span::before {
						display: none !important;
					}

					#hester-header-inner .site-navigation > ul a > span.description {
						display: none;
					}

					#hester-header-inner .site-navigation > ul .menu-item-has-children > a {
						display: inline-flex;
    					width: 100%;
						max-width: calc(100% - 50px);
					}

					#hester-header-inner .hester-nav .menu-item-has-children>a > span, 
					#hester-header-inner .hester-nav .page_item_has_children>a > span {
					    border-right: 1px solid rgba(0,0,0,.09);
					}

					#hester-header-inner .hester-nav .menu-item-has-children>a > .hester-icon, 
					#hester-header-inner .hester-nav .page_item_has_children>a > .hester-icon {
						transform: none;
						width: 50px;
					    margin: 0;
					    position: absolute;
					    right: 0;
					    pointer-events: none;
					    height: 1em;
						display: none;
					}

					.hester-header-layout-3 .hester-widget-location-left .dropdown-item {
						left: auto;
						right: -7px;
					}

					.hester-header-layout-3 .hester-widget-location-left .dropdown-item::after {
						left: auto;
						right: 8px;
					}

					.hester-nav .sub-menu li.current-menu-item > a {
						font-weight: 500;
					}

					.hester-mobile-toggen {
						width: 50px;
						height: 1em;
						background: none;
						border: none;
						cursor: pointer;
					}

					.hester-mobile-toggen .hester-icon {
						transform: none;
						width: 50px;
						margin: 0;
						position: absolute;
						right: 0;
						pointer-events: none;
						height: 1em;
					}

					#hester-header-inner .site-navigation > ul .menu-item-has-children.hester-open > .hester-mobile-toggen > .hester-icon {
						transform: rotate(180deg);
					}

					' . $header_layout_3_additional_css . '
				}
			';

			/**
			 * Main Navigation.
			 */

			// Font Color.
			$main_nav_font_color = hester_option( 'main_nav_font_color' );

			if ( $main_nav_font_color['link-color'] ) {
				$css .= '#hester-header-inner .hester-nav > ul > li > a { color: ' . $main_nav_font_color['link-color'] . '; }';
			}

			if ( $main_nav_font_color['link-hover-color'] ) {
				$css .= '
					#hester-header-inner .hester-nav > ul > li > a:hover,
					#hester-header-inner .hester-nav > ul > li.menu-item-has-children:hover > a,
					#hester-header-inner .hester-nav > ul > li.current-menu-item > a,
					#hester-header-inner .hester-nav > ul > li.current-menu-ancestor > a,
					#hester-header-inner .hester-nav > ul > li.page_item_has_children:hover > a,
					#hester-header-inner .hester-nav > ul > li.current_page_item > a,
					#hester-header-inner .hester-nav > ul > li.current_page_ancestor > a {
						color: ' . $main_nav_font_color['link-hover-color'] . ';
					}
					.hester-menu-animation-squareborder:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li > a:hover,
					.hester-menu-animation-squareborder:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li.menu-item-has-children:hover > a,
					.hester-menu-animation-squareborder:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li.current-menu-item > a,
					.hester-menu-animation-squareborder:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li.current-menu-ancestor > a,
					.hester-menu-animation-squareborder:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li.page_item_has_children:hover > a,
					.hester-menu-animation-squareborder:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li.current_page_item > a,
					.hester-menu-animation-squareborder:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li.current_page_ancestor > a {
						color: ' . $main_nav_font_color['link-hover-color'] . ';
						border-color: ' . $main_nav_font_color['link-hover-color'] . ';
					}
				';
			}

			if ( 'layout-3' === hester_option( 'header_layout' ) ) {

				// Background.
				$css .= $this->get_design_options_field_css( '.hester-header-layout-3 .hester-nav-container', 'main_nav_background', 'background' );

				// Border.
				$css .= $this->get_design_options_field_css( '.hester-header-layout-3 .hester-nav-container', 'main_nav_border', 'border' );
			}

			// Font size.
			$css .= $this->get_range_field_css( '.hester-nav.hester-header-element, .hester-header-layout-1 .hester-header-widgets, .hester-header-layout-2 .hester-header-widgets', 'font-size', 'main_nav_font_size', false );

			/**
			 * Hero Section.
			 */
			if ( hester_option( 'enable_hero' ) ) {
				// Hero height.
				$css .= '#hero .hester-hover-slider .hover-slide-item { height: ' . hester_option( 'hero_hover_slider_height' ) . 'px; }';
			}

			/**
			 * Pre Footer.
			 */
			if ( hester_option( 'enable_pre_footer_cta' ) ) {

				// Call to Action.
				if ( hester_option( 'enable_pre_footer_cta' ) ) {

					$cta_style = absint( hester_option( 'pre_footer_cta_style' ) );

					// Background.
					$cta_background = hester_option( 'pre_footer_cta_background' );

					if ( 1 === $cta_style || is_customize_preview() ) {
						$css .= $this->get_design_options_field_css( '.hester-pre-footer-cta-style-1 #hester-pre-footer .hester-flex-row::after', 'pre_footer_cta_background', 'background' );
					}

					if ( 2 === $cta_style || is_customize_preview() ) {
						$css .= $this->get_design_options_field_css( '.hester-pre-footer-cta-style-2 #hester-pre-footer::after', 'pre_footer_cta_background', 'background' );
					}

					if ( 'image' === $cta_background['background-type'] && isset( $cta_background['background-color-overlay'] ) && $cta_background['background-color-overlay'] ) {
						$css .= '
							.hester-pre-footer-cta-style-1 #hester-pre-footer .hester-flex-row::before,
			 				.hester-pre-footer-cta-style-2 #hester-pre-footer::before {
			 					background-color: ' . $cta_background['background-color-overlay'] . ';
			 				}
			 				';
					}

					// Text color.
					$css .= $this->get_design_options_field_css( '#hester-pre-footer .h2, #hester-pre-footer .h3, #hester-pre-footer .h4', 'pre_footer_cta_text_color', 'color' );

					// Border.
					if ( 1 === $cta_style || is_customize_preview() ) {
						$css .= $this->get_design_options_field_css( '.hester-pre-footer-cta-style-1 #hester-pre-footer .hester-flex-row::before', 'pre_footer_cta_border', 'border' );
					}

					if ( 2 === $cta_style || is_customize_preview() ) {
						$css .= $this->get_design_options_field_css( '.hester-pre-footer-cta-style-2 #hester-pre-footer::before', 'pre_footer_cta_border', 'border' );
					}

					// Font size.
					$css .= $this->get_range_field_css( '#hester-pre-footer .h3', 'font-size', 'pre_footer_cta_font_size', true );
				}
			}

			// Footer Background.
			if ( hester_option( 'enable_footer' ) || hester_option( 'enable_copyright' ) ) {

				// Background.
				$css .= $this->get_design_options_field_css( '#colophon', 'footer_background', 'background' );

				// Footer font color.
				$footer_font_color = hester_option( 'footer_text_color' );

				// Footer text color.
				if ( isset( $footer_font_color['text-color'] ) && $footer_font_color['text-color'] ) {
					$css .= '
						#colophon { 
							color: ' . $footer_font_color['text-color'] . ';
						}
					';
				}

				// Footer link color.
				if ( isset( $footer_font_color['link-color'] ) && $footer_font_color['link-color'] ) {
					$css .= '
						#colophon a { 
							color: ' . $footer_font_color['link-color'] . '; 
						}
					';
				}

				// Footer link hover color.
				if ( isset( $footer_font_color['link-hover-color'] ) && $footer_font_color['link-hover-color'] ) {
					$css .= '
						#colophon a:hover,
						.using-keyboard #colophon a:focus,
						#colophon li.current_page_item > a,
						#colophon .hester-social-nav > ul > li > a .hester-icon.bottom-icon { 
							color: ' . $footer_font_color['link-hover-color'] . ';
						}
					';
				}

				// Footer widget title.
				if ( isset( $footer_font_color['widget-title-color'] ) && $footer_font_color['widget-title-color'] ) {
					$css .= '
						#colophon .widget-title { 
							color: ' . $footer_font_color['widget-title-color'] . ';
						}
					';
				}
			}

			// Main Footer border.
			if ( hester_option( 'enable_footer' ) ) {

				// Border.
				$footer_border = hester_option( 'footer_border' );

				if ( $footer_border['border-top-width'] ) {
					$css .= '
						#colophon {
							border-top-width: ' . $footer_border['border-top-width'] . 'px;
							border-top-style: ' . $footer_border['border-style'] . ';
							border-top-color: ' . $footer_border['border-color'] . ';
						}
					';
				}

				if ( $footer_border['border-bottom-width'] ) {
					$css .= '
						#colophon {
							border-bottom-width: ' . $footer_border['border-bottom-width'] . 'px;
							border-bottom-style: ' . $footer_border['border-style'] . ';
							border-bottom-color: ' . $footer_border['border-color'] . ';
						}
					';
				}
			}

			// Sidebar.
			$css .= '
				#secondary {
					width: ' . intval( hester_option( 'sidebar_width' ) ) . '%;
				}

				body:not(.hester-no-sidebar) #primary {
					max-width: ' . intval( 100 - intval( hester_option( 'sidebar_width' ) ) ) . '%;
				}
			';

			// Content background.
			$boxed_content_background_color = hester_option( 'boxed_content_background_color' );

			// Boxed Separated Layout specific CSS.
			$css .= '
				.hester-layout__boxed-separated.author .author-box, 
				.hester-layout__boxed-separated #content, 
				.hester-layout__boxed-separated.hester-sidebar-style-3 #secondary .hester-widget, 
				.hester-layout__boxed-separated.hester-sidebar-style-3 .elementor-widget-sidebar .hester-widget, 
				.hester-layout__boxed-separated.blog .hester-article, 
				.hester-layout__boxed-separated.search-results .hester-article, 
				.hester-layout__boxed-separated.category .hester-article {
					background-color: ' . $boxed_content_background_color . ';
				}

				@media screen and (max-width: 960px) {
					.hester-layout__boxed-separated #page {
						background-color: ' . $boxed_content_background_color . ';
					}
				}
			';

			$css .= '
				.hester-layout__boxed #page {
					background-color: ' . $boxed_content_background_color . ';
				}
			';

			// Content text color.
			$content_text_color = hester_option( 'content_text_color' );

			$css .= '
				body {
					color: ' . $content_text_color . ';
				}

				:root {
					--hester-secondary_38: ' . $content_text_color . ';
				}

				.comment-form .comment-notes,
				#comments .no-comments,
				#page .wp-caption .wp-caption-text,
				#comments .comment-meta,
				.comments-closed,
				.entry-meta,
				.hester-entry cite,
				legend,
				.hester-page-header-description,
				.page-links em,
				.site-content .page-links em,
				.single .entry-footer .last-updated,
				.single .post-nav .post-nav-title,
				#main .widget_recent_comments span,
				#main .widget_recent_entries span,
				#main .widget_calendar table > caption,
				.post-thumb-caption,
				.wp-block-image figcaption,
				.wp-block-embed figcaption {
					color: ' . $content_text_color . ';
				}
			';

			// hester_hex2rgba( $content_text_color, 0.73 )
			// Lightened or darkened background color for backgrounds, borders & inputs.
			$background_color = hester_get_background_color();

			$content_text_color_offset = hester_light_or_dark( $background_color, hester_luminance( $background_color, -0.045 ), hester_luminance( $background_color, 0.2 ) );

			// Only add for dark background color.
			if ( ! hester_is_light_color( $background_color ) ) {
				$css .= '
					#content textarea,
					#content input[type="text"],
					#content input[type="number"],
					#content input[type="email"],
					#content input[type=password],
					#content input[type=tel],
					#content input[type=url],
					#content input[type=search],
					#content input[type=date] {
						background-color: ' . $background_color . ';
					}
				';

				// Offset border color.
				$css .= '
					.hester-sidebar-style-3 #secondary .hester-widget {
						border-color: ' . $content_text_color_offset . ';
					}
				';

				// Offset background color.
				$css .= '
					.entry-meta .entry-meta-elements > span:before {
						background-color: ' . $content_text_color_offset . ';
					}
				';
			}

			// Content link hover color.
			$css .= '
				.content-area a:not(.hester-btn):not(.wp-block-button__link):hover,
				#secondary .hester-core-custom-list-widget .hester-entry a:not(.hester-btn):hover,
				.hester-breadcrumbs a:hover {
					color: ' . hester_option( 'content_link_hover_color' ) . ';
				}
			';

			// Headings Color.
			$css .= '
				h1, h2, h3, h4, h5, h6,
				.h1, .h2, .h3, .h4,
				.hester-logo .site-title,
				.error-404 .page-header h1 {
					color: ' . hester_option( 'headings_color' ) . ';
				}
				:root {
					--hester-secondary: ' . hester_option( 'headings_color' ) . ';
				}
			';

			// Container width.
			$css .= '
				.hester-container,
				.alignfull.hester-wrap-content > div {
					max-width: ' . hester_option( 'container_width' ) . 'px;
				}

				.hester-layout__boxed #page,
				.hester-layout__boxed.hester-sticky-header.hester-is-mobile #hester-header-inner,
				.hester-layout__boxed.hester-sticky-header:not(.hester-header-layout-3) #hester-header-inner,
				.hester-layout__boxed.hester-sticky-header:not(.hester-is-mobile).hester-header-layout-3 #hester-header-inner .hester-nav-container > .hester-container {
					max-width: ' . ( intval( hester_option( 'container_width' ) ) + 100 ) . 'px;
				}
			';

			// Adjust fullwidth sections for boxed layouts.
			if ( 'boxed' === hester_option( 'site_layout' ) || is_customize_preview() ) {
				$css .= '
					@media screen and (max-width: ' . intval( hester_option( 'container_width' ) ) . 'px) {
						body.hester-layout__boxed.hester-no-sidebar .elementor-section.elementor-section-stretched,
						body.hester-layout__boxed.hester-no-sidebar .hester-fw-section,
						body.hester-layout__boxed.hester-no-sidebar .entry-content .alignfull {
							margin-left: -5rem !important;
							margin-right: -5rem !important;
						}
					}
				';
			}

			// Logo max height.
			$css .= $this->get_range_field_css( '.hester-logo img', 'max-height', 'logo_max_height' );
			$css .= $this->get_range_field_css( '.hester-logo img.hester-svg-logo', 'height', 'logo_max_height' );

			// Logo margin.
			$css .= $this->get_spacing_field_css( '.hester-logo .logo-inner', 'margin', 'logo_margin' );

			/**
			 * Transparent header.
			 */

			// Logo max height.
			$css .= $this->get_range_field_css( '.hester-tsp-header .hester-logo img', 'max-height', 'tsp_logo_max_height' );
			$css .= $this->get_range_field_css( '.hester-tsp-header .hester-logo img.hester-svg-logo', 'height', 'tsp_logo_max_height' );

			// Logo margin.
			$css .= $this->get_spacing_field_css( '.hester-tsp-header .hester-logo .logo-inner', 'margin', 'tsp_logo_margin' );

			// Main Header custom background.
			$css .= $this->get_design_options_field_css( '.hester-tsp-header #hester-header-inner', 'tsp_header_background', 'background' );

			/** Font Colors */

			$tsp_font_color = hester_option( 'tsp_header_font_color' );

			// Header text color.
			if ( isset( $tsp_font_color['text-color'] ) && $tsp_font_color['text-color'] ) {
				$css .= '
					.hester-tsp-header .hester-logo .site-description {
						color: ' . $tsp_font_color['text-color'] . ';
					}
				';
			}

			// Header link color.
			if ( isset( $tsp_font_color['link-color'] ) && $tsp_font_color['link-color'] ) {
				$css .= '
					.hester-tsp-header #hester-header,
					.hester-tsp-header .hester-header-widgets a:not(.hester-btn),
					.hester-tsp-header .hester-logo a,
					.hester-tsp-header .hester-hamburger,
					.hester-tsp-header #hester-header-inner .hester-nav > ul > li > a { 
						color: ' . $tsp_font_color['link-color'] . ';
					}
				';
			}

			// Header link hover color.
			if ( isset( $tsp_font_color['link-hover-color'] ) && $tsp_font_color['link-hover-color'] ) {
				$css .= '
					.hester-tsp-header .hester-header-widgets a:not(.hester-btn):hover, 
					.hester-tsp-header #hester-header-inner .hester-header-widgets .hester-active,
					.hester-tsp-header .hester-logo .site-title a:hover, 
					.hester-tsp-header .hester-hamburger:hover, 
					.is-mobile-menu-active .hester-tsp-header .hester-hamburger,
					.hester-tsp-header.using-keyboard .site-title a:focus,
					.hester-tsp-header.using-keyboard .hester-header-widgets a:not(.hester-btn):focus,
					.hester-tsp-header #hester-header-inner .hester-nav > ul > li.hovered > a,
					.hester-tsp-header #hester-header-inner .hester-nav > ul > li > a:hover,
					.hester-tsp-header #hester-header-inner .hester-nav > ul > li.menu-item-has-children:hover > a,
					.hester-tsp-header #hester-header-inner .hester-nav > ul > li.current-menu-item > a,
					.hester-tsp-header #hester-header-inner .hester-nav > ul > li.current-menu-ancestor > a,
					.hester-tsp-header #hester-header-inner .hester-nav > ul > li.page_item_has_children:hover > a,
					.hester-tsp-header #hester-header-inner .hester-nav > ul > li.current_page_item > a,
					.hester-tsp-header #hester-header-inner .hester-nav > ul > li.current_page_ancestor > a {
						color: ' . $tsp_font_color['link-hover-color'] . ';
					}
					.hester-menu-animation-squareborder.hester-tsp-header:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li > a:hover,
					.hester-menu-animation-squareborder.hester-tsp-header:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li.menu-item-has-children:hover > a,
					.hester-menu-animation-squareborder.hester-tsp-header:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li.current-menu-item > a,
					.hester-menu-animation-squareborder.hester-tsp-header:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li.current-menu-ancestor > a,
					.hester-menu-animation-squareborder.hester-tsp-header:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li.page_item_has_children:hover > a,
					.hester-menu-animation-squareborder.hester-tsp-header:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li.current_page_item > a,
					.hester-menu-animation-squareborder.hester-tsp-header:not(.hester-is-mobile) #hester-header-inner .hester-nav > ul > li.current_page_ancestor > a {
						color: ' . $tsp_font_color['link-hover-color'] . ';
						border-color: ' . $tsp_font_color['link-hover-color'] . ';
					}
				';
			}

			/** Border Color */
			$css .= $this->get_design_options_field_css( '.hester-tsp-header #hester-header-inner', 'tsp_header_border', 'border' );

			/** Separator Color */
			$css .= $this->get_design_options_field_css( '.hester-tsp-header .hester-header-widget', 'tsp_header_border', 'separator_color' );

			/**
			 * Page Header.
			 */
			if ( hester_option( 'page_header_enable' ) ) {

				// Font size.
				$css .= $this->get_range_field_css( '#page .page-header .page-title', 'font-size', 'page_header_font_size', true );

				// Page Title spacing.
				$css .= $this->get_spacing_field_css( '.hester-page-title-align-left .page-header.hester-has-page-title, .hester-page-title-align-right .page-header.hester-has-page-title, .hester-page-title-align-center .page-header .hester-page-header-wrapper', 'padding', 'page_header_spacing' );

				// Page Header background.
				$css .= $this->get_design_options_field_css( '.hester-tsp-header:not(.hester-tsp-absolute) #masthead', 'page_header_background', 'background' );
				$css .= $this->get_design_options_field_css( '.page-header', 'page_header_background', 'background' );

				// Page Header font color.
				$page_header_color = hester_option( 'page_header_text_color' );

				// Page Header text color.
				if ( isset( $page_header_color['text-color'] ) && $page_header_color['text-color'] ) {
					$css .= '
						.page-header .page-title { 
							color: ' . $page_header_color['text-color'] . '; }

						.page-header .hester-page-header-description {
							color: ' . hester_hex2rgba( $page_header_color['text-color'], 0.75 ) . '; 
						}
					';
				}

				// Page Header link color.
				if ( isset( $page_header_color['link-color'] ) && $page_header_color['link-color'] ) {
					$css .= '
						.page-header .hester-breadcrumbs a { 
							color: ' . $page_header_color['link-color'] . '; }

						.page-header .hester-breadcrumbs span,
						.page-header .breadcrumb-trail .trail-items li::after, .page-header .hester-breadcrumbs .separator {
							color: ' . hester_hex2rgba( $page_header_color['link-color'], 0.75 ) . '; 
						}
					';
				}

				// Page Header link hover color.
				if ( isset( $page_header_color['link-hover-color'] ) && $page_header_color['link-hover-color'] ) {
					$css .= '
						.page-header .hester-breadcrumbs a:hover { 
							color: ' . $page_header_color['link-hover-color'] . '; }
					';
				}

				// Page Header border color.
				$page_header_border = hester_option( 'page_header_border' );

				$css .= $this->get_design_options_field_css( '.page-header', 'page_header_border', 'border' );
			}

			/**
			 * Breadcrumbs.
			 */
			if ( hester_option( 'breadcrumbs_enable' ) ) {

				// Spacing.
				$css .= $this->get_spacing_field_css( '.hester-breadcrumbs', 'padding', 'breadcrumbs_spacing' );

				if ( 'below-header' === hester_option( 'breadcrumbs_position' ) ) {

					// Background.
					$css .= $this->get_design_options_field_css( '.hester-breadcrumbs', 'breadcrumbs_background', 'background' );

					// Border.
					$css .= $this->get_design_options_field_css( '.hester-breadcrumbs', 'breadcrumbs_border', 'border' );

					// Text Color.
					$css .= $this->get_design_options_field_css( '.hester-breadcrumbs', 'breadcrumbs_text_color', 'color' );
				}
			}

			/**
			 * Copyright Bar.
			 */
			if ( hester_option( 'enable_copyright' ) ) {
				$css .= $this->get_design_options_field_css( '#hester-copyright', 'copyright_background', 'background' );

				// Copyright font color.
				$copyright_color = hester_option( 'copyright_text_color' );

				// Copyright text color.
				if ( isset( $copyright_color['text-color'] ) && $copyright_color['text-color'] ) {
					$css .= '
						#hester-copyright { 
							color: ' . $copyright_color['text-color'] . '; }
					';
				}

				// Copyright link color.
				if ( isset( $copyright_color['link-color'] ) && $copyright_color['link-color'] ) {
					$css .= '
						#hester-copyright a { 
							color: ' . $copyright_color['link-color'] . '; }
					';
				}

				// Copyright link hover color.
				if ( isset( $copyright_color['link-hover-color'] ) && $copyright_color['link-hover-color'] ) {
					$css .= '
						#hester-copyright a:hover,
						.using-keyboard #hester-copyright a:focus,
						#hester-copyright .hester-social-nav > ul > li > a .hester-icon.bottom-icon,
						#hester-copyright .hester-nav > ul > li.current-menu-item > a,
						#hester-copyright .hester-nav > ul > li.current-menu-ancestor > a,
						#hester-copyright .hester-nav > ul > li:hover > a { 
							color: ' . $copyright_color['link-hover-color'] . '; }
					';
				}

				// Copyright separator color.
				$footer_text_color = hester_option( 'footer_text_color' );
				$footer_text_color = $footer_text_color['text-color'];

				$copyright_separator_color = hester_light_or_dark( $footer_text_color, 'rgba(255,255,255,0.1)', 'rgba(0,0,0,0.1)' );

				$css .= '
					#hester-copyright.contained-separator > .hester-container::before {
						background-color: ' . $copyright_separator_color . ';
					}

					#hester-copyright.fw-separator {
						border-top-color: ' . $copyright_separator_color . ';
					}
				';
			}

			/**
			 * Typography.
			 */

			// Base HTML font size.
			$css .= $this->get_range_field_css( 'html', 'font-size', 'html_base_font_size', true, '%' );

			// Font smoothing.
			if ( hester_option( 'font_smoothing' ) ) {
				$css .= '
					* {
						-moz-osx-font-smoothing: grayscale;
						-webkit-font-smoothing: antialiased;
					}
				';
			}

			// Body.
			$css .= $this->get_typography_field_css( 'body', 'body_font' );

			// Headings.
			$css .= $this->get_typography_field_css( 'h1, .h1, .hester-logo .site-title, .page-header .page-title, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6', 'headings_font' );

			$css .= $this->get_typography_field_css( 'h1, .h1, .hester-logo .site-title, .page-header .page-title', 'h1_font' );
			$css .= $this->get_typography_field_css( 'h2, .h2', 'h2_font' );
			$css .= $this->get_typography_field_css( 'h3, .h3', 'h3_font' );
			$css .= $this->get_typography_field_css( 'h4, .h4', 'h4_font' );
			$css .= $this->get_typography_field_css( 'h5, .h5', 'h5_font' );
			$css .= $this->get_typography_field_css( 'h6, .h6', 'h6_font' );
			$css .= $this->get_typography_field_css( 'h1 em, h2 em, h3 em, h4 em, h5 em, h6 em, .h1 em, .h2 em, .h3 em, .h4 em, .h5 em, .h6 em, .hester-logo .site-title em, .error-404 .page-header h1 em', 'heading_em_font' );

			// Emphasized Heading.
			$css .= $this->get_typography_field_css( 'h1 em, h2 em, h3 em, h4 em, h5 em, h6 em, .h1 em, .h2 em, .h3 em, .h4 em, .h5 em, .h6 em, .hester-logo .site-title em, .error-404 .page-header h1 em', 'heading_em_font' );

			// Site Title font size.
			$css .= $this->get_range_field_css( '#hester-header .hester-logo .site-title', 'font-size', 'logo_text_font_size', true );

			// Sidebar widget title.
			$css .= $this->get_range_field_css( '#main .widget-title', 'font-size', 'sidebar_widget_title_font_size', true );

			// Footer widget title.
			$css .= $this->get_range_field_css( '#colophon .widget-title', 'font-size', 'footer_widget_title_font_size', true );

			// Blog Single Post - Title Spacing.
			$css .= $this->get_spacing_field_css( '.hester-single-title-in-page-header #page .page-header .hester-page-header-wrapper', 'padding', 'single_title_spacing', true );

			// Blog Single Post - Content Font Size.
			$css .= $this->get_range_field_css( '.single-post .entry-content', 'font-size', 'single_content_font_size', true );

			// Blog Single Post - narrow container.
			if ( 'narrow' === hester_option( 'single_content_width' ) ) {
				$css .= '
					.single-post.narrow-content .entry-content > :not([class*="align"]):not([class*="gallery"]):not(.wp-block-image):not(.quote-inner):not(.quote-post-bg), 
					.single-post.narrow-content .mce-content-body:not([class*="page-template-full-width"]) > :not([class*="align"]):not([data-wpview-type*="gallery"]):not(blockquote):not(.mceTemp), 
					.single-post.narrow-content .entry-footer, 
					.single-post.narrow-content .entry-content > .alignwide,
					.single-post.narrow-content p.has-background:not(.alignfull):not(.alignwide),
					.single-post.narrow-content .post-nav, 
					.single-post.narrow-content #hester-comments-toggle, 
					.single-post.narrow-content #comments, 
					.single-post.narrow-content .entry-content .aligncenter, .single-post.narrow-content .hester-narrow-element, 
					.single-post.narrow-content.hester-single-title-in-content .entry-header, 
					.single-post.narrow-content.hester-single-title-in-content .entry-meta, 
					.single-post.narrow-content.hester-single-title-in-content .post-category,
					.single-post.narrow-content.hester-no-sidebar .hester-page-header-wrapper,
					.single-post.narrow-content.hester-no-sidebar .hester-breadcrumbs nav {
						max-width: ' . hester_option( 'single_narrow_container_width' ) . 'px;
						margin-left: auto;
						margin-right: auto;
					}

					.single-post.narrow-content .author-box,
					.single-post.narrow-content .entry-content > .alignwide,
					.single.hester-single-title-in-page-header .page-header.hester-align-center .hester-page-header-wrapper {
						max-width: ' . ( intval( hester_option( 'single_narrow_container_width' ) ) + 70 ) . 'px;
					}
				';
			}

			// Slider Height.
			$css .= $this->get_range_field_css( '.starter__slider-section .starter__slider, .starter__slider-wrapper, .starter__slider-image img', 'min-height', 'slider_height', true );
			$css .= $this->get_range_field_css( '.starter__slider-section .starter__slider, .starter__slider-wrapper, .starter__slider-image img', 'max-height', 'slider_height', true );

			// Slider Heading Title.
			$css .= $this->get_typography_field_css( '.starter__slider .starter__slider-title', 'slider_title_font' );

			// Common Front Section CSS
			$sections = hester_design_common_section();
			foreach ( $sections as $section ) {
				$css .= $this->get_spacing_field_css( ".hester_section_{$section} .hester_bg", 'padding', "{$section}_section_spacing" );

				$section_background = hester_option( "{$section}_background" );

				$css .= $this->get_design_options_field_css( ".hester_section_{$section} .hester_bg::after", "{$section}_background", 'background' );
				$css .= $this->get_design_options_field_css( ".hester_section_{$section} .hester_bg", "{$section}_text_color", 'color' );

				if ( is_array( hester_option( "{$section}_text_color" ) ) && ! empty( hester_option( "{$section}_text_color" ) ) ) {
					$css .= "
					.hester_section_{$section} .hester_bg .starter__heading-title .title {
						color: inherit;
					}";
				}

				if ( is_array( $section_background ) && 'image' === $section_background['background-type'] && isset( $section_background['background-color-overlay'] ) && $section_background['background-color-overlay'] ) {
					$css .= "
					.hester_section_{$section} .hester_bg::before {
					background-color: " . $section_background['background-color-overlay'] . ';
				}';
				}
			}

			// Allow CSS to be filtered.
			$css = apply_filters( 'hester_dynamic_styles', $css );

			// Add user custom CSS.
			if ( $custom_css || ! is_customize_preview() ) {
				$css .= wp_get_custom_css();
			}

			// Minify the CSS code.
			$css = $this->minify( $css );

			return $css;
		}

		/**
		 * Update dynamic css file with new CSS. Cleans caches after that.
		 *
		 * @return [Boolean] returns true if successfully updated the dynamic file.
		 */
		public function update_dynamic_file() {

			$css = $this->get_css( true );

			if ( empty( $css ) || '' === trim( $css ) ) {
				return;
			}

			// Load file.php file.
			require_once ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'file.php'; // phpcs:ignore

			global $wp_filesystem;

			// Check if the the global filesystem isn't setup yet.
			if ( is_null( $wp_filesystem ) ) {
				WP_Filesystem();
			}

			$wp_filesystem->mkdir( $this->dynamic_css_path );

			if ( $wp_filesystem->put_contents( $this->dynamic_css_path . 'dynamic-styles.css', $css ) ) {
				$this->clean_cache();
				set_transient( 'hester_has_dynamic_css', true, 0 );
				return true;
			}

			return false;
		}

		/**
		 * Delete dynamic css file.
		 *
		 * @return void
		 */
		public function delete_dynamic_file() {

			// Load file.php file.
			require_once ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'file.php'; // phpcs:ignore

			global $wp_filesystem;

			// Check if the the global filesystem isn't setup yet.
			if ( is_null( $wp_filesystem ) ) {
				WP_Filesystem();
			}

			$wp_filesystem->delete( $this->dynamic_css_path . 'dynamic-styles.css' );

			delete_transient( 'hester_has_dynamic_css' );
		}

		/**
		 * Simple CSS code minification.
		 *
		 * @param  string $css code to be minified.
		 * @return string, minifed code
		 * @since  1.0.0
		 */
		private function minify( $css ) {
			$css = preg_replace( '/\s+/', ' ', $css );
			$css = preg_replace( '/\/\*[^\!](.*?)\*\//', '', $css );
			$css = preg_replace( '/(,|:|;|\{|}) /', '$1', $css );
			$css = preg_replace( '/ (,|;|\{|})/', '$1', $css );
			$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
			$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );

			return trim( $css );
		}

		/**
		 * Cleans various caches. Compatible with cache plugins.
		 *
		 * @since 1.0.0
		 */
		private function clean_cache() {

			// If W3 Total Cache is being used, clear the cache.
			if ( function_exists( 'w3tc_pgcache_flush' ) ) {
				w3tc_pgcache_flush();
			}

			// if WP Super Cache is being used, clear the cache.
			if ( function_exists( 'wp_cache_clean_cache' ) ) {
				global $file_prefix;
				wp_cache_clean_cache( $file_prefix );
			}

			// If SG CachePress is installed, reset its caches.
			if ( class_exists( 'SG_CachePress_Supercacher' ) ) {
				if ( method_exists( 'SG_CachePress_Supercacher', 'purge_cache' ) ) {
					SG_CachePress_Supercacher::purge_cache();
				}
			}

			// Clear caches on WPEngine-hosted sites.
			if ( class_exists( 'WpeCommon' ) ) {

				if ( method_exists( 'WpeCommon', 'purge_memcached' ) ) {
					WpeCommon::purge_memcached();
				}

				if ( method_exists( 'WpeCommon', 'clear_maxcdn_cache' ) ) {
					WpeCommon::clear_maxcdn_cache();
				}

				if ( method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
					WpeCommon::purge_varnish_cache();
				}
			}

			// Clean OpCache.
			if ( function_exists( 'opcache_reset' ) ) {
				opcache_reset(); // phpcs:ignore PHPCompatibility.FunctionUse.NewFunctions.opcache_resetFound
			}

			// Clean WordPress cache.
			if ( function_exists( 'wp_cache_flush' ) ) {
				wp_cache_flush();
			}
		}

		/**
		 * Prints spacing field CSS based on passed params.
		 *
		 * @since  1.0.0
		 *
		 * @param  string $css_selector CSS selector.
		 * @param  string $css_property CSS property, such as 'margin', 'padding' or 'border'.
		 * @param  string $setting_id The ID of the customizer setting containing all information about the setting.
		 * @param  bool   $responsive Has responsive values.
		 * @return string  Generated CSS.
		 */
		public function get_spacing_field_css( $css_selector, $css_property, $setting_id, $responsive = true ) {

			// Get the saved setting.
			$setting = hester_option( $setting_id );

			// If setting doesn't exist, return.
			if ( ! is_array( $setting ) ) {
				return;
			}

			// Get the unit. Defaults to px.
			$unit = 'px';

			if ( isset( $setting['unit'] ) ) {
				if ( $setting['unit'] ) {
					$unit = $setting['unit'];
				}

				unset( $setting['unit'] );
			}

			// CSS buffer.
			$css_buffer = '';

			// Loop through options.
			foreach ( $setting as $key => $value ) {

				// Check if responsive options are available.
				if ( is_array( $value ) ) {

					if ( 'desktop' === $key ) {
						$mq_open  = '';
						$mq_close = '';
					} elseif ( 'tablet' === $key ) {
						$mq_open  = '@media only screen and (max-width: 768px) {';
						$mq_close = '}';
					} elseif ( 'mobile' === $key ) {
						$mq_open  = '@media only screen and (max-width: 480px) {';
						$mq_close = '}';
					} else {
						$mq_open  = '';
						$mq_close = '';
					}

					// Add media query prefix.
					$css_buffer .= $mq_open . $css_selector . '{';

					// Loop through all choices.
					foreach ( $value as $pos => $val ) {

						if ( empty( $val ) ) {
							continue;
						}

						if ( 'border' === $css_property ) {
							$pos .= '-width';
						}

						$css_buffer .= $css_property . '-' . $pos . ': ' . intval( $val ) . $unit . ';';
					}

					$css_buffer .= '}' . $mq_close;
				} else {

					if ( 'border' === $css_property ) {
						$key .= '-width';
					}

					$css_buffer .= $css_property . '-' . $key . ': ' . intval( $value ) . $unit . ';';
				}
			}

			// Check if field is has responsive values.
			if ( ! $responsive ) {
				$css_buffer = $css_selector . '{' . $css_buffer . '}';
			}

			// Finally, return the generated CSS code.
			return $css_buffer;
		}

		/**
		 * Prints range field CSS based on passed params.
		 *
		 * @since  1.0.0
		 *
		 * @param  string $css_selector CSS selector.
		 * @param  string $css_property CSS property, such as 'margin', 'padding' or 'border'.
		 * @param  string $setting_id The ID of the customizer setting containing all information about the setting.
		 * @param  bool   $responsive Has responsive values.
		 * @param  string $unit Unit.
		 * @return string  Generated CSS.
		 */
		public function get_range_field_css( $css_selector, $css_property, $setting_id, $responsive = true, $unit = 'px' ) {

			// Get the saved setting.
			$setting = hester_option( $setting_id );

			// If just a single value option.
			if ( ! is_array( $setting ) ) {
				return $css_selector . ' { ' . $css_property . ': ' . $setting . $unit . '; }';
			}

			// Resolve units.
			if ( isset( $setting['unit'] ) ) {
				if ( $setting['unit'] ) {
					$unit = $setting['unit'];
				}

				unset( $setting['unit'] );
			}

			// CSS buffer.
			$css_buffer = '';

			if ( is_array( $setting ) && ! empty( $setting ) ) {

				// Media query syntax wrap.
				$mq_open  = '';
				$mq_close = '';

				// Loop through options.
				foreach ( $setting as $key => $value ) {

					if ( empty( $value ) ) {
						continue;
					}

					if ( 'desktop' === $key ) {
						$mq_open  = '';
						$mq_close = '';
					} elseif ( 'tablet' === $key ) {
						$mq_open  = '@media only screen and (max-width: 768px) {';
						$mq_close = '}';
					} elseif ( 'mobile' === $key ) {
						$mq_open  = '@media only screen and (max-width: 480px) {';
						$mq_close = '}';
					} else {
						$mq_open  = '';
						$mq_close = '';
					}

					// Add media query prefix.
					$css_buffer .= $mq_open . $css_selector . '{';
					$css_buffer .= $css_property . ': ' . floatval( $value ) . $unit . ';';
					$css_buffer .= '}' . $mq_close;
				}
			}

			// Finally, return the generated CSS code.
			return $css_buffer;
		}

		/**
		 * Prints design options field CSS based on passed params.
		 *
		 * @since 1.0.0
		 * @param string       $css_selector CSS selector.
		 * @param string|mixed $setting The ID of the customizer setting containing all information about the setting.
		 * @param string       $type Design options field type.
		 * @return string      Generated CSS.
		 */
		public function get_design_options_field_css( $css_selector, $setting, $type ) {

			if ( is_string( $setting ) ) {
				// Get the saved setting.
				$setting = hester_option( $setting );
			}

			// Setting has to be array.
			if ( ! is_array( $setting ) || empty( $setting ) ) {
				return;
			}

			// CSS buffer.
			$css_buffer = '';

			// Background.
			if ( 'background' === $type ) {

				// Background type.
				$background_type = $setting['background-type'];

				if ( 'color' === $background_type ) {
					if ( isset( $setting['background-color'] ) && ! empty( $setting['background-color'] ) ) {
						$css_buffer .= 'background: ' . $setting['background-color'] . ';';
					}
				} elseif ( 'gradient' === $background_type ) {

					$css_buffer .= 'background: ' . $setting['gradient-color-1'] . ';';

					if ( 'linear' === $setting['gradient-type'] ) {
						$css_buffer .= '
							background: -webkit-linear-gradient(' . $setting['gradient-linear-angle'] . 'deg, ' . $setting['gradient-color-1'] . ' ' . $setting['gradient-color-1-location'] . '%, ' . $setting['gradient-color-2'] . ' ' . $setting['gradient-color-2-location'] . '%);
							background: -o-linear-gradient(' . $setting['gradient-linear-angle'] . 'deg, ' . $setting['gradient-color-1'] . ' ' . $setting['gradient-color-1-location'] . '%, ' . $setting['gradient-color-2'] . ' ' . $setting['gradient-color-2-location'] . '%);
							background: linear-gradient(' . $setting['gradient-linear-angle'] . 'deg, ' . $setting['gradient-color-1'] . ' ' . $setting['gradient-color-1-location'] . '%, ' . $setting['gradient-color-2'] . ' ' . $setting['gradient-color-2-location'] . '%);

						';
					} elseif ( 'radial' === $setting['gradient-type'] ) {
						$css_buffer .= '
							background: -webkit-radial-gradient(' . $setting['gradient-position'] . ', circle, ' . $setting['gradient-color-1'] . ' ' . $setting['gradient-color-1-location'] . '%, ' . $setting['gradient-color-2'] . ' ' . $setting['gradient-color-2-location'] . '%);
							background: -o-radial-gradient(' . $setting['gradient-position'] . ', circle, ' . $setting['gradient-color-1'] . ' ' . $setting['gradient-color-1-location'] . '%, ' . $setting['gradient-color-2'] . ' ' . $setting['gradient-color-2-location'] . '%);
							background: radial-gradient(circle at ' . $setting['gradient-position'] . ', ' . $setting['gradient-color-1'] . ' ' . $setting['gradient-color-1-location'] . '%, ' . $setting['gradient-color-2'] . ' ' . $setting['gradient-color-2-location'] . '%);
						';
					}
				} elseif ( 'image' === $background_type ) {
					$css_buffer .= '
						background-image: url(' . $setting['background-image'] . ');
						background-size: ' . $setting['background-size'] . ';
						background-attachment: ' . $setting['background-attachment'] . ';
						background-position: ' . $setting['background-position-x'] . '% ' . $setting['background-position-y'] . '%;
						background-repeat: ' . $setting['background-repeat'] . ';
					';
				}

				$css_buffer = ! empty( $css_buffer ) ? $css_selector . '{' . $css_buffer . '}' : '';

				if ( 'image' === $background_type && isset( $setting['background-color-overlay'] ) && $setting['background-color-overlay'] && isset( $setting['background-image'] ) && $setting['background-image'] ) {
					$css_buffer .= $css_selector . '::after { background-color: ' . $setting['background-color-overlay'] . '; }';
				}
			} elseif ( 'color' === $type ) {

				// Text color.
				if ( isset( $setting['text-color'] ) && ! empty( $setting['text-color'] ) ) {
					$css_buffer .= $css_selector . ' { color: ' . $setting['text-color'] . '; }';
				}

				// Link Color.
				if ( isset( $setting['link-color'] ) && ! empty( $setting['link-color'] ) ) {
					$css_buffer .= $css_selector . ' a { color: ' . $setting['link-color'] . '; }';
				}

				// Link Hover Color.
				if ( isset( $setting['link-hover-color'] ) && ! empty( $setting['link-hover-color'] ) ) {
					$css_buffer .= $css_selector . ' a:hover { color: ' . $setting['link-hover-color'] . ' !important; }';
				}
			} elseif ( 'border' === $type ) {

				// Color.
				if ( isset( $setting['border-color'] ) && ! empty( $setting['border-color'] ) ) {
					$css_buffer .= 'border-color:' . $setting['border-color'] . ';';
				}

				// Style.
				if ( isset( $setting['border-style'] ) && ! empty( $setting['border-style'] ) ) {
					$css_buffer .= 'border-style: ' . $setting['border-style'] . ';';
				}

				// Width.
				$positions = array( 'top', 'right', 'bottom', 'left' );

				foreach ( $positions as $position ) {
					if ( isset( $setting[ 'border-' . $position . '-width' ] ) && ! empty( $setting[ 'border-' . $position . '-width' ] ) ) {
						$css_buffer .= 'border-' . $position . '-width: ' . $setting[ 'border-' . $position . '-width' ] . 'px;';
					}
				}

				$css_buffer = ! empty( $css_buffer ) ? $css_selector . '{' . $css_buffer . '}' : '';
			} elseif ( 'separator_color' === $type && isset( $setting['separator-color'] ) && ! empty( $setting['separator-color'] ) ) {

				// Separator Color.
				$css_buffer .= $css_selector . '::after { background-color:' . $setting['separator-color'] . '; }';
			}

			// Finally, return the generated CSS code.
			return $css_buffer;
		}

		/**
		 * Prints typography field CSS based on passed params.
		 *
		 * @since  1.0.0
		 * @param  string       $css_selector CSS selector.
		 * @param  string|mixed $setting The ID of the customizer setting containing all information about the setting.
		 * @return string       Generated CSS.
		 */
		public function get_typography_field_css( $css_selector, $setting ) {

			if ( is_string( $setting ) ) {
				// Get the saved setting.
				$setting = hester_option( $setting );
			}

			// Setting has to be array.
			if ( ! is_array( $setting ) || empty( $setting ) ) {
				return;
			}

			// CSS buffer.
			$css_buffer = '';

			// Properties.
			$properties = array(
				'font-weight',
				'font-style',
				'text-transform',
				'text-decoration',
			);

			foreach ( $properties as $property ) {

				if ( 'inherit' !== $setting[ $property ] ) {
					$css_buffer .= $property . ':' . $setting[ $property ] . ';';
				}
			}

			// Font family.
			if ( 'inherit' !== $setting['font-family'] ) {
				$font_family = hester()->fonts->get_font_family( $setting['font-family'] );

				$css_buffer .= 'font-family: ' . $font_family . ';';
			}

			// Letter spacing.
			if ( ! empty( $setting['letter-spacing'] ) ) {
				$css_buffer .= 'letter-spacing:' . $setting['letter-spacing'] . $setting['letter-spacing-unit'] . ';';
			}

			// Font size.
			if ( ! empty( $setting['font-size-desktop'] ) ) {
				$css_buffer .= 'font-size:' . $setting['font-size-desktop'] . $setting['font-size-unit'] . ';';
			}

			// Line Height.
			if ( ! empty( $setting['line-height-desktop'] ) ) {
				$css_buffer .= 'line-height:' . $setting['line-height-desktop'] . ';';
			}

			$css_buffer = $css_buffer ? $css_selector . '{' . $css_buffer . '}' : '';

			// Responsive options - tablet.
			$tablet = '';

			if ( ! empty( $setting['font-size-tablet'] ) ) {
				$tablet .= 'font-size:' . $setting['font-size-tablet'] . $setting['font-size-unit'] . ';';
			}

			if ( ! empty( $setting['line-height-tablet'] ) ) {
				$tablet .= 'line-height:' . $setting['line-height-tablet'] . ';';
			}

			$tablet = ! empty( $tablet ) ? '@media only screen and (max-width: 768px) {' . $css_selector . '{' . $tablet . '} }' : '';

			$css_buffer .= $tablet;

			// Responsive options - mobile.
			$mobile = '';

			if ( ! empty( $setting['font-size-mobile'] ) ) {
				$mobile .= 'font-size:' . $setting['font-size-mobile'] . $setting['font-size-unit'] . ';';
			}

			if ( ! empty( $setting['line-height-mobile'] ) ) {
				$mobile .= 'line-height:' . $setting['line-height-mobile'] . ';';
			}

			$mobile = ! empty( $mobile ) ? '@media only screen and (max-width: 480px) {' . $css_selector . '{' . $mobile . '} }' : '';

			$css_buffer .= $mobile;

			// Equeue google fonts.
			if ( hester()->fonts->is_google_font( $setting['font-family'] ) ) {

				$params = array();

				if ( 'inherit' !== $setting['font-weight'] ) {
					$params['weight'] = $setting['font-weight'];
				}

				if ( 'inherit' !== $setting['font-style'] ) {
					$params['style'] = $setting['font-style'];
				}

				if ( $setting['font-subsets'] && ! empty( $setting['font-subsets'] ) ) {
					$params['subsets'] = $setting['font-subsets'];
				}

				hester()->fonts->enqueue_google_font(
					$setting['font-family'],
					$params
				);
			}

			// Finally, return the generated CSS code.
			return $css_buffer;
		}

		/**
		 * Filters the dynamic styles to include button styles and makes sure it has the highest priority.
		 *
		 * @since  1.0.0
		 * @param  string $css The dynamic CSS.
		 * @return string Filtered dynamic CSS.
		 */
		public function get_button_styles( $css ) {

			/**
			 * Primary Button.
			 */

			$primary_button_selector = '
				.hester-btn, 
				body:not(.wp-customizer) input[type=submit], 
				.site-main .woocommerce #respond input#submit, 
				.site-main .woocommerce a.button, 
				.site-main .woocommerce button.button, 
				.site-main .woocommerce input.button, 
				.woocommerce ul.products li.product .added_to_cart, 
				.woocommerce ul.products li.product .button, 
				.woocommerce div.product form.cart .button, 
				.woocommerce #review_form #respond .form-submit input, 
				#infinite-handle span';

			$primary_button_bg_color      = hester_option( 'primary_button_bg_color' );
			$primary_button_border_radius = hester_option( 'primary_button_border_radius' );

			if ( '' !== $primary_button_bg_color ) {
				$css .= $primary_button_selector . ' {
					background-color: ' . $primary_button_bg_color . ';
				}';
			}

			// Primary button text color, border color & border width.
			$css .= $primary_button_selector . ' {
				color: ' . hester_option( 'primary_button_text_color' ) . ';
				border-color: ' . hester_option( 'primary_button_border_color' ) . ';
				border-width: ' . hester_option( 'primary_button_border_width' ) . 'rem;
				border-top-left-radius: ' . $primary_button_border_radius['top-left'] . 'rem;
				border-top-right-radius: ' . $primary_button_border_radius['top-right'] . 'rem;
				border-bottom-right-radius: ' . $primary_button_border_radius['bottom-right'] . 'rem;
				border-bottom-left-radius: ' . $primary_button_border_radius['bottom-left'] . 'rem;
			}';

			// Primary button hover.
			$primary_button_hover_selector = '
				.hester-btn:hover, 
				.hester-btn:focus, 
				body:not(.wp-customizer) input[type=submit]:hover,
				body:not(.wp-customizer) input[type=submit]:focus, 
				.site-main .woocommerce #respond input#submit:hover,
				.site-main .woocommerce #respond input#submit:focus, 
				.site-main .woocommerce a.button:hover,
				.site-main .woocommerce a.button:focus, 
				.site-main .woocommerce button.button:hover,
				.site-main .woocommerce button.button:focus, 
				.site-main .woocommerce input.button:hover, 
				.site-main .woocommerce input.button:focus, 
				.woocommerce ul.products li.product .added_to_cart:hover,
				.woocommerce ul.products li.product .added_to_cart:focus, 
				.woocommerce ul.products li.product .button:hover,
				.woocommerce ul.products li.product .button:focus, 
				.woocommerce div.product form.cart .button:hover,
				.woocommerce div.product form.cart .button:focus, 
				.woocommerce #review_form #respond .form-submit input:hover,
				.woocommerce #review_form #respond .form-submit input:focus, 
				#infinite-handle span:hover';

			$primary_button_hover_bg_color = hester_option( 'primary_button_hover_bg_color' );

			// Primary button hover bg color.
			if ( '' !== $primary_button_hover_bg_color ) {
				$css .= $primary_button_hover_selector . ' {
					background-color: ' . $primary_button_hover_bg_color . ';
				}';
			}

			// Primary button hover color & border.
			$css .= $primary_button_hover_selector . '{
				color: ' . hester_option( 'primary_button_hover_text_color' ) . ';
				border-color: ' . hester_option( 'primary_button_hover_border_color' ) . ';
			}';

			// Primary button typography.
			$css .= $this->get_typography_field_css( $primary_button_selector, 'primary_button_typography' );

			/**
			 * Secondary Button.
			 */

			$secondary_button_selector = '
				.btn-secondary,
				.hester-btn.btn-secondary';

			$secondary_button_bg_color      = hester_option( 'secondary_button_bg_color' );
			$secondary_button_border_radius = hester_option( 'secondary_button_border_radius' );

			// Secondary button text color, border color & border width.
			$css .= $secondary_button_selector . ' {
				color: ' . hester_option( 'secondary_button_text_color' ) . ';
				border-color: ' . hester_option( 'secondary_button_border_color' ) . ';
				border-width: ' . hester_option( 'secondary_button_border_width' ) . 'rem;
				background-color: ' . $secondary_button_bg_color . ';
				border-top-left-radius: ' . $secondary_button_border_radius['top-left'] . 'rem;
				border-top-right-radius: ' . $secondary_button_border_radius['top-right'] . 'rem;
				border-bottom-right-radius: ' . $secondary_button_border_radius['bottom-right'] . 'rem;
				border-bottom-left-radius: ' . $secondary_button_border_radius['bottom-left'] . 'rem;
			}';

			// Secondary button hover.
			$secondary_button_hover_selector = '
				.btn-secondary:hover, 
				.btn-secondary:focus, 
				.hester-btn.btn-secondary:hover, 
				.hester-btn.btn-secondary:focus';

			$secondary_button_hover_bg_color = hester_option( 'secondary_button_hover_bg_color' );

			// Secondary button hover color & border.
			$css .= $secondary_button_hover_selector . '{
				color: ' . hester_option( 'secondary_button_hover_text_color' ) . ';
				border-color: ' . hester_option( 'secondary_button_hover_border_color' ) . ';
				background-color: ' . $secondary_button_hover_bg_color . ';
			}';

			// Secondary button typography.
			$css .= $this->get_typography_field_css( $secondary_button_selector, 'secondary_button_typography' );

			// Text Button.
			$css .= '
				.hester-btn.btn-text-1, .btn-text-1 {
					color: ' . hester_option( 'text_button_text_color' ) . ';
				}
			';

			$css .= '
				.hester-btn.btn-text-1:hover, .hester-btn.btn-text-1:focus, .btn-text-1:hover, .btn-text-1:focus {
					color: ' . hester_option( 'accent_color' ) . ';
				}
			';

			$css .= '
				.hester-btn.btn-text-1 > span::before {
					background-color: ' . hester_option( 'accent_color' ) . ';
				}
			';

			if ( hester_option( 'text_button_hover_text_color' ) ) {
				$css .= '
					.hester-btn.btn-text-1:hover, .hester-btn.btn-text-1:focus, .btn-text-1:hover, .btn-text-1:focus {
						color: ' . hester_option( 'text_button_hover_text_color' ) . ';
					}

					.hester-btn.btn-text-1 > span::before {
						background-color: ' . hester_option( 'text_button_hover_text_color' ) . ';
					}
				';
			}

			// Secondary button typography.
			$css .= $this->get_typography_field_css( '.hester-btn.btn-text-1, .btn-text-1', 'text_button_typography' );

			// Return the filtered CSS.
			return $css;
		}

		/**
		 * Generate dynamic Block Editor styles.
		 *
		 * @since  1.0.9
		 * @return string
		 */
		public function get_block_editor_css() {

			// Current post.
			$post_id   = get_the_ID();
			$post_type = get_post_type( $post_id );

			// Layout.
			$site_layout          = hester_get_site_layout( $post_id );
			$sidebar_position     = hester_get_sidebar_position( $post_id );
			$container_width      = hester_option( 'container_width' );
			$single_content_width = hester_option( 'single_content_width' );

			$container_width = $container_width - 100;

			if ( hester_is_sidebar_displayed( $post_id ) ) {

				$sidebar_width   = hester_option( 'sidebar_width' );
				$container_width = $container_width * ( 100 - intval( $sidebar_width ) ) / 100;
				$container_width = $container_width - 50;

				if ( 'boxed-separated' === $site_layout ) {
					if ( 3 === intval( hester_option( 'sidebar_style' ) ) ) {
						$container_width += 15;
					}
				}
			}

			if ( 'boxed-separated' === $site_layout ) {
				$container_width += 16;
			}

			if ( 'boxed' === $site_layout ) {
				$container_width = $container_width + 200;
			}

			$background_color = get_background_color();
			$accent_color     = hester_option( 'accent_color' );
			$content_color    = hester_option( 'boxed_content_background_color' );
			$text_color       = hester_option( 'content_text_color' );
			$link_hover_color = hester_option( 'content_link_hover_color' );
			$headings_color   = hester_option( 'headings_color' );
			$font_smoothing   = hester_option( 'font_smoothing' );

			$css = '';

			// Base HTML font size.
			$css .= $this->get_range_field_css( 'html', 'font-size', 'html_base_font_size', true, '%' );

			// Accent color.
			$css .= '
				.editor-styles-wrapper .block-editor-rich-text__editable mark,
				.editor-styles-wrapper .block-editor-rich-text__editable span.highlight,
				.editor-styles-wrapper .block-editor-rich-text__editable code,
				.editor-styles-wrapper .block-editor-rich-text__editable kbd,
				.editor-styles-wrapper .block-editor-rich-text__editable var,
				.editor-styles-wrapper .block-editor-rich-text__editable samp,
				.editor-styles-wrapper .block-editor-rich-text__editable tt {
					background-color: ' . hester_hex2rgba( $accent_color, .09 ) . ';
				}

				.editor-styles-wrapper .wp-block code.block,
				.editor-styles-wrapper .block code {
					background-color: ' . hester_hex2rgba( $accent_color, .075 ) . ';
				}

				.editor-styles-wrapper .wp-block .block-editor-rich-text__editable a,
				.editor-styles-wrapper .block-editor-rich-text__editable code,
				.editor-styles-wrapper .block-editor-rich-text__editable kbd,
				.editor-styles-wrapper .block-editor-rich-text__editable var,
				.editor-styles-wrapper .block-editor-rich-text__editable samp,
				.editor-styles-wrapper .block-editor-rich-text__editable tt {
					color: ' . $accent_color . ';
				}

				#editor .editor-styles-wrapper ::-moz-selection { background-color: ' . $accent_color . '; color: #FFF; }
				#editor .editor-styles-wrapper ::selection { background-color: ' . $accent_color . '; color: #FFF; }

				
				.editor-styles-wrapper blockquote,
				.editor-styles-wrapper .wp-block-quote {
					border-color: ' . $accent_color . ';
				}
			';

			// Container width.
			if ( 'fw-stretched' === $site_layout ) {
				$css .= '
					.editor-styles-wrapper .wp-block {
						max-width: none;
					}
				';
			} elseif ( 'boxed-separated' === $site_layout || 'boxed' === $site_layout ) {

				$css .= '
					.editor-styles-wrapper {
						max-width: ' . $container_width . 'px;
						margin: 0 auto;
					}

					.editor-styles-wrapper .wp-block {
						max-width: none;
					}
				';

				if ( 'boxed' === $site_layout ) {
					$css .= '
						.editor-styles-wrapper {
							-webkit-box-shadow: 0 0 30px rgba(50, 52, 54, 0.06);
							box-shadow: 0 0 30px rgba(50, 52, 54, 0.06);
							padding-left: 42px;
							padding-right: 42px;
						}
					';
				} else {
					$css .= '
						.editor-styles-wrapper {
							border-radius: 3px;
							border: 1px solid rgba(190, 190, 190, 0.30);
						}
					';
				}
			} else {
				$css .= '
					.editor-styles-wrapper .wp-block {
						max-width: ' . $container_width . 'px;
					}
				';
			}

			if ( 'post' === $post_type && 'narrow' === $single_content_width ) {

				$narrow_container_width = intval( hester_option( 'single_narrow_container_width' ) );

				$css .= '
					.editor-styles-wrapper .wp-block {
						max-width: ' . $narrow_container_width . 'px;
					}
				';
			}

			// Background color.
			if ( 'boxed-separated' === $site_layout || 'boxed' === $site_layout ) {
				$css .= '
					:root .edit-post-layout .interface-interface-skeleton__content {
						background-color: #' . trim( $background_color, '#' ) . ';
					}

					:root .editor-styles-wrapper {
						background-color: ' . $content_color . ';
					}
				';
			} else {
				$css .= '
					:root .editor-styles-wrapper {
						background-color: #' . trim( $background_color, '#' ) . ';
					}
				';
			}

			// Body.
			$css .= $this->get_typography_field_css( ':root .editor-styles-wrapper, .editor-styles-wrapper .wp-block, .block-editor-default-block-appender textarea.block-editor-default-block-appender__content', 'body_font' );
			$css .= '
				:root .editor-styles-wrapper {
					color: ' . $text_color . ';
				}
			';

			// If single post, use single post font size settings.
			if ( 'post' === $post_type ) {
				$css .= $this->get_range_field_css( ':root .editor-styles-wrapper .wp-block', 'font-size', 'single_content_font_size', true );
			}

			// Headings typography.
			$css .= $this->get_typography_field_css( ':root .editor-styles-wrapper h1.wp-block, :root .editor-styles-wrapper h2.wp-block, :root .editor-styles-wrapper h3.wp-block, :root .editor-styles-wrapper h4.wp-block, :root .editor-styles-wrapper h5.wp-block, :root .editor-styles-wrapper h6.wp-block, :root .editor-styles-wrapper .editor-post-title__block .editor-post-title__input', 'headings_font' );

			// Heading em.
			$css .= $this->get_typography_field_css( '.editor-styles-wrapper h1.wp-block em, .editor-styles-wrapper h2.wp-block em, .editor-styles-wrapper h3.wp-block em, .editor-styles-wrapper h4.wp-block em, .editor-styles-wrapper h5.wp-block em, .editor-styles-wrapper h6.wp-block em', 'heading_em_font' );

			// Headings (H1-H6).
			$css .= $this->get_typography_field_css( ':root .editor-styles-wrapper h1.wp-block, :root .editor-styles-wrapper .h1, :root .editor-styles-wrapper .editor-post-title__block .editor-post-title__input', 'h1_font' );
			$css .= $this->get_typography_field_css( ':root .editor-styles-wrapper h2.wp-block, :root .editor-styles-wrapper .h2', 'h2_font' );
			$css .= $this->get_typography_field_css( ':root .editor-styles-wrapper h3.wp-block, :root .editor-styles-wrapper .h3', 'h3_font' );
			$css .= $this->get_typography_field_css( ':root .editor-styles-wrapper h4.wp-block', 'h4_font' );
			$css .= $this->get_typography_field_css( ':root .editor-styles-wrapper h5.wp-block', 'h5_font' );
			$css .= $this->get_typography_field_css( ':root .editor-styles-wrapper h6.wp-block', 'h6_font' );

			$css .= '
				:root .editor-styles-wrapper h1,
				:root .editor-styles-wrapper h2,
				:root .editor-styles-wrapper h3,
				:root .editor-styles-wrapper h4,
				:root .editor-styles-wrapper .h4,
				:root .editor-styles-wrapper h5,
				:root .editor-styles-wrapper h6,
				:root .editor-post-title__block .editor-post-title__input {
					color: ' . $headings_color . ';
				}
			';

			// Page header font size.
			$css .= $this->get_range_field_css( ':root .editor-styles-wrapper .editor-post-title__block .editor-post-title__input', 'font-size', 'page_header_font_size', true );

			// Link hover color.
			$css .= '
				.editor-styles-wrapper .wp-block .block-editor-rich-text__editable a:hover { 
					color: ' . $link_hover_color . '; 
				}
			';

			// Font smoothing.
			if ( $font_smoothing ) {
				$css .= '
					.editor-styles-wrapper {
						-moz-osx-font-smoothing: grayscale;
						-webkit-font-smoothing: antialiased;
					}
				';
			}

			return $css;
		}
	}
endif;

/**
 * The function which returns the one Hester_Dynamic_Styles instance.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $dynamic_styles = hester_dynamic_styles(); ?>
 *
 * @since 1.0.0
 * @return object
 */
function hester_dynamic_styles() {
	return Hester_Dynamic_Styles::instance();
}

hester_dynamic_styles();
