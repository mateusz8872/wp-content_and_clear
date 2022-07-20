<?php
/**
 * Hester Customizer helper functions.
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
 * Returns array of available widgets.
 *
 * @since 1.0.0
 * @return array, $widgets array of available widgets.
 */
function hester_get_customizer_widgets() {

	$widgets = array(
		'text'    => 'Hester_Customizer_Widget_Text',
		'nav'     => 'Hester_Customizer_Widget_Nav',
		'socials' => 'Hester_Customizer_Widget_Socials',
		'search'  => 'Hester_Customizer_Widget_Search',
		'button'  => 'Hester_Customizer_Widget_Button',
	);

	return apply_filters( 'hester_customizer_widgets', $widgets );
}

/**
 * Get choices for "Hide on" customizer options.
 *
 * @since  1.0.0
 * @return array
 */
function hester_get_display_choices() {

	// Default options.
	$return = array(
		'home'       => array(
			'title' => esc_html__( 'Home Page', 'hester' ),
		),
		'posts_page' => array(
			'title' => esc_html__( 'Blog / Posts Page', 'hester' ),
		),
		'search'     => array(
			'title' => esc_html__( 'Search', 'hester' ),
		),
		'archive'    => array(
			'title' => esc_html__( 'Archive', 'hester' ),
			'desc'  => esc_html__( 'Dynamic pages such as categories, tags, custom taxonomies...', 'hester' ),
		),
		'post'       => array(
			'title' => esc_html__( 'Single Post', 'hester' ),
		),
		'page'       => array(
			'title' => esc_html__( 'Single Page', 'hester' ),
		),
	);

	// Get additionally registered post types.
	$post_types = get_post_types(
		array(
			'public'   => true,
			'_builtin' => false,
		),
		'objects'
	);

	if ( is_array( $post_types ) && ! empty( $post_types ) ) {
		foreach ( $post_types as $slug => $post_type ) {
			$return[ $slug ] = array(
				'title' => $post_type->label,
			);
		}
	}

	return apply_filters( 'hester_display_choices', $return );
}

/**
 * Get device choices for "Display on" customizer options.
 *
 * @since  1.0.0
 * @return array
 */
function hester_get_device_choices() {

	// Default options.
	$return = array(
		'desktop' => array(
			'title' => esc_html__( 'Hide On Desktop', 'hester' ),
		),
		'tablet'  => array(
			'title' => esc_html__( 'Hide On Tablet', 'hester' ),
		),
		'mobile'  => array(
			'title' => esc_html__( 'Hide On Mobile', 'hester' ),
		),
	);

	return apply_filters( 'hester_device_choices', $return );
}
