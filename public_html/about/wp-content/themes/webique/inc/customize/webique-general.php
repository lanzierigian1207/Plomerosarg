<?php
function webique_general_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	$wp_customize->add_panel(
		'webique_general', array(
			'priority' => 31,
			'title' => esc_html__( 'General', 'webique' ),
		)
	);
	
	
	
	/*=========================================
	Scroller
	=========================================*/
	$wp_customize->add_section(
		'top_scroller', array(
			'title' => esc_html__( 'Scroller', 'webique' ),
			'priority' => 4,
			'panel' => 'webique_general',
		)
	);
	
	$wp_customize->add_setting( 
		'hs_scroller' , 
			array(
			'default' => '1',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'capability' => 'edit_theme_options',
			'priority' => 1,
		) 
	);
	
	$wp_customize->add_control(
	'hs_scroller', 
		array(
			'label'	      => esc_html__( 'Hide / Show Scroller', 'webique' ),
			'section'     => 'top_scroller',
			'type'        => 'checkbox'
		) 
	);
	
	// Scroller icon // 
	$wp_customize->add_setting(
    	'scroller_icon',
    	array(
	        'default' => 'fa-arrow-up',
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control(new Webique_Icon_Picker_Control($wp_customize, 
		'scroller_icon',
		array(
		    'label'   		=> __('Scroller Icon','webique'),
		    'section' 		=> 'top_scroller',
			'iconset' => 'fa',
			
		))  
	);
	
	//Pro feature
		class Webique_scroller_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme	
			?>
				<a class="customizer_scroller_upgrade_section up-to-pro" href="https://www.nayrathemes.com/webique-pro/" target="_blank"><?php esc_html_e('Unlock By Upgrade to Pro','webique'); ?></a>
				
			<?php
			}
		}
		
		$wp_customize->add_setting( 'webique_scroller_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Webique_scroller_section_upgrade(
			$wp_customize,
			'webique_scroller_upgrade_to_pro',
				array(
					'section'				=> 'top_scroller',
				)
			)
		);
	
	/*=========================================
	Breadcrumb  Section
	=========================================*/
	$wp_customize->add_section(
		'breadcrumb_setting', array(
			'title' => esc_html__( 'Breadcrumb', 'webique' ),
			'priority' => 12,
			'panel' => 'webique_general',
		)
	);
	
	// Settings
	$wp_customize->add_setting(
		'breadcrumb_settings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 1,
		)
	);

	$wp_customize->add_control(
	'breadcrumb_settings',
		array(
			'type' => 'hidden',
			'label' => __('Settings','webique'),
			'section' => 'breadcrumb_setting',
		)
	);
	
	// Breadcrumb Hide/ Show Setting // 
	$wp_customize->add_setting( 
		'hs_breadcrumb' , 
			array(
			'default' => '1',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'capability' => 'edit_theme_options',
			'priority' => 2,
		) 
	);
	
	$wp_customize->add_control(
	'hs_breadcrumb', 
		array(
			'label'	      => esc_html__( 'Hide / Show Section', 'webique' ),
			'section'     => 'breadcrumb_setting',
			'type'        => 'checkbox'
		) 
	);
	
	// enable Effect
	$wp_customize->add_setting(
		'breadcrumb_effect_enable'
			,array(
			'default' => '1',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'breadcrumb_effect_enable',
		array(
			'type' => 'checkbox',
			'label' => __('Enable Water Effect on Breadcrumb?','webique'),
			'section' => 'breadcrumb_setting',
		)
	);
	
	// Breadcrumb Content Section // 
	$wp_customize->add_setting(
		'breadcrumb_contents'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 5,
		)
	);

	$wp_customize->add_control(
	'breadcrumb_contents',
		array(
			'type' => 'hidden',
			'label' => __('Content','webique'),
			'section' => 'breadcrumb_setting',
		)
	);
	
	// Content size // 
	if ( class_exists( 'Cleverfox_Customizer_Range_Slider_Control' ) ) {
		$wp_customize->add_setting(
			'breadcrumb_min_height',
			array(
				'default' => 246,
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'webique_sanitize_range_value',
				'transport'         => 'postMessage',
				'priority' => 8,
			)
		);
		$wp_customize->add_control( 
			new Cleverfox_Customizer_Range_Slider_Control( $wp_customize, 'breadcrumb_min_height', 
				array(
					'label'      => __( 'Min Height', 'webique'),
					'section'  => 'breadcrumb_setting',
					'input_attrs' => array(
						'min'    => 1,
						'max'    => 1000,
						'step'   => 1,
						//'suffix' => 'px', //optional suffix
					),
				) ) 
			);
	}	
		
	// Background // 
	$wp_customize->add_setting(
		'breadcrumb_bg_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 9,
		)
	);

	$wp_customize->add_control(
	'breadcrumb_bg_head',
		array(
			'type' => 'hidden',
			'label' => __('Background','webique'),
			'section' => 'breadcrumb_setting',
		)
	);
	
	// Background Image // 
    $wp_customize->add_setting( 
    	'breadcrumb_bg_img' , 
    	array(
			'default' 			=> esc_url(get_template_directory_uri() .'/assets/images/breadcrumb/breadcrumb.jpg'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_url',	
			'priority' => 10,
		) 
	);
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize , 'breadcrumb_bg_img' ,
		array(
			'label'          => esc_html__( 'Background Image', 'webique'),
			'section'        => 'breadcrumb_setting',
		) 
	));
	
	// Background Attachment // 
	$wp_customize->add_setting( 
		'breadcrumb_back_attach' , 
			array(
			'default' => 'scroll',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_select',
			'priority'  => 10,
		) 
	);
	
	$wp_customize->add_control(
	'breadcrumb_back_attach' , 
		array(
			'label'          => __( 'Background Attachment', 'webique' ),
			'section'        => 'breadcrumb_setting',
			'type'           => 'select',
			'choices'        => 
			array(
				'inherit' => __( 'Inherit', 'webique' ),
				'scroll' => __( 'Scroll', 'webique' ),
				'fixed'   => __( 'Fixed', 'webique' )
			) 
		) 
	);
	
	/*=========================================
	Webique Container
	=========================================*/
	$wp_customize->add_section(
        'webique_container',
        array(
        	'priority'      => 2,
            'title' 		=> __('Container','webique'),
			'panel'  		=> 'webique_general',
		)
    );
	
	if ( class_exists( 'Cleverfox_Customizer_Range_Slider_Control' ) ) {
		//container width
		$wp_customize->add_setting(
			'webique_site_cntnr_width',
			array(
				'default'			=> '1200',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'webique_sanitize_range_value',
				'transport'         => 'postMessage',
				'priority'      => 1,
			)
		);
		$wp_customize->add_control( 
		new Cleverfox_Customizer_Range_Slider_Control( $wp_customize, 'webique_site_cntnr_width', 
			array(
				'label'      => __( 'Container Width', 'webique' ),
				'section'  => 'webique_container',
				'input_attrs' => array(
					 'min'           => 768,
					'max'           => 2000,
					'step'          => 1,
					//'suffix' => 'px', //optional suffix
				),
			) ) 
		);
		
	}
}

add_action( 'customize_register', 'webique_general_setting' );
