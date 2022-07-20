<?php
/**
 * Header Cart Widget cart & checkout buttons.
 *
 * @package Hester
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="hester-cart-buttons">
	<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="hester-btn btn-text-1" role="button">
		<span><?php esc_html_e( 'View Cart', 'hester' ); ?></span>
	</a>

	<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="hester-btn btn-fw" role="button">
		<span><?php esc_html_e( 'Checkout', 'hester' ); ?></span>
	</a>
</div>
