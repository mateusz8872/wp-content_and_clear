<?php
/**
 * Hester compatibility class for Elementor.
 *
 * @package Hester
 * @author  Peregrine Themes
 * @since   1.0.0
 */

namespace Elementor; // phpcs:ignore

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Return if Elementor not active.
if ( ! class_exists( '\Elementor\Plugin' ) ) {
	return;
}

if ( ! class_exists( 'Hester_Elementor' ) ) :

	/**
	 * Elementor compatibility.
	 */
	class Hester_Elementor {

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
		 * @return Hester_Elementor
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Hester_Elementor ) ) {
				self::$instance = new Hester_Elementor();
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

			// Enqueue Elementor editor styles.
			add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue_editor_style' ) );

			// Setup postdata for Elementor pages.
			add_action( 'wp', array( $this, 'setup_postdata' ), 1 );
			add_action( 'elementor/preview/init', array( $this, 'setup_postdata' ), 5 );

			// Enqueue additional Elementor styles.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_additional' ) );
		}

		/**
		 * Editor stylesheet.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_editor_style() {

			// Script debug.
			$hester_dir    = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'dev/' : '';
			$hester_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_style(
				'hester-elementor-editor',
				HESTER_THEME_URI . '/assets/css/compatibility/elementor-editor-style' . $hester_suffix . '.css',
				false,
				HESTER_THEME_VERSION,
				'all'
			);
		}

		/**
		 * Setup default postdata for Elementor pages.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function setup_postdata() {

			// Page builder compatibility disabled.
			if ( ! hester_enable_page_builder_compatibility() ) {
				return;
			}

			// Skip posts.
			if ( 'post' === get_post_type() ) {
				return;
			}

			// Don't modify postdata if we are not on Elementor's edit page.
			if ( ! $this->is_elementor_editor() ) {
				return;
			}

			global $post;

			$id = hester_get_the_id();

			$setup = get_post_meta( $id, '_hester_page_builder_setup', true );

			if ( isset( $post ) && empty( $setup ) && ( is_admin() || is_singular() ) && empty( $post->post_content ) && $this->is_built_with_elementor( $id ) ) {

				update_post_meta( $id, '_hester_page_builder_setup', true );
				update_post_meta( $id, 'hester_disable_page_title', true );
				update_post_meta( $id, 'hester_disable_breadcrumbs', true );
				update_post_meta( $id, 'hester_disable_thumbnail', true );
				update_post_meta( $id, 'hester_sidebar_position', 'no-sidebar' );

				update_post_meta( $id, '_wp_page_template', 'page-templates/template-hester-fullwidth.php' );
			}

		}

		/**
		 * Additional Elementor styles.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_additional() {

			// Script debug.
			$hester_dir    = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'dev/' : '';
			$hester_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			// Enqueue theme stylesheet.
			wp_enqueue_style(
				'hester-elementor',
				HESTER_THEME_URI . '/assets/css/compatibility/elementor' . $hester_suffix . '.css',
				false,
				HESTER_THEME_VERSION,
				'all'
			);
		}

		/**
		 * Check if page is built with elementor.
		 *
		 * @param int $id Post/Page Id.
		 * @since 1.0.0
		 *
		 * @return boolean
		 */
		public function is_built_with_elementor( $id ) {
			if ( version_compare( ELEMENTOR_VERSION, '1.5.0', '<' ) ) {
				return ( 'builder' === Plugin::$instance->db->get_edit_mode( $id ) );
			} else {
				return Plugin::$instance->db->is_built_with_elementor( $id );
			}
		}

		/**
		 * Check if Elementor Editor is loaded.
		 *
		 * @since 1.0.0
		 *
		 * @return boolean Elementor editor is loaded.
		 */
		private function is_elementor_editor() {
			return ( isset( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] ) || isset( $_REQUEST['elementor-preview'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
	}

endif;

/**
 * Returns the one Hester_Elementor instance.
 */
function hester_elementor() {
	return Hester_Elementor::instance();
}

hester_elementor();
