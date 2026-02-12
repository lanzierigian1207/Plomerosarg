<?php
function webique_slider_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
$theme = wp_get_theme(); // gets the current theme
	/*=========================================
	Slider Section Panel
	=========================================*/
	$wp_customize->add_panel(
		'webique_frontpage_sections', array(
			'priority' => 32,
			'title' => esc_html__( 'Homepage Sections', 'clever-fox' ),
		)
	);
	
	$wp_customize->add_section(
		'slider_setting', array(
			'title' => esc_html__( 'Slider Section', 'clever-fox' ),
			'panel' => 'webique_frontpage_sections',
			'priority' => 1,
		)
	);
	
	
	// Setting Head
	$wp_customize->add_setting(
		'slider_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 1,
		)
	);

	$wp_customize->add_control(
	'slider_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Setting','clever-fox'),
			'section' => 'slider_setting',
		)
	);
	
	// Hide / Show 
	$wp_customize->add_setting(
		'slider_hs'
			,array(
			'default'     	=> '1',	
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'priority' => 1,
		)
	);

	$wp_customize->add_control(
	'slider_hs',
		array(
			'type' => 'checkbox',
			'label' => __('Hide / Show','clever-fox'),
			'section' => 'slider_setting',
		)
	);
	
	// slider Contents
	$wp_customize->add_setting(
		'slider_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'slider_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Contents','clever-fox'),
			'section' => 'slider_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add slides
	 */
	 $theme = wp_get_theme();
	 if($theme->name == 'Websy'):
	
		$wp_customize->add_setting( 'slider', 
			array(
			 'sanitize_callback' => 'webique_repeater_sanitize',
			  'default' => webique_get_slider_default()
			)
		);
		
		$wp_customize->add_control( 
		new Webique_Repeater( $wp_customize, 
			'slider', 
				array(
					'label'   => esc_html__('Slide','clever-fox'),
					'section' => 'slider_setting',
					'add_field_label'                   => esc_html__( 'Add New Slider', 'clever-fox' ),
					'item_name'                         => esc_html__( 'Slider', 'clever-fox' ),					
					'customizer_repeater_title_control' => true,
					'customizer_repeater_subtitle_control' => true,
					'customizer_repeater_subtitle2_control' => true,
					'customizer_repeater_description_control' => true,
					'customizer_repeater_button_text_control'=> true,
					'customizer_repeater_button_link_control' => true,
					'customizer_repeater_newtab_control' => true,
					'customizer_repeater_nofollow_control' => true,
					'customizer_repeater_image2_control' => true,
				) 
			) 
		);
		
		else:
		
		$wp_customize->add_setting( 'slider', 
			array(
			 'sanitize_callback' => 'webique_repeater_sanitize',
			  'default' => webique_get_slider_default()
			)
		);
		
		$wp_customize->add_control( 
		new Webique_Repeater( $wp_customize, 
			'slider', 
				array(
					'label'   => esc_html__('Slide','clever-fox'),
					'section' => 'slider_setting',
					'add_field_label'                   => esc_html__( 'Add New Slider', 'clever-fox' ),
					'item_name'                         => esc_html__( 'Slider', 'clever-fox' ),					
					'customizer_repeater_title_control' => true,
					'customizer_repeater_subtitle_control' => true,
					'customizer_repeater_subtitle2_control' => true,
					'customizer_repeater_description_control' => true,
					'customizer_repeater_button_text_control'=> true,
					'customizer_repeater_button_link_control' => true,
					'customizer_repeater_newtab_control' => true,
					'customizer_repeater_nofollow_control' => true,
					'customizer_repeater_image_control' => true,
				) 
			) 
		);
	endif;
	
	//Pro feature
		class Webique_slider__section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme	
				if ( 'Websy' == $theme->name){
			?>		
				<a class="customizer_slider_upgrade_section up-to-pro" href="https://www.nayrathemes.com/websy-pro/" target="_blank" style="display: none;"><?php esc_html_e('Upgrade to Pro','clever-fox'); ?></a>
			<?php
				} else {
				?>		
				<a class="customizer_slider_upgrade_section up-to-pro" href="https://www.nayrathemes.com/webique-pro/" target="_blank" style="display: none;"><?php esc_html_e('Upgrade to Pro','clever-fox'); ?></a>
			<?php
			}}
		}
		
		$wp_customize->add_setting( 'webique_slider_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Webique_slider__section_upgrade(
			$wp_customize,
			'webique_slider_upgrade_to_pro',
				array(
					'section'				=> 'slider_setting',
				)
			)
		);
		
	
	// slider opacity
	$overlay_color	= '0.6';
	if ( class_exists( 'Cleverfox_Customizer_Range_Slider_Control' ) ) {
		$wp_customize->add_setting(
			'slider_opacity',
			array(
				'default'	      => $overlay_color,
				'capability'     	=> 'edit_theme_options',
				//'sanitize_callback' => 'webique_sanitize_range_value',
				'priority' => 7,
			)
		);
		$wp_customize->add_control( 
		new Cleverfox_Customizer_Range_Slider_Control( $wp_customize, 'slider_opacity', 
			array(
				'label'      => __( 'opacity', 'clever-fox' ),
				'section'  => 'slider_setting',
				 'input_attrs' => array(
					'min'    => 0,
					'max'    => 0.9,
					'step'   => 0.1,
					//'suffix' => 'px', //optional suffix
				),
			) ) 
		);
	}	
	
	$wp_customize->add_setting(
	'slide_overlay_color', 
	array(
		'default'	      => '#000000',
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
    ));
	
	$wp_customize->add_control( 
		new WP_Customize_Color_Control
		($wp_customize, 
			'slide_overlay_color', 
			array(
				'label'      => __( 'Overlay Color', 'clever-fox' ),
				'section'    => 'slider_setting'
			) 
		) 
	);
	
	//Slider Autoplay
	$wp_customize->add_setting( 
		'slider_autoplay', 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_select',
			'priority' => 10,
		) 
	);
	$wp_customize->add_control('slider_autoplay', array(
    'label' => __('Slider Autoplay', 'clever-fox'),
    'section' => 'slider_setting',
	'type'			=> 'select',
	'choices'        => 
			array(
				'1'		=>__('Yes', 'clever-fox'),
				'0'=>__('No', 'clever-fox'),
			) 
	));
	
	// Slider Loop
	$wp_customize->add_setting( 
		'slider_loop' , 
			array(
			'default' => '0',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_select',
			'priority' => 10,
		) 
	);
	$wp_customize->add_control('slider_loop', array(
    'label' => __('Slider Loop', 'clever-fox'),
    'section' => 'slider_setting',
	'type'			=> 'select',
	'choices'        => 
			array(
				'1'		=>__('Yes', 'clever-fox'),
				'0'=>__('No', 'clever-fox'),
			) 
	));	
	
	//Pro feature
		class Webique_slider_pro_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>		
				<a class="customizer_slider_upgrade_section up-to-pro" href="https://www.nayrathemes.com/webique-pro/" target="_blank"><?php esc_html_e('Unlock By Upgrade to Pro','clever-fox'); ?></a>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'webique_slider_settings_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Webique_slider_pro_upgrade(
			$wp_customize,
			'webique_slider_settings_upgrade_to_pro',
				array(
					'section'				=> 'slider_setting',
				)
			)
		);
}

add_action( 'customize_register', 'webique_slider_setting' );

function webique_slider_partials( $wp_customize ){	
	// Slider controls
	$wp_customize->selective_refresh->add_partial(
		'slider', array(
			'selector' => '#slider-section .theme-content.text-left',
			'container_inclusive' => true,
			'render_callback' => 'slider_callback',
			'fallback_refresh' => true,
		)
	);
}

add_action( 'customize_register', 'webique_slider_partials' );

function slider_callback() {
	return get_theme_mod( 'slider' );
}