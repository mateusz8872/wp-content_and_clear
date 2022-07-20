<?php
/**
 * The template for displaying all pages, single posts and attachments.
 *
 * This is a new template file that WordPress introduced in
 * version 4.3.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.0.0
 */

?>

<?php
get_header();

do_action( 'hester_before_singular_container' );
?>

<div class="hester-container">

	<div id="primary" class="content-area">

		<?php do_action( 'hester_before_content' ); ?>

		<main id="content" class="site-content" role="main"<?php hester_schema_markup( 'main' ); ?>>

			<?php
			do_action( 'hester_before_singular' );

			do_action( 'hester_content_singular' );

			do_action( 'hester_after_singular' );
			?>

		</main><!-- #content .site-content -->

		<?php do_action( 'hester_after_content' ); ?>

	</div><!-- #primary .content-area -->

	<?php do_action( 'hester_sidebar' ); ?>

</div><!-- END .hester-container -->

<?php
do_action( 'hester_after_singular_container' );
get_footer();
