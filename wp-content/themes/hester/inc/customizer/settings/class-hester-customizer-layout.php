<?php
/**
 * Hester Layout section in Customizer.
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

if ( ! class_exists( 'Hester_Customizer_Layout' ) ) :
	/**
	 * Hester Layout section in Customizer.
	 */
	class Hester_Customizer_Layout {

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
			$options['section']['hester_layout_section'] = array(
				'title'    => esc_html__( 'Layout', 'hester' ),
				'panel'    => 'hester_panel_general',
				'priority' => 10,
			);

			// Site layout.
			$options['setting']['hester_site_layout'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'section'     => 'hester_layout_section',
					'label'       => esc_html__( 'Site Layout', 'hester' ),
					'description' => esc_html__( 'Choose your site&rsquo;s main layout.', 'hester' ),
					'choices'     => array(
						'fw-contained'    => esc_html__( 'Full Width: Contained', 'hester' ),
						'fw-stretched'    => esc_html__( 'Full Width: Stretched', 'hester' ),
						'boxed-separated' => esc_html__( 'Boxed Content', 'hester' ),
						'boxed'           => esc_html__( 'Boxed', 'hester' ),
						'framed'          => esc_html__( 'Framed', 'hester' ),
					),
				),
			);

			// Container width.
			$options['setting']['hester_container_width'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_range',
				'control'           => array(
					'type'        => 'hester-range',
					'section'     => 'hester_layout_section',
					'label'       => esc_html__( 'Content Width', 'hester' ),
					'description' => esc_html__( 'Change your site&rsquo;s main container width.', 'hester' ),
					'min'         => 500,
					'max'         => 1920,
					'step'        => 10,
					'unit'        => 'px',
					'required'    => array(
						array(
							'control'  => 'hester_site_layout',
							'value'    => 'fw-stretched',
							'operator' => '!=',
						),
					),
				),
			);

			return $options;
		}
	}
endif;
new Hester_Customizer_Layout();
