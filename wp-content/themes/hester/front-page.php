<?php

/**
 * The front page template file.
 *
 * If the user has selected a static page for their homepage, this is what will
 * appear.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Hester
 * @since Hester 1.0
 */

if ((!is_page_template() || get_option('fresh_site')) && get_theme_mod('hester_enable_front_page', false) && class_exists('Hester_Core')) {
	get_header();
	/**
	 * Hester before front page sections hook.
	 *
	 * @hooked hester_before_front_page_sections
	 */

	do_action('hester_before_front_page_sections', false); ?>

	<div class="hester-frontpage-sections"><?php

		do_action('hester_before_home_order_sections', false);
		/**
		 * Hester Sections hook.
		 *
		 * @hooked hester_info_section - 1
		 * @hooked hester_services_section - 2
		 * @hooked hester_extra_section - 3
		 * @hooked hester_features_section - 4
		 * @hooked hester_products_section - 5
		 * @hooked hester_blog_section - 6
		 */
		do_action('hester_sections', false);

		do_action('hester_after_home_order_sections', false); ?>
	</div>

	<?php
	/**
	 * After front page sections hook
	 *
	 * @hook hester_after_front_page_sections
	 */
	do_action('hester_after_front_page_sections', false);
	get_footer();
	
} else {
	if (is_page_template('page-templates/template-hester-fullwidth.php')) {
		get_template_part('page-templates/template-hester-fullwidth');
	} elseif (is_page()) {
		get_template_part('page');
	} else {
		get_template_part('index');
	}
}
?>