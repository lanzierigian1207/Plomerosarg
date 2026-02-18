<?php 
	
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package webique
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function webique_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Parallax
	$footer_parallax_enable		= get_theme_mod('footer_parallax_enable','1');
	if($footer_parallax_enable=='1'):
		$classes[] = "footer-parallax";
	endif;
	// Themes
	$webique_theme	=	get_theme_mod('webique_theme','theme-1');
	$classes[] = esc_attr($webique_theme);
	
	return $classes;
}
add_filter( 'body_class', 'webique_body_classes' );

if ( ! function_exists( 'wp_body_open' ) ) {
	/**
	 * Backward compatibility for wp_body_open hook.
	 *
	 * @since 1.0.0
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

if(! function_exists('webique_pro_preloader')){
	function webique_pro_preloader(){ 		
	$hs_preloader	= get_theme_mod('hs_preloader');
	if($hs_preloader == '1'):
	?>
	<div class="prealoader">
		<div class="load-dot">
			<div class="dot"></div>
			<div class="dot"></div>
			<div class="dot"></div>
		</div>
	</div>
	<?php 
	endif;
	}
}
add_action('webique_pro_preloader','webique_pro_preloader');

// Webique Navigation
if ( ! function_exists( 'webique_primary_navigation' ) ) :
function webique_primary_navigation() {
	wp_nav_menu( 
		array(  
			'theme_location' => 'primary_menu',
			'container'  => '',
			'menu_class' => 'menu-wrap nav',
			'fallback_cb' => 'WP_Bootstrap_Navwalker::fallback',
			'walker' => new WP_Bootstrap_Navwalker()
			 ) 
		);
	} 
endif;
add_action( 'webique_primary_navigation', 'webique_primary_navigation' );	

/**
 * Function that returns if the menu is sticky
 */
if (!function_exists('webique_sticky_menu')):
    function webique_sticky_menu()
    {
        $is_sticky = get_theme_mod('hide_show_sticky','1');

        if ($is_sticky == '1'):
            return 'sticky-nav';
        else:
            return 'not-sticky';
        endif;
    }
endif;

// add_action('webique_sticky_menu','webique_sticky_menu');

// Webique Navigation Search
if ( ! function_exists( 'webique_navigation_search' ) ) :
function webique_navigation_search() {
	$hide_show_search 	= get_theme_mod( 'hide_show_search','1'); 
	if($hide_show_search=='1'):	
?>
<li class="search-button">
	<a href="javascript:void(0)" id="view-search-btn" class="header-search-toggle"><i class="fa fa-search"></i></a>
	<div class="view-search-btn header-search-popup">
		<form  method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Site Search', 'webique' ); ?>"> 
			<input type="search" class="search-form-control header-search-field" placeholder="<?php esc_attr_e( 'Type To Search', 'webique' ); ?>" name="s" id="search">
			<i class="fa fa-search"></i>
			<a href="javascript:void(0)" class="close-style header-search-close"></a>
		</form>
	</div>
</li>
<?php endif;
	} 
endif;
add_action( 'webique_navigation_search', 'webique_navigation_search' );


// webique Navigation Cart
if ( ! function_exists( 'webique_navigation_cart' ) ) :
function webique_navigation_cart() {
	$hide_show_cart 	= get_theme_mod( 'hide_show_cart','1'); 
		if($hide_show_cart=='1' && class_exists( 'WooCommerce' )):	
	?>
		<li class="cart-wrapper">
			<a href="javascript:void(0);" class="cart-icon-wrap" id="cart" title="View your shopping cart">
				<i class="fa fa-shopping-cart"></i>
				<?php 
					if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
						$count = WC()->cart->cart_contents_count;
						$cart_url = wc_get_cart_url();
						
						if ( $count > 0 ) {
						?>
							 <span><?php echo esc_html( $count ); ?></span>
						<?php 
						}
						else {
							?>
							<span><?php echo esc_html_e('0','webique'); ?></span>
							<?php 
						}
					}
				?>
			</a>
			<!-- Shopping Cart Start -->
			<div class="shopping-cart">
				<?php get_template_part('woocommerce/cart/mini','cart'); ?>
			</div>
			<!-- Shopping Cart End -->
		</li>
	<?php endif; 
	} 
endif;
add_action( 'webique_navigation_cart', 'webique_navigation_cart' );

/**
 * Webique Above Header Social
 */
if ( ! function_exists( 'webique_abv_hdr_social' ) ) {
	function webique_abv_hdr_social() {
		//above_header_first
		$hide_show_social_icon 		= get_theme_mod( 'hide_show_social_icon','1'); 
		$social_icons 				= get_theme_mod( 'social_icons',webique_get_social_icon_default());	
		
		 if($hide_show_social_icon == '1') { ?>
			<aside class="share-toolkit widget widget_social_widget">
				<ul>
					<?php
						$social_icons = json_decode($social_icons);
						if( $social_icons!='' )
						{
						foreach($social_icons as $index => $social_item){	
						$social_icon = ! empty( $social_item->icon_value ) ? apply_filters( 'webique_translate_single_string', $social_item->icon_value, 'Header section' ) : '';	
						$social_link = ! empty( $social_item->link ) ? apply_filters( 'webique_translate_single_string', $social_item->link, 'Header section' ) : '';
					?>
						<li><a <?php if($index == '0'): echo 'style="margin-left: 0;"'; endif; ?> href="<?php echo esc_url( $social_link ); ?>" class="zig-zag"><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i></a></li>
					<?php }} ?>
				</ul>
			</aside>
		<?php } 
	}
}
add_action( 'webique_abv_hdr_social', 'webique_abv_hdr_social' );

/*
 *
 * Social Icon
 */
function webique_get_social_icon_default() {
	return apply_filters(
		'webique_get_social_icon_default', json_encode(
				 array(
				array(
					'icon_value'	  =>  esc_html__( 'fa-facebook', 'webique' ),
					'link'	  =>  esc_html__( '#', 'webique' ),
					'id'              => 'customizer_repeater_header_social_001',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-google-plus', 'webique' ),
					'link'	  =>  esc_html__( '#', 'webique' ),
					'id'              => 'customizer_repeater_header_social_002',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-twitter', 'webique' ),
					'link'	  =>  esc_html__( '#', 'webique' ),
					'id'              => 'customizer_repeater_header_social_003',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-linkedin', 'webique' ),
					'link'	  =>  esc_html__( '#', 'webique' ),
					'id'              => 'customizer_repeater_header_social_004',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-behance', 'webique' ),
					'link'	  =>  esc_html__( '#', 'webique' ),
					'id'              => 'customizer_repeater_header_social_005',
				)
			)
		)
	);
}

// Webique Pro Logo
if ( ! function_exists( 'webique_logo_content' ) ) :
function webique_logo_content() {
		if(has_custom_logo())
			{	
				the_custom_logo();
			}
			$theme_title = get_bloginfo( 'name');
			if ($theme_title) :
			?>
			
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" >
				<h4 class="site-title m-0">
					<?php 
						echo esc_html(get_bloginfo('name'));
					?>
				</h4>
			</a>	
		<?php 						
			endif;
	
			$webique_description = get_bloginfo( 'description');
			if ($webique_description) : ?>
				<p class="site-description"><?php echo esc_html($webique_description); ?></p>
		<?php endif;
	} 
endif;
add_action( 'webique_logo_content', 'webique_logo_content' );


// Webique Navigation Toggle
if ( ! function_exists( 'webique_navigation_toggle' ) ) :
function webique_navigation_toggle() {
	$hs_nav_toggle 				= get_theme_mod( 'hs_nav_toggle','1'); 
	// $webique_toggle_content 	= get_theme_mod( 'webique_toggle_content','Lorem ipsum is simply dummy text here...');
	$webique_theme		=	get_theme_mod('webique_theme','theme-3');
	if($hs_nav_toggle=='1'):	
?>
<li class="about-toggle-list">
	<div class="hamburger hamburger-about">	
		<button type="button" class="toggle-lines about-toggle <?php if($webique_theme=='theme-2') echo 'style-2'; ?>" data-target=".docker-widget-popup">
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
add_action( 'webique_navigation_toggle', 'webique_navigation_toggle' );

// Webique Navigation Button
if ( ! function_exists( 'webique_navigation_button' ) ) :
function webique_navigation_button() {
	$hide_show_nav_btn 		= get_theme_mod( 'hide_show_nav_btn','1');
	$nav_btn_lbl 			= get_theme_mod( 'nav_btn_lbl','Consult Now');
	$nav_btn_link 			= get_theme_mod( 'nav_btn_link','#');
	$hdr_btn_new_tab 			= get_theme_mod( 'hdr_btn_new_tab','1');
	if($hide_show_nav_btn=='1'  && !empty($nav_btn_lbl)):	
?>
	<a href="<?php echo esc_url($nav_btn_link); ?>" class="av-btn av-btn-primary av-btn-bubble hdr-btn-1" target="<?php if($hdr_btn_new_tab == '1'): echo '_blank'; endif; ?>"><?php echo wp_kses_post($nav_btn_lbl); ?></a>
<?php endif;
	} 
endif;
add_action( 'webique_navigation_button', 'webique_navigation_button' );

// Webique Navigation Button2
if ( ! function_exists( 'webique_navigation_button2' ) ) :
function webique_navigation_button2() {
	$hide_show_nav_btn2 		= get_theme_mod( 'hide_show_nav2_btn','1');
	$nav_btn2_lbl 			= get_theme_mod( 'nav_btn2_lbl','Consult Now');
	$nav_btn2_link 			= get_theme_mod( 'nav_btn2_link','#');
	$hdr_btn2_new_tab 			= get_theme_mod( 'hdr_btn2_new_tab','1');
	if($hide_show_nav2_btn=='1'  && !empty($nav_btn2_lbl)):	
?>
	<a href="<?php echo esc_url($nav_btn2_link); ?>" class="av-btn av-btn-primary av-btn-bubble" target="<?php if($hdr_btn2_new_tab == '1'): echo '_blank'; endif; ?>"><?php echo wp_kses_post($nav_btn2_lbl); ?></a>
<?php endif;
	} 
endif;
add_action( 'webique_navigation_button2', 'webique_navigation_button2' );


/**
 * Display Sidebars
 */
if ( ! function_exists( 'webique_get_sidebars' ) ) {
	/**
	 * Get Sidebar
	 *
	 * @since 1.0
	 * @param  string $sidebar_id   Sidebar Id.
	 * @return void
	 */
	function webique_get_sidebars( $sidebar_id ) {
		if ( is_active_sidebar( $sidebar_id ) ) {
			dynamic_sidebar( $sidebar_id );
		} elseif ( current_user_can( 'edit_theme_options' ) ) {
			?>
			<div class="widget">
				<p class='no-widget-text'>
					<a href='<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>'>
						<?php esc_html_e( 'Add Widgets', 'webique' ); ?>
					</a>
				</p>
			</div>
			<?php
		}
	}
}

/**
 * Get registered sidebar name by sidebar ID.
 *
 * @since  1.0.0
 * @param  string $sidebar_id Sidebar ID.
 * @return string Sidebar name.
 */
function webique_get_sidebar_name_by_id( $sidebar_id = '' ) {

	if ( ! $sidebar_id ) {
		return;
	}

	global $wp_registered_sidebars;
	$sidebar_name = '';

	if ( isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
		$sidebar_name = $wp_registered_sidebars[ $sidebar_id ]['name'];
	}

	return $sidebar_name;
}

if (!function_exists('webique_str_replace_assoc')) {

    /**
     * webique_str_replace_assoc
     * @param  array $replace
     * @param  array $subject
     * @return array
     */
    function webique_str_replace_assoc(array $replace, $subject) {
        return str_replace(array_keys($replace), array_values($replace), $subject);
    }
}

// Excerpt Read More
if ( ! function_exists( 'webique_execerpt_link' ) ) :
function webique_execerpt_link() {
	$enable_post_btn = get_theme_mod( 'enable_post_btn');
	$read_btn_txt = get_theme_mod( 'read_btn_txt','Read More');
	if ( $enable_post_btn == '1' ) { 
	?>
	<a href="<?php echo esc_url(get_permalink());?>" class="more-link "><?php echo esc_html($read_btn_txt); ?></a>
<?php } 
	} 
endif;

// Custom excerpt length
function custom_excerpt_length( $length ) {
	 $webique_post_excerpt = get_theme_mod('webique_post_excerpt','30');
    if( $webique_post_excerpt == 1000 ) {
        return 9999;
    }
    return esc_html( $webique_post_excerpt );
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );



// excerpt more
function webique_excerpt_more( $more ) {
	return get_theme_mod('webique_blog_excerpt_more','&hellip;');;
}
add_filter( 'excerpt_more', 'webique_excerpt_more' );


// Comments Counts
if ( ! function_exists( 'webique_comment_count' ) ) :
function webique_comment_count() {
	$webique_comments_count 	= get_comments_number();
	if ( 0 === intval( $webique_comments_count ) ) {
		echo esc_html__( '0 Comments', 'webique' );
	} else {
		/* translators: %s Comment number */
		 echo sprintf( _n( '%s Comment', '%s Comments', $webique_comments_count, 'webique' ), number_format_i18n( $webique_comments_count ) );
	}
} 
endif;

// Webique Footer Group First
if ( ! function_exists( 'webique_footer_group_first' ) ) :
function webique_footer_group_first() {
	$footer_bottom_1 			= get_theme_mod('footer_bottom_1','custom');	
	$footer_first_custom 		= get_theme_mod('footer_first_custom','Copyright &copy; [current_year] | Powered by [theme_author]');
	$footer_first_payments			= get_theme_mod('footer_first_payments', payments_card_default());
	// payments
		 if($footer_bottom_1 == 'payments'): ?>
			<ul>
			<?php 
			if(!empty($footer_first_payments)) { 
			$footer_first_payments = json_decode( $footer_first_payments );
			foreach ( $footer_first_payments as $payment_item ) {
				$icon = ! empty( $payment_item->icon_value ) ? apply_filters( 'webique_translate_single_string', $payment_item->icon_value, 'footer payment section' ) : '';					
				$link = ! empty( $payment_item->link ) ? apply_filters( 'webique_translate_single_string', $payment_item->link, 'footer payment section' ) : '';$newtab = ! empty( $payment_item->newtab ) ? apply_filters( 'webique_translate_single_string', $payment_item->newtab, 'footer payment section' ) : '';
				$nofollow = ! empty( $payment_item->nofollow ) ? apply_filters( 'webique_translate_single_string', $payment_item->nofollow, 'footer payment section' ) : '';
			?>
				<li><a href="<?php echo esc_url($link); ?>" <?php if($newtab =='yes') {echo 'target="_blank"'; } ?> rel="<?php if($newtab =='yes') {echo 'noreferrer noopener'; } ?> <?php if($nofollow =='yes') {echo 'nofollow'; } ?>"><i class="fa <?php echo esc_attr($icon); ?>"></i></a></li>
			<?php }} ?>	
			</ul>
		 <?php endif; 
		
		// Custom
		 if($footer_bottom_1 == 'custom'): ?>
			<?php 	
				$webique_copyright_allowed_tags = array(
					'[current_year]' => date_i18n('Y', current_time( 'timestamp' )),
					'[site_title]'   => get_bloginfo('name'),
					'[theme_author]' => sprintf(__('<a href="#">Webique</a>', 'webique')),
				);
			?>                        
				<p class="theme-copyright">
					<?php
						echo apply_filters('webique_footer_copyright', wp_kses_post(webique_str_replace_assoc($webique_copyright_allowed_tags, $footer_first_custom)));
					?>
				</p>
		<?php endif; 
		
		// Widget
		 if($footer_bottom_1 == 'widget'): ?>
			<?php  webique_get_sidebars( 'webique-footer-layout-first' ); ?>
		<?php endif; 
		
		// Menu
		 if($footer_bottom_1 == 'menu'): ?>
			<aside class="widget widget_nav_menu">
				<div class="menu-pages-container">
					<?php 
						wp_nav_menu( 
							array(  
								'theme_location' => 'footer_menu',
								'container'  => '',
								'menu_class' => 'menu',
								'fallback_cb' => 'WP_Bootstrap_Navwalker::fallback',
								'walker' => new WP_Bootstrap_Navwalker()
								 ) 
							);
					?>   
				</div>
			</aside>	
		<?php endif; ?>
	<?php 
	} 
endif;	
add_action('webique_footer_group_first','webique_footer_group_first');

// Webique Footer Group Second
if ( ! function_exists( 'webique_footer_group_second' ) ) :
function webique_footer_group_second() {
	$footer_bottom_2 				= get_theme_mod('footer_bottom_2','payments');	
	$footer_second_custom 			= get_theme_mod('footer_second_custom');
	$footer_second_payments			= get_theme_mod('footer_second_payments', payments_card_default());
			
		// payments
		 if($footer_bottom_2 == 'payments'): ?>
			<ul>
			<?php 
			if(!empty($footer_second_payments)) { 
			$footer_second_payments = json_decode( $footer_second_payments );
			foreach ( $footer_second_payments as $payment_item ) {
				$icon = ! empty( $payment_item->icon_value ) ? apply_filters( 'webique_translate_single_string', $payment_item->icon_value, 'footer payment section' ) : '';					
				$link = ! empty( $payment_item->link ) ? apply_filters( 'webique_translate_single_string', $payment_item->link, 'footer payment section' ) : '';
				;$newtab = ! empty( $payment_item->newtab ) ? apply_filters( 'webique_translate_single_string', $payment_item->newtab, 'footer payment section' ) : '';
				$nofollow = ! empty( $payment_item->nofollow ) ? apply_filters( 'webique_translate_single_string', $payment_item->nofollow, 'footer payment section' ) : '';
			?>
				<li><a href="<?php echo esc_url($link); ?>" <?php if($newtab =='yes') {echo 'target="_blank"'; } ?> rel="<?php if($newtab =='yes') {echo 'noreferrer noopener'; } ?> <?php if($nofollow =='yes') {echo 'nofollow'; } ?>"><i class="fa <?php echo esc_attr($icon); ?>"></i></a></li>
			<?php }} ?>	
			</ul>
		 <?php endif; 
		
		// Custom
		 if($footer_bottom_2 == 'custom'): 
			echo  do_shortcode($footer_second_custom);
		 endif; 
		
		// Widget
		 if($footer_bottom_2 == 'widget'): ?>
			<?php  webique_get_sidebars( 'webique-footer-layout-second' ); ?>
		<?php endif; 
		
		// Menu
		 if($footer_bottom_2 == 'menu'): ?>
			<aside class="widget widget_nav_menu">
				<div class="menu-pages-container">
					<?php 
						wp_nav_menu( 
							array(  
								'theme_location' => 'footer_menu',
								'container'  => '',
								'menu_class' => 'menu',
								'fallback_cb' => 'WP_Bootstrap_Navwalker::fallback',
								'walker' => new WP_Bootstrap_Navwalker()
								 ) 
							);
					?>   
				</div>
			</aside>	
		<?php endif; ?>
	<?php 
	} 
endif;	
add_action('webique_footer_group_second','webique_footer_group_second');


// Activate WordPress Maintenance Mode
$enable_comming_soon = get_theme_mod('enable_comming_soon');
  if($enable_comming_soon == '1') { 
	function wp_maintenance_mode() {
		if (!current_user_can('edit_themes') || !is_user_logged_in()) {
		   $file = get_template_directory() . '/inc/maintenance.php';
				include($file);
				exit();
		}
	}
	add_action('get_header', 'wp_maintenance_mode');
 }
 
 /*******************************************************************************
 *  Get Started Notice
 *******************************************************************************/

add_action( 'wp_ajax_webique_dismissed_notice_handler', 'webique_ajax_notice_handler' );

/**
 * AJAX handler to store the state of dismissible notices.
 */
function webique_ajax_notice_handler() {
    if ( isset( $_POST['type'] ) ) {
        // Pick up the notice "type" - passed via jQuery (the "data-notice" attribute on the notice)
        $type = sanitize_text_field( wp_unslash( $_POST['type'] ) );
        // Store it in the options table
        update_option( 'dismissed-' . $type, TRUE );
    }
}

function webique_deprecated_hook_admin_notice() {
        // Check if it's been dismissed...
        if ( ! get_option('dismissed-get_started', FALSE ) ) {
            // Added the class "notice-get-started-class" so jQuery pick it up and pass via AJAX,
            // and added "data-notice" attribute in order to track multiple / different notices
            // multiple dismissible notice states ?>
            <div class="updated notice notice-get-started-class is-dismissible" data-notice="get_started">
                <div class="webique-getting-started-notice clearfix">
                    <div class="webique-theme-screenshot">
                        <img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/screenshot.jpg" class="screenshot" alt="<?php esc_attr_e( 'Theme Screenshot', 'webique' ); ?>" />
                    </div><!-- /.webique-theme-screenshot -->
                    <div class="webique-theme-notice-content">
                        <h2 class="webique-notice-h2">
                            <?php
                        printf(
                        /* translators: 1: welcome page link starting html tag, 2: welcome page link ending html tag. */
                            esc_html__( 'Welcome! Thank you for choosing %1$s!', 'webique' ), '<strong>'. wp_get_theme()->get('Name'). '</strong>' );
                        ?>
                        </h2>

                        <p class="plugin-install-notice"><?php echo sprintf(__('Install and activate <strong>Clever Fox</strong> plugin for taking full advantage of all the features this theme has to offer.', 'webique')) ?></p>
						<?php $theme = wp_get_theme(); ?>
	
                        <a class="webique-btn-get-started button button-primary button-hero webique-button-padding" href="#" data-name="" data-slug=""><?php esc_html_e( /* translators: Theme Name*/  sprintf(__('Get started with %s','webique'),$theme->name));  ?></a><span class="webique-push-down">
                        <?php
                            /* translators: %1$s: Anchor link start %2$s: Anchor link end */
                            printf(
                                'or %1$sCustomize theme%2$s</a></span>',
                                '<a target="_blank" href="' . esc_url( admin_url( 'customize.php' ) ) . '">',
                                '</a>'
                            );
                        ?>
                    </div><!-- /.webique-theme-notice-content -->
                </div>
            </div>
        <?php }
}

add_action( 'admin_notices', 'webique_deprecated_hook_admin_notice' );

/*******************************************************************************
 *  Plugin Installer
 *******************************************************************************/

add_action( 'wp_ajax_install_act_plugin', 'webique_admin_install_plugin' );

function webique_admin_install_plugin() {
    /**
     * Install Plugin.
     */
    include_once ABSPATH . '/wp-admin/includes/file.php';
    include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

    if ( ! file_exists( WP_PLUGIN_DIR . '/clever-fox' ) ) {
        $api = plugins_api( 'plugin_information', array(
            'slug'   => sanitize_key( wp_unslash( 'clever-fox' ) ),
            'fields' => array(
                'sections' => false,
            ),
        ) );

        $skin     = new WP_Ajax_Upgrader_Skin();
        $upgrader = new Plugin_Upgrader( $skin );
        $result   = $upgrader->install( $api->download_link );
    }

    // Activate plugin.
    if ( current_user_can( 'activate_plugin' ) ) {
        $result = activate_plugin( 'clever-fox/clever-fox.php' );
    }
}

/*********************
	Default Functions 
**********************/
// Webique Marquee Default
function header_marquee_default() {
	return apply_filters(
		'header_marquee_default', json_encode(
			 array(
				array(
					'title'	  =>  esc_html__( 'Web Development', 'webique' ),
					'link'	  =>  esc_url( '#' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_header_marquee_001',
				),
				array(
					'title'	  =>  esc_html__( 'Graphic Design', 'webique' ),
					'link'	  =>  esc_url( '#' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_header_marquee_002',
				),
				array(
					'title'	  =>  esc_html__( 'App Development', 'webique' ),
					'link'	  =>  esc_url( '#' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_header_marquee_003',
				),
				array(
					'title'	  =>  esc_html__( 'Brand Identify', 'webique' ),
					'link'	  =>  esc_url( '#' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_header_marquee_004',
				),
				array(
					'title'	  =>  esc_html__( 'Branding Design', 'webique' ),
					'link'	  =>  esc_url( '#' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_header_marquee_005',
				),
				array(
					'title'	  =>  esc_html__( 'Marketing', 'webique' ),
					'link'	  =>  esc_url( '#' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_header_marquee_006',
				),
				array(
					'title'	  =>  esc_html__( 'Web Development', 'webique' ),
					'link'	  =>  esc_url( '#' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_header_marquee_007',
				),
			)
		)
	);
}

/*
 *
 * Slider Default
 */
 function webique_get_slider_default() {
	return apply_filters(
		'webique_get_slider_default', json_encode(
				 array(
				array(
					'image_url'       => get_template_directory_uri() . '/assets/images/slider/img01.jpg',
					'image_url2'       => get_template_directory_uri() . '/assets/images/slider/img04.jpg',
					'title'           => esc_html__( 'Web Development', 'webique' ),
					'subtitle'         => esc_html__( 'Innovative', 'webique' ),
					'subtitle2'         => esc_html__( 'Agency', 'webique' ),
					'description'       => esc_html__( 'We create and build flexible & creative design within your budget. Helping you increase sales', 'webique' ),
					'button'	  =>  esc_html__( 'Get Started', 'webique' ),
					'button_link'	  =>  esc_html__( '#', 'webique' ),
					'button2_link'	  =>  esc_html__( '#', 'webique' ),'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_slider_001',
					'customer_repeater' => json_encode(
						array(
							array(
								'id'   => 'customizer-repeater-customer-slide_001',
								'url' => get_template_directory_uri() . '/assets/images/avatar/img01.jpg',
							),
							array(
								'id'   => 'customizer-repeater-customer-slide_002',
								'url' => get_template_directory_uri() . '/assets/images/avatar/img02.jpg',
							),
							array(
								'id'   => 'customizer-repeater-customer-slide_003',
								'url' => get_template_directory_uri() . '/assets/images/avatar/img03.jpg',
							),
							array(
								'id'   => 'customizer-repeater-customer-slide_004',
								'url' => get_template_directory_uri() . '/assets/images/avatar/img04.jpg',
							)
						)
					),
					'text'	  =>  esc_html__( '10k+', 'webique' ),
					'text2'	  =>  esc_html__( 'Real Customers', 'webique' ),
					'text3'	  =>  esc_html__( 'Best', 'webique' ),
					'text4'	  =>  esc_html__( 'Agency Award', 'webique' ),
					'text5'	  =>  esc_html__( '4', 'webique' )
				),
				array(
					'image_url'       => get_template_directory_uri() . '/assets/images/slider/img01.jpg',
					'image_url2'       => get_template_directory_uri() . '/assets/images/slider/img04.jpg',
					'title'           => esc_html__( 'Web Development', 'webique' ),
					'subtitle'         => esc_html__( 'Innovative', 'webique' ),
					'subtitle2'         => esc_html__( 'Agency', 'webique' ),
					'description'       => esc_html__( 'We create and build flexible & creative design within your budget. Helping you increase sales', 'webique' ),
					'button'	  =>  esc_html__( 'Get Started', 'webique' ),
					'button_link'	  =>  esc_html__( '#', 'webique' ),
					'button2_link'	  =>  esc_html__( '#', 'webique' ),
					'id'              => 'customizer_repeater_slider_002','newtab'		=>	'',
					'nofollow'		=>	'',
					'customer_repeater' => json_encode(
						array(
							array(
								'id'   => 'customizer-repeater-customer-slide_021',
								'url' => get_template_directory_uri() . '/assets/images/avatar/img01.jpg',
							),
							array(
								'id'   => 'customizer-repeater-customer-slide_022',
								'url' => get_template_directory_uri() . '/assets/images/avatar/img02.jpg',
							),
							array(
								'id'   => 'customizer-repeater-customer-slide_023',
								'url' => get_template_directory_uri() . '/assets/images/avatar/img03.jpg',
							),
							array(
								'id'   => 'customizer-repeater-customer-slide_024',
								'url' => get_template_directory_uri() . '/assets/images/avatar/img04.jpg',
							)
						)
					),
					'text'	  =>  esc_html__( '10k+', 'webique' ),
					'text2'	  =>  esc_html__( 'Real Customers', 'webique' ),
					'text3'	  =>  esc_html__( 'Best', 'webique' ),
					'text4'	  =>  esc_html__( 'Agency Award', 'webique' ),
					'text5'	  =>  esc_html__( '4', 'webique' )
				),
				array(
					'image_url'       => get_template_directory_uri() . '/assets/images/slider/img01.jpg',
					'image_url2'       => get_template_directory_uri() . '/assets/images/slider/img04.jpg',
					'title'           => esc_html__( 'Web Development', 'webique' ),
					'subtitle'         => esc_html__( 'Innovative', 'webique' ),
					'subtitle2'         => esc_html__( 'Agency', 'webique' ),
					'description'       => esc_html__( 'We create and build flexible & creative design within your budget. Helping you increase sales', 'webique' ),
					'button'	  =>  esc_html__( 'Get Started', 'webique' ),
					'button_link'	  =>  esc_html__( '#', 'webique' ),
					'button2_link'	  =>  esc_html__( '#', 'webique' ),'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_slider_003',
					'customer_repeater' => json_encode(
						array(
							array(
								'id'   => 'customizer-repeater-customer-slide_031',
								'url' => get_template_directory_uri() . '/assets/images/avatar/img01.jpg',
							),
							array(
								'id'   => 'customizer-repeater-customer-slide_032',
								'url' => get_template_directory_uri() . '/assets/images/avatar/img02.jpg',
							),
							array(
								'id'   => 'customizer-repeater-customer-slide_033',
								'url' => get_template_directory_uri() . '/assets/images/avatar/img03.jpg',
							),
							array(
								'id'   => 'customizer-repeater-customer-slide_034',
								'url' => get_template_directory_uri() . '/assets/images/avatar/img04.jpg',
							)
						)
					),
					'text'	  =>  esc_html__( '10k+', 'webique' ),
					'text2'	  =>  esc_html__( 'Real Customers', 'webique' ),
					'text3'	  =>  esc_html__( 'Best', 'webique' ),
					'text4'	  =>  esc_html__( 'Agency Award', 'webique' ),
					'text5'	  =>  esc_html__( '4', 'webique' )
				),
				
			)
		)
	);
}

/*
 *
 * Service Default
 */
 function webique_get_service_default() {
	return apply_filters(
		'webique_get_service_default', json_encode(
				 array(
				array(
					'title'           => esc_html__( 'Secure Business', 'webique' ),
					'description'         => esc_html__( 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'webique' ),
					'button'	  =>  esc_html__( 'Read More', 'webique' ),
					'button_link'	  =>  esc_html__( '#', 'webique' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'image_url2'       => get_template_directory_uri() . '/assets/images/slider/img01.jpg',
					'icon_value' => 'fa-shield',
					'id'              => 'customizer_repeater_service_001',
				),
				array(
					'title'           => esc_html__( 'Facebook Ads', 'webique' ),
					'description'         => esc_html__( 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'webique' ),
					'button'	  =>  esc_html__( 'Read More', 'webique' ),
					'button_link'	  =>  esc_html__( '#', 'webique' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'image_url2'       => get_template_directory_uri() . '/assets/images/slider/img01.jpg',
					'icon_value' => 'fa-facebook',
					'id'              => 'customizer_repeater_service_002',
				),
				array(
					'title'           => esc_html__( 'Digital Marketing', 'webique' ),
					'description'         => esc_html__( 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'webique' ),
					'button'	  =>  esc_html__( 'Read More', 'webique' ),
					'button_link'	  =>  esc_html__( '#', 'webique' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'image_url2'       => get_template_directory_uri() . '/assets/images/slider/img01.jpg',
					'icon_value' => 'fa-pie-chart',
					'id'              => 'customizer_repeater_service_003',
				),
				array(
					'title'           => esc_html__( 'Data Security', 'webique' ),
					'description'         => esc_html__( 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'webique' ),
					'button'	  =>  esc_html__( 'Read More', 'webique' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'button_link'	  =>  esc_html__( '#', 'webique' ),
					'image_url2'       => get_template_directory_uri() . '/assets/images/slider/img01.jpg',
					'icon_value' => 'fa-database',
					'id'              => 'customizer_repeater_service_004',
				),
				array(
					'title'           => esc_html__( 'Email Marketing', 'webique' ),
					'description'         => esc_html__( 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'webique' ),
					'button'	  =>  esc_html__( 'Read More', 'webique' ),
					'button_link'	  =>  esc_html__( '#', 'webique' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'image_url2'       => get_template_directory_uri() . '/assets/images/slider/img01.jpg',
					'icon_value' => 'fa-envelope',
					'id'              => 'customizer_repeater_service_005',
				),
				array(
					'title'           => esc_html__( 'Seo & Optimization', 'webique' ),
					'description'         => esc_html__( 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'webique' ),
					'button'	  =>  esc_html__( 'Read More', 'webique' ),
					'button_link'	  =>  esc_html__( '#', 'webique' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'image_url2'       => get_template_directory_uri() . '/assets/images/slider/img01.jpg',
					'icon_value' => 'fa-area-chart',
					'id'              => 'customizer_repeater_service_006',
				),
								
			)
		)
	);
}

/*
 *
 * Pricing Default
 */
 function get_pricing_side_default() {
	return apply_filters(
		'get_pricing_side_default', json_encode(
				 array(
				array(
					'image_url'       => get_template_directory_uri() . '/assets/images/slider/img01.jpg',
					'icon_value' => 'fa-whatsapp',
					'title'           => esc_html__( 'WhatsApp', 'webique' ),
					'link'	  =>  esc_html__( '#', 'webique' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'choice'	=> 'customizer_repeater_image',
					'id'              => 'customizer_repeater_side_pricing_001',
				),
				array(
					'image_url'       => get_template_directory_uri() . '/assets/images/slider/img01.jpg',
					'icon_value' => 'fa-facebook-square',
					'title'           => esc_html__( 'Facebook', 'webique' ),
					'choice'	=> 'customizer_repeater_image',
					'link'	  =>  esc_html__( '#', 'webique' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_side_pricing_002',
				),
				array(
					'title'           => esc_html__( 'Instagram', 'webique' ),
					'choice'	=> 'customizer_repeater_image',
					'link'	  =>  esc_html__( '#', 'webique' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'image_url'       => get_template_directory_uri() . '/assets/images/slider/img01.jpg',
					'icon_value' => 'fa-instagram',
					'id'              => 'customizer_repeater_side_pricing_003',
				),
				array(
					'title'           => esc_html__( 'Team', 'webique' ),
					'choice'	=> 'customizer_repeater_image',
					'link'	  =>  esc_html__( '#', 'webique' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'image_url'       => get_template_directory_uri() . '/assets/images/slider/img01.jpg',
					'icon_value' => 'fa-teams',
					'id'              => 'customizer_repeater_side_pricing_004',
				)
								
			)
		)
	);
}

/*
 *
 * Features Default
 */
 function webique_get_features_default() {
	return apply_filters(
		'webique_get_features_default', json_encode(
				 array(
				array(					
					'icon_value' 		=> 'fa-clock-o',
					'title'           	=> esc_html__( 'Flexible Office Hours', 'webique' ),
					'subtitle'          => esc_html__( 'Next Edge Works', 'webique' ),
					'id'              	=> 'customizer_repeater_features_001',
				),
				array(
					'icon_value' 		=> 'fa-apple',
					'title'           	=> esc_html__( 'Macbook Pro', 'webique' ),
					'subtitle'          => esc_html__( 'Next Edge Works', 'webique' ),
					'id'             	=> 'customizer_repeater_features_002',
				),
				array(
					'icon_value' 		=> 'fa-cutlery',
					'title'           	=> esc_html__( 'Well Stocked Fridge', 'webique' ),
					'subtitle'          => esc_html__( 'Next Edge Works', 'webique' ),
					'id'              	=> 'customizer_repeater_features_003',
				),
				array(
					'icon_value' 		=> 'fa-umbrella',
					'title'           	=> esc_html__( 'Generous Holiday', 'webique' ),
					'subtitle'          => esc_html__( 'Next Edge Works', 'webique' ),
					'id'              	=> 'customizer_repeater_features_004',
				),
				array(					
					'icon_value' 		=> 'fa-bus',
					'title'           	=> esc_html__( 'Public Transport', 'webique' ),
					'subtitle'          => esc_html__( 'Next Edge Works', 'webique' ),					
					'id'              	=> 'customizer_repeater_features_005',
				),
				array(
					'icon_value' 		=> 'fa-coffee',
					'title'           	=> esc_html__( 'Friday Teatime Talks', 'webique' ),
					'subtitle'          => esc_html__( 'Next Edge Works', 'webique' ),
					'id'             	=> 'customizer_repeater_features_006',
				),
				array(
					'icon_value' 		=> 'fa-user',
					'title'           	=> esc_html__( 'Awesome Clients', 'webique' ),
					'subtitle'          => esc_html__( 'Next Edge Works', 'webique' ),
					'id'              	=> 'customizer_repeater_features_007',
				),
				array(
					'icon_value' 		=> 'fa-life-ring',
					'title'           	=> esc_html__( 'Training & Support', 'webique' ),
					'subtitle'          => esc_html__( 'Next Edge Works', 'webique' ),
					'id'              	=> 'customizer_repeater_features_008',
				)
								
			)
		)
	);
}

/*
 *
 * Funfact Default
 */
 function webique_get_funfact_default() {
	return apply_filters(
		'webique_get_funfact_default', json_encode(
				 array(
				array(					
					'title'           	=> esc_html__( 'Happy Customers', 'webique' ),
					'subtitle' 		=> '4100',
					'text' 			=> '+',
					'id'              	=> 'customizer_repeater_funfact_001',
				),
				array(
					'title'           	=> esc_html__( 'Completed Projects', 'webique' ),
					'subtitle' 		=> '8250',
					'text' 			=> '+',
					'id'             	=> 'customizer_repeater_funfact_002',
				),
				array(
					'title'           	=> esc_html__( 'Years Of Experience', 'webique' ),
					'subtitle' 		=> '25',
					'text' 			=> '+',
					'id'              	=> 'customizer_repeater_funfact_003',
				),
				array(
					'title'           	=> esc_html__( 'Awards', 'webique' ),
					'subtitle' 		=> '50',
					'text' 			=> '+',
					'id'              	=> 'customizer_repeater_funfact_004',
				),
				array(					
					'title'           	=> esc_html__( 'Service Rating', 'webique' ),
					'subtitle' 		=> '99',
					'text' 			=> '%',
					'id'              	=> 'customizer_repeater_funfact_005',
				),
				array(
					'title'           	=> esc_html__( 'Professional Team', 'webique' ),
					'subtitle' 		=> '100',
					'text' 			=> '+',
					'id'             	=> 'customizer_repeater_funfact_006',
				),
			)								
		)

	);
}

/*
 *
 * Testimonial Default
 */
 function webique_get_testimonial_default() {
	return apply_filters(
		'webique_get_testimonial_default', json_encode(
				 array(
				array(
					'title'				=> esc_html__( 'Best Design', 'webique' ),
					'subtitle'			=> esc_html__( ' Dabus nisl aliquet congue tellus nascetur lectus sagittis mattis arcu dictumst augue volutpat felis etiam suspendisse rhoncus mauris dignissim ante Sagpien.', 'webique' ),
					'image_url'			=> get_template_directory_uri() . '/assets/images/test_img.jpg',
					'text'				=> esc_html__( 'Context', 'webique' ),
					'text2'				=> esc_html__( '5', 'webique' ),
					'image_url2'		=> get_template_directory_uri() . '/assets/images/contex.jpg',
					'subtitle2'			=> esc_html__( 'Jasica Brown', 'webique' ),
					'text3'			=> esc_html__( 'Engineer', 'webique' ),	
					'id'              	=> 'customizer_repeater_testimonial_001',
				),
				array(
					'title'				=> esc_html__( 'Good Quality', 'webique' ),
					'subtitle'			=> esc_html__( ' Dabus nisl aliquet congue tellus nascetur lectus sagittis mattis arcu dictumst augue volutpat felis etiam suspendisse rhoncus mauris dignissim ante Sagpien.', 'webique' ),
					'image_url'			=> get_template_directory_uri() . '/assets/images/test_img.jpg',
					'text'				=> esc_html__( 'Context', 'webique' ),
					'text2'				=> esc_html__( '5', 'webique' ),
					'image_url2'		=> get_template_directory_uri() . '/assets/images/contex.jpg',
					'subtitle2'			=> esc_html__( 'Jasica Brown', 'webique' ),
					'text3'			=> esc_html__( 'Engineer', 'webique' ),	
					'id'             	=> 'customizer_repeater_testimonial_002',
				),
				array(
					'title'				=> esc_html__( 'Reliable Service', 'webique' ),
					'subtitle'				=> esc_html__( ' Dabus nisl aliquet congue tellus nascetur lectus sagittis mattis arcu dictumst augue volutpat felis etiam suspendisse rhoncus mauris dignissim ante Sagpien.', 'webique' ),
					'image_url'				=> get_template_directory_uri() . '/assets/images/test_img.jpg',
					'text'				=> esc_html__( 'Context', 'webique' ),
					'text2'				=> esc_html__( '5', 'webique' ),
					'image_url2'		=> get_template_directory_uri() . '/assets/images/contex.jpg',
					'subtitle2'				=> esc_html__( 'Jasica Brown', 'webique' ),
					'text3'				=> esc_html__( 'Engineer', 'webique' ),	
					'id'              	=> 'customizer_repeater_testimonial_003',
				),
				array(
					'title'				=> esc_html__( 'Recommended', 'webique' ),
					'subtitle'				=> esc_html__( ' Dabus nisl aliquet congue tellus nascetur lectus sagittis mattis arcu dictumst augue volutpat felis etiam suspendisse rhoncus mauris dignissim ante Sagpien.', 'webique' ),
					'image_url'				=> get_template_directory_uri() . '/assets/images/test_img.jpg',
					'text'				=> esc_html__( 'Context', 'webique' ),
					'text2'				=> esc_html__( '5', 'webique' ),
					'image_url2'		=> get_template_directory_uri() . '/assets/images/contex.jpg',
					'subtitle2'				=> esc_html__( 'Jasica Brown', 'webique' ),
					'text3'				=> esc_html__( 'Engineer', 'webique' ),	
					'id'              	=> 'customizer_repeater_testimonial_004',
				),
				array(					
					'title'				=> esc_html__( 'Best Interface', 'webique' ),
					'subtitle'				=> esc_html__( ' Dabus nisl aliquet congue tellus nascetur lectus sagittis mattis arcu dictumst augue volutpat felis etiam suspendisse rhoncus mauris dignissim ante Sagpien.', 'webique' ),
					'image_url'				=> get_template_directory_uri() . '/assets/images/test_img.jpg',
					'text'				=> esc_html__( 'Context', 'webique' ),
					'text2'				=> esc_html__( '5', 'webique' ),
					'image_url2'		=> get_template_directory_uri() . '/assets/images/contex.jpg',
					'subtitle2'				=> esc_html__( 'Jasica Brown', 'webique' ),
					'text3'				=> esc_html__( 'Engineer', 'webique' ),					
					'id'              	=> 'customizer_repeater_testimonial_005',
				),								
			)
		)
	);
}

/*
 *
 * Team Default
 */
 function webique_get_team_default() {
	return apply_filters(
		'webique_get_team_default', json_encode(
					  array(
				array(
					'image_url'       => get_template_directory_uri() . '/assets/images/teams/team1.jpg',
					'title'           => esc_html__( 'Steven Lucy', 'webique' ),
					'subtitle'        => esc_html__( 'Executive','webique' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_team_0001',
					'social_repeater' => json_encode(
						array(
							array(
								'id'   => 'customizer-repeater-social-repeater-team_001',
								'link' => 'facebook.com',
								'icon' => 'fa-facebook',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_002',
								'link' => 'googleplus.com',
								'icon' => 'fa-google-plus',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_003',
								'link' => 'twitter.com',
								'icon' => 'fa-twitter',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_004',
								'link' => 'instagram.com',
								'icon' => 'fa-instagram',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_005',
								'link' => 'linkedin.com',
								'icon' => 'fa-linkedin',
							)
						)
					),
				),
				array(
					'image_url'       => get_template_directory_uri() . '/assets/images/teams/team1.jpg',
					'title'           => esc_html__( 'Glenn Maxwell', 'webique' ),
					'subtitle'        => esc_html__( 'Project Manager', 'webique' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_team_0002',
					'social_repeater' => json_encode(
						array(
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0011',
								'link' => 'facebook.com',
								'icon' => 'fa-facebook',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0012',
								'link' => 'googleplus.com',
								'icon' => 'fa-google-plus',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0013',
								'link' => 'twitter.com',
								'icon' => 'fa-twitter',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0014',
								'link' => 'pinterest.com',
								'icon' => 'fa-instagram',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0015',
								'link' => 'linkedin.com',
								'icon' => 'fa-linkedin',
							),
						)
					),
				),
				array(
					'image_url'       => get_template_directory_uri() . '/assets/images/teams/team1.jpg',
					'title'           => esc_html__( 'Aoron Finch', 'webique' ),
					'subtitle'        => esc_html__( 'Manager and director', 'webique' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_team_0003',
					'social_repeater' => json_encode(
						array(
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0021',
								'link' => 'facebook.com',
								'icon' => 'fa-facebook',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0022',
								'link' => 'googleplus.com',
								'icon' => 'fa-google-plus',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0023',
								'link' => 'twitter.com',
								'icon' => 'fa-twitter',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0024',
								'link' => 'linkedin.com',
								'icon' => 'fa-instagram',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0025',
								'link' => 'linkedin.com',
								'icon' => 'fa-linkedin',
							),
						)
					),
				),
				array(
					'image_url'       => get_template_directory_uri() . '/assets/images/teams/team1.jpg',
					'title'           => esc_html__( 'Christiana Ena', 'webique' ),
					'subtitle'        => esc_html__( 'Executive Officer', 'webique' ),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_team_0004',
					'social_repeater' => json_encode(
						array(
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0031',
								'link' => 'facebook.com',
								'icon' => 'fa-facebook',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0032',
								'link' => 'googleplus.com',
								'icon' => 'fa-google-plus',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0033',
								'link' => 'twitter.com',
								'icon' => 'fa-twitter',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0034',
								'link' => 'linkedin.com',
								'icon' => 'fa-instagram',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0035',
								'link' => 'linkedin.com',
								'icon' => 'fa-linkedin',
							),
						)
					),
				)
			)
		)
	);
}


/*
 *
 * Business Default
 */
 function webique_get_business_default() {
	return apply_filters(
		'webique_get_business_default', json_encode(
				 array(
				array(	
					'image_url2'       	=> get_template_directory_uri() . '/assets/images/features/features.png',
					'title'           	=> esc_html__( 'Business 1', 'webique' ),
					'subtitle' 			=> 'Financial Control',
					'icon_value' 		=> 'fa-desktop',
					'id'              	=> 'customizer_repeater_business_001',
				),
				array(	
					'image_url2'       	=> get_template_directory_uri() . '/assets/images/features/features.png',
					'title'           	=> esc_html__( 'Business 2', 'webique' ),
					'subtitle' 			=> 'Financial Control',
					'icon_value' 		=> 'fa-desktop',
					'id'              	=> 'customizer_repeater_business_002',
				),
				array(	
					'image_url2'       	=> get_template_directory_uri() . '/assets/images/features/features.png',
					'title'           	=> esc_html__( 'Business 3', 'webique' ),
					'subtitle' 			=> 'Financial Control',
					'icon_value' 		=> 'fa-desktop',
					'id'              	=> 'customizer_repeater_business_003',
				),
				array(	
					'image_url2'       	=> get_template_directory_uri() . '/assets/images/features/features.png',
					'title'           	=> esc_html__( 'Business 4', 'webique' ),
					'subtitle' 			=> 'Financial Control',
					'icon_value' 		=> 'fa-desktop',
					'id'              	=> 'customizer_repeater_business_004',
				),
				array(	
					'image_url2'       	=> get_template_directory_uri() . '/assets/images/features/features.png',
					'title'           	=> esc_html__( 'Business 5', 'webique' ),
					'subtitle' 			=> 'Financial Control',
					'icon_value' 		=> 'fa-desktop',
					'id'              	=> 'customizer_repeater_business_005',
				),
			)								
		)

	);
}

/*
 *
 * Client Default
 */
function webique_get_client_default() {
	return apply_filters(
		'webique_get_client_default', json_encode(
				 array(
				array(
					'image_url'       => get_template_directory_uri() . '/assets/images/client/client1.jpg',
					'link'       => '#',
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_client_001',
				),
				array(
					'image_url'       => get_template_directory_uri() . '/assets/images/client/client1.jpg',
					'link'       => '#',
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_client_002',				
				),
				array(
					'image_url'       => get_template_directory_uri() . '/assets/images/client/client1.jpg',
					'link'       => '#',
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_client_003',
				),
				array(
					'image_url'       => get_template_directory_uri() . '/assets/images/client/client1.jpg',
					'link'       => '#',
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_client_004',
				),
				array(
					'image_url'       => get_template_directory_uri() . '/assets/images/client/client1.jpg',
					'link'       => '#',
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_client_005',
				),
			)
		)
	);
}

/*
 *
 * Cta Default
 */
function webique_get_cta_default() {
	return apply_filters(
		'webique_get_cta_default', json_encode(
				 array(
				array(
					'image_url'       => get_template_directory_uri() . '/assets/images/cta/img1.jpg',
					'id'              => 'customizer_repeater_cta_001',
				),
				array(
					'image_url'       => get_template_directory_uri() . '/assets/images/cta/img2.jpg',
					'id'              => 'customizer_repeater_cta_002',				
				),
				array(
					'image_url'       => get_template_directory_uri() . '/assets/images/cta/img3.jpg',
					'id'              => 'customizer_repeater_cta_003',
				),
				array(
					'image_url'       => get_template_directory_uri() . '/assets/images/cta/img4.jpg',
					'id'              => 'customizer_repeater_cta_004',
				)
			)
		)
	);
}

/*
 *
 * Application Platforms Default
 */
function webique_get_platform_default() {
	return apply_filters(
		'webique_get_platform_default', json_encode(
				 array(
				array(
					'title'			=> 'Application',
					'subtitle'		=> 'for Android',
					'icon_value'    => 'fa-android',
					'link'			=> '#',
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'            => 'customizer_repeater_app_001',
				),
				array(
					'title'			=> 'Application',
					'subtitle'		=> 'for iOS',
					'icon_value'    => 'fa-apple',
					'link'			=> '#',
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'            => 'customizer_repeater_app_002',
				),
				array(
					'title'			=> 'Application',
					'subtitle'		=> 'for Windows',
					'icon_value'    => 'fa-windows',
					'link'			=> '#',
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'            => 'customizer_repeater_app_003',
				),
				array(
					'title'			=> 'Application',
					'subtitle'		=> 'for Linux',
					'icon_value'    => 'fa-linux',
					'link'			=> '#',
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'            => 'customizer_repeater_app_004',
				),
			)
		)
	);
}

/*
 *
 * Companies Review Default
 */
function webique_get_comp_reviews_default() {
	return apply_filters(
		'webique_get_comp_reviews_default', json_encode(
				 array(
				array(
					'title'			=> 'Clutch',
					'subtitle'		=> '5.0',
					'image_url'    	=> get_template_directory_uri(). '/assets/images/footer/f-logo1.png',
					'link'			=> '#',
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'            => 'customizer_repeater_review_001',
				),
				array(
					'title'			=> 'Clutch',
					'subtitle'		=> '4.0',
					'image_url'    	=> get_template_directory_uri(). '/assets/images/footer/f-logo2.png',
					'link'			=> '#',
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'            => 'customizer_repeater_review_002',
				),
				array(
					'title'			=> 'Clutch',
					'subtitle'		=> '3.0',
					'image_url'    	=> get_template_directory_uri(). '/assets/images/footer/f-logo3.png',
					'link'			=> '#',
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'            => 'customizer_repeater_review_003',
				),
				array(
					'title'			=> 'Clutch',
					'subtitle'		=> '4.0',
					'image_url'    	=> get_template_directory_uri(). '/assets/images/footer/f-logo4.png',
					'link'			=> '#',
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'            => 'customizer_repeater_review_004',
				),
			)
		)
	);
}

/*
 *
 * Skill Default
 */
 function webique_get_skill_default() {
	return apply_filters(
		'webique_get_skill_default', json_encode(
				 array(
				array(					
					'title'           	=> esc_html__( 'Digital Strategy', 'webique' ),
					'text' 				=> esc_html__( '90', 'webique' ),
					'id'              	=> 'customizer_repeater_skill_001',
				),
				array(					
					'title'           	=> esc_html__( 'Finance Services', 'webique' ),
					'text' 				=> esc_html__( '80', 'webique' ),
					'id'              	=> 'customizer_repeater_skill_002',
				),
				array(					
					'title'           	=> esc_html__( 'Consulting', 'webique' ),
					'text' 				=> esc_html__( '70', 'webique' ),
					'id'              	=> 'customizer_repeater_skill_003',
				),
			)								
		)

	);
}

/*
 *
 * FAQ's Default
 */
 function webique_get_faq_default() {
	return apply_filters(
		'webique_get_faq_default', json_encode(
				 array(
				array(					
					'title'           	=> esc_html__( 'What types of services do you offer?', 'webique' ),					
					'text' 				=> esc_html__( 'It is a long established fact that a reader will be distracted by readable content when looking at its layout.', 'webique' ),
					'id'              	=> 'customizer_repeater_faq_001',
				),
				array(					
					'title'           	=> esc_html__( 'What types of services do you offer?', 'webique' ),					
					'text' 				=> esc_html__( 'It is a long established fact that a reader will be distracted by readable content when looking at its layout.', 'webique' ),
					'id'              	=> 'customizer_repeater_faq_002',
				),
				array(					
					'title'           	=> esc_html__( 'What types of services do you offer?', 'webique' ),					
					'text' 				=> esc_html__( 'It is a long established fact that a reader will be distracted by readable content when looking at its layout.', 'webique' ),
					'id'              	=> 'customizer_repeater_faq_003',
				),
				array(					
					'title'           	=> esc_html__( 'What types of services do you offer?', 'webique' ),					
					'text' 				=> esc_html__( 'It is a long established fact that a reader will be distracted by readable content when looking at its layout.', 'webique' ),
					'id'              	=> 'customizer_repeater_faq_004',
				),
				array(					
					'title'           	=> esc_html__( 'What types of services do you offer?', 'webique' ),					
					'text' 				=> esc_html__( 'It is a long established fact that a reader will be distracted by readable content when looking at its layout.', 'webique' ),
					'id'              	=> 'customizer_repeater_faq_005',
				), 
			)								
		)

	);
}

/*
 *
 * Work Process Default
 */
 function webique_get_work_default() {
	return apply_filters(
		'webique_get_work_default', json_encode(
				 array(
				array(					
					'title'           	=> esc_html__( 'WHAT WE OFFER', 'webique' ),
					'subtitle'          => esc_html__( 'Marketing & Advertising', 'webique' ),					
					'description' 		=> esc_html__( 'Consectetur adipiscing elit sed do eiusm onsectetur adipiscing elit.', 'webique' ),
					'id'              	=> 'customizer_repeater_work_001',
				),
				array(					
					'title'           	=> esc_html__( 'WE ARE A GOOD COMPANY', 'webique' ),
					'subtitle'          => esc_html__( 'Personal Development', 'webique' ),					
					'description' 		=> esc_html__( 'Consectetur adipiscing elit sed do eiusm onsectetur adipiscing elit.', 'webique' ),
					'id'              	=> 'customizer_repeater_work_002',
				),
				array(					
					'title'           	=> esc_html__( 'WHAT WE DO', 'webique' ),
					'subtitle'          => esc_html__( 'Research & Strategy', 'webique' ),					
					'description' 		=> esc_html__( 'Consectetur adipiscing elit sed do eiusm onsectetur adipiscing elit.', 'webique' ),
					'id'              	=> 'customizer_repeater_work_003',
				),
				array(					
					'title'           	=> esc_html__( 'WE OFFER FOR YOU', 'webique' ),
					'subtitle'          => esc_html__( 'Marketing Automation', 'webique' ),					
					'description' 		=> esc_html__( 'Consectetur adipiscing elit sed do eiusm onsectetur adipiscing elit.', 'webique' ),
					'id'              	=> 'customizer_repeater_work_004',
				),
			)								
		)

	);
}

/*
 *
 * Career Default
 */
 function webique_get_career_default() {
	return apply_filters(
		'webique_get_career_default', json_encode(
				 array(
				array(					
					'title'           	=> esc_html__( 'Senior Staff Engineer', 'webique' ),
					'description' 		=> esc_html__( 'Consectetur adipiscing elit sed do eiusm onsectetur adipiscing elit.', 'webique' ),
					'icon_value'          => esc_html__( 'fa-user', 'webique' ),
					'text'          => esc_html__( 'FULL TIME', 'webique' ),
					'button'          => esc_html__( 'Apply Now', 'webique' ),
					'button_link'          => esc_url('#'),
					'id'              	=> 'customizer_repeater_career_001',
				),
				array(					
					'title'           	=> esc_html__( 'Senior Staff Engineer', 'webique' ),
					'description' 		=> esc_html__( 'Consectetur adipiscing elit sed do eiusm onsectetur adipiscing elit.', 'webique' ),
					'icon_value'          => esc_html__( 'fa-paint-brush', 'webique' ),
					'text'          => esc_html__( 'FULL TIME', 'webique' ),
					'button'          => esc_html__( 'Apply Now', 'webique' ),
					'button_link'          => esc_url('#'),
					'id'              	=> 'customizer_repeater_career_002',
				),
				array(					
					'title'           	=> esc_html__( 'Senior Staff Engineer', 'webique' ),
					'description' 		=> esc_html__( 'Consectetur adipiscing elit sed do eiusm onsectetur adipiscing elit.', 'webique' ),
					'icon_value'          => esc_html__( 'fa-laptop', 'webique' ),
					'text'          => esc_html__( 'FULL TIME', 'webique' ),
					'button'          => esc_html__( 'Apply Now', 'webique' ),
					'button_link'          => esc_url('#'),
					'id'              	=> 'customizer_repeater_career_003',
				),
				array(					
					'title'           	=> esc_html__( 'Senior Staff Engineer', 'webique' ),
					'description' 		=> esc_html__( 'Consectetur adipiscing elit sed do eiusm onsectetur adipiscing elit.', 'webique' ),
					'icon_value'          => esc_html__( 'fa-globe', 'webique' ),
					'text'          => esc_html__( 'FULL TIME', 'webique' ),
					'button'          => esc_html__( 'Apply Now', 'webique' ),
					'button_link'          => esc_url('#'),
					'id'              	=> 'customizer_repeater_career_004',
				),
				array(					
					'title'           	=> esc_html__( 'Senior Staff Engineer', 'webique' ),
					'description' 		=> esc_html__( 'Consectetur adipiscing elit sed do eiusm onsectetur adipiscing elit.', 'webique' ),
					'icon_value'          => esc_html__( 'fa-codepen', 'webique' ),
					'text'          => esc_html__( 'FULL TIME', 'webique' ),
					'button'          => esc_html__( 'Apply Now', 'webique' ),
					'button_link'          => esc_url('#'),
					'id'              	=> 'customizer_repeater_career_005',
				),
				array(					
					'title'           	=> esc_html__( 'Senior Staff Engineer', 'webique' ),
					'description' 		=> esc_html__( 'Consectetur adipiscing elit sed do eiusm onsectetur adipiscing elit.', 'webique' ),
					'icon_value'          => esc_html__( 'fa-inbox', 'webique' ),
					'text'          => esc_html__( 'FULL TIME', 'webique' ),
					'button'          => esc_html__( 'Apply Now', 'webique' ),
					'button_link'          => esc_url('#'),
					'id'              	=> 'customizer_repeater_career_006',
				),
			)								
		)

	);
}

/*
 *
 * Benefit Default
 */
 function webique_get_benefit_default() {
	return apply_filters(
		'webique_get_benefit_default', json_encode(
				 array(
				array(					
					'title'           	=> esc_html__( 'Healthcare', 'webique' ),
					'description' 		=> esc_html__( 'Find a brief answer to your short question here', 'webique' ),
					'icon_value'          => esc_html__( 'fa-heart-o', 'webique' ),
					'id'              	=> 'customizer_repeater_benefit_001',
				),
				array(					
					'title'           	=> esc_html__( 'Career Growth', 'webique' ),
					'description' 		=> esc_html__( 'Find a brief answer to your short question here', 'webique' ),
					'icon_value'          => esc_html__( 'fa-line-chart', 'webique' ),
					'id'              	=> 'customizer_repeater_benefit_002',
				),
				array(					
					'title'           	=> esc_html__( 'Coffee & Snack', 'webique' ),
					'description' 		=> esc_html__( 'Find a brief answer to your short question here', 'webique' ),
					'icon_value'          => esc_html__( 'fa-coffee', 'webique' ),
					'id'              	=> 'customizer_repeater_benefit_003',
				),
				array(					
					'title'           	=> esc_html__( 'Paid Time Off', 'webique' ),
					'description' 		=> esc_html__( 'Find a brief answer to your short question here', 'webique' ),
					'icon_value'          => esc_html__( 'fa-money', 'webique' ),
					'id'              	=> 'customizer_repeater_benefit_004',
				),
			)								
		)

	);
}

/*
 *
 * Connect Info Default
 */
 function webique_get_connect_default() {
	return apply_filters(
		'webique_get_connect_default', json_encode(
				 array(
				array(					
					'title'           	=> esc_html__( 'Teams Call', 'webique' ),
					'description' 		=> esc_html__( 'Find a brief answer to your short question here.', 'webique' ),
					'icon_value'        => esc_html__( 'fa-teams', 'webique' ),
					'button'          	=> esc_html__( 'Connect Now', 'webique' ),
					'button_link'          	=> esc_url('#'),
					'id'              	=> 'customizer_repeater_connect_001',
				),
				array(					
					'title'           	=> esc_html__( 'WhatsApp', 'webique' ),
					'description' 		=> esc_html__( 'Find a brief answer to your short question here.', 'webique' ),
					'icon_value'        => esc_html__( 'fa-whatsapp', 'webique' ),
					'button'          	=> esc_html__( 'Connect Now', 'webique' ),
					'button_link'          	=> esc_url('#'),
					'id'              	=> 'customizer_repeater_connect_002',
				),
				array(					
					'title'           	=> esc_html__( 'Zoom Meeting', 'webique' ),
					'description' 		=> esc_html__( 'Find a brief answer to your short question here.', 'webique' ),
					'icon_value'        => esc_html__( 'fa-video-camera', 'webique' ),
					'button'          	=> esc_html__( 'Connect Now', 'webique' ),
					'button_link'          	=> esc_url('#'),
					'id'              	=> 'customizer_repeater_connect_003',
				),
				array(					
					'title'           	=> esc_html__( 'Live Chat', 'webique' ),
					'description' 		=> esc_html__( 'Find a brief answer to your short question here.', 'webique' ),
					'icon_value'        => esc_html__( 'fa-headphones', 'webique' ),
					'button'          	=> esc_html__( 'Connect Now', 'webique' ),
					'button_link'          	=> esc_url('#'),
					'id'              	=> 'customizer_repeater_connect_004',
				),
			)								
		)

	);
}

/*
 *
 * Connect Awards Default
 */
 function webique_get_award_default() {
	return apply_filters(
		'webique_get_award_default', json_encode(
				 array(
				array(					
					'title'           	=> esc_html__( 'Award 1', 'webique' ),
					'image_url' 		=> esc_url( get_template_directory_uri(). '/assets/images/award/1.png', 'webique' ),
					'link'          	=> esc_url('#'),
					'id'              	=> 'customizer_repeater_award_001',
				),
				array(					
					'title'           	=> esc_html__( 'Award 2', 'webique' ),
					'image_url' 		=> esc_url( get_template_directory_uri(). '/assets/images/award/1.png', 'webique' ),
					'link'          	=> esc_url('#'),
					'id'              	=> 'customizer_repeater_award_002',
				),
				array(					
					'title'           	=> esc_html__( 'Award 3', 'webique' ),
					'image_url' 		=> esc_url( get_template_directory_uri(). '/assets/images/award/1.png', 'webique' ),
					'link'          	=> esc_url('#'),
					'id'              	=> 'customizer_repeater_award_003',
				),
				array(					
					'title'           	=> esc_html__( 'Award 4', 'webique' ),
					'image_url' 		=> esc_url( get_template_directory_uri(). '/assets/images/award/1.png', 'webique' ),
					'link'          	=> esc_url('#'),
					'id'              	=> 'customizer_repeater_award_004',
				),
			)								
		)

	);
}

/*
 *
 * Contact Map Info Default
 */
function webique_get_pg_contact_map_info_default() {
	return apply_filters(
		'webique_get_pg_contact_map_info_default', json_encode(
				 array(
				array(
					'image_url'           => esc_url (get_template_directory_uri(). '/assets/images/contact/flag/unitedStates.png' ),	
					'title'           => esc_html__( 'United States', 'webique' ),
					'subtitle'           => esc_html__( 'Office', 'webique' ),
					'link'           => esc_url( 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d51856.75050320975!2d139.768121!3d35.675847!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x605d1b87f02e57e7%3A0x2e01618b22571b89!2zVMO0a3nDtCwgTmjhuq10IELhuqNu!5e0!3m2!1svi!2sus!4v1719308962873!5m2!1svi!2sus', 'webique' ),
					'id'              => 'customizer_repeater_pg_contact_map_info_001',
				),
				array(
					'image_url'           => esc_url (get_template_directory_uri(). '/assets/images/contact/flag/germany.png' ),	
					'title'           => esc_html__( 'Germany', 'webique' ),
					'subtitle'           => esc_html__( 'Office', 'webique' ),
					'link'           => esc_url( 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d1438544.2714565487!2d10.197676761709376!3d51.14314480954461!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1489634550185', 'webique' ),
					'id'              => 'customizer_repeater_pg_contact_map_info_001',
				),
				array(
					'image_url'           => esc_url (get_template_directory_uri(). '/assets/images/contact/flag/spain.png' ),	
					'title'           => esc_html__( 'Spain', 'webique' ),
					'subtitle'           => esc_html__( 'Office', 'webique' ),
					'link'           => esc_url( 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d158969.55657176184!2d-0.176367!3d51.496715!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48760542e6182f3f%3A0x7bb7e385c39764c4!2zQuG6o28gdMOgbmcgTOG7i2NoIHPhu60gVOG7sSBuaGnDqm4gTHXDom4gxJDDtG4!5e0!3m2!1svi!2sus!4v1719308620173!5m2!1svi!2sus', 'webique' ),
					'id'              => 'customizer_repeater_pg_contact_map_info_001',
				),
				array(
					'image_url'           => esc_url (get_template_directory_uri(). '/assets/images/contact/flag/russia.png' ),	
					'title'           => esc_html__( 'Russia', 'webique' ),
					'subtitle'           => esc_html__( 'Office', 'webique' ),
					'link'           => esc_url( 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d158969.55657176184!2d-0.176367!3d51.496715!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48760542e6182f3f%3A0x7bb7e385c39764c4!2zQuG6o28gdMOgbmcgTOG7i2NoIHPhu60gVOG7sSBuaGnDqm4gTHXDom4gxJDDtG4!5e0!3m2!1svi!2sus!4v1719308885091!5m2!1svi!2sus', 'webique' ),
					'id'              => 'customizer_repeater_pg_contact_map_info_001',
				),
				array(
					'image_url'           => esc_url (get_template_directory_uri(). '/assets/images/contact/flag/france.png' ),	
					'title'           => esc_html__( 'France', 'webique' ),
					'subtitle'           => esc_html__( 'Office', 'webique' ),
					'link'           => esc_url( 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d194388.1638384037!2d-3.717769!3d40.424022!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd42286e8bea1259%3A0x1d6ad9c64a7706ab!2sTemple%20of%20Debod!5e0!3m2!1svi!2sus!4v1719308938883!5m2!1svi!2sus', 'webique' ),
					'id'              => 'customizer_repeater_pg_contact_map_info_001',
				),
			)
		)
	);
}

// Webique Contact Marquee Default
function contact_marquee_default() {
	return apply_filters(
		'contact_marquee_default', json_encode(
			 array(
				array(
					'title'	  =>  esc_html__( 'Monday 11:00 AM - 06:00 PM', 'webique' ),
					'link'		=> esc_url('#'),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_contact_marquee_001',
				),
				array(
					'title'	  =>  esc_html__( 'Tuesday 11:00 AM - 06:00 PM', 'webique' ),
					'link'		=> esc_url('#'),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_contact_marquee_002',
				),
				array(
					'title'	  =>  esc_html__( 'Wednesday 11:00 AM - 06:00 PM', 'webique' ),
					'link'		=> esc_url('#'),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_contact_marquee_003',
				),
				array(
					'title'	  =>  esc_html__( 'Thursday 11:00 AM - 06:00 PM', 'webique' ),
					'link'		=> esc_url('#'),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_contact_marquee_004',
				),
				array(
					'title'	  =>  esc_html__( 'Friday 11:00 AM - 06:00 PM', 'webique' ),
					'link'		=> esc_url('#'),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_contact_marquee_005',
				),
				array(
					'title'	  =>  esc_html__( 'Saturday 11:00 AM - 06:00 PM', 'webique' ),
					'link'		=> esc_url('#'),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_contact_marquee_006',
				),
				array(
					'title'	  =>  esc_html__( 'Sunday Closed', 'webique' ),
					'link'		=> esc_url('#'),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_contact_marquee_007',
				)
			)
		)
	);
}

// Webique Contact Marquee Default
function payments_card_default() {
	return apply_filters(
		'payments_card_default', json_encode(
			 array(
				array(
					'icon_value'	  =>  'fa-cc-visa',
					'link'			  => esc_url('#'),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_payment_card_001',
				),
				array(
					'icon_value'	  =>  'fa-cc-mastercard',
					'link'			  => esc_url('#'),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_payment_card_002',
				),
				array(
					'icon_value'	  =>  'fa-cc-paypal',
					'link'			  => esc_url('#'),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_payment_card_003',
				),
				array(
					'icon_value'	  =>  'fa-cc-stripe',
					'link'			  => esc_url('#'),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_payment_card_004',
				),
				array(
					'icon_value'	  =>  'fa-cc-discover',
					'link'			  => esc_url('#'),
					'newtab'		=>	'',
					'nofollow'		=>	'',
					'id'              => 'customizer_repeater_payment_card_005',
				),
			)
		)
	);
}

