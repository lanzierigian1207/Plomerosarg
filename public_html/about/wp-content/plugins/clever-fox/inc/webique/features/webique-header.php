<?php
function webique_lite_header_settings( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	
	/*=========================================
	Webique Site Identity
	=========================================*/	
	// Logo Width // 
	if ( class_exists( 'Cleverfox_Customizer_Range_Slider_Control' ) ) {
		$wp_customize->add_setting(
			'logo_width',
			array(
				'default'			=> '250',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'webique_sanitize_range_value',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 
		new Cleverfox_Customizer_Range_Slider_Control( $wp_customize, 'logo_width', 
			array(
				'label'      => __( 'Logo Width', 'clever-fox' ),
				'section'  => 'title_tagline',
				'input_attrs' => array(
					'min'    => 0,
					'max'    => 500,
					'step'   => 1,
					//'suffix' => 'px', //optional suffix
				),
			) ) 
		);
		
		// Site Title Font Size// 
		$wp_customize->add_setting(
			'site_ttl_size',
			array(
				'default'			=> '30',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'webique_sanitize_range_value',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 
		new Cleverfox_Customizer_Range_Slider_Control( $wp_customize, 'site_ttl_size', 
			array(
				'label'      => __( 'Site Title Font Size', 'clever-fox' ),
				'section'  => 'title_tagline',
				'input_attrs' => array(
					'min'    => 0,
					'max'    => 100,
					'step'   => 1,
					//'suffix' => 'px', //optional suffix
				),
			) ) 
		);
		
		// Site Description Font Size// 
		$wp_customize->add_setting(
			'site_desc_size',
			array(
				'default'			=> '16',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'webique_sanitize_range_value',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 
		new Cleverfox_Customizer_Range_Slider_Control( $wp_customize, 'site_desc_size', 
			array(
				'label'      => __( 'Site Description Font Size', 'clever-fox' ),
				'section'  => 'title_tagline',
				'input_attrs' => array(
					'min'    => 0,
					'max'    => 100,
					'step'   => 1,
					//'suffix' => 'px', //optional suffix
				),
			) ) 
		);
		
		//Pro feature
		class Webique_site_typo__upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme	
			?>
				<a class="customizer_site_typo_upgrade_section up-to-pro" href="https://www.nayrathemes.com/webique-pro/" target="_blank""><?php esc_html_e('Unlock By Upgrade to Pro','clever-fox'); ?></a>
				
			<?php
			}
		}
		
		$wp_customize->add_setting( 'webique_site__upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Webique_site_typo__upgrade(
			$wp_customize,
			'webique_site__upgrade_to_pro',
				array(
					'section'				=> 'title_tagline',
				)
			)
		);
		
	}	
	
	
	/*=========================================
	Above Header Section
	=========================================*/
	$wp_customize->add_section(
        'above_header',
        array(
        	'priority'      => 2,
            'title' 		=> __('Above Header','clever-fox'),
			'panel'  		=> 'header_section',
		)
    );

	/*=========================================
	Social
	=========================================*/
	$wp_customize->add_setting(
		'hdr_social_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'hdr_social_head',
		array(
			'type' => 'hidden',
			'label' => __('Social Icons','clever-fox'),
			'section' => 'above_header',
			'priority' => 1,
		)
	);
	
	
	$wp_customize->add_setting( 
		'hide_show_social_icon' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'priority' => 1,
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_social_icon', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'clever-fox' ),
			'section'     => 'above_header',
			'type'        => 'checkbox'
		) 
	);
	
	/**
	 * Customizer Repeater
	 */
		$wp_customize->add_setting( 'social_icons', 
			array(
			 'sanitize_callback' => 'webique_repeater_sanitize',
			 'priority' => 2,
			 'default' => webique_get_social_icon_default()
		)
		);
		
		$wp_customize->add_control( 
			new WEBIQUE_Repeater( $wp_customize, 
				'social_icons', 
					array(
						'label'   => esc_html__('Social Icons','clever-fox'),
						'section' => 'above_header',
						'add_field_label'                   => esc_html__( 'Add New Social', 'clever-fox' ),
						'item_name'                         => esc_html__( 'Social', 'clever-fox' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);
	
	//Pro feature
		class Webique_social__section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>
			<a class="customizer_social_upgrade_section up-to-pro"  href="https://www.nayrathemes.com/webique-pro/" target="_blank"  style="display: none;"><?php esc_html_e('Upgrade to Pro','clever-fox'); ?></a>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'webique_social_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Webique_social__section_upgrade(
			$wp_customize,
			'webique_social_upgrade_to_pro',
				array(
					'section'				=> 'above_header',
				)
			)
		);	
	
	/*=========================================
	Email
	=========================================*/
	$wp_customize->add_setting(
		'hdr_top_email'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 11,
		)
	);

	$wp_customize->add_control(
	'hdr_top_email',
		array(
			'type' => 'hidden',
			'label' => __('Email','clever-fox'),
			'section' => 'above_header',
		)
	);
	$wp_customize->add_setting( 
		'hide_show_email_details' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'priority' => 12,
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_email_details', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'clever-fox' ),
			'section'     => 'above_header',
			'type'        => 'checkbox'
		) 
	);	
	
	// icon // 
	$wp_customize->add_setting(
    	'tlh_email_icon',
    	array(
	        'default' => 'fa-envelope',
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control(new Webique_Icon_Picker_Control($wp_customize, 
		'tlh_email_icon',
		array(
		    'label'   		=> __('Icon','clever-fox'),
		    'section' 		=> 'above_header',
			'iconset' => 'fa',
			
		))  
	);	
	// Mobile title // 
	$wp_customize->add_setting(
    	'tlh_email_title',
    	array(
	        'default'			=> __('Email Us','clever-fox'),
			'sanitize_callback' => 'webique_sanitize_text',
			'capability' => 'edit_theme_options',
			'transport'         => $selective_refresh,
			'priority' => 13,
		)
	);	

	$wp_customize->add_control( 
		'tlh_email_title',
		array(
		    'label'   		=> __('Title','clever-fox'),
		    'section' 		=> 'above_header',
			'type'		 =>	'text'
		)  
	);
	
	// Email Link // 
	$wp_customize->add_setting(
    	'tlh_email_link',
    	array(
			'default'			=> __('info@example.com','clever-fox'),
			'sanitize_callback' => 'webique_sanitize_text',
			'capability' => 'edit_theme_options',
			'priority' => 14,
		)
	);	

	$wp_customize->add_control( 
		'tlh_email_link',
		array(
		    'label'   		=> __('Link','clever-fox'),
		    'section' 		=> 'above_header',
			'type'		 =>	'text'
		)  
	);
	
	
	
	/*=========================================
	Mobile
	=========================================*/
	$wp_customize->add_setting(
		'hdr_top_mbl'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 16,
		)
	);

	$wp_customize->add_control(
	'hdr_top_mbl',
		array(
			'type' => 'hidden',
			'label' => __('Phone','clever-fox'),
			'section' => 'above_header',
			
		)
	);
	$wp_customize->add_setting( 
		'hide_show_mbl_details' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'priority' => 17,
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_mbl_details', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'clever-fox' ),
			'section'     => 'above_header',
			'type'        => 'checkbox'
		) 
	);	
	// icon // 
	$wp_customize->add_setting(
    	'tlh_mobile_icon',
    	array(
	        'default' => 'fa-whatsapp',
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control(new Webique_Icon_Picker_Control($wp_customize, 
		'tlh_mobile_icon',
		array(
		    'label'   		=> __('Icon','clever-fox'),
		    'section' 		=> 'above_header',
			'iconset' => 'fa',
			
		))  
	);
	
	// Mobile title // 
	$wp_customize->add_setting(
    	'tlh_mobile_title',
    	array(
	        'default'			=> __('Call Us','clever-fox'),
			'sanitize_callback' => 'webique_sanitize_text',
			'transport'         => $selective_refresh,
			'capability' => 'edit_theme_options',
			'priority' => 18,
		)
	);	

	$wp_customize->add_control( 
		'tlh_mobile_title',
		array(
		    'label'   		=> __('Title','clever-fox'),
		    'section' 		=> 'above_header',
			'type'		 =>	'text'
		)  
	);
	
	// Link // 
	$wp_customize->add_setting(
    	'tlh_mobile_link',
    	array(
			'default'		=> __('987654321','clever-fox'),
			'sanitize_callback' => 'webique_sanitize_text',
			'capability' => 'edit_theme_options',
			'priority' => 19,
		)
	);	

	$wp_customize->add_control( 
		'tlh_mobile_link',
		array(			
		    'label'   		=> __('Link','clever-fox'),
		    'section' 		=> 'above_header',
			'type'		 =>	'text'
		)  
	);
	
	// Button
	$wp_customize->add_setting(
		'hdr_nav_btn'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 5,
		)
	);

	$wp_customize->add_control(
	'hdr_nav_btn',
		array(
			'type' => 'hidden',
			'label' => __('Button','webique'),
			'section' => 'above_header',
		)
	);
	

	$wp_customize->add_setting( 
		'hide_show_nav_btn' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'priority' => 6,
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_nav_btn', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'webique' ),
			'section'     => 'above_header',
			'type'        => 'checkbox'
		) 
	);	
		
	// Button Label // 
	$wp_customize->add_setting(
    	'nav_btn_lbl',
    	array(
			'sanitize_callback' => 'webique_sanitize_text',
			'capability' => 'edit_theme_options',
			'priority' => 7,
		)
	);	

	$wp_customize->add_control( 
		'nav_btn_lbl',
		array(
		    'label'   		=> __('Button Label','webique'),
		    'section' 		=> 'above_header',
			'type'		 =>	'text'
		)  
	);
	
	// Button Link // 
	$wp_customize->add_setting(
    	'nav_btn_link',
    	array(
			'sanitize_callback' => 'webique_sanitize_url',
			'capability' => 'edit_theme_options',
			'priority' => 8,
		)
	);	

	$wp_customize->add_control( 
		'nav_btn_link',
		array(
		    'label'   		=> __('Button Link','webique'),
		    'section' 		=> 'above_header',
			'type'		 =>	'text'
		)  
	);
	
	/*=========================================
	Header Animation Bar Section
	=========================================*/
	$wp_customize->add_section(
        'hdr_anim_bar',
        array(
        	'priority'      => 2,
            'title' 		=> __('Header Animation Bar','clever-fox'),
			'panel'  		=> 'header_section',
		)
    );
	
	$wp_customize->add_setting(
		'hdr_anim_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'hdr_anim_head',
		array(
			'type' => 'hidden',
			'label' => __('Animation Bar','clever-fox'),
			'section' => 'hdr_anim_bar',
		)
	);
	
	
	$wp_customize->add_setting( 
		'hide_show_hdr_anim_bar' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_hdr_anim_bar', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'clever-fox' ),
			'section'     => 'hdr_anim_bar',
			'type'        => 'checkbox',
			'settings'	  => 'hide_show_hdr_anim_bar'
		) 
	);
	
	/**
	 * Customizer Repeater
	 */
		$wp_customize->add_setting( 'header_marquee_titles', 
			array(
			 'sanitize_callback' => 'webique_repeater_sanitize',
			 'priority' => 2,
			 'default' => header_marquee_default()
		)
		);
		
		$wp_customize->add_control( 
			new WEBIQUE_Repeater( $wp_customize, 
				'header_marquee_titles', 
					array(
						'label'   => esc_html__('HeaderAnimation','clever-fox'),
						'section' => 'hdr_anim_bar',
						'add_field_label'                   => esc_html__( 'Add New Title', 'clever-fox' ),
						'settings'=> 'header_marquee_titles',
						'customizer_repeater_title_control' => true,
						'customizer_repeater_link_control' => true,
						'customizer_repeater_newtab_control' => true,
						'customizer_repeater_nofollow_control' => true,
					) 
				) 
			);
		
		//Pro feature
		class Webique_header_animation__section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>
			<a class="customizer_header_animation_upgrade_section up-to-pro"  href="https://www.nayrathemes.com/webique-pro/" target="_blank"  style="display: none;"><?php esc_html_e('Upgrade to Pro','clever-fox'); ?></a>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'webique_header_animation_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Webique_header_animation__section_upgrade(
			$wp_customize,
			'webique_header_animation_upgrade_to_pro',
				array(
					'section'				=> 'hdr_anim_bar',
				)
			)
		);	
}
add_action( 'customize_register', 'webique_lite_header_settings' );

