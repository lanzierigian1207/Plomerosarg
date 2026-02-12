<?php
if( ! function_exists( 'cleverfox_webique_dynamic_styles' ) ):
    function cleverfox_webique_dynamic_styles() {
		$output_css = '';
		
		$theme = wp_get_theme(); // gets the current theme
		
		/**
		 * Logo Width 
		 */
		 $logo_width			= get_theme_mod('logo_width','250');		 
		if($logo_width !== '') { 
				$output_css .=".logo img, .mobile-logo img {
					max-width: clamp(0px, " .esc_attr($logo_width). "px, 100%);
				}\n";
			}
		
		/**
		 * Slider
		 */
		$slider_opacity						 = get_theme_mod('slider_opacity','0.6');
		
		$output_css .=".theme-slider {
			background: rgba(0, 0, 0, $slider_opacity);
		}\n";
		
		
		/**
		 * CTA
		 */
		 $cta_bg_setting		= get_theme_mod('cta_bg_setting',esc_url(CLEVERFOX_PLUGIN_URL . 'inc/webique/images/slider/img01.jpg')); 
		$cta_bg_position	= get_theme_mod('cta_bg_position','scroll');	
				$output_css .=".cta-section {
					background-image: url(".esc_url($cta_bg_setting).");
					background-attachment: " .esc_attr($cta_bg_position). ";
				}\n";
		
		
		
		/**
		 *  Typography Body
		 */
		 $webique_body_text_transform	 	 = get_theme_mod('webique_body_text_transform','inherit');
		 $webique_body_font_style	 		 = get_theme_mod('webique_body_font_style','inherit');
		 $webique_body_font_size	 		 = get_theme_mod('webique_body_font_size','15');
		 $webique_body_line_height		 = get_theme_mod('webique_body_line_height','1.5');
		
		 $output_css .=" body{ 
			font-size: " .esc_attr($webique_body_font_size). "px;
			line-height: " .esc_attr($webique_body_line_height). ";
			text-transform: " .esc_attr($webique_body_text_transform). ";
			font-style: " .esc_attr($webique_body_font_style). ";
		}\n";		 
		
		/**
		 *  Typography Heading
		 */
		 for ( $i = 1; $i <= 6; $i++ ) {	
			 $webique_heading_text_transform 	= get_theme_mod('webique_h' . $i . '_text_transform','inherit');
			 $webique_heading_font_style	 	= get_theme_mod('webique_h' . $i . '_font_style','inherit');
			 $webique_heading_font_size	 		 = get_theme_mod('webique_h' . $i . '_font_size');
			 $webique_heading_line_height		 	 = get_theme_mod('webique_h' . $i . '_line_height');
			 
			 $output_css .=" h" . $i . "{ 
				font-size: " .esc_attr($webique_heading_font_size). "px;
				line-height: " .esc_attr($webique_heading_line_height). ";
				text-transform: " .esc_attr($webique_heading_text_transform). ";
				font-style: " .esc_attr($webique_heading_font_style). ";
			}\n";
		 }
		 		
		/**
		 * Features
		 */
		 $features_bg_img					= get_theme_mod('features_bg_img',get_template_directory_uri() .'/assets/images/features/bg1.png');		
		 $features_right_img				= get_theme_mod('features_right_img',get_template_directory_uri() .'/assets/images/features/features.png'); 
		  
				$output_css .=".features-image {
					background: url(".esc_url($features_right_img).");
			}\n";
			$output_css .=".features-section::before {
					background: url(".esc_url($features_bg_img).");
			}\n";
			
			
	 wp_add_inline_style( 'webique-style', $output_css );
    }
endif;
add_action( 'wp_enqueue_scripts', 'cleverfox_webique_dynamic_styles' );
?>