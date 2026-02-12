<?php  
	$testimonial_ttl		= get_theme_mod('testimonial_ttl','Testimonial');
	$testimonial_description= get_theme_mod('testimonial_description','Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur quisquam saepe eveniet, cumque tempore veritatis!');
	$testimonial_bg_image			= get_theme_mod('testimonial_bg_image',esc_url(CLEVERFOX_PLUGIN_URL .'inc/nexcraft/images/slider/slide-img2.jpg'));
	$testimonial_bg_position	= get_theme_mod('testimonial_bg_position','fixed');
	$testimonial_contents	= get_theme_mod('testimonial_contents',nexcraft_get_testimonial_default()); 						
?>	
<!-- testimonial -->
    <section class="testimonial-section" <?php if(!empty($testimonial_bg_image)){ ?> style="background:url('<?php echo esc_url($testimonial_bg_image); ?>'); background-repeat:no-repeat;background-size:cover;background-attachment:<?php echo esc_attr($testimonial_bg_position); ?>" <?php } ?>>
        <div class="container">
            <?php if(!empty($testimonial_ttl) || !empty($testimonial_description)): ?>
				<div class="section-title">
					<?php if(!empty($testimonial_ttl)): ?>
						<h2 class="maintitle">
							<svg xmlns="http://www.w3.org/2000/svg" width="54" height="27" viewBox="0 0 54 27" style="fill: var(--primary-color);" class="desg1"><path id="Rectangle_2_copy_3" data-name="Rectangle 2 copy 3" class="cls-1" d="M1156 147h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-1A2 2 0 0 1 1156 147Zm7 0h5a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-5a2 2 0 0 1-2-2v-1A2 2 0 0 1 1163 147Zm3 13h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-1A2 2 0 0 1 1166 160Zm7 0h8a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-8a2 2 0 0 1-2-2v-1A2 2 0 0 1 1173 160Zm-11.5 11a1.5 1.5 0 1 1-1.5 1.5A1.5 1.5 0 0 1 1161.5 171Zm4 0h3a1.5 1.5 0 0 1 0 3h-3A1.5 1.5 0 0 1 1165.5 171Zm7 0h7a1.5 1.5 0 0 1 0 3h-7A1.5 1.5 0 0 1 1172.5 171Zm16.5-11h17a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-17a2 2 0 0 1-2-2v-1A2 2 0 0 1 1189 160Z" transform="translate(-1154 -147)"/></svg>
							
								<span><?php echo wp_kses_post($testimonial_ttl); ?></span>
							
							<svg xmlns="http://www.w3.org/2000/svg" width="54" height="27" viewBox="0 0 54 27" style="fill: var(--primary-color);"><path id="Rectangle_2_copy_3" data-name="Rectangle 2 copy 3" class="cls-1" d="M1156 147h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-1A2 2 0 0 1 1156 147Zm7 0h5a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-5a2 2 0 0 1-2-2v-1A2 2 0 0 1 1163 147Zm3 13h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-1A2 2 0 0 1 1166 160Zm7 0h8a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-8a2 2 0 0 1-2-2v-1A2 2 0 0 1 1173 160Zm-11.5 11a1.5 1.5 0 1 1-1.5 1.5A1.5 1.5 0 0 1 1161.5 171Zm4 0h3a1.5 1.5 0 0 1 0 3h-3A1.5 1.5 0 0 1 1165.5 171Zm7 0h7a1.5 1.5 0 0 1 0 3h-7A1.5 1.5 0 0 1 1172.5 171Zm16.5-11h17a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-17a2 2 0 0 1-2-2v-1A2 2 0 0 1 1189 160Z" transform="translate(-1154 -147)"/></svg>
						</h2>
					<?php endif; ?>
					
					<?php if(!empty($testimonial_description)): ?>
						<p><?php echo wp_kses_post($testimonial_description); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<?php 
				if ( ! empty( $testimonial_contents ) ) { 
				$testimonial_contents = json_decode( $testimonial_contents );
			?>
				<div  id="testimonialIndicators" class="carousel slide">
					<div class="carousel-indicators col-lg-6">
						<?php $count=0;								
							foreach ( $testimonial_contents as $testimonial_item ) {
								
								$image = ! empty( $testimonial_item->image_url ) ? apply_filters( 'nexcraft_pro_translate_single_string', $testimonial_item->image_url, 'Testimonial section' ) : '';
								$active = ($count == 0)?'active':'';
								if(!empty($image)):
						?>
							<button type="button" data-bs-target="#testimonialIndicators" data-bs-slide-to="<?php echo esc_attr($count); ?>" class="<?php echo esc_attr($active); ?>"
							aria-current="true">
								<img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr__('Testimonial Image','nexcraft-pro'); ?>"><span class="testimonial-effect-1"></span>
							</button>						
						<?php ++$count; endif; } ?>
					</div>
					<div class="carousel-inner col-lg-6 offset-lg-6">
						<?php $count=0;							
							foreach ( $testimonial_contents as $testimonial_item ) {
								
								$image = ! empty( $testimonial_item->image_url ) ? apply_filters( 'nexcraft_translate_single_string', $testimonial_item->image_url, 'Testimonial section' ) : '';
								$title = ! empty( $testimonial_item->title ) ? apply_filters( 'nexcraft_translate_single_string', $testimonial_item->title, 'Testimonial section' ) : '';
								$subtitle = ! empty( $testimonial_item->subtitle ) ? apply_filters( 'nexcraft_translate_single_string', $testimonial_item->subtitle, 'Testimonial section' ) : '';
								$text = ! empty( $testimonial_item->text ) ? apply_filters( 'nexcraft_translate_single_string', $testimonial_item->text, 'Testimonial section' ) : '';
								$active = ($count == 0)?'active':'';
						?>
							<div class="carousel-item <?php echo esc_attr($active); ?>" data-bs-interval="2000">
								<div class="row">
									<div class="col-lg-12">
										<div class="testimonial-content">
											<?php if(!empty($text)): ?>
												<p>
													<?php if($text): esc_html(printf(/* translators: %s: text */__( '%s','nexcraft-pro' ),$text)); endif; ?>
												</p>
											<?php endif; ?>
											
											<div class="client-info">
												<?php if(!empty($title) || !empty($subtitle)): ?>
													<h2>
														<?php if($title): esc_html(printf(/* translators: %s: title */__( '%s','nexcraft-pro' ),$title)); endif; ?>
													</h2>
													<span>
														<?php if($subtitle): esc_html(printf(/* translators: %s: subtitle */__( '%s','nexcraft-pro' ),$subtitle)); endif; ?>
													</span>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php ++$count; } ?>
					</div>
				</div>
			<?php } ?>
        </div>
    </section>
    <!-- testimonial end -->