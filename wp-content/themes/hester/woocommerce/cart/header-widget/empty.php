<?php
/**
 * Header Cart Widget empty cart.
 *
 * @package Hester
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="hester-empty-cart">
	<?php echo hester()->icons->get_svg( 'shopping-empty', array( 'aria-hidden' => 'true' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<p><?php esc_html_e( 'No products in the cart.', 'hester' ); ?></p>
</div>
