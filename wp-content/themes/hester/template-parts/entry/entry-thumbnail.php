<?php
/**
 * Template part for displaying media of the entry.
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

$hester_post_format = get_post_format();

if ( is_single() ) {
	$hester_post_format = '';
}

do_action( 'hester_before_entry_thumbnail' );

get_template_part( 'template-parts/entry/format/media', $hester_post_format );

do_action( 'hester_after_entry_thumbnail' );
