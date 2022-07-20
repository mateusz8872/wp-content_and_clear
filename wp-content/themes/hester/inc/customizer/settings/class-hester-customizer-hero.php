<?php
/**
 * Hester Hero Section Settings section in Customizer.
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

if ( ! class_exists( 'Hester_Customizer_Hero' ) ) :
	/**
	 * Hester Page Title Settings section in Customizer.
	 */
	class Hester_Customizer_Hero {

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

			// Hero Section.
			$options['section']['hester_section_hero'] = array(
				'title'    => esc_html__( 'Hero', 'hester' ),
				'priority' => 3,
			);

			// Hero enable.
			$options['setting']['hester_enable_hero'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-toggle',
					'section' => 'hester_section_hero',
					'label'   => esc_html__( 'Enable Hero Section', 'hester' ),
				),
			);

			// Visibility.
			$options['setting']['hester_hero_visibility'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'section'     => 'hester_section_hero',
					'label'       => esc_html__( 'Device Visibility', 'hester' ),
					'description' => esc_html__( 'Devices where the Hero is displayed.', 'hester' ),
					'choices'     => array(
						'all'                => esc_html__( 'Show on All Devices', 'hester' ),
						'hide-mobile'        => esc_html__( 'Hide on Mobile', 'hester' ),
						'hide-tablet'        => esc_html__( 'Hide on Tablet', 'hester' ),
						'hide-mobile-tablet' => esc_html__( 'Hide on Mobile and Tablet', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_enable_hero',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Hero display on.
			$options['setting']['hester_hero_enable_on'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_no_sanitize',
				'control'           => array(
					'type'        => 'hester-checkbox-group',
					'label'       => esc_html__( 'Enable On: ', 'hester' ),
					'description' => esc_html__( 'Choose on which pages you want to enable Hero. ', 'hester' ),
					'section'     => 'hester_section_hero',
					'choices'     => array(
						'home'       => array(
							'title' => esc_html__( 'Home Page', 'hester' ),
						),
						'posts_page' => array(
							'title' => esc_html__( 'Blog / Posts Page', 'hester' ),
						),
					),
					'required'    => array(
						array(
							'control'  => 'hester_enable_hero',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Hover Slider heading.
			$options['setting']['hester_hero_hover_slider'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-heading',
					'section'  => 'hester_section_hero',
					'label'    => esc_html__( 'Style', 'hester' ),
					'required' => array(
						array(
							'control'  => 'hester_enable_hero',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_hero_type',
							'value'    => 'hover-slider',
							'operator' => '==',
						),
					),
				),
			);

			// Hover Slider container width.
			$options['setting']['hester_hero_hover_slider_container'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'section'     => 'hester_section_hero',
					'label'       => esc_html__( 'Width', 'hester' ),
					'description' => esc_html__( 'Stretch the container to full width, or match your site&rsquo;s content width.', 'hester' ),
					'choices'     => array(
						'content-width' => esc_html__( 'Content Width', 'hester' ),
						'full-width'    => esc_html__( 'Full Width', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_enable_hero',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_hero_hover_slider',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_hero_type',
							'value'    => 'hover-slider',
							'operator' => '==',
						),
					),
				),
			);

			// Hover Slider height.
			$options['setting']['hester_hero_hover_slider_height'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_range',
				'control'           => array(
					'type'        => 'hester-range',
					'section'     => 'hester_section_hero',
					'label'       => esc_html__( 'Height', 'hester' ),
					'description' => esc_html__( 'Set the height of the container.', 'hester' ),
					'min'         => 350,
					'max'         => 1000,
					'step'        => 1,
					'unit'        => 'px',
					'required'    => array(
						array(
							'control'  => 'hester_enable_hero',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_hero_hover_slider',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_hero_type',
							'value'    => 'hover-slider',
							'operator' => '==',
						),
					),
				),
			);

			// Hover Slider overlay.
			$options['setting']['hester_hero_hover_slider_overlay'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'section'     => 'hester_section_hero',
					'label'       => esc_html__( 'Overlay', 'hester' ),
					'description' => esc_html__( 'Choose hero overlay style.', 'hester' ),
					'choices'     => array(
						'none' => esc_html__( 'None', 'hester' ),
						'1'    => esc_html__( 'Overlay 1', 'hester' ),
						'2'    => esc_html__( 'Overlay 2', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_enable_hero',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_hero_hover_slider',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_hero_type',
							'value'    => 'hover-slider',
							'operator' => '==',
						),
					),
				),
			);

			// Hover Slider Elements.
			$options['setting']['hester_hero_hover_slider_elements'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_sortable',
				'control'           => array(
					'type'        => 'hester-sortable',
					'section'     => 'hester_section_hero',
					'label'       => esc_html__( 'Post Elements', 'hester' ),
					'description' => esc_html__( 'Set order and visibility for post elements.', 'hester' ),
					'sortable'    => false,
					'choices'     => array(
						'category'  => esc_html__( 'Categories', 'hester' ),
						'meta'      => esc_html__( 'Post Details', 'hester' ),
						'read_more' => esc_html__( 'Continue Reading', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_enable_hero',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_hero_hover_slider',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_hero_type',
							'value'    => 'hover-slider',
							'operator' => '==',
						),
					),
				),
				'partial'           => array(
					'selector'            => '#hero',
					'render_callback'     => 'hester_blog_hero',
					'container_inclusive' => true,
					'fallback_refresh'    => true,
				),
			);

			// Post Settings heading.
			$options['setting']['hester_hero_hover_slider_posts'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-heading',
					'section'  => 'hester_section_hero',
					'label'    => esc_html__( 'Post Settings', 'hester' ),
					'required' => array(
						array(
							'control'  => 'hester_enable_hero',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_hero_type',
							'value'    => 'hover-slider',
							'operator' => '==',
						),
					),
				),
			);

			// Post count.
			$options['setting']['hester_hero_hover_slider_post_number'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_range',
				'control'           => array(
					'type'        => 'hester-range',
					'section'     => 'hester_section_hero',
					'label'       => esc_html__( 'Post Number', 'hester' ),
					'description' => esc_html__( 'Set the number of visible posts.', 'hester' ),
					'min'         => 1,
					'max'         => 4,
					'step'        => 1,
					'unit'        => '',
					'required'    => array(
						array(
							'control'  => 'hester_enable_hero',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_hero_hover_slider_posts',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_hero_type',
							'value'    => 'hover-slider',
							'operator' => '==',
						),
					),
				),
				'partial'           => array(
					'selector'            => '#hero',
					'render_callback'     => 'hester_blog_hero',
					'container_inclusive' => true,
					'fallback_refresh'    => true,
				),
			);

			// Post category.
			$options['setting']['hester_hero_hover_slider_category'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'section'     => 'hester_section_hero',
					'label'       => esc_html__( 'Category', 'hester' ),
					'description' => esc_html__( 'Display posts from selected category only. Leave empty to include all.', 'hester' ),
					'is_select2'  => true,
					'data_source' => 'category',
					'multiple'    => true,
					'required'    => array(
						array(
							'control'  => 'hester_enable_hero',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_hero_hover_slider_posts',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_hero_type',
							'value'    => 'hover-slider',
							'operator' => '==',
						),
					),
				),
			);

			return $options;
		}
	}
endif;
new Hester_Customizer_Hero();
