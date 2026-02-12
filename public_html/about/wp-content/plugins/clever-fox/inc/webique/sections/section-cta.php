<?php
	$cta_hs 			= get_theme_mod('cta_hs','1');
	$cta_call_icon 		= get_theme_mod('cta_call_icon','fa-phone');
	$cta_call_title		= get_theme_mod('cta_call_title','Available 24/7'); 
	$cta_call_text		= get_theme_mod('cta_call_text','+91 246 2365'); 
	$cta_email_icon 	= get_theme_mod('cta_email_icon','fa-envelope');
	$cta_email_title	= get_theme_mod('cta_email_title','Email Us:'); 
	$cta_email_text		= get_theme_mod('cta_email_text','info@company.com');
	$cta_title			= get_theme_mod('cta_title','Get A Free Consultation');
	$cta_subtitle		= get_theme_mod('cta_subtitle','99% Satisfy Clients');
	$cta_btn_lbl		= get_theme_mod('cta_btn_lbl','Contact Us'); 	
	$cta_btn_link		= get_theme_mod('cta_btn_link');
	$cta_btn_newtab		= get_theme_mod('cta_btn_newtab','1');
	$cta_btn_nofollow	= get_theme_mod('cta_btn_nofollow','1');
	$cta_contents		= get_theme_mod('cta_contents',webique_get_cta_default());
	$cta_rating			= get_theme_mod('cta_rating','5');
	$cta_bg_setting		= get_theme_mod('cta_bg_setting',CLEVERFOX_PLUGIN_URL.'inc/webique/images/cta_bg.jpg'); 
	if($cta_hs=='1'){
?>
<section id="cta-section" class="cta-section av-py-default ripple-area" style="background: url( <?php echo esc_url($cta_bg_setting); ?>) center center no-repeat; background-size: cover; background-attachment: fixed">
	<div class="av-container">
		<div class="row align-items-center justify-content-center">
			<div class="col-lg-12">
				<div class="cta-content text-center my-4">
					<div class="row align-items-center justify-content-center wow fadeInUp" data-wow-duration="1500ms">
						<div class="col-md-4 p-0">
							<div class="cta-text top">
								<span class="cta_tittle"><?php echo wp_kses_post($cta_title); ?></span>
							</div>
						</div>
					</div>
					<div class="row align-items-center cta-box">
						<div class="col-md-4 text-md-left text-center wow zoomIn" data-wow-duration="1500ms">
							<div class="info-box d-flex align-items-center justify-content-md-start justify-content-center ps-lg-3 ps-xl-4">
								<div class="icon-circle">
									<i class="fa <?php echo esc_attr($cta_call_icon); ?> primary-color"></i>
								</div>
								<div class="text-left ms-2">
								<?php if(!empty($cta_call_title)){ ?>
									<h5 class="call-title"><?php echo wp_kses_post($cta_call_title); ?></h5>
								<?php } ?>
								<?php if(!empty($cta_call_text)){ ?>
									<a href="tel:<?php echo esc_attr(str_replace(' ','',$cta_call_text)); ?>" class="contact-info">
										<span class="title call-text"><?php echo wp_kses_post($cta_call_text); ?></span>
									</a>
								<?php } ?>
								</div>
							</div>
						</div>
						<div class="col-md-4 text-center wow zoomIn" data-wow-duration="1500ms">
							<div class="info-box">
								<div class="rating">
								<?php for ( $rating = 1; $rating <= $cta_rating; $rating++ ) { ?>
									<span class="fa fa-star"></span>
								<?php } ?>
								</div>
								<h4><?php echo wp_kses_post($cta_subtitle); ?></h4>
								<div class="client-images">
								<?php 
									if ( ! empty( $cta_contents ) ) {
									$cta_contents = json_decode( $cta_contents );	
									foreach ( $cta_contents as $index => $cta_item ) {	
									$image = ! empty( $cta_item->image_url ) ? apply_filters( 'webique_translate_single_string', $cta_item->image_url, 'cta section' ) : '';
								?>
									<img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr__('Client '. $index , 'clever-fox'); ?>">
									<?php } } ?>									
								</div>
							</div>
						</div>
						<div class="col-md-4 text-md-end text-center wow zoomIn" data-wow-duration="1500ms"> 
							<div class="info-box d-flex align-items-center justify-content-md-end justify-content-center pe-lg-3 pe-xl-4">
								<div class="text-end me-2">
									<?php if(!empty($cta_email_title)){ ?>
										<h5 class="email-title"><?php echo wp_kses_post($cta_email_title); ?></h5>
									<?php } ?>
									<?php if(!empty($cta_email_text)){ ?>
										<a href="mailto:<?php echo esc_attr(str_replace(' ','',$cta_email_text)); ?>" class="contact-info">
											<span class="title email-text"><?php echo wp_kses_post($cta_email_text); ?></span>
										</a>
									<?php } ?>
								</div>
								<div class="icon-circle">
									<i class="fa <?php echo esc_attr($cta_email_icon); ?> primary-color"></i>
								</div>
							</div>
						</div>
					</div>
					<?php if(!empty($cta_btn_lbl)){ ?>
					<div class="row align-items-center justify-content-center wow fadeInDown" data-wow-duration="1500ms">
						<div class="col-md-4 p-0">
							<div class="cta-text bottom">
								<a href="<?php echo esc_url($cta_btn_link); ?>"  target="<?php if($cta_btn_newtab == '1' ) { echo '_blank'; } ?>" rel="<?php if($cta_btn_newtab == '1' ) { echo 'noreferrer noopener';} ?> <?php if($cta_btn_nofollow == '1' ) { echo 'nofollow';} ?>" class="fw-bold">
									<span class="cta_btn_lbl"><?php echo wp_kses_post($cta_btn_lbl); ?></span>
								</a>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php } ?>