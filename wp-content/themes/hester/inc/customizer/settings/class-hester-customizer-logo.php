<?php
/**
 * Hester Logo section in Customizer.
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

if ( ! class_exists( 'Hester_Customizer_Logo' ) ) :
	/**
	 * Hester Logo section in Customizer.
	 */
	class Hester_Customizer_Logo {

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

			// Logo Retina.
			$options['setting']['hester_logo_default_retina'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_background',
				'control'           => array(
					'type'        => 'hester-background',
					'section'     => 'title_tagline',
					'label'       => esc_html__( 'Retina Logo', 'hester' ),
					'description' => esc_html__( 'Upload exactly 2x the size of your default logo to make your logo crisp on HiDPI screens. This options is not required if logo above is in SVG format.', 'hester' ),
					'priority'    => 20,
					'advanced'    => false,
					'strings'     => array(
						'select_image' => __( 'Select logo', 'hester' ),
						'use_image'    => __( 'Select', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'custom_logo',
							'value'    => false,
							'operator' => '!=',
						),
					),
				),
				'partial'           => array(
					'selector'            => '.hester-logo',
					'render_callback'     => 'hester_logo',
					'container_inclusive' => false,
					'fallback_refresh'    => true,
				),
			);

			// Logo Max Height.
			$options['setting']['hester_logo_max_height'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_responsive',
				'control'           => array(
					'type'        => 'hester-range',
					'label'       => esc_html__( 'Logo Height', 'hester' ),
					'description' => esc_html__( 'Maximum logo image height.', 'hester' ),
					'section'     => 'title_tagline',
					'priority'    => 30,
					'min'         => 0,
					'max'         => 1000,
					'step'        => 10,
					'unit'        => 'px',
					'responsive'  => true,
					'required'    => array(
						array(
							'control'  => 'custom_logo',
							'value'    => false,
							'operator' => '!=',
						),
					),
				),
			);

			// Logo margin.
			$options['setting']['hester_logo_margin'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_responsive',
				'control'           => array(
					'type'        => 'hester-spacing',
					'label'       => esc_html__( 'Logo Margin', 'hester' ),
					'description' => esc_html__( 'Specify spacing around logo. Negative values are allowed.', 'hester' ),
					'section'     => 'title_tagline',
					'settings'    => 'hester_logo_margin',
					'priority'    => 40,
					'choices'     => array(
						'top'    => esc_html__( 'Top', 'hester' ),
						'right'  => esc_html__( 'Right', 'hester' ),
						'bottom' => esc_html__( 'Bottom', 'hester' ),
						'left'   => esc_html__( 'Left', 'hester' ),
					),
					'responsive'  => true,
					'unit'        => array(
						'px',
					),
				),
			);

			// Show tagline.
			$options['setting']['hester_display_tagline'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-toggle',
					'label'    => esc_html__( 'Display Tagline', 'hester' ),
					'section'  => 'title_tagline',
					'settings' => 'hester_display_tagline',
					'priority' => 80,
				),
				'partial'           => array(
					'selector'            => '.hester-logo',
					'render_callback'     => 'hester_logo',
					'container_inclusive' => false,
					'fallback_refresh'    => true,
				),
			);

			// Site Identity heading.
			$options['setting']['hester_logo_heading_site_identity'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-heading',
					'label'    => esc_html__( 'Site Identity', 'hester' ),
					'section'  => 'title_tagline',
					'settings' => 'hester_logo_heading_site_identity',
					'priority' => 50,
					'toggle'   => false,
				),
			);

			// Logo typography heading.
			$options['setting']['hester_typography_logo_heading'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-heading',
					'label'    => esc_html__( 'Typography', 'hester' ),
					'section'  => 'title_tagline',
					'priority' => 100,
					'required' => array(
						array(
							'control'  => 'custom_logo',
							'value'    => false,
							'operator' => '==',
						),
					),
				),
			);

			// Site title font size.
			$options['setting']['hester_logo_text_font_size'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_responsive',
				'control'           => array(
					'type'       => 'hester-range',
					'label'      => esc_html__( 'Site Title Font Size', 'hester' ),
					'section'    => 'title_tagline',
					'priority'   => 100,
					'min'        => 8,
					'max'        => 30,
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
							'control'  => 'custom_logo',
							'value'    => false,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_typography_logo_heading',
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
new Hester_Customizer_Logo();
