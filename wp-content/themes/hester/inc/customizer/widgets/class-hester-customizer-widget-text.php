<?php
/**
 * Hester Customizer widgets class.
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

if ( ! class_exists( 'Hester_Customizer_Widget_Text' ) ) :

	/**
	 * Hester Customizer widget class
	 */
	class Hester_Customizer_Widget_Text extends Hester_Customizer_Widget {

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 * @param array $args An array of the values for this widget.
		 */
		public function __construct( $args = array() ) {

			$args['name']        = __( 'Text', 'hester' );
			$args['description'] = __( 'Arbitrary text.', 'hester' );
			$args['icon']        = 'dashicons dashicons-text';
			$args['type']        = 'text';

			$values = array(
				'content'    => esc_html__( 'Text widget content goes here...', 'hester' ),
				'visibility' => 'all',
			);

			$args['values'] = isset( $args['values'] ) ? wp_parse_args( $args['values'], $values ) : $values;

			$args['values']['content'] = wp_kses( $args['values']['content'], hester_get_allowed_html_tags() );

			parent::__construct( $args );
		}

		/**
		 * Displays the form for this widget on the Widgets page of the WP Admin area.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function form() {
			?>
			<p>
				<label for="widget-text-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-content"><?php esc_html_e( 'Content', 'hester' ); ?>:</label>
				<textarea class="widefat" id="widget-text-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-content" name="widget-text[<?php echo esc_attr( $this->number ); ?>][content]" data-option-name="content" rows="4"><?php echo wp_kses( $this->values['content'], hester_get_allowed_html_tags() ); ?></textarea>
				<span class="description">
					<?php
					echo wp_kses_post(
						sprintf(
							/* translators: %1$s is opening anchor tag, %2$s is a closing anchor tag. */
							__( 'Shortcodes and basic html elements allowed. See the list of %1$savailable dynamic strings%2$s.', 'hester' ),
							'<a href="' . esc_url( 'http://docs.peregrine-themes.com/' ) . '" target="_blank" rel="noopener noreferrer">',
							'</a>'
						)
					);
					?>

				</span>
			</p>
			<?php
		}
	}
endif;
