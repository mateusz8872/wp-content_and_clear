<?php
// Template name: Blog Masonry

get_header();

$subheading  = get_field( 'sub_heading' ) ?? '';
$heading     = get_field( 'heading' ) ?? '';
$description = get_field( 'description' ) ?? '';

?>

<div class="hester-container">

	<div id="primary" class="content-area">

		<main id="content" class="site-content" role="main" <?php hester_schema_markup( 'main' ); ?>>

			<div id="starter-blog-section" class="starter__blog-section filter__integrate pt-5 pb-5">
				<div class="hester-flex-row">
					<div class="col-md-7 col-xs-12 mx-md-auto mb-h center-xs">
						<?php
						$heading_block = array(
							'heading'     => $heading,
							'sub_heading' => $subheading,
							'description' => $description,
							'section'     => 'blog-masonry',
						);

						get_template_part( 'template-parts/components/block', 'heading', $heading_block );
						?>
					</div>
				</div>
				<div class="hester-flex-row g-4 filter__init">
					<?php
					$args = array(
						'post_type'           => 'post',
						'posts_per_page'      => 10,
						'ignore_sticky_posts' => true,
						'paged'               => get_query_var( 'paged' ),
					);

					$posts = new WP_Query( $args );

					if ( $posts->have_posts() ) :
						while ( $posts->have_posts() ) :
							$posts->the_post();
							?>
							<div class="col-md-4 col-sm-6 col-xs-12 filter__item">
								<?php get_template_part( 'template-parts/content/content', hester_get_article_feed_layout() ); ?>
							</div>
							<?php
											endwhile;

						hester_pagination();

					else :
						get_template_part( 'template-parts/content/content', 'none' );
					endif;
					?>
				</div>
			</div>
			<!-- .starter__blog-section -->

		</main><!-- #content .site-content -->

	</div><!-- #primary .content-area -->

</div><!-- END .hester-container -->

<!-- .starter__faq-section -->
<?php
get_footer(); ?>
