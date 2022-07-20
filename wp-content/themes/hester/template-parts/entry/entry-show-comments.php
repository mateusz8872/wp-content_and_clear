<?php
/**
 * Template part for displaying ”Show Comments” button.
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.0.0
 */

// Do not show if the post is password protected.
if ( post_password_required() ) {
	return;
}

$hester_comment_count = get_comments_number();
$hester_comment_title = esc_html__( 'Leave a Comment', 'hester' );

if ( $hester_comment_count > 0 ) {
	/* translators: %s is comment count */
	$hester_comment_title = esc_html( sprintf( _n( 'Show %s Comment', 'Show %s Comments', $hester_comment_count, 'hester' ), $hester_comment_count ) );
}

?>
<a href="#" id="hester-comments-toggle" class="hester-btn btn-large btn-fw btn-left-icon">
	<?php echo hester()->icons->get_svg( 'chat' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<span><?php echo $hester_comment_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
</a>
