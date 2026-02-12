<?php
/**
 * @package nexcraft
 */

require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/extras.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/dynamic-style.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/features/nexcraft-slider.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/features/nexcraft-info.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/features/nexcraft-service.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/features/nexcraft-project.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/features/nexcraft-cta.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/features/nexcraft-features.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/features/nexcraft-funfact.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/features/nexcraft-client.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/features/nexcraft-typography.php';

if ( ! function_exists( 'cleverfox_nexcraft_frontpage_sections' ) ) :
	function cleverfox_nexcraft_frontpage_sections() {	
		require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/sections/section-slider.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/sections/section-info-1.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/sections/section-service.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/sections/section-project.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/sections/section-cta.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/sections/section-features.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/sections/section-funfact.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/nexcraft/sections/section-client.php';
    }
	add_action( 'nexcraft_sections', 'cleverfox_nexcraft_frontpage_sections' );
endif;

set_theme_mod('tlh_mobile_icon','fa-phone');
set_theme_mod('tlh_mobile_number',__('+70 975 975 70','clever-fox'));

set_theme_mod('tlh_email_icon','fa-envelope');
set_theme_mod('tlh_email','email@company.com');

set_theme_mod('tlh_office_hours_icon','fa-clock');
set_theme_mod('tlh_office_hours',__('09:00 Am - 07:00 Pm','clever-fox'));

set_theme_mod('tlh_appointment_btn_lbl',__('Get Free Quote','clever-fox'));

/*Footer */
set_theme_mod('footer_top_contact_icon','fa-hand-point-right');
set_theme_mod('footer_top_contact_title',__('Contact Us','clever-fox'));

set_theme_mod('footer_get_in_touch_icon','fa-phone');
set_theme_mod('footer_get_in_touch_title',__('Call Us Now','clever-fox'));
set_theme_mod('footer_get_in_touch_number',__('+12 345 678 90','clever-fox'));

set_theme_mod('footer_email_icon','fa-envelope');
set_theme_mod('footer_email_title',__('Eamil','clever-fox'));
set_theme_mod('footer_email','email@company.com');

set_theme_mod('footer_contct_icon','fa-location-arrow');
set_theme_mod('footer_address_title',__('Location','clever-fox'));
set_theme_mod('footer_contact_address',__('St Klida Main Road','clever-fox'));