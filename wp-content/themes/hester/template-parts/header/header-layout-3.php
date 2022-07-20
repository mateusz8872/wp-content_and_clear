<?php
/**
 * The template for displaying header layout 3.
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.0.0
 */

?>

<div class="hester-header-container">
	<div class="hester-logo-container">
		<div class="hester-container">

			<?php
			do_action( 'hester_header_widget_location', 'left' );
			hester_header_logo_template();
			do_action( 'hester_header_widget_location', 'right' );
			?>

			<span class="hester-header-element hester-mobile-nav">
				<?php hester_hamburger( hester_option( 'main_nav_mobile_label' ), 'hester-primary-nav' ); ?>
			</span>

		</div><!-- END .hester-container -->
	</div><!-- END .hester-logo-container -->

	<div class="hester-nav-container">
		<div class="hester-container">

			<?php hester_main_navigation_template(); ?>

		</div><!-- END .hester-container -->
	</div><!-- END .hester-nav-container -->
</div><!-- END .hester-header-container -->
