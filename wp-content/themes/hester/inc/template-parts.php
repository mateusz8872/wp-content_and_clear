<?php

/**
 * Template parts.
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

/**
 * Adds the meta tag to the site header.
 *
 * @since 1.0.0
 */
function hester_meta_viewport() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
}
add_action( 'wp_head', 'hester_meta_viewport', 1 );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 *
 * @since 1.0.0
 */
function hester_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'hester_pingback_header' );

/**
 * Adds the meta tag for website accent color.
 *
 * @since 1.0.0
 */
function hester_meta_theme_color() {

	$color = hester_option( 'accent_color' );

	if ( $color ) {
		printf( '<meta name="theme-color" content="%s">', esc_attr( $color ) );
	}
}
add_action( 'wp_head', 'hester_meta_theme_color' );

/**
 * Outputs the theme top bar area.
 *
 * @since 1.0.0
 */
function hester_topbar_output() {

	if ( ! hester_is_top_bar_displayed() ) {
		return;
	}

	get_template_part( 'template-parts/topbar/topbar' );
}
add_action( 'hester_header', 'hester_topbar_output', 10 );

/**
 * Outputs the top bar widgets.
 *
 * @since 1.0.0
 * @param string $location Widget location in top bar.
 */
function hester_topbar_widgets_output( $location ) {

	do_action( 'hester_top_bar_widgets_before_' . $location );

	$hester_top_bar_widgets = hester_option( 'top_bar_widgets' );

	if ( is_array( $hester_top_bar_widgets ) && ! empty( $hester_top_bar_widgets ) ) {
		foreach ( $hester_top_bar_widgets as $widget ) {

			if ( ! isset( $widget['values'] ) ) {
				continue;
			}

			if ( $location !== $widget['values']['location'] ) {
				continue;
			}

			if ( function_exists( 'hester_top_bar_widget_' . $widget['type'] ) ) {

				$classes   = array();
				$classes[] = 'hester-topbar-widget__' . esc_attr( $widget['type'] );
				$classes[] = 'hester-topbar-widget';

				if ( isset( $widget['values']['visibility'] ) && $widget['values']['visibility'] ) {
					$classes[] = 'hester-' . esc_attr( $widget['values']['visibility'] );
				}

				$classes = apply_filters( 'hester_topbar_widget_classes', $classes, $widget );
				$classes = trim( implode( ' ', $classes ) );

				printf( '<div class="%s">', esc_attr( $classes ) );
				call_user_func( 'hester_top_bar_widget_' . $widget['type'], $widget['values'] );
				printf( '</div><!-- END .hester-topbar-widget -->' );
			}
		}
	}

	do_action( 'hester_top_bar_widgets_after_' . $location );
}
add_action( 'hester_topbar_widgets', 'hester_topbar_widgets_output' );

/**
 * Outputs the theme header area.
 *
 * @since 1.0.0
 */
function hester_header_output() {

	if ( ! hester_is_header_displayed() ) {
		return;
	}

	get_template_part( 'template-parts/header/base' );
}
add_action( 'hester_header', 'hester_header_output', 20 );

/**
 * Outputs the header widgets in Header Widget Locations.
 *
 * @since 1.0.0
 * @param string $locations Widget location.
 */
