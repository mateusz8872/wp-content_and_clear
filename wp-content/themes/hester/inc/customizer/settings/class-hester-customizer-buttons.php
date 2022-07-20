<?php

/**
 * Buttons section in Customizer » General Settings.
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

if ( ! class_exists( 'Hester_Customizer_Buttons' ) ) :
	/**
	 * Buttons section in Customizer » General Settings.
	 */
	class Hester_Customizer_Buttons {

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

			// Upsell section
			$options['section']['hester_section_upsell_button'] = array(
				'class'    => 'Hester_Customizer_Control_Section_Pro',
				'title'    => esc_html__( 'Need more features?', 'hester' ),
				'pro_url'  => 'https://peregrine-themes.com/',
				'pro_text' => esc_html__( 'Upgrade to pro', 'hester' ),
				'priority' => 60,
			);

			$options['setting']['hester_section_upsell_heading'] = array(
				'control' => array(
					'type'    => 'hidden',
					'section' => 'hester_section_upsell_button',
				),
			);

			// Section.
			$options['section']['hester_section_buttons'] = array(
				'title'    => esc_html__( 'Buttons', 'hester' ),
				'panel'    => 'hester_panel_general',
				'priority' => 60,
			);

			/**
			 * Primary Button
			 */

			$options['setting']['hester_primary_button_heading'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Primary Button', 'hester' ),
					'section' => 'hester_section_buttons',
				),
			);

			// Primary button background color.
			$options['setting']['hester_primary_button_bg_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_color',
				'control'           => array(
					'type'        => 'hester-color',
					'label'       => esc_html__( 'Background Color', 'hester' ),
					'description' => esc_html__( 'Set primary button background color. If left empty, accent color will be used instead.', 'hester' ),
					'section'     => 'hester_section_buttons',
					'opacity'     => true,
					'required'    => array(
						array(
							'control'  => 'hester_primary_button_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Primary button hover background color.
			$options['setting']['hester_primary_button_hover_bg_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_color',
				'control'           => array(
					'type'        => 'hester-color',
					'label'       => esc_html__( 'Hover Background Color', 'hester' ),
					'description' => esc_html__( 'Set primary button hover background color. If left empty, lightened accent color will be used instead.', 'hester' ),
					'section'     => 'hester_section_buttons',
					'opacity'     => true,
					'required'    => array(
						array(
							'control'  => 'hester_primary_button_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Primary button text color.
			$options['setting']['hester_primary_button_text_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_color',
				'control'           => array(
					'type'     => 'hester-color',
					'label'    => esc_html__( 'Text Color', 'hester' ),
					'section'  => 'hester_section_buttons',
					'opacity'  => true,
					'required' => array(
						array(
							'control'  => 'hester_primary_button_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Primary button text hover color.
			$options['setting']['hester_primary_button_hover_text_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_color',
				'control'           => array(
					'type'     => 'hester-color',
					'label'    => esc_html__( 'Hover Text Color', 'hester' ),
					'section'  => 'hester_section_buttons',
					'opacity'  => true,
					'required' => array(
						array(
							'control'  => 'hester_primary_button_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Primary button border width.
			$options['setting']['hester_primary_button_border_width'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_range',
				'control'           => array(
					'type'     => 'hester-range',
					'section'  => 'hester_section_buttons',
					'label'    => esc_html__( 'Border Width', 'hester' ),
					'min'      => 0,
					'max'      => 15,
					'step'     => .1,
					'unit'     => 'rem',
					'required' => array(
						array(
							'control'  => 'hester_primary_button_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Primary button border radius.
			$options['setting']['hester_primary_button_border_radius'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_responsive',
				'control'           => array(
					'type'        => 'hester-spacing',
					'label'       => esc_html__( 'Border Radius', 'hester' ),
					'description' => esc_html__( 'Specify primary button corner roundness. Top left, top right, bottom left and bottom right is the order of the corresponding corners.', 'hester' ),
					'section'     => 'hester_section_buttons',
					'choices'     => array(
						'top-left'     => '&nwarr;',
						'top-right'    => '&nearr;',
						'bottom-right' => '&searr;',
						'bottom-left'  => '&swarr;',
					),
					'unit'        => array(
						'rem',
					),
					'required'    => array(
						array(
							'control'  => 'hester_primary_button_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Primary button border color.
			$options['setting']['hester_primary_button_border_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_color',
				'control'           => array(
					'type'     => 'hester-color',
					'label'    => esc_html__( 'Border Color', 'hester' ),
					'section'  => 'hester_section_buttons',
					'opacity'  => true,
					'required' => array(
						array(
							'control'  => 'hester_primary_button_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Primary button hover border color.
			$options['setting']['hester_primary_button_hover_border_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_color',
				'control'           => array(
					'type'     => 'hester-color',
					'label'    => esc_html__( 'Hover Border Color', 'hester' ),
					'section'  => 'hester_section_buttons',
					'opacity'  => true,
					'required' => array(
						array(
							'control'  => 'hester_primary_button_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Primary button typography.
			$options['setting']['hester_primary_button_typography'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_typography',
				'control'           => array(
					'type'     => 'hester-typography',
					'label'    => esc_html__( 'Typography', 'hester' ),
					'section'  => 'hester_section_buttons',
					'display'  => array(
						'font-family'    => array(),
						'font-subsets'   => array(),
						'font-weight'    => array(),
						'text-transform' => array(),
						'letter-spacing' => array(),
						'font-size'      => array(),
						'line-height'    => array(),
					),
					'required' => array(
						array(
							'control'  => 'hester_primary_button_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			/**
			 * Secondary Button
			 */

			// Secondary button.
			$options['setting']['hester_secondary_button_heading'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Secondary Button', 'hester' ),
					'section' => 'hester_section_buttons',
				),
			);

			// Secondary button background color.
			$options['setting']['hester_secondary_button_bg_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_color',
				'control'           => array(
					'type'     => 'hester-color',
					'label'    => esc_html__( 'Background Color', 'hester' ),
					'section'  => 'hester_section_buttons',
					'opacity'  => true,
					'required' => array(
						array(
							'control'  => 'hester_secondary_button_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Secondary button hover background color.
			$options['setting']['hester_secondary_button_hover_bg_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_color',
				'control'           => array(
					'type'     => 'hester-color',
					'label'    => esc_html__( 'Hover Background Color', 'hester' ),
					'section'  => 'hester_section_buttons',
					'opacity'  => true,
					'required' => array(
						array(
							'control'  => 'hester_secondary_button_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Secondary button text color.
			$options['setting']['hester_secondary_button_text_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_color',
				'control'           => array(
					'type'     => 'hester-color',
					'label'    => esc_html__( 'Text Color', 'hester' ),
					'section'  => 'hester_section_buttons',
					'opacity'  => true,
					'required' => array(
						array(
							'control'  => 'hester_secondary_button_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Secondary button text hover color.
			$options['setting']['hester_secondary_button_hover_text_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_color',
				'control'           => array(
					'type'     => 'hester-color',
					'label'    => esc_html__( 'Hover Text Color', 'hester' ),
					'section'  => 'hester_section_buttons',
					'opacity'  => true,
					'required' => array(
						array(
							'control'  => 'hester_secondary_button_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Secondary button border width.
			$options['setting']['hester_secondary_button_border_width'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_range',
				'control'           => array(
					'type'     => 'hester-range',
					'section'  => 'hester_section_buttons',
					'label'    => esc_html__( 'Border Width', 'hester' ),
					'min'      => 0,
					'max'      => 15,
					'step'     => .1,
					'unit'     => 'rem',
					'required' => array(
						array(
							'control'  => 'hester_secondary_button_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Secondary button border radius.
			$options['setting']['hester_secondary_button_border_radius'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_responsive',
				'control'           => array(
					'type'        => 'hester-spacing',
					'label'       => esc_html__( 'Border Radius', 'hester' ),
					'description' => esc_html__( 'Specify secondary button corner roundness. Top left, top right, bottom left and bottom right is the order of the corresponding corners.', 'hester' ),
					'section'     => 'hester_section_buttons',
					'choices'     => array(
						'top-left'     => '&nwarr;',
						'top-right'    => '&nearr;',
						'bottom-right' => '&searr;',
						'bottom-left'  => '&swarr;',
					),
					'unit'        => array(
						'rem',
					),
					'required'    => array(
						array(
							'control'  => 'hester_secondary_button_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Secondary button border color.
			$options['setting']['hester_secondary_button_border_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_color',
				'control'           => array(
					'type'     => 'hester-color',
					'label'    => esc_html__( 'Border Color', 'hester' ),
					'section'  => 'hester_section_buttons',
					'opacity'  => true,
					'required' => array(
						array(
							'control'  => 'hester_secondary_button_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Secondary button hover border color.
			$options['setting']['hester_secondary_button_hover_border_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_color',
				'control'           => array(
					'type'     => 'hester-color',
					'label'    => esc_html__( 'Hover Border Color', 'hester' ),
					'section'  => 'hester_section_buttons',
					'opacity'  => true,
					'required' => array(
						array(
							'control'  => 'hester_secondary_button_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Secondary button typography.
			$options['setting']['hester_secondary_button_typography'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_typography',
				'control'           => array(
					'type'     => 'hester-typography',
					'label'    => esc_html__( 'Typography', 'hester' ),
					'section'  => 'hester_section_buttons',
					'display'  => array(
						'font-family'    => array(),
						'font-subsets'   => array(),
						'font-weight'    => array(),
						'text-transform' => array(),
						'letter-spacing' => array(),
						'font-size'      => array(),
						'line-height'    => array(),
					),
					'required' => array(
						array(
							'control'  => 'hester_secondary_button_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			/**
			 * Text Button
			 */

			$options['setting']['hester_text_button_heading'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Text Button', 'hester' ),
					'section' => 'hester_section_buttons',
				),
			);

			// Text button text color.
			$options['setting']['hester_text_button_text_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_color',
				'control'           => array(
					'type'     => 'hester-color',
					'label'    => esc_html__( 'Text Color', 'hester' ),
					'section'  => 'hester_section_buttons',
					'opacity'  => true,
					'required' => array(
						array(
							'control'  => 'hester_text_button_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Text button text hover color.
			$options['setting']['hester_text_button_hover_text_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_color',
				'control'           => array(
					'type'        => 'hester-color',
					'label'       => esc_html__( 'Hover Text Color', 'hester' ),
					'description' => esc_html__( 'If left empty, accent color will be used instead.', 'hester' ),
					'section'     => 'hester_section_buttons',
					'opacity'     => true,
					'required'    => array(
						array(
							'control'  => 'hester_text_button_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Text button typography.
			$options['setting']['hester_text_button_typography'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_typography',
				'control'           => array(
					'type'     => 'hester-typography',
					'label'    => esc_html__( 'Typography', 'hester' ),
					'section'  => 'hester_section_buttons',
					'display'  => array(
						'font-family'    => array(),
						'font-subsets'   => array(),
						'font-weight'    => array(),
						'text-transform' => array(),
						'letter-spacing' => array(),
						'font-size'      => array(),
						'line-height'    => array(),
					),
					'required' => array(
						array(
							'control'  => 'hester_text_button_heading',
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
new Hester_Customizer_Buttons();
