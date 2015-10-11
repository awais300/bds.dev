<?php
/**
 * template name: Submit Product
 */
?>
<?php get_header();?>

<?php 
$prod_val = '';
if(isset($_GET['prod']) && !empty($_GET['prod'])) {
	$prod_val = $_GET['prod'];
}
$countries = get_countries();
$current_country = get_ip_to_country();
?>

<div id="content">
		<div class="post" id="post-<?php the_ID(); ?>">
<h2><?php the_title(); ?></h2>
			<div class="altr_product_cs">            
				<div class="col-md-6 cs_frm">
					<form id="submit-product" class="submit-product" name="submit-product" action="/" method="post">
						<label>Buycott Product Name*</label>
						<div id='loading-small-1'><img class="spin-img" src="<?php bloginfo('template_directory')?>/images/spinner.gif"></div>
						<input type="text" name="ptitle" id="ban-product" autofocus value="<?php echo $prod_val; ?>" class="ban-product" />

						<div id="alt-container">
						<label>Alternate to Buycott Product</label>
						<div id='loading-small-2'><img class="spin-img" src="<?php bloginfo('template_directory')?>/images/spinner.gif"></div>
						<input type="text" name="alt-product[]" disabled="disabled" class="alt-products" /><span></span>
						</div>
						<p><a href="#" id="addnew">Add more alternates</a></p>

						<input type="hidden" name="pp" id="pp" value="No">
						<input type="hidden" name="pid" id="pid" value="0">
						<input type="hidden" name="cp" id="cp" value="No">

						<label>Your Country Name*</label>	
						<select name="country" class="country" id="country">
						<option value="">Select</option>
						<?php 
							$selected = '';
							foreach ($countries as $country): 
								if(strtolower($country) == strtolower($current_country)) {
									$selected = "selected='selected'";
								}
						?>
							<option value="<?php echo $country?>" <?php echo $selected;?>><?php echo $country?></option>
						<?php $selected =''; endforeach; ?>
						</select>
						<br/>
						<input id="submit-btn" type="submit" value="submit" class="submit-btn" />
						<div id="info-message"></div>
					</form>
				</div>
			</div>
		</div>
</div>

<?php get_footer(); ?>
