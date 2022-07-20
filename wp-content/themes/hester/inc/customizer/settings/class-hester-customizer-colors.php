<?php
/**
 * Hester Base Colors section in Customizer.
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

if ( ! class_exists( 'Hester_Customizer_Colors' ) ) :
	/**
	 * Hester Colors section in Customizer.
	 */
	class Hester_Customizer_Colors {

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
			$options['section']['hester_section_colors'] = array(
				'title'    => esc_html__( 'Base Colors', 'hester' ),
				'panel'    => 'hester_panel_general',
				'priority' => 20,
			);

			// Accent color.
			$options['setting']['hester_accent_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_color',
				'control'           => array(
					'type'        => 'hester-color',
					'label'       => esc_html__( 'Accent Color', 'hester' ),
					'description' => esc_html__( 'The accent color is used subtly throughout your site, to call attention to key elements.', 'hester' ),
					'section'     => 'hester_section_colors',
					'priority'    => 10,
					'opacity'     => false,
				),
			);

			// Body background heading.
			$options['setting']['hester_body_background_heading'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-heading',
					'priority' => 40,
					'label'    => esc_html__( 'Body Background', 'hester' ),
					'section'  => 'hester_section_colors',
					'toggle'   => false,
				),
			);

			// Content background heading.
			$options['setting']['hester_content_colors_heading'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-heading',
					'priority' => 50,
					'label'    => esc_html__( 'Content', 'hester' ),
					'section'  => 'hester_section_colors',
					'toggle'   => false,
				),
			);

			// Content text color.
			$options['setting']['hester_content_text_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_color',
				'control'           => array(
					'type'     => 'hester-color',
					'label'    => esc_html__( 'Text Color', 'hester' ),
					'section'  => 'hester_section_colors',
					'priority' => 50,
					'opacity'  => true,
				),
			);

			// Content text color.
			$options['setting']['hester_content_link_hover_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_color',
				'control'           => array(
					'type'        => 'hester-color',
					'label'       => esc_html__( 'Link Hover Color', 'hester' ),
					'description' => esc_html__( 'This only applies to entry content area, other links will use the accent color on hover.', 'hester' ),
					'section'     => 'hester_section_colors',
					'priority'    => 50,
					'opacity'     => true,
				),
			);

			// Headings color.
			$options['setting']['hester_headings_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_color',
				'control'           => array(
					'type'     => 'hester-color',
					'label'    => esc_html__( 'Headings Color', 'hester' ),
					'section'  => 'hester_section_colors',
					'priority' => 50,
					'opacity'  => true,
				),
			);

			// Content background color.
			$options['setting']['hester_boxed_content_background_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_color',
				'control'           => array(
					'type'        => 'hester-color',
					'label'       => esc_html__( 'Boxed Content - Background Color', 'hester' ),
					'description' => esc_html__( 'Only used if Site Layout is Boxed or Boxed Content.', 'hester' ),
					'section'     => 'hester_section_colors',
					'priority'    => 50,
					'opacity'     => true,
				),
			);

			return $options;
		}

	}
endif;
new Hester_Customizer_Colors();
