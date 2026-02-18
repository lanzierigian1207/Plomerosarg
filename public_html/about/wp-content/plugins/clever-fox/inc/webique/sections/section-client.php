<?php  
	$client_hs 				= get_theme_mod('client_hs','1');
	$client_contents		= get_theme_mod('client_contents',webique_get_client_default());
	if($client_hs=='1'){	
?>
<section id="client-section" class="client-section client-home shape3-section" data-roller="start:0.5">
	<div class="av-container">		
		<div class="av-columns-area">
			<div class="av-column-12 client-inner">
				<div class="client-slider owl-carousel" data-cursor-type="text">
					<?php
						if ( ! empty( $client_contents ) ) {
						$client_contents = json_decode( $client_contents );
						foreach ( $client_contents as $client_item ) {							
							$link = ! empty( $client_item->link ) ? apply_filters( 'webique_translate_single_string', $client_item->link, 'Client section' ) : '';
							$image = ! empty( $client_item->image_url ) ? apply_filters( 'webique_translate_single_string', $client_item->image_url, 'Client section' ) : '';
							$newtab = ! empty( $client_item->newtab ) ? apply_filters( 'webique_translate_single_string', $client_item->newtab, 'Client section' ) : '';
							$nofollow = ! empty( $client_item->nofollow ) ? apply_filters( 'webique_translate_single_string', $client_item->nofollow, 'Client section' ) : '';
					?>
						<div class="client-item wow fadeInUp bubbly-effect" data-wow-delay="0ms" data-wow-duration="1500ms">
							<a href="<?php echo esc_url($link); ?>" <?php if($newtab =='yes') {echo 'target="_blank"'; } ?> rel="<?php if($newtab =='yes') {echo 'noreferrer noopener'; } ?> <?php if($nofollow =='yes') {echo 'nofollow'; } ?>">
								<?php if(!empty($image)): ?>
									<img src="<?php echo esc_url($image); ?>">
								<?php endif; ?>
							</a>
						</div>
					<?php } } ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php } ?>