<?php
/**
 * template name: map
 */
?>
<?php get_header();?>
<div id="content">
		<div class="post" id="post-<?php the_ID(); ?>">
		<h2><?php //the_title(); ?></h2>
		<?php
			$my_id = get_the_ID();
			$post = get_post($my_id);
			$content = $post->post_content;
			$content = apply_filters('the_content', $content);
			$content = str_replace(']]>', ']]>', $content);
			echo $content;
		?>
		</div>
</div>

<?php get_footer(); ?>
