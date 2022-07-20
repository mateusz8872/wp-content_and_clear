<?php
/**
 * Class for sections that are hiding.
 *
 * @since 1.0.0
 * @package hester
 */

/**
 * Class Hester_Hiding_Section
 *
 * @since  1.0.0
 * @access public
 */
class Hester_Customizer_Control_Section_Hiding extends WP_Customize_Section {

	/**
	 * The type of customize section being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'hester-section-hiding';

	/**
	 * Flag to display icon when entering in customizer
	 *
	 * @since  1.0.0
	 * @access public
	 * @var bool
	 */
	public $visible;

	/**
	 * Name of customizer hiding control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var bool
	 */
	public $hiding_control;

	/**
	 * Id of customizer hiding control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var integer
	 */
	public $id;


	/**
	 * Hester_Hiding_Section constructor.
	 *
	 * @param WP_Customize_Manager $manager Customizer Manager.
	 * @param string               $id Control id.
	 * @param array                $args Arguments.
	 */
	public function __construct( WP_Customize_Manager $manager, $id, array $args = array() ) {
		parent::__construct( $manager, $id, $args );

		if ( ! empty( $args['hiding_control'] ) ) {
			$this->visible = get_theme_mod( $args['hiding_control'] );
		}

		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {

		// Script debug.
		$hester_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Control type.
		$hester_type = str_replace( 'hester-', '', $this->type );

		/**
		 * Enqueue control stylesheet
		 */
		wp_enqueue_style(
			'hester-' . $hester_type . '-control-style',
			HESTER_THEME_URI . '/inc/customizer/controls/' . $hester_type . '/' . $hester_type . $hester_suffix . '.css',
			false,
			HESTER_THEME_VERSION,
			'all'
		);

		wp_enqueue_script(
			'hester-' . $hester_type . '-control',
			HESTER_THEME_URI . '/inc/customizer/controls/' . $hester_type . '/' . $hester_type . $hester_suffix . '.js',
			array( 'jquery' ),
			HESTER_THEME_VERSION,
			true
		);

		wp_enqueue_script(
			'hester-' . $hester_type . '-ordering-control',
			HESTER_THEME_URI . '/inc/customizer/controls/' . $hester_type . '/section-ordering' . $hester_suffix . '.js',
			array( 'jquery', 'jquery-ui-sortable', 'customize-controls' ),
			HESTER_THEME_VERSION,
			true
		);

		$control_settings = array(
			'sections_container' => '#accordion-panel-hester_panel_homepage > ul, #sub-accordion-panel-hester_panel_homepage',
			'blocked_items'      => '#accordion-section-hester_section_slider',
			'saved_data_input'   => '#customize-control-hester_sections_order input',
		);
		wp_localize_script( 'hester-' . $hester_type . '-ordering-control', 'control_settings', $control_settings );
		wp_localize_script( 'hester-' . $hester_type . '-control', 'hester_customizer_sections', array( 'sections' => hester_home_sections() ) );
	}

	/**
	 * Add custom parameters to pass to the JS via JSON.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function json() {
		$json                   = parent::json();
		$json['visible']        = $this->visible;
		$json['hiding_control'] = $this->hiding_control;
		return $json;
	}

	/**
	 * Outputs the Underscore.js template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	protected function render_template() {
		?>
		<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }}">
			<h3 class="accordion-section-title <# if ( data.visible ) { #> hester-section-visible <# } else { #> hester-section-hidden <# }#>" tabindex="0">
				{{ data.title }}
				<# if ( data.visible ) { #>
					<a data-control="{{ data.hiding_control }}" class="hester-toggle-section" href="#"><span class="dashicons dashicons-visibility"></span></a>
				<# } else { #>
					<a data-control="{{ data.hiding_control }}" class="hester-toggle-section" href="#"><span class="dashicons dashicons-hidden"></span></a>
				<# } #>
			</h3>
			<ul class="accordion-section-content">
				<li class="customize-section-description-container section-meta <# if ( data.description_hidden ) { #>customize-info<# } #>">
					<div class="customize-section-title">
						<button class="customize-section-back" tabindex="-1">
						</button>
						<h3>
							<span class="customize-action">
								{{{ data.customizeAction }}}
							</span>
							{{ data.title }}
						</h3>
						<# if ( data.description && data.description_hidden ) { #>
							<button type="button" class="customize-help-toggle dashicons dashicons-editor-help" aria-expanded="false"></button>
							<div class="description customize-section-description">
								{{{ data.description }}}
							</div>
							<# } #>
					</div>

					<# if ( data.description && ! data.description_hidden ) { #>
						<div class="description customize-section-description">
							{{{ data.description }}}
						</div>
						<# } #>
				</li>
			</ul>
		</li>
		<?php
	}
}
