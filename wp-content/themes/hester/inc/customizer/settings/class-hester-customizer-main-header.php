<?php
/**
 * Hester Main Header Settings section in Customizer.
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

if ( ! class_exists( 'Hester_Customizer_Main_Header' ) ) :
	/**
	 * Hester Main Header section in Customizer.
	 */
	class Hester_Customizer_Main_Header {

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

			// Main Header Section.
			$options['section']['hester_section_main_header'] = array(
				'title'    => esc_html__( 'Main Header', 'hester' ),
				'panel'    => 'hester_panel_header',
				'priority' => 20,
			);

			// Header Layout.
			$options['setting']['hester_header_layout'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-radio-image',
					'label'       => esc_html__( 'Header Layout', 'hester' ),
					'description' => esc_html__( 'Pre-defined positions of header elements, such as logo and navigation.', 'hester' ),
					'section'     => 'hester_section_main_header',
					'choices'     => array(
						'layout-1' => array(
							'image' => HESTER_THEME_URI . '/inc/customizer/assets/images/header-layout-1.svg',
							'title' => esc_html__( 'Header 1', 'hester' ),
						),
						'layout-2' => array(
							'image' => HESTER_THEME_URI . '/inc/customizer/assets/images/header-layout-2.svg',
							'title' => esc_html__( 'Header 2', 'hester' ),
						),
						'layout-3' => array(
							'image' => HESTER_THEME_URI . '/inc/customizer/assets/images/header-layout-3.svg',
							'title' => esc_html__( 'Header 3', 'hester' ),
						),
					),
				),
			);

			// Header container width.
			$options['setting']['hester_header_container_width'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'label'       => esc_html__( 'Header Width', 'hester' ),
					'description' => esc_html__( 'Stretch the Header container to full width, or match your site&rsquo;s content width.', 'hester' ),
					'section'     => 'hester_section_main_header',
					'choices'     => array(
						'content-width' => esc_html__( 'Content Width', 'hester' ),
						'full-width'    => esc_html__( 'Full Width', 'hester' ),
					),
				),
			);

			// Header widgets heading.
			$options['setting']['hester_header_heading_widgets'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'        => 'hester-heading',
					'label'       => esc_html__( 'Header Widgets', 'hester' ),
					'description' => esc_html__( 'Click the Add Widget button to add available widgets to your Header. Click the down arrow icon to expand widget options.', 'hester' ),
					'section'     => 'hester_section_main_header',
					'space'       => true,
				),
			);

			// Header widgets.
			$options['setting']['hester_header_widgets'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_widget',
				'control'           => array(
					'type'       => 'hester-widget',
					'label'      => esc_html__( 'Header Widgets', 'hester' ),
					'section'    => 'hester_section_main_header',
					'widgets'    => apply_filters(
						'hester_main_header_widgets',
						array(
							'search' => array(
								'max_uses' => 1,
							),
							'button' => array(
								'max_uses' => 1,
							),
						)
					),
					'locations'  => array(
						'left'  => esc_html__( 'Left', 'hester' ),
						'right' => esc_html__( 'Right', 'hester' ),
					),
					'visibility' => array(
						'all'                => esc_html__( 'Show on All Devices', 'hester' ),
						'hide-mobile'        => esc_html__( 'Hide on Mobile', 'hester' ),
						'hide-tablet'        => esc_html__( 'Hide on Tablet', 'hester' ),
						'hide-mobile-tablet' => esc_html__( 'Hide on Mobile and Tablet', 'hester' ),
					),
					'required'   => array(
						array(
							'control'  => 'hester_header_heading_widgets',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
				'partial'           => array(
					'selector'            => '#hester-header',
					'render_callback'     => 'hester_header_content_output',
					'container_inclusive' => false,
					'fallback_refresh'    => true,
				),
			);

			// Header widget separator.
			$options['setting']['hester_header_widgets_separator'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'label'       => esc_html__( 'Widgets Separator', 'hester' ),
					'description' => esc_html__( 'Display a separator line between widgets.', 'hester' ),
					'section'     => 'hester_section_main_header',
					'choices'     => array(
						'none'    => esc_html__( 'None', 'hester' ),
						'regular' => esc_html__( 'Regular', 'hester' ),
						'slanted' => esc_html__( 'Slanted', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_header_heading_widgets',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Header design options heading.
			$options['setting']['hester_header_heading_design_options'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Design Options', 'hester' ),
					'section' => 'hester_section_main_header',
					'space'   => true,
				),
			);

			// Header Background.
			$options['setting']['hester_header_background'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'        => 'hester-design-options',
					'label'       => esc_html__( 'Background', 'hester' ),
					'description' => '',
					'section'     => 'hester_section_main_header',
					'space'       => true,
					'display'     => array(
						'background' => array(
							'color'    => esc_html__( 'Solid Color', 'hester' ),
							'gradient' => esc_html__( 'Gradient', 'hester' ),
							'image'    => esc_html__( 'Image', 'hester' ),
						),
					),
					'required'    => array(
						array(
							'control'  => 'hester_header_heading_design_options',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Header Text Color.
			$options['setting']['hester_header_text_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'label'    => esc_html__( 'Font Color', 'hester' ),
					'section'  => 'hester_section_main_header',
					'space'    => true,
					'display'  => array(
						'color' => array(
							'text-color'       => esc_html__( 'Tagline Color', 'hester' ),
							'link-color'       => esc_html__( 'Link Color', 'hester' ),
							'link-hover-color' => esc_html__( 'Link Hover Color', 'hester' ),
						),
					),
					'required' => array(
						array(
							'control'  => 'hester_header_heading_design_options',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Header Border.
			$options['setting']['hester_header_border'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'label'    => esc_html__( 'Border', 'hester' ),
					'section'  => 'hester_section_main_header',
					'space'    => true,
					'display'  => array(
						'border' => array(
							'style'     => esc_html__( 'Style', 'hester' ),
							'color'     => esc_html__( 'Color', 'hester' ),
							'width'     => esc_html__( 'Width (px)', 'hester' ),
							'positions' => array(
								'top'    => esc_html__( 'Top', 'hester' ),
								'bottom' => esc_html__( 'Bottom', 'hester' ),
							),
							'separator' => esc_html__( 'Separator Color', 'hester' ),
						),
					),
					'required' => array(
						array(
							'control'  => 'hester_header_heading_design_options',
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
new Hester_Customizer_Main_Header();
