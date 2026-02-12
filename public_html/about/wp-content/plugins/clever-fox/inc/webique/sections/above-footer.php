<?php
	if ( ! function_exists( 'webique_above_footer' ) ) :
	function webique_above_footer() {
		$hs_above_footer		= get_theme_mod('hs_above_footer','1'); 
			// $footer_above_content	= get_theme_mod('footer_above_content',webique_get_footer_above_default());
			if ($hs_above_footer == '1') {
		?>
			<div class="footer-above py-3 py-xl-4">
	<div class="av-container">
		<div class="row align-items-baseline">
			<div class="col-12 col-sm-6 col-lg-4 order-2 order-lg-1 wow fadeInUp footer_info_mail" data-wow-delay="0ms" data-wow-duration="1500ms">
				<aside class="widget widget-contact">
					<div class="contact-area">
						<?php 
						$hide_show_fci_email_details = get_theme_mod('hide_show_fci_email_details', '1');
						$fci_email_title = get_theme_mod('fci_email_title', __('Email Us','clever-fox'));
						$fci_email_icon = get_theme_mod('fci_email_icon', 'fa-envelope-o');
						$fci_email_link = get_theme_mod('fci_email_link', 'info@example.com');
						if($hide_show_fci_email_details) {
					?>
						<div class="contact-icon zig-zag">
							<i class="fa <?php echo  esc_attr($fci_email_icon); ?>"></i>
						</div>
						<div class="icon-content">
							<h4 class="primary-color"><?php esc_html(printf(/*Translators: Email Title*/ __('%s','clever-fox'),$fci_email_title)); ?></h4>
							<a href="mailto:<?php echo esc_attr($fci_email_link); ?>" class="contact-info">
								<span class="title"><?php esc_html(printf(/*Translators: Email Link*/ __('%s','clever-fox'),$fci_email_link)); ?></span>
							</a>
						</div>
						<?php } ?>
					</div>
				</aside>
			</div>
			<div class="col-12 col-lg-4 order-1 order-lg-2 wow fadeInUp mb-lg-0 mb-2" data-wow-delay="20ms" data-wow-duration="1500ms">
				<aside class="widget widget-contact d-fciex justify-content-sm-center mb-2 mb-sm-0">
					<div class="footer-logo text-sm-center">
					   <?php 
						   $footer_logo = get_theme_mod('footer_logo', get_template_directory_uri(). '/assets/images/footer/footer_logo.png'); 
						   $footer_logo_link	= get_theme_mod('logo_link', esc_url( home_url( '/' )));
						   $footer_logo_newtab		= get_theme_mod('footer_logo_newtab','1');
						   $footer_logo_nofollow	= get_theme_mod('footer_logo_nofollow','1');
						?>
						<a href="<?php echo esc_url($footer_logo_link); ?>" target="<?php if($footer_logo_newtab == '1' ) { echo '_blank'; } ?>" rel="<?php if($footer_logo_newtab == '1' ) { echo 'noreferrer noopener';} ?> <?php if($footer_logo_nofollow == '1' ) { echo 'nofollow';} ?>" class="footer-site-title d-block"><img src="<?php echo esc_url($footer_logo); ?>" alt="<?php echo esc_html(get_bloginfo('name')); ?>"></a>
					</div>
				</aside>
			</div>
			<div class="col-12 col-sm-6 col-lg-4 order-3 order-lg-3 wow fadeInUp footer_info_call" data-wow-delay="400ms" data-wow-duration="1500ms">
				<aside class="widget widget-contact">
					<div class="contact-area justify-content-sm-end justify-content-start mb-0">
					<?php								
						$hide_show_fci_mobile_details = get_theme_mod('hide_show_fci_mobile_details', '1');
						$fci_mobile_title = get_theme_mod('fci_mobile_title', __('Call Us','clever-fox'));
						$fci_mobile_icon = get_theme_mod('fci_mobile_icon', 'fa-whatsapp');
						$fci_mobile_link = get_theme_mod('fci_mobile_link', '987654321');	
						if($hide_show_fci_mobile_details) {
					?>
						<div class="contact-icon zig-zag">
							<i class="fa <?php echo esc_attr($fci_mobile_icon); ?>"></i>
						</div>
						<div class="icon-content">
							<h4 class="primary-color"><?php esc_html(printf(/*Translators: Phone Title*/ __('%s','clever-fox'),$fci_mobile_title)); ?></h4>
							<a href="tel:<?php echo esc_attr(str_replace(' ', '', $fci_mobile_link)); ?>" class="contact-info">
								<span class="title"><?php esc_html(printf(/*Translators: Phone Link*/ __('%s','clever-fox'),$fci_mobile_link)); ?></span>
							</a>
						</div>
						<?php } ?>
					</div>
				</aside>
			</div>
		</div>
	</div>
</div>
<?php $footer_bar_contents		= get_theme_mod('footer_bar_contents',header_marquee_default());
	$hide_show_ftr_anim_bar		= get_theme_mod('hide_show_ftr_anim_bar','1');
	if($hide_show_ftr_anim_bar) { ?>
	<div class="marquee-section style1 bg-blur mrq-loop footer_marquee" direction="right" scrollamount="30">
		<ul>
			<?php 
				if ( ! empty( $footer_bar_contents ) ) {
				$footer_bar_contents = json_decode( $footer_bar_contents );
				foreach ( $footer_bar_contents as $index => $footer_bar_content ) {
					$title = ! empty( $footer_bar_content->title ) ? apply_filters( 'webique_translate_single_string', $footer_bar_content->title, 'animation bars section' ) : '';
					$link = ! empty( $footer_bar_content->link ) ? apply_filters( 'webique_translate_single_string', $footer_bar_content->link, 'animation bars section' ) : '';
					$newtab = ! empty( $footer_bar_content->newtab ) ? apply_filters( 'webique_translate_single_string', $footer_bar_content->newtab, 'animation bars section' ) : '';
					$nofollow = ! empty( $footer_bar_content->nofollow ) ? apply_filters( 'webique_translate_single_string', $footer_bar_content->nofollow, 'animation bars section' ) : '';					
			?>
				<li class="item <?php echo (($index%2 == 0) ? 'active' : ''); ?>"><a href="<?php echo esc_url($link); ?>" <?php if($newtab =='yes') {echo 'target="_blank"'; } ?> rel="<?php if($newtab =='yes') {echo 'noreferrer noopener'; } ?> <?php if($nofollow =='yes') {echo 'nofollow'; } ?>"><?php esc_html(printf(/*Translators: Title */__('%s','clever-fox'),$title)); ?></a></li>
			<?php } } ?>
		</ul>
	</div>
<?php }  } 
} endif;
add_action('webique_above_footer', 'webique_above_footer');




