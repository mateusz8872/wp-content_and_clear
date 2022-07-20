<?php
/**
 * The template for displaying header layout 1.
 *
 * @package Hester
 * @author  Peregrine Themes
 * @since   1.0.0
 */

?>

<div class="hester-container hester-header-container">

	<?php
	hester_header_logo_template();
	?>

	<span class="hester-header-element hester-mobile-nav">
		<?php hester_hamburger( hester_option( 'main_nav_mobile_label' ), 'hester-primary-nav' ); ?>
	</span>

	<?php
	hester_main_navigation_template();
	do_action( 'hester_header_widget_location', array( 'left', 'right' ) );
	?>

</div><!-- END .hester-container -->
