<?php
function webique_testimonial_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Testimonial  Section
	=========================================*/
	$wp_customize->add_section(
		'testimonial_setting', array(
			'title' => esc_html__( 'Testimonial Section', 'clever-fox' ),
			'priority' => 6,
			'panel' => 'webique_frontpage_sections',
		)
	);
	
	$wp_customize->add_setting( 
		'testimonial_hs' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'testimonial_hs', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'clever-fox' ),
			'section'     => 'testimonial_setting',
			'type'        => 'checkbox'
		) 
	);

	// Testimonial Header Section // 
	$wp_customize->add_setting(
		'testimonial_headings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'testimonial_headings',
		array(
			'type' => 'hidden',
			'label' => __('Header','clever-fox'),
			'section' => 'testimonial_setting',
		)
	);
	
	// Testimonial Title // 
	$wp_customize->add_setting(
    	'testimonial_ttl',
    	array(
	        'default'			=> 'Our <span class="primary-color">Testimonial</span>',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);	
	
	$wp_customize->add_control( 
		'testimonial_ttl',
		array(
		    'label'   => __('Title','clever-fox'),
		    'section' => 'testimonial_setting',
			'type'           => 'text',
			'settings'	=> 'testimonial_ttl',
		)  
	);
	
	// Testimonial Description // 
	$wp_customize->add_setting(
    	'testimonial_desc',
    	array(
	        'default'			=> __('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','clever-fox'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'transport'         => $selective_refresh,
		)
	);	
	
	$wp_customize->add_control( 
		'testimonial_desc',
		array(
		    'label'   => __('Description','clever-fox'),
		    'section' => 'testimonial_setting',
			'type'           => 'textarea',
			'settings'	=> 'testimonial_desc',
		)  
	);

	// Testimonial content Section // 
	
	$wp_customize->add_setting(
		'testimonial_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'testimonial_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','clever-fox'),
			'section' => 'testimonial_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add testimonial
	 */
	
		$wp_customize->add_setting( 'testimonial_contents', 
			array(
			 'sanitize_callback' => 'webique_repeater_sanitize',
			 'default' => websy_get_testimonial_default()
			)
		);
		
		$wp_customize->add_control( 
			new Webique_Repeater( $wp_customize, 
				'testimonial_contents', 
					array(
						'label'   => esc_html__('Testimonial','clever-fox'),
						'section' => 'testimonial_setting',
						'add_field_label'                   => esc_html__( 'Add New Testimonial', 'clever-fox' ),
						'item_name'                         => esc_html__( 'Testimonial', 'clever-fox' ),
						'customizer_repeater_image_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_subtitle2_control' => true,
						'customizer_repeater_text2_control' => true,
						'customizer_repeater_text3_control' => true,
					) 
				) 
			);

            
	//Pro feature
		class Webique_testimonial__section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme	
				if ( 'Websy' == $theme->name){
			?>		
				<a class="customizer_testimonial_upgrade_section up-to-pro" href="https://www.nayrathemes.com/webique-pro/" target="_blank" style="display: none;"><?php esc_html_e('Upgrade to Pro','clever-fox'); ?></a>
			<?php
			}}
		}
		
		$wp_customize->add_setting( 'webique_testimonial_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Webique_testimonial__section_upgrade(
			$wp_customize,
			'webique_testimonial_upgrade_to_pro',
				array(
					'section'				=> 'testimonial_setting',
				)
			)
		);
	
	//Testimonial Speed
		if ( class_exists( 'Webique_Customizer_Range_Control' ) ) {
		$wp_customize->add_setting(
			'testimonial_animation_speed',
			array(
				'default' => '5000',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'webique_sanitize_range_value',
				'priority' => 11,
			)
		);
		$wp_customize->add_control( 
		new Webique_Customizer_Range_Control( $wp_customize, 'testimonial_animation_speed', 
			array(
				'label'      => __( 'Testimonial Speed', 'clever-fox' ),
				'section'  => 'testimonial_setting',
				'settings' => 'testimonial_animation_speed',
				 'media_query'   => false,
					'input_attr'    => array(
						'desktop' => array(
							'min'           => 500,
							'max'           => 10000,
							'step'          => 500,
							'default_value' => 5000,
						),
					),
			) ) 
		);
	}
		// Slider Autoplay
		$wp_customize->add_setting( 
			'testimonial_autoplay', 
				array(
				'default' => '1',
				'capability'     => 'edit_theme_options',
				'sanitize_callback' => 'webique_sanitize_select',
				'priority' => 10,
			) 
		);
		
		$wp_customize->add_control('testimonial_autoplay', array(
			'label' => __('Testimonial Autoplay', 'clever-fox'),
			'section' => 'testimonial_setting',
			'settings' => 'testimonial_autoplay',
			'type'			=> 'select',
			'choices'        => 
					array(
						'1'		=>__('Yes', 'clever-fox'),
						'0'=>__('No', 'clever-fox'),
					) 
			));
			
		//  Loop
		$wp_customize->add_setting( 
			'testimonial_loop' , 
				array(
				'default' => '0',
				'capability'     => 'edit_theme_options',
				'sanitize_callback' => 'webique_sanitize_select',
				'priority' => 10,
			) 
		);
		
		$wp_customize->add_control('testimonial_loop', array(
			'label' => __('Testimonial Loop', 'clever-fox'),
			'section' => 'testimonial_setting',
			'settings' => 'testimonial_loop',
			'type'			=> 'select',
			'choices'        => 
					array(
						'1'		=>__('Yes', 'clever-fox'),
						'0'=>__('No', 'clever-fox'),
					) 
		));	

		//Pro feature
		class Webique_testimonial_pro_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>		
				<a class="customizer_testimonial_upgrade_section up-to-pro" href="https://www.nayrathemes.com/webique-pro/" target="_blank"><?php esc_html_e('Unlock By Upgrade to Pro','clever-fox'); ?></a>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'webique_testimonial_settings_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Webique_testimonial_pro_upgrade(
			$wp_customize,
			'webique_testimonial_settings_upgrade_to_pro',
				array(
					'section'				=> 'testimonial_setting',
				)
			)
		);
		
		//Funfact
		$wp_customize->add_setting(
			'funfact_heading'
				,array(
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'webique_sanitize_text',
			)
		);

		$wp_customize->add_control(
		'funfact_heading',
			array(
				'type' => 'hidden',
				'label' => __('Funfact Content','clever-fox'),
				'section' => 'testimonial_setting',
				// 'priority' => 21,
			)
		);
	
	/**
	 * Customizer Repeater for add Funfact
	 */
	
		$wp_customize->add_setting( 'funfacts_contents', 
			array(
			 'sanitize_callback' => 'webique_repeater_sanitize',
			 // 'transport'         => $selective_refresh,
			 'default' => webique_get_funfact_default()
			)
		);
		
		$wp_customize->add_control( 
			new Webique_Repeater( $wp_customize, 
			'funfacts_contents', 
				array(
					'label'   => esc_html__('Funfact','clever-fox'),
					'section' => 'testimonial_setting',
					'setting'	=> 'funfact_contents',
					'add_field_label'                   => esc_html__( 'Add New Funfact', 'clever-fox' ),
					'item_name'                         => esc_html__( 'Funcfact', 'clever-fox' ),
					'customizer_repeater_title_control' => true,
					'customizer_repeater_subtitle_control' => true,
					
				) 
			) 
		);

		//Pro feature
		class Webique_funfact__section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme	
				if ( 'Websy' == $theme->name){
			?>		
				<a class="customizer_funfact_upgrade_section up-to-pro" href="https://www.nayrathemes.com/webique-pro/" target="_blank" style="display: none;"><?php esc_html_e('Upgrade to Pro','clever-fox'); ?></a>
			<?php
			}}
		}
		
		$wp_customize->add_setting( 'webique_funfact_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Webique_funfact__section_upgrade(
			$wp_customize,
			'webique_funfact_upgrade_to_pro',
				array(
					'section'				=> 'testimonial_setting',
				)
			)
		);
		

		
}

