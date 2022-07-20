<?php
/**
 * Hester Customizer radio image control class.
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @see         https://github.com/aristath/kirki
 * @since       1.0.0
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Hester_Customizer_Control_Radio_Image' ) ) :
	/**
	 * Hester Customizer custom heading control class.
	 */
	class Hester_Customizer_Control_Radio_Image extends Hester_Customizer_Control {

		/**
		 * The control type.
		 *
		 * @var string
		 */
		public $type = 'hester-radio-image';

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @see WP_Customize_Control::to_json()
		 */
		public function to_json() {
			parent::to_json();

			$this->json['choices'] = $this->choices;
		}

		/**
		 * An Underscore (JS) template for this control's content (but not its container).
		 *
		 * Class variables for this control class are available in the `data` JS object;
		 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
		 *
		 * @see WP_Customize_Control::print_template()
		 */
		protected function content_template() {
			?>
			<div class="hester-control-wrapper">

				<# if ( data.label ) { #>
					<div class="customize-control-title hester-field">
						<span>{{{ data.label }}}</span>

						<# if ( data.description ) { #>
							<i class="hester-info-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-help-circle">
									<circle cx="12" cy="12" r="10"></circle>
									<path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
									<line x1="12" y1="17" x2="12" y2="17"></line>
								</svg>
								<span class="hester-tooltip">{{{ data.description }}}</span>
							</i>
						<# } #>
					</div>
				<# } #>

				<div class="hester-radio-image-choices">
					<# for ( key in data.choices ) { #>
						<input {{{ data.inputAttrs }}} class="hester-radio-image" type="radio" value="{{ key }}" name="hester_radio_group-{{ data.id }}" id="{{ data.id }}-{{ key }}" {{{ data.link }}}<# if ( data.value === key ) { #> checked="checked"<# } #>>
							<label for="{{ data.id }}-{{ key }}">
								<img src="{{ data.choices[ key ].image }}" />
								<span class="hester-image-title hester-tooltip small-tooltip">{{ data.choices[ key ].title }}</span>
							</label>
						</input>
					<# } #>
				</div><!-- END .hester-radio-image-choices -->

			</div><!-- END .hester-control-wrapper -->
			<?php
		}
	}
endif;
