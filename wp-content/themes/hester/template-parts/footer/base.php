<?php
/**
 * The template for displaying theme footer.
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.0.0
 */

?>

<?php do_action( 'hester_before_footer' ); ?>
<div id="hester-footer" <?php hester_footer_classes(); ?>>
	<div class="hester-container">
		<div class="hester-flex-row" id="hester-footer-widgets">

			<?php hester_footer_widgets(); ?>

		</div><!-- END .hester-flex-row -->
	</div><!-- END .hester-container -->
</div><!-- END #hester-footer -->
<?php do_action( 'hester_after_footer' ); ?>
