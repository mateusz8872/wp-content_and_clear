<?php
/**
 * Hester Sticky Header Settings section in Customizer.
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

if ( ! class_exists( 'Hester_Customizer_Sticky_Header' ) ) :
	/**
	 * Hester Sticky Header section in Customizer.
	 */
	class Hester_Customizer_Sticky_Header {

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

			// Sticky Header Section.
			$options['section']['hester_section_sticky_header'] = array(
				'title'    => esc_html__( 'Sticky Header', 'hester' ),
				'panel'    => 'hester_panel_header',
				'priority' => 80,
			);

			// Enable Transparent Header.
			$options['setting']['hester_sticky_header'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-toggle',
					'label'   => esc_html__( 'Enable Sticky Header', 'hester' ),
					'section' => 'hester_section_sticky_header',
				),
			);

			// Responsive heading.
			$options['setting']['hester_sticky_header_responsive'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-heading',
					'section'  => 'hester_section_sticky_header',
					'label'    => esc_html__( 'Responsive', 'hester' ),
					'required' => array(
						array(
							'control'  => 'hester_sticky_header',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Hide sticky header on.
			$options['setting']['hester_sticky_header_hide_on'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_no_sanitize',
				'control'           => array(
					'type'        => 'hester-checkbox-group',
					'label'       => esc_html__( 'Hide on: ', 'hester' ),
					'description' => esc_html__( 'Choose on which devices to hide Sticky Header on. ', 'hester' ),
					'section'     => 'hester_section_sticky_header',
					'choices'     => hester_get_device_choices(),
					'required'    => array(
						array(
							'control'  => 'hester_sticky_header',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_sticky_header_responsive',
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
new Hester_Customizer_Sticky_Header();
