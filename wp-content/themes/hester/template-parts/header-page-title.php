<?php
/**
 * Template part for displaying page header.
 *
 * @package Hester
 * @author  Peregrine Themes
 * @since   1.0.0
 */

?>

<div <?php hester_page_header_classes(); ?><?php hester_page_header_atts(); ?>>
	<div class="hester-container">

	<?php do_action( 'hester_page_header_start' ); ?>

	<?php if ( hester_page_header_has_title() ) { ?>

		<div class="hester-page-header-wrapper">

			<div class="hester-page-header-title">
				<?php hester_page_header_title(); ?>
			</div>

			<?php $hester_description = apply_filters( 'hester_page_header_description', hester_get_the_description() ); ?>

			<?php if ( $hester_description ) { ?>

				<div class="hester-page-header-description">
					<?php echo wp_kses( $hester_description, hester_get_allowed_html_tags() ); ?>
				</div>

			<?php } ?>
		</div>

	<?php } ?>

	<?php do_action( 'hester_page_header_end' ); ?>

	</div>
</div>
