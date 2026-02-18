<?php
$activate = array(
        'gradiant-sidebar-primary' => array(
            'search-1',
            'recent-posts-1',
            'archives-1',
        ),
		'gradiant-footer-1' => array(
			 'text-1',
        ),
		'gradiant-footer-2' => array(
			 'categories-1',
        ),
		'gradiant-footer-3' => array(
			 'search-1',
        )
    );
	$theme = wp_get_theme();
    /* the default titles will appear */
   update_option('widget_text', array(  
		1 => array('title' => 'About '. $theme->name,
        'text'=>'<div class="textwidget">
				<p>'.sprintf(/* translators: %s: Description */esc_html__( '%s.', 'clever-fox' ),CLEVERFOX_FOOTER_ABOUT).'</p>
				<div class="footer-badge">
					<img src="'.CLEVERFOX_PLUGIN_URL.'inc/gradiant/images/footer/about-01.png" alt="">
					<img src="'.CLEVERFOX_PLUGIN_URL.'inc/gradiant/images/footer/about-02.png" alt="">
					<img src="'.CLEVERFOX_PLUGIN_URL.'inc/gradiant/images/footer/about-03.png" alt="">
				</div>
			</div>'),		
		2 => array('title' => 'Recent Posts'),
		3 => array('title' => 'Categories'), 
        ));
		 update_option('widget_categories', array(
			1 => array('title' => 'Categories'), 
			2 => array('title' => 'Categories')));

		update_option('widget_archives', array(
			1 => array('title' => 'Archives'), 
			2 => array('title' => 'Archives')));
			
		update_option('widget_search', array(
			1 => array('title' => 'Search'), 
			2 => array('title' => 'Search')));	
		
    update_option('sidebars_widgets',  $activate);
	$MediaId = get_option('gradiant_media_id');
	set_theme_mod( 'custom_logo', $MediaId[0] );
	set_theme_mod( 'footer_first_img', CLEVERFOX_PLUGIN_URL .'inc/'.strtolower($theme->name).'/images/logo.png' );
	set_theme_mod('nav_btn_lbl','Book Now');
?>