<?php
/**
 * Template part for displaying entry header.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
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

?>

<?php do_action( 'hester_before_entry_header' ); ?>
<header class="entry-header">

	<?php
	$hester_tag = is_single( get_the_ID() ) && ! hester_page_header_has_title() ? 'h1' : 'h4';
	$hester_tag = apply_filters( 'hester_entry_header_tag', $hester_tag );

	$hester_title_string = '%2$s%1$s';

	if ( 'link' === get_post_format() ) {
		$hester_title_string = '<a href="%3$s" title="%3$s" rel="bookmark">%2$s%1$s</a>';
	} elseif ( ! is_single( get_the_ID() ) ) {
		$hester_title_string = '<a href="%3$s" title="%4$s" rel="bookmark">%2$s%1$s</a>';
	}

	$hester_title_icon = apply_filters( 'hester_post_title_icon', '' );
	$hester_title_icon = hester()->icons->get_svg( $hester_title_icon );
	?>

	<<?php echo tag_escape( $hester_tag ); ?> class="entry-title"<?php hester_schema_markup( 'headline' ); ?>>
		<?php
		echo sprintf(
			wp_kses_post( $hester_title_string ),
			wp_kses_post( get_the_title() ),
			wp_kses_post( $hester_title_icon ),
			esc_url( hester_entry_get_permalink() ),
			the_title_attribute( array( 'echo' => false ) )
		);
		?>
	</<?php echo tag_escape( $hester_tag ); ?>>

</header>
<?php do_action( 'hester_after_entry_header' ); ?>
