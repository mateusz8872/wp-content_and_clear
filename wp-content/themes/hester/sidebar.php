<?php
/**
 * The template for displaying theme sidebar.
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.0.0
 */

if ( ! hester_is_sidebar_displayed() ) {
	return;
}

$hester_sidebar = hester_get_sidebar();
?>

<aside id="secondary" class="widget-area hester-sidebar-container"<?php hester_schema_markup( 'sidebar' ); ?> role="complementary">

	<div class="hester-sidebar-inner">
		<?php do_action( 'hester_before_sidebar' ); ?>

		<?php
		if ( is_active_sidebar( $hester_sidebar ) ) {

			dynamic_sidebar( $hester_sidebar );

		} elseif ( current_user_can( 'edit_theme_options' ) ) {

			$hester_sidebar_name = hester_get_sidebar_name_by_id( $hester_sidebar );
			$title_shape         = '<span class="title-shape"><span></span></span>';
			?>
			<div class="hester-sidebar-widget hester-widget hester-no-widget">

				<div class='h4 widget-title'><?php echo $title_shape . esc_html( $hester_sidebar_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div> 

				<p class='no-widget-text'>
					<?php if ( is_customize_preview() ) { ?>
						<a href='#' class="hester-set-widget" data-sidebar-id="<?php echo esc_attr( $hester_sidebar ); ?>">
					<?php } else { ?>
						<a href='<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>'>
					<?php } ?>
						<?php esc_html_e( 'Click here to assign a widget.', 'hester' ); ?>
					</a>
				</p>
			</div>
			<?php
		}
		?>

		<?php do_action( 'hester_after_sidebar' ); ?>
	</div>

</aside><!--#secondary .widget-area -->

<?php
