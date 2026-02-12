<?php  
	$service_hs 			= get_theme_mod('service_hs','1');
	$service_title 			= get_theme_mod('service_title',__('Our <span class="primary-color">Expertise</span>','clever-fox'));
	$service_description	= get_theme_mod('service_description',__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','clever-fox')); 
	$service_contents		= get_theme_mod('service_contents',webique_get_service_default());
	if($service_hs=='1'){
?>	
<section id="service-section" class="service-section av-py-default service-home">
	<div class="av-container">
		<?php if(!empty($service_title)  || !empty($service_description)): ?>
			<div class="av-columns-area">
				<div class="av-column-12">
					<div class="heading-default text-center">
						<div class="title-container animation-style2">
							<div class="arrow-left"></div>
								<?php if(!empty($service_title)): ?>
									<h1 class="title"><?php echo wp_kses_post($service_title); ?></h1>				
								<?php endif; ?>
							<div class="arrow-right"></div>
						</div>
						<?php if(!empty($service_description)): ?>
							<p><?php echo wp_kses_post($service_description); ?></p>
						<?php endif; ?>	
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="av-columns-area service-contents">
			<?php
				if ( ! empty( $service_contents ) ) {
				$service_contents = json_decode( $service_contents );
				foreach ( $service_contents as $service_item ) {
					$title = ! empty( $service_item->title ) ? apply_filters( 'webique_translate_single_string', $service_item->title, 'Service section' ) : '';
					$description = ! empty( $service_item->description ) ? apply_filters( 'webique_translate_single_string', $service_item->description, 'Service section' ) : '';
					$button = ! empty( $service_item->button ) ? apply_filters( 'webique_translate_single_string', $service_item->button, 'Service section' ) : '';
					$link = ! empty( $service_item->button_link ) ? apply_filters( 'webique_translate_single_string', $service_item->button_link, 'Service section' ) : '';
					$newtab = ! empty( $service_item->newtab ) ? apply_filters( 'webique_translate_single_string', $service_item->newtab, 'Service section' ) : '';
					$nofollow = ! empty( $service_item->nofollow ) ? apply_filters( 'webique_translate_single_string', $service_item->nofollow, 'Service section' ) : '';
					$image = ! empty( $service_item->image_url2 ) ? apply_filters( 'webique_translate_single_string', $service_item->image_url2, 'Service section' ) : '';
					$icon = ! empty( $service_item->icon_value ) ? apply_filters( 'webique_translate_single_string', $service_item->icon_value, 'Service section' ) : '';
			?>
				<div class="av-column-4 av-sm-column-6 wow fadeInUp" data-wow-delay="0ms" data-wow-duration="1500ms">
					<div class="service-item tilt">
						
							<?php if(!empty($image)): ?>
								<div class="service-overlay">
									<img src="<?php echo esc_url($image); ?>">
								</div>
							<?php endif; ?>

							<div class="service-content">
								<?php if(!empty($title)): ?>
									<h3><?php esc_html( printf(/*translators: Title */__('%s','clever-fox'),$title) ); ?></h3>
								<?php endif; ?>
								
								<?php if(!empty($description)): ?>
									<p><?php esc_html( printf(/*translators: Description */__('%s','clever-fox'),$description) ); ?></p>
								<?php endif; ?>						

								<?php if(!empty($button)): ?>								
									<a href="<?php echo esc_url($link); ?>" class="av-btn av-btn-bubble" <?php if($newtab =='yes') {echo 'target="_blank"'; } ?> rel="<?php if($newtab =='yes') {echo 'noreferrer noopener'; } ?> <?php if($nofollow =='yes') {echo 'nofollow'; } ?>" ><?php esc_html( printf(/*translators: Button Label */__('%s','clever-fox'),$button) ); ?> <i class="fa fa-chevron-right"></i> </a>
								<?php endif; ?>
							<?php if(!empty($icon)): ?>
								<div class="icon zig-zag"><i class="fa <?php echo esc_attr($icon); ?>"></i></div>
							<?php endif; ?>
						</div>						
					</div>
				</div>
			<?php } } ?>	
		</div>
	</div>	
</section>
<?php } ?>