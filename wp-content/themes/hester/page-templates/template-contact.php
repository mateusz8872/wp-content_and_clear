<?php
// Template name: Contact us

get_header();

$subheading           = get_field( 'sub_heading' ) ?? '';
$heading              = get_field( 'heading' ) ?? '';
$description          = get_field( 'description' ) ?? '';
$contact_infos        = get_field( 'contact_infos' ) ?? array();
$contact_infos_column = get_field( 'contact_infos_column' ) ?? '';
$google_map           = get_field( 'google_map' ) ?? '';
$form                 = get_field( 'form' ) ?? array();


do_action( 'hester_before_singular_container' );
?>

<div class="hester-container">

	<div id="primary" class="content-area">

		<?php do_action( 'hester_before_content' ); ?>

		<main id="content" class="site-content" role="main" <?php hester_schema_markup( 'main' ); ?>>

			<?php do_action( 'hester_before_singular' ); ?>

			<div id="starter-contact-section" class="starter__contact-section">
				<?php if ( $heading != '' || $subheading != '' || $description != '' ) { ?>
				<div class="hester-flex-row">
					<div class="col-md-7 col-xs-12 mx-md-auto mb-h center-xs">
						<?php
						$heading_block = array(
							'heading'     => $heading,
							'sub_heading' => $subheading,
							'description' => $description,
							'section'     => 'contact',
						);

						get_template_part( 'template-parts/components/block', 'heading', $heading_block );
						?>
					</div>
				</div>
				<?php } ?>
				<div class="hester-flex-row g-4">
					<?php
					if ( is_array( $contact_infos ) && ! empty( $contact_infos ) ) {
						foreach ( $contact_infos as $info ) {
							?>
							<div class="col-md<?php echo esc_attr( $contact_infos_column ); ?> col-sm-6 col-xs-12">
								<div class="starter__contact-item">
									<div class="widget widget_information">
										<div class="widget_information-wrapper is__grid">
											<div class="widget_information-start">
												<div class="widget_information-icon"><i class="<?php echo esc_attr( $info['icon'] ); ?>"></i></div>
											</div>
											<div class="widget_information-end">
												<div class="h4 title"><?php echo esc_html( $info['title'] ); ?></div>
												<div class="description"><?php echo wp_kses_post( $info['text'] ); ?></div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php
						}
					}
					?>
				</div>
			</div>
			<!-- .starter__contact-section -->

			<div id="starter-contactform-section" class="starter__contactform-section bg-primary-light">
				<div class="hester-flex-row">
					<?php if ( $google_map !== '' ) { ?>
					<div class="col-md-6 col-xs-12">
						<div class="starter__map">
							<iframe src="<?php echo esc_url_raw( $google_map ); ?>" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
						</div>
					</div>
					<?php } ?>
					<div class="col-md-6 col-xs-12 starter__contactform-padding">
						<?php if ( isset( $form['subtitle'] ) && $form['subtitle'] !== '' || isset( $form['title'] ) && $form['title'] !== '' ) { ?>
						<div class="hester-flex-row">
							<div class="col-md-12 col-xs-12 mx-md-auto mb-4 center-xs">
								<div class="starter__heading-title">
									<?php if ( isset( $form['subtitle'] ) && $form['subtitle'] !== '' ) { ?>
									<div class="h6 sub-title text-primary">
										<span class="sub-shape"><span></span></span><?php echo esc_html( $form['subtitle'] ); ?>
									</div>
										<?php
									}
									?>
									<?php if ( isset( $form['title'] ) && $form['title'] !== '' ) { ?>
									<div class="h2 title">
										<span class="title-shape"><span></span></span><?php echo esc_html( $form['title'] ); ?>
									</div>
										<?php
									}
									?>
								</div>
							</div>
						</div>
						<?php } ?>
						<div class="hester-flex-row">
							<div class="col-xs-12">
								<div class="starter__contactform-wrapper">
									<div class="starter__contactform">
										<?php echo do_shortcode( $form['form_shortcode'] ); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php do_action( 'hester_after_singular' ); ?>

		</main><!-- #content .site-content -->

		<?php do_action( 'hester_after_content' ); ?>

	</div><!-- #primary .content-area -->

	<?php
	do_action( 'hester_sidebar' );
	?>

</div><!-- END .hester-container -->

<!-- .starter__faq-section -->
<?php
do_action( 'hester_after_singular_container' );
get_footer(); ?>
