<?php
/**
 * Hester Customizer widgets class.
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

if ( ! class_exists( 'Hester_Customizer_Widget_Cart' ) ) :

	/**
	 * Hester Customizer widget class
	 */
	class Hester_Customizer_Widget_Cart extends Hester_Customizer_Widget {

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 * @param array $args An array of the values for this widget.
		 */
		public function __construct( $args = array() ) {

			parent::__construct( $args );

			$this->name        = esc_html__( 'Cart', 'hester' );
			$this->description = esc_html__( 'Displays WooCommerce cart.', 'hester' );
			$this->icon        = 'dashicons dashicons-cart';
			$this->type        = 'cart';
		}

		/**
		 * Displays the form for this widget on the Widgets page of the WP Admin area.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function form() {}
	}
endif;