function hester_header_widgets( $locations ) {

	$locations   = (array) $locations;
	$all_widgets = (array) hester_option( 'header_widgets' );

	$header_widgets = $all_widgets;
	$header_class   = '';

	if ( ! empty( $locations ) ) {

		$header_widgets = array();

		foreach ( $locations as $location ) {

			$header_class = ' hester-widget-location-' . $location;

			$header_widgets[ $location ] = array();

			if ( ! empty( $all_widgets ) ) {
				foreach ( $all_widgets as $i => $widget ) {
					if ( $location === $widget['values']['location'] ) {
						$header_widgets[ $location ][] = $widget;
					}
				}
			}
		}
	}

	echo '<div class="hester-header-widgets hester-header-element' . esc_attr( $header_class ) . '">';

	if ( ! empty( $header_widgets ) ) {
		foreach ( $header_widgets as $location => $widgets ) {

			do_action( 'hester_header_widgets_before_' . $location );

			if ( ! empty( $widgets ) ) {
				foreach ( $widgets as $widget ) {
					if ( function_exists( 'hester_header_widget_' . $widget['type'] ) ) {

						$classes   = array();
						$classes[] = 'hester-header-widget__' . esc_attr( $widget['type'] );
						$classes[] = 'hester-header-widget';

						if ( isset( $widget['values']['visibility'] ) && $widget['values']['visibility'] ) {
							$classes[] = 'hester-' . esc_attr( $widget['values']['visibility'] );
						}

						$classes = apply_filters( 'hester_header_widget_classes', $classes, $widget );
						$classes = trim( implode( ' ', $classes ) );

						printf( '<div class="%s"><div class="hester-widget-wrapper">', esc_attr( $classes ) );
						call_user_func( 'hester_header_widget_' . $widget['type'], $widget['values'] );
						printf( '</div></div><!-- END .hester-header-widget -->' );
					}
				}
			}

			do_action( 'hester_header_widgets_after_' . $location );
		}
	}

	echo '</div><!-- END .hester-header-widgets -->';
}
add_action( 'hester_header_widget_location', 'hester_header_widgets', 1 );

/**
 * Outputs the content of theme header.
 *
 * @since 1.0.0
 */
function hester_header_content_output() {

	// Get the selected header layout from Customizer.
	$header_layout = hester_option( 'header_layout' );

	?>
	<div id="hester-header-inner">
		<?php

		// Load header layout template.
		get_template_part( 'template-parts/header/header', $header_layout );

		?>
	</div><!-- END #hester-header-inner -->
	<?php
}
add_action( 'hester_header_content', 'hester_header_content_output' );

/**
 * Outputs the main footer area.
 *
 * @since 1.0.0
 */
function hester_footer_output() {

	if ( ! hester_is_footer_displayed() ) {
		return;
	}

	get_template_part( 'template-parts/footer/base' );
}
add_action( 'hester_footer', 'hester_footer_output', 20 );

/**
 * Outputs the copyright area.
 *
 * @since 1.0.0
 */
function hester_copyright_bar_output() {

	if ( ! hester_is_copyright_bar_displayed() ) {
		return;
	}

	get_template_part( 'template-parts/footer/copyright/copyright' );
}
add_action( 'hester_footer', 'hester_copyright_bar_output', 30 );

/**
 * Outputs the copyright widgets.
 *
 * @since 1.0.0
 * @param string $location Widget location in copyright.
 */
function hester_copyright_widgets_output( $location ) {

	do_action( 'hester_copyright_widgets_before_' . $location );

	$hester_widgets = hester_option( 'copyright_widgets' );

	if ( is_array( $hester_widgets ) && ! empty( $hester_widgets ) ) {
		foreach ( $hester_widgets as $widget ) {

			if ( ! isset( $widget['values'] ) ) {
				continue;
			}

			if ( isset( $widget['values'], $widget['values']['location'] ) && $location !== $widget['values']['location'] ) {
				continue;
			}

			if ( function_exists( 'hester_copyright_widget_' . $widget['type'] ) ) {

				$classes   = array();
				$classes[] = 'hester-copyright-widget__' . esc_attr( $widget['type'] );
				$classes[] = 'hester-copyright-widget';

				if ( isset( $widget['values']['visibility'] ) && $widget['values']['visibility'] ) {
					$classes[] = 'hester-' . esc_attr( $widget['values']['visibility'] );
				}

				$classes = apply_filters( 'hester_copyright_widget_classes', $classes, $widget );
				$classes = trim( implode( ' ', $classes ) );

				printf( '<div class="%s">', esc_attr( $classes ) );
				call_user_func( 'hester_copyright_widget_' . $widget['type'], $widget['values'] );
				printf( '</div><!-- END .hester-copyright-widget -->' );
			}
		}
	}

	do_action( 'hester_copyright_widgets_after_' . $location );
}
add_action( 'hester_copyright_widgets', 'hester_copyright_widgets_output' );

