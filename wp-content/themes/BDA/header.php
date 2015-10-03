<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <title><?php wp_title(); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
        <link rel="icon" type="image/x-icon" href="http://example.com/favicon.ico" />
		<link href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="<?php bloginfo('stylesheet_url');?>" type="text/css" rel="stylesheet" media="screen, projection" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,300italic,300,700,700italic|Open+Sans+Condensed:300,700' rel="stylesheet" type='text/css'>
		<script src="http://code.jquery.com/jquery.js"></script>
		<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/bootstrap.min.js"></script>
    <?php
    // add JS for comment threading support
    if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
    ?>
		<?php wp_head(); ?>
	</head>
 
  <body <?php body_class(isset($class) ? $class : ''); ?>>
    <div class="container">
    <nav class="navbar navbar-default" role="navigation">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?php echo home_url(); ?>"><img src="<?php bloginfo('template_directory')?>/images/logo.jpg" height="50" /></a>
      </div>
   
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse">
       <?php wp_nav_menu( array('menu' => 'Main', 'menu_class' => 'nav navbar-nav navbar-right', 'depth'=> 3, 'container'=> false, 'walker'=> new Bootstrap_Walker_Nav_Menu)); ?>
      </div><!-- /.navbar-collapse -->
    </nav>
    </div>

    <div id="main-container" class="container">
    
    