<?php
/**
 * Hester compatibility class for Beaver Themer.
 *
 * @package Hester
 * @author  Peregrine Themes
 * @since   1.0.0
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Return if Beaver Themer not active.
if ( ! class_exists( 'FLThemeBuilderLoader' ) || ! class_exists( 'FLThemeBuilderLayoutData' ) ) {
	return;
}

// PHP 5.3+ is required.
if ( ! version_compare( PHP_VERSION, '5.3', '>=' ) ) {
	return;
}

if ( ! class_exists( 'Hester_Beaver_Themer' ) ) :

	/**
	 * Beaver Themer compatibility.
	 */
	class Hester_Beaver_Themer {

		/**
		 * Singleton instance of the class.
		 *
		 * @var object
		 */
		private static $instance;

		/**
		 * Instance.
		 *
		 * @since 1.0.0
		 * @return Hester_Beaver_Themer
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Hester_Beaver_Themer ) ) {
				self::$instance = new Hester_Beaver_Themer();
			}
			return self::$instance;
		}

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __construct() {
			add_action( 'after_setup_theme', array( $this, 'add_theme_support' ) );
			add_action( 'wp', array( $this, 'header_footer_render' ) );
			add_action( 'wp', array( $this, 'page_header_render' ) );
			add_filter( 'fl_theme_builder_part_hooks', array( $this, 'register_part_hooks' ) );
		}

		/**
		 * Add theme support
		 *
		 * @since 1.0.0
		 */
		public function add_theme_support() {
			add_theme_support( 'fl-theme-builder-headers' );
			add_theme_support( 'fl-theme-builder-footers' );
			add_theme_support( 'fl-theme-builder-parts' );
		}

		/**
		 * Update header/footer with Beaver template
		 *
		 * @since 1.0.0
		 */
		public function header_footer_render() {

			// Get the header ID.
			$header_ids = FLThemeBuilderLayoutData::get_current_page_header_ids();

			// If we have a header, remove the theme header and hook in Theme Builder's.
			if ( ! empty( $header_ids ) ) {

				// Remove Top Bar.
				remove_action( 'hester_header', 'hester_topbar_output', 10 );

				// Remove Main Header.
				remove_action( 'hester_header', 'hester_header_output', 20 );

				// Replacement header.
				add_action( 'hester_header', 'FLThemeBuilderLayoutRenderer::render_header' );
			}

			// Get the footer ID.
			$footer_ids = FLThemeBuilderLayoutData::get_current_page_footer_ids();

			// If we have a footer, remove the theme footer and hook in Theme Builder's.
			if ( ! empty( $footer_ids ) ) {

				// Remove Main Footer.
				remove_action( 'hester_footer', 'hester_footer_output', 20 );

				// Remove Copyright Bar.
				remove_action( 'hester_footer', 'hester_copyright_bar_output', 30 );

				// Replacement footer.
				add_action( 'hester_footer', 'FLThemeBuilderLayoutRenderer::render_footer' );
			}
		}

		/**
		 * Remove page header if using Beaver Themer.
		 *
		 * @since 1.0.0
		 */
		public function page_header_render() {

			// Get the page ID.
			$page_ids = FLThemeBuilderLayoutData::get_current_page_content_ids();

			// If we have a content layout, remove the theme page header.
			if ( ! empty( $page_ids ) ) {
				remove_action( 'hester_page_header', 'hester_page_header_template' );
			}
		}

		/**
		 * Register hooks
		 *
		 * @since 1.0.0
		 */
		public function register_part_hooks() {
			return array(
				array(
					'label' => 'Header',
					'hooks' => array(
						'hester_before_masthead' => esc_html__( 'Before Header', 'hester' ),
						'hester_after_masthead'  => esc_html__( 'After Header', 'hester' ),
					),
				),
				array(
					'label' => 'Main',
					'hooks' => array(
						'hester_before_main' => esc_html__( 'Before Main', 'hester' ),
						'hester_after_main'  => esc_html__( 'After Main', 'hester' ),
					),
				),
				array(
					'label' => 'Content',
					'hooks' => array(
						'hester_before_page_content' => esc_html__( 'Before Content', 'hester' ),
						'hester_after_page_content'  => esc_html__( 'After Content', 'hester' ),
					),
				),
				array(
					'label' => 'Footer',
					'hooks' => array(
						'hester_before_colophon' => esc_html__( 'Before Footer', 'hester' ),
						'hester_after_colophon'  => esc_html__( 'After Footer', 'hester' ),
					),
				),
				array(
					'label' => 'Sidebar',
					'hooks' => array(
						'hester_before_sidebar' => esc_html__( 'Before Sidebar', 'hester' ),
						'hester_after_sidebar'  => esc_html__( 'After Sidebar', 'hester' ),
					),
				),
				array(
					'label' => 'Singular',
					'hooks' => array(
						'hester_before_singular'       => __( 'Before Singular', 'hester' ),
						'hester_after_singular'        => __( 'After Singular', 'hester' ),
						'hester_before_comments'       => __( 'Before Comments', 'hester' ),
						'hester_after_comments'        => __( 'After Comments', 'hester' ),
						'hester_before_single_content' => __( 'Before Single Content', 'hester' ),
						'hester_after_single_content'  => __( 'After Single Content', 'hester' ),
					),
				),
			);
		}

	}

endif;

/**
 * Returns the one Hester_Beaver_Themer instance.
 */
function hester_beaver_themer() {
	return Hester_Beaver_Themer::instance();
}

hester_beaver_themer();
