<?php
/**
 * The template for displaying header navigation.
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.0.0
 */

?>

<nav class="site-navigation main-navigation hester-primary-nav hester-nav hester-header-element" role="navigation"<?php hester_schema_markup( 'site_navigation' ); ?> aria-label="<?php esc_attr_e( 'Site Navigation', 'hester' ); ?>">

<?php

if ( has_nav_menu( 'hester-primary' ) ) {
	wp_nav_menu(
		array(
			'theme_location' => 'hester-primary',
			'menu_id'        => 'hester-primary-nav',
			'container'      => '',
			'link_before'    => '<span>',
			'link_after'     => '</span>',
		)
	);
} else {
	wp_page_menu(
		array(
			'menu_class'  => 'hester-primary-nav',
			'show_home'   => true,
			'container'   => 'ul',
			'before'      => '',
			'after'       => '',
			'link_before' => '<span>',
			'link_after'  => '</span>',
		)
	);
}

?>
</nav><!-- END .hester-nav -->
