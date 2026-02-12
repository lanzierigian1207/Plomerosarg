<?php
	if ( ! function_exists( 'webora_above_header' ) ) :
	function webora_above_header(){
		$hide_show_email_details 		= get_theme_mod('hide_show_email_details', '1');
		$hide_show_mbl_details 		= get_theme_mod('hide_show_mbl_details', '1');
		
	?>

<!--===// Start: Header Above
	=================================-->
	<div id="above-header" class="header-above-info">
		<div class="d-flex align-items-center">
		   <!--===// Start: Marquee
					=================================-->
			<?php do_action('header_animation_bar'); ?>
			 <!-- End: Marquee
			=================================-->
			<?php 
			if( ( $hide_show_email_details == '1' ) || ( $hide_show_mbl_details == '1' ) ){
			$hide_show_email_details 	= get_theme_mod('hide_show_email_details', '1');
			$tlh_email_title 			= get_theme_mod('tlh_email_title', __('Email Us','webique-pro'));
			$tlh_email_icon 			= get_theme_mod('tlh_email_icon', 'fa-envelope-o');
			$tlh_email_link 			= get_theme_mod('tlh_email_link', 'email@company.com');									
		?>
			<div class="header-above-top">
			<?php if($hide_show_email_details == '1' ): ?>
				<aside class="widget widget-contact d-inline-block me-4">
					<div class="contact-area">
						<div class="contact-icon bg-white icon-bounce">
						<i class="fa <?php echo  esc_attr($tlh_email_icon); ?>"></i>
					</div>
					<div class="icon-content">
					<?php if(!empty($tlh_email_title)){ ?>
						<h4><?php echo esc_html( sprintf(/*Translators: Email Title */ __('%s','webique-pro'),$tlh_email_title)); ?></h4>
					<?php } ?>
					<?php if(!empty($tlh_email_link)){ ?>
						<a href="mailto:<?php echo esc_attr($tlh_email_link); ?>" class="contact-info">
							<span class="title"><?php echo esc_html( sprintf(/*Translators: Email Link */ __('%s','webique-pro'),$tlh_email_link)); ?></span>
						</a>
					<?php } ?>
					</div>
					</div>
				</aside>
				<?php endif; ?>
				<?php 
					$hide_show_mbl_details 		= get_theme_mod('hide_show_mbl_details', '1');
					$tlh_mobile_title 			= get_theme_mod('tlh_mobile_title', __('Call Us','webique-pro'));
					$tlh_mobile_icon 			= get_theme_mod('tlh_mobile_icon', 'fa-whatsapp');
					$tlh_mobile_link 			= get_theme_mod('tlh_mobile_link', '70 975 975 70');
				?>
				<?php if($hide_show_mbl_details == '1' ): ?>
				<aside class="widget widget-contact d-inline-block ms-4">
					<div class="contact-area justify-content-sm-end justify-content-start mb-0">
						<div class="contact-icon bg-white icon-bounce">
							<i class="fa <?php echo  esc_attr($tlh_mobile_icon); ?>"></i>
						</div>
						<div class="icon-content">
						<?php if(!empty($tlh_mobile_title)){ ?>
							<h4><?php echo esc_html( sprintf(/*Translators: Mobile Title */ __('%s','webique-pro'),$tlh_mobile_title)); ?></h4>
						<?php } ?>
						<?php if(!empty($tlh_mobile_link)){ ?>
							<a href="tel:<?php echo esc_attr(str_replace(' ', '', $tlh_mobile_link)); ?>" class="contact-info">
								<span class="title"><?php echo esc_html( sprintf(/*Translators: Mobile Link */ __('%s','webique-pro'),$tlh_mobile_link)); ?></span>
							</a>
						<?php } ?>
						</div>
					</div>
				</aside>
				<?php endif; ?>
			</div>
	<?php } ?>
			
		</div>
	</div>
<?php } endif;

add_action('webora_above_header', 'webora_above_header');
?>