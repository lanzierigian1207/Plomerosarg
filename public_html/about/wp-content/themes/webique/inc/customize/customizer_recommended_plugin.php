<?php
/* Notifications in customizer */


require get_template_directory() . '/inc/customizer-notify/webique-customizer-notify.php';
$webique_config_customizer = array(
	'recommended_plugins'       => array(
		'clever-fox' => array(
			'recommended' => true,
			'description' => sprintf(__('Install and activate <strong>Cleverfox</strong> plugin for taking full advantage of all the features this theme has to offer.', 'webique')),
		),
	),
	'recommended_actions'       => array(),
	'recommended_actions_title' => esc_html__( 'Recommended Actions', 'webique' ),
	'recommended_plugins_title' => esc_html__( 'Recommended Plugin', 'webique' ),
	'install_button_label'      => esc_html__( 'Install and Activate', 'webique' ),
	'activate_button_label'     => esc_html__( 'Activate', 'webique' ),
	'webique_deactivate_button_label'   => esc_html__( 'Deactivate', 'webique' ),
);
Webique_Customizer_Notify::init( apply_filters( 'webique_customizer_notify_array', $webique_config_customizer ) );
?>