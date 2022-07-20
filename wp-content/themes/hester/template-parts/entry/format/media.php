<?php
/**
 * Template part for displaying entry thumbnail (featured image).
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

// Get default post media.
$hester_media = hester_get_post_media( '' );

if ( ! $hester_media || post_password_required() ) {
	return;
}

$hester_post_format = get_post_format();

// Wrap with link for non-singular pages.
if ( 'link' === $hester_post_format || ! is_single( get_the_ID() ) ) {

	$hester_icon = '';

	if ( is_sticky() ) {
		$hester_icon = sprintf(
			'<span class="entry-media-icon" title="%1$s" aria-hidden="true"><span class="entry-media-icon-wrapper">%2$s%3$s</span></span>',
			esc_attr__( 'Featured', 'hester' ),
			hester()->icons->get_svg(
				'star',
				array(
					'class'       => 'top-icon',
					'aria-hidden' => 'true',
				)
			),
			hester()->icons->get_svg( 'star', array( 'aria-hidden' => 'true' ) )
		);
	} elseif ( 'video' === $hester_post_format ) {
		$hester_icon = sprintf(
			'<span class="entry-media-icon" aria-hidden="true"><span class="entry-media-icon-wrapper">%1$s%2$s</span></span>',
			hester()->icons->get_svg(
				'play',
				array(
					'class'       => 'top-icon',
					'aria-hidden' => 'true',
				)
			),
			hester()->icons->get_svg( 'play', array( 'aria-hidden' => 'true' ) )
		);
	} elseif ( 'link' === $hester_post_format ) {
		$hester_icon = sprintf(
			'<span class="entry-media-icon" title="%1$s" aria-hidden="true"><span class="entry-media-icon-wrapper">%2$s%3$s</span></span>',
			esc_url( hester_entry_get_permalink() ),
			hester()->icons->get_svg(
				'external-link',
				array(
					'class'       => 'top-icon',
					'aria-hidden' => 'true',
				)
			),
			hester()->icons->get_svg( 'external-link', array( 'aria-hidden' => 'true' ) )
		);
	}

	$hester_icon = apply_filters( 'hester_post_format_media_icon', $hester_icon, $hester_post_format );

	$hester_media = sprintf(
		'<a href="%1$s" class="entry-image-link">%2$s%3$s</a>',
		esc_url( hester_entry_get_permalink() ),
		$hester_media,
		$hester_icon
	);
}

$hester_media = apply_filters( 'hester_post_thumbnail', $hester_media );

// Print the post thumbnail.
echo wp_kses(
	sprintf(
		'<div class="post-thumb entry-media thumbnail">%1$s</div>',
		$hester_media
	),
	hester_get_allowed_html_tags()
);
