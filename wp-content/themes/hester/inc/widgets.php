<?php
/**
 * Widget customization and register sidebar widget areas.
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

if ( ! function_exists( 'hester_widgets_init' ) ) :
	/**
	 * Register widget area.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 * @since 1.0.0
	 */
	function hester_widgets_init() {

		$title_shape        = '<span class="title-shape"><span></span></span>';

		// Default Sidebar.
		register_sidebar(
			array(
				'name'          => esc_html__( 'Default Sidebar', 'hester' ),
				'id'            => 'hester-sidebar',
				'description'   => esc_html__( 'Widgets in this area are displayed in the left or right sidebar area based on your Default Sidebar Position settings.', 'hester' ),
				'before_widget' => '<div id="%1$s" class="hester-sidebar-widget hester-widget hester-entry widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="h4 widget-title">' . $title_shape . '',
				'after_title'   => '</div>',
			)
		);

		// Footer 1.
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer 1', 'hester' ),
				'id'            => 'hester-footer-1',
				'description'   => esc_html__( 'Widgets in this area are displayed in the first footer column.', 'hester' ),
				'before_widget' => '<div id="%1$s" class="hester-footer-widget hester-widget hester-entry widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="h4 widget-title">' . $title_shape . '',
				'after_title'   => '</div>',
			)
		);

		// Footer 2.
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer 2', 'hester' ),
				'id'            => 'hester-footer-2',
				'description'   => esc_html__( 'Widgets in this area are displayed in the second footer column.', 'hester' ),
				'before_widget' => '<div id="%1$s" class="hester-footer-widget hester-widget hester-entry widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="h4 widget-title">' . $title_shape . '',
				'after_title'   => '</div>',
			)
		);

		// Footer 3.
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer 3', 'hester' ),
				'id'            => 'hester-footer-3',
				'description'   => esc_html__( 'Widgets in this area are displayed in the third footer column.', 'hester' ),
				'before_widget' => '<div id="%1$s" class="hester-footer-widget hester-widget hester-entry widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="h4 widget-title">' . $title_shape . '',
				'after_title'   => '</div>',
			)
		);

		// Footer 4.
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer 4', 'hester' ),
				'id'            => 'hester-footer-4',
				'description'   => esc_html__( 'Widgets in this area are displayed in the fourth footer column.', 'hester' ),
				'before_widget' => '<div id="%1$s" class="hester-footer-widget hester-widget hester-entry widget %2$s clr">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="h4 widget-title">' . $title_shape . '',
				'after_title'   => '</div>',
			)
		);
	}
endif;
add_action( 'widgets_init', 'hester_widgets_init' );