add_action( 'customize_register', 'webique_testimonial_setting' );


// Header selective refresh
function webique_testimonial_partials( $wp_customize ){	
	// Testimonial Title
	$wp_customize->selective_refresh->add_partial(
		'testimonial_ttl', array(
			'selector' => '.testimonial-section .heading-default h1',
			'settings'            => 'testimonial_ttl',
			'render_callback' => 'tesimonial_ttl_callback',
		)
	);
	// Testimonial description
	$wp_customize->selective_refresh->add_partial(
		'testimonial_desc', array(
			'selector' => '.testimonial-section .heading-default p',
			'settings'            => 'testimonial_desc',
			'render_callback' => 'testimonial_desc_callback',
		)
	);
	// Testimonial Contents
	$wp_customize->selective_refresh->add_partial(
		'testimonial_contents', array(
			'selector' => '.testimonial-section .owl-carousel',
		)
	);
	// Funfact Contents
	$wp_customize->selective_refresh->add_partial(
		'testimonial_contents', array(
			'selector' => '.testimonial-box',
		)
	);
}

add_action( 'customize_register', 'webique_testimonial_partials' );

// Title
function tesimonial_ttl_callback() {
	get_theme_mod('testimonial_ttl');
}
//Description
function testimonial_desc_callback() {
	get_theme_mod('testimonial_desc');
}