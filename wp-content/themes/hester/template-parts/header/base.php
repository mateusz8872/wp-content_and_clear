<?php
/**
 * The base template for displaying theme header area.
 *
 * @see https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.0.0
 */

?>
<?php do_action( 'hester_before_header' ); ?>
<div id="hester-header" <?php hester_header_classes(); ?>>
	<?php do_action( 'hester_header_content' ); ?>
</div><!-- END #hester-header -->
<?php do_action( 'hester_after_header' ); ?>