if ( ! function_exists( 'webique_above_copy_footer' ) ) :
	function webique_above_copy_footer() { ?>
	<div class="footer-live-chat">
	<div class="av-container">
		<div class="row align-items-center">
			<div class="col-12 col-sm-6 col-lg-3 order-1 order-lg-1 wow fadeInUp footer_sale_chat" data-wow-delay="0ms" data-wow-duration="1500ms">
			<?php								
				$hide_show_fchat1_details 	= get_theme_mod('hide_show_fchat1_details', '1');
				$fchat1_title 				= get_theme_mod('fchat1_title', __('Live Chat','clever-fox'));
				$fchat1_icon 				= get_theme_mod('fchat1_icon', 'fa-headphones');
				$fchat1_subtitle 			= get_theme_mod('fchat1_subtitle', 'Sales Team');
				$fchat1_box_trigger 		= get_theme_mod('fchat1_box_trigger', '');
				if($hide_show_fchat1_details) {
			?>
				<aside class="widget widget-contact">
					<div class="contact-area d-fciex align-items-center">
						<a href="" class="contact-icon zig-zag-bg icon-bounce">
							<i class="fa <?php echo esc_attr($fchat1_icon); ?>" aria-hidden="true"></i>
						</a>
						<div class="icon-content">
							<h4 class="primary-color"><?php esc_html(printf(/*Translators: Chat Title*/ __('%s','clever-fox'),$fchat1_title)); ?></h4>
							<p><?php esc_html(printf(/*Translators: Chat Subtitle*/ __('%s','clever-fox'),$fchat1_subtitle)); ?></p>
						</div>
					</div>
				</aside>
				<?php } ?>
			</div>
			<div class="col-12 col-lg-6 order-3 order-lg-2 mt-sm-2 mt-lg-0 wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms">
				<aside class="widget widget-contact d-fciex justify-content-center">
					<div class="footer-download-slider owl-carousel owl-theme" data-cursor-type="text">
					<?php 
					 $application_platforms = get_theme_mod('application_platforms',webique_get_platform_default());
					 
					 if ( ! empty( $application_platforms ) ) {
					$application_platforms = json_decode( $application_platforms );
					foreach ( $application_platforms as $app_item ) {
						$title = ! empty( $app_item->title ) ? apply_filters( 'webique_translate_single_string', $app_item->title, 'footer section' ) : '';
						$subtitle = ! empty( $app_item->subtitle ) ? apply_filters( 'webique_translate_single_string', $app_item->subtitle, 'footer section' ) : '';
						$icon = ! empty( $app_item->icon_value ) ? apply_filters( 'webique_translate_single_string', $app_item->icon_value, 'footer section' ) : '';
						$link = ! empty( $app_item->link ) ? apply_filters( 'webique_translate_single_string', $app_item->link, 'footer section' ) : '';
						$newtab = ! empty( $app_item->newtab ) ? apply_filters( 'webique_translate_single_string', $app_item->newtab, 'footer section' ) : '';
						$nofollow = ! empty( $app_item->nofollow ) ? apply_filters( 'webique_translate_single_string', $app_item->nofollow, 'footer section' ) : '';
					?>
						<div class="d-fciex justify-content-center">
							<button type="button" class="download-box" <?php if($newtab =='yes') {echo 'target="_blank"'; } ?> rel="<?php if($newtab =='yes') {echo 'noreferrer noopener'; } ?> <?php if($nofollow =='yes') {echo 'nofollow'; } ?>">
								<i class="fa <?php echo esc_attr($icon); ?>"></i>
								<div>
									<small><?php esc_html(printf(/*Translators: Title*/ __('%s','clever-fox'),$title)); ?></small>
									<h6><?php esc_html(printf(/*Translators: Subtitle*/ __('%s','clever-fox'),$subtitle)); ?></h6>
								</div>
							</button>
						</div>
					 <?php }} ?>                                    
					</div>
				</aside>
			</div>
		   <div class="col-12 col-sm-6 col-lg-3 order-2 order-lg-3 wow fadeInUp footer_support_chat" data-wow-delay="400ms" data-wow-duration="1500ms">
		   <?php								
				$hide_show_fchat2_details 	= get_theme_mod('hide_show_fchat2_details', '1');
				$fchat2_title 				= get_theme_mod('fchat2_title', __('Live Chat','clever-fox'));
				$fchat2_icon 				= get_theme_mod('fchat2_icon', 'fa-headphones');
				$fchat2_subtitle 			= get_theme_mod('fchat2_subtitle', 'Support Team');
				$fchat2_box_trigger 		= get_theme_mod('fchat2_box_trigger', '');
				if($hide_show_fchat2_details) {
			?>
				<aside class="widget widget-contact">
					<div class="contact-area d-fciex align-items-center justify-content-sm-end justify-content-start">
						<div class="icon-content text-end">
							<h4 class="primary-color"><?php esc_html(printf(/*Translators: Chat Title*/ __('%s','clever-fox'),$fchat2_title)); ?></h4>
							<p><?php esc_html(printf(/*Translators: Chat Subtitle*/ __('%s','clever-fox'),$fchat2_subtitle)); ?></p>
						</div>
						<a href="" class="contact-icon zig-zag-bg icon-bounce">
							<i class="fa <?php echo esc_attr($fchat2_icon); ?>" aria-hidden="true"></i>
						</a>
					</div>
				</aside>
				<?php } ?>
			</div>
		</div>                    
	</div>
</div>

	<?php	} endif; ?>
<?php
add_action('webique_above_copy_footer', 'webique_above_copy_footer');
?>