<?php
/**
 * Template part for displaying entry content.
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

<?php do_action( 'hester_before_entry_content' ); ?>
<div class="entry-content hester-entry"<?php hester_schema_markup( 'text' ); ?>>
	<?php the_content(); ?>
</div>

<?php hester_link_pages(); ?>

<?php do_action( 'hester_after_entry_content' ); ?>
