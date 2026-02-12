<?php 
class Webique_pagination
{	function Webique_page($curpage, $post_type_data)
	{	?>
<?php if($post_type_data->max_num_pages > 1) { ?>
	
	<div class="av-column-12 mt-5 wow fadeInUp" data-wow-delay="0ms" data-wow-duration="1500ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: fadeInUp;">
		<section class="pagination">
			<?php
			if($curpage > 1  )	{ ?>
			<a id="pg-button-prev" href="<?php echo get_pagenum_link(($curpage-1 > 1 ? $curpage-1 : 1)); ?>" class="pagination__button" >
				<i class="fa fa-angle-left"></i>
			</a>
			<?php } ?>
			
			<ul class="pagination__list">
			<?php for($i=1;$i<=$post_type_data->max_num_pages;$i++)	{ ?>
				<li class="pagination__item pagination__item--<?php echo $i; ?>">
				<?php if ( $i == $curpage ) { ?>
					<span id="pg-button-<?php echo $i; ?>" class="active"  ><?php echo $i; ?></span>
				<?php } else { ?>
					<a id="pg-button-<?php echo $i; ?>" href="<?php echo get_pagenum_link($i); ?>" ><?php echo $i; ?></a>
				<?php } ?>
				</li>
			<?php } ?>							
			</ul>
			
			<?php if($i-1 != $curpage) { ?>
			<a id="pg-button-next" href="<?php echo get_pagenum_link(($curpage + 1 <= $post_type_data->max_num_pages ? $curpage + 1 : $post_type_data->max_num_pages)); ?>" class="pagination__button">
				<i class="fa fa-angle-right"></i>
			</a>
			<?php } ?>
		</section>
	</div>
	
<?php }
	} 
}
?>