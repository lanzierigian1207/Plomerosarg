<?php
function webique_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'webique_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => 'f84233',
		'width'                  => 2000,
		'height'                 => 200,
		'flex-height'            => true,
		'wp-head-callback'       => 'webique_header_style',
	) ) );
}
add_action( 'after_setup_theme', 'webique_custom_header_setup' );

if ( ! function_exists( 'webique_header_style' ) ) :

function webique_header_style() {
	$header_text_color = get_header_textcolor();

	?>
	<style type="text/css">
	<?php
		if ( ! display_header_text() ) :
	?>
		.site-title,
		.site-description {
			position: absolute;
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		else :
	?>
		body .header h4.site-title,
		body .header p.site-description {
			background: linear-gradient(-137deg, var(--sp-primary) 20%, #<?php echo esc_attr( $header_text_color ); ?> 100% );
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent
		}
	<?php endif; ?>
	</style>
	<?php
}
endif;
