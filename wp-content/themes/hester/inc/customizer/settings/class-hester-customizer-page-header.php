<?php
/**
 * Hester Page Title Settings section in Customizer.
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

if ( ! class_exists( 'Hester_Customizer_Page_Header' ) ) :
	/**
	 * Hester Page Title Settings section in Customizer.
	 */
	class Hester_Customizer_Page_Header {

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

			// Page Title Section.
			$options['section']['hester_section_page_header'] = array(
				'title'    => esc_html__( 'Page Header', 'hester' ),
				'panel'    => 'hester_panel_header',
				'priority' => 60,
			);

			// Page Header enable.
			$options['setting']['hester_page_header_enable'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-toggle',
					'label'   => esc_html__( 'Enable Page Header', 'hester' ),
					'section' => 'hester_section_page_header',
				),
			);

			// Alignment.
			$options['setting']['hester_page_header_alignment'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'     => 'hester-alignment',
					'label'    => esc_html__( 'Title Alignment', 'hester' ),
					'section'  => 'hester_section_page_header',
					'choices'  => 'horizontal',
					'icons'    => array(
						'left'   => 'dashicons dashicons-editor-alignleft',
						'center' => 'dashicons dashicons-editor-aligncenter',
						'right'  => 'dashicons dashicons-editor-alignright',
					),
					'required' => array(
						array(
							'control'  => 'hester_page_header_enable',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Spacing.
			$options['setting']['hester_page_header_spacing'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_responsive',
				'control'           => array(
					'type'        => 'hester-spacing',
					'label'       => esc_html__( 'Page Title Spacing', 'hester' ),
					'description' => esc_html__( 'Specify Page Title top and bottom padding.', 'hester' ),
					'section'     => 'hester_section_page_header',
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
							'control'  => 'hester_page_header_enable',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Page Header design options heading.
			$options['setting']['hester_page_header_heading_design'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-heading',
					'label'    => esc_html__( 'Design Options', 'hester' ),
					'section'  => 'hester_section_page_header',
					'required' => array(
						array(
							'control'  => 'hester_page_header_enable',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Page Header background design.
			$options['setting']['hester_page_header_background'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'label'    => esc_html__( 'Background', 'hester' ),
					'section'  => 'hester_section_page_header',
					'display'  => array(
						'background' => array(
							'color'    => esc_html__( 'Solid Color', 'hester' ),
							'gradient' => esc_html__( 'Gradient', 'hester' ),
							'image'    => esc_html__( 'Image', 'hester' ),
						),
					),
					'required' => array(
						array(
							'control'  => 'hester_page_header_enable',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_page_header_heading_design',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Page Header Text Color.
			$options['setting']['hester_page_header_text_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'label'    => esc_html__( 'Font Color', 'hester' ),
					'section'  => 'hester_section_page_header',
					'display'  => array(
						'color' => array(
							'text-color'       => esc_html__( 'Text Color', 'hester' ),
							'link-color'       => esc_html__( 'Link Color', 'hester' ),
							'link-hover-color' => esc_html__( 'Link Hover Color', 'hester' ),
						),
					),
					'required' => array(
						array(
							'control'  => 'hester_page_header_enable',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_page_header_heading_design',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Page Header Border.
			$options['setting']['hester_page_header_border'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'label'    => esc_html__( 'Border', 'hester' ),
					'section'  => 'hester_section_page_header',
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
							'control'  => 'hester_page_header_enable',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_page_header_heading_design',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Page Header typography heading.
			$options['setting']['hester_typography_page_header'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-heading',
					'label'    => esc_html__( 'Typography', 'hester' ),
					'section'  => 'hester_section_page_header',
					'required' => array(
						array(
							'control'  => 'hester_page_header_enable',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Page Header font size.
			$options['setting']['hester_page_header_font_size'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_responsive',
				'control'           => array(
					'type'        => 'hester-range',
					'label'       => esc_html__( 'Page Title Font Size', 'hester' ),
					'description' => esc_html__( 'Choose your page title font size.', 'hester' ),
					'section'     => 'hester_section_page_header',
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
							'control'  => 'hester_typography_page_header',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_page_header_enable',
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
new Hester_Customizer_Page_Header();
