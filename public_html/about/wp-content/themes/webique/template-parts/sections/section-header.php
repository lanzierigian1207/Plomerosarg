<?php
if ( get_header_image() ) : ?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-header" id="custom-header" rel="home">
		<img src="<?php echo esc_url(get_header_image()); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="<?php echo esc_attr(get_bloginfo( 'title' )); ?>">
	</a>
<?php endif; ?>

<?php 
	//Header Animation Bar Call
	do_action('header_animation_bar'); 
?>

<!--===// Start: Header
=================================-->
<header id="header-section" class="header header-one">
	<div class="navigator-wrapper">
		<!--===// Start: Navigation
		=================================-->
			<div class="nav-area d-av-block ">
				<div class="navbar-area <?php echo esc_attr(webique_sticky_menu()); ?>">
					<div class="av-container">
						<div class="row mx-2">
							<div class="col-5 col-lg-3 my-auto p-0">
								<div class="logo">
									<?php do_action('webique_logo_content'); ?>
								</div>
							</div>
							<div class="col-7 col-lg-9 my-auto p-0">
								<div class="theme-menu">
									<nav class="menubar" id="AVMenu">
										<div class="logo">
											<?php do_action('webique_logo_content'); ?>
										</div>
										<?php do_action('webique_primary_navigation'); ?>                       
									</nav>
									<div class="menu-right">
										<ul class="header-wrap-right">
											<?php do_action('webique_navigation_search'); ?>											
											<?php do_action('webique_navigation_cart'); ?>
											<?php if(class_exists('Clever_Fox_Setup')) { do_action('webique_navigation_toggle'); } ?>
											<li class="about-toggle-list d-lg-none">
												<div class="hamburger hamburger-menu">
													<button type="button" class="toggle-lines menu-toggle" data-target="#AVMenu">
														<div class="top-bun"></div>
														<div class="meat"></div>
														<div class="bottom-bun"></div>
													</button>
													<div class="menu-close fade-overlay"></div>
												</div>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<!--===// End:  Navigation
		=================================-->
	</div>
	<!--===// Start: Header Above
	=================================-->
	<?php do_action('webique_above_header'); ?>
	<!--===// End: Header Top
	=================================-->
 </header>
<!-- End: Header
=================================-->
	

<?php
	if ( !is_page_template( 'templates/template-homepage.php' ) ) {
		webique_breadcrumbs_style();  
	}	
?>