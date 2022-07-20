<?php
/**
 * Template part for displaying entry footer.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
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

?>

<?php do_action( 'hester_before_entry_footer' ); ?>
<footer class="entry-footer">
	<?php

	// Allow text to be filtered.
	$hester_read_more_text = apply_filters( 'hester_entry_read_more_text', __( 'Read More', 'hester' ) );

	?>
	<a href="<?php echo esc_url( hester_entry_get_permalink() ); ?>" class="hester-btn btn-text-1"><span><?php echo esc_html( $hester_read_more_text ); ?></span></a>
</footer>
<?php do_action( 'hester_after_entry_footer' ); ?>