// Header selective refresh
function webique_lite_header_partials( $wp_customize ){
	
	// hide_show_nav_btn
	$wp_customize->selective_refresh->add_partial(
		'hide_show_nav_btn', array(
			'selector' => '.navigator .av-button-area',
			'container_inclusive' => true,
			'render_callback' => 'header_navigation',
			'fallback_refresh' => true,
		)
	);
	// tlh_mobile_title
	$wp_customize->selective_refresh->add_partial( 'tlh_mobile_title', array(
		'selector'            => '#above-header .wgt-3 .title',
		'settings'            => 'tlh_mobile_title',
		'render_callback'  => 'webique_tlh_mobile_title_render_callback',
	) );
	
	// tlh_email_title
	$wp_customize->selective_refresh->add_partial( 'tlh_email_title', array(
		'selector'            => '#above-header .wgt-2 .title',
		'settings'            => 'tlh_email_title',
		'render_callback'  => 'webique_tlh_email_title_render_callback',
	) );
	
	// tlh_contact_title
	$wp_customize->selective_refresh->add_partial( 'tlh_contact_title', array(
		'selector'            => '#above-header .wgt-1 .title',
		'settings'            => 'tlh_contact_title',
		'render_callback'  => 'webique_tlh_contact_title_render_callback',
	) );
	}

add_action( 'customize_register', 'webique_lite_header_partials' );

// tlh_mobile_title
function webique_tlh_mobile_title_render_callback() {
	return get_theme_mod( 'tlh_mobile_title' );
}

// tlh_email_title
function webique_tlh_email_title_render_callback() {
	return get_theme_mod( 'tlh_email_title' );
}

// tlh_contact_title
function webique_tlh_contact_title_render_callback() {
	return get_theme_mod( 'tlh_contact_title' );
}

