<?php
/**
 * Hester Customizer sections and panels.
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

if ( ! class_exists( 'Hester_Customizer_Sections' ) ) :
	/**
	 * Hester Customizer sections and panels.
	 */
	class Hester_Customizer_Sections {

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			/**
			 * Registers our custom panels in Customizer.
			 */
			add_filter( 'hester_customizer_options', array( $this, 'register_panel' ) );
		}

		/**
		 * Registers our custom options in Customizer.
		 *
		 * @since 1.0.0
		 * @param array $options Array of customizer options.
		 */
		public function register_panel( $options ) {

			// General panel.
			$options['panel']['hester_panel_general'] = array(
				'title'    => esc_html__( 'General Settings', 'hester' ),
				'priority' => 1,
			);

			// Homapepage panel.
			$options['panel']['hester_panel_homepage'] = array(
				'title'    => esc_html__( 'Homapage sections', 'hester' ),
				'priority' => 3,
			);

			// Header panel.
			$options['panel']['hester_panel_header'] = array(
				'title'    => esc_html__( 'Header', 'hester' ),
				'priority' => 3,
			);

			// Footer panel.
			$options['panel']['hester_panel_footer'] = array(
				'title'    => esc_html__( 'Footer', 'hester' ),
				'priority' => 5,
			);

			// Blog settings.
			$options['panel']['hester_panel_blog'] = array(
				'title'    => esc_html__( 'Blog', 'hester' ),
				'priority' => 6,
			);

			return $options;
		}
	}
endif;
new Hester_Customizer_Sections();
