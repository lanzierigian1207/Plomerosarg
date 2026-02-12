<!--===// Start: Slider Section
            =================================--> 
<?php  
	$slider_hs 						= get_theme_mod('slider_hs','1');
	$slider 						= get_theme_mod('slider',webique_get_slider_default());
	$slider_autoplay				= get_theme_mod('slider_autoplay','false');
	if($slider_hs=='1'){
	if ( ! empty( $slider ) ) {
	$slider = json_decode( $slider );
?>	
<section id="slider-section" class="slider-wrapper style2">
	<div class="main-slider2 owl-carousel">
	<?php
			foreach ( $slider as $slide_item ) {
				$title = ! empty( $slide_item->title ) ? apply_filters( 'webique_translate_single_string', $slide_item->title, 'slider section' ) : '';
				$subtitle = ! empty( $slide_item->subtitle ) ? apply_filters( 'webique_translate_single_string', $slide_item->subtitle, 'slider section' ) : '';
				$subtitle2 = ! empty( $slide_item->subtitle2 ) ? apply_filters( 'webique_translate_single_string', $slide_item->subtitle2, 'slider section' ) : '';
				$description = ! empty( $slide_item->description ) ? apply_filters( 'webique_translate_single_string', $slide_item->description, 'slider section' ) : '';
				$button = ! empty( $slide_item->button) ? apply_filters( 'webique_translate_single_string', $slide_item->button,'slider section' ) : '';
				$button_link = ! empty( $slide_item->button_link ) ? apply_filters( 'webique_translate_single_string', $slide_item->button_link, 'slider section' ) : '';				
				$image2 = ! empty( $slide_item->image_url2 ) ? apply_filters( 'webique_translate_single_string', $slide_item->image_url2, 'slider section' ) : '';
				$newtab = ! empty( $slide_item->newtab ) ? apply_filters( 'webique_translate_single_string', $slide_item->newtab, 'slider section' ) : '';
				$nofollow = ! empty( $slide_item->nofollow ) ? apply_filters( 'webique_translate_single_string', $slide_item->nofollow, 'slider section' ) : '';				
		?>
		<div class="item">			
			<div class="theme-slider">
				<div class="theme-table">
					<div class="theme-table-cell">
						<div class="av-container">                                
							<div class="theme-content text-left">
							<?php if(!empty($title)){ ?>
								<div class="sub-title-box tilt" data-animation="fadeInUp" data-delay="150ms">		
									<div><h3><?php echo esc_html( sprintf(/*translators: Slide Title */__('%s','clever-fox'),$title) ); ?></h3></div>									
								</div>
							<?php } ?>
							<?php if(!empty($subtitle)|| !empty($subtitle2)){ ?>
								<h1 data-animation="fadeInUp" data-delay="200ms"><?php echo esc_html( sprintf(/*translators: Slide Subtitle */__('%s','clever-fox'),$subtitle) ); ?> <span><?php echo esc_html( sprintf(/*translators: Slide Title 2 */__('%s','clever-fox'),$subtitle2) ); ?></span></h1>
							<?php } ?>
							<?php if(!empty($description)){ ?>                                    
								<p data-animation="fadeInUp" data-delay="500ms"><?php echo esc_html( sprintf(/*translators: Description */__('%s','clever-fox'),$description) ); ?></p>
							<?php } ?>
								<div class="avatar-get-started">
								<?php if(!empty($button)) { ?>
									<a data-animation="fadeInUp" data-delay="800ms" href="<?php echo esc_url($button_link); ?>" <?php if($newtab =='yes') {echo 'target="_blank"'; } ?> rel="<?php if($newtab =='yes') {echo 'noreferrer noopener';} ?> <?php if($nofollow =='yes') {echo 'nofollow';} ?>" class="av-btn av-btn-primary av-btn-bubble"><?php echo esc_html( sprintf(/*translators: Button Label */__('%s','clever-fox'),$button) ); ?></a>
								<?php } ?>
					
								</div>
							</div>
							<div class="theme-content-offer aline-right" data-animation="fadeInUp" data-delay="200ms">
								<div class="award-badge side-img zig-zag2">
								<?php if(!empty($image2)): ?>
									<img src="<?php echo esc_url($image2); ?>" data-img-url="<?php echo esc_url($image2); ?>" alt="<?php echo esc_attr( sprintf(/*translators: Data Image Title */__('%s','clever-fox'),$title) ); ?>">
								<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php }  ?>	
	</div>
	<div class="thumb-box">
		<div class="av-container p-0">         
			<div class="owl-carousel owl-thumbs-main">
			<?php 
			foreach ( $slider as $index => $slide_item ) {
			$image2 = ! empty( $slide_item->image_url2 ) ? apply_filters( 'webique_translate_single_string', $slide_item->image_url2, 'slider section' ) : '';
		?><?php if(!empty($image2)): ?>
				<div class="item"><img src="<?php echo esc_url($image2); ?>" alt="<?php esc_attr_e('Thumb '. $index ,'clever-fox'); ?>"></div>
			<?php endif; } ?>				
			</div>
		</div>
	</div>
</section>
	<?php } } ?>