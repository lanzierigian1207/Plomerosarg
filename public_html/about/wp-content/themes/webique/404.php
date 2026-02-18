<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Webique
 */

get_header();
?>
<section id="section404" class="section404 shape2-section">
	<div class="av-container">
		<div class="av-columns-area wow fadeInUp">
			<div class="av-column-6 text-center mx-auto">
				<div class="card404">	
						<h1 class="primary-color"><?php esc_html_e('404','webique'); ?></h1>		
						<h4><?php echo esc_html__('Page Not Found','webique'); ?></h4> 
						<p><?php echo esc_html__('Oops! The Page you are looking for does not exist','webique'); ?></p>
					<div class="card404-btn mt-3">						
						<a href="<?php echo esc_url(home_url('/')); ?>" class="av-btn av-btn-secondary av-btn-bubble py-1"><?php esc_html_e('Go To Home','webique'); ?> <i class="fa fa-long-arrow-right"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php get_footer(); ?>
