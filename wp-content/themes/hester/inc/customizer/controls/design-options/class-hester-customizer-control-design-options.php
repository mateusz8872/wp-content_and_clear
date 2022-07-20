<?php
/**
 * Hester Customizer design options control class.
 *
 * @package Hester
 * @author  Peregrine Themes
 * @since   1.0.0
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Hester_Customizer_Control_Design_Options' ) ) :
	/**
	 * Hester Customizer design options control class.
	 */
	class Hester_Customizer_Control_Design_Options extends Hester_Customizer_Control {

		/**
		 * The control type.
		 *
		 * @var string
		 */
		public $type = 'hester-design-options';

		/**
		 * The displayed fields.
		 *
		 * @var string
		 */
		public $display = array();

		/**
		 * The control icon.
		 *
		 * @var string
		 */
		public $icon = 'edit';

		/**
		 * Set the default options.
		 *
		 * @since 1.0.8
		 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
		 * @param string               $id      Control ID.
		 * @param array                $args    Default parent's arguments.
		 */
		public function __construct( $manager, $id, $args = array() ) {

			$this->display = array(
				'background' => array(),
				'color'      => array(),
				'border'     => array(),
			);

			parent::__construct( $manager, $id, $args );
		}

		/**
		 * Enqueue control related scripts/styles.
		 *
		 * @access public
		 */
		public function enqueue() {

			parent::enqueue();

			// Script debug.
			$hester_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			// Enqueue WordPress color picker styles.
			wp_enqueue_style( 'wp-color-picker' );

			// Enqueue background image stylesheet.
			if ( isset( $this->display['background'] ) ) {
				wp_enqueue_style(
					'hester-background-control-style',
					HESTER_THEME_URI . '/inc/customizer/controls/background/background' . $hester_suffix . '.css',
					false,
					HESTER_THEME_VERSION,
					'all'
				);
			}
		}

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @see WP_Customize_Control::to_json()
		 */
		public function to_json() {
			parent::to_json();

			$this->json['display'] = $this->display;
			$this->json['icon']    = $this->icon;

			$this->json['l10n'] = array(
				'background-type' => esc_html__( 'Type', 'hester' ),
				'gradient'        => array(
					'color-1'         => esc_html__( 'Color 1', 'hester' ),
					'color-2'         => esc_html__( 'Color 2', 'hester' ),
					'color-location'  => esc_html__( 'Location', 'hester' ),
					'type'            => esc_html__( 'Type', 'hester' ),
					'linear'          => esc_html__( 'Linear', 'hester' ),
					'radial'          => esc_html__( 'Radial', 'hester' ),
					'angle'           => esc_html__( 'Angle', 'hester' ),
					'position'        => esc_html__( 'Position', 'hester' ),
					'radial-position' => array(
						'center center' => esc_html__( 'Center Center', 'hester' ),
						'center left'   => esc_html__( 'Center Left', 'hester' ),
						'center right'  => esc_html__( 'Center Right', 'hester' ),
						'top center'    => esc_html__( 'Top Center', 'hester' ),
						'top left'      => esc_html__( 'Top Left', 'hester' ),
						'top right'     => esc_html__( 'Top Right', 'hester' ),
						'bottom center' => esc_html__( 'Bottom Center', 'hester' ),
						'bottom left'   => esc_html__( 'Bottom Left', 'hester' ),
						'bottom right'  => esc_html__( 'Bottom Right', 'hester' ),
					),
				),
				'image'           => array(
					'placeholder'  => __( 'No image selected', 'hester' ),
					'less'         => __( 'Less Settings', 'hester' ),
					'more'         => __( 'Advanced', 'hester' ),
					'select_image' => __( 'Select Image', 'hester' ),
					'use_image'    => __( 'Use This Image', 'hester' ),
				),
				'border-styles'   => array(
					'solid'  => esc_html__( 'Solid', 'hester' ),
					'dotted' => esc_html__( 'Dotted', 'hester' ),
					'dashed' => esc_html__( 'Dashed', 'hester' ),
				),
			);
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
			<div class="hester-design-options-wrapper hester-popup-options hester-control-wrapper">

				<div class="hester-design-options-heading">
					<# if ( data.label ) { #>
						<span class="customize-control-title">{{{ data.label }}}</span>
					<# } #>

					<# if ( data.description ) { #>
						<span class="description customize-control-description">{{{ data.description }}}</span>
					<# } #>
				</div>

				<a href="#" class="reset-defaults">
					<span class="dashicons dashicons-image-rotate"></span>
				</a>

				<a href="#" class="popup-link">
					<span class="dashicons dashicons-{{data.icon}}"></span>
				</a>

				<div class="popup-content hidden">

					<# if ( 'background' in data.display ) { #>

						<!-- Background Type -->
						<div class="hester-select-wrapper popup-element style-1">
							<label for="background-type-{{ data.id }}">{{{ data.l10n['background-type'] }}}</label>
							<div class="popup-input-wrapper">
								<select data-option="background-type" id="background-type-{{ data.id }}">
									<# _.each( data.display['background'], function( value, key ){ #>
										<option value="{{ key }}"<# if ( key === data.value['background-type'] ) { #> selected="selected"<# } #>>{{{ value }}}</option>
									<# }); #>
								</select>
							</div>
						</div>

						<# if ( 'color' in data.display['background'] ) { #>

							<div data-dep-field="background-type" data-dep-value="color">
								<!-- Background Color -->
								<div class="popup-element color-element style-1">
									<label for="background-color-{{ data.id }}">{{{ data.display['background']['color'] }}}</label>

									<input class="hester-color-control" data-option="background-color" type="text" value="{{data.value['background-color']}}" data-show-opacity="true" data-default-color="{{data.default['background-color']}}" />
								</div>
							</div>
						<# } #>

						<# if ( 'gradient' in data.display['background'] ) { #>

							<div data-dep-field="background-type" data-dep-value="gradient">

								<!-- Color 1 -->
								<div class="popup-element color-element style-1">
									<label for="gradient-color-1-{{ data.id }}">{{{ data.l10n['gradient']['color-1'] }}}</label>

									<input class="hester-color-control" data-option="gradient-color-1" type="text" value="{{data.value['gradient-color-1']}}" data-show-opacity="true" data-default-color="{{data.default['gradient-color-1']}}" />
								</div>

								<!-- Color 1 Location -->
								<div class="hester-range-wrapper popup-element color-element style-2" data-option-id="gradient-color-1-location">
									<label for="gradient-color-1-location-{{ data.id }}">{{{ 
									data.l10n['gradient']['color-location'] }}}</label>

									<div class="hester-control-wrap">
										<input 
											type="range" 
											value="{{data.value['gradient-color-1-location']}}" 
											min="0" 
											max="100" 
											step="1" />

										<input 
											type="number" 
											class="hester-range-input" 
											value="{{data.value['gradient-color-1-location']}}"
											data-option="gradient-color-1-location" />
									</div>
								</div>

								<!-- Color 2 -->
								<div class="popup-element color-element style-1">
									<label for="gradient-color-2-{{ data.id }}">{{{ data.l10n['gradient']['color-2'] }}}</label>

									<input class="hester-color-control" data-option="gradient-color-2" type="text" value="{{data.value['gradient-color-2']}}" data-show-opacity="true" data-default-color="{{data.default['gradient-color-2']}}" />
								</div>

								<!-- Color 2 Location -->
								<div class="hester-range-wrapper popup-element color-element style-2" data-option-id="gradient-color-2-location">
									<label for="gradient-color-2-location-{{ data.id }}">{{{ data.l10n['gradient']['color-location'] }}}</label>

									<div class="hester-control-wrap">
										<input 
											type="range" 
											value="{{data.value['gradient-color-2-location']}}" 
											min="0" 
											max="100" 
											step="1" />

										<input 
											type="number" 
											class="hester-range-input" 
											value="{{data.value['gradient-color-2-location']}}"
											data-option="gradient-color-2-location" />
									</div>
								</div>

								<!-- Type -->
								<div class="hester-select-wrapper popup-element style-1">
									<label for="gradient-type-{{ data.id }}">{{{ data.l10n['gradient']['type'] }}}</label>
									<div class="popup-input-wrapper">
										<select data-option="gradient-type" id="gradient-type-{{ data.id }}">
											<option value="linear"<# if ( 'linear' === data.value['gradient-type'] ) { #> selected="selected"<# } #>>{{{ data.l10n['gradient']['linear'] }}}</option>
											<option value="radial"<# if ( 'radial' === data.value['gradient-type'] ) { #> selected="selected"<# } #>>{{{ data.l10n['gradient']['radial'] }}}</option>
										</select>
									</div>
								</div>

								<!-- Linear Angle -->
								<div data-dep-field="gradient-type" data-dep-value="linear" class="hester-range-wrapper popup-element color-element style-2" data-option-id="gradient-linear-angle">
									<label for="gradient-angle-{{ data.id }}">{{{ 
									data.l10n['gradient']['angle'] }}}</label>

									<div class="hester-control-wrap">
										<input 
											type="range" 
											value="{{data.value['gradient-linear-angle']}}" 
											min="0" 
											max="360" 
											step="1" />

										<input 
											type="number" 
											class="hester-range-input" 
											value="{{data.value['gradient-linear-angle']}}"
											data-option="gradient-linear-angle" />
									</div>
								</div>

								<!-- Radial Position -->
								<div data-dep-field="gradient-type" data-dep-value="radial" class="hester-select-wrapper popup-element style-1">
									<label for="gradient-position-{{ data.id }}">{{{ data.l10n['gradient']['position'] }}}</label>
									<div class="popup-input-wrapper">
										<select data-option="gradient-position" id="gradient-position-{{ data.id }}">
											<# _.each( data.l10n['gradient']['radial-position'], function( value, key ){ #>
												<option value="{{ key }}"<# if ( key === data.value['gradient-position'] ) { #> selected="selected"<# } #>>{{{ value }}}</option>
											<# }); #>
										</select>
									</div>
								</div>
							</div>
						<# } #>

						<# if ( 'image' in data.display['background'] ) { #>

							<div class="hester-background-wrapper" data-dep-field="background-type" data-dep-value="image">

								<!-- Background Image -->
								<div class="background-image">

									<div class="attachment-media-view background-image-upload">

										<# if ( data.value['background-image'] ) { #>
											<div class="thumbnail thumbnail-image"><img src="{{ data.value['background-image'] }}" alt="" /></div>
										<# } else { #>
											<div class="placeholder"><?php esc_html_e( 'No image selected', 'hester' ); ?></div>
										<# } #>

										<input type="hidden" data-option="background-image" value="{{ data.value['background-image'] }}" name="background-image-{{ data.id }}" />

										<div class="actions">

											<button class="button background-image-upload-remove-button<# if ( ! data.value['background-image'] ) { #> hidden<# } #>"><?php esc_html_e( 'Remove', 'hester' ); ?></button>

											<button type="button" class="button background-image-upload-button">{{{ data.l10n['image']['select_image'] }}}</button>

											<a href="#" class="advanced-settings<# if ( ! data.value['background-image'] ) { #> hidden<# } #>">
												<span class="message"><?php esc_html_e( 'Advanced', 'hester' ); ?></span>
												<span class="dashicons dashicons-arrow-down"></span>
											</a>

										</div>
									</div>
								</div>

								<!-- Background Advanced -->
								<div class="background-image-advanced">

									<!-- Background Repeat -->
									<div class="background-repeat">
										<select {{{ data.inputAttrs }}} data-option="background-repeat">
											<option value="no-repeat"<# if ( 'no-repeat' === data.value['background-repeat'] ) { #> selected <# } #>><?php esc_html_e( 'No Repeat', 'hester' ); ?></option>
											<option value="repeat"<# if ( 'repeat' === data.value['background-repeat'] ) { #> selected <# } #>><?php esc_html_e( 'Repeat All', 'hester' ); ?></option>
											<option value="repeat-x"<# if ( 'repeat-x' === data.value['background-repeat'] ) { #> selected <# } #>><?php esc_html_e( 'Repeat Horizontally', 'hester' ); ?></option>
											<option value="repeat-y"<# if ( 'repeat-y' === data.value['background-repeat'] ) { #> selected <# } #>><?php esc_html_e( 'Repeat Vertically', 'hester' ); ?></option>
										</select>
									</div>

									<!-- Background Position -->
									<div class="background-position">

										<h4><?php esc_html_e( 'Background Position', 'hester' ); ?></h4>

										<div class="hester-range-wrapper" data-option-id="background-position-x">
											<span><?php esc_html_e( 'Horizontal', 'hester' ); ?></span>
											<div class="hester-control-wrap">
												<input 
													type="range" 
													data-key="background-position-x"
													value="{{ data.value['background-position-x'] }}" 
													min="0" 
													max="100" 
													step="1" />
												<input 
													type="number" 
													class="hester-range-input"
													data-option="background-position-x"
													value="{{ data.value['background-position-x'] }}"  />
												<span class="hester-range-suffix">%</span>
											</div>
										</div>

										<div class="hester-range-wrapper" data-option-id="background-position-y">
											<span><?php esc_html_e( 'Vertical', 'hester' ); ?></span>
											<div class="hester-control-wrap">
												<input 
													type="range"
													data-key="background-position-y"
													value="{{ data.value['background-position-y'] }}" 
													min="0" 
													max="100" 
													step="1" />
												<input 
													type="number"
													class="hester-range-input"
													data-option="background-position-y"
													value="{{ data.value['background-position-y'] }}"  />
												<span class="hester-range-suffix">%</span>
											</div>
										</div>

									</div>

									<!-- Background Size -->
									<div class="background-size">
										<h4><?php esc_html_e( 'Background Size', 'hester' ); ?></h4>
										<div class="buttonset">
											<input {{{ data.inputAttrs }}} data-option="background-size" class="switch-input screen-reader-text" type="radio" value="cover" name="_customize-bg-{{{ data.id }}}-size" id="{{ data.id }}cover" <# if ( 'cover' === data.value['background-size'] ) { #> checked="checked" <# } #>>
												<label class="switch-label" for="{{ data.id }}cover"><?php esc_html_e( 'Cover', 'hester' ); ?></label>
											</input>
											<input {{{ data.inputAttrs }}} data-option="background-size" class="switch-input screen-reader-text" type="radio" value="contain" name="_customize-bg-{{{ data.id }}}-size" id="{{ data.id }}contain" <# if ( 'contain' === data.value['background-size'] ) { #> checked="checked" <# } #>>
												<label class="switch-label" for="{{ data.id }}contain"><?php esc_html_e( 'Contain', 'hester' ); ?></label>
											</input>
											<input {{{ data.inputAttrs }}} data-option="background-size" class="switch-input screen-reader-text" type="radio" value="auto" name="_customize-bg-{{{ data.id }}}-size" id="{{ data.id }}auto" <# if ( 'auto' === data.value['background-size'] ) { #> checked="checked" <# } #>>
												<label class="switch-label" for="{{ data.id }}auto"><?php esc_html_e( 'Auto', 'hester' ); ?></label>
											</input>
										</div>
									</div>

									<!-- Background Attachment -->
									<div class="background-attachment">
										<h4><?php esc_html_e( 'Background Attachment', 'hester' ); ?></h4>
										<div class="buttonset">
											<input {{{ data.inputAttrs }}} data-option="background-attachment" lass="switch-input screen-reader-text" type="radio" value="inherit" name="_customize-bg-{{{ data.id }}}-attachment" id="{{ data.id }}inherit" <# if ( 'inherit' === data.value['background-attachment'] ) { #> checked="checked" <# } #>>
												<label class="switch-label" for="{{ data.id }}inherit"><?php esc_html_e( 'Inherit', 'hester' ); ?></label>
											</input>
											<input {{{ data.inputAttrs }}} data-option="background-attachment" class="switch-input screen-reader-text" type="radio" value="scroll" name="_customize-bg-{{{ data.id }}}-attachment" id="{{ data.id }}scroll" <# if ( 'scroll' === data.value['background-attachment'] ) { #> checked="checked" <# } #>>
												<label class="switch-label" for="{{ data.id }}scroll"><?php esc_html_e( 'Scroll', 'hester' ); ?></label>
											</input>
											<input {{{ data.inputAttrs }}} data-option="background-attachment" class="switch-input screen-reader-text" type="radio" value="fixed" name="_customize-bg-{{{ data.id }}}-attachment" id="{{ data.id }}fixed" <# if ( 'fixed' === data.value['background-attachment'] ) { #> checked="checked" <# } #>>
												<label class="switch-label" for="{{ data.id }}fixed"><?php esc_html_e( 'Fixed', 'hester' ); ?></label>
											</input>
										</div>
									</div>

									<!-- Background Color Overlay -->
									<div class="background-color-overlay popup-element color-element style-1">

										<label for="background-color-overlay-{{ data.id }}"><h4><?php esc_html_e( 'Overlay Color', 'hester' ); ?></h4></label>

										<input class="hester-color-control" data-option="background-color-overlay" type="text" value="{{data.value['background-color-overlay']}}" data-show-opacity="true" data-default-color="{{data.default['background-color-overlay']}}" />
									</div>

									<!-- Background Image ID -->
									<input type="hidden" data-option="background-image-id" value="{{ data.value['background-image-id'] }}" class="background-image-id" />
								</div>

							</div>
						<# } #>
					<# } #>

					<# if ( 'color' in data.display ) { #>

						<# _.each( data.display['color'], function( title, id ){ #>

							<div class="popup-element color-element style-1">
								<label for="{{ id }}-{{ data.id }}">{{{ title }}}</label>

								<input class="hester-color-control" data-option="{{ id }}" type="text" value="{{data.value[ id ]}}" data-show-opacity="true" data-default-color="{{data.default[ id ]}}" />
							</div>
						<# }); #>

					<# } #>

					<# if ( 'border' in data.display ) { #>

						<# if ( 'width' in data.display['border'] && 'positions' in data.display['border'] ) { #>

							<div class="customize-control-hester-spacing popup-element style-2">

								<label>{{{ data.display['border']['width'] }}}</label>

								<div class="hester-control-wrap">

									<ul class="active">

										<# _.each( data.display['border']['positions'], function( title, id ){ #>
											<li class="spacing-control-wrap spacing-input">
												<input {{{ data.inputAttrs }}} name="spacing-control-{{ id }}" type="number" data-option="border-{{ id }}-width" value="{{{ data.value[ 'border-' + id + '-width' ] }}}" />
												<span class="hester-spacing-label">{{{ title }}}</span>
											</li>
										<# }); #>

										<li class="spacing-control-wrap">
											<div class="spacing-link-values">
												<span class="dashicons dashicons-admin-links hester-spacing-linked" data-element="{{ data.id }}" title="{{ data.title }}"></span>
												<span class="dashicons dashicons-editor-unlink hester-spacing-unlinked" data-element="{{ data.id }}" title="{{ data.title }}"></span>
											</div>
										</li>

									</ul>
								</div>
							</div>

						<# } #>

						<# if ( 'style' in data.display['border'] ) { #>
							<!-- Border Style -->
							<div class="hester-select-wrapper popup-element style-1">
								<label for="border-style-{{ data.id }}">{{{ data.display['border']['style'] }}}</label>
								<div class="popup-input-wrapper">
									<select data-option="border-style" id="border-style-{{ data.id }}">
										<# _.each( data.l10n['border-styles'], function( value, key ){ #>
											<option value="{{ key }}"<# if ( key === data.value['border-style'] ) { #> selected="selected"<# } #>>{{{ value }}}</option>
										<# }); #>
									</select>
								</div>
							</div>
						<# } #>

						<# if ( 'color' in data.display['border'] ) { #>
							<!-- Border Color -->
							<div class="popup-element color-element style-1">
								<label for="border-color-{{ data.id }}">{{{ data.display['border']['color'] }}}</label>

								<input class="hester-color-control" data-option="border-color" type="text" value="{{data.value['border-color']}}" data-show-opacity="true" data-default-color="{{data.default['border-color']}}" />
							</div>
						<# } #>

						<# if ( 'separator' in data.display['border'] ) { #>
							<!-- Separator Color -->
							<div class="popup-element color-element style-1">
								<label for="separator-color-{{ data.id }}">{{{ data.display['border']['separator'] }}}</label>

								<input class="hester-color-control" data-option="separator-color" type="text" value="{{data.value['separator-color']}}" data-show-opacity="true" data-default-color="{{data.default['separator-color']}}" />
							</div>
						<# } #>

					<# } #>

				</div><!-- .popup-content -->

			</div><!-- .hester-control-wrapper -->
			<?php
		}
	}
endif;
