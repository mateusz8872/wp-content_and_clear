<?php
/**
 * Hester Blog » Blog Page / Archive section in Customizer.
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

if ( ! class_exists( 'Hester_Customizer_Home_sections' ) ) :
	/**
	 * Hester Blog » Blog Page / Archive section in Customizer.
	 */
	class Hester_Customizer_Home_sections {

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			/**
			 * Registers our custom options in Customizer.
			 */
			add_filter( 'hester_customizer_options', array( $this, 'register_options' ) );
		}

		/**
		 * Registers our custom options in Customizer.
		 *
		 * @since 1.0.0
		 * @param array $options Array of customizer options.
		 */
		public function register_options( $options ) {

			include HESTER_THEME_PATH . '/inc/customizer/settings/front-page/index.php';

			// echo '<pre>';print_r($options);echo '</pre>';die;

			return $options;
		}
	}
endif;

new Hester_Customizer_Home_sections();
