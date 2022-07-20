<?php
/**
 * Hester Top Bar Settings section in Customizer.
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

if ( ! class_exists( 'Hester_Customizer_Top_Bar' ) ) :
	/**
	 * Hester Top Bar Settings section in Customizer.
	 */
	class Hester_Customizer_Top_Bar {

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
			$options['section']['hester_section_top_bar'] = array(
				'title'    => esc_html__( 'Top Bar', 'hester' ),
				'panel'    => 'hester_panel_header',
				'priority' => 10,
			);

			// Enable Top Bar.
			$options['setting']['hester_top_bar_enable'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'        => 'hester-toggle',
					'label'       => esc_html__( 'Enable Top Bar', 'hester' ),
					'description' => esc_html__( 'Top Bar is a section with widgets located above Main Header area.', 'hester' ),
					'section'     => 'hester_section_top_bar',
				),
			);

			// Top Bar container width.
			$options['setting']['hester_top_bar_container_width'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'label'       => esc_html__( 'Top Bar Width', 'hester' ),
					'description' => esc_html__( 'Stretch the Top Bar container to full width, or match your site&rsquo;s content width.', 'hester' ),
					'section'     => 'hester_section_top_bar',
					'choices'     => array(
						'content-width' => esc_html__( 'Content Width', 'hester' ),
						'full-width'    => esc_html__( 'Full Width', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_top_bar_enable',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Top Bar visibility.
			$options['setting']['hester_top_bar_visibility'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'label'       => esc_html__( 'Device Visibility', 'hester' ),
					'description' => esc_html__( 'Devices where the Top Bar is displayed.', 'hester' ),
					'section'     => 'hester_section_top_bar',
					'choices'     => array(
						'all'                => esc_html__( 'Show on All Devices', 'hester' ),
						'hide-mobile'        => esc_html__( 'Hide on Mobile', 'hester' ),
						'hide-tablet'        => esc_html__( 'Hide on Tablet', 'hester' ),
						'hide-mobile-tablet' => esc_html__( 'Hide on Mobile and Tablet', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_top_bar_enable',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Top Bar widgets heading.
			$options['setting']['hester_top_bar_heading_widgets'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'        => 'hester-heading',
					'label'       => esc_html__( 'Top Bar Widgets', 'hester' ),
					'description' => esc_html__( 'Click the Add Widget button to add available widgets to your Top Bar.', 'hester' ),
					'section'     => 'hester_section_top_bar',
					'required'    => array(
						array(
							'control'  => 'hester_top_bar_enable',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Top Bar widgets.
			$options['setting']['hester_top_bar_widgets'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_widget',
				'control'           => array(
					'type'       => 'hester-widget',
					'label'      => esc_html__( 'Top Bar Widgets', 'hester' ),
					'section'    => 'hester_section_top_bar',
					'widgets'    => array(
						'text'    => array(
							'max_uses' => 3,
						),
						'nav'     => array(
							'max_uses' => 1,
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
							'control'  => 'hester_top_bar_heading_widgets',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_top_bar_enable',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
				'partial'           => array(
					'selector'            => '#hester-topbar',
					'render_callback'     => 'hester_topbar_output',
					'container_inclusive' => true,
					'fallback_refresh'    => true,
				),
			);

			// Top Bar widget separator.
			$options['setting']['hester_top_bar_widgets_separator'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'label'       => esc_html__( 'Widgets Separator', 'hester' ),
					'description' => esc_html__( 'Display a separator line between widgets.', 'hester' ),
					'section'     => 'hester_section_top_bar',
					'choices'     => array(
						'none'    => esc_html__( 'None', 'hester' ),
						'regular' => esc_html__( 'Regular', 'hester' ),
						'slanted' => esc_html__( 'Slanted', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_top_bar_heading_widgets',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_top_bar_enable',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Top Bar design options heading.
			$options['setting']['hester_top_bar_heading_design_options'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-heading',
					'label'    => esc_html__( 'Design Options', 'hester' ),
					'section'  => 'hester_section_top_bar',
					'required' => array(
						array(
							'control'  => 'hester_top_bar_enable',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Top Bar Background.
			$options['setting']['hester_top_bar_background'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'label'    => esc_html__( 'Background', 'hester' ),
					'section'  => 'hester_section_top_bar',
					'display'  => array(
						'background' => array(
							'color'    => esc_html__( 'Solid Color', 'hester' ),
							'gradient' => esc_html__( 'Gradient', 'hester' ),
						),
					),
					'required' => array(
						array(
							'control'  => 'hester_top_bar_enable',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_top_bar_heading_design_options',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Top Bar Text Color.
			$options['setting']['hester_top_bar_text_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'label'    => esc_html__( 'Font Color', 'hester' ),
					'section'  => 'hester_section_top_bar',
					'display'  => array(
						'color' => array(
							'text-color'       => esc_html__( 'Text Color', 'hester' ),
							'link-color'       => esc_html__( 'Link Color', 'hester' ),
							'link-hover-color' => esc_html__( 'Link Hover Color', 'hester' ),
						),
					),
					'required' => array(
						array(
							'control'  => 'hester_top_bar_enable',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_top_bar_heading_design_options',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Top Bar Border.
			$options['setting']['hester_top_bar_border'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'label'    => esc_html__( 'Border', 'hester' ),
					'section'  => 'hester_section_top_bar',
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
							'control'  => 'hester_top_bar_enable',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_top_bar_heading_design_options',
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
new Hester_Customizer_Top_Bar();
