<?php
/**
 * The template for displaying theme copyright bar.
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.0.0
 */

?>

<?php do_action( 'hester_before_copyright' ); ?>
<div id="hester-copyright" <?php hester_copyright_classes(); ?>>
	<div class="hester-container">
		<div class="hester-flex-row">

			<div class="col-xs-12 center-xs col-md flex-basis-auto start-md"><?php do_action( 'hester_copyright_widgets', 'start' ); ?></div>
			<div class="col-xs-12 center-xs col-md flex-basis-auto end-md"><?php do_action( 'hester_copyright_widgets', 'end' ); ?></div>

		</div><!-- END .hester-flex-row -->
	</div>
</div><!-- END #hester-copyright -->
<?php do_action( 'hester_after_copyright' ); ?>
