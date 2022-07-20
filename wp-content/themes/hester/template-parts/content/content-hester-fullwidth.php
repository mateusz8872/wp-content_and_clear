<?php
/**
 * Template part for displaying content of Hester Canvas [Fullwidth] page template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Hester
 * @author  Peregrine Themes
 * @since   1.0.0
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?><?php hester_schema_markup( 'article' ); ?>>
	<div class="entry-content hester-entry hester-fullwidth-entry">
		<?php
		do_action( 'hester_before_page_content' );

		the_content();

		do_action( 'hester_after_page_content' );
		?>
	</div><!-- END .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
