<?php
// Template name: FAQ

get_header();

$faq_groups = get_field( 'faq_group' );
if ( ! is_array( $faq_groups ) && empty( $faq_groups ) ) {
	return;
}
do_action( 'hester_before_singular_container' );
?>

<div class="hester-container">

	<div id="primary" class="content-area">

		<?php do_action( 'hester_before_content' ); ?>

		<main id="content" class="site-content" role="main" <?php hester_schema_markup( 'main' ); ?>>

			<?php do_action( 'hester_before_singular' ); ?>

			<div id="starter-faq-section" class="starter__faq-section pt-5 pb-5">
				<div class="hester-flex-row g-5">
					<div class="col-md-4 col-xs-12">
						<div class="starter__faq-wrapper">
							<ul class="nav nav-starter is-navtab-init-s2" id="starter-tab" role="tablist">
								<?php foreach ( $faq_groups as $key => $faq_group ) { ?>
									<li class="nav-item<?php echo 0 === $key ? ' active' : ''; ?>" role="presentation">
										<a class="hester-btn nav-link" href="#starter-tab-data<?php echo esc_attr( $key + 1 ); ?>"><?php echo esc_html( $faq_group['tab_title'] ); ?></a>
									</li>
								<?php } ?>
							</ul>
						</div>
					</div>
					<div class="col-md-8 col-xs-12">
						<div class="starter__faq-content">
							<div class="tab-content" id="starter-tabContent">
								<?php foreach ( $faq_groups as $key => $faq_group ) { ?>
									<div class="tab-pane fade<?php echo 0 === $key ? ' active' : ''; ?>" id="starter-tab-data<?php echo esc_attr( $key + 1 ); ?>">
										<div class="starter__heading-title mb-5">
											<div class="h4 title">
												<span class="title-shape"><span></span></span><?php echo esc_html( $faq_group['tab_title'] ); ?>
											</div>
										</div>
										<div class="starter__faq-accordion">
											<div class="accordion">
												<?php foreach ( $faq_group['faqs'] as $index => $faq ) { ?>
													<div class="accordion-item">
														<div class="accordion-header">
															<button class="accordion-button" type="button" aria-expanded="<?php echo 0 === $index ? 'true' : 'false'; ?>">
																<?php echo esc_html( $faq['question'] ); ?>
															</button>
														</div>
														<div class="accordion-collapse collapse">
															<div class="accordion-body">
																<?php echo apply_filters( 'the_content', $faq['answer'] ); ?>
															</div>
														</div>
													</div>
												<?php } ?>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
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
