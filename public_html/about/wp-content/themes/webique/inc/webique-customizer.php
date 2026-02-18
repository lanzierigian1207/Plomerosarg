<?php
/**
 * Webique Theme Customizer.
 *
 * @package Webique
 */

 if ( ! class_exists( 'Webique_Customizer' ) ) {

	/**
	 * Customizer Loader
	 *
	 * @since 1.0.0
	 */
	class Webique_Customizer {

		/**
		 * Instance
		 *
		 * @access private
		 * @var object
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			/**
			 * Customizer
			 */
			add_action( 'customize_preview_init',                  array( $this, 'webique_customize_preview_js' ) );
			add_action( 'customize_controls_enqueue_scripts', 	   array( $this, 'webique_customizer_script' ) );
			add_action( 'customize_register',                      array( $this, 'webique_customizer_register' ) );
			add_action( 'after_setup_theme',                       array( $this, 'webique_customizer_settings' ) );
		}
		
		/**
		 * Add postMessage support for site title and description for the Theme Customizer.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		function webique_customizer_register( $wp_customize ) {
			
			$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
			$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
			$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
			$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';
			$wp_customize->get_setting('custom_logo')->transport = 'refresh';

			/**
			 * Helper files
			 */
			require WEBIQUE_PARENT_INC_DIR . '/custom-controls/font-control.php';
			require WEBIQUE_PARENT_INC_DIR . '/sanitization.php';
		}

		/**
		 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
		 */
		function webique_customize_preview_js() {
			wp_enqueue_script( 'webique-customizer', WEBIQUE_PARENT_URI . '/assets/js/customizer-preview.js', array( 'customize-preview' ), '20151215', true );
		}
		
		function webique_customizer_script() {
			 wp_enqueue_script( 'webique-customizer-section', WEBIQUE_PARENT_URI .'/assets/js/customizer-section.js', array("jquery"),'', true  );	
		}

		// Include customizer customizer settings.
			
		function webique_customizer_settings() {
				require WEBIQUE_PARENT_INC_DIR . '/customize/webique-header.php';
				require WEBIQUE_PARENT_INC_DIR . '/customize/webique-blog.php';
				require WEBIQUE_PARENT_INC_DIR . '/customize/webique-footer.php';
				require WEBIQUE_PARENT_INC_DIR . '/customize/webique-general.php';
				require WEBIQUE_PARENT_INC_DIR . '/customize/webique-premium.php';
				require WEBIQUE_PARENT_INC_DIR . '/customize/customizer_recommended_plugin.php';
				require WEBIQUE_PARENT_INC_DIR . '/customize/customizer_import_data.php';
		}

	}
}// End if().

/**
 *  Kicking this off by calling 'get_instance()' method
 */
Webique_Customizer::get_instance();