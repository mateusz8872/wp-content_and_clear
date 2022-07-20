<?php
/**
 * The template for displaying search results pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.0.0
 */

?>

<?php get_header(); ?>

<div class="hester-container">

	<div id="primary" class="content-area">

		<?php do_action( 'hester_before_content' ); ?>

		<main id="content" class="site-content" role="main"<?php hester_schema_markup( 'main' ); ?>>

			<?php do_action( 'hester_content_search' ); ?>

		</main><!-- #content .site-content -->

		<?php do_action( 'hester_after_content' ); ?>

	</div><!-- #primary .content-area -->

	<?php do_action( 'hester_sidebar' ); ?>

</div><!-- END .hester-container -->

<?php
get_footer();
