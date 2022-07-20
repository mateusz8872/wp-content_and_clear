<?php
/**
 * Hester Main Footer section in Customizer.
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.0.0
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Hester_Customizer_Main_Footer' ) ) :
	/**
	 * Hester Main Footer section in Customizer.
	 */
	class Hester_Customizer_Main_Footer {

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			/**
			 * Registers our custom options in Customizer.
			 */
			add_filter( 'hester_customizer_options', array( $this, 'register_options' ) );
		}

		/**
		 * Registers our custom options in Customizer.
		 *
		 * @since 1.0.0
		 * @param array $options Array of customizer options.
		 */
		public function register_options( $options ) {

			// Section.
			$options['section']['hester_section_main_footer'] = array(
				'title'    => esc_html__( 'Main Footer', 'hester' ),
				'panel'    => 'hester_panel_footer',
				'priority' => 20,
			);

			// Enable Footer.
			$options['setting']['hester_enable_footer'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-toggle',
					'label'   => esc_html__( 'Enable Main Footer', 'hester' ),
					'section' => 'hester_section_main_footer',
				),
			);

			// Footer Layout.
			$options['setting']['hester_footer_layout'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-radio-image',
					'label'       => esc_html__( 'Column Layout', 'hester' ),
					'description' => esc_html__( 'Choose your site&rsquo;s footer column layout.', 'hester' ),
					'section'     => 'hester_section_main_footer',
					'choices'     => array(
						'layout-1' => array(
							'image' => HESTER_THEME_URI . '/inc/customizer/assets/images/footer-layout-1.svg',
							'title' => esc_html__( '1/4 + 1/4 + 1/4 + 1/4', 'hester' ),
						),
						'layout-2' => array(
							'image' => HESTER_THEME_URI . '/inc/customizer/assets/images/footer-layout-2.svg',
							'title' => esc_html__( '1/3 + 1/3 + 1/3', 'hester' ),
						),
						'layout-3' => array(
							'image' => HESTER_THEME_URI . '/inc/customizer/assets/images/footer-layout-3.svg',
							'title' => esc_html__( '2/3 + 1/3', 'hester' ),
						),
						'layout-4' => array(
							'image' => HESTER_THEME_URI . '/inc/customizer/assets/images/footer-layout-4.svg',
							'title' => esc_html__( '1/3 + 2/3', 'hester' ),
						),
						'layout-5' => array(
							'image' => HESTER_THEME_URI . '/inc/customizer/assets/images/footer-layout-5.svg',
							'title' => esc_html__( '2/3 + 1/4 + 1/4', 'hester' ),
						),
						'layout-6' => array(
							'image' => HESTER_THEME_URI . '/inc/customizer/assets/images/footer-layout-6.svg',
							'title' => esc_html__( '1/4 + 1/4 + 2/3', 'hester' ),
						),
						'layout-7' => array(
							'image' => HESTER_THEME_URI . '/inc/customizer/assets/images/footer-layout-7.svg',
							'title' => esc_html__( '1/2 + 1/2', 'hester' ),
						),
						'layout-8' => array(
							'image' => HESTER_THEME_URI . '/inc/customizer/assets/images/footer-layout-8.svg',
							'title' => esc_html__( '1', 'hester' ),
						),
					),
					'required'    => array(
						array(
							'control'  => 'hester_enable_footer',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
				'partial'           => array(
					'selector'            => '#hester-footer-widgets',
					'render_callback'     => 'hester_footer_widgets',
					'container_inclusive' => false,
					'fallback_refresh'    => true,
				),
			);

			// Center footer widgets..
			$options['setting']['hester_footer_widgets_align_center'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-toggle',
					'label'    => esc_html__( 'Center Widget Content', 'hester' ),
					'section'  => 'hester_section_main_footer',
					'required' => array(
						array(
							'control'  => 'hester_enable_footer',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
				'partial'           => array(
					'selector'            => '#hester-footer-widgets',
					'render_callback'     => 'hester_footer_widgets',
					'container_inclusive' => false,
					'fallback_refresh'    => true,
				),
			);

			// Main Footer visibility.
			$options['setting']['hester_footer_visibility'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'label'       => esc_html__( 'Device Visibility', 'hester' ),
					'description' => esc_html__( 'Devices where Main Footer is displayed.', 'hester' ),
					'section'     => 'hester_section_main_footer',
					'choices'     => array(
						'all'                => esc_html__( 'Show on All Devices', 'hester' ),
						'hide-mobile'        => esc_html__( 'Hide on Mobile', 'hester' ),
						'hide-tablet'        => esc_html__( 'Hide on Tablet', 'hester' ),
						'hide-mobile-tablet' => esc_html__( 'Hide on Mobile and Tablet', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_enable_footer',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Footer Widget Heading Style.
			$options['setting']['hester_footer_widget_heading_style'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'label'       => esc_html__( 'Footer Widget Heading Style', 'hester' ),
					'description' => esc_html__( 'Adds footer widget heading styles, so you can create stylish footer widgets.', 'hester' ),
					'section'     => 'hester_section_main_footer',
					'choices'     => array(
						'0' => esc_html__( 'Heading Default', 'hester' ),
						'1' => esc_html__( 'Heading Style-1', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_enable_footer',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Footer Design Options heading.
			$options['setting']['hester_footer_heading_design_options'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-heading',
					'label'    => esc_html__( 'Design Options', 'hester' ),
					'section'  => 'hester_section_main_footer',
					'required' => array(
						array(
							'control'  => 'hester_enable_footer',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Footer Background.
			$options['setting']['hester_footer_background'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'label'    => esc_html__( 'Background', 'hester' ),
					'section'  => 'hester_section_main_footer',
					'display'  => array(
						'background' => array(
							'color'    => esc_html__( 'Solid Color', 'hester' ),
							'gradient' => esc_html__( 'Gradient', 'hester' ),
							'image'    => esc_html__( 'Image', 'hester' ),
						),
					),
					'required' => array(
						array(
							'control'  => 'hester_enable_footer',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_footer_heading_design_options',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Footer Text Color.
			$options['setting']['hester_footer_text_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'label'    => esc_html__( 'Font Color', 'hester' ),
					'section'  => 'hester_section_main_footer',
					'display'  => array(
						'color' => array(
							'text-color'         => esc_html__( 'Text Color', 'hester' ),
							'link-color'         => esc_html__( 'Link Color', 'hester' ),
							'link-hover-color'   => esc_html__( 'Link Hover Color', 'hester' ),
							'widget-title-color' => esc_html__( 'Widget Title Color', 'hester' ),
						),
					),
					'required' => array(
						array(
							'control'  => 'hester_enable_footer',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_footer_heading_design_options',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Footer Border.
			$options['setting']['hester_footer_border'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'label'    => esc_html__( 'Border', 'hester' ),
					'section'  => 'hester_section_main_footer',
					'display'  => array(
						'border' => array(
							'style'     => esc_html__( 'Style', 'hester' ),
							'color'     => esc_html__( 'Color', 'hester' ),
							'width'     => esc_html__( 'Width (px)', 'hester' ),
							'positions' => array(
								'top'    => esc_html__( 'Top', 'hester' ),
								'bottom' => esc_html__( 'Bottom', 'hester' ),
							),
						),
					),
					'required' => array(
						array(
							'control'  => 'hester_enable_footer',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_footer_heading_design_options',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Footer typography heading.
			$options['setting']['hester_typography_main_footer_heading'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-heading',
					'label'    => esc_html__( 'Typography', 'hester' ),
					'section'  => 'hester_section_main_footer',
					'required' => array(
						array(
							'control'  => 'hester_enable_footer',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Footer widget title font size.
			$options['setting']['hester_footer_widget_title_font_size'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_responsive',
				'control'           => array(
					'type'        => 'hester-range',
					'label'       => esc_html__( 'Widget Title Font Size', 'hester' ),
					'description' => esc_html__( 'Choose your widget title font size.', 'hester' ),
					'section'     => 'hester_section_main_footer',
					'responsive'  => true,
					'unit'        => array(
						array(
							'id'   => 'px',
							'name' => 'px',
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
						),
						array(
							'id'   => 'em',
							'name' => 'em',
							'min'  => 0.5,
							'max'  => 5,
							'step' => 0.01,
						),
						array(
							'id'   => 'rem',
							'name' => 'rem',
							'min'  => 0.5,
							'max'  => 5,
							'step' => 0.01,
						),
					),
					'required'    => array(
						array(
							'control'  => 'hester_typography_main_footer_heading',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_enable_footer',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			return $options;
		}

	}
endif;
new Hester_Customizer_Main_Footer();
