<?php
/**
 * Hester Customizer checkbox group control class.
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

if ( ! class_exists( 'Hester_Customizer_Control_Checkbox_Group' ) ) :
	/**
	 * Hester Customizer checkbox group control class.
	 */
	class Hester_Customizer_Control_Checkbox_Group extends Hester_Customizer_Control {

		/**
		 * The control type.
		 *
		 * @var string
		 */
		public $type = 'hester-checkbox-group';

		/**
		 * Link target.
		 *
		 * @since 1.0.0
		 * @var   string
		 */
		public $choices = array();

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
		 * @param string               $id      Control ID.
		 * @param array                $args    Default parent's arguments.
		 */
		public function __construct( $manager, $id, $args = array() ) { // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
			parent::__construct( $manager, $id, $args );
		}

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
			<div class="hester-checkbox-group-wrapper hester-control-wrapper">

				<# if ( data.label ) { #>
					<div class="hester-control-heading customize-control-title hester-field">
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

				<div class="hester-checkbox-group-control">

					<# _.each( data.choices, function( params, key ) { #>

						<p>
							<label class="hester-checkbox">
								<input type="checkbox" name="{{ data.id }}-{{ key }}" data-id="{{ key }}" <# if ( _.contains( data.value, key ) ) { #>checked="checked" <# } #>>
								<span class="hester-label">{{ params.title }}
									<# if ( params.desc ) { #>
									<i class="hester-info-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-help-circle">
											<circle cx="12" cy="12" r="10"></circle>
											<path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
											<line x1="12" y1="17" x2="12" y2="17"></line>
										</svg>
										<span class="hester-tooltip">{{ params.desc }}</span>
									</i>
									<# } #>
								</span>
							</label>
						</p>

					<# } ) #>

				</div>

			</div><!-- END .hester-button-wrapper -->
			<?php
		}

	}
endif;
