<?php
/**
 * Template part for displaying post in post listing.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.0.0
 */

?>

<?php do_action( 'hester_before_article' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'hester-article' ); ?><?php hester_schema_markup( 'article' ); ?>>

	<?php
	$hester_blog_entry_format = get_post_format();

	if ( 'quote' === $hester_blog_entry_format ) {
		get_template_part( 'template-parts/entry/format/media', $hester_blog_entry_format );
	} else {

		$hester_blog_entry_elements = hester_get_blog_entry_elements();

		if ( ! empty( $hester_blog_entry_elements ) ) {
			foreach ( $hester_blog_entry_elements as $hester_element ) {
				get_template_part( 'template-parts/entry/entry', $hester_element );
			}
		}
	}
	?>

</article><!-- #post-<?php the_ID(); ?> -->

<?php do_action( 'hester_after_article' ); ?>
