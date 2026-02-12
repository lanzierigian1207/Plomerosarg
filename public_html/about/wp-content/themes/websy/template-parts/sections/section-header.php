<?php
if ( get_header_image() ) : ?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-header" id="custom-header" rel="home">
		<img src="<?php echo get_header_image(); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="<?php echo esc_attr(get_bloginfo( 'title' )); ?>">
	</a>
<?php endif; ?>

<?php 
	//Header Animation Bar Call
	do_action('webique_header_animation_bar');
?>

	
<!--===// Start: Header
        =================================-->		
	
<header id="header-section" class="header header-two">
<!--===// Start: Header Above
	=================================-->
	<?php
		$hide_show_email_details 	= get_theme_mod('hide_show_email_details', '1');
		$hide_show_mbl_details 		= get_theme_mod('hide_show_mbl_details', '1');		
		if( ( $hide_show_email_details == '1' ) || ( $hide_show_mbl_details == '1' ) ){
	?>
	<div id="above-header" class="header-above-info py-3 py-xl-4">
		<div class="av-container">
			<div class="row ">
				<div class="col-12 col-sm-6 col-lg-4 order-2 order-lg-1 wow fadeInLeft">
				<?php 
					$hide_show_email_details 	= get_theme_mod('hide_show_email_details', '1');
					$tlh_email_title 			= get_theme_mod('tlh_email_title');
					$tlh_email_icon 			= get_theme_mod('tlh_email_icon', 'fa-envelope-o');
					$tlh_email_link 			= get_theme_mod('tlh_email_link');
					if($hide_show_email_details == '1' ):								
				?>
					<aside class="widget widget-contact">
						<div class="contact-area">
							<div class="contact-icon zig-zag-bg icon-bounce">
								<i class="fa <?php echo  esc_attr($tlh_email_icon); ?>"></i>
							</div>
							<div class="icon-content">
							<?php if(!empty($tlh_email_title)){ ?>
								<h4 class="primary-color"><?php echo esc_html( sprintf(/*Translators: Email Title */ __('%s','websy'),$tlh_email_title)); ?></h4>
							<?php } ?>
							<?php if(!empty($tlh_email_link)){ ?>
								<a href="mailto:<?php echo esc_attr($tlh_email_link); ?>" class="contact-info">
									<span class="title"><?php echo esc_html( sprintf(/*Translators: Email Link */ __('%s','websy'),$tlh_email_link)); ?></span>
								</a>
							<?php } ?>
							</div>
						</div>
					</aside>
					<?php endif; ?>
				</div>				
				<div class="col-12 col-lg-4 order-1 order-lg-2 my-auto">
					<aside class="widget widget-contact d-flex justify-content-sm-center">
						<div class="logo">
							<?php do_action('webique_logo_content'); ?>
						</div>
					</aside>
				</div>				
				<?php 
					$hide_show_mbl_details 		= get_theme_mod('hide_show_mbl_details', '1');
					$tlh_mobile_title 			= get_theme_mod('tlh_mobile_title');
					$tlh_mobile_icon 			= get_theme_mod('tlh_mobile_icon', 'fa-whatsapp');
					$tlh_mobile_link 			= get_theme_mod('tlh_mobile_link');
					if($hide_show_mbl_details == '1' ):
				?>
				<div class="col-12 col-sm-6 col-lg-4 order-3 order-lg-3 wow fadeInRight">
					<aside class="widget widget-contact">
						 <div class="contact-area justify-content-sm-end justify-content-start mb-0">
							<div class="icon-content text-end">
							<?php if(!empty($tlh_mobile_title)){ ?>
								<h4 class="primary-color"><?php echo esc_html( sprintf(/*Translators: Mobile Title */ __('%s','websy'),$tlh_mobile_title)); ?></h4>
							<?php } ?>
							<?php if(!empty($tlh_mobile_link)){ ?>
								<a href="tel:<?php echo esc_attr(str_replace(' ', '', $tlh_mobile_link)); ?>" class="contact-info">
									<span class="title"><?php echo esc_html( sprintf(/*Translators: Mobile Link */ __('%s','websy'),$tlh_mobile_link)); ?></span>
								</a>
							<?php } ?>
							</div>
							<div class="contact-icon zig-zag-bg icon-bounce">
								<i class="fa <?php echo  esc_attr($tlh_mobile_icon); ?>"></i>
							</div>
						</div>
					</aside>
					<?php endif; ?>
				</div>
			</div>
		</div>		
	</div>
	<?php } ?>
	<!--===// End: Header Top
	=================================-->
	
	<div class="navigator-wrapper">
		<!--===// Start: Navigation
		=================================-->
			<div class="nav-area d-av-block ">
				<div class="navbar-area sticky-nav">
					<div class="av-container">
						<div class="row mx-2">
							<div class="col-4 col-lg-2 my-auto p-0">
								<div class="consult d-none d-lg-block">
										<?php do_action('websy_navigation_button_two'); ?>
							
									</div>
								<div class="d-block d-lg-none">
								   <?php do_action('webique_logo_content'); ?>
								</div>
							</div>
							<div class="col-8 col-lg-10 my-auto ps-md-5 pe-0">
								<div class="theme-menu">
									<div class="menu-box">
										<div class="hd-line-left"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
										<div class="hd-side-left"></div>
										<div class="hd-border-top"><div class="line"></div></div>
										<div class="hd-border-bottom"><div class="line"></div></div>
										<nav class="menubar" id="AVMenu">
											<div class="logo">
												<?php do_action('webique_logo_content'); ?>
											</div>
											<?php do_action('webique_primary_navigation'); ?>                
										</nav>
										<div class="hd-side-right"></div>
										<div class="hd-line-right"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
									</div>
									<div class="menu-right">
										<ul class="header-wrap-right">
											<?php do_action('webique_navigation_search'); ?>											
											<?php do_action('webique_navigation_cart'); ?>
																							
											<?php do_action('websy_navigation_toggle'); ?>								
												
											<li class="about-toggle-list d-lg-none">
												<div class="hamburger hamburger-menu">
													<button type="button" class="toggle-lines menu-toggle style-2" data-target="#AVMenu">
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
</header>


 <!-- End: Header
        =================================-->

<?php
	if ( !is_page_template( 'templates/template-homepage.php' ) ) {
		webique_breadcrumbs_style();  
	}	
?>