<?php
/**
 * Template part for displaying page header for single post.
 *
 * @package Hester
 * @author  Peregrine Themes
 * @since   1.0.0
 */

?>

<div <?php hester_page_header_classes(); ?><?php hester_page_header_atts(); ?>>

	<?php do_action( 'hester_page_header_start' ); ?>

	<?php if ( 'in-page-header' === hester_option( 'single_title_position' ) ) { ?>

		<div class="hester-container">
			<div class="hester-page-header-wrapper">

				<?php
				if ( hester_single_post_displays( 'category' ) ) {
					get_template_part( 'template-parts/entry/entry', 'category' );
				}

				if ( hester_page_header_has_title() ) {
					echo '<div class="hester-page-header-title">';
					hester_page_header_title();
					echo '</div>';
				}

				if ( hester_has_entry_meta_elements() ) {
					get_template_part( 'template-parts/entry/entry', 'meta' );
				}
				?>

			</div>
		</div>

	<?php } ?>

	<?php do_action( 'hester_page_header_end' ); ?>

</div>
