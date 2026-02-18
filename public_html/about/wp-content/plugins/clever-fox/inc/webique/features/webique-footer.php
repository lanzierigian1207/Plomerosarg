<?php
function webique_lite_footer( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Footer Above
	=========================================*/	
	$wp_customize->add_section(
        'footer_above',
        array(
            'title' 		=> __('Footer Above','clever-fox'),
			'panel'  		=> 'footer_section',
			'priority'      => 2,
		)
    );
	// hide/show
	$wp_customize->add_setting( 
		'hs_above_footer' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'priority' => 1,
		) 
	);
	
	/*=========================================
	Email
	=========================================*/
	$wp_customize->add_setting(
		'ftr_top_email'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 11,
		)
	);

	$wp_customize->add_control(
	'ftr_top_email',
		array(
			'type' => 'hidden',
			'label' => __('Email','clever-fox'),
			'section' => 'footer_above',
		)
	);
	$wp_customize->add_setting( 
		'hide_show_fci_email_details' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'priority' => 12,
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_fci_email_details', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'clever-fox' ),
			'section'     => 'footer_above',
			'type'        => 'checkbox'
		) 
	);	
	
	// icon // 
	$wp_customize->add_setting(
    	'fci_email_icon',
    	array(
	        'default' => 'fa-envelope',
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control(new Webique_Icon_Picker_Control($wp_customize, 
	'fci_email_icon',
		array(
		    'label'   		=> __('Icon','clever-fox'),
		    'section' 		=> 'footer_above',
			'iconset' => 'fa',
			
		))  
	);	
	// Email title // 
	$wp_customize->add_setting(
    	'fci_email_title',
    	array(
	        'default'			=> __('Email Us','clever-fox'),
			'sanitize_callback' => 'webique_sanitize_text',
			'capability' => 'edit_theme_options',
			'transport'         => $selective_refresh,
			'priority' => 13,
		)
	);	

	$wp_customize->add_control( 
		'fci_email_title',
		array(
		    'label'   		=> __('Title','clever-fox'),
		    'section' 		=> 'footer_above',
			'type'		 =>	'text'
		)  
	);
	
	// Email Link // 
	$wp_customize->add_setting(
    	'fci_email_link',
    	array(
			'default'			=> __('info@example.com','clever-fox'),
			'sanitize_callback' => 'webique_sanitize_text',
			'capability' => 'edit_theme_options',
			'priority' => 14,
		)
	);	

	$wp_customize->add_control( 
		'fci_email_link',
		array(
		    'label'   		=> __('Link','clever-fox'),
		    'section' 		=> 'footer_above',
			'type'		 =>	'text'
		)  
	);
	
	
	
	/*=========================================
	Mobile
	=========================================*/
	$wp_customize->add_setting(
		'ftr_top_mbl'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 16,
		)
	);

	$wp_customize->add_control(
	'ftr_top_mbl',
		array(
			'type' => 'hidden',
			'label' => __('Phone','clever-fox'),
			'section' => 'footer_above',
			
		)
	);
	$wp_customize->add_setting( 
		'hide_show_fci_mobile_details' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'priority' => 17,
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_fci_mobile_details', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'clever-fox' ),
			'section'     => 'footer_above',
			'type'        => 'checkbox'
		) 
	);	
	// icon // 
	$wp_customize->add_setting(
    	'fci_mobile_icon',
    	array(
	        'default' => 'fa-whatsapp',
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control(new Webique_Icon_Picker_Control($wp_customize, 
		'fci_mobile_icon',
		array(
		    'label'   		=> __('Icon','clever-fox'),
		    'section' 		=> 'footer_above',
			'iconset' => 'fa',
			
		))  
	);
	
	// Mobile title // 
	$wp_customize->add_setting(
    	'fci_mobile_title',
    	array(
	        'default'			=> __('Call Us','clever-fox'),
			'sanitize_callback' => 'webique_sanitize_text',
			'transport'         => $selective_refresh,
			'capability' => 'edit_theme_options',
			'priority' => 18,
		)
	);	

	$wp_customize->add_control( 
		'fci_mobile_title',
		array(
		    'label'   		=> __('Title','clever-fox'),
		    'section' 		=> 'footer_above',
			'type'		 =>	'text'
		)  
	);
	
	// Link // 
	$wp_customize->add_setting(
    	'fci_mobile_link',
    	array(
			'default'		=> __('987654321','clever-fox'),
			'sanitize_callback' => 'webique_sanitize_text',
			'capability' => 'edit_theme_options',
			'priority' => 19,
		)
	);	

	$wp_customize->add_control( 
		'fci_mobile_link',
		array(			
		    'label'   		=> __('Link','clever-fox'),
		    'section' 		=> 'footer_above',
			'type'		 =>	'text'
		)  
	);
	
	$wp_customize->add_setting( 
		'footer_logo' , 
		array(
			'default'			=> esc_url(get_template_directory_uri(). '/assets/images/footer/footer_logo.png'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_url',	
			'priority'  => 2,
		) 
	);

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize , 'footer_logo' ,
		array(
			'label'          => esc_html__( 'Logo', 'clever-fox'),
			'section'        => 'footer_above'
		) 
	));
	
	//Link
	$wp_customize->add_setting( 
		'footer_logo_link' , 
		array(			
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_url',	
			'priority'  => 2,
		) 
	);

	$wp_customize->add_control( 'footer_logo_link' ,
		array(
			'label'          => esc_html__( 'Logo Link', 'clever-fox'),
			'section'        => 'footer_above'		 
	));
	
	$wp_customize->add_setting( 
		'footer_logo_newtab' , 
			array(
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'default' => '1'
		) 
	);
	
	$wp_customize->add_control(
	'footer_logo_newtab', 
		array(
			'label'	      => esc_html__( 'Open In New Tab', 'clever-fox' ),
			'section'     => 'footer_above',
			'type'        => 'checkbox'
		) 
	);
	
	$wp_customize->add_setting( 
		'footer_logo_nofollow' , 
			array(
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'default' => '1'
		) 
	);

	$wp_customize->add_control(
	'footer_logo_nofollow', 
		array(
			'label'	      => esc_html__( 'Add "nofollow" To Link', 'clever-fox' ),
			'section'     => 'footer_above',
			'type'        => 'checkbox'
		) 
	);
	
	if ( class_exists( 'Webique_Customizer_Range_Control' ) ) {
		$wp_customize->add_setting(
			'footer_logo_width',
			array(
				'default' => '250',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'webique_sanitize_range_value',
				// 'transport'			=> $selective_refresh,
			)
		);
		$wp_customize->add_control( 
		new Webique_Customizer_Range_Control( $wp_customize, 'footer_logo_width', 
			array(
				'label'      => __( 'Logo Width', 'clever-fox' ),
				'section'  => 'footer_above',
				 'media_query'   => true,
				'input_attr'    => array(
					'mobile'  => array(
						'min'           => 0,
						'max'           => 500,
						'step'          => 1,
						'default_value' => 250,
					),
					'tablet'  => array(
						'min'           => 0,
						'max'           => 500,
						'step'          => 1,
						'default_value' => 250,
					),
					'desktop' => array(
						'min'           => 0,
						'max'           => 500,
						'step'          => 1,
						'default_value' => 250,
					),
				),
			  ) 
			) 
		);
	}
	
	/*=========================================
	Footer Animation Bar Section
	=========================================*/
	
	$wp_customize->add_setting(
		'ftr_anim_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'ftr_anim_head',
		array(
			'type' => 'hidden',
			'label' => __('Animation Bar','clever-fox'),
			'section' => 'footer_above',
			'priority' => 10,
		)
	);
	
	
	$wp_customize->add_setting( 
		'hide_show_ftr_anim_bar' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'priority' => 5,
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_ftr_anim_bar', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'clever-fox' ),
			'section'     => 'footer_above',
			'type'        => 'checkbox'
		) 
	);
	
	/**
	 * Customizer Repeater
	 */
	$wp_customize->add_setting( 'footer_bar_contents', 
		array(
		 'sanitize_callback' => 'webique_repeater_sanitize',
		 'priority' => 2,
		 'default' => header_marquee_default()
		)
	);
	
	$wp_customize->add_control( 
		new WEBIQUE_Repeater( $wp_customize, 
			'footer_bar_contents', 
				array(
					'label'   => esc_html__('Animation','clever-fox'),
					'section' => 'footer_above',
					'add_field_label'                   => esc_html__( 'Add New Animation', 'clever-fox' ),
					'customizer_repeater_title_control' => true,
					'customizer_repeater_link_control' => true,
					'customizer_repeater_newtab_control' => true,
					'customizer_repeater_nofollow_control' => true,
				) 
			) 
		);
		
		//Pro feature
		class Webique_footer_animation__section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>
			<a class="customizer_footer_animation_upgrade_section up-to-pro"  href="https://www.nayrathemes.com/webique-pro/" target="_blank"  style="display: none;"><?php esc_html_e('Upgrade to Pro','clever-fox'); ?></a>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'webique_footer_animation_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Webique_footer_animation__section_upgrade(
			$wp_customize,
			'webique_footer_animation_upgrade_to_pro',
				array(
					'section'				=> 'footer_above',
				)
			)
		);	
	
		
	$wp_customize->add_section(
        'footer_sponsors_section',
        array(
            'title' 		=> __('Sponsors','clever-fox'),
			'panel'  		=> 'footer_section',
			'priority'      => 4,
		)
    );
		
	$wp_customize->add_setting(
		'fchat'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 13,
		)
	);

	$wp_customize->add_control(
	'fchat',
		array(
			'type' => 'hidden',
			'label' => __('Help','clever-fox'),
			'section' => 'footer_sponsors_section',
		)
	);
	
	$wp_customize->add_setting( 
		'hide_show_fchat1_details' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'priority' => 12,
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_fchat1_details', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'clever-fox' ),
			'section'     => 'footer_sponsors_section',
			'type'        => 'checkbox'
		) 
	);	
	
	// icon // 
	$wp_customize->add_setting(
    	'fchat1_icon',
    	array(
	        'default' => 'fa-headphones',
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control(new Webique_Icon_Picker_Control($wp_customize, 
		'fchat1_icon',
		array(
		    'label'   		=> __('Icon','clever-fox'),
		    'section' 		=> 'footer_sponsors_section',
			'iconset' => 'fa',
			
		))  
	);	
	// Email title // 
	$wp_customize->add_setting(
    	'fchat1_title',
    	array(
	        'default'			=> __('Email Us','clever-fox'),
			'sanitize_callback' => 'webique_sanitize_text',
			'capability' => 'edit_theme_options',
			'transport'         => $selective_refresh,
			'priority' => 13,
		)
	);	

	$wp_customize->add_control( 
		'fchat1_title',
		array(
		    'label'   		=> __('Title','clever-fox'),
		    'section' 		=> 'footer_sponsors_section',
			'type'		 =>	'text'
		)  
	);
	// Email subtitle // 
	$wp_customize->add_setting(
    	'fchat1_subtitle',
    	array(
	        'default'			=> __('Sales Team','clever-fox'),
			'sanitize_callback' => 'webique_sanitize_text',
			'capability' => 'edit_theme_options',
			'transport'         => $selective_refresh,
			'priority' => 13,
		)
	);	

	$wp_customize->add_control( 
		'fchat1_subtitle',
		array(
		    'label'   		=> __('Subtitle','clever-fox'),
		    'section' 		=> 'footer_sponsors_section',
			'type'		 =>	'text'
		)  
	);
	
	
	/*=========================================
	Mobile
	=========================================*/
	$wp_customize->add_setting(
		'supor_chat'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 16,
		)
	);

	$wp_customize->add_control(
	'supor_chat',
		array(
			'type' => 'hidden',
			'label' => __('Chat Support','clever-fox'),
			'section' => 'footer_sponsors_section',
			
		)
	);
	$wp_customize->add_setting( 
		'hide_show_fchat2_details' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'priority' => 17,
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_fchat2_details', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'clever-fox' ),
			'section'     => 'footer_sponsors_section',
			'type'        => 'checkbox'
		) 
	);	
	// icon // 
	$wp_customize->add_setting(
    	'fchat2_icon',
    	array(
	        'default' => 'fa-headphones',
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control(new Webique_Icon_Picker_Control($wp_customize, 
		'fchat2_icon',
		array(
		    'label'   		=> __('Icon','clever-fox'),
		    'section' 		=> 'footer_sponsors_section',
			'iconset' => 'fa',
			
		))  
	);
	
	// Mobile title // 
	$wp_customize->add_setting(
    	'fchat2_title',
    	array(
	        'default'			=> __('Live Chat','clever-fox'),
			'sanitize_callback' => 'webique_sanitize_text',
			'transport'         => $selective_refresh,
			'capability' => 'edit_theme_options',
			'priority' => 18,
		)
	);	

	$wp_customize->add_control( 
		'fchat2_title',
		array(
		    'label'   		=> __('Title','clever-fox'),
		    'section' 		=> 'footer_sponsors_section',
			'type'		 =>	'text'
		)  
	);
	
	// Mobile subtitle // 
	$wp_customize->add_setting(
    	'fchat2_subtitle',
    	array(
	        'default'			=> __('Support Team','clever-fox'),
			'sanitize_callback' => 'webique_sanitize_text',
			'transport'         => $selective_refresh,
			'capability' => 'edit_theme_options',
			'priority' => 18,
		)
	);	

	$wp_customize->add_control( 
		'fchat2_subtitle',
		array(
		    'label'   		=> __('Support Team','clever-fox'),
		    'section' 		=> 'footer_sponsors_section',
			'type'		 =>	'text'
		)  
	);
	
	/*=========================================
	Platforms
	=========================================*/
	$wp_customize->add_setting(
		'platforms'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 16,
		)
	);
	
	$wp_customize->add_control(
	'platforms',
		array(
			'type' => 'hidden',
			'label' => __('Platforms','clever-fox'),
			'section' => 'footer_sponsors_section',
			
		)
	);
	
	$wp_customize->add_setting( 'application_platforms', 
		array(
		 'sanitize_callback' => 'webique_repeater_sanitize',
		 // 'transport'         => $selective_refresh,
		 'priority' => 8,
		 'default' => webique_get_platform_default()
		)
	);

	$wp_customize->add_control( 
		new Webique_Repeater( $wp_customize, 
		'application_platforms', 
			array(
				'label'   => esc_html__('Platforms','clever-fox'),
				'section' => 'footer_sponsors_section',
				'add_field_label'                   => esc_html__( 'Add New Platform', 'clever-fox' ),
				'item_name'                         => esc_html__( 'Platform', 'clever-fox' ),
				'customizer_repeater_title_control' => true,
				'customizer_repeater_subtitle_control' => true,
				'customizer_repeater_icon_control' => true,
				'customizer_repeater_link_control' => true,
				'customizer_repeater_newtab_control' => true,
				'customizer_repeater_nofollow_control' => true,
			) 
		) 
	);
	
	//Pro feature
		class Webique_footer_platform__section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>
			<a class="customizer_footer_platform_upgrade_section up-to-pro"  href="https://www.nayrathemes.com/webique-pro/" target="_blank"  style="display: none;"><?php esc_html_e('Upgrade to Pro','clever-fox'); ?></a>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'webique_footer_platform_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Webique_footer_platform__section_upgrade(
			$wp_customize,
			'webique_footer_platform_upgrade_to_pro',
				array(
					'section'				=> 'footer_sponsors_section',
				)
			)
		);
		
}
add_action( 'customize_register', 'webique_lite_footer' );
// Footer selective refresh
function webique_lite_footer_partials( $wp_customize ){	
	//footer_above_content 
	$wp_customize->selective_refresh->add_partial( 'footer_above_content', array(
		'selector'            => '.footer-above .av-columns-area',
	) );
	
	}

add_action( 'customize_register', 'webique_lite_footer_partials' );