<?php
/**
 * Header Cart Widget dropdown header.
 *
 * @package Hester
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hester_cart_count    = WC()->cart->get_cart_contents_count();
$hester_cart_subtotal = WC()->cart->get_cart_subtotal();

?>
<div class="wc-cart-widget-header">
	<span class="hester-cart-count">
		<?php
		/* translators: %s: the number of cart items; */
		echo wp_kses_post( sprintf( _n( '%s item', '%s items', $hester_cart_count, 'hester' ), $hester_cart_count ) );
		?>
	</span>

	<span class="hester-cart-subtotal">
		<?php
		/* translators: %s is the cart subtotal. */
		echo wp_kses_post( sprintf( __( 'Subtotal: %s', 'hester' ), '<span>' . $hester_cart_subtotal . '</span>' ) );
		?>
	</span>
</div>
