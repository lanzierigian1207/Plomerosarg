<?php
function webique_client_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Client  Section
	=========================================*/
	$wp_customize->add_section(
		'client_setting', array(
			'title' => esc_html__( 'Client Section', 'clever-fox' ),
			'priority' => 9,
			'panel' => 'webique_frontpage_sections',
		)
	);

	// Client content Section // 
	
	$wp_customize->add_setting(
		'client_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'client_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','clever-fox'),
			'section' => 'client_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add client
	 */
	
		$wp_customize->add_setting( 'client_contents', 
			array(
			 'sanitize_callback' => 'webique_repeater_sanitize',
			 // 'transport'         => $selective_refresh,
			 'priority' => 8,
			 'default' => webique_get_client_default()
			)
		);
		
		$wp_customize->add_control( 
			new Webique_Repeater( $wp_customize, 
				'client_contents', 
					array(
						'label'   => esc_html__('Client','clever-fox'),
						'section' => 'client_setting',
						'add_field_label'                   => esc_html__( 'Add New Client', 'clever-fox' ),
						'item_name'                         => esc_html__( 'Client', 'clever-fox' ),
						'customizer_repeater_image_control' => true,
						'customizer_repeater_link_control' => true,
						'customizer_repeater_newtab_control' => true,
						'customizer_repeater_nofollow_control' => true,
					) 
				) 
			);
			
		//Pro feature
		class Webique_client__section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme	
			?>
			<a class="customizer_client_upgrade_section up-to-pro" href="https://www.nayrathemes.com/webique-pro/" target="_blank" style="display: none;"><?php esc_html_e('Upgrade to Pro','clever-fox'); ?></a>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'webique_client_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Webique_client__section_upgrade(
			$wp_customize,
			'webique_client_upgrade_to_pro',
				array(
					'section'				=> 'client_setting',
				)
			)
		);
		
	// client column // 
	$wp_customize->add_setting(
    	'client_sec_column',
    	array(
	        'default'			=> '5',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_select',
			'priority' => 9,
		)
	);	

$wp_customize->add_control(
		'client_sec_column',
		array(
		    'label'   		=> __('Client Column','clever-fox'),
		    'section' 		=> 'client_setting',
			'settings'   	 => 'client_sec_column',
			'type'			=> 'select',
			'choices'        => 
			array(
				'2' => __( '2 Column', 'clever-fox' ),
				'3' => __( '3 Column', 'clever-fox' ),
				'4' => __( '4 Column', 'clever-fox' ),
				'5' => __( '5 Column', 'clever-fox' )
			) 
		) 
	);
	
	//Pro feature
		class Webique_client_col__section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme	
			?>
			<a class="customizer_client__upgrade_section up-to-pro" href="https://www.nayrathemes.com/webique-pro/" target="_blank"><?php esc_html_e('Unlock By Upgrade to Pro','clever-fox'); ?></a>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'webique_client_col_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Webique_client_col__section_upgrade(
			$wp_customize,
			'webique_client_col_upgrade_to_pro',
				array(
					'section'				=> 'client_setting',
				)
			)
		);
}

add_action( 'customize_register', 'webique_client_setting' );

function webique_client_partials( $wp_customize ){
	// client_contents
	$wp_customize->selective_refresh->add_partial( 'client_contents', array(
		'selector'            => '.client-section .client-slider',
	) );
	
	}
add_action( 'customize_register', 'webique_client_partials' );