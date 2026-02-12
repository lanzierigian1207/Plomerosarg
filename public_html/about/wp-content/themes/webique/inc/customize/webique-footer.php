<?php
function webique_footer( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	// Footer Panel // 
	$wp_customize->add_panel( 
		'footer_section', 
		array(
			'priority'      => 34,
			'capability'    => 'edit_theme_options',
			'title'			=> __('Footer', 'webique'),
		) 
	);
	
		
	
	// Footer Setting Section // 
	$wp_customize->add_section(
        'footer_copy_Section',
        array(
            'title' 		=> __('Below Footer','webique'),
			'panel'  		=> 'footer_section',
			'priority'      => 4,
		)
    );
	
	// footer first text // 
	$webique_footer_copyright = esc_html__('Copyright &copy; [current_year] [site_title] | Powered by [theme_author]', 'webique' );
	$wp_customize->add_setting(
    	'footer_first_custom',
    	array(
			'default'			=> $webique_footer_copyright,
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'wp_kses_post',
		)
	);	

	$wp_customize->add_control( 
		'footer_first_custom',
		array(
		    'label'   		=> __('Copyright Text','webique'),
		    'section'		=> 'footer_copy_Section',
			'type' 			=> 'textarea',
			'transport'         => $selective_refresh,
		)  
	);		
	
	$wp_customize->add_setting(
		'bf_right'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 13,
		)
	);

	$wp_customize->add_control(
	'bf_right',
		array(
			'type' => 'hidden',
			'label' => __('Payments','webique'),
			'section' => 'footer_copy_Section',
		)
	);
	$wp_customize->add_setting( 'footer_second_payments', 
		array(
		 'sanitize_callback' => 'webique_repeater_sanitize',
		 // 'transport'         => $selective_refresh,
		 'default' => payments_card_default()
	));

	$wp_customize->add_control( 
		new Webique_Repeater( $wp_customize, 
		'footer_second_payments', 
			array(
				'label'   => esc_html__('Cards','webique'),
				'section' => 'footer_copy_Section',
				'add_field_label'                   => esc_html__( 'Add New Card', 'webique' ),
				'item_name'                         => esc_html__( 'Card', 'webique' ),
				'customizer_repeater_icon_control' => true,
				'customizer_repeater_link_control' => true,
				'customizer_repeater_newtab_control' => true,
				'customizer_repeater_nofollow_control' => true,
			) 
		) 
	);
	
	//Pro feature
		class Webique_footer_payment_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>					
				<a class="customizer_footer_card_upgrade_section up-to-pro" href="https://www.nayrathemes.com/webique-pro/" target="_blank" style="display: none;"><?php esc_html_e('Upgrade to Pro','webique'); ?></a>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'webique_footer_payment_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Webique_footer_payment_upgrade(
			$wp_customize,
			'webique_footer_payment_upgrade_to_pro',
				array(
					'section'				=> 'footer_copy_Section',
				)
			)
		);

	
	// Footer Background // 
	$wp_customize->add_section(
        'footer_background',
        array(
            'title' 		=> __('Footer Background','webique'),
			'panel'  		=> 'footer_section',
			'priority'      => 4,
		)
    );
	// enable Effect
	$wp_customize->add_setting(
		'footer_effect_enable'
			,array(
			'default' => '1',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'footer_effect_enable',
		array(
			'type' => 'checkbox',
			'label' => __('Enable Water Effect','webique'),
			'section' => 'footer_background',
		)
	);
	
	
	//  Color
	$wp_customize->add_setting(
	'footer_bg_color', 
	array(
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		'default' => '#0d0c44'
    ));
	
	$wp_customize->add_control( 
		new WP_Customize_Color_Control
		($wp_customize, 
			'footer_bg_color', 
			array(
				'label'      => __( 'Background Color', 'webique' ),
				'section'    => 'footer_background',
			) 
		) 
	);
	
	//  Image // 
    $wp_customize->add_setting( 
    	'footer_bg_img' , 
    	array(
			'default' 			=> esc_url(get_template_directory_uri() .'/assets/images/footer/footer_bg.jpg'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_url',	
		) 
	);
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize , 'footer_bg_img' ,
		array(
			'label'          => esc_html__( 'Background Image', 'webique'),
			'section'        => 'footer_background',
		) 
	));
	
	//  Opacity Color
	$wp_customize->add_setting(
	'footer_bg_opacity_clr', 
	array(
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		'default' => '#000000'
    ));
	
	$wp_customize->add_control( 
		new WP_Customize_Color_Control
		($wp_customize, 
			'footer_bg_opacity_clr', 
			array(
				'label'      => __( 'Opacity Color', 'webique' ),
				'section'    => 'footer_background',
			) 
		) 
	);
	
	// opacity
	if ( class_exists( 'Webique_Customizer_Range_Control' ) ) {
		$wp_customize->add_setting(
			'footer_bg_opacity',
			array(
				'default'	      => '0.75',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'webique_sanitize_range_value',
			)
		);
		$wp_customize->add_control( 
		new Webique_Customizer_Range_Control( $wp_customize, 'footer_bg_opacity', 
			array(
				'label'      => __( 'opacity', 'webique' ),
				'section'  => 'footer_background',
				 'media_query'   => false,
					'input_attr'    => array(
						'desktop' => array(
							'min'           => 0,
							'max'           => 0.9,
							'step'          => 0.1,
							'default_value' => 0.75,
						),
					),
			) ) 
		);
	}
	
	
}
add_action( 'customize_register', 'webique_footer' );
// Footer selective refresh
function webique_footer_partials( $wp_customize ){	
	//footer_above_content 
	$wp_customize->selective_refresh->add_partial( 'footer_above_content', array(
		'selector'            => '.footer-above .av-columns-area',
	) );
	
	// fci_email_title
	$wp_customize->selective_refresh->add_partial( 'fci_email_title', array(
		'selector'            => '.footer_info_mail h4',
		'settings'            => 'fci_email_title',
		'render_callback'  => 'webique_fci_email_title_render_callback',
	) );
	
	// fci_email_link
	$wp_customize->selective_refresh->add_partial( 'fci_email_link', array(
		'selector'            => '.footer_info_mail a',
		'settings'            => 'fci_email_link',
		'render_callback'  => 'webique_fci_email_link_render_callback',
	) );
	
	// fci_mobile_title
	$wp_customize->selective_refresh->add_partial( 'fci_mobile_title', array(
		'selector'            => '.footer_info_call h4',
		'settings'            => 'fci_mobile_title',
		'render_callback'  => 'webique_fci_mobile_title_render_callback',
	) );
	
	// fci_mobile_link
	$wp_customize->selective_refresh->add_partial( 'fci_mobile_link', array(
		'selector'            => '.footer_info_call a',
		'settings'            => 'fci_mobile_link',
		'render_callback'  => 'webique_fci_mobile_link_render_callback',
	) );
	
	// fchat1_title
	$wp_customize->selective_refresh->add_partial( 'fchat1_title', array(
		'selector'            => '.footer_sale_chat h4',
		'settings'            => 'fchat1_title',
		'render_callback'  => 'webique_fchat1_title_render_callback',
	) );
	
	// fchat1_subtitle
	$wp_customize->selective_refresh->add_partial( 'fchat1_subtitle', array(
		'selector'            => '.footer_sale_chat p',
		'settings'            => 'fchat1_subtitle',
		'render_callback'  => 'webique_fchat1_subtitle_render_callback',
	) );
	
	// fchat2_title
	$wp_customize->selective_refresh->add_partial( 'fchat2_title', array(
		'selector'            => '.footer_support_chat h4',
		'settings'            => 'fchat2_title',
		'render_callback'  => 'webique_fchat2_title_render_callback',
	) );
	
	// fchat2_subtitle
	$wp_customize->selective_refresh->add_partial( 'fchat2_subtitle', array(
		'selector'            => '.footer_support_chat p',
		'settings'            => 'fchat2_subtitle',
		'render_callback'  => 'webique_fchat2_subtitle_render_callback',
	) );
	
	// platform_reviews
	$wp_customize->selective_refresh->add_partial( 'platform_reviews', array(
		'selector'            => '.footer-download-slider',
	) );
	
	// companies_reviews
	$wp_customize->selective_refresh->add_partial( 'companies_reviews', array(
		'selector'            => '.footer-client-slider',
	) );
	
	// footer_bar_contents
	$wp_customize->selective_refresh->add_partial( 'footer_bar_contents', array(
		'selector'            => '.footer_marquee.marquee-section a',
	) );
	
	// footer_bottom_1
	$wp_customize->selective_refresh->add_partial( 'footer_bottom_1', array(
		'selector'            => '.footer-copyright .row',
	) );
	
	// footer_third_custom
	$wp_customize->selective_refresh->add_partial( 'footer_third_custom', array(
		'selector'            => '.footer-copyright .copyright-text',
		'settings'            => 'footer_third_custom',
		'render_callback'  => 'webique_footer_third_custom_render_callback',
	) );
	
	//footer_widget_middle_content
	$wp_customize->selective_refresh->add_partial( 'footer_widget_middle_content', array(
		'selector'            => '.footer-main .footer-info-overwrap',
		'settings'            => 'footer_widget_middle_content',
		'render_callback'  => 'webique_footer_widget_middle_content_render_callback',
	) );
	}

add_action( 'customize_register', 'webique_footer_partials' );


// copyright_content
function webique_footer_third_custom_render_callback() {
	return get_theme_mod( 'footer_third_custom' );
}

// fci_email_title
function webique_fci_email_title_render_callback() {
	return get_theme_mod( 'fci_email_title' );
}

// fci_email_link
function webique_fci_email_link_render_callback() {
	return get_theme_mod( 'fci_email_link' );
}

// fci_mobile_title
function webique_fci_mobile_title_render_callback() {
	return get_theme_mod( 'fci_mobile_title' );
}

// fci_mobile_link
function webique_fci_mobile_link_render_callback() {
	return get_theme_mod( 'fci_mobile_link' );
}

// fchat1_title
function webique_fchat1_title_render_callback() {
	return get_theme_mod( 'fchat1_title' );
}

// fchat1_subtitle
function webique_fchat1_subtitle_render_callback() {
	return get_theme_mod( 'fchat1_subtitle' );
}

// fchat2_title
function webique_fchat2_title_render_callback() {
	return get_theme_mod( 'fchat2_title' );
}

// fchat2_subtitle
function webique_fchat2_subtitle_render_callback() {
	return get_theme_mod( 'fchat2_subtitle' );
}

// footer_widget_middle_content
function webique_footer_widget_middle_content_render_callback() {
	return get_theme_mod( 'footer_widget_middle_content' );
}