<?php
/**
 * The template for displaying Hero Hover Slider.
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.0.0
 */

$hester_hero_categories = ! empty( $hester_hero_categories ) ? implode( ', ', $hester_hero_categories ) : '';

// Setup Hero posts.
$hester_args = array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => hester_option( 'hero_hover_slider_post_number' ), // phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page
	'ignore_sticky_posts' => true,
	'tax_query'           => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		array(
			'taxonomy' => 'post_format',
			'field'    => 'slug',
			'terms'    => array( 'post-format-quote' ),
			'operator' => 'NOT IN',
		),
	),
);

$hester_hero_categories = hester_option( 'hero_hover_slider_category' );

if ( ! empty( $hester_hero_categories ) ) {
	$hester_args['category_name'] = implode( ', ', $hester_hero_categories );
}

$hester_args = apply_filters( 'hester_hero_hover_slider_query_args', $hester_args );

$hester_posts = new WP_Query( $hester_args );

// No posts found.
if ( ! $hester_posts->have_posts() ) {
	return;
}

$hester_hero_bgs_html   = '';
$hester_hero_items_html = '';

$hester_hero_elements = (array) hester_option( 'hero_hover_slider_elements' );
$hester_hero_readmore = isset( $hester_hero_elements['read_more'] ) && $hester_hero_elements['read_more'] ? ' hester-hero-readmore' : '';

while ( $hester_posts->have_posts() ) :
	$hester_posts->the_post();

	// Background images HTML markup.
	$hester_hero_bgs_html .= '<div class="hover-slide-bg" data-background="' . get_the_post_thumbnail_url( get_the_ID(), 'full' ) . '"></div>';

	// Post items HTML markup.
	ob_start();
	?>
	<div class="col-xs-<?php echo esc_attr( 12 / $hester_args['posts_per_page'] ); ?> hover-slider-item-wrapper<?php echo esc_attr( $hester_hero_readmore ); ?>">
		<div class="hover-slide-item">
			<div class="slide-inner">

				<?php if ( isset( $hester_hero_elements['category'] ) && $hester_hero_elements['category'] ) { ?>
					<div class="post-category">
						<?php hester_entry_meta_category( ' ', false ); ?>
					</div>
				<?php } ?>

				<?php if ( get_the_title() ) { ?>
					<h3><a href="<?php echo esc_url( hester_entry_get_permalink() ); ?>"><?php the_title(); ?></a></h3>
				<?php } ?>

				<?php if ( isset( $hester_hero_elements['meta'] ) && $hester_hero_elements['meta'] ) { ?>
					<div class="entry-meta">
						<div class="entry-meta-elements">
							<?php
							hester_entry_meta_author();

							hester_entry_meta_date(
								array(
									'show_modified'   => false,
									'published_label' => '',
								)
							);
							?>
						</div>
					</div><!-- END .entry-meta -->
				<?php } ?>

				<?php if ( $hester_hero_readmore ) { ?>
					<a href="<?php echo esc_url( hester_entry_get_permalink() ); ?>" class="read-more hester-btn btn-small btn-outline btn-uppercase" role="button"><span><?php esc_html_e( 'Continue Reading', 'hester' ); ?></span></a>
				<?php } ?>

			</div><!-- END .slide-inner -->
		</div><!-- END .hover-slide-item -->
	</div><!-- END .hover-slider-item-wrapper -->
	<?php
	$hester_hero_items_html .= ob_get_clean();
endwhile;

// Restore original Post Data.
wp_reset_postdata();

// Hero container.
$hester_hero_container = hester_option( 'hero_hover_slider_container' );
$hester_hero_container = 'full-width' === $hester_hero_container ? 'hester-container hester-container__wide' : 'hester-container';

// Hero overlay.
$hester_hero_overlay = absint( hester_option( 'hero_hover_slider_overlay' ) );
?>

<div class="hester-hover-slider slider-overlay-<?php echo esc_attr( $hester_hero_overlay ); ?>">
	<div class="hover-slider-backgrounds">

		<?php echo wp_kses_post( $hester_hero_bgs_html ); ?>

	</div><!-- END .hover-slider-items -->

	<div class="hester-hero-container <?php echo esc_attr( $hester_hero_container ); ?>">
		<div class="hester-flex-row hover-slider-items">

			<?php echo wp_kses_post( $hester_hero_items_html ); ?>

		</div><!-- END .hover-slider-items -->
	</div>

	<div class="hester-spinner visible">
		<div></div>
		<div></div>
	</div>
</div><!-- END .hester-hover-slider -->
