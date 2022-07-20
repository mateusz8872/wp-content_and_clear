<?php
/**
 * Hester Misc section in Customizer.
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

if ( ! class_exists( 'Hester_Customizer_Misc' ) ) :
	/**
	 * Hester Misc section in Customizer.
	 */
	class Hester_Customizer_Misc {

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
			$options['section']['hester_section_misc'] = array(
				'title'    => esc_html__( 'Misc Settings', 'hester' ),
				'panel'    => 'hester_panel_general',
				'priority' => 60,
			);

			// Schema toggle.
			$options['setting']['hester_enable_schema'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'        => 'hester-toggle',
					'label'       => esc_html__( 'Schema Markup', 'hester' ),
					'description' => esc_html__( 'Add structured data to your content.', 'hester' ),
					'section'     => 'hester_section_misc',
				),
			);

			// Custom form styles.
			$options['setting']['hester_custom_input_style'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'        => 'hester-toggle',
					'label'       => esc_html__( 'Custom Form Styles', 'hester' ),
					'description' => esc_html__( 'Custom design for checkboxes and radio buttons.', 'hester' ),
					'section'     => 'hester_section_misc',
				),
			);

			// Initialize Parallax Footer
			$options['setting']['hester_parallax_footer'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'        => 'hester-toggle',
					'label'       => esc_html__( 'Parallax Footer', 'hester' ),
					'description' => esc_html__( 'Add parallax effect on footer.', 'hester' ),
					'section'     => 'hester_section_misc',
				),
			);

			// Page Preloader heading.
			$options['setting']['hester_preloader_heading'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Page Preloader', 'hester' ),
					'section' => 'hester_section_misc',
				),
			);

			// Enable/Disable Page Preloader.
			$options['setting']['hester_preloader'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'        => 'hester-toggle',
					'label'       => esc_html__( 'Enable Page Preloader', 'hester' ),
					'description' => esc_html__( 'Show animation until page is fully loaded.', 'hester' ),
					'section'     => 'hester_section_misc',
					'required'    => array(
						array(
							'control'  => 'hester_preloader_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Preloader visibility.
			$options['setting']['hester_preloader_visibility'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'label'       => esc_html__( 'Device Visibility', 'hester' ),
					'description' => esc_html__( 'Devices where Page Preloader is displayed.', 'hester' ),
					'section'     => 'hester_section_misc',
					'choices'     => array(
						'all'                => esc_html__( 'Show on All Devices', 'hester' ),
						'hide-mobile'        => esc_html__( 'Hide on Mobile', 'hester' ),
						'hide-tablet'        => esc_html__( 'Hide on Tablet', 'hester' ),
						'hide-mobile-tablet' => esc_html__( 'Hide on Mobile and Tablet', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_preloader_heading',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_preloader',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Scroll Top heading.
			$options['setting']['hester_scroll_top_heading'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Scroll Top Button', 'hester' ),
					'section' => 'hester_section_misc',
				),
			);

			// Enable/Disable Scroll Top.
			$options['setting']['hester_enable_scroll_top'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'        => 'hester-toggle',
					'label'       => esc_html__( 'Enable Scroll Top Button', 'hester' ),
					'description' => esc_html__( 'A sticky button that allows users to easily return to the top of a page.', 'hester' ),
					'section'     => 'hester_section_misc',
					'required'    => array(
						array(
							'control'  => 'hester_scroll_top_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Scroll Top device visibility.
			$options['setting']['hester_scroll_top_visibility'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'label'       => esc_html__( 'Device Visibility', 'hester' ),
					'description' => esc_html__( 'Devices where the button is displayed.', 'hester' ),
					'section'     => 'hester_section_misc',
					'choices'     => array(
						'all'                => esc_html__( 'Show on All Devices', 'hester' ),
						'hide-mobile'        => esc_html__( 'Hide on Mobile', 'hester' ),
						'hide-tablet'        => esc_html__( 'Hide on Tablet', 'hester' ),
						'hide-mobile-tablet' => esc_html__( 'Hide on Mobile and Tablet', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_enable_scroll_top',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_scroll_top_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Cursor Dot heading.
			$options['setting']['hester_cursor_dot_heading'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Cursor Dot Effect', 'hester' ),
					'section' => 'hester_section_misc',
				),
			);

			// Enable/Disable Cursor Dot.
			$options['setting']['hester_enable_cursor_dot'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'        => 'hester-toggle',
					'label'       => esc_html__( 'Enable Cursor Dot', 'hester' ),
					'description' => esc_html__( 'A cursor dot effect show on desktop size mode only with work on mouse.', 'hester' ),
					'section'     => 'hester_section_misc',
					'required'    => array(
						array(
							'control'  => 'hester_cursor_dot_heading',
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
new Hester_Customizer_Misc();
