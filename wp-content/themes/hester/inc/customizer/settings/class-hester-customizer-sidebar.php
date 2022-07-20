<?php
/**
 * Hester Sidebar section in Customizer.
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

if ( ! class_exists( 'Hester_Customizer_Sidebar' ) ) :

	/**
	 * Hester Sidebar section in Customizer.
	 */
	class Hester_Customizer_Sidebar {

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
			$options['section']['hester_section_sidebar'] = array(
				'title'    => esc_html__( 'Sidebar', 'hester' ),
				'priority' => 4,
			);

			// Default sidebar position.
			$options['setting']['hester_sidebar_position'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'section'     => 'hester_section_sidebar',
					'label'       => esc_html__( 'Default Position', 'hester' ),
					'description' => esc_html__( 'Choose default sidebar position layout. You can change this setting per page via metabox settings.', 'hester' ),
					'choices'     => array(
						'no-sidebar'    => esc_html__( 'No Sidebar', 'hester' ),
						'left-sidebar'  => esc_html__( 'Left Sidebar', 'hester' ),
						'right-sidebar' => esc_html__( 'Right Sidebar', 'hester' ),
					),
				),
			);

			// Single post sidebar position.
			$options['setting']['hester_single_post_sidebar_position'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'label'       => esc_html__( 'Single Post', 'hester' ),
					'description' => esc_html__( 'Choose default sidebar position layout for single posts. You can change this setting per post via metabox settings.', 'hester' ),
					'section'     => 'hester_section_sidebar',
					'choices'     => array(
						'default'       => esc_html__( 'Default', 'hester' ),
						'no-sidebar'    => esc_html__( 'No Sidebar', 'hester' ),
						'left-sidebar'  => esc_html__( 'Left Sidebar', 'hester' ),
						'right-sidebar' => esc_html__( 'Right Sidebar', 'hester' ),
					),
				),
			);

			// Single page sidebar position.
			$options['setting']['hester_single_page_sidebar_position'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'label'       => esc_html__( 'Page', 'hester' ),
					'description' => esc_html__( 'Choose default sidebar position layout for pages. You can change this setting per page via metabox settings.', 'hester' ),
					'section'     => 'hester_section_sidebar',
					'choices'     => array(
						'default'       => esc_html__( 'Default', 'hester' ),
						'no-sidebar'    => esc_html__( 'No Sidebar', 'hester' ),
						'left-sidebar'  => esc_html__( 'Left Sidebar', 'hester' ),
						'right-sidebar' => esc_html__( 'Right Sidebar', 'hester' ),
					),
				),
			);

			// Archive sidebar position.
			$options['setting']['hester_archive_sidebar_position'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'label'       => esc_html__( 'Archives & Search', 'hester' ),
					'description' => esc_html__( 'Choose default sidebar position layout for archives and search results.', 'hester' ),
					'section'     => 'hester_section_sidebar',
					'choices'     => array(
						'default'       => esc_html__( 'Default', 'hester' ),
						'no-sidebar'    => esc_html__( 'No Sidebar', 'hester' ),
						'left-sidebar'  => esc_html__( 'Left Sidebar', 'hester' ),
						'right-sidebar' => esc_html__( 'Right Sidebar', 'hester' ),
					),
				),
			);

			// Sidebar options heading.
			$options['setting']['hester_sidebar_options_heading'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Options', 'hester' ),
					'section' => 'hester_section_sidebar',
				),
			);

			// Sidebar style.
			$options['setting']['hester_sidebar_style'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'section'     => 'hester_section_sidebar',
					'label'       => esc_html__( 'Sidebar Style', 'hester' ),
					'description' => esc_html__( 'Choose sidebar style.', 'hester' ),
					'choices'     => array(
						'1' => esc_html__( 'Minimal', 'hester' ),
						'2' => esc_html__( 'Title Focus', 'hester' ),
						'3' => esc_html__( 'Widgets Separated', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_sidebar_options_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Sidebar width.
			$options['setting']['hester_sidebar_width'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_range',
				'control'           => array(
					'type'        => 'hester-range',
					'section'     => 'hester_section_sidebar',
					'label'       => esc_html__( 'Sidebar Width', 'hester' ),
					'description' => esc_html__( 'Change your sidebar width.', 'hester' ),
					'min'         => 15,
					'max'         => 50,
					'step'        => 1,
					'unit'        => '%',
					'required'    => array(
						array(
							'control'  => 'hester_sidebar_options_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Sticky sidebar.
			$options['setting']['hester_sidebar_sticky'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'section'     => 'hester_section_sidebar',
					'label'       => esc_html__( 'Sticky Sidebar', 'hester' ),
					'description' => esc_html__( 'Stick sidebar when scrolling.', 'hester' ),
					'choices'     => array(
						''            => esc_html__( 'Disable', 'hester' ),
						'sidebar'     => esc_html__( 'Stick first widget', 'hester' ),
						'last-widget' => esc_html__( 'Stick last widget', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_sidebar_options_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Sidebar mobile position.
			$options['setting']['hester_sidebar_responsive_position'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'section'     => 'hester_section_sidebar',
					'label'       => esc_html__( 'Responsive Sidebar Position', 'hester' ),
					'description' => esc_html__( 'Control sidebar position on smaller screens.', 'hester' ),
					'choices'     => array(
						'hide'           => esc_html__( 'Hide', 'hester' ),
						'before-content' => esc_html__( 'Before Content', 'hester' ),
						'after-content'  => esc_html__( 'After Content', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_sidebar_options_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Sidebar typography heading.
			$options['setting']['hester_typography_sidebar_heading'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Typography', 'hester' ),
					'section' => 'hester_section_sidebar',
				),
			);

			// Sidebar widget heading.
			$options['setting']['hester_sidebar_widget_title_font_size'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_responsive',
				'control'           => array(
					'type'        => 'hester-range',
					'label'       => esc_html__( 'Widget Title Font Size', 'hester' ),
					'description' => esc_html__( 'Specify sidebar widget title font size.', 'hester' ),
					'section'     => 'hester_section_sidebar',
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
							'control'  => 'hester_typography_sidebar_heading',
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

new Hester_Customizer_Sidebar();
