<?php
/**
 * Template part for displaying entry meta info.
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

/**
 * Only show meta tags for posts.
 */
if ( ! in_array( get_post_type(), (array) apply_filters( 'hester_entry_meta_post_type', array( 'post' ) ), true ) ) {
	return;
}

do_action( 'hester_before_entry_meta' );

// Get meta items to be displayed.
$hester_meta_elements = hester_get_entry_meta_elements();

if ( ! empty( $hester_meta_elements ) ) {

	echo '<div class="entry-meta"><div class="entry-meta-elements">';

	do_action( 'hester_before_entry_meta_elements' );

	// Loop through meta items.
	foreach ( $hester_meta_elements as $hester_meta_item ) {

		// Call a template tag function.
		if ( function_exists( 'hester_entry_meta_' . $hester_meta_item ) ) {
			call_user_func( 'hester_entry_meta_' . $hester_meta_item );
		}
	}

	// Add edit post link.
	$hester_edit_icon = hester()->icons->get_meta_icon( 'edit', hester()->icons->get_svg( 'edit-3', array( 'aria-hidden' => 'true' ) ) );

	hester_edit_post_link(
		sprintf(
			wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
				$hester_edit_icon . __( 'Edit <span class="screen-reader-text">%s</span>', 'hester' ),
				hester_get_allowed_html_tags()
			),
			get_the_title()
		),
		'<span class="edit-link">',
		'</span>'
	);

	do_action( 'hester_after_entry_meta_elements' );

	echo '</div></div>';
}

do_action( 'hester_after_entry_meta' );
