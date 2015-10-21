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
		        <input type="text" value="" placeholder="Type boycott product name" autofocus name="s" id="bds-search-input" class=" drop_down"/><input type="submit" value="Search">
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
	
	<?php //comments_template(); ?>
	<div id="disqus_thread"></div>
<script>
    
    var disqus_config = function () {
        this.page.url = 'http://bds.dev/',
        this.page.identifier = bdsdev
    };
    
    (function() {  // DON'T EDIT BELOW THIS LINE
        var d = document, s = d.createElement('script');
        
        s.src = '//bdsdev.disqus.com/embed.js';
        
        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
    })();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>

</div>

<?php get_footer(); ?>
