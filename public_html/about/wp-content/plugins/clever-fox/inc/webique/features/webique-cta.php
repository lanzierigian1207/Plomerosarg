<?php
function webique_cta_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	CTA  Section
	=========================================*/
	$wp_customize->add_section(
		'cta_setting', array(
			'title' => esc_html__( 'Call to Action Section', 'clever-fox' ),
			'priority' => 5,
			'panel' => 'webique_frontpage_sections',
		)
	);
	
	$wp_customize->add_setting( 
		'cta_hs' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'cta_hs', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'clever-fox' ),
			'section'     => 'cta_setting',
			'type'        => 'checkbox'
		) 
	);
	
	// CTA Call Section // 
	$wp_customize->add_setting(
		'cta_call_contents'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 1,
		)
	);

	$wp_customize->add_control(
	'cta_call_contents',
		array(
			'type' => 'hidden',
			'label' => __('Left Content','clever-fox'),
			'section' => 'cta_setting',
		)
	);
	
	// icon // 
	$wp_customize->add_setting(
	'cta_call_icon',
    	array(
	        'default' => 'fa-phone',
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
			'priority' => 2,
		)
	);	

	$wp_customize->add_control(new Webique_Icon_Picker_Control($wp_customize, 
		'cta_call_icon',
		array(
		    'label'   		=> __('Icon','clever-fox'),
		    'section' 		=> 'cta_setting',
			'iconset' => 'fa',
			
		))  
	);	
	
	
	// CTA Call Title // 
	$wp_customize->add_setting(
    	'cta_call_title',
    	array(
	        'default'			=> __('Available 24/7','clever-fox'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 3,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_call_title',
		array(
		    'label'   => __('Title','clever-fox'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
	
	// CTA Call Text // 
	$wp_customize->add_setting(
    	'cta_call_text',
    	array(
	        'default'			=> '+91 246 2365',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_call_text',
		array(
		    'label'   => __('Text','clever-fox'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
	
	
	// CTA Email Section // 
	$wp_customize->add_setting(
		'cta_email_contents'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 5,
		)
	);

	$wp_customize->add_control(
	'cta_email_contents',
		array(
			'type' => 'hidden',
			'label' => __('Right Content','clever-fox'),
			'section' => 'cta_setting',
		)
	);
	
	// icon // 
	$wp_customize->add_setting(
    	'cta_email_icon',
    	array(
	        'default' => 'fa-envelope',
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
			'priority' => 6,
		)
	);	

	$wp_customize->add_control(new Webique_Icon_Picker_Control($wp_customize, 
		'cta_email_icon',
		array(
		    'label'   		=> __('Icon','clever-fox'),
		    'section' 		=> 'cta_setting',
			'iconset' => 'fa',
			
		))  
	);	
	
	
	// CTA Email Title // 
	$wp_customize->add_setting(
    	'cta_email_title',
    	array(
	        'default'			=> __('Email Us:','clever-fox'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 7,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_email_title',
		array(
		    'label'   => __('Title','clever-fox'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
	
	// CTA Email Text // 
	$wp_customize->add_setting(
    	'cta_email_text',
    	array(
	        'default'			=> 'info@company.com',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 8,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_email_text',
		array(
		    'label'   => __('Text','clever-fox'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
	
	
	// CTA Content Section // 
	$wp_customize->add_setting(
		'cta_right_contents'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 9,
		)
	);

	$wp_customize->add_control(
	'cta_right_contents',
		array(
			'type' => 'hidden',
			'label' => __('Right Content','clever-fox'),
			'section' => 'cta_setting',
		)
	);
	
	
	// icon // 
	$wp_customize->add_setting(
    	'cta_rating',
    	array(
	        'default' => '5',
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
			'priority' => 10,
		)
	);	

	$wp_customize->add_control('cta_rating',array(
		    'label'   		=> __('Rating Stars','clever-fox'),
		    'section' 		=> 'cta_setting',
			'type'			=> 'text'
		) 
	);	
	
	
	// CTA Title // 
	$wp_customize->add_setting(
    	'cta_title',
    	array(
	        'default'			=> __('Get A Free Consultation','clever-fox'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 11,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_title',
		array(
		    'label'   => __('Title','clever-fox'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
	
	// CTA Description // 
	$wp_customize->add_setting(
    	'cta_subtitle',
    	array(
	        'default'			=> __('99% Satisfy Clients','clever-fox'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 12,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_subtitle',
		array(
		    'label'   => __('Description','clever-fox'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
	
	// Button // 	
	$wp_customize->add_setting(
		'cta_btn'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 13,
		)
	);

	$wp_customize->add_control(
	'cta_btn',
		array(
			'type' => 'hidden',
			'label' => __('Button','clever-fox'),
			'section' => 'cta_setting',
		)
	);
	
	
	$wp_customize->add_setting(
    	'cta_btn_lbl',
    	array(
	        'default'			=> __('Contact Us','clever-fox'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 14,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_btn_lbl',
		array(
		    'label'   => __('Button Label','clever-fox'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
	
	$wp_customize->add_setting(
    	'cta_btn_link',
    	array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_url',
			'priority' => 15,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_btn_link',
		array(
		    'label'   => __('Link','clever-fox'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
	
	$wp_customize->add_setting( 
		'cta_btn_newtab' , 
			array(
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'default' => '1'
		) 
	);

	$wp_customize->add_control(
	'cta_btn_newtab', 
		array(
			'label'	      => esc_html__( 'Open In New Tab', 'clever-fox' ),
			'section'     => 'cta_setting',
			'type'        => 'checkbox'
		) 
	);
	
	$wp_customize->add_setting( 
		'cta_btn_nofollow' , 
			array(
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'default' => '1'
		) 
	);

	$wp_customize->add_control(
	'cta_btn_nofollow', 
		array(
			'label'	      => esc_html__( 'Add "nofollow" To Link', 'clever-fox' ),
			'section'     => 'cta_setting',
			'type'        => 'checkbox'
		) 
	);
	
	
	// CTA Background // 	
	$wp_customize->add_setting(
		'cta_bg_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 16,
		)
	);

	$wp_customize->add_control(
	'cta_bg_head',
		array(
			'type' => 'hidden',
			'label' => __('Background','clever-fox'),
			'section' => 'cta_setting',
		)
	);
	
    $wp_customize->add_setting( 
    	'cta_bg_setting' , 
    	array(
			'default' 			=> esc_url(CLEVERFOX_PLUGIN_URL.'inc/webique/images/cta_bg.jpg'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_url',	
			'priority' => 17,
		) 
	);
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize , 'cta_bg_setting' ,
		array(
			'label'          => __( 'Background Image', 'clever-fox' ),
			'section'        => 'cta_setting',
		) 
	));
	
	$wp_customize->add_setting( 'cta_contents', 
		array(
		 'sanitize_callback' => 'webique_repeater_sanitize',
		 'priority' => 23,
		 'default' => webique_get_cta_default()
		)
	);

	$wp_customize->add_control( 
		new Webique_Repeater( $wp_customize, 
		'cta_contents', 
			array(
				'label'   => esc_html__('Clients','clever-fox'),
				'section' => 'cta_setting',
				'settings'	=> 'cta_contents',
				'add_field_label'                   => esc_html__( 'Add New Cta_Client', 'clever-fox' ),
				'item_name'                         => esc_html__( 'Cta_Client', 'clever-fox' ),
				'customizer_repeater_image_control' => true,
			) 
		) 
	);
	//Pro feature
		class Webique_cta_client__section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme	
			?>
			<a class="customizer_cta_client_upgrade_section up-to-pro" href="https://www.nayrathemes.com/webique-pro/" target="_blank" style="display: none;"><?php esc_html_e('Upgrade to Pro','clever-fox'); ?></a>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'webique_cta_client_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Webique_cta_client__section_upgrade(
			$wp_customize,
			'webique_cta_client_upgrade_to_pro',
				array(
					'section'				=> 'cta_setting',
				)
			)
		);

	$wp_customize->add_setting( 
		'cta_bg_position' , 
			array(
			'default' => 'fixed',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_select',
			'priority' => 18,
		) 
	);
	
	$wp_customize->add_control(
		'cta_bg_position' , 
			array(
				'label'          => __( 'Image Position', 'clever-fox' ),
				'section'        => 'cta_setting',
				'type'           => 'radio',
				'choices'        => 
				array(
					'fixed'=> __( 'Fixed', 'clever-fox' ),
					'scroll' => __( 'Scroll', 'clever-fox' )
			)  
		) 
	);
	
	// Overlay Color
	$wp_customize->add_setting(
	'cta_bg_overlay_clr', 
	array(
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		'default' => '#202049',
		'priority' => 19
    ));
	
	$wp_customize->add_control( 
		new WP_Customize_Color_Control
		($wp_customize, 
			'cta_bg_overlay_clr', 
			array(
				'label'      => __( 'Overlay Color', 'clever-fox' ),
				'section'    => 'cta_setting',
			) 
		) 
	);
	
	// opacity
	if ( class_exists( 'Cleverfox_Customizer_Range_Slider_Control' ) ) {
		$wp_customize->add_setting(
			'cta_bg_opacity',
			array(
				'default'	      => '0.9',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'webique_sanitize_range_value',
				'priority' => 20,
			)
		);
		$wp_customize->add_control( 
		new Cleverfox_Customizer_Range_Slider_Control( $wp_customize, 'cta_bg_opacity', 
			array(
				'label'      => __( 'opacity', 'clever-fox' ),
				'section'  => 'cta_setting',
				 'media_query'   => false,
					'input_attr'    => array(
						'desktop' => array(
							'min'           => 0,
							'max'           => 0.9,
							'step'          => 0.1,
							'default_value' => 0.9,
						),
					),
			) ) 
		);
	}
	
	
	// enable Effect
	$wp_customize->add_setting(
		'cta_effect_enable'
			,array(
			'default' => '1',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'priority' => 21,
		)
	);

	$wp_customize->add_control(
	'cta_effect_enable',
		array(
			'type' => 'checkbox',
			'label' => __('Enable Water Effect','clever-fox'),
			'section' => 'cta_setting',
		)
	);
	
	//Pro feature
		class Webique_cta___pro_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>		
				<a class="customizer_slider_upgrade_section up-to-pro" href="https://www.nayrathemes.com/webique-pro/" target="_blank"><?php esc_html_e('Unlock By Upgrade to Pro','clever-fox'); ?></a>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'webique_cta___settings_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Webique_cta___pro_upgrade(
			$wp_customize,
			'webique_cta___settings_upgrade_to_pro',
				array(
					'section'				=> 'cta_setting',
				)
			)
		);
}

add_action( 'customize_register', 'webique_cta_setting' );

// CTA selective refresh
function webique_ata_section_partials( $wp_customize ){
	
	// cta_call_title
	$wp_customize->selective_refresh->add_partial( 'cta_call_title', array(
		'selector'            => '.cta-section .cta-box .call-title',
		'settings'            => 'cta_call_title',
		'render_callback'  => 'webique_cta_call_title_render_callback',
	) );
	
	// cta_call_text
	$wp_customize->selective_refresh->add_partial( 'cta_call_text', array(
		'selector'            => '.cta-section .cta-box .call-text',
		'settings'            => 'cta_call_text',
		'render_callback'  => 'webique_cta_call_text_render_callback',
	) );
	
	// cta_email_title
	$wp_customize->selective_refresh->add_partial( 'cta_email_title', array(
		'selector'            => '.cta-section .cta-box .email-title',
		'settings'            => 'cta_email_title',
		'render_callback'  => 'webique_cta_email_title_render_callback',
	) );
	
	// cta_email_text
	$wp_customize->selective_refresh->add_partial( 'cta_email_text', array(
		'selector'            => '.cta-section .cta-box .email-text',
		'settings'            => 'cta_email_text',
		'render_callback'  => 'webique_cta_email_text_render_callback',
	) );
	
	// cta_title
	$wp_customize->selective_refresh->add_partial( 'cta_title', array(
		'selector'            => '.cta-section .cta-text .cta_tittle',
		'settings'            => 'cta_title',
		'render_callback'  => 'webique_cta_title_render_callback',
	) );
	
	// cta_subtitle
	$wp_customize->selective_refresh->add_partial( 'cta_subtitle', array(
		'selector'            => '.cta-section .info-box h4',
		'settings'            => 'cta_subtitle',
		'render_callback'  => 'webique_cta_subtitle_render_callback',
	) );
	
	// cta_description
	$wp_customize->selective_refresh->add_partial( 'cta_description', array(
		'selector'            => '.cta-section .cta-content-wrap p',
		'settings'            => 'cta_description',
		'render_callback'  => 'webique_cta_description_render_callback',
	) );
	
	// cta_btn_lbl
	$wp_customize->selective_refresh->add_partial( 'cta_btn_lbl', array(
		'selector'            => '.cta-section .cta-text .cta_btn_lbl',
		'settings'            => 'cta_btn_lbl',
		'render_callback'  => 'webique_cta_btn_lbl_render_callback',
	) );
}

add_action( 'customize_register', 'webique_ata_section_partials' );

// cta_title
function webique_cta_title_render_callback() {
	return get_theme_mod( 'cta_title' );
}

// cta_subtitle
function webique_cta_subtitle_render_callback() {
	return get_theme_mod( 'cta_subtitle' );
}


// cta_description
function webique_cta_description_render_callback() {
	return get_theme_mod( 'cta_description' );
}

// cta_btn_lbl
function webique_cta_btn_lbl_render_callback() {
	return get_theme_mod( 'cta_btn_lbl' );
}

// cta_call_title
function webique_cta_call_title_render_callback() {
	return get_theme_mod( 'cta_call_title' );
}

// cta_call_text
function webique_cta_call_text_render_callback() {
	return get_theme_mod( 'cta_call_text' );
}

// cta_email_title
function webique_cta_email_title_render_callback() {
	return get_theme_mod( 'cta_email_title' );
}

// cta_email_text
function webique_cta_email_text_render_callback() {
	return get_theme_mod( 'cta_email_text' );
}
