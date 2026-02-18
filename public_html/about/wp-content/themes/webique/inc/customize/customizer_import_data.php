<?php
class webique_import_dummy_data {

	private static $instance;

	public static function init( ) {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof webique_import_dummy_data ) ) {
			self::$instance = new webique_import_dummy_data;
			self::$instance->webique_setup_actions();
		}

	}

	/**
	 * Setup the class props based on the config array.
	 */
	

	/**
	 * Setup the actions used for this class.
	 */
	public function webique_setup_actions() {

		// Enqueue scripts
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'webique_import_customize_scripts' ), 0 );

	}
	
	

	public function webique_import_customize_scripts() {

	wp_enqueue_script( 'webique-import-customizer-js', get_template_directory_uri() . '/assets/js/webique-import-customizer.js', array( 'customize-controls' ) );
	}
}

$webique_import_customizers = array(

		'import_data' => array(
			'recommended' => true,
			
		),
);
webique_import_dummy_data::init( apply_filters( 'webique_import_customizer', $webique_import_customizers ) );