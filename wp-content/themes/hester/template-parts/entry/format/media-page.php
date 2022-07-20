<?php
/**
 * Template part for displaying page featured image.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Hester
 * @author  Peregrine Themes
 * @since   1.0.0
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get default post media.
$hester_media = hester_get_post_media( '' );

if ( ! $hester_media || post_password_required() ) {
	return;
}

$hester_media = apply_filters( 'hester_post_thumbnail', $hester_media, get_the_ID() );

$hester_classes = array( 'post-thumb', 'entry-media', 'thumbnail' );

$hester_classes = apply_filters( 'hester_post_thumbnail_wrapper_classes', $hester_classes, get_the_ID() );
$hester_classes = trim( implode( ' ', array_unique( $hester_classes ) ) );

// Print the post thumbnail.
echo wp_kses_post(
	sprintf(
		'<div class="%2$s">%1$s</div>',
		$hester_media,
		esc_attr( $hester_classes )
	)
);
