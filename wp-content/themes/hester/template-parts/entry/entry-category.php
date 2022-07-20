<?php
/**
 * Template part for displaying entry category.
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.0.0
 */

?>

<div class="post-category">

	<?php
	do_action( 'hester_before_post_category' );

	if ( is_singular() ) {
		hester_entry_meta_category( ' ', false );
	} else {
		if ( 'blog-horizontal' === hester_get_article_feed_layout() ) {
			hester_entry_meta_category( ' ', false );
		} else {
			hester_entry_meta_category( ', ', false );
		}
	}

	do_action( 'hester_after_post_category' );
	?>

</div>
