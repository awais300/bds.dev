<?php
/**
 * template name:Visualization
 */
?>
<?php get_header();?>

<?php 
$prod_val = '';
$disable = 'disabled="disabled"';
if(isset($_GET['prod']) && !empty($_GET['prod'])) {
	$prod_val = $_GET['prod'];
	$disable = '';
} 
$countries = get_countries();
$current_country = get_ip_to_country();
?>

<div id="content">
		<div class="post cmaps" id="post-<?php the_ID(); ?>">
		<h2>Top participating countries - Alternate Products</h2>
		<?php echo do_shortcode('[visualizer id="68"]'); ?>
		</div>
		<hr>
		<div class="post cmaps" id="post-<?php the_ID(); ?>">
		<h2>Top participating countries - Boycott Products</h2>
		<?php echo do_shortcode('[visualizer id="78"]'); ?> 
		</div>
</div>

<?php get_footer(); ?>
