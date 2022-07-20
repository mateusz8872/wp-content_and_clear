<?php
/**
 * The template for displaying page preloader.
 *
 * @see https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Hester
 * @author  Peregrine Themes
 * @since   1.0.0
 */

?>

<div id="hester-preloader"<?php hester_preloader_classes(); ?>>
	<?php get_template_part( 'template-parts/preloader/preloader', hester_option( 'preloader_style' ) ); ?>
</div><!-- END #hester-preloader -->
