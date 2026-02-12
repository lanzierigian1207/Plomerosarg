<?php

class Webique_Customizer_Notify {

	private $recommended_actions;

	
	private $recommended_plugins;

	
	private static $instance;

	
	private $recommended_actions_title;

	
	private $recommended_plugins_title;

	
	private $dismiss_button;

	
	private $install_button_label;

	
	private $activate_button_label;

	
	private $webique_deactivate_button_label;
	
	
	private $config;

	
	public static function init( $config ) {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Webique_Customizer_Notify ) ) {
			self::$instance = new Webique_Customizer_Notify;
			if ( ! empty( $config ) && is_array( $config ) ) {
				self::$instance->config = $config;
				self::$instance->setup_config();
				self::$instance->setup_actions();
			}
		}

	}

	
	public function setup_config() {

		global $webique_customizer_notify_recommended_plugins;
		global $webique_customizer_notify_recommended_actions;

		global $install_button_label;
		global $activate_button_label;
		global $webique_deactivate_button_label;

		$this->recommended_actions = isset( $this->config['recommended_actions'] ) ? $this->config['recommended_actions'] : array();
		$this->recommended_plugins = isset( $this->config['recommended_plugins'] ) ? $this->config['recommended_plugins'] : array();

		$this->recommended_actions_title = isset( $this->config['recommended_actions_title'] ) ? $this->config['recommended_actions_title'] : '';
		$this->recommended_plugins_title = isset( $this->config['recommended_plugins_title'] ) ? $this->config['recommended_plugins_title'] : '';
		$this->dismiss_button            = isset( $this->config['dismiss_button'] ) ? $this->config['dismiss_button'] : '';

		$webique_customizer_notify_recommended_plugins = array();
		$webique_customizer_notify_recommended_actions = array();

		if ( isset( $this->recommended_plugins ) ) {
			$webique_customizer_notify_recommended_plugins = $this->recommended_plugins;
		}

		if ( isset( $this->recommended_actions ) ) {
			$webique_customizer_notify_recommended_actions = $this->recommended_actions;
		}

		$install_button_label    = isset( $this->config['install_button_label'] ) ? $this->config['install_button_label'] : '';
		$activate_button_label   = isset( $this->config['activate_button_label'] ) ? $this->config['activate_button_label'] : '';
		$webique_deactivate_button_label = isset( $this->config['webique_deactivate_button_label'] ) ? $this->config['webique_deactivate_button_label'] : '';

	}

	
	public function setup_actions() {

		// Register the section
		add_action( 'customize_register', array( $this, 'webique_plugin_notification_customize_register' ) );

		// Enqueue scripts and styles
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'webique_customizer_notify_scripts_for_customizer' ), 0 );

		/* ajax callback for dismissable recommended actions */
		add_action( 'wp_ajax_quality_customizer_notify_dismiss_action', array( $this, 'webique_customizer_notify_dismiss_recommended_action_callback' ) );

		add_action( 'wp_ajax_ti_customizer_notify_dismiss_recommended_plugins', array( $this, 'webique_customizer_notify_dismiss_recommended_plugins_callback' ) );

	}

	
	public function webique_customizer_notify_scripts_for_customizer() {

		wp_enqueue_style( 'webique-customizer-notify-css', get_template_directory_uri() . '/inc/customizer-notify/css/webique-customizer-notify.css', array());

		wp_enqueue_style( 'webique-plugin-install' );
		wp_enqueue_script( 'webique-plugin-install' );
		wp_add_inline_script( 'webique-plugin-install', 'var pagenow = "customizer";' );

		wp_enqueue_script( 'webique-updates' );

		wp_enqueue_script( 'webique-customizer-notify-js', get_template_directory_uri() . '/inc/customizer-notify/js/webique-customizer-notify.js', array( 'customize-controls' ));
		wp_localize_script(
			'webique-customizer-notify-js', 'WebiqueCustomizercompanionObject', array(
				'webique_ajaxurl'            => esc_url(admin_url( 'admin-ajax.php' )),
				'webique_template_directory' => esc_url(get_template_directory_uri()),
				'webique_base_path'          => esc_url(admin_url()),
				'webique_activating_string'  => __( 'Activating', 'webique' ),
			)
		);

	}

	
	public function webique_plugin_notification_customize_register( $wp_customize ) {

		
		require_once get_template_directory() . '/inc/customizer-notify/webique-customizer-notify-section.php';

		$wp_customize->register_section_type( 'Webique_Customizer_Notify_Section' );

		$wp_customize->add_section(
			new Webique_Customizer_Notify_Section(
				$wp_customize,
				'Webique-customizer-notify-section',
				array(
					'title'          => $this->recommended_actions_title,
					'plugin_text'    => $this->recommended_plugins_title,
					'dismiss_button' => $this->dismiss_button,
					'priority'       => 0,
				)
			)
		);

	}

	
	public function webique_customizer_notify_dismiss_recommended_action_callback() {

		global $webique_customizer_notify_recommended_actions;

		$action_id = ( isset( $_GET['id'] ) ) ? $_GET['id'] : 0;

		echo esc_html($action_id); 

		if ( ! empty( $action_id ) ) {

			
			if ( get_theme_mod( 'webique_customizer_notify_show' ) ) {

				$webique_customizer_notify_show_recommended_actions = get_theme_mod( 'webique_customizer_notify_show' );
				switch ( $_GET['todo'] ) {
					case 'add':
						$webique_customizer_notify_show_recommended_actions[ $action_id ] = true;
						break;
					case 'dismiss':
						$webique_customizer_notify_show_recommended_actions[ $action_id ] = false;
						break;
				}
				echo esc_html($webique_customizer_notify_show_recommended_actions);
				
			} else {
				$webique_customizer_notify_show_recommended_actions = array();
				if ( ! empty( $webique_customizer_notify_recommended_actions ) ) {
					foreach ( $webique_customizer_notify_recommended_actions as $webique_lite_customizer_notify_recommended_action ) {
						if ( $webique_lite_customizer_notify_recommended_action['id'] == $action_id ) {
							$webique_customizer_notify_show_recommended_actions[ $webique_lite_customizer_notify_recommended_action['id'] ] = false;
						} else {
							$webique_customizer_notify_show_recommended_actions[ $webique_lite_customizer_notify_recommended_action['id'] ] = true;
						}
					}
					echo esc_html($webique_customizer_notify_show_recommended_actions);
				}
			}
		}
		die(); 
	}

	
	public function webique_customizer_notify_dismiss_recommended_plugins_callback() {

		$action_id = ( isset( $_GET['id'] ) ) ? $_GET['id'] : 0;

		echo esc_html($action_id); 

		if ( ! empty( $action_id ) ) {

			$webique_lite_customizer_notify_show_recommended_plugins = get_theme_mod( 'webique_customizer_notify_show_recommended_plugins' );

			switch ( $_GET['todo'] ) {
				case 'add':
					$webique_lite_customizer_notify_show_recommended_plugins[ $action_id ] = false;
					break;
				case 'dismiss':
					$webique_lite_customizer_notify_show_recommended_plugins[ $action_id ] = true;
					break;
			}
			echo esc_html($webique_customizer_notify_show_recommended_actions);
		}
		die(); 
	}

}
