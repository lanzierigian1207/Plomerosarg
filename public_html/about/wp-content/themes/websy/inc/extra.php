<?php 
	
// Webique Navigation Button_two
if ( ! function_exists( 'websy_navigation_button_two' ) ) :
function websy_navigation_button_two() {
	$hide_show_nav_btn2 		= get_theme_mod( 'hide_show_nav_btn2','1');
	$nav_btn2_lbl 				= get_theme_mod( 'nav_btn2_lbl');
	$nav_btn2_icon 				= get_theme_mod( 'nav_btn2_icon','fa-user');
	$nav_btn2_link 				= get_theme_mod( 'nav_btn2_link','#');
	$hdr_btn2_new_tab 			= get_theme_mod( 'hdr_btn2_new_tab','1');
	if($hide_show_nav_btn2=='1'  && !empty($nav_btn2_lbl)):	
?>
	<a href="<?php echo esc_url($nav_btn2_link); ?>" class="text-white" target="<?php if($hdr_btn2_new_tab == '1'): echo '_blank'; endif; ?>"><i class="fa <?php echo esc_attr($nav_btn2_icon); ?> me-1"></i> <?php echo wp_kses_post($nav_btn2_lbl); ?></a>
<?php endif;
} 
endif;
add_action( 'websy_navigation_button_two', 'websy_navigation_button_two' );

// Webique Navigation Toggle
if ( ! function_exists( 'websy_navigation_toggle' ) ) :
function websy_navigation_toggle() {
	$hs_nav_toggle 				= get_theme_mod( 'hs_nav_toggle','1'); 
	if($hs_nav_toggle=='1'):	
?>
<li class="about-toggle-list">
	<div class="hamburger hamburger-about">	
		<button type="button" class="toggle-lines about-toggle style-2" data-target=".docker-widget-popup">
			<div class="top-bun"></div>
			<div class="meat"></div>
			<div class="bottom-bun"></div>
		</button>
		<!-- Docker Widget Popup -->
		<div class="docker-widget-popup">
			<div class="docker-overlay-layer"></div>
			<div class="docker-div">
				<div class="docker-anim">
					<button type="button" class="docker-widget-close close-style"></button>
					<div class="docker-widget">
						<?php webique_get_sidebars( 'webique-authot-sidebar' ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</li>
<?php endif; 
} 
endif;
add_action( 'websy_navigation_toggle', 'websy_navigation_toggle' );

/*
 *
 * Testimonial Default
 */
 function websy_get_testimonial_default() {
	return apply_filters(
		'websy_get_testimonial_default', json_encode(
				 array(
				array(
					'title'				=> esc_html__( 'Best Design', 'websy' ),
					'subtitle'			=> esc_html__( ' Dabus nisl aliquet congue tellus nascetur lectus sagittis mattis arcu dictumst augue volutpat felis etiam suspendisse rhoncus mauris dignissim ante Sagpien.', 'websy' ),
					'image_url'			=> get_stylesheet_directory_uri() . '/assets/images/testimonial/testimonial-img1.jpg',
					'text2'				=> esc_html__( '5', 'websy' ),
					'subtitle2'			=> esc_html__( 'Jasica Brown', 'websy' ),
					'text3'				=> esc_html__( 'Engineer', 'websy' ),	
					'id'              	=> 'customizer_repeater_testimonial_001',
				),
				array(
					'title'				=> esc_html__( 'Good Quality', 'websy' ),
					'subtitle'			=> esc_html__( ' Dabus nisl aliquet congue tellus nascetur lectus sagittis mattis arcu dictumst augue volutpat felis etiam suspendisse rhoncus mauris dignissim ante Sagpien.', 'websy' ),
					'image_url'			=> get_stylesheet_directory_uri() . '/assets/images/testimonial/testimonial-img2.jpg',
					'text2'				=> esc_html__( '5', 'websy' ),
					'subtitle2'			=> esc_html__( 'Jasica Brown', 'websy' ),
					'text3'				=> esc_html__( 'Engineer', 'websy' ),	
					'id'             	=> 'customizer_repeater_testimonial_002',
				),
				array(
					'title'				=> esc_html__( 'Reliable Service', 'websy' ),
					'subtitle'			=> esc_html__( ' Dabus nisl aliquet congue tellus nascetur lectus sagittis mattis arcu dictumst augue volutpat felis etiam suspendisse rhoncus mauris dignissim ante Sagpien.', 'websy' ),
					'image_url'			=> get_stylesheet_directory_uri() . '/assets/images/testimonial/testimonial-img3.jpg',
					'text2'				=> esc_html__( '5', 'websy' ),
					'subtitle2'				=> esc_html__( 'Jasica Brown', 'websy' ),
					'text3'				=> esc_html__( 'Engineer', 'websy' ),	
					'id'              	=> 'customizer_repeater_testimonial_003',
				),
				array(
					'title'				=> esc_html__( 'Recommended', 'websy' ),
					'subtitle'			=> esc_html__( ' Dabus nisl aliquet congue tellus nascetur lectus sagittis mattis arcu dictumst augue volutpat felis etiam suspendisse rhoncus mauris dignissim ante Sagpien.', 'websy' ),
					'image_url'			=> get_stylesheet_directory_uri() . '/assets/images/testimonial/testimonial-img4.jpg',
					'text2'				=> esc_html__( '5', 'websy' ),
					'subtitle2'				=> esc_html__( 'Jasica Brown', 'websy' ),
					'text3'				=> esc_html__( 'Engineer', 'websy' ),	
					'id'              	=> 'customizer_repeater_testimonial_004',
				),
				array(					
					'title'				=> esc_html__( 'Best Interface', 'websy' ),
					'subtitle'			=> esc_html__( ' Dabus nisl aliquet congue tellus nascetur lectus sagittis mattis arcu dictumst augue volutpat felis etiam suspendisse rhoncus mauris dignissim ante Sagpien.', 'websy' ),
					'image_url'			=> get_stylesheet_directory_uri() . '/assets/images/testimonial/testimonial-img5.jpg',
					'text2'				=> esc_html__( '5', 'websy' ),
					'subtitle2'				=> esc_html__( 'Jasica Brown', 'websy' ),
					'text3'				=> esc_html__( 'Engineer', 'websy' ),					
					'id'              	=> 'customizer_repeater_testimonial_005',
				),								
			)
		)
	);
}
