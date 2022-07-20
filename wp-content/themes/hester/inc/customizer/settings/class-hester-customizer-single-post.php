<?php
/**
 * Hester Blog - Single Post section in Customizer.
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

if ( ! class_exists( 'Hester_Customizer_Single_Post' ) ) :
	/**
	 * Hester Blog - Single Post section in Customizer.
	 */
	class Hester_Customizer_Single_Post {

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
			$options['section']['hester_section_blog_single_post'] = array(
				'title'    => esc_html__( 'Single Post', 'hester' ),
				'panel'    => 'hester_panel_blog',
				'priority' => 20,
			);

			// Single post layout.
			$options['setting']['hester_single_post_layout_heading'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Layout', 'hester' ),
					'section' => 'hester_section_blog_single_post',
				),
			);

			// Content Layout.
			$options['setting']['hester_single_title_position'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'label'       => esc_html__( 'Title Position', 'hester' ),
					'description' => esc_html__( 'Select title position for single post pages.', 'hester' ),
					'section'     => 'hester_section_blog_single_post',
					'choices'     => array(
						'in-content'     => esc_html__( 'In Content', 'hester' ),
						'in-page-header' => esc_html__( 'In Page Header', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_single_post_layout_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Alignment.
			$options['setting']['hester_single_title_alignment'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'     => 'hester-alignment',
					'label'    => esc_html__( 'Title Alignment', 'hester' ),
					'section'  => 'hester_section_blog_single_post',
					'choices'  => 'horizontal',
					'icons'    => array(
						'left'   => 'dashicons dashicons-editor-alignleft',
						'center' => 'dashicons dashicons-editor-aligncenter',
						'right'  => 'dashicons dashicons-editor-alignright',
					),
					'required' => array(
						array(
							'control'  => 'hester_single_post_layout_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Spacing.
			$options['setting']['hester_single_title_spacing'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_responsive',
				'control'           => array(
					'type'        => 'hester-spacing',
					'label'       => esc_html__( 'Title Spacing', 'hester' ),
					'description' => esc_html__( 'Specify title top and bottom padding.', 'hester' ),
					'section'     => 'hester_section_blog_single_post',
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
							'control'  => 'hester_single_post_layout_heading',
							'value'    => true,
							'operator' => '==',
						),
						array(
							'control'  => 'hester_single_title_position',
							'value'    => 'in-page-header',
							'operator' => '==',
						),
					),
				),
			);

			// Content width.
			$options['setting']['hester_single_content_width'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'label'       => esc_html__( 'Content Width', 'hester' ),
					'description' => esc_html__( 'Narrow content width or match your site&rsquo;s Content Width (defined in General Settings &raquo; Layout).', 'hester' ),
					'section'     => 'hester_section_blog_single_post',
					'choices'     => array(
						'wide'   => esc_html__( 'Content Width', 'hester' ),
						'narrow' => esc_html__( 'Narrow Width', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_single_post_layout_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Narrow container width.
			$options['setting']['hester_single_narrow_container_width'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_range',
				'control'           => array(
					'type'        => 'hester-range',
					'label'       => esc_html__( 'Narrow Container Width', 'hester' ),
					'description' => esc_html__( 'Choose the width (in px) for narrow container on single posts.', 'hester' ),
					'section'     => 'hester_section_blog_single_post',
					'min'         => 500,
					'max'         => 1500,
					'step'        => 10,
					'required'    => array(
						array(
							'control'  => 'hester_single_content_width',
							'value'    => 'narrow',
							'operator' => '==',
						),
						array(
							'control'  => 'hester_single_post_layout_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Single post elements.
			$options['setting']['hester_single_post_elements_heading'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Post Elements', 'hester' ),
					'section' => 'hester_section_blog_single_post',
				),
			);

			$options['setting']['hester_single_post_elements'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_sortable',
				'control'           => array(
					'type'        => 'hester-sortable',
					'section'     => 'hester_section_blog_single_post',
					'label'       => esc_html__( 'Post Elements', 'hester' ),
					'description' => esc_html__( 'Set visibility of post elements.', 'hester' ),
					'sortable'    => false,
					'choices'     => array(
						'thumb'          => esc_html__( 'Featured Image', 'hester' ),
						'category'       => esc_html__( 'Post Categories', 'hester' ),
						'tags'           => esc_html__( 'Post Tags', 'hester' ),
						'last-updated'   => esc_html__( 'Last Updated Date', 'hester' ),
						'about-author'   => esc_html__( 'About Author Box', 'hester' ),
						'prev-next-post' => esc_html__( 'Next/Prev Post Links', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_single_post_elements_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Meta/Post Details Layout.
			$options['setting']['hester_single_post_meta_elements'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_sortable',
				'control'           => array(
					'type'        => 'hester-sortable',
					'label'       => esc_html__( 'Post Meta', 'hester' ),
					'description' => esc_html__( 'Set order and visibility for post meta details.', 'hester' ),
					'section'     => 'hester_section_blog_single_post',
					'choices'     => array(
						'author'   => esc_html__( 'Author', 'hester' ),
						'date'     => esc_html__( 'Publish Date', 'hester' ),
						'comments' => esc_html__( 'Comments', 'hester' ),
						'category' => esc_html__( 'Categories', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_single_post_elements_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Meta icons.
			$options['setting']['hester_single_entry_meta_icons'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-toggle',
					'section'  => 'hester_section_blog_single_post',
					'label'    => esc_html__( 'Show avatar and icons in post meta', 'hester' ),
					'required' => array(
						array(
							'control'  => 'hester_single_post_elements_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Toggle Comments.
			$options['setting']['hester_single_toggle_comments'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'        => 'hester-toggle',
					'label'       => esc_html__( 'Show Toggle Comments', 'hester' ),
					'description' => esc_html__( 'Hide comments and comment form behind a toggle button. ', 'hester' ),
					'section'     => 'hester_section_blog_single_post',
					'required'    => array(
						array(
							'control'  => 'hester_single_post_elements_heading',
							'value'    => true,
							'operator' => '==',
						),
					),
				),
			);

			// Single Post typography heading.
			$options['setting']['hester_typography_single_post_heading'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-heading',
					'label'   => esc_html__( 'Typography', 'hester' ),
					'section' => 'hester_section_blog_single_post',
				),
			);

			// Single post content font size.
			$options['setting']['hester_single_content_font_size'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_responsive',
				'control'           => array(
					'type'        => 'hester-range',
					'label'       => esc_html__( 'Post Content Font Size', 'hester' ),
					'description' => esc_html__( 'Choose your single post content font size.', 'hester' ),
					'section'     => 'hester_section_blog_single_post',
					'responsive'  => true,
					'unit'        => array(
						array(
							'id'   => 'px',
							'name' => 'px',
							'min'  => 8,
							'max'  => 30,
							'step' => 1,
						),
						array(
							'id'   => 'em',
							'name' => 'em',
							'min'  => 0.5,
							'max'  => 1.875,
							'step' => 0.01,
						),
						array(
							'id'   => 'rem',
							'name' => 'rem',
							'min'  => 0.5,
							'max'  => 1.875,
							'step' => 0.01,
						),
					),
					'required'    => array(
						array(
							'control'  => 'hester_typography_single_post_heading',
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
new Hester_Customizer_Single_Post();
