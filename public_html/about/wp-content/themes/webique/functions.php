<?php
if ( ! function_exists( 'webique_setup' ) ) :
function webique_setup() {

/**
 * Define Theme Version
 */
define( 'WEBIQUE_THEME_VERSION', '2.5' );

// Root path/URI.
define( 'WEBIQUE_PARENT_DIR', get_template_directory() );
define( 'WEBIQUE_PARENT_URI', get_template_directory_uri() );

// Root path/URI.
define( 'WEBIQUE_PARENT_INC_DIR', WEBIQUE_PARENT_DIR . '/inc');
define( 'WEBIQUE_PARENT_INC_URI', WEBIQUE_PARENT_URI . '/inc');

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 */
	add_theme_support( 'title-tag' );
	
	add_theme_support( 'custom-header' );
	
	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );
	
	//Add selective refresh for sidebar widget
	add_theme_support( 'customize-selective-refresh-widgets' );
	
	/*
	 * Make theme available for translation.
	 */
	load_theme_textdomain( 'webique' );
		
	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary_menu' => esc_html__( 'Primary Menu', 'webique' )
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );
	
	
	add_theme_support('custom-logo');
	
	/*
	 * WooCommerce Plugin Support
	 */
	add_theme_support( 'woocommerce' );
	
	// Gutenberg wide images.
		add_theme_support( 'align-wide' );
	
	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'assets/css/editor-style.css', webique_google_font() ) );
	
	//Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'webique_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', 'webique_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function webique_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'webique_content_width', 1170 );
}
add_action( 'after_setup_theme', 'webique_content_width', 0 );


/**
 * All Styles & Scripts.
 */
require_once get_template_directory() . '/inc/enqueue.php';

/**
 * Nav Walker fo Bootstrap Dropdown Menu.
 */

require_once get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';

/**
 * Implement the Custom Header feature.
 */
require_once get_template_directory() . '/inc/custom-header.php';


/**
 * Called Breadcrumb
 */
require_once get_template_directory() . '/inc/breadcrumb/breadcrumb.php';

/**
 * Sidebar.
 */
require_once get_template_directory() . '/inc/sidebar/sidebar.php';

/**
 * Custom template tags for this theme.
 */
require_once get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require_once get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
 require_once get_template_directory() . '/inc/webique-customizer.php';


/**
 * Customizer additions.
 */
 require get_template_directory() . '/inc/customizer-repeater/functions.php';


/**
 * Called Pagination
 */
require_once get_template_directory() . '/inc/pagination/pagination.php';

/* Data Migration From Lite Chilren to Lite Parent */

function webique_child_theme_changes() {
    $current_theme = wp_get_theme();
    
    // Ensure we are switching from Webique parent
    if ($current_theme->get_template() !== 'webique') {
        return;
    }

    $parent_theme_mods = get_option('theme_mods_webique');
    if (!is_array($parent_theme_mods)) {
        return;
    }

    // Get current (new) theme slug
    $child_theme_slug = get_stylesheet(); // e.g., 'webique-child'

    $child_theme_mods = get_option("theme_mods_{$child_theme_slug}");
    if (!is_array($child_theme_mods)) {
        return;
    }

    // Apply all mods from child theme to active theme
    foreach ($child_theme_mods as $mod_key => $mod_value) {
		
	 // Handle if value is a JSON string
            if ( is_string( $mod_value ) ) {
                $decoded = json_decode( $mod_value, true );

                if ( json_last_error() === JSON_ERROR_NONE && is_array( $decoded ) ) {
                    $cleaned_array = [];

                    // If it's a list of arrays
                    foreach ( $decoded as $item ) {
                        if ( is_array( $item ) ) {
                            $cleaned_item = array_filter(
                                $item,
                                function( $value ) {
                                    return $value !== 'undefined';
                                }
                            );
                            $cleaned_array[] = $cleaned_item;
                        }
                    }

                    // Re-encode and assign back
                    $mod_value = json_encode( $cleaned_array );
                }
            }
        set_theme_mod($mod_key, $mod_value);
    }
}
add_action('after_switch_theme', 'webique_child_theme_changes');