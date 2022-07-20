<?php
/**
 * The header for our theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.0.0
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?><?php hester_schema_markup( 'html' ); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php do_action( 'hester_before_page_wrapper' ); ?>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'hester' ); ?></a>

	<?php do_action( 'hester_before_masthead' ); ?>

	<header id="masthead" class="site-header" role="banner"<?php hester_masthead_atts(); ?><?php hester_schema_markup( 'header' ); ?>>
		<?php do_action( 'hester_header' ); ?>
		<?php do_action( 'hester_page_header' ); ?>
	</header><!-- #masthead .site-header -->

	<?php do_action( 'hester_after_masthead' ); ?>

	<?php do_action( 'hester_before_main' ); ?>
		<div id="main" class="site-main">

			<?php do_action( 'hester_main_start' ); ?>
