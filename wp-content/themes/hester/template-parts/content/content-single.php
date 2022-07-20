<?php
/**
 * Template for Single post
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Hester
 * @author  Peregrine Themes
 * @since   1.0.0
 */

?>

<?php do_action( 'hester_before_article' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'hester-article' ); ?><?php hester_schema_markup( 'article' ); ?>>

	<?php
	if ( 'quote' === get_post_format() ) {
		get_template_part( 'template-parts/entry/format/media', 'quote' );
	}

	$hester_single_post_elements = hester_get_single_post_elements();

	if ( ! empty( $hester_single_post_elements ) ) {
		foreach ( $hester_single_post_elements as $hester_element ) {

			if ( 'content' === $hester_element ) {
				do_action( 'hester_before_single_content' );
				get_template_part( 'template-parts/entry/entry', $hester_element );
				do_action( 'hester_after_single_content' );
			} else {
				get_template_part( 'template-parts/entry/entry', $hester_element );
			}
		}
	}
	?>

</article><!-- #post-<?php the_ID(); ?> -->

<?php do_action( 'hester_after_article' ); ?>
