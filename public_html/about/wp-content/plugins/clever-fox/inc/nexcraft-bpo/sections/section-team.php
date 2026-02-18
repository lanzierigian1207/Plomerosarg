<?php  
	$team_title				= get_theme_mod('team_title','Team');
	$team_description		= get_theme_mod('team_description','Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur quisquam saepe eveniet, cumque tempore veritatis!');
	$team_contents			= get_theme_mod('team_contents',nexcraft_get_team_default());
	$team_sec_column		= get_theme_mod('team_sec_column','3');  
	$team_bg_position		= get_theme_mod('team_bg_position','fixed');
?>
<!-- team  -->
    <section class="team-section"  id="team-section">
        <div class="container">
            <?php if(!empty($team_title) || !empty($team_description)): ?>
				<div class="section-title col-lg-6 mx-auto">
					<?php if(!empty($team_title)): ?>
						<h2 class="maintitle">
						<svg xmlns="http://www.w3.org/2000/svg" width="54" height="27" viewBox="0 0 54 27" style="fill: var(--primary-color);" class="desg1"><path id="Rectangle_2_copy_3" data-name="Rectangle 2 copy 3" class="cls-1" d="M1156 147h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-1A2 2 0 0 1 1156 147Zm7 0h5a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-5a2 2 0 0 1-2-2v-1A2 2 0 0 1 1163 147Zm3 13h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-1A2 2 0 0 1 1166 160Zm7 0h8a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-8a2 2 0 0 1-2-2v-1A2 2 0 0 1 1173 160Zm-11.5 11a1.5 1.5 0 1 1-1.5 1.5A1.5 1.5 0 0 1 1161.5 171Zm4 0h3a1.5 1.5 0 0 1 0 3h-3A1.5 1.5 0 0 1 1165.5 171Zm7 0h7a1.5 1.5 0 0 1 0 3h-7A1.5 1.5 0 0 1 1172.5 171Zm16.5-11h17a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-17a2 2 0 0 1-2-2v-1A2 2 0 0 1 1189 160Z" transform="translate(-1154 -147)"/></svg>
						
							<span><?php echo wp_kses_post($team_title); ?></span>
						
						<svg xmlns="http://www.w3.org/2000/svg" width="54" height="27" viewBox="0 0 54 27" style="fill: var(--primary-color);"><path id="Rectangle_2_copy_3" data-name="Rectangle 2 copy 3" class="cls-1" d="M1156 147h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-1A2 2 0 0 1 1156 147Zm7 0h5a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-5a2 2 0 0 1-2-2v-1A2 2 0 0 1 1163 147Zm3 13h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-1A2 2 0 0 1 1166 160Zm7 0h8a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-8a2 2 0 0 1-2-2v-1A2 2 0 0 1 1173 160Zm-11.5 11a1.5 1.5 0 1 1-1.5 1.5A1.5 1.5 0 0 1 1161.5 171Zm4 0h3a1.5 1.5 0 0 1 0 3h-3A1.5 1.5 0 0 1 1165.5 171Zm7 0h7a1.5 1.5 0 0 1 0 3h-7A1.5 1.5 0 0 1 1172.5 171Zm16.5-11h17a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-17a2 2 0 0 1-2-2v-1A2 2 0 0 1 1189 160Z" transform="translate(-1154 -147)"/></svg>
					</h2>
					<?php endif; ?>
					
					<?php if(!empty($team_description)): ?>
						<p>
							<?php echo esc_html($team_description); ?>
						</p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
            <div class="row">
				<?php
					$team_contents = json_decode($team_contents);
					if( $team_contents!='' )
					{
					foreach($team_contents as $team_item){
					$icon   = ! empty( $team_item->icon_value ) ? apply_filters( 'nexcraft_translate_single_string', $team_item->icon_value, 'Team section' ) : '';
					$image    = ! empty( $team_item->image_url2 ) ? apply_filters( 'nexcraft_translate_single_string', $team_item->image_url2, 'Team section' ) : '';
					$title    = ! empty( $team_item->title ) ? apply_filters( 'nexcraft_translate_single_string', $team_item->title, 'Team section' ) : '';
					$subtitle = ! empty( $team_item->subtitle ) ? apply_filters( 'nexcraft_translate_single_string', $team_item->subtitle, 'Team section' ) : '';
				?>			
					<div class="col-lg-3 col-md-6 col-sm-6">
						<div class="team">
							<?php if(!empty($image)): ?>
								<div class="team-image">
									<img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
								</div>
							<?php endif; ?>	
							
							
							<div class="team-content">
								<?php if(!empty($title) || !empty($subtitle)): ?>
									<div class="icon">
										<i class="fas <?php echo esc_attr($icon); ?>"></i>
									</div>
									<h2>
										<?php if($title): esc_html(printf(/* translators: %s: title */__( '%s','nexcraft-pro' ),$title)); endif; ?>
									</h2>
									<span>
										<?php if($subtitle): esc_html(printf(/* translators: %s: subtitle */__( '%s','nexcraft-pro' ),$subtitle)); endif; ?>
									</span>
								<?php endif; ?>
								
								<aside class="widget widget_social_widget">
									<ul>
										<?php if ( ! empty( $team_item->social_repeater ) ) :
											$icons         = html_entity_decode( $team_item->social_repeater );
											$icons_decoded = json_decode( $icons, true );
											if ( ! empty( $icons_decoded ) ) : ?>
											<?php
												foreach ( $icons_decoded as $value ) {
													$social_icon = ! empty( $value['icon'] ) ? apply_filters( 'nexcraft_translate_single_string', $value['icon'], 'Team section' ) : '';
													$social_link = ! empty( $value['link'] ) ? apply_filters( 'nexcraft_translate_single_string', $value['link'], 'Team section' ) : '';
													if ( ! empty( $social_icon ) ) {
											?>
												<li><a href="<?php echo esc_url( $social_link ); ?>"><i class=" fab <?php echo esc_attr( $social_icon ); ?>"></i></a></li>
										<?php	} } endif; endif; ?>
									</ul>
								</aside>
							</div>								
						</div>
					</div>
				<?php } } ?>	
            </div>
        </div>
    </section>
    <!-- team end -->