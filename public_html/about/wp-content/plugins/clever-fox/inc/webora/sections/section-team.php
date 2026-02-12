<?php  
	$team_hs 				= get_theme_mod('team_hs','1');
	$team_title 			= get_theme_mod('team_title',__('Our <span class="primary-color">Team</span>','clever-fox'));
	$team_description		= get_theme_mod('team_description',__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','clever-fox')); 
	$team_contents			= get_theme_mod('team_contents',webora_get_team_default());
	if($team_hs=='1'){
?>	
 <section id="team-section" class="team-section av-py-default shape1-section">
	<div class="av-container">
		<?php if(!empty($team_title)  || !empty($team_description)): ?>
			<div class="av-columns-area">
				<div class="av-column-12">
					<div class="heading-default text-center">
						<div class="title-container animation-style2">
							<div class="arrow-left"></div>
								<?php if(!empty($team_title)): ?>
									<h1 class="title"><?php echo wp_kses_post($team_title); ?></h1>				
								<?php endif; ?>
							<div class="arrow-right"></div>
						</div>
						<?php if(!empty($team_description)): ?>
							<p><?php echo wp_kses_post($team_description); ?></p>
						<?php endif; ?>	
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="av-columns-area">
		<!-->
		<?php
		if ( ! empty( $team_contents ) ) {
			$team_contents = json_decode( $team_contents );
			foreach ( $team_contents as $team_item ) {
				$title = ! empty( $team_item->title ) ? apply_filters( 'webique_translate_single_string', $team_item->title, 'team section' ) : '';
				$subtitle = ! empty( $team_item->subtitle ) ? apply_filters( 'webique_translate_single_string', $team_item->subtitle, 'team section' ) : '';
				$image = ! empty( $team_item->image_url ) ? apply_filters( 'webique_translate_single_string', $team_item->image_url, 'team section' ) : '';
				$link = ! empty( $team_item->link ) ? apply_filters( 'webique_translate_single_string', $team_item->link, 'team section' ) : '';
				$newtab = ! empty( $team_item->newtab ) ? apply_filters( 'webique_translate_single_string', $team_item->newtab, 'team section' ) : '';
				$nofollow = ! empty( $team_item->nofollow ) ? apply_filters( 'webique_translate_single_string', $team_item->nofollow, 'team section' ) : '';
		?>
			<div class="av-column-3 av-sm-column-6 wow fadeInUp" data-wow-delay="0ms" data-wow-duration="1500ms">
				<div class="team-item box-hover">
					<div class="av-media">
						<img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr( sprintf(/*translators: Team Member Image */__('%s\'s Image','webique-pro'),$title) ); ?>">
					</div>
					<div class="team-content">
					<?php if(!empty($title)){ ?>
						<h5 class="av-name"><a href="<?php echo esc_url($link); ?>" <?php if($newtab =='yes') {echo 'target="_blank"'; } ?> rel="<?php if($newtab =='yes') {echo 'noreferrer noopener'; } ?> <?php if($nofollow =='yes') {echo 'nofollow'; } ?>"><?php echo esc_html( sprintf(/*translators: Title */__('%s','webique-pro'),$title) ); ?></a></h5>
					<?php } ?>
					<?php if(!empty($subtitle)){ ?>
						<span class="av-position"><?php echo esc_html( sprintf(/*translators: Designation */__('%s','webique-pro'),$subtitle) ); ?></span>
					<?php } ?>
					</div>
					<ul class="team-social">
						<?php if ( ! empty( $team_item->social_repeater ) ) :
							$icons         = html_entity_decode( $team_item->social_repeater );
							$icons_decoded = json_decode( $icons, true );
							if ( ! empty( $icons_decoded ) ) : ?>
							<?php
								foreach ( $icons_decoded as $value ) {
									$social_icon = ! empty( $value['icon'] ) ? apply_filters( 'webique_translate_single_string', $value['icon'], 'Team section' ) : '';
									$social_link = ! empty( $value['link'] ) ? apply_filters( 'webique_translate_single_string', $value['link'], 'Team section' ) : '';
									if ( ! empty( $social_icon ) ) {
							?>
								<li><a href="<?php echo esc_url( $social_link ); ?>" <?php if($newtab =='yes') {echo 'target="_blank"'; } ?> rel="<?php if($newtab =='yes') {echo 'noreferrer noopener'; } ?> <?php if($nofollow =='yes') {echo 'nofollow'; } ?>" ><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i></a></li>
						<?php	} } endif; endif; ?>
					</ul>
				</div>
			</div>
			<?php }} ?>
		   <!-->
		</div>
	</div>
</section>
<?php } ?>