<?php
/**
 * @package   Webique
 */

require CLEVERFOX_PLUGIN_DIR . 'inc/webique/dynamic-style.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/webique/sections/header-animation-bar.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/webique/sections/above-footer.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/webique/features/webique-header.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/webique/features/webique-footer.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/websy/features/websy-header.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/webique/features/webique-slider.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/webique/features/webique-service.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/webique/features/webique-features.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/webique/features/webique-cta.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/websy/features/websy-testimonial.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/webique/features/webique-typography.php';

if ( ! function_exists( 'cleverfox_webique_frontpage_sections' ) ) :
	function cleverfox_webique_frontpage_sections() {
		require CLEVERFOX_PLUGIN_DIR . 'inc/websy/sections/section-slider.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/webique/sections/section-service.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/webique/sections/section-cta.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/webique/sections/section-features.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/websy/sections/section-testimonial.php';
    }
	add_action( 'webique_sections', 'cleverfox_webique_frontpage_sections' );
endif;

function websy_customize_remove( $wp_customize ) {
	 $wp_customize->remove_control('hdr_social_head');
	 	 $wp_customize->remove_control('hide_show_social_icon');
		 $wp_customize->remove_control('social_icons');
		 $wp_customize->remove_control('hdr_nav_btn');
	 	 $wp_customize->remove_control('hide_show_nav_btn');
		 $wp_customize->remove_control('nav_btn_lbl');
		 $wp_customize->remove_control('nav_btn_link');
		 
}
add_action( 'customize_register', 'websy_customize_remove' );
