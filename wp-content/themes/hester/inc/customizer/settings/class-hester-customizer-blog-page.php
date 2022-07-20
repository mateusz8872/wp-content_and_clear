<?php
/**
 * Hester Blog » Blog Page / Archive section in Customizer.
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

if ( ! class_exists( 'Hester_Customizer_Blog_Page' ) ) :
	/**
	 * Hester Blog » Blog Page / Archive section in Customizer.
	 */
	class Hester_Customizer_Blog_Page {

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
			$options['section']['hester_section_blog_page'] = array(
				'title' => esc_html__( 'Blog Page / Archive', 'hester' ),
				'panel' => 'hester_panel_blog',
			);

			// Layout.
			$options['setting']['hester_blog_layout'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'        => 'hester-select',
					'label'       => esc_html__( 'Layout', 'hester' ),
					'description' => esc_html__( 'Choose blog layout. This will affect blog layout on archives, search results and posts page.', 'hester' ),
					'section'     => 'hester_section_blog_page',
					'choices'     => array(
						'blog-layout-1'   => esc_html__( 'Vertical', 'hester' ),
						'blog-horizontal' => esc_html__( 'Horizontal', 'hester' ),
					),
				),
			);

			$_image_sizes = hester_get_image_sizes();
			$size_choices = array();

			if ( ! empty( $_image_sizes ) ) {
				foreach ( $_image_sizes as $key => $value ) {
					$name = ucwords( str_replace( array( '-', '_' ), ' ', $key ) );

					$size_choices[ $key ] = $name;

					if ( $value['width'] || $value['height'] ) {
						$size_choices[ $key ] .= ' (' . $value['width'] . 'x' . $value['height'] . ')';
					}
				}
			}

			// Featured Image Size.
			$options['setting']['hester_blog_image_size'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'    => 'hester-select',
					'label'   => esc_html__( 'Featured Image Size', 'hester' ),
					'section' => 'hester_section_blog_page',
					'choices' => $size_choices,
				),
			);

			// Post Elements.
			$options['setting']['hester_blog_entry_elements'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_sortable',
				'control'           => array(
					'type'        => 'hester-sortable',
					'section'     => 'hester_section_blog_page',
					'label'       => esc_html__( 'Post Elements', 'hester' ),
					'description' => esc_html__( 'Set order and visibility for post elements.', 'hester' ),
					'choices'     => array(
						'summary'        => esc_html__( 'Summary', 'hester' ),
						'header'         => esc_html__( 'Title', 'hester' ),
						'meta'           => esc_html__( 'Post Meta', 'hester' ),
						'thumbnail'      => esc_html__( 'Featured Image', 'hester' ),
						'summary-footer' => esc_html__( 'Read More', 'hester' ),
					),
					'required'    => array(
						array(
							'control'  => 'hester_blog_layout',
							'value'    => 'blog-layout-1',
							'operator' => '==',
						),
					),
				),
			);

			// Meta/Post Details Layout.
			$options['setting']['hester_blog_entry_meta_elements'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_sortable',
				'control'           => array(
					'type'        => 'hester-sortable',
					'section'     => 'hester_section_blog_page',
					'label'       => esc_html__( 'Post Meta', 'hester' ),
					'description' => esc_html__( 'Set order and visibility for post meta details.', 'hester' ),
					'choices'     => array(
						'author'   => esc_html__( 'Author', 'hester' ),
						'date'     => esc_html__( 'Publish Date', 'hester' ),
						'comments' => esc_html__( 'Comments', 'hester' ),
						'category' => esc_html__( 'Categories', 'hester' ),
						'tag'      => esc_html__( 'Tags', 'hester' ),
					),
				),
			);

			// Post Categories.
			$options['setting']['hester_blog_horizontal_post_categories'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'        => 'hester-toggle',
					'label'       => esc_html__( 'Show Post Categories', 'hester' ),
					'description' => esc_html__( 'A list of categories the post belongs to. Displayed above post title.', 'hester' ),
					'section'     => 'hester_section_blog_page',
					'required'    => array(
						array(
							'control'  => 'hester_blog_layout',
							'value'    => 'blog-horizontal',
							'operator' => '==',
						),
					),
				),
			);

			// Read More Button.
			$options['setting']['hester_blog_horizontal_read_more'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'     => 'hester-toggle',
					'label'    => esc_html__( 'Show Read More Button', 'hester' ),
					'section'  => 'hester_section_blog_page',
					'required' => array(
						array(
							'control'  => 'hester_blog_layout',
							'value'    => 'blog-horizontal',
							'operator' => '==',
						),
					),
				),
			);

			// Meta Author image.
			$options['setting']['hester_entry_meta_icons'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_toggle',
				'control'           => array(
					'type'    => 'hester-toggle',
					'section' => 'hester_section_blog_page',
					'label'   => esc_html__( 'Show avatar and icons in post meta', 'hester' ),
				),
			);

			// Featured Image Position.
			$options['setting']['hester_blog_image_position'] = array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'hester_sanitize_select',
				'control'           => array(
					'type'     => 'hester-select',
					'label'    => esc_html__( 'Featured Image Position', 'hester' ),
					'section'  => 'hester_section_blog_page',
					'choices'  => array(
						'left'  => esc_html__( 'Left', 'hester' ),
						'right' => esc_html__( 'Right', 'hester' ),
					),
					'required' => array(
						array(
							'control'  => 'hester_blog_layout',
							'value'    => 'blog-horizontal',
							'operator' => '==',
						),
					),
				),
			);

			// Excerpt Length.
			$options['setting']['hester_excerpt_length'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'hester_sanitize_range',
				'control'           => array(
					'type'        => 'hester-range',
					'section'     => 'hester_section_blog_page',
					'label'       => esc_html__( 'Excerpt Length', 'hester' ),
					'description' => esc_html__( 'Number of words displayed in the excerpt.', 'hester' ),
					'min'         => 0,
					'max'         => 100,
					'step'        => 1,
					'unit'        => '',
					'responsive'  => false,
				),
			);

			// Excerpt more.
			$options['setting']['hester_excerpt_more'] = array(
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
				'control'           => array(
					'type'        => 'hester-text',
					'section'     => 'hester_section_blog_page',
					'label'       => esc_html__( 'Excerpt More', 'hester' ),
					'description' => esc_html__( 'What to append to excerpt if the text is cut.', 'hester' ),
				),
			);

			return $options;
		}
	}
endif;

new Hester_Customizer_Blog_Page();
