<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Webique
 */

get_header();
$class =  (!is_single() || is_active_sidebar('webique-woocommerce-sidebar') )?'col-lg-8':'col-lg-12';
?>
<!-- Blog & Sidebar Section -->
<section id="product" class="post-section av-py-default">
	<div class="av-container">
		<div class="row">
		<!--Blog Detail-->
			<div <?php echo ( !is_single() || is_active_sidebar('webique-woocommerce-sidebar') ) ? 'id="av-primary-content"' : ''; ?> class="<?php echo esc_attr($class); ?> wow fadeInUp av-primary-content">
				<?php woocommerce_content(); ?>
			</div>		
		<?php get_sidebar('woocommerce'); ?>
		</div>	
	</div>
</section>
<!-- End of Blog & Sidebar Section -->

<?php get_footer(); ?>