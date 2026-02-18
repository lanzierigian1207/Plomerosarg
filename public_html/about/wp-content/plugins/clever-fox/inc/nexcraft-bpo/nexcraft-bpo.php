<?php
/**
 * @package NexCraft
 */

require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/extras.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/dynamic-style.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft-bpo/features/nexcraft-bpo-slider.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft-bpo/features/nexcraft-bpo-info.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/features/nexcraft-service.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/features/nexcraft-project.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/features/nexcraft-cta.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/features/nexcraft-features.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/features/nexcraft-typography.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft-bpo/features/nexcraft-bpo-testimonial.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft-bpo/features/nexcraft-bpo-team.php';

if ( ! function_exists( 'cleverfox_nexcraft_frontpage_sections' ) ) :
	function cleverfox_nexcraft_frontpage_sections() {	
		require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft-bpo/sections/section-slider.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft-bpo/sections/section-info-3.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/sections/section-service.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/sections/section-project.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/sections/section-cta.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/sections/section-features.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft-bpo/sections/section-testimonial.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft-bpo/sections/section-team.php';
    }
	add_action( 'nexcraft_sections', 'cleverfox_nexcraft_frontpage_sections' );
endif;

function nexcraft_bpo_customize_remove( $wp_customize ) {
	$wp_customize->remove_control('hdr_nav_toggle');
	$wp_customize->remove_control('hs_nav_toggle');
}
add_action( 'customize_register', 'nexcraft_bpo_customize_remove' );


set_theme_mod( 'tlh_mobile_icon', 'fa-mobile');
set_theme_mod( 'tlh_mobile_number', '70-975-975-70'); 
set_theme_mod( 'tlh_email_icon', 'fa-envelope');
set_theme_mod( 'tlh_email', __('email@email.com','clever-fox'));
set_theme_mod( 'tlh_office_hours_icon', 'fa-clock');
set_theme_mod( 'tlh_office_hours','11:00 - 20:00');
set_theme_mod( 'tlh_appointment_btn_lbl',__('Get Free Quote','clever-fox'));