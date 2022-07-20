<?php
/**
 * Template part for displaying blog post - horizontal.
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

		$hester_classes     = array();
		$hester_classes[]   = 'hester-blog-entry-wrapper';
		$hester_thumb_align = hester_option( 'blog_image_position' );
		$hester_thumb_align = apply_filters( 'hester_horizontal_blog_image_position', $hester_thumb_align );
		$hester_classes[]   = 'hester-thumb-' . $hester_thumb_align;
		$hester_classes     = implode( ' ', $hester_classes );
		?>

		<div class="<?php echo esc_attr( $hester_classes ); ?>">
			<?php get_template_part( 'template-parts/entry/entry-thumbnail' ); ?>

			<div class="hester-entry-content-wrapper">

				<?php
				if ( hester_option( 'blog_horizontal_post_categories' ) ) {
					get_template_part( 'template-parts/entry/entry-category' );
				}

				get_template_part( 'template-parts/entry/entry-header' );
				get_template_part( 'template-parts/entry/entry-summary' );


				if ( hester_option( 'blog_horizontal_read_more' ) ) {
					get_template_part( 'template-parts/entry/entry-summary-footer' );
				}

				get_template_part( 'template-parts/entry/entry-meta' );
				?>
			</div>
		</div>

	<?php } ?>

</article><!-- #post-<?php the_ID(); ?> -->

<?php do_action( 'hester_after_article' ); ?>
