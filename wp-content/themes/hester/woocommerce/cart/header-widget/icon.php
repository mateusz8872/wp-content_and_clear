<?php
/**
 * Header Cart Widget icon.
 *
 * @package Hester
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hester_cart_count = WC()->cart->get_cart_contents_count();
$hester_cart_icon  = apply_filters( 'hester_wc_cart_widget_icon', 'shopping-bag' );

?>
<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="hester-cart">
	<?php echo hester()->icons->get_svg( $hester_cart_icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php if ( $hester_cart_count > 0 ) { ?>
		<span class="hester-cart-count"><?php echo esc_html( $hester_cart_count ); ?></span>
	<?php } ?>
</a>
