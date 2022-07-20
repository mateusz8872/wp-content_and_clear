<?php
/**
 * Generic notice if hester core plugin isn't install
 *
 * @package Hester
 */

if ( ! class_exists( 'Hester_Core' ) ) {

	$options['section']['hester_section_hester_core'] = array(
		'class'        => 'Hester_Customizer_Control_Generic_Notice',
		'section_text' =>
		sprintf(
			/* translators: %1$s is Plugin Name */
			esc_html__( 'If you want to take full advantage of the options this theme has to offer, please install and activate %1$s.', 'hester' ),
			esc_html__( 'Hester Core plugin', 'hester' )
		),
		'slug'         => 'hester-core',
		'priority'     => 0,
		'capability'   => 'install_plugins',
		'hide_notice'  => false,
		'options'      => array(
			'redirect' => admin_url( 'customize.php' ) . '?autofocus[section]=static_front_page',
		),
	);

	$options['setting']['hester_section_hester_core_recommendation'] = array(
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
		'control'           => array(
			'type'    => 'hidden',
			'section' => 'hester_section_hester_core',
		),
	);
}

// Pro features section
$options['section']['hester_section_hester_pro'] = array(
	'title' => esc_html__( 'View pro features', 'hester' ),
	"priority"=>0
);

$options['setting']['hester_section_hester_pro_features'] = array(
	'transport'         => 'refresh',
	'sanitize_callback' => 'sanitize_text_field',
	'control'           => array(
		'type'    => 'hester-pro',
		'section' => 'hester_section_hester_pro',
	),
);

/**
 * Common customizer options.
 */
$sections = hester_design_common_section();

foreach ( $sections as $section ) {

	$options['setting'][ "hester_{$section}_design_options_heading" ] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'hester_sanitize_toggle',
		'control'           => array(
			'type'     => 'hester-heading',
			'section'  => "hester_section_{$section}",
			'label'    => esc_html__( 'Design options', 'hester' ),
			'priority' => 40,
			'required' => array(
				array(
					'control'  => "hester_enable_{$section}",
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	// Page Header background design.
	$options['setting'][ "hester_{$section}_background" ] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'hester_sanitize_design_options',
		'control'           => array(
			'type'     => 'hester-design-options',
			'label'    => esc_html__( 'Background', 'hester' ),
			'section'  => "hester_section_{$section}",
			'priority' => 41,
			'display'  => array(
				'background' => array(
					'color'    => esc_html__( 'Solid Color', 'hester' ),
					'gradient' => esc_html__( 'Gradient', 'hester' ),
					'image'    => esc_html__( 'Image', 'hester' ),
				),
			),
			'required' => array(
				array(
					'control'  => "hester_enable_{$section}",
					'value'    => true,
					'operator' => '==',
				),
				array(
					'control'  => "hester_{$section}_design_options_heading",
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	// Page Header Text Color.
	$options['setting'][ "hester_{$section}_text_color" ] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'hester_sanitize_design_options',
		'control'           => array(
			'type'     => 'hester-design-options',
			'label'    => esc_html__( 'Font Color', 'hester' ),
			'section'  => "hester_section_{$section}",
			'priority' => 42,
			'display'  => array(
				'color' => array(
					'text-color'       => esc_html__( 'Text Color', 'hester' ),
					'link-color'       => esc_html__( 'Link Color', 'hester' ),
					'link-hover-color' => esc_html__( 'Link Hover Color', 'hester' ),
				),
			),
			'required' => array(
				array(
					'control'  => "hester_enable_{$section}",
					'value'    => true,
					'operator' => '==',
				),
				array(
					'control'  => "hester_{$section}_design_options_heading",
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	// Section top/bottom spacing.
	$options['setting'][ "hester_{$section}_section_spacing" ] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'hester_sanitize_responsive',
		'control'           => array(
			'type'        => 'hester-spacing',
			'label'       => esc_html__( 'Section spacing', 'hester' ),
			'description' => esc_html__( 'Specify section\'s top bottom spacing. Negative values are allowed.', 'hester' ),
			'section'     => "hester_section_{$section}",
			'settings'    => "hester_{$section}_section_spacing",
			'priority'    => 43,
			'choices'     => array(
				'top'    => esc_html__( 'Top', 'hester' ),
				'bottom' => esc_html__( 'Bottom', 'hester' ),
			),
			'responsive'  => true,
			'unit'        => array(
				'rem',
			),
			'required'    => array(
				array(
					'control'  => "hester_enable_{$section}",
					'value'    => true,
					'operator' => '==',
				),
				array(
					'control'  => "hester_{$section}_design_options_heading",
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	// Section container width.
	$options['setting'][ "hester_{$section}_container_width" ] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'hester_sanitize_select',
		'control'           => array(
			'type'        => 'hester-select',
			'label'       => esc_html__( 'Section Width', 'hester' ),
			'description' => esc_html__( 'Stretch the Section container to full width, or match your site&rsquo;s content width.', 'hester' ),
			'section'     => "hester_section_{$section}",
			'priority'    => 44,
			'choices'     => array(
				'content-width' => esc_html__( 'Content Width', 'hester' ),
				'full-width'    => esc_html__( 'Full Width', 'hester' ),
			),
			'required'    => array(
				array(
					'control'  => "hester_enable_{$section}",
					'value'    => true,
					'operator' => '==',
				),
				array(
					'control'  => "hester_{$section}_design_options_heading",
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);
}
return $options;
