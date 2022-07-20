<?php
/**
 * The template for displaying scroll to top button.
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.0.0
 */

?>

<a href="#" id="hester-scroll-top" class="hester-smooth-scroll" title="<?php esc_attr_e( 'Scroll to Top', 'hester' ); ?>" <?php hester_scroll_top_classes(); ?>>
	<span class="hester-scroll-icon" aria-hidden="true">
		<?php echo hester()->icons->get_svg( 'chevron-up', array( 'class' => 'top-icon' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo hester()->icons->get_svg( 'chevron-up' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</span>
	<span class="screen-reader-text"><?php esc_html_e( 'Scroll to Top', 'hester' ); ?></span>
</a><!-- END #hester-scroll-to-top -->
