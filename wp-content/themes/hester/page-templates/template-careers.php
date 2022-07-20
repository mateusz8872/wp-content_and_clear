<?php
// Template name: Careers

get_header();

$subtitle    = get_field( 'sub_title' );
$title       = get_field( 'title' );
$description = get_field( 'description' );
$jobs        = get_field( 'jobs' );


do_action( 'hester_before_singular_container' );
?>

<div class="hester-container">

	<div id="primary" class="content-area">

		<?php do_action( 'hester_before_content' ); ?>

		<main id="content" class="site-content" role="main" <?php hester_schema_markup( 'main' ); ?>>

			<?php do_action( 'hester_before_singular' ); ?>

			<div id="starter-career-section" class="starter__career-section pt-5 pb-5">
				<div class="hester-flex-row">
					<div class="col-md-7 col-xs-12 mx-md-auto mb-h center-xs">
						<?php
						$heading_block = array(
							'heading'     => $title,
							'sub_heading' => $subtitle,
							'description' => $description,
							'section'     => 'careers',
						);

						get_template_part( 'template-parts/components/block', 'heading', $heading_block );
						?>
					</div>
				</div>
				<div class="hester-flex-row g-4 st-career-init-s1">
				<?php
				if ( is_array( $jobs ) && ! empty( $jobs ) ) {
					foreach ( $jobs as $job ) {
						?>

							<div class="col-md-4 col-sm-6 col-xs-12 mx-auto">
								<div class="starter__career-item">
									<div class="starter__career-inner">
										<div class="starter__career-holder">
											 <?php if ( $job['icon'] != '' ) { ?>
												<div class="starter__career-icon">
													<img src="<?php echo esc_url_raw( $job['icon'] ); ?>" alt="<?php echo esc_attr( $job['job_title'] ); ?>">
												</div>
											<?php } ?>
											<div class="starter__career-meta">
												<h5 class="title"><?php echo esc_html( $job['job_title'] ); ?></h5>

												 <?php if ( is_array( $job['job_info'] ) && ! empty( $job['job_info'] ) ) { ?>
													<ul class="starter__career-feature">
														<?php foreach ( $job['job_info'] as $info ) { ?>
															<li><b><?php echo wp_kses_post( $info['key'] ); ?></b>: <?php echo wp_kses_post( $info['value'] ); ?></li>
														<?php } ?>
													</ul>
												<?php } ?>
											</div>
										</div>
										<div class="description"><?php echo esc_html( $job['job_description'] ); ?></div>
										 <?php if ( is_array( $job['button'] ) && ! empty( $job['button'] ) ) { ?>
											<div class="starter__career-button">
												<a class="hester-btn btn-secondary" target="<?php echo $job['button']['target']; ?>" href="<?php echo esc_url_raw( $job['button']['url'] ); ?>"><i class="fas fa-arrow-circle-right"></i><span><?php echo esc_html( $job['button']['title'] ); ?></span></a>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<?php
					}
				}
				?>
				</div>
			</div>

			<?php do_action( 'hester_after_singular' ); ?>

		</main><!-- #content .site-content -->

		<?php do_action( 'hester_after_content' ); ?>

	</div><!-- #primary .content-area -->

	<?php do_action( 'hester_sidebar' ); ?>

</div><!-- END .hester-container -->

<!-- .starter__faq-section -->
<?php
do_action( 'hester_after_singular_container' );
get_footer(); ?>
