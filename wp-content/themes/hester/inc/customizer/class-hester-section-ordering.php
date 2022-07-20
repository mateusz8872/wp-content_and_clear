<?php

/**
 * Customizer sections order main file
 *
 * @package Hester
 */

/**
 * Class Hester_Section_Ordering
 */
class Hester_Section_Ordering {

	/**
	 * Singleton instance of the class.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	private static $instance;


	/**
	 * Main Hester_Section_Ordering Instance.
	 *
	 * @since 1.0.0
	 * @return Hester_Section_Ordering
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Hester_Section_Ordering ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Initialize the section ordering module.
	 */
	public function __construct() {
		add_action( 'customize_preview_init', array( $this, 'refresh_positions' ), 100 );
		add_action( 'init', array( $this, 'refresh_positions' ), 100 );
		add_filter( 'hester_section_priority', array( $this, 'get_section_priority' ), 10, 2 );
	}

	/**
	 * Function for returning section priority
	 *
	 * @param integer $value Default priority.
	 * @param string  $key Section id.
	 *
	 * @return int
	 */
	public function get_section_priority( $value, $key = '' ) {

		$orders = get_theme_mod( 'hester_sections_order' );
		if ( empty( $orders ) ) {
			return $value;
		}
		$json = json_decode( $orders );
		if ( isset( $json->$key ) ) {
			return $json->$key;
		}

		return $value;
	}

	/**
	 * Function to refresh customize preview when changing sections order
	 */
	public function refresh_positions() {
		$section_order         = get_theme_mod( 'hester_sections_order' );
		$section_order_decoded = json_decode( $section_order, true );
		if ( ! empty( $section_order_decoded ) ) {
			remove_all_actions( 'hester_sections' );
			foreach ( $section_order_decoded as $k => $priority ) {
				$this->hook_section_by_slug( $k, $priority );
			}
		}
	}


	/**
	 * Hook section by slug.
	 *
	 * @param string  $slug section slug.
	 * @param integer $priority section priority.
	 */
	private function hook_section_by_slug( $slug, $priority ) {
		if ( empty( $slug ) ) {
			return;
		}
		add_action( 'hester_sections', $slug, absint( $priority ) );
	}
}


function hester_section_reordering() {
	return Hester_Section_Ordering::instance();
}
hester_section_reordering();
