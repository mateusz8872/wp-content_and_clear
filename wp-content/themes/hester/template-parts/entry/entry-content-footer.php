<?php
/**
 * Template part for displaying entry tags.
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.0.0
 */

$hester_entry_elements    = hester_option( 'single_post_elements' );
$hester_entry_footer_tags = isset( $hester_entry_elements['tags'] ) && $hester_entry_elements['tags'] && has_tag();
$hester_entry_footer_date = isset( $hester_entry_elements['last-updated'] ) && $hester_entry_elements['last-updated'] && get_the_time( 'U' ) !== get_the_modified_time( 'U' );

$hester_entry_footer_tags = apply_filters( 'hester_display_entry_footer_tags', $hester_entry_footer_tags );
$hester_entry_footer_date = apply_filters( 'hester_display_entry_footer_date', $hester_entry_footer_date );

// Nothing is enabled, don't display the div.
if ( ! $hester_entry_footer_tags && ! $hester_entry_footer_date ) {
	return;
}
?>

<?php do_action( 'hester_before_entry_footer' ); ?>

<div class="entry-footer">

	<?php
	// Post Tags.
	if ( $hester_entry_footer_tags ) {
		hester_entry_meta_tag(
			'<div class="post-tags"><span class="cat-links">',
			'',
			'</span></div>',
			0,
			false
		);
	}

	// Last Updated Date.
	if ( $hester_entry_footer_date ) {

		$hester_before = '<span class="last-updated hester-iflex-center">';

		if ( true === hester_option( 'single_entry_meta_icons' ) ) {
			$hester_before .= hester()->icons->get_svg( 'edit-3' );
		}

		hester_entry_meta_date(
			array(
				'show_published' => false,
				'show_modified'  => true,
				'before'         => $hester_before,
				'after'          => '</span>',
			)
		);
	}
	?>

</div>

<?php do_action( 'hester_after_entry_footer' ); ?>
