</div>
<!--===// Start: Cursor
	=================================-->
<div id="cursor">
	<div class="cursor__circle"></div>
</div>
 <!-- End: Cursor
=================================-->
<?php $footer_effect_enable = get_theme_mod('footer_effect_enable', '1'); ?>
<footer id="footer-section" class="footer-section footer-one <?php if($footer_effect_enable) echo 'ripple-area'; ?>">

	<?php  do_action('webique_above_footer'); ?>

<!--*** Widgets Collection ***-->

<div class="footer-content py-4">
	<div class="av-container">
		<div class="row">
			<?php  dynamic_sidebar( 'webique-footer-1');  ?>
			<?php  dynamic_sidebar( 'webique-footer-2');  ?>
			<?php  dynamic_sidebar( 'webique-footer-3');  ?>
			<?php  dynamic_sidebar( 'webique-footer-4');  ?>			
		</div>
	</div>
</div>

<?php do_action('webique_above_copy_footer'); ?>

<div class="footer-copyright">
	<div class="av-container">
		<div class="row flex-column align-items-center flex-md-row justify-content-between">		
		   <div class="col-auto wow fadeInUp order-1 order-md-0">
				<div class="widget-left">
					<?php do_action('webique_footer_group_first'); ?>
				</div>
			</div>
			<div class="col-auto wow fadeInUp">
				<div class="widget-right">                          
					<?php do_action('webique_footer_group_second'); ?>
				</div>
			</div>
		</div>
	</div>
</div>
</footer>


<!--=========
Scroll up
===========-->
<button type="button" class="scrollup style-1 is-active"><i class="fa fa-arrow-up"></i></button>

<!--============
End: Scroll up
=============-->
<?php wp_footer(); ?>
