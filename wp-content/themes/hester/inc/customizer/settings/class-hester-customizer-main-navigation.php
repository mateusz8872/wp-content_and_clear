<?php
/**
 * Hester Main Navigation Settings section in Customizer.
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

if ( ! class_exists( 'Hester_Customizer_Main_Navigation' ) ) :
	/**
	 * Hester Main Navigation Settings section in Customizer.
	 */
	class Hester_Customizer_Main_Navigation {

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

			// Main Navigation Section.
			$options['section']['hester_section_main_navigation'] = array(
				'title'    => esc_html__( 'Main Navigation', 'hester' ),
				'panel'    => 'hester_panel_header',
				'priority' => 30,
			);

			// Navigation animation heading.
			$options['setting']['hester_main_nav_heading_animation'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Animation', 'hester' ),
					'section' => 'hester_section_main_navigation',
				),
			);

			// Hover animation.
			$options['setting']['hester_main_nav_hover_animation'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'label'       => esc_html__( 'Hover Animation', 'hester' ),
					'description' => esc_html__( 'Choose menu item hover animation style.', 'hester' ),
					'section'     => 'hester_section_main_navigation',
					'choices'     => array(
						'none'         => esc_html__( 'None', 'hester' ),
						'underline'    => esc_html__( 'Underline', 'hester' ),
						'squarebox'    => esc_html__( 'Square Box', 'hester' ),
						'squareborder' => esc_html__( 'Square Border', 'hester' ),
						'focusmenu'    => esc_html__( 'Focus Menu', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_main_nav_heading_animation',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Sub Menus heading.
			$options['setting']['hester_main_nav_heading_sub_menus'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Sub Menus', 'hester' ),
					'section' => 'hester_section_main_navigation',
				),
			);

			// Sub-Menu Indicators.
			$options['setting']['hester_main_nav_sub_indicators'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'        => 'hester-toggle',
					'label'       => esc_html__( 'Dropdown Indicators', 'hester' ),
					'description' => esc_html__( 'Show indicators (arrow icons) on parent menu items that have sub menus.', 'hester' ),
					'section'     => 'hester_section_main_navigation',
					'required'    => array(
						array(
							'control'  => 'hester_main_nav_heading_sub_menus',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
				'partial'           => array(
					'selector'            => '.main-navigation',
					'render_callback'     => 'hester_main_navigation_template',
					'container_inclusive' => true,
					'fallback_refresh'    => true,
				),
			);

			// Mobile Menu heading.
			$options['setting']['hester_main_nav_heading_mobile_menu'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Mobile Menu', 'hester' ),
					'section' => 'hester_section_main_navigation',
				),
			);

			// Mobile Menu Breakpoint.
			$options['setting']['hester_main_nav_mobile_breakpoint'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_range',
				'control'           => array(
					'type'        => 'hester-range',
					'label'       => esc_html__( 'Mobile Breakpoint', 'hester' ),
					'description' => esc_html__( 'Choose the breakpoint (in px) when to show the mobile navigation.', 'hester' ),
					'section'     => 'hester_section_main_navigation',
					'min'         => 0,
					'max'         => 1920,
					'step'        => 1,
					'unit'        => 'px',
					'required'    => array(
						array(
							'control'  => 'hester_main_nav_heading_mobile_menu',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Mobile Menu Button Label.
			$options['setting']['hester_main_nav_mobile_label'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
				'control'           => array(
					'type'        => 'hester-text',
					'label'       => esc_html__( 'Mobile Menu Button Label', 'hester' ),
					'description' => esc_html__( 'This text will be displayed next to the mobile menu button.', 'hester' ),
					'section'     => 'hester_section_main_navigation',
					'placeholder' => esc_html__( 'Leave empty to hide the label...', 'hester' ),
					'required'    => array(
						array(
							'control'  => 'hester_main_nav_heading_mobile_menu',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Navigation design options heading.
			$options['setting']['hester_nav_design_options'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Design Options', 'hester' ),
					'section' => 'hester_section_main_navigation',
				),
			);

			// Navigation Background.
			$options['setting']['hester_main_nav_background'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'label'    => esc_html__( 'Background', 'hester' ),
					'section'  => 'hester_section_main_navigation',
					'display'  => array(
						'background' => array(
							'color'    => esc_html__( 'Solid Color', 'hester' ),
							'gradient' => esc_html__( 'Gradient', 'hester' ),
						),
					),
					'required' => array(
						array(
							'control'  => 'hester_nav_design_options',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_header_layout',
							'value'    => 'layout-3',
							'operator' => '==',
						),
					),
				),
			);

			// Navigation Font Color.
			$options['setting']['hester_main_nav_font_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'label'    => esc_html__( 'Font Color', 'hester' ),
					'section'  => 'hester_section_main_navigation',
					'display'  => array(
						'color' => array(
							'link-color'       => esc_html__( 'Link Color', 'hester' ),
							'link-hover-color' => esc_html__( 'Link Hover Color', 'hester' ),
						),
					),
					'required' => array(
						array(
							'control'  => 'hester_nav_design_options',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Navigation Border.
			$options['setting']['hester_main_nav_border'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'label'    => esc_html__( 'Border', 'hester' ),
					'section'  => 'hester_section_main_navigation',
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
							'control'  => 'hester_nav_design_options',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_header_layout',
							'value'    => 'layout-3',
							'operator' => '==',
						),
					),
				),
			);

			// Main navigation typography heading.
			$options['setting']['hester_typography_main_nav_heading'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Typography', 'hester' ),
					'section' => 'hester_section_main_navigation',
				),
			);

			// Main navigation font size.
			$options['setting']['hester_main_nav_font_size'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_responsive',
				'control'           => array(
					'type'        => 'hester-range',
					'label'       => esc_html__( 'Font Size', 'hester' ),
					'description' => esc_html__( 'Choose your main navigation font size.', 'hester' ),
					'section'     => 'hester_section_main_navigation',
					'unit'        => array(
						array(
							'id'   => 'px',
							'name' => 'px',
							'min'  => 8,
							'max'  => 25,
							'step' => 1,
						),
						array(
							'id'   => 'em',
							'name' => 'em',
							'min'  => 0.5,
							'max'  => 2,
							'step' => 0.01,
						),
						array(
							'id'   => 'rem',
							'name' => 'rem',
							'min'  => 0.5,
							'max'  => 2,
							'step' => 0.01,
						),
					),
					'required'    => array(
						array(
							'control'  => 'hester_typography_main_nav_heading',
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
new Hester_Customizer_Main_Navigation();
