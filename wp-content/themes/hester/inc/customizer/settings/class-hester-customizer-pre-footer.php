<?php
/**
 * Hester Pre Footer section in Customizer.
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

if ( ! class_exists( 'Hester_Customizer_Pre_Footer' ) ) :
	/**
	 * Hester Pre Footer section in Customizer.
	 */
	class Hester_Customizer_Pre_Footer {

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

			// Pre Footer.
			$options['section']['hester_section_pre_footer'] = array(
				'title'    => esc_html__( 'Pre Footer', 'hester' ),
				'panel'    => 'hester_panel_footer',
				'priority' => 10,
			);

			// Pre Footer - Call to Action.
			$options['setting']['hester_pre_footer_cta'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Call to Action', 'hester' ),
					'section' => 'hester_section_pre_footer',
				),
			);

			// Enable Pre Footer CTA.
			$options['setting']['hester_enable_pre_footer_cta'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-toggle',
					'label'    => esc_html__( 'Enable Call to Action', 'hester' ),
					'section'  => 'hester_section_pre_footer',
					'required' => array(
						array(
							'control'  => 'hester_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
				'partial'           => array(
					'selector'            => '#hester-pre-footer',
					'render_callback'     => 'hester_pre_footer',
					'container_inclusive' => true,
					'fallback_refresh'    => true,
				),
			);

			// Pre Footer visibility.
			$options['setting']['hester_pre_footer_cta_visibility'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'label'       => esc_html__( 'Device Visibility', 'hester' ),
					'description' => esc_html__( 'Devices where the Top Bar is displayed.', 'hester' ),
					'section'     => 'hester_section_pre_footer',
					'choices'     => array(
						'all'                => esc_html__( 'Show on All Devices', 'hester' ),
						'hide-mobile'        => esc_html__( 'Hide on Mobile', 'hester' ),
						'hide-tablet'        => esc_html__( 'Hide on Tablet', 'hester' ),
						'hide-mobile-tablet' => esc_html__( 'Hide on Mobile and Tablet', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_enable_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Pre Footer Hide on.
			$options['setting']['hester_pre_footer_cta_hide_on'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_no_sanitize',
				'control'           => array(
					'type'        => 'hester-checkbox-group',
					'label'       => esc_html__( 'Disable On: ', 'hester' ),
					'description' => esc_html__( 'Choose on which pages you want to disable Pre Footer Call to Action. ', 'hester' ),
					'section'     => 'hester_section_pre_footer',
					'choices'     => hester_get_display_choices(),
					'required'    => array(
						array(
							'control'  => 'hester_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_enable_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Pre Footer CTA Style.
			$options['setting']['hester_pre_footer_cta_style'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'label'       => esc_html__( 'Style', 'hester' ),
					'description' => esc_html__( 'Choose CTA Style.', 'hester' ),
					'section'     => 'hester_section_pre_footer',
					'choices'     => array(
						'1' => esc_html__( 'Contained', 'hester' ),
						'2' => esc_html__( 'Fullwidth', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_enable_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Pre Footer CTA Text.
			$options['setting']['hester_pre_footer_cta_text'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_textarea',
				'control'           => array(
					'type'        => 'hester-textarea',
					'label'       => esc_html__( 'Content', 'hester' ),
					'description' => esc_html__( 'Shortcodes and basic html elements allowed.', 'hester' ),
					'placeholder' => esc_html__( 'Call to Action Content', 'hester' ),
					'section'     => 'hester_section_pre_footer',
					'rows'        => '5',
					'required'    => array(
						array(
							'control'  => 'hester_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_enable_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Pre Footer CTA Button Text.
			$options['setting']['hester_pre_footer_cta_btn_text'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
				'control'           => array(
					'type'        => 'hester-text',
					'label'       => esc_html__( 'Button Text', 'hester' ),
					'description' => esc_html__( 'Label for the CTA button.', 'hester' ),
					'section'     => 'hester_section_pre_footer',
					'required'    => array(
						array(
							'control'  => 'hester_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_enable_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Pre Footer CTA Button URL.
			$options['setting']['hester_pre_footer_cta_btn_url'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
				'control'           => array(
					'type'        => 'hester-text',
					'label'       => esc_html__( 'Button Link', 'hester' ),
					'description' => esc_html__( 'Link for the CTA button.', 'hester' ),
					'placeholder' => 'http://',
					'section'     => 'hester_section_pre_footer',
					'required'    => array(
						array(
							'control'  => 'hester_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_enable_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Pre Footer CTA open in new tab.
			$options['setting']['hester_pre_footer_cta_btn_new_tab'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-toggle',
					'label'    => esc_html__( 'Open link in new tab?', 'hester' ),
					'section'  => 'hester_section_pre_footer',
					'required' => array(
						array(
							'control'  => 'hester_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_enable_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Pre Footer - Call to Action Design Options.
			$options['setting']['hester_pre_footer_cta_design_options'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-heading',
					'label'    => esc_html__( 'Design Options', 'hester' ),
					'section'  => 'hester_section_pre_footer',
					'required' => array(
						array(
							'control'  => 'hester_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_enable_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Pre Footer - Call to Action Background.
			$options['setting']['hester_pre_footer_cta_background'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'label'    => esc_html__( 'Background', 'hester' ),
					'section'  => 'hester_section_pre_footer',
					'display'  => array(
						'background' => array(
							'color'    => esc_html__( 'Solid Color', 'hester' ),
							'gradient' => esc_html__( 'Gradient', 'hester' ),
							'image'    => esc_html__( 'Image', 'hester' ),
						),
					),
					'required' => array(
						array(
							'control'  => 'hester_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_enable_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_pre_footer_cta_design_options',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Pre Footer - Call to Action Text Color.
			$options['setting']['hester_pre_footer_cta_text_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'label'    => esc_html__( 'Font Color', 'hester' ),
					'section'  => 'hester_section_pre_footer',
					'display'  => array(
						'color' => array(
							'text-color'       => esc_html__( 'Text Color', 'hester' ),
							'link-color'       => esc_html__( 'Link Color', 'hester' ),
							'link-hover-color' => esc_html__( 'Link Hover Color', 'hester' ),
						),
					),
					'required' => array(
						array(
							'control'  => 'hester_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_enable_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_pre_footer_cta_design_options',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Pre Footer - Call to Action Border.
			$options['setting']['hester_pre_footer_cta_border'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'label'    => esc_html__( 'Border', 'hester' ),
					'section'  => 'hester_section_pre_footer',
					'display'  => array(
						'border' => array(
							'style'     => esc_html__( 'Style', 'hester' ),
							'color'     => esc_html__( 'Color', 'hester' ),
							'width'     => esc_html__( 'Width (px)', 'hester' ),
							'positions' => array(
								'top'    => esc_html__( 'Top', 'hester' ),
								'right'  => esc_html__( 'Right', 'hester' ),
								'bottom' => esc_html__( 'Bottom', 'hester' ),
								'left'   => esc_html__( 'Left', 'hester' ),
							),
						),
					),
					'required' => array(
						array(
							'control'  => 'hester_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_enable_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_pre_footer_cta_design_options',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// CTA typography heading.
			$options['setting']['hester_pre_footer_cta_typography'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-heading',
					'label'    => esc_html__( 'Typography', 'hester' ),
					'section'  => 'hester_section_pre_footer',
					'required' => array(
						array(
							'control'  => 'hester_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_enable_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// CTA font size.
			$options['setting']['hester_pre_footer_cta_font_size'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_responsive',
				'control'           => array(
					'type'       => 'hester-range',
					'label'      => esc_html__( 'Font Size', 'hester' ),
					'section'    => 'hester_section_pre_footer',
					'min'        => 8,
					'max'        => 90,
					'step'       => 1,
					'responsive' => true,
					'unit'       => array(
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
					'required'   => array(
						array(
							'control'  => 'hester_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_enable_pre_footer_cta',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_pre_footer_cta_typography',
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
new Hester_Customizer_Pre_Footer();
