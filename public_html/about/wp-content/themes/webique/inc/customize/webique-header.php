<?php
function webique_header_settings( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Header Settings Panel
	=========================================*/
	$wp_customize->add_panel( 
		'header_section', 
		array(
			'priority'      => 2,
			'capability'    => 'edit_theme_options',
			'title'			=> __('Header', 'webique'),
		) 
	);
	
	/*=========================================
	Webique Site Identity
	=========================================*/
	$wp_customize->add_section(
        'title_tagline',
        array(
        	'priority'      => 1,
            'title' 		=> __('Site Identity','webique'),
			'panel'  		=> 'header_section',
		)
    );

	/*=========================================
	Header Navigation
	=========================================*/	
	$wp_customize->add_section(
        'header_navigation',
        array(
        	'priority'      => 4,
            'title' 		=> __('Header Navigation','webique'),
			'panel'  		=> 'header_section',
		)
    );
	
	// Search
	$wp_customize->add_setting(
		'hdr_nav_search'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 1,
		)
	);

	$wp_customize->add_control(
	'hdr_nav_search',
		array(
			'type' => 'hidden',
			'label' => __('Search','webique'),
			'section' => 'header_navigation',
		)
	);
	$wp_customize->add_setting( 
		'hide_show_search' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'priority' => 2,
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_search', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'webique' ),
			'section'     => 'header_navigation',
			'type'        => 'checkbox'
		) 
	);	
	
	// Cart
	$wp_customize->add_setting(
		'hdr_nav_cart'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'hdr_nav_cart',
		array(
			'type' => 'hidden',
			'label' => __('Cart','webique'),
			'section' => 'header_navigation',
		)
	);
	
	$wp_customize->add_setting( 
		'hide_show_cart' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'priority' => 4,
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_cart', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'webique' ),
			'section'     => 'header_navigation',
			'type'        => 'checkbox'
		) 
	);	
	// Header Toggle
	$wp_customize->add_setting(
		'hdr_nav_toggle'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 11,
		)
	);

	$wp_customize->add_control(
	'hdr_nav_toggle',
		array(
			'type' => 'hidden',
			'label' => __('Toggle','webique'),
			'section' => 'header_navigation',
		)
	);
	$wp_customize->add_setting( 
		'hs_nav_toggle' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'priority' => 12,
		) 
	);
	
	$wp_customize->add_control(
	'hs_nav_toggle', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'webique' ),
			'section'     => 'header_navigation',
			'type'        => 'checkbox'
		) 
	);
	
	
	/*=========================================
	Sticky Header
	=========================================*/	
	$wp_customize->add_section(
        'sticky_header_set',
        array(
        	'priority'      => 4,
            'title' 		=> __('Sticky Header','webique'),
			'panel'  		=> 'header_section',
		)
    );
	
	// Heading
	$wp_customize->add_setting(
		'sticky_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_text',
			'priority' => 1,
		)
	);

	$wp_customize->add_control(
	'sticky_head',
		array(
			'type' => 'hidden',
			'label' => __('Sticky Header','webique'),
			'section' => 'sticky_header_set',
		)
	);
	$wp_customize->add_setting( 
		'hide_show_sticky' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'webique_sanitize_checkbox',
			'priority' => 2,
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_sticky', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'webique' ),
			'section'     => 'sticky_header_set',
			'type'        => 'checkbox'
		) 
	);	
}
add_action( 'customize_register', 'webique_header_settings' );
