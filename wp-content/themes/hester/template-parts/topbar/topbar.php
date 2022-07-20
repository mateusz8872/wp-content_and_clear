<?php
/**
 * The template for displaying theme top bar.
 *
 * @see https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Hester
 * @author  Peregrine Themes
 * @since   1.0.0
 */

?>

<?php do_action( 'hester_before_topbar' ); ?>
<div id="hester-topbar" <?php hester_top_bar_classes(); ?>>
	<div class="hester-container">
		<div class="hester-flex-row">
			<div class="col-md flex-basis-auto start-sm"><?php do_action( 'hester_topbar_widgets', 'left' ); ?></div>
			<div class="col-md flex-basis-auto end-sm"><?php do_action( 'hester_topbar_widgets', 'right' ); ?></div>
		</div>
	</div>
</div><!-- END #hester-topbar -->
<?php do_action( 'hester_after_topbar' ); ?>
