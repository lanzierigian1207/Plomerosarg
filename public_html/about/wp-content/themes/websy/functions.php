<?php
/**
 * Define Theme Version
 */
define( 'WEBSY_THEME_VERSION', '3.3' );

function websy_css() {
	$parent_style = 'webique-parent-style';
	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'websy-style', get_stylesheet_uri(), array( $parent_style ));
	
	wp_enqueue_style('websy-color-default',get_stylesheet_directory_uri() .'/assets/css/color/default.css');
	wp_dequeue_style('webique-default');	
}
add_action( 'wp_enqueue_scripts', 'websy_css',999);

function websy_setup()	{	
	add_theme_support( 'woocommerce' );
	add_theme_support( "title-tag" );
	add_theme_support( 'automatic-feed-links' );
	
}
add_action( 'after_setup_theme', 'websy_setup' );

/**
 * Dynamic Styles
 */
if( ! function_exists( 'websy_dynamic_style' ) ):
    function websy_dynamic_style() {

		$output_css = '';
		
			
		 /**
		 *  Breadcrumb Style
		 */
		$websy_hs_breadcrumb	= get_theme_mod('hs_breadcrumb','1');	
		
		if($websy_hs_breadcrumb == '') { 
				$output_css .=".webique-content {
					padding-top: 200px;
				}\n";
			}
		
		
		/**
		 *  Parallax
		 */
	
    }
endif;
add_action( 'wp_enqueue_scripts', 'websy_dynamic_style',999);

function websy_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'webique_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => '4DB8F1',
		'width'                  => 2000,
		'height'                 => 200,
		'flex-height'            => true,
		'wp-head-callback'       => 'webique_header_style',
	) ) );
}
add_action( 'after_setup_theme', 'websy_custom_header_setup' );


/**
 * Called all the Customize file.
 */
require( get_stylesheet_directory() . '/inc/customize/websy-premium.php');
require( get_stylesheet_directory() . '/inc/websy-customizer.php');
require( get_stylesheet_directory() . '/inc/extra.php');


/**
 * Import Options From Parent Theme
 *
 */
function websy_parent_theme_options() {
	$webique_mods = get_option( 'theme_mods_webique' );
	if ( ! empty( $webique_mods ) ) {
		foreach ( $webique_mods as $webique_mod_k => $webique_mod_v ) {
			set_theme_mod( $webique_mod_k, $webique_mod_v );
		}
	}
}
add_action( 'after_switch_theme', 'websy_parent_theme_options' );
