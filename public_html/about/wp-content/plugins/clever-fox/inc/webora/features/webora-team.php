<?php
function webique_team_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Team  Section
	=========================================*/
	$wp_customize->add_section(
		'team_setting', array(
			'title' => esc_html__( 'Team Section', 'clever-fox' ),
			'priority' => 7,
			'panel' => 'webique_frontpage_sections',
		)
	);
	
	$wp_customize->add_setting( 
		'team_hs' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'team_hs', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'clever-fox' ),
			'section'     => 'team_setting',
			'type'        => 'checkbox'
		) 
	);	

	// Team Header Section // 
	$wp_customize->add_setting(
		'team_headings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'team_headings',
		array(
			'type' => 'hidden',
			'label' => __('Header','clever-fox'),
			'section' => 'team_setting',
		)
	);
	
	// Team Title // 
	$wp_customize->add_setting(
    	'team_title',
    	array(
	        'default'			=> 'Our <span class="primary-color">Team</span>',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'team_title',
		array(
		    'label'   => __('Title','clever-fox'),
		    'section' => 'team_setting',
			'type'           => 'text',
		)  
	);
	
	// Team Description // 
	$wp_customize->add_setting(
    	'team_description',
    	array(
	        'default'			=> __('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','clever-fox'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'team_description',
		array(
		    'label'   => __('Description','clever-fox'),
		    'section' => 'team_setting',
			'type'           => 'textarea',
		)  
	);

	// Team content Section // 
	
	$wp_customize->add_setting(
		'team_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'team_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','clever-fox'),
			'section' => 'team_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add team
	 */
	
		$wp_customize->add_setting( 'team_contents', 
			array(
			 'sanitize_callback' => 'webique_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'priority' => 8,
			 'default' => webora_get_team_default()
			)
		);
		
		$wp_customize->add_control( 
			new Webique_Repeater( $wp_customize, 
				'team_contents', 
					array(
						'label'   => esc_html__('Team','clever-fox'),
						'section' => 'team_setting',
						'add_field_label'                   => esc_html__( 'Add New Team', 'clever-fox' ),
						'item_name'                         => esc_html__( 'Team', 'clever-fox' ),
						'customizer_repeater_image_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_link_control' => true,
						'customizer_repeater_repeater_control' => true,
						'customizer_repeater_newtab_control' => true,
						'customizer_repeater_nofollow_control' => true,
					) 
				) 
			);

			//Pro feature
		class Webique_team__section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme	
				if ( 'Webora' == $theme->name){
			?>		
				<a class="customizer_team_upgrade_section up-to-pro" href="https://www.nayrathemes.com/webique-pro/" target="_blank" style="display: none;"><?php esc_html_e('Upgrade to Pro','clever-fox'); ?></a>
			<?php
			}}
		}
		
		$wp_customize->add_setting( 'webique_team_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Webique_team__section_upgrade(
			$wp_customize,
			'webique_team_upgrade_to_pro',
				array(
					'section'				=> 'team_setting',
				)
			)
		);

	// team column // 
	$wp_customize->add_setting(
    	'team_sec_column',
    	array(
	        'default'			=> '3',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_select',
			'priority' => 9,
		)
	);	

	$wp_customize->add_control(
		'team_sec_column',
		array(
		    'label'   		=> __('Team Column','clever-fox'),
		    'section' 		=> 'team_setting',
			'settings'   	 => 'team_sec_column',
			'type'			=> 'select',
			'choices'        => 
			array(
				'6' => __( '2 Column', 'clever-fox' ),
				'4' => __( '3 Column', 'clever-fox' ),
				'3' => __( '4 Column', 'clever-fox' ),
			) 
		) 
	);

	//Pro feature
		class Webique_team_pro_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>		
				<a class="customizer_team_upgrade_section up-to-pro" href="https://www.nayrathemes.com/webique-pro/" target="_blank"><?php esc_html_e('Unlock By Upgrade to Pro','clever-fox'); ?></a>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'webique_team_settings_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Webique_team_pro_upgrade(
			$wp_customize,
			'webique_team_settings_upgrade_to_pro',
				array(
					'section'				=> 'team_setting',
				)
			)
		);

}

add_action( 'customize_register', 'webique_team_setting' );

// team selective refresh
function webique_home_team_section_partials( $wp_customize ){	
	// team title
	$wp_customize->selective_refresh->add_partial( 'team_title', array(
		'selector'            => '.team-section .heading-default h1',
		'settings'            => 'team_title',
		'render_callback'  => 'webique_team_title_render_callback',
	
	) );
	
	// team description
	$wp_customize->selective_refresh->add_partial( 'team_description', array(
		'selector'            => '.team-section .heading-default p',
		'settings'            => 'team_description',
		'render_callback'  => 'webique_team_desc_render_callback',
	
	) );
	// team content
	$wp_customize->selective_refresh->add_partial( 'team_contents', array(
		'selector'            => '.team-section .av-columns-area'
	
	) );
	
	}

add_action( 'customize_register', 'webique_home_team_section_partials' );

// team title
function webique_team_title_render_callback() {
	return get_theme_mod( 'team_title' );
}

// team description
function webique_team_desc_render_callback() {
	return get_theme_mod( 'team_description' );
}