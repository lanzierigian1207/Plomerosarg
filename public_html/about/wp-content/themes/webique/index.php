<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Webique
 */

get_header(); 
?>
<section id="post-section" class="post-section av-py-default">
	<div class="av-container">
		<div class="av-columns-area">
			 <div id="av-primary-content" class="<?php esc_attr(webique_post_layout()); ?>">
			
				<?php 
					$webique_paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
					$args = array( 'post_type' => 'post','paged'=>$webique_paged );	
					$blog = new WP_Query( $args );
				?>
				<?php if( $blog->have_posts() ): ?>
					<?php while( $blog->have_posts() ) : $blog->the_post(); 
							get_template_part('template-parts/content/content','page'); 
					endwhile; ?>			
			
					<!--Pagination -->
					<?php $cpount_blog = wp_count_posts()->publish;
						if($cpount_blog > '1') { 
					?>
					<?php 
						$Webique_pagination = new Webique_pagination();
						$Webique_pagination->Webique_page($webique_paged, $blog);
						} else { 
						/*No Pagination*/
						} 
					?>
				
				<?php else: ?>
					<?php get_template_part('template-parts/content/content','none'); ?>
				<?php endif; ?>
			</div>
			<?php  get_sidebar(); ?>
		</div>
	</div>
</section>
<?php get_footer(); ?>
