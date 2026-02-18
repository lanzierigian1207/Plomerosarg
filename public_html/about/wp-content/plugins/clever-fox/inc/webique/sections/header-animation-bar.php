<?php
	if(! function_exists('header_animation_bar')):
	function header_animation_bar() {
	$header_marquee_titles 		= get_theme_mod('header_marquee_titles',header_marquee_default());	
	$theme						= wp_get_theme();
	$webique_theme				= $theme->get('Name');
	$hide_show_hdr_anim_bar 	= get_theme_mod('hide_show_hdr_anim_bar','1');
	if( $hide_show_hdr_anim_bar == '1' ) {
	?>
	 <div class="<?php if($webique_theme === 'Webora') echo esc_attr('header-marquee'); ?> marquee-header marquee-section style1 bg-gradient2 mrq-loop" direction="right" scrollamount="30">
            <ul>
			<?php
				$header_marquee_titles = json_decode($header_marquee_titles);
				if( $header_marquee_titles!='' )
				{
				foreach($header_marquee_titles as $index => $marquee_item){	
				$marquee_title = ! empty( $marquee_item->title ) ? apply_filters( 'webique_translate_single_string', $marquee_item->title, 'Header Section' ) : '';	
				$marquee_link = ! empty( $marquee_item->link ) ? apply_filters( 'webique_translate_single_string', $marquee_item->link, 'Header Section' ) : '';
				$newtab = ! empty( $marquee_item->newtab ) ? apply_filters( 'webique_translate_single_string', $marquee_item->newtab, 'Header section' ) : '';
				$nofollow = ! empty( $marquee_item->nofollow ) ? apply_filters( 'webique_translate_single_string', $marquee_item->nofollow, 'Header section' ) : '';
			?>
                <li class="item wow <?php if($index%2 == '0') { echo 'slideInRight active'; } else { echo 'slideInLeft'; }  ?>"><a href="<?php echo esc_url($marquee_link); ?>" <?php if($newtab =='yes') {echo 'target="_blank"'; } ?> rel="<?php if($newtab =='yes') {echo 'noreferrer noopener'; } ?> <?php if($nofollow =='yes') {echo 'nofollow'; } ?>"><?php echo esc_html(sprintf(/* translators: %s: Marquee Text */ __( '%s','clever-fox' ),$marquee_title)); ?></a></li>
                <?php }} ?>
            </ul>
        </div>
	<?php
}}
endif;
add_action('header_animation_bar','header_animation_bar');