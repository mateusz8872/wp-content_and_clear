<?php
/**
 * The template for displaying call to action in pre footer.
 *
 * @see https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.0.0
 */

$hester_cta_text = apply_filters( 'hester_pre_footer_cta_text', hester_option( 'pre_footer_cta_text' ) );

$hester_cta_button_args = array(
	'text'    => hester_option( 'pre_footer_cta_btn_text' ),
	'url'     => hester_option( 'pre_footer_cta_btn_url' ),
	'new_tab' => hester_option( 'pre_footer_cta_btn_new_tab' ),
	'class'   => 'hester-btn btn-large',
);
$hester_cta_button_args = apply_filters( 'hester_pre_footer_cta_button', $hester_cta_button_args );

$hester_cta_button = '';

if ( $hester_cta_button_args['text'] || is_customize_preview() ) {
	$hester_cta_button = sprintf(
		'<a href="%1$s" class="%2$s" role="button" %3$s>%4$s</a>',
		esc_url( $hester_cta_button_args['url'] ),
		esc_attr( $hester_cta_button_args['class'] ),
		$hester_cta_button_args['new_tab'] ? 'target="_blank" rel="noopener noreferrer"' : 'target="_self"',
		esc_html( $hester_cta_button_args['text'] )
	);
}

// Classes.
$hester_cta_classes    = array( 'hester-container', 'hester-pre-footer-cta' );
$hester_cta_visibility = hester_option( 'pre_footer_cta_visibility' );

if ( 'all' !== $hester_cta_visibility ) {
	$hester_cta_classes[] = 'hester-' . $hester_cta_visibility;
}

$hester_cta_classes = apply_filters( 'hester_pre_footer_cta_classes', $hester_cta_classes );
$hester_cta_classes = trim( implode( ' ', $hester_cta_classes ) );

?>
<div class="<?php echo esc_attr( $hester_cta_classes ); ?>">
	<div class="hester-flex-row middle-md">

		<div class="col-xs-12 col-md-8 center-xs start-md">
			<p class="h3"><?php echo wp_kses_post( $hester_cta_text ); ?></p>
		</div>

		<div class="col-xs-12 col-md-4 center-xs end-md">
			<?php echo $hester_cta_button; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>

	</div>
</div>
