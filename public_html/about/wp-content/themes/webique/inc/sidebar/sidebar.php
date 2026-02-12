<?php	
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package webique
 */

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */

function webique_widgets_init() {	
	register_sidebar( array(
		'name' => __( 'Sidebar Widget Area', 'webique' ),
		'id' => 'webique-sidebar-primary',
		'description' => __( 'The Primary Widget Area', 'webique' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h5 class="widget-title"><span></span>',
		'after_title' => '</h5>',
	) );	
	 
	register_sidebar( array(
		'name' => __( 'Footer Column 1', 'webique' ),
		'id' => 'webique-footer-1',
		'description' => __( 'The Footer Widget Area', 'webique' ),
		'before_widget' => ' <div class=" widget %2$s col-12 col-sm-6 col-lg-3 wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms"><aside class="%1$s">',
		'after_widget' => '</aside></div>',
		'before_title' => '<h5 class="widget-title">',
		'after_title' => '</h5>',
	) );
	 
	register_sidebar( array(
		'name' => __( 'Footer Column 2', 'webique' ),
		'id' => 'webique-footer-2',
		'description' => __( 'The Footer Widget Area', 'webique' ),
		'before_widget' => ' <div class=" widget %2$s col-12 col-sm-6 col-lg-3 wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms"><aside class="%1$s">',
		'after_widget' => '</aside></div>',
		'before_title' => '<h5 class="widget-title">',
		'after_title' => '</h5>',
	) );
	 
	register_sidebar( array(
		'name' => __( 'Footer Column 3', 'webique' ),
		'id' => 'webique-footer-3',
		'description' => __( 'The Footer Widget Area', 'webique' ),
		'before_widget' => ' <div class=" widget %2$s col-12 col-sm-6 col-lg-3 wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms"><aside class="%1$s">',
		'after_widget' => '</aside></div>',
		'before_title' => '<h5 class="widget-title">',
		'after_title' => '</h5>',
	) );
	 
	register_sidebar( array(
		'name' => __( 'Footer Column 4', 'webique' ),
		'id' => 'webique-footer-4',
		'description' => __( 'The Footer Widget Area', 'webique' ),
		'before_widget' => ' <div class=" widget %2$s col-12 col-sm-6 col-lg-3 wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms"><aside class="%1$s">',
		'after_widget' => '</aside></div>',
		'before_title' => '<h5 class="widget-title">',
		'after_title' => '</h5>',
	) );
	
	register_sidebar( array(
		'name' => __( 'WooCommerce Widget Area', 'webique' ),
		'id' => 'webique-woocommerce-sidebar',
		'description' => __( 'This Widget area for WooCommerce Widget', 'webique' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h5 class="widget-title">',
		'after_title' => '</h5>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Author Widget Area', 'webique' ),
		'id' => 'webique-authot-sidebar',
		'description' => __( 'This widget area is for the Author widget', 'webique' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h5 class="widget-title">',
		'after_title' => '</h5>',
	) );	
}
add_action( 'widgets_init', 'webique_widgets_init' );
?>