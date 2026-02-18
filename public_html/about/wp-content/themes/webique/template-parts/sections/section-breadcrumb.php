<?php 
	$webique_hs_breadcrumb			= get_theme_mod('hs_breadcrumb','1');
	$webique_bread_effect_enable	= get_theme_mod('breadcrumb_effect_enable','1');
	
if($webique_hs_breadcrumb == '1') {	
?>
<section id="breadcrumb-section" class="breadcrumb-section breadcrumb-left <?php if($webique_bread_effect_enable=='1'): echo 'ripple-area'; endif; ?>">
	<div class="av-container">
		<div class="av-columns-area">
			<div class="av-column-12">
				<div class="breadcrumb-content">
					<div class="breadcrumb-icone wow fadeInLeft" style="visibility: visible; animation-name: fadeInLeft;">
						<a href="<?php echo esc_url(home_url('/')); ?>" class="wave-effect"><i class="fa fa-home"></i></a>
					</div>
					<div class="breadcrumb-heading wow fadeInLeft">
						<h2><?php 
								if ( is_home() || is_front_page()):

									single_post_title();
							
								elseif ( is_day() ) : 
								
									printf( __( 'Daily Archives: %s', 'webique' ), get_the_date() );
								
								elseif ( is_month() ) :
								
									printf( __( 'Monthly Archives: %s', 'webique' ), (get_the_date( 'F Y' ) ));
									
								elseif ( is_year() ) :
								
									printf( __( 'Yearly Archives: %s', 'webique' ), (get_the_date( 'Y' ) ) );
									
								elseif ( is_category() ) :
								
									printf( __( 'Category Archives: %s', 'webique' ), (single_cat_title( '', false ) ));

								elseif ( is_tag() ) :
								
									printf( __( 'Tag Archives: %s', 'webique' ), (single_tag_title( '', false ) ));
									
								elseif ( is_404() ) :

									printf( __( 'Error 404', 'webique' ));
									
								elseif ( is_author() ) :
								
									printf( __( 'Author: %s', 'webique' ), (get_the_author( '', false ) ));
									
								elseif ( class_exists( 'woocommerce' ) ) : 
									
									if ( is_shop() ) {
										woocommerce_page_title();
									}
									
									elseif ( is_cart() ) {
										the_title();
									}
									
									elseif ( is_checkout() ) {
										the_title();
									}
									
									else {
										the_title();
									}
								else :
										the_title();
										
								endif;
									
							?></h2>	
					</div>
					<div class="breadcrumb-list wow fadeInRight">
						<ul><?php if (function_exists('webique_breadcrumbs')) webique_breadcrumbs();?></ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php } ?>	