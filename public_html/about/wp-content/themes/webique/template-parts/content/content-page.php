<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Webique
 */
?>

<article  id="post-<?php the_ID(); ?>" <?php post_class('post-items mb-6'); ?> data-wow-delay="300ms" data-wow-duration="1500ms">
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