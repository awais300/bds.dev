<?php
/*
Template Name: Home
*/
?>

<?php get_header(); ?>

<div id="content">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
        <!-- search -->
        <div id='loading'><img src="<?php bloginfo('template_directory')?>/images/spinner.gif"></div>
        <div class="search-box cs_search_fld">
	        <div id="live-search" class="span4 offset4">
		        <form method="get" id="bds-search" action="/">
		        <input type="text" value="" placeholder="Type buycott product name" autofocus name="s" id="bds-search-input" class=" drop_down"/><input type="submit" value="Search">
		        </form>
	        </div>
        </div>
        
        <!-- /search -->
            
            <div class="altr_product_cs">
            
            	<div class="col-md-6 wrap_product_section">
            		<div id="alternate-content" class="alternate-content"></div>
            	</div>
                
				<?php //the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

				<?php //wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			</div>
		</div>
		<?php endwhile; endif; ?>
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
	
	<?php comments_template(); ?>

</div>

<?php get_footer(); ?>
