<?php //phpcs:ignore
/**
 * Theme functions and definitions.
 *
 * @package Hester
 * @author  Peregrine Themes
 * @since   1.0.0
 */

/**
 * Main Hester class.
 *
 * @since 1.0.0
 */
final class Hester {

	/**
	 * Singleton instance of the class.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	private static $instance;
	/**
	 * Theme version.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $version = '1.0.8';
	/**
	 * Main Hester Instance.
	 *
	 * Insures that only one instance of Hester exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 1.0.0
	 * @return Hester
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Hester ) ) {
			self::$instance = new Hester();
			self::$instance->constants();
			self::$instance->includes();
			self::$instance->objects();
			// Hook now that all of the Hester stuff is loaded.
			do_action( 'hester_loaded' );
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
	}
	/**
	 * Setup constants.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function constants() {
		if ( ! defined( 'HESTER_THEME_VERSION' ) ) {
			define( 'HESTER_THEME_VERSION', $this->version );
		}
		if ( ! defined( 'HESTER_THEME_URI' ) ) {
			define( 'HESTER_THEME_URI', get_parent_theme_file_uri() );
		}
		if ( ! defined( 'HESTER_THEME_PATH' ) ) {
			define( 'HESTER_THEME_PATH', get_parent_theme_file_path() );
		}
	}
	/**
	 * Include files.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function includes() {
		require_once HESTER_THEME_PATH . '/inc/common.php';
		require_once HESTER_THEME_PATH . '/inc/helpers.php';
		require_once HESTER_THEME_PATH . '/inc/widgets.php';
		require_once HESTER_THEME_PATH . '/inc/template-tags.php';
		require_once HESTER_THEME_PATH . '/inc/template-parts.php';
		require_once HESTER_THEME_PATH . '/inc/icon-functions.php';
		require_once HESTER_THEME_PATH . '/inc/breadcrumbs.php';
		require_once HESTER_THEME_PATH . '/inc/class-hester-dynamic-styles.php';
		// Core.
		require_once HESTER_THEME_PATH . '/inc/core/class-hester-options.php';
		require_once HESTER_THEME_PATH . '/inc/core/class-hester-enqueue-scripts.php';
		require_once HESTER_THEME_PATH . '/inc/core/class-hester-fonts.php';
		require_once HESTER_THEME_PATH . '/inc/core/class-hester-theme-setup.php';
		// Compatibility.
		require_once HESTER_THEME_PATH . '/inc/compatibility/woocommerce/class-hester-woocommerce.php';
		require_once HESTER_THEME_PATH . '/inc/compatibility/socialsnap/class-hester-socialsnap.php';
		require_once HESTER_THEME_PATH . '/inc/compatibility/class-hester-wpforms.php';
		require_once HESTER_THEME_PATH . '/inc/compatibility/class-hester-jetpack.php';
		require_once HESTER_THEME_PATH . '/inc/compatibility/class-hester-endurance.php';
		require_once HESTER_THEME_PATH . '/inc/compatibility/class-hester-beaver-themer.php';
		require_once HESTER_THEME_PATH . '/inc/compatibility/class-hester-elementor.php';
		require_once HESTER_THEME_PATH . '/inc/compatibility/class-hester-elementor-pro.php';
		require_once HESTER_THEME_PATH . '/inc/compatibility/class-hester-hfe.php';

		if ( is_admin() ) {
			require_once HESTER_THEME_PATH . '/inc/utilities/class-hester-plugin-utilities.php';
			require_once HESTER_THEME_PATH . '/inc/admin/class-hester-admin.php';

		}
		new Hester_Enqueue_Scripts();
		// Customizer.
		require_once HESTER_THEME_PATH . '/inc/customizer/class-hester-customizer.php';
		require_once HESTER_THEME_PATH . '/inc/customizer/class-hester-section-ordering.php';
	}
	/**
	 * Setup objects to be used throughout the theme.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function objects() {

		hester()->options    = new Hester_Options();
		hester()->fonts      = new Hester_Fonts();
		hester()->icons      = new Hester_Icons();
		hester()->customizer = new Hester_Customizer();
		if ( is_admin() ) {
			hester()->admin = new Hester_Admin();
		}
	}
}

/**
 * The function which returns the one Hester instance.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $hester = hester(); ?>
 *
 * @since 1.0.0
 * @return object
 */
function hester() {
	return Hester::instance();
}

hester();