/**
 * Outputs the theme sidebar area.
 *
 * @since 1.0.0
 */
function hester_sidebar_output() {

	if ( hester_is_sidebar_displayed() ) {
		get_sidebar();
	}
}
add_action( 'hester_sidebar', 'hester_sidebar_output' );

/**
 * Outputs the back to top button.
 *
 * @since 1.0.0
 */
function hester_back_to_top_output() {

	if ( ! hester_option( 'enable_scroll_top' ) ) {
		return;
	}

	get_template_part( 'template-parts/misc/back-to-top' );
}
add_action( 'hester_after_page_wrapper', 'hester_back_to_top_output' );

/**
 * Outputs the cursor dot.
 *
 * @since 1.0.0
 */
function hester_cursor_dot_output() {

	if ( ! hester_option( 'enable_cursor_dot' ) ) {
		return;
	}

	get_template_part( 'template-parts/misc/cursor-dot' );
}
add_action( 'hester_after_page_wrapper', 'hester_cursor_dot_output' );

/**
 * Outputs the theme page content.
 *
 * @since 1.0.0
 */
function hester_page_header_template() {

	do_action( 'hester_before_page_header' );

	if ( hester_is_page_header_displayed() ) {
		if ( is_singular( 'post' ) ) {
			get_template_part( 'template-parts/header-page-title-single' );
		} else {
			get_template_part( 'template-parts/header-page-title' );
		}
	}

	do_action( 'hester_after_page_header' );
}
add_action( 'hester_page_header', 'hester_page_header_template' );

/**
 * Outputs the theme hero content.
 *
 * @since 1.0.0
 */
function hester_blog_hero() {

	if ( ! hester_is_hero_displayed() ) {
		return;
	}

	// Hero type.
	$hero_type = hester_option( 'hero_type' );

	do_action( 'hester_before_hero' );

	// Enqueue Hester Slider script.
	wp_enqueue_script( 'hester-slider' );

	?>
	<div id="hero" <?php hester_hero_classes(); ?>>
		<?php get_template_part( 'template-parts/hero/hero', $hero_type ); ?>
	</div><!-- END #hero -->
	<?php

	do_action( 'hester_after_hero' );
}
add_action( 'hester_after_masthead', 'hester_blog_hero', 30 );

/**
 * Outputs the queried articles.
 *
 * @since 1.0.0
 */
function hester_content() {

	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content/content', hester_get_article_feed_layout() );
		endwhile;

		hester_pagination();

	else :
		get_template_part( 'template-parts/content/content', 'none' );
	endif;
}
add_action( 'hester_content', 'hester_content' );
add_action( 'hester_content_archive', 'hester_content' );
add_action( 'hester_content_search', 'hester_content' );

/**
 * Outputs the theme single content.
 *
 * @since 1.0.0
 */
function hester_content_singular() {

	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();

			if ( is_singular( 'post' ) ) {
				do_action( 'hester_content_single' );
			} else {
				do_action( 'hester_content_page' );
			}

		endwhile;
	else :
		get_template_part( 'template-parts/content/content', 'none' );
	endif;
}
add_action( 'hester_content_singular', 'hester_content_singular' );

/**
 * Outputs the theme 404 page content.
 *
 * @since 1.0.0
 */
function hester_404_page_content() {

	get_template_part( 'template-parts/content/content', '404' );
}
add_action( 'hester_content_404', 'hester_404_page_content' );

/**
 * Outputs the theme page content.
 *
 * @since 1.0.0
 */
function hester_content_page() {

	get_template_part( 'template-parts/content/content', 'page' );
}
add_action( 'hester_content_page', 'hester_content_page' );

/**
 * Outputs the theme single post content.
 *
 * @since 1.0.0
 */
function hester_content_single() {

	get_template_part( 'template-parts/content/content', 'single' );
}
add_action( 'hester_content_single', 'hester_content_single' );

/**
 * Outputs the comments template.
 *
 * @since 1.0.0
 */
function hester_output_comments() {
	comments_template();
}
add_action( 'hester_after_singular', 'hester_output_comments' );

/**
 * Outputs the theme archive page info.
 *
 * @since 1.0.0
 */
function hester_archive_info() {

	// Author info.
	if ( is_author() ) {
		get_template_part( 'template-parts/entry/entry', 'about-author' );
	}
}
add_action( 'hester_before_content', 'hester_archive_info' );

/**
 * Outputs more posts button to author description box.
 *
 * @since 1.0.0
 */
function hester_add_author_posts_button() {
	if ( ! is_author() ) {
		get_template_part( 'template-parts/entry/entry', 'author-posts-button' );
	}
}
add_action( 'hester_entry_after_author_description', 'hester_add_author_posts_button' );

/**
 * Outputs Comments Toggle button.
 *
 * @since 1.0.0
 */
function hester_comments_toggle() {

	if ( hester_comments_toggle_displayed() ) {
		get_template_part( 'template-parts/entry/entry-show-comments' );
	}
}
add_action( 'hester_before_comments', 'hester_comments_toggle' );

/**
 * Outputs Pre-Footer area.
 *
 * @since 1.0.0
 */
function hester_pre_footer() {

	if ( ! hester_is_pre_footer_displayed() ) {
		return;
	}

	get_template_part( 'template-parts/pre-footer/base' );
}
add_action( 'hester_before_colophon', 'hester_pre_footer' );

/**
 * Outputs Page Preloader.
 *
 * @since 1.0.0
 */
function hester_preloader() {

	if ( ! hester_is_preloader_displayed() ) {
		return;
	}

	get_template_part( 'template-parts/preloader/base' );
}
add_action( 'hester_before_page_wrapper', 'hester_preloader' );

/**
 * Outputs breadcrumbs after header.
 *
 * @since  1.1.0
 * @return void
 */
function hester_breadcrumb_after_header_output() {

	if ( 'below-header' === hester_option( 'breadcrumbs_position' ) && hester_has_breadcrumbs() ) {

		$alignment = 'hester-text-align-' . hester_option( 'breadcrumbs_alignment' );

		$args = array(
			'container_before' => '<div class="hester-breadcrumbs"><div class="hester-container ' . $alignment . '">',
			'container_after'  => '</div></div>',
		);

		hester_breadcrumb( $args );
	}
}
add_action( 'hester_main_start', 'hester_breadcrumb_after_header_output' );

/**
 * Outputs breadcumbs in page header.
 *
 * @since  1.1.0
 * @return void
 */
function hester_breadcrumb_page_header_output() {

	if ( hester_page_header_has_breadcrumbs() ) {

		if ( is_singular( 'post' ) ) {
			$args = array(
				'container_before' => '<div class="hester-container hester-breadcrumbs">',
				'container_after'  => '</div>',
			);
		} else {
			$args = array(
				'container_before' => '<div class="hester-breadcrumbs">',
				'container_after'  => '</div>',
			);
		}

		hester_breadcrumb( $args );
	}
}
add_action( 'hester_page_header_end', 'hester_breadcrumb_page_header_output' );

/**
 * Replace tranparent header logo.
 *
 * @since  1.1.1
 * @param  string $output Current logo markup.
 * @return string         Update logo markup.
 */
function hester_transparent_header_logo( $output ) {

	// Check if transparent header is displayed.
	if ( hester_is_header_transparent() ) {

		// Check if transparent logo is set.
		$logo = hester_option( 'tsp_logo' );
		$logo = isset( $logo['background-image-id'] ) ? $logo['background-image-id'] : false;

		$retina = hester_option( 'tsp_logo_retina' );
		$retina = isset( $retina['background-image-id'] ) ? $retina['background-image-id'] : false;

		if ( $logo ) {
			$output = hester_get_logo_img_output( $logo, $retina, 'hester-tsp-logo' );
		}
	}

	return $output;
}
add_filter( 'hester_logo_img_output', 'hester_transparent_header_logo' );
add_filter( 'hester_site_title_markup', 'hester_transparent_header_logo' );

/**
 * Output the main navigation template.
 */
function hester_main_navigation_template() {
	get_template_part( 'template-parts/header/navigation' );
}

/**
 * Output the Header logo template.
 */
function hester_header_logo_template() {
	get_template_part( 'template-parts/header/logo' );
}

if ( ! function_exists( 'hester_display_customizer_shortcut' ) ) {
	/**
	 * This function display a shortcut to a customizer control.
	 *
	 * @param string $class_name The name of control we want to link this shortcut with.
	 * @param bool   $is_section_toggle Tells function to display eye icon if it's true.
	 */
	function hester_display_customizer_shortcut( $class_name, $is_section_toggle = false, $should_return = false ) {
		if ( ! is_customize_preview() ) {
			return;
		}
		$icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
				<path d="M13.89 3.39l2.71 2.72c.46.46.42 1.24.03 1.64l-8.01 8.02-5.56 1.16 1.16-5.58s7.6-7.63 7.99-8.03c.39-.39 1.22-.39 1.68.07zm-2.73 2.79l-5.59 5.61 1.11 1.11 5.54-5.65zm-2.97 8.23l5.58-5.6-1.07-1.08-5.59 5.6z"></path>
			</svg>';
		if ( $is_section_toggle ) {
			$icon = '<i class="far fa-eye"></i>';
		}

		$data = '<span class="hester-hide-section-shortcut customize-partial-edit-shortcut customize-partial-edit-shortcut-' . esc_attr( $class_name ) . '">
		<button class="customize-partial-edit-shortcut-button">
			' . $icon . '
		</button>
	</span>';
		if ( $should_return === true ) {
			return $data;
		}
		echo $data;
	}
}

function hester_about_button() {
	$button_widgets = hester_option( 'about_widgets' );

	if ( empty( $button_widgets ) ) {
		return;
	}
	foreach ( $button_widgets as $widget ) {
		call_user_func( 'hester_about_widget_' . $widget['type'], $widget['values'] );
	}
}

function hester_cta_widgets() {
	$widgets = hester_option( 'cta_widgets' );

	if ( empty( $widgets ) ) {
		return;
	}
	foreach ( $widgets as $widget ) {
		call_user_func( 'hester_cta_widget_' . $widget['type'], $widget['values'] );
	}
}

/**
 * Outputs the content of theme Service.
 *
 * @since 1.0.0
 */
function hester_service_content_output( $args ) {
	$args = (object) $args;
	// Get the selected service layout from Customizer.
	$services_style = hester_option( 'services_style' );

	// Load service layout template.
	get_template_part( 'template-parts/components/service/service-layout', $services_style, $args );

}
add_action( 'hester_service_content', 'hester_service_content_output', 10, 1 );

/**
 * Outputs the content of theme Info.
 *
 * @since 1.0.0
 */
function hester_info_content_output( $args ) {
	$args = (object) $args;
	// Get the selected info layout from Customizer.
	$info_style = hester_option( 'info_style' );

	// Load info layout template.
	get_template_part( 'template-parts/components/info/info-layout', $info_style, $args );

}
add_action( 'hester_info_content', 'hester_info_content_output', 10, 1 );

/**
 * Outputs the content of theme Team.
 *
 * @since 1.0.0
 */
function hester_team_content_output( $args ) {
	$args = (object) $args;
	// Get the selected team layout from Customizer.
	$team_style = hester_option( 'team_style' );

	// Load team layout template.
	get_template_part( 'template-parts/components/team/team-layout', $team_style, $args );

}
add_action( 'hester_team_content', 'hester_team_content_output', 10, 1 );

/**
 * Outputs the content of theme Features.
 *
 * @since 1.0.0
 */
function hester_features_content_output( $args ) {
	$args = (object) $args;
	// Get the selected features layout from Customizer.
	$features_style = hester_option( 'features_style' );

	// Load features layout template.
	get_template_part( 'template-parts/components/features/features-layout', $features_style, $args );

}
add_action( 'hester_features_content', 'hester_features_content_output', 10, 1 );
