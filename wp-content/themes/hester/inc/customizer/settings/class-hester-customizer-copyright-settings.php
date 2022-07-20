<?php
/**
 * Hester Copyright Bar section in Customizer.
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

if ( ! class_exists( 'Hester_Customizer_Copyright_Settings' ) ) :
	/**
	 * Hester Copyright Bar section in Customizer.
	 */
	class Hester_Customizer_Copyright_Settings {

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// Registers our custom options in Customizer.
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
			$options['section']['hester_section_copyright_bar'] = array(
				'title'    => esc_html__( 'Copyright Bar', 'hester' ),
				'priority' => 30,
				'panel'    => 'hester_panel_footer',
			);

			// Enable Copyright Bar.
			$options['setting']['hester_enable_copyright'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-toggle',
					'label'   => esc_html__( 'Enable Copyright Bar', 'hester' ),
					'section' => 'hester_section_copyright_bar',
				),
			);

			// Copyright Layout.
			$options['setting']['hester_copyright_layout'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-radio-image',
					'section'     => 'hester_section_copyright_bar',
					'label'       => esc_html__( 'Copyright Layout', 'hester' ),
					'description' => esc_html__( 'Choose your site&rsquo;s copyright widgets layout.', 'hester' ),
					'choices'     => array(
						'layout-1' => array(
							'image' => HESTER_THEME_URI . '/inc/customizer/assets/images/copyright-layout-1.svg',
							'title' => esc_html__( 'Centered', 'hester' ),
						),
						'layout-2' => array(
							'image' => HESTER_THEME_URI . '/inc/customizer/assets/images/copyright-layout-2.svg',
							'title' => esc_html__( 'Inline', 'hester' ),
						),
					),
					'required'    => array(
						array(
							'control'  => 'hester_enable_copyright',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Enable Copyright Bar.
			$options['setting']['hester_copyright_separator'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'section'     => 'hester_section_copyright_bar',
					'label'       => esc_html__( 'Copyright Separator', 'hester' ),
					'description' => esc_html__( 'Select type of Copyright Separator.', 'hester' ),
					'choices'     => array(
						'none'                => esc_html__( 'None', 'hester' ),
						'contained-separator' => esc_html__( 'Contained Separator', 'hester' ),
						'fw-separator'        => esc_html__( 'Fullwidth Separator', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_enable_copyright',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Copyright visibility.
			$options['setting']['hester_copyright_visibility'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'section'     => 'hester_section_copyright_bar',
					'label'       => esc_html__( 'Device Visibility', 'hester' ),
					'description' => esc_html__( 'Devices where Copyright Bar is displayed.', 'hester' ),
					'choices'     => array(
						'all'                => esc_html__( 'Show on All Devices', 'hester' ),
						'hide-mobile'        => esc_html__( 'Hide on Mobile', 'hester' ),
						'hide-tablet'        => esc_html__( 'Hide on Tablet', 'hester' ),
						'hide-mobile-tablet' => esc_html__( 'Hide on Mobile and Tablet', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_enable_copyright',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Copyright widgets heading.
			$options['setting']['hester_copyright_heading_widgets'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'        => 'hester-heading',
					'section'     => 'hester_section_copyright_bar',
					'label'       => esc_html__( 'Copyright Bar Widgets', 'hester' ),
					'description' => esc_html__( 'Click the Add Widget button to add available widgets to your Copyright Bar.', 'hester' ),
					'required'    => array(
						array(
							'control'  => 'hester_enable_copyright',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Copyright widgets.
			$options['setting']['hester_copyright_widgets'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_widget',
				'control'           => array(
					'type'       => 'hester-widget',
					'section'    => 'hester_section_copyright_bar',
					'label'      => esc_html__( 'Copyright Bar Widgets', 'hester' ),
					'widgets'    => array(
						'text'    => array(
							'max_uses' => 3,
						),
						'nav'     => array(
							'menu_location' => apply_filters( 'hester_footer_menu_location', 'hester-footer' ),
							'max_uses'      => 1,
						),
						'socials' => array(
							'max_uses' => 1,
							'styles'   => array(
								'minimal' => esc_html__( 'Minimal', 'hester' ),
								'rounded' => esc_html__( 'Rounded', 'hester' ),
							),
						),
					),
					'locations'  => array(
						'start' => esc_html__( 'Start', 'hester' ),
						'end'   => esc_html__( 'End', 'hester' ),
					),
					'visibility' => array(
						'all'                => esc_html__( 'Show on All Devices', 'hester' ),
						'hide-mobile'        => esc_html__( 'Hide on Mobile', 'hester' ),
						'hide-tablet'        => esc_html__( 'Hide on Tablet', 'hester' ),
						'hide-mobile-tablet' => esc_html__( 'Hide on Mobile and Tablet', 'hester' ),
					),
					'required'   => array(
						array(
							'control'  => 'hester_copyright_heading_widgets',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_enable_copyright',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
				'partial'           => array(
					'selector'            => '#hester-copyright',
					'render_callback'     => 'hester_copyright_bar_output',
					'container_inclusive' => true,
					'fallback_refresh'    => true,
				),
			);

			// Copyright design options heading.
			$options['setting']['hester_copyright_heading_design_options'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-heading',
					'section'  => 'hester_section_copyright_bar',
					'label'    => esc_html__( 'Design Options', 'hester' ),
					'required' => array(
						array(
							'control'  => 'hester_enable_copyright',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Copyright Background.
			$options['setting']['hester_copyright_background'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'section'  => 'hester_section_copyright_bar',
					'label'    => esc_html__( 'Background', 'hester' ),
					'space'    => true,
					'display'  => array(
						'background' => array(
							'color'    => esc_html__( 'Solid Color', 'hester' ),
							'gradient' => esc_html__( 'Gradient', 'hester' ),
						),
					),
					'required' => array(
						array(
							'control'  => 'hester_copyright_heading_design_options',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_enable_copyright',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Copyright Text Color.
			$options['setting']['hester_copyright_text_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'        => 'hester-design-options',
					'section'     => 'hester_section_copyright_bar',
					'label'       => esc_html__( 'Font Color', 'hester' ),
					'description' => '',
					'space'       => true,
					'display'     => array(
						'color' => array(
							'text-color'       => esc_html__( 'Text Color', 'hester' ),
							'link-color'       => esc_html__( 'Link Color', 'hester' ),
							'link-hover-color' => esc_html__( 'Link Hover Color', 'hester' ),
						),
					),
					'required'    => array(
						array(
							'control'  => 'hester_copyright_heading_design_options',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_enable_copyright',
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
new Hester_Customizer_Copyright_Settings();
