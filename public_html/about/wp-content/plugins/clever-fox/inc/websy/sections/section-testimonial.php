<?php 
	$testimonial_hs 				= get_theme_mod('testimonial_hs','1');
	$testimonial_ttl 				= get_theme_mod('testimonial_ttl','Our <span class="primary-color">Testimonial</span>');
	$testimonial_desc				= get_theme_mod('testimonial_desc','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); 
	$testimonial_contents			= get_theme_mod('testimonial_contents',websy_get_testimonial_default());
	if($testimonial_hs=='1'){	
?>	
<section id="testimonial-section" class="testimonial-section av-py-default testimonial-home">
	<div class="av-container">
		<?php if(!empty($testimonial_ttl)  || !empty($testimonial_desc)): ?>
			<div class="av-columns-area">
				<div class="av-column-12">
					<div class="heading-default text-center">
						<div class="title-container animation-style2">
							<div class="arrow-left"></div>
								<?php if(!empty($testimonial_ttl)): ?>
									<h1 class="title"><?php echo wp_kses_post($testimonial_ttl); ?></h1>				
								<?php endif; ?>
							<div class="arrow-right"></div>
						</div>
						<?php if(!empty($testimonial_desc)): ?>
							<p><?php echo wp_kses_post($testimonial_desc); ?></p>
						<?php endif; ?>	
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="av-columns-area">
			<div class="av-column-8">
				<div class="testimonial-slider owl-carousel owl-theme testimonial-carousel">
				<?php
					if ( ! empty( $testimonial_contents ) ) {
					$testimonial_contents = json_decode( $testimonial_contents );
					foreach ( $testimonial_contents as $testimonial_item ) {
						$title = ! empty( $testimonial_item->title ) ? apply_filters( 'webique_translate_single_string', $testimonial_item->title, 'Testimonial section' ) : '';
						$subtitle = ! empty( $testimonial_item->subtitle ) ? apply_filters( 'webique_translate_single_string', $testimonial_item->subtitle, 'Testimonial section' ) : '';
						$subtitle2 = ! empty( $testimonial_item->subtitle2 ) ? apply_filters( 'webique_translate_single_string', $testimonial_item->subtitle2, 'Testimonial section' ) : '';
						$subtitle3 = ! empty( $testimonial_item->text3 ) ? apply_filters( 'webique_translate_single_string', $testimonial_item->text3, 'Testimonial section' ) : '';
						$image = ! empty( $testimonial_item->image_url ) ? apply_filters( 'webique_translate_single_string', $testimonial_item->image_url, 'Testimonial section' ) : '';
						$text2 = ! empty( $testimonial_item->text2 ) ? apply_filters( 'webique_translate_single_string', $testimonial_item->text2, 'Testimonial section' ) : '';
					
				?>
				 <div class="testimonial-item wow fadeInUp" data-cursor-type="text" data-wow-delay="0ms" data-wow-duration="1500ms">
					<div class="testimonial-content">
					
					<div class="testimonial-text p-2 pt-4">
						<div class="quote-right-box">
							<span>
								<i class="fa fa-quote-right"></i>
							</span>
						</div>
						<?php if(!empty($title)){ ?><h3>“ <?php echo esc_html( sprintf(/*translators: Title */__('%s','clever-fox'),$title) ); ?> ”</h3><?php } ?>
						<?php if(!empty($subtitle)){ ?><p class="ellipsis"><?php esc_html( printf(/*translators: Subtitle */__('%s','clever-fox'),$subtitle) ); ?></p><?php } ?>
					</div>
					<div class="testimonial-footer">
						<div class="d-flex">
							 <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/testimonial/contex.jpg' ); ?>" alt="Context img">
							<span class="brand"><?php echo esc_html__('Context','clever-fox'); ?></span>
						</div>
						<?php if(!empty($text2)){ ?>
						<div class="rating">
							<?php for($i=1;$i<=$text2;$i++){ ?>
								<i class="fa fa-star"></i>
							<?php } ?>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="testimonial-author">
					<div class="me-2">
						<img src="<?php echo esc_url($image); ?>" alt="Jessica Brown">
					</div>
					<div class="ms-1 mt-1">
						<?php if(!empty($subtitle2)){ ?><h4 class="primary-color"><?php echo esc_html( sprintf(/*translators: Subtitle2 */__('%s','clever-fox'),$subtitle2) ); ?></h4><?php } ?>
						<?php if(!empty($subtitle3)){ ?><p><?php echo esc_html( sprintf(/*translators: Subtitle3 */__('%s','clever-fox'),$subtitle3) ); ?></p><?php } ?>
					</div>
				</div>						
					</div>
			<?php } } ?>	
				</div>
			</div>
			<div class="av-column-4 ps-lg-1">
				<div class="testimonial-box">
				<?php
					$funfacts_contents  = get_theme_mod('funfacts_contents', webique_get_funfact_default());
					if ( ! empty( $funfacts_contents ) ) {
					$funfacts_contents = json_decode( $funfacts_contents );
					foreach ( $funfacts_contents as $index => $funfacts_content ) {
						$count = ! empty( $funfacts_content->subtitle ) ? apply_filters( 'webique_translate_single_string', $funfacts_content->subtitle, 'Testimonial section' ) : '';
						$text = ! empty( $funfacts_content->text ) ? apply_filters( 'webique_translate_single_string', $funfacts_content->text, 'Testimonial section' ) : '';
						$title = ! empty( $funfacts_content->title ) ? apply_filters( 'webique_translate_single_string', $funfacts_content->title, 'Testimonial section' ) : '';
						$background = ($index%3 == '0' ) ? 'bg-gradient1' : (( $index%3 == '1') ? 'bg-gradient2' : 'bg-primary-light');
				?>
					<div class="testimonial-inner <?php echo esc_attr($background); ?> wow fadeInUp" data-wow-delay="0ms" data-wow-duration="1500ms">
						<?php if(!empty($count)){ ?><h1 class="<?php echo ($index%3 == '2') ? 'primary-color' : ''; ?>"><span class="counter"><?php echo esc_html($count); ?></span>+</h1><?php } ?>
						<?php if(!empty($title)){ ?><span><?php echo esc_html( sprintf(/*translators: title */__('%s','clever-fox'),$title) ); ?></span><?php } ?>
					</div>					
					<?php }} ?>					
				</div>
			</div>
		</div>
	</div>	
</section>
<?php } ?>