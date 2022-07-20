<?php
/**
 * Template Name: Hester Fullwidth
 *
 * 100% wide page template without vertical spacing.
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.0.0
 */

get_header();
do_action( 'hester_before_singular_container' );
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/content/content', 'hester-fullwidth' );
	endwhile;
endif;
do_action( 'hester_after_singular_container' );
get_footer();
