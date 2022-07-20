<?php
/**
 * Hester Breadcrumbs Settings section in Customizer.
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.1.0
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Hester_Customizer_Breadcrumbs' ) ) :
	/**
	 * Hester Breadcrumbs Settings section in Customizer.
	 */
	class Hester_Customizer_Breadcrumbs {

		/**
		 * Primary class constructor.
		 *
		 * @since 1.1.0
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
		 * @since 1.1.0
		 * @param array $options Array of customizer options.
		 */
		public function register_options( $options ) {

			// Main Navigation Section.
			$options['section']['hester_section_breadcrumbs'] = array(
				'title'    => esc_html__( 'Breadcrumbs', 'hester' ),
				'panel'    => 'hester_panel_header',
				'priority' => 70,
			);

			// Breadcrumbs.
			$options['setting']['hester_breadcrumbs_enable'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-toggle',
					'label'   => esc_html__( 'Enable Breadcrumbs', 'hester' ),
					'section' => 'hester_section_breadcrumbs',
				),
			);

			// Hide breadcrumbs on.
			$options['setting']['hester_breadcrumbs_hide_on'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_no_sanitize',
				'control'           => array(
					'type'        => 'hester-checkbox-group',
					'label'       => esc_html__( 'Disable On: ', 'hester' ),
					'description' => esc_html__( 'Choose on which pages you want to disable breadcrumbs. ', 'hester' ),
					'section'     => 'hester_section_breadcrumbs',
					'choices'     => hester_get_display_choices(),
					'required'    => array(
						array(
							'control'  => 'hester_breadcrumbs_enable',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Position.
			$options['setting']['hester_breadcrumbs_position'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'     => 'hester-select',
					'label'    => esc_html__( 'Position', 'hester' ),
					'section'  => 'hester_section_breadcrumbs',
					'choices'  => array(
						'in-page-header' => esc_html__( 'In Page Header', 'hester' ),
						'below-header'   => esc_html__( 'Below Header (Separate Container)', 'hester' ),
					),
					'required' => array(
						array(
							'control'  => 'hester_breadcrumbs_enable',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Alignment.
			$options['setting']['hester_breadcrumbs_alignment'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'     => 'hester-alignment',
					'label'    => esc_html__( 'Alignment', 'hester' ),
					'section'  => 'hester_section_breadcrumbs',
					'choices'  => 'horizontal',
					'icons'    => array(
						'left'   => 'dashicons dashicons-editor-alignleft',
						'center' => 'dashicons dashicons-editor-aligncenter',
						'right'  => 'dashicons dashicons-editor-alignright',
					),
					'required' => array(
						array(
							'control'  => 'hester_breadcrumbs_enable',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_breadcrumbs_position',
							'value'    => 'below-header',
							'operator' => '==',
						),
					),
				),
			);

			// Spacing.
			$options['setting']['hester_breadcrumbs_spacing'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_responsive',
				'control'           => array(
					'type'        => 'hester-spacing',
					'label'       => esc_html__( 'Spacing', 'hester' ),
					'description' => esc_html__( 'Specify top and bottom padding.', 'hester' ),
					'section'     => 'hester_section_breadcrumbs',
					'choices'     => array(
						'top'    => esc_html__( 'Top', 'hester' ),
						'bottom' => esc_html__( 'Bottom', 'hester' ),
					),
					'responsive'  => true,
					'unit'        => array(
						'px',
					),
					'required'    => array(
						array(
							'control'  => 'hester_breadcrumbs_enable',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Design options heading.
			$options['setting']['hester_breadcrumbs_heading_design'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-heading',
					'label'    => esc_html__( 'Design Options', 'hester' ),
					'section'  => 'hester_section_breadcrumbs',
					'required' => array(
						array(
							'control'  => 'hester_breadcrumbs_enable',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_breadcrumbs_position',
							'value'    => 'below-header',
							'operator' => '==',
						),
					),
				),
			);

			// Background design.
			$options['setting']['hester_breadcrumbs_background'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'label'    => esc_html__( 'Background', 'hester' ),
					'section'  => 'hester_section_breadcrumbs',
					'display'  => array(
						'background' => array(
							'color'    => esc_html__( 'Solid Color', 'hester' ),
							'gradient' => esc_html__( 'Gradient', 'hester' ),
							'image'    => esc_html__( 'Image', 'hester' ),
						),
					),
					'required' => array(
						array(
							'control'  => 'hester_breadcrumbs_enable',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_breadcrumbs_heading_design',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_breadcrumbs_position',
							'value'    => 'below-header',
							'operator' => '==',
						),
					),
				),
			);

			// Text Color.
			$options['setting']['hester_breadcrumbs_text_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'label'    => esc_html__( 'Font Color', 'hester' ),
					'section'  => 'hester_section_breadcrumbs',
					'display'  => array(
						'color' => array(
							'text-color'       => esc_html__( 'Text Color', 'hester' ),
							'link-color'       => esc_html__( 'Link Color', 'hester' ),
							'link-hover-color' => esc_html__( 'Link Hover Color', 'hester' ),
						),
					),
					'required' => array(
						array(
							'control'  => 'hester_breadcrumbs_enable',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_breadcrumbs_heading_design',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_breadcrumbs_position',
							'value'    => 'below-header',
							'operator' => '==',
						),
					),
				),
			);

			// Border.
			$options['setting']['hester_breadcrumbs_border'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'label'    => esc_html__( 'Border', 'hester' ),
					'section'  => 'hester_section_breadcrumbs',
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
							'control'  => 'hester_breadcrumbs_enable',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_breadcrumbs_heading_design',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_breadcrumbs_position',
							'value'    => 'below-header',
							'operator' => '==',
						),
					),
				),
			);

			return $options;
		}
	}
endif;
new Hester_Customizer_Breadcrumbs();
