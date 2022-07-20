<?php
/**
 * Hester Base Typography section in Customizer.
 *
 * @package Hester
 * @author  Peregrine Themes
 * @since   1.0.0
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Hester_Customizer_Typography' ) ) :
	/**
	 * Hester Typography section in Customizer.
	 */
	class Hester_Customizer_Typography {

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
			$options['section']['hester_section_typography'] = array(
				'title'    => esc_html__( 'Base Typography', 'hester' ),
				'panel'    => 'hester_panel_general',
				'priority' => 30,
			);

			// HTML base font size.
			$options['setting']['hester_html_base_font_size'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_responsive',
				'control'           => array(
					'type'        => 'hester-range',
					'label'       => esc_html__( 'Base Font Size', 'hester' ),
					'description' => esc_html__( 'REM base of the root (html) element. ( 62.5 × 16 ) / 100  = 10px', 'hester' ),
					'section'     => 'hester_section_typography',
					'min'         => 50,
					'max'         => 100,
					'step'        => 0.5,
					'unit'        => '%',
					'responsive'  => true,
				),
			);

			// Anti-Aliased Font Smoothing.
			$options['setting']['hester_font_smoothing'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'        => 'hester-toggle',
					'label'       => esc_html__( 'Font Smoothing', 'hester' ),
					'description' => esc_html__( 'Enable/Disable anti-aliasing font smoothing.', 'hester' ),
					'section'     => 'hester_section_typography',
				),
			);

			// Headings typography heading.
			$options['setting']['hester_typography_body_heading'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Body & Content', 'hester' ),
					'section' => 'hester_section_typography',
				),
			);

			// Body Font.
			$options['setting']['hester_body_font'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_typography',
				'control'           => array(
					'type'     => 'hester-typography',
					'label'    => esc_html__( 'Body Typography', 'hester' ),
					'section'  => 'hester_section_typography',
					'display'  => array(
						'font-family'     => array(),
						'font-subsets'    => array(),
						'font-weight'     => array(),
						'font-style'      => array(),
						'text-transform'  => array(),
						'text-decoration' => array(),
						'letter-spacing'  => array(),
						'font-size'       => array(),
						'line-height'     => array(),
					),
					'required' => array(
						array(
							'control'  => 'hester_typography_body_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Headings typography heading.
			$options['setting']['hester_typography_headings_heading'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Headings (H1 - H6)', 'hester' ),
					'section' => 'hester_section_typography',
				),
			);

			// Headings default.
			$options['setting']['hester_headings_font'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_typography',
				'control'           => array(
					'type'     => 'hester-typography',
					'label'    => esc_html__( 'Headings Default', 'hester' ),
					'section'  => 'hester_section_typography',
					'display'  => array(
						'font-family'    => array(),
						'font-subsets'   => array(),
						'font-weight'    => array(),
						'font-style'     => array(),
						'text-transform' => array(),
					),
					'required' => array(
						array(
							'control'  => 'hester_typography_headings_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			for ( $i = 1; $i <= 6; $i++ ) {

				$options['setting'][ 'hester_h' . $i . '_font' ] = array(
					'transport'         => 'postMessage',
					'sanitize_callback' => 'hester_sanitize_typography',
					'control'           => array(
						'type'     => 'hester-typography',
						/* translators: %s Heading size */
						'label'    => esc_html( sprintf( __( 'H%s', 'hester' ), $i ) ),
						'section'  => 'hester_section_typography',
						'display'  => array(
							'font-family'     => array(),
							'font-subsets'    => array(),
							'font-weight'     => array(),
							'font-style'      => array(),
							'text-transform'  => array(),
							'text-decoration' => array(),
							'letter-spacing'  => array(),
							'font-size'       => array(),
							'line-height'     => array(),
						),
						'required' => array(
							array(
								'control'  => 'hester_typography_headings_heading',
								'value'    => true,
								'operator' => '==',
							),
						),
					),
				);
			}

			$options['setting']['hester_heading_em_font'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_typography',
				'control'           => array(
					'type'        => 'hester-typography',
					'label'       => esc_html__( 'Heading Emphasized Text', 'hester' ),
					'description' => esc_html__( 'Adds a separate font for styling of &lsaquo;em&rsaquo; tags, so you can create stylish typographic elements.', 'hester' ),
					'section'     => 'hester_section_typography',
					'display'     => array(
						'font-family'     => array(),
						'font-subsets'    => array(),
						'font-weight'     => array(),
						'font-style'      => array(),
						'text-transform'  => array(),
						'text-decoration' => array(),
						'letter-spacing'  => array(),
					),
					'required'    => array(
						array(
							'control'  => 'hester_typography_headings_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Section Heading Style.
			$options['setting']['hester_section_heading_style'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'label'       => esc_html__( 'Section Heading Style', 'hester' ),
					'description' => esc_html__( 'Adds a heading style for home sections, so you can create stylish homepage.', 'hester' ),
					'section'     => 'hester_section_typography',
					'choices'     => array(
						'0' => esc_html__( 'Heading Default', 'hester' ),
						'1' => esc_html__( 'Heading Style1', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_typography_headings_heading',
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
new Hester_Customizer_Typography();
