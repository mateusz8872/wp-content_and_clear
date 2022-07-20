<?php
/**
 * The template for displaying theme pre footer bar.
 *
 * @see https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Hester
 * @author  Peregrine Themes
 * @since   1.0.0
 */

?>

<div id="hester-pre-footer">

	<?php
	if ( hester_is_pre_footer_cta_displayed() ) {
		get_template_part( 'template-parts/pre-footer/call-to-action' );
	}
	?>

</div><!-- END #hester-pre-footer -->
