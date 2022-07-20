<?php

/**
 * Hester Options Class.
 *
 * @package  Hester
 * @author  Peregrine Themes
 * @since    1.0.0
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Hester_Options' ) ) :

	/**
	 * Hester Options Class.
	 */
	class Hester_Options {

		/**
		 * Singleton instance of the class.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private static $instance;

		/**
		 * Options variable.
		 *
		 * @since 1.0.0
		 * @var mixed $options
		 */
		private static $options;

		/**
		 * Main Hester_Options Instance.
		 *
		 * @since 1.0.0
		 * @return Hester_Options
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Hester_Options ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// Refresh options.
			add_action( 'after_setup_theme', array( $this, 'refresh' ) );
		}

		/**
		 * Set default option values.
		 *
		 * @since  1.0.0
		 * @return array Default values.
		 */
		public function get_defaults() {

			$defaults = array(

				/**
				 * General Settings.
				 */

				// Layout.
				'hester_site_layout'                       => 'fw-contained',
				'hester_container_width'                   => 1270,

				// Base Colors.
				'hester_accent_color'                      => '#5049E1',
				'hester_content_text_color'                => '#383838',
				'hester_headings_color'                    => '#232323',
				'hester_content_link_hover_color'          => '#232323',
				'hester_body_background_heading'           => true,
				'hester_content_background_heading'        => true,
				'hester_boxed_content_background_color'    => '#FFFFFF',
				'hester_scroll_top_visibility'             => 'all',

				// Base Typography.
				'hester_html_base_font_size'               => array(
					'desktop' => 62.5,
					'tablet'  => 53,
					'mobile'  => 50,
				),
				'hester_font_smoothing'                    => true,
				'hester_typography_body_heading'           => false,
				'hester_typography_headings_heading'       => false,
				'hester_body_font'                         => hester_typography_defaults(
					array(
						'font-family'         => 'default',
						'font-weight'         => 400,
						'font-size-desktop'   => '1.7',
						'font-size-unit'      => 'rem',
						'line-height-desktop' => '1.8',
					)
				),
				'hester_headings_font'                     => hester_typography_defaults(
					array(
						'font-weight'     => 700,
						'font-style'      => 'normal',
						'text-transform'  => 'none',
						'text-decoration' => 'none',
					)
				),
				'hester_h1_font'                           => hester_typography_defaults(
					array(
						'font-weight'         => 700,
						'font-size-desktop'   => '4',
						'font-size-unit'      => 'rem',
						'line-height-desktop' => '1.1',
					)
				),
				'hester_h2_font'                           => hester_typography_defaults(
					array(
						'font-weight'         => 700,
						'font-size-desktop'   => '3.6',
						'font-size-unit'      => 'rem',
						'line-height-desktop' => '1.2',
					)
				),
				'hester_h3_font'                           => hester_typography_defaults(
					array(
						'font-weight'         => 700,
						'font-size-desktop'   => '2.8',
						'font-size-unit'      => 'rem',
						'line-height-desktop' => '1.2',
					)
				),
				'hester_h4_font'                           => hester_typography_defaults(
					array(
						'font-weight'         => 700,
						'font-size-desktop'   => '2.4',
						'font-size-unit'      => 'rem',
						'line-height-desktop' => '1.2',
					)
				),
				'hester_h5_font'                           => hester_typography_defaults(
					array(
						'font-weight'         => 700,
						'font-size-desktop'   => '2',
						'font-size-unit'      => 'rem',
						'line-height-desktop' => '1.2',
					)
				),
				'hester_h6_font'                           => hester_typography_defaults(
					array(
						'font-weight'         => 600,
						'font-size-desktop'   => '1.8',
						'font-size-unit'      => 'rem',
						'line-height-desktop' => '1.72',
					)
				),
				'hester_heading_em_font'                   => hester_typography_defaults(
					array(
						'font-family' => 'Playfair Display',
						'font-weight' => 'inherit',
						'font-style'  => 'italic',
					)
				),
				'hester_section_heading_style'             => '1',
				'hester_footer_widget_title_font_size'     => array(
					'desktop' => 1.8,
					'unit'    => 'rem',
				),

				// Primary Button.
				'hester_primary_button_heading'            => false,
				'hester_primary_button_bg_color'           => '',
				'hester_primary_button_hover_bg_color'     => '',
				'hester_primary_button_text_color'         => '#FFFFFF',
				'hester_primary_button_hover_text_color'   => '#FFFFFF',
				'hester_primary_button_border_radius'      => array(
					'top-left'     => 0.6,
					'top-right'    => 0.6,
					'bottom-right' => 0.6,
					'bottom-left'  => 0.6,
					'unit'         => 'rem',
				),
				'hester_primary_button_border_width'       => .2,
				'hester_primary_button_border_color'       => 'rgba(0, 0, 0, 0.12)',
				'hester_primary_button_hover_border_color' => 'rgba(0, 0, 0, 0.12)',
				'hester_primary_button_typography'         => hester_typography_defaults(
					array(
						'font-family'         => 'inherit',
						'font-weight'         => 500,
						'font-size-desktop'   => '1.6',
						'font-size-unit'      => 'rem',
						'line-height-desktop' => '1.4',
					)
				),

				// Secondary Button.
				'hester_secondary_button_heading'          => false,
				'hester_secondary_button_bg_color'         => '#232323',
				'hester_secondary_button_hover_bg_color'   => '#3e4750',
				'hester_secondary_button_text_color'       => '#FFFFFF',
				'hester_secondary_button_hover_text_color' => '#FFFFFF',
				'hester_secondary_button_border_radius'    => array(
					'top-left'     => 0.6,
					'top-right'    => 0.6,
					'bottom-right' => 0.6,
					'bottom-left'  => 0.6,
					'unit'         => 'rem',
				),
				'hester_secondary_button_border_width'     => .2,
				'hester_secondary_button_border_color'     => 'rgba(0, 0, 0, 0.12)',
				'hester_secondary_button_hover_border_color' => 'rgba(0, 0, 0, 0.12)',
				'hester_secondary_button_typography'       => hester_typography_defaults(
					array(
						'font-family'         => 'inherit',
						'font-weight'         => 500,
						'font-size-desktop'   => '1.6',
						'font-size-unit'      => 'rem',
						'line-height-desktop' => '1.4',
					)
				),

				// Text button.
				'hester_text_button_heading'               => false,
				'hester_text_button_text_color'            => '#232323',
				'hester_text_button_hover_text_color'      => '',
				'hester_text_button_typography'            => hester_typography_defaults(
					array(
						'font-family'         => 'inherit',
						'font-weight'         => 500,
						'font-size-desktop'   => '1.6',
						'font-size-unit'      => 'rem',
						'line-height-desktop' => '1.4',
					)
				),

				// Misc Settings.
				'hester_enable_schema'                     => true,
				'hester_custom_input_style'                => true,
				'hester_preloader_heading'                 => false,
				'hester_preloader'                         => false,
				'hester_preloader_style'                   => '1',
				'hester_preloader_visibility'              => 'all',
				'hester_scroll_top_heading'                => false,
				'hester_enable_scroll_top'                 => true,
				'hester_enable_cursor_dot'                 => false,
				'hester_parallax_footer'                   => false,

				/**
				 * Logos & Site Title.
				 */
				'hester_logo_default_retina'               => '',
				'hester_logo_max_height'                   => array(
					'desktop' => 45,
				),
				'hester_logo_margin'                       => array(
					'desktop' => array(
						'top'    => 25,
						'right'  => 80,
						'bottom' => 25,
						'left'   => 0,
					),
					'tablet'  => array(
						'top'    => 25,
						'right'  => 1,
						'bottom' => 25,
						'left'   => 0,
					),
					'mobile'  => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
					'unit'    => 'px',
				),
				'hester_display_tagline'                   => false,
				'hester_logo_heading_site_identity'        => true,
				'hester_typography_logo_heading'           => false,
				'hester_logo_text_font_size'               => array(
					'desktop' => 3,
					'unit'    => 'rem',
				),

				/**
				 * Header.
				 */

				// Top Bar.
				'hester_top_bar_enable'                    => false,
				'hester_top_bar_container_width'           => 'content-width',
				'hester_top_bar_visibility'                => 'hide-mobile-tablet',
				'hester_top_bar_heading_widgets'           => true,
				'hester_top_bar_widgets'                   => array(
					array(
						'classname' => 'hester_customizer_widget_text',
						'type'      => 'text',
						'values'    => array(
							'content'    => esc_html__( 'This is a placeholder text widget in Top Bar section.', 'hester' ),
							'location'   => 'left',
							'visibility' => 'all',
						),
					),
				),
				'hester_top_bar_widgets_separator'         => 'regular',
				'hester_top_bar_heading_design_options'    => false,
				'hester_top_bar_background'                => hester_design_options_defaults(
					array(
						'background' => array(
							'color'    => array(
								'background-color' => '#FFFFFF',
							),
							'gradient' => array(),
						),
					)
				),
				'hester_top_bar_text_color'                => hester_design_options_defaults(
					array(
						'color' => array(),
					)
				),
				'hester_top_bar_border'                    => hester_design_options_defaults(
					array(
						'border' => array(
							'border-bottom-width' => '1',
							'border-style'        => 'solid',
							'border-color'        => 'rgba(0,0,0, .085)',
							'separator-color'     => '#cccccc',
						),
					)
				),

				// Main Header.
				'hester_header_layout'                     => 'layout-1',
				'hester_header_container_width'            => 'content-width',
				'hester_header_heading_widgets'            => true,
				'hester_header_widgets'                    => array(
					array(
						'classname' => 'hester_customizer_widget_search',
						'type'      => 'search',
						'values'    => array(
							'location'   => 'left',
							'visibility' => 'hide-mobile-tablet',
						),
					),
				),
				'hester_header_widgets_separator'          => 'slanted',
				'hester_header_heading_design_options'     => false,
				'hester_header_background'                 => hester_design_options_defaults(
					array(
						'background' => array(
							'color'    => array(
								'background-color' => '#FFFFFF',
							),
							'gradient' => array(),
							'image'    => array(),
						),
					)
				),
				'hester_header_border'                     => hester_design_options_defaults(
					array(
						'border' => array(
							'border-bottom-width' => 1,
							'border-color'        => 'rgba(0,0,0, .085)',
							'separator-color'     => '#cccccc',
						),
					)
				),
				'hester_header_text_color'                 => hester_design_options_defaults(
					array(
						'color' => array(
							'text-color' => '#66717f',
							'link-color' => '#232323',
						),
					)
				),

				// Transparent Header.
				'hester_tsp_header'                        => false,
				'hester_tsp_header_disable_on'             => array(
					'404',
					'posts_page',
					'archive',
					'search',
				),
				'hester_tsp_logo_heading'                  => false,
				'hester_tsp_logo'                          => '',
				'hester_tsp_logo_retina'                   => '',
				'hester_tsp_logo_max_height'               => array(
					'desktop' => 45,
				),
				'hester_tsp_logo_margin'                   => array(
					'desktop' => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
					'tablet'  => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
					'mobile'  => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
					'unit'    => 'px',
				),
				'hester_tsp_colors_heading'                => false,
				'hester_tsp_header_background'             => hester_design_options_defaults(
					array(
						'background' => array(
							'color' => array(),
						),
					)
				),
				'hester_tsp_header_font_color'             => hester_design_options_defaults(
					array(
						'color' => array(),
					)
				),
				'hester_tsp_header_border'                 => hester_design_options_defaults(
					array(
						'border' => array(),
					)
				),

				// Sticky Header.
				'hester_sticky_header'                     => false,
				'hester_sticky_header_hide_on'             => array( '' ),

				// Main Navigation.
				'hester_main_nav_heading_animation'        => false,
				'hester_main_nav_hover_animation'          => 'underline',
				'hester_main_nav_heading_sub_menus'        => false,
				'hester_main_nav_sub_indicators'           => true,
				'hester_main_nav_heading_mobile_menu'      => false,
				'hester_main_nav_mobile_breakpoint'        => 960,
				'hester_main_nav_mobile_label'             => '',
				'hester_nav_design_options'                => false,
				'hester_main_nav_background'               => hester_design_options_defaults(
					array(
						'background' => array(
							'color'    => array(
								'background-color' => '#FFFFFF',
							),
							'gradient' => array(),
						),
					)
				),
				'hester_main_nav_border'                   => hester_design_options_defaults(
					array(
						'border' => array(
							'border-top-width'    => 1,
							'border-bottom-width' => 1,
							'border-style'        => 'solid',
							'border-color'        => 'rgba(0,0,0, .085)',
						),
					)
				),
				'hester_main_nav_font_color'               => hester_design_options_defaults(
					array(
						'color' => array(),
					)
				),
				'hester_typography_main_nav_heading'       => false,
				'hester_main_nav_font_size'                => array(
					'value' => 1.7,
					'unit'  => 'rem',
				),

				// Page Header.
				'hester_page_header_enable'                => true,
				'hester_page_header_alignment'             => 'left',
				'hester_page_header_spacing'               => array(
					'desktop' => array(
						'top'    => 30,
						'bottom' => 30,
					),
					'tablet'  => array(
						'top'    => '',
						'bottom' => '',
					),
					'mobile'  => array(
						'top'    => '',
						'bottom' => '',
					),
					'unit'    => 'px',
				),
				'hester_page_header_background'            => hester_design_options_defaults(
					array(
						'background' => array(
							'color'    => array( 'background-color' => 'rgba(0,0,0,.025)' ),
							'gradient' => array(),
							'image'    => array(),
						),
					)
				),
				'hester_page_header_text_color'            => hester_design_options_defaults(
					array(
						'color' => array(),
					)
				),
				'hester_page_header_border'                => hester_design_options_defaults(
					array(
						'border' => array(
							'border-bottom-width' => 1,
							'border-style'        => 'solid',
							'border-color'        => 'rgba(0,0,0,.062)',
						),
					)
				),
				'hester_typography_page_header'            => false,
				'hester_page_header_font_size'             => array(
					'desktop' => 2.6,
					'unit'    => 'rem',
				),

				// Breadcrumbs.
				'hester_breadcrumbs_enable'                => true,
				'hester_breadcrumbs_hide_on'               => array( 'home' ),
				'hester_breadcrumbs_position'              => 'in-page-header',
				'hester_breadcrumbs_alignment'             => 'left',
				'hester_breadcrumbs_spacing'               => array(
					'desktop' => array(
						'top'    => 15,
						'bottom' => 15,
					),
					'tablet'  => array(
						'top'    => '',
						'bottom' => '',
					),
					'mobile'  => array(
						'top'    => '',
						'bottom' => '',
					),
					'unit'    => 'px',
				),
				'hester_breadcrumbs_heading_design'        => false,
				'hester_breadcrumbs_background'            => hester_design_options_defaults(
					array(
						'background' => array(
							'color'    => array(),
							'gradient' => array(),
							'image'    => array(),
						),
					)
				),
				'hester_breadcrumbs_text_color'            => hester_design_options_defaults(
					array(
						'color' => array(),
					)
				),
				'hester_breadcrumbs_border'                => hester_design_options_defaults(
					array(
						'border' => array(
							'border-top-width'    => 0,
							'border-bottom-width' => 0,
							'border-color'        => '',
							'border-style'        => 'solid',
						),
					)
				),

				/**
				 * Hero.
				 */
				'hester_enable_hero'                       => false,
				'hester_hero_type'                         => 'hover-slider',
				'hester_hero_visibility'                   => 'all',
				'hester_hero_enable_on'                    => array( 'home' ),
				'hester_hero_hover_slider'                 => false,
				'hester_hero_hover_slider_container'       => 'content-width',
				'hester_hero_hover_slider_height'          => 500,
				'hester_hero_hover_slider_overlay'         => '1',
				'hester_hero_hover_slider_elements'        => array(
					'category'  => true,
					'meta'      => true,
					'read_more' => true,
				),
				'hester_hero_hover_slider_posts'           => false,
				'hester_hero_hover_slider_post_number'     => 3,
				'hester_hero_hover_slider_category'        => array(),

				// About section.
				'hester_enable_about'                      => false,
				'hester_about_page'                        => '',

				/**
				 * Blog.
				 */

				// Blog Page / Archive.
				'hester_blog_entry_elements'               => array(
					'thumbnail'      => true,
					'header'         => true,
					'meta'           => true,
					'summary'        => true,
					'summary-footer' => true,
				),
				'hester_blog_entry_meta_elements'          => array(
					'author'   => true,
					'date'     => true,
					'category' => true,
					'tag'      => false,
					'comments' => true,
				),
				'hester_entry_meta_icons'                  => false,
				'hester_excerpt_length'                    => 30,
				'hester_excerpt_more'                      => '&hellip;',
				'hester_blog_layout'                       => 'blog-layout-1',
				'hester_blog_image_position'               => 'left',
				'hester_blog_image_size'                   => 'large',
				'hester_blog_horizontal_post_categories'   => true,
				'hester_blog_horizontal_read_more'         => false,

				// Single Post.
				'hester_single_post_layout_heading'        => false,
				'hester_single_title_position'             => 'in-content',
				'hester_single_title_alignment'            => 'left',
				'hester_single_title_spacing'              => array(
					'desktop' => array(
						'top'    => 152,
						'bottom' => 100,
					),
					'tablet'  => array(
						'top'    => 90,
						'bottom' => 55,
					),
					'mobile'  => array(
						'top'    => '',
						'bottom' => '',
					),
					'unit'    => 'px',
				),
				'hester_single_content_width'              => 'wide',
				'hester_single_narrow_container_width'     => 700,
				'hester_single_post_elements_heading'      => false,
				'hester_single_post_meta_elements'         => array(
					'author'   => true,
					'date'     => true,
					'comments' => true,
					'category' => false,
				),
				'hester_single_post_thumb'                 => true,
				'hester_single_post_categories'            => true,
				'hester_single_post_tags'                  => true,
				'hester_single_last_updated'               => true,
				'hester_single_about_author'               => true,
				'hester_single_post_next_prev'             => true,
				'hester_single_post_elements'              => array(
					'thumb'          => true,
					'category'       => true,
					'tags'           => true,
					'last-updated'   => true,
					'about-author'   => true,
					'prev-next-post' => true,
				),
				'hester_single_toggle_comments'            => false,
				'hester_single_entry_meta_icons'           => false,
				'hester_typography_single_post_heading'    => false,
				'hester_single_content_font_size'          => array(
					'desktop' => '1.6',
					'unit'    => 'rem',
				),

				/**
				 * Sidebar.
				 */

				'hester_sidebar_position'                  => 'right-sidebar',
				'hester_single_post_sidebar_position'      => 'no-sidebar',
				'hester_single_page_sidebar_position'      => 'default',
				'hester_archive_sidebar_position'          => 'default',
				'hester_sidebar_options_heading'           => false,
				'hester_sidebar_style'                     => '1',
				'hester_sidebar_width'                     => 25,
				'hester_sidebar_sticky'                    => '',
				'hester_sidebar_responsive_position'       => 'after-content',
				'hester_typography_sidebar_heading'        => false,
				'hester_sidebar_widget_title_font_size'    => array(
					'desktop' => 2.4,
					'unit'    => 'rem',
				),

				/**
				 * Footer.
				 */

				// Pre Footer.
				'hester_pre_footer_cta'                    => true,
				'hester_enable_pre_footer_cta'             => false,
				'hester_pre_footer_cta_visibility'         => 'all',
				'hester_pre_footer_cta_hide_on'            => array(),
				'hester_pre_footer_cta_style'              => '1',
				'hester_pre_footer_cta_text'               => wp_kses_post( __( 'This is an example of <em>Pre Footer</em> section in Hester.', 'hester' ) ),
				'hester_pre_footer_cta_btn_text'           => wp_kses_post( __( 'Example Button', 'hester' ) ),
				'hester_pre_footer_cta_btn_url'            => '#',
				'hester_pre_footer_cta_btn_new_tab'        => false,
				'hester_pre_footer_cta_design_options'     => false,
				'hester_pre_footer_cta_background'         => hester_design_options_defaults(
					array(
						'background' => array(
							'color'    => array(),
							'gradient' => array(),
							'image'    => array(),
						),
					)
				),
				'hester_pre_footer_cta_border'             => hester_design_options_defaults(
					array(
						'border' => array(),
					)
				),
				'hester_pre_footer_cta_text_color'         => hester_design_options_defaults(
					array(
						'color' => array(
							'text-color' => '#FFFFFF',
						),
					)
				),
				'hester_pre_footer_cta_typography'         => false,
				'hester_pre_footer_cta_font_size'          => array(
					'desktop' => 2.8,
					'unit'    => 'rem',
				),

				// Copyright.
				'hester_enable_copyright'                  => true,
				'hester_copyright_layout'                  => 'layout-1',
				'hester_copyright_separator'               => 'contained-separator',
				'hester_copyright_visibility'              => 'all',
				'hester_copyright_heading_widgets'         => true,
				'hester_copyright_widgets'                 => array(
					array(
						'classname' => 'hester_customizer_widget_text',
						'type'      => 'text',
						'values'    => array(
							'content'    => esc_html__( 'Copyright {{the_year}} &mdash; {{site_title}}. All rights reserved. {{theme_link}}', 'hester' ),
							'location'   => 'start',
							'visibility' => 'all',
						),
					),
				),
				'hester_copyright_heading_design_options'  => false,
				'hester_copyright_background'              => hester_design_options_defaults(
					array(
						'background' => array(
							'color'    => array(),
							'gradient' => array(),
						),
					)
				),
				'hester_copyright_text_color'              => hester_design_options_defaults(
					array(
						'color' => array(
							'text-color'       => '',
							'link-color'       => '',
							'link-hover-color' => '#FFFFFF',
						),
					)
				),

				// Main Footer.
				'hester_enable_footer'                     => true,
				'hester_footer_layout'                     => 'layout-1',
				'hester_footer_widgets_align_center'       => false,
				'hester_footer_visibility'                 => 'all',
				'hester_footer_widget_heading_style'       => '1',
				'hester_footer_heading_design_options'     => false,
				'hester_footer_background'                 => hester_design_options_defaults(
					array(
						'background' => array(
							'color'    => array(
								'background-color' => '#232323',
							),
							'gradient' => array(),
							'image'    => array(),
						),
					)
				),
				'hester_footer_text_color'                 => hester_design_options_defaults(
					array(
						'color' => array(
							'text-color'         => '#9BA1A7',
							'link-color'         => '',
							'link-hover-color'   => '#FFFFFF',
							'widget-title-color' => '#FFFFFF',
						),
					)
				),
				'hester_footer_border'                     => hester_design_options_defaults(
					array(
						'border' => array(),
					)
				),
				'hester_typography_main_footer_heading'    => false,
			);

			/**
			 * common options in all front page sections.
			 */
			$sections = hester_design_common_section();

			foreach ( $sections as $section ) {
				// service section padding
				$defaults[ "hester_{$section}_section_spacing" ] = array(
					'desktop' => array(
						'top'    => 10,
						'bottom' => 10,
					),
					'tablet'  => array(
						'top'    => 6,
						'bottom' => 6,
					),
					'mobile'  => array(
						'top'    => 6,
						'bottom' => 6,
					),
					'unit'    => 'rem',
				);
			}

			$defaults = apply_filters( 'hester_default_option_values', $defaults );
			return $defaults;
		}

		/**
		 * Get the options from static array()
		 *
		 * @since  1.0.0
		 * @return array    Return array of theme options.
		 */
		public function get_options() {
			return self::$options;
		}

		/**
		 * Get the options from static array().
		 *
		 * @since  1.0.0
		 * @param string $id Options jet to get.
		 * @return array Return array of theme options.
		 */
		public function get( $id ) {
			$value = isset( self::$options[ $id ] ) ? self::$options[ $id ] : self::get_default( $id );
			$value = apply_filters("theme_mod_{$id}", $value); // phpcs:ignore
			return $value;
		}

		/**
		 * Set option.
		 *
		 * @since  1.0.0
		 * @param string $id Option key.
		 * @param any    $value Option value.
		 * @return void
		 */
		public function set( $id, $value ) {
			set_theme_mod( $id, $value );
			self::$options[ $id ] = $value;
		}

		/**
		 * Refresh options.
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function refresh() {
			self::$options = wp_parse_args(
				get_theme_mods(),
				self::get_defaults()
			);
		}

		/**
		 * Returns the default value for option.
		 *
		 * @since  1.0.0
		 * @param  string $id Option ID.
		 * @return mixed      Default option value.
		 */
		public function get_default( $id ) {
			$defaults = self::get_defaults();
			return isset( $defaults[ $id ] ) ? $defaults[ $id ] : false;
		}
	}

endif;
