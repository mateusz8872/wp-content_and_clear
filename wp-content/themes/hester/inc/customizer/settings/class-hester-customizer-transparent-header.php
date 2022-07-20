<?php
/**
 * Hester Transparent Header Settings section in Customizer.
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

if ( ! class_exists( 'Hester_Customizer_Transparent_Header' ) ) :
	/**
	 * Hester Main Transparent section in Customizer.
	 */
	class Hester_Customizer_Transparent_Header {

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

			// Transparent Header Section.
			$options['section']['hester_section_transparent_header'] = array(
				'title'    => esc_html__( 'Transparent Header', 'hester' ),
				'panel'    => 'hester_panel_header',
				'priority' => 80,
			);

			// Enable Transparent Header.
			$options['setting']['hester_tsp_header'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-toggle',
					'label'   => esc_html__( 'Enable Globally', 'hester' ),
					'section' => 'hester_section_transparent_header',
				),
			);

			// Disable choices.
			$disable_choices = array(
				'404'        => array(
					'title' => esc_html__( '404 page', 'hester' ),
				),
				'posts_page' => array(
					'title' => esc_html__( 'Blog / Posts page', 'hester' ),
				),
				'archive'    => array(
					'title' => esc_html__( 'Archive pages', 'hester' ),
				),
				'search'     => array(
					'title' => esc_html__( 'Search pages', 'hester' ),
				),
				'post'       => array(
					'title' => esc_html__( 'Posts', 'hester' ),
				),
				'page'       => array(
					'title' => esc_html__( 'Pages', 'hester' ),
				),
			);

			// Get additionally registered post types.
			$post_types = get_post_types(
				array(
					'public'   => true,
					'_builtin' => false,
				),
				'objects'
			);

			if ( is_array( $post_types ) && ! empty( $post_types ) ) {
				foreach ( $post_types as $slug => $post_type ) {
					$disable_choices[ $slug ] = array(
						'title' => $post_type->label,
					);
				}
			}

			// Transparent header display on.
			$options['setting']['hester_tsp_header_disable_on'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_no_sanitize',
				'control'           => array(
					'type'        => 'hester-checkbox-group',
					'label'       => esc_html__( 'Disable On: ', 'hester' ),
					'description' => esc_html__( 'Choose on which pages you want to disable Transparent Header. ', 'hester' ),
					'section'     => 'hester_section_transparent_header',
					'choices'     => $disable_choices,
					'required'    => array(
						array(
							'control'  => 'hester_tsp_header',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Logo Settings Heading.
			$options['setting']['hester_tsp_logo_heading'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Logo Settings', 'hester' ),
					'section' => 'hester_section_transparent_header',
				),
			);

			// Logo.
			$options['setting']['hester_tsp_logo'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_background',
				'control'           => array(
					'type'        => 'hester-background',
					'section'     => 'hester_section_transparent_header',
					'label'       => esc_html__( 'Alternative Logo', 'hester' ),
					'description' => esc_html__( 'Upload a different logo to be used with Transparent Header.', 'hester' ),
					'advanced'    => false,
					'strings'     => array(
						'select_image' => __( 'Select logo', 'hester' ),
						'use_image'    => __( 'Select', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_tsp_logo_heading',
							'value'    => true,
							'operator' => '==',
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

			// Logo Retina.
			$options['setting']['hester_tsp_logo_retina'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_background',
				'control'           => array(
					'type'        => 'hester-background',
					'section'     => 'hester_section_transparent_header',
					'label'       => esc_html__( 'Alternative Logo - Retina', 'hester' ),
					'description' => esc_html__( 'Upload exactly 2x the size of your alternative logo to make your logo crisp on HiDPI screens. This options is not required if logo above is in SVG format.', 'hester' ),
					'advanced'    => false,
					'strings'     => array(
						'select_image' => __( 'Select logo', 'hester' ),
						'use_image'    => __( 'Select', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_tsp_logo_heading',
							'value'    => true,
							'operator' => '==',
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
			$options['setting']['hester_tsp_logo_max_height'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_responsive',
				'control'           => array(
					'type'        => 'hester-range',
					'label'       => esc_html__( 'Logo Height', 'hester' ),
					'description' => esc_html__( 'Maximum logo image height on transparent header.', 'hester' ),
					'section'     => 'hester_section_transparent_header',
					'min'         => 0,
					'max'         => 1000,
					'step'        => 10,
					'unit'        => 'px',
					'responsive'  => true,
					'required'    => array(
						array(
							'control'  => 'hester_tsp_logo_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Logo margin.
			$options['setting']['hester_tsp_logo_margin'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_responsive',
				'control'           => array(
					'type'        => 'hester-spacing',
					'label'       => esc_html__( 'Logo Margin', 'hester' ),
					'description' => esc_html__( 'Specify spacing around logo on transparent header. Negative values are allowed. Leave empty to inherit from Logos & Site Title » Logo Margin.', 'hester' ),
					'section'     => 'hester_section_transparent_header',
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
					'required'    => array(
						array(
							'control'  => 'hester_tsp_logo_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Custom Colors Heading.
			$options['setting']['hester_tsp_colors_heading'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Main Header Colors', 'hester' ),
					'section' => 'hester_section_transparent_header',
				),
			);

			// Background.
			$options['setting']['hester_tsp_header_background'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'section'  => 'hester_section_transparent_header',
					'label'    => esc_html__( 'Background', 'hester' ),
					'space'    => true,
					'display'  => array(
						'background' => array(
							'color' => esc_html__( 'Solid Color', 'hester' ),
						),
					),
					'required' => array(
						array(
							'control'  => 'hester_tsp_colors_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Text Color.
			$options['setting']['hester_tsp_header_font_color'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'section'  => 'hester_section_transparent_header',
					'label'    => esc_html__( 'Font Color', 'hester' ),
					'space'    => true,
					'display'  => array(
						'color' => array(
							'text-color'       => esc_html__( 'Text Color', 'hester' ),
							'link-color'       => esc_html__( 'Link Color', 'hester' ),
							'link-hover-color' => esc_html__( 'Link Hover Color', 'hester' ),
						),
					),
					'required' => array(
						array(
							'control'  => 'hester_tsp_colors_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Border.
			$options['setting']['hester_tsp_header_border'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_design_options',
				'control'           => array(
					'type'     => 'hester-design-options',
					'section'  => 'hester_section_transparent_header',
					'label'    => esc_html__( 'Border', 'hester' ),
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
							'control'  => 'hester_tsp_colors_heading',
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
new Hester_Customizer_Transparent_Header();
