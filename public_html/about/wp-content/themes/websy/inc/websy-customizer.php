<?php
/**
 * Websy Theme Customizer.
 *
 * @package Websy
 */

 if ( ! class_exists( 'Websy_Customizer' ) ) {

	/**
	 * Customizer Loader
	 *
	 * @since 1.0.0
	 */
	class Websy_Customizer {

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
			add_action( 'customize_controls_enqueue_scripts', 	   array( $this, 'websy_customizer_script' ) );
			}
		
		/**
		 * Add postMessage support for site title and description for the Theme Customizer.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */

		/**
		 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
		 */
		function websy_customizer_script() {
			 wp_enqueue_script( 'websy-customizer-section', get_stylesheet_directory_uri() .'/assets/js/customizer-section.js', array("jquery"),'', true  );	
		}	

	}
}// End if().

/**
 *  Kicking this off by calling 'get_instance()' method
 */
Websy_Customizer::get_instance();