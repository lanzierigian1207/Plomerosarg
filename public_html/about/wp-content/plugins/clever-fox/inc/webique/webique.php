<?php
/**
 * @package   Webique
 */

require CLEVERFOX_PLUGIN_DIR . 'inc/webique/dynamic-style.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/webique/sections/above-header.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/webique/sections/header-animation-bar.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/webique/sections/above-footer.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/webique/features/webique-header.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/webique/features/webique-footer.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/webique/features/webique-slider.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/webique/features/webique-features.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/webique/features/webique-service.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/webique/features/webique-cta.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/webique/features/webique-client.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/webique/features/webique-typography.php';

if ( ! function_exists( 'cleverfox_webique_frontpage_sections' ) ) :
	function cleverfox_webique_frontpage_sections() {
		require CLEVERFOX_PLUGIN_DIR . 'inc/webique/sections/section-slider.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/webique/sections/section-service.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/webique/sections/section-cta.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/webique/sections/section-features.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/webique/sections/section-client.php';
    }
	add_action( 'webique_sections', 'cleverfox_webique_frontpage_sections' );
endif;