<?php  
	$webique_blog_hs 			= get_theme_mod('blog_hs','1');
	$webique_blog_title 		= get_theme_mod('blog_title');
	$webique_blog_desc			= get_theme_mod('blog_description');  
	$webique_blog_display_num	= get_theme_mod('blog_display_num','3'); 
	if($webique_blog_hs=='1'){
?>
<section id="post-section" class="post-section av-py-default blog-home">
	<div class="post-bg-box"></div>
	<div class="av-container position-relative">
			<div class="av-columns-area">
				<div class="av-column-12">
				<?php if(!empty($webique_blog_title)  || !empty($webique_blog_desc)): ?>	
				<div class="heading-default text-center my-sm-5">
					<div class="title-container animation-style2">
						<div class="arrow-left"></div>
							<?php if(!empty($webique_blog_title)): ?>
								<h1 class="title"><?php echo wp_kses_post($webique_blog_title); ?></h1>				
							<?php endif; ?>
						<div class="arrow-right"></div>
					</div>
					<?php if(!empty($webique_blog_desc)): ?>
						<p><?php echo wp_kses_post($webique_blog_desc); ?></p>
					<?php endif; ?>	
				</div>
				<?php endif; ?>
				
				<div class="post-carousel owl-carousel owl-theme" data-cursor-type="text">
				<?php 
					$webique_blog_args = array( 'post_type' => 'post',  'posts_per_page' => $webique_blog_display_num,'post__not_in'=>get_option("sticky_posts")) ; 	
					
					$webique_wp_query = new WP_Query($webique_blog_args);
					if($webique_wp_query)
					{	
					 $post_count=0;
					while($webique_wp_query->have_posts()):$webique_wp_query->the_post();  
				?>
					<article class="post-items wow fadeInUp" data-wow-delay="300ms" data-wow-duration="1500ms">
					<?php if ( has_post_thumbnail() ) { ?>
						<figure class="post-image">
							<div class="featured-image">
								<a href="<?php get_permalink(); ?>" class="post-hover av-media">
									<?php the_post_thumbnail(); ?>
								</a>
								<a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>" class="post-author d-flex align-items-center">
									<img src="<?php echo esc_url(get_avatar_url(get_the_author_meta( 'ID' ))); ?>">
								   <span> <?php esc_html(the_author()); ?> </span>
								</a>
							</div>
						</figure>
					<?php } ?>
						<div class="post-content">
							<span class="post-date"><a href="<?php echo esc_url(get_day_link(get_post_time('Y'), get_post_time('m'), get_post_time('j'))); ?>"><span><i class="fa fa-calendar"></i></span> <?php echo esc_html(get_the_date('j')); ?> <?php echo esc_html(get_the_date('M')); ?> <?php echo esc_html(get_the_date('Y')); ?></a></span>
							<p class="blog-category mt-3 ">								
								<i class="fa fa-folder-open"></i><?php the_category(', '); ?>
							</p>
							<?php     
								if ( is_single() ) :
								
								the_title('<h5 class="post-title">', '</h5>' );
								
								else:
								
								the_title( sprintf( '<h5 class="post-title"><a href="%s" rel="bookmark" class="ellipsis">', esc_url( get_permalink() ) ), '</a></h5>' );
								
								endif; 
							?> 
							<?php 
								the_content( 
										sprintf( 
											__( 'Read More', 'webique' ), 
											'<span class="screen-reader-text">  '.esc_html(get_the_title()).'</span>' 
										) 
									);
							 ?>
							<div class="post-footer mt-4">
								<i class="fa fa-tags"></i>
								<a href="#"><?php the_tags(''); ?></a>
							</div>
						</div>
					</article>
					<?php endwhile; 
					}
					wp_reset_postdata(); ?>					
				</div>
			</div>
		</div>
	</div>
</section>
<?php } ?>