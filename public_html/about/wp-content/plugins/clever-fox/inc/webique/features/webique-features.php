<?php
function webique_features_setting( $wp_customize ) {
$selective_refresh =  'refresh';
	/*=========================================
	Features  Section
	=========================================*/
	$wp_customize->add_section(
		'features_setting', array(
			'title' => esc_html__( 'Features Section', 'clever-fox' ),
			'priority' => 6,
			'panel' => 'webique_frontpage_sections',
		)
	);

	$wp_customize->add_setting( 
		'features_hs' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'features_hs', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'clever-fox' ),
			'section'     => 'features_setting',
			'type'        => 'checkbox'
		) 
	);
	
	// Features content Section // 
	
	$wp_customize->add_setting(
		'features_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'features_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Left Content','clever-fox'),
			'section' => 'features_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add features
	 */
	
		$wp_customize->add_setting( 'features_contents', 
			array(
			 'sanitize_callback' => 'webique_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'priority' => 8,
			 'default' => webique_get_features_default()
			)
		);
		
		$wp_customize->add_control( 
			new Webique_Repeater( $wp_customize, 
				'features_contents', 
					array(
						'label'   => esc_html__('Features','clever-fox'),
						'section' => 'features_setting',
						'add_field_label'                   => esc_html__( 'Add New Feature', 'clever-fox' ),
						'item_name'                         => esc_html__( 'Features', 'clever-fox' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_title_control' => true,
					) 
				) 
			);
			
		//Pro feature
		class Webique_features__section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme	
			?>
			<a class="customizer_features_upgrade_section up-to-pro" href="https://www.nayrathemes.com/webique-pro/" target="_blank" style="display: none;"><?php esc_html_e('Upgrade to Pro','clever-fox'); ?></a>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'webique_features_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Webique_features__section_upgrade(
			$wp_customize,
			'webique_features_upgrade_to_pro',
				array(
					'section'				=> 'features_setting',
				)
			)
		);
			

			
		//Image	
		 $wp_customize->add_setting( 
			'features_bg_img' , 
			array(
				'default' 			=> esc_url(get_template_directory_uri() .'/assets/images/features/bg1.png'),
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'webique_sanitize_url',	
				'priority' => 12,
			) 
		);
		
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize , 'features_bg_img' ,
			array(
				'label'          => esc_html__(  'Pattern Image', 'clever-fox'),
				'section'        => 'features_setting',
			) 
		));
		
		$wp_customize->add_setting(
			'features_bg_attachment',
			array(
				'default'			=> 'fixed',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'webique_sanitize_select',
			)
		);	

		$wp_customize->add_control(
			'features_bg_attachment',
			array(
				'label'   		=> __('Pattern Attachment','clever-fox'),
				'section' 		=> 'features_setting',
				'settings'   	 => 'features_bg_attachment',
				'type'			=> 'radio',
				'choices'        => 
				array(
					'scroll' => __( 'Scroll', 'clever-fox' ),
					'fixed' => __( 'Fixed', 'clever-fox' ),
					
				) 
			) 
		);		
		
		//Pro feature
		class Webique_features_bg_img_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme	
			?>
			<a class="customizer_features_upgrade_section up-to-pro" href="https://www.nayrathemes.com/webique-pro/" target="_blank"><?php esc_html_e('Unlock By Upgrade to Pro','clever-fox'); ?></a>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'webique_features_bg_img_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Webique_features_bg_img_section_upgrade(
			$wp_customize,
			'webique_features_bg_img_upgrade_to_pro',
				array(
					'section'				=> 'features_setting',
				)
			)
		);
		
		// features column // 
	$wp_customize->add_setting(
    	'features_sec_column',
    	array(
	        'default'			=> '6',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_select',
			'priority' => 9,
		)
	);	

	$wp_customize->add_control(
		'features_sec_column',
		array(
		    'label'   		=> __('Features Column','clever-fox'),
		    'section' 		=> 'features_setting',
			'settings'   	 => 'features_sec_column',
			'type'			=> 'select',
			'choices'        => 
			array(
				'12' => __( '1 Column', 'clever-fox' ),
				'6' => __( '2 Column', 'clever-fox' ),
			) 
		) 
	);
		
	//Pro feature
		class Webique_features_column_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme	
			?>
			<a class="customizer_features_upgrade_section up-to-pro" href="https://www.nayrathemes.com/webique-pro/" target="_blank"><?php esc_html_e('Unlock By Upgrade to Pro','clever-fox'); ?></a>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'webique_features_column_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Webique_features_column_section_upgrade(
			$wp_customize,
			'webique_features_column_upgrade_to_pro',
				array(
					'section'				=> 'features_setting',
				)
			)
		);
		
	
	// Features Right content // 
	
	$wp_customize->add_setting(
		'features_right_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 11,
		)
	);

	$wp_customize->add_control(
	'features_right_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Right Content','clever-fox'),
			'section' => 'features_setting',
		)
	);
	
	//  Image // 
    $wp_customize->add_setting( 
    	'features_right_img' , 
    	array(
			'default' 			=> esc_url(get_template_directory_uri() .'/assets/images/features/features.png'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_url',	
			'priority' => 12,
		) 
	);
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize , 'features_right_img' ,
		array(
			'label'          => esc_html__(  'Image', 'clever-fox'),
			'section'        => 'features_setting',
		) 
	));	
	
	$wp_customize->add_setting(
		'feature_background_attachment',
		array(
			'default'			=> 'fixed',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_select',
		)
	);	

	$wp_customize->add_control(
		'feature_background_attachment',
		array(
			'label'   		=> __('Background Attachment','clever-fox'),
			'section' 		=> 'features_setting',
			'settings'   	 => 'feature_background_attachment',
			'type'			=> 'radio',
			'choices'        => 
			array(
				'scroll' => __( 'Scroll', 'clever-fox' ),
				'fixed' => __( 'Fixed', 'clever-fox' ),
				
			) 
		) 
	);
	
	//Pro feature
		class Webique_feature_background_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme	
			?>
			<a class="customizer_features_upgrade_section up-to-pro" href="https://www.nayrathemes.com/webique-pro/" target="_blank"><?php esc_html_e('Unlock By Upgrade to Pro','clever-fox'); ?></a>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'webique_feature_background_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Webique_feature_background_section_upgrade(
			$wp_customize,
			'webique_feature_background_upgrade_to_pro',
				array(
					'section'				=> 'features_setting',
				)
			)
		);
		
}

add_action( 'customize_register', 'webique_features_setting' );

// features selective refresh
function webique_home_features_section_partials( $wp_customize ){	
	// features title
	$wp_customize->selective_refresh->add_partial( 'features_contents', array(
		'selector'            => '.features-home .features-item',
		'settings'            => 'features_contents',
		'render_callback'  => 'webique_features_title_render_callback',
	
	) );
	
	
	}

add_action( 'customize_register', 'webique_home_features_section_partials' );

function webique_features_title_render_callback() {
	return get_theme_mod( 'features_contents' );
}