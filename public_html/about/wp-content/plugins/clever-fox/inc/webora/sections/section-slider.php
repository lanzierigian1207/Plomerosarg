<!--===// Start: Slider Section
            =================================--> 
<?php
	$slider_hs 						= get_theme_mod('slider_hs','1');
	$slider 						= get_theme_mod('slider',webique_get_slider_default());
	$slider_autoplay				= get_theme_mod('slider_autoplay','true');
	if($slider_hs=='1'){
?>	
<section id="slider-section" class="slider-wrapper">
	<div class="main-slider owl-carousel owl-theme style-3">
		<?php
			if ( ! empty( $slider ) ) {
			$slider = json_decode( $slider );
			foreach ( $slider as $slide_item ) {
				$title = ! empty( $slide_item->title ) ? apply_filters( 'webique_translate_single_string', $slide_item->title, 'slider section' ) : '';
				$subtitle = ! empty( $slide_item->subtitle ) ? apply_filters( 'webique_translate_single_string', $slide_item->subtitle, 'slider section' ) : '';
				$subtitle2 = ! empty( $slide_item->subtitle2 ) ? apply_filters( 'webique_translate_single_string', $slide_item->subtitle2, 'slider section' ) : '';
				$description = ! empty( $slide_item->description ) ? apply_filters( 'webique_translate_single_string', $slide_item->description, 'slider section' ) : '';
				$button = ! empty( $slide_item->button) ? apply_filters( 'webique_translate_single_string', $slide_item->button,'slider section' ) : '';
				$button_link = ! empty( $slide_item->button_link ) ? apply_filters( 'webique_translate_single_string', $slide_item->button_link, 'slider section' ) : '';				
				$image = ! empty( $slide_item->image_url ) ? apply_filters( 'webique_translate_single_string', $slide_item->image_url, 'slider section' ) : '';
				$newtab = ! empty( $slide_item->newtab ) ? apply_filters( 'webique_translate_single_string', $slide_item->newtab, 'slider section' ) : '';
				$nofollow = ! empty( $slide_item->nofollow ) ? apply_filters( 'webique_translate_single_string', $slide_item->nofollow, 'slider section' ) : '';
		?>
		<div class="item">
			<img src="<?php echo esc_url($image); ?>" data-img-url="<?php echo esc_url($image); ?>" alt="<?php esc_attr( printf(/*translators: Data Image Title */__('%s','webique-pro'),$title) ); ?>">
			<div class="theme-slider">
				<div class="theme-table">
					<div class="theme-table-cell">
						<div class="av-container">                                
							<div class="theme-content text-left">
							<?php if(!empty($title)){ ?>
								<div class="sub-title-box tilt" data-animation="fadeInUp" data-delay="150ms">		
									<div><h3><?php echo esc_html( sprintf(/*translators: Slide Title */__('%s','webique-pro'),$title) ); ?></h3></div>									
								</div>
							<?php } ?>
							<?php if(!empty($subtitle)|| !empty($subtitle2)){ ?>
								<h1 data-animation="fadeInUp" data-delay="200ms"><?php echo esc_html( sprintf(/*translators: Slide Subtitle */__('%s','webique-pro'),$subtitle) ); ?> <span><?php echo esc_html( sprintf(/*translators: Slide Title 2 */__('%s','webique-pro'),$subtitle2) ); ?></span></h1>
							<?php } ?>
							<?php if(!empty($description)){ ?>                                    
								<p data-animation="fadeInUp" data-delay="500ms"><?php echo esc_html( sprintf(/*translators: Description */__('%s','webique-pro'),$description) ); ?></p>
							<?php } ?>
								<div class="avatar-get-started">
								<?php if(!empty($button)) { ?>
									<a data-animation="fadeInUp" data-delay="800ms" href="<?php echo esc_url($button_link); ?>" <?php if($newtab =='yes') {echo 'target="_blank"'; } ?> rel="<?php if($newtab =='yes') {echo 'noreferrer noopener';} ?> <?php if($nofollow =='yes') {echo 'nofollow';} ?>" class="av-btn av-btn-primary av-btn-bubble"><?php echo esc_html( sprintf(/*translators: Button Label */__('%s','webique-pro'),$button) ); ?></a>
								<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } } ?>
	</div>
</section>
<?php } ?>