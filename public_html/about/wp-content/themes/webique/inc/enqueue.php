<?php
 /**
 * Enqueue scripts and styles.
 */
function webique_scripts() {
	
	// Styles	
	wp_enqueue_style('animate-min',get_template_directory_uri().'/assets/css/animate.min.css');
	
	wp_enqueue_style('font-awesome-min',get_template_directory_uri().'/assets/css/font-awesome.min.css');

	wp_enqueue_style('owl-carousel-min',get_template_directory_uri().'/assets/css/owl.carousel.min.css');
	
	wp_enqueue_style('magnific-popup-min',get_template_directory_uri().'/assets/vendor/magnific-popup/magnific-popup.min.css');
	
	wp_enqueue_style('swiper-min',get_template_directory_uri().'/assets/vendor/swiper/swiper-bundle.min.css');
	
	wp_enqueue_style('bootstrap-min',get_template_directory_uri().'/assets/vendor/bootstrap/bootstrap.min.css');
	
	wp_enqueue_style('webique-default', get_template_directory_uri() . '/assets/css/color/default.css');
	
	wp_enqueue_style('webique-editor-style',get_template_directory_uri().'/assets/css/editor-style.css');
	
	wp_enqueue_style('webique-theme',get_template_directory_uri().'/assets/css/theme.css');
	
	wp_enqueue_style('webique-meanmenu', get_template_directory_uri() . '/assets/css/meanmenu.css');
	
	wp_enqueue_style('webique-woocommerce',get_template_directory_uri().'/assets/css/woo.css');

	wp_enqueue_style('webique-main', get_template_directory_uri() . '/assets/css/main.css');

	wp_style_add_data('webique-main', 'rtl', 'replace');

	wp_enqueue_style('webique-widgets',get_template_directory_uri().'/assets/css/widgets.css');

	wp_enqueue_style('webique-block-widgets',get_template_directory_uri().'/assets/css/block-widgets.css');

	wp_enqueue_style('webique-lagecy-widgets',get_template_directory_uri().'/assets/css/lagecy-widgets.css');
	
	wp_enqueue_style('webique-wc-blocks-style',get_template_directory_uri().'/assets/css/wc-blocks-style.css');

	wp_enqueue_style('webique-wc-blocks-vendors-style',get_template_directory_uri().'/assets/css/wc-blocks-vendors-style.css');

	wp_enqueue_style('webique-media-query', get_template_directory_uri() . '/assets/css/responsive.css');
	
	wp_enqueue_style( 'webique-style', get_stylesheet_uri() );
	
	// Scripts
	wp_enqueue_script( 'jquery' );
	
	wp_enqueue_script('wow-min', get_template_directory_uri() . '/assets/js/wow.min.js', array('jquery'), false, true);
	
	wp_enqueue_script('jarallax-min', get_template_directory_uri() . '/assets/js/jarallax.min.js', array('jquery'), false, true);
	
	wp_enqueue_script('jquery-ripples', get_template_directory_uri() . '/assets/js/jquery.ripples.min.js', array('jquery'),false, true);
	
	wp_enqueue_script('gsap-min', get_template_directory_uri() . '/assets/vendor/gsap/gsap.js', array('jquery'), false, true);

	wp_enqueue_script('scroll-trigger-min', get_template_directory_uri() . '/assets/vendor/gsap/scroll_trigger.min.js', array('jquery'), false, true);

	wp_enqueue_script('gsap-animation', get_template_directory_uri() . '/assets/vendor/gsap/gsap-animation.js', array('jquery'), false, true);
	
	wp_enqueue_script('magnific-popup', get_template_directory_uri() . '/assets/vendor/magnific-popup/magnific-popup.js', array('jquery'), false, true);
	
	wp_enqueue_script('waypoint-min', get_template_directory_uri() . '/assets/vendor/counter/waypoints-min.js', array('jquery'), false, true);

	wp_enqueue_script('counterup-min', get_template_directory_uri() . '/assets/vendor/counter/counterup.min.js', array('jquery'), false, true);
	
	wp_enqueue_script('mixitup-min', get_template_directory_uri() . '/assets/vendor/mixitup/mixitup.min.js', array('jquery'), false, true);
	
	wp_enqueue_script('swiper-min', get_template_directory_uri() . '/assets/vendor/swiper/swiper-bundle.min.js', array('jquery'), false, true);
	
	wp_enqueue_script('bootstrap-min', get_template_directory_uri() . '/assets/vendor/bootstrap/bootstrap.bundle.min.js', array('jquery'), true);

	wp_enqueue_script('owl-carousel-min', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array('jquery'), true);

	wp_enqueue_script('owl-carousel-filter', get_template_directory_uri() . '/assets/js/owlcarousel2-filter.min.js', array('jquery'), true);

	wp_enqueue_script('webique-custom', get_template_directory_uri() . '/assets/js/custom.js', array('jquery'), false, true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'webique_scripts' );

//Admin Enqueue for Admin
function webique_admin_enqueue_scripts(){
	wp_enqueue_style('webique-admin-style', get_template_directory_uri() . '/assets/css/admin.css');
	wp_enqueue_script( 'webique-admin-script', get_template_directory_uri() . '/assets/js/webique-admin-script.js', array( 'jquery' ), '', true );
    wp_localize_script( 'webique-admin-script', 'webique_ajax_object',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) )
    );
}
add_action( 'admin_enqueue_scripts', 'webique_admin_enqueue_scripts' );
?>