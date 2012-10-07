<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package ydn
 * @since ydn 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'ydn' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>
  <div id="pre-header" class="container">
    <div class="pull-left">FOLLOW US <a target="_blank" href="http://www.facebook.com/YaleDailyNews"><img alt="Follow us on Facebook" src="http://yaledailynews.media.clients.ellingtoncms.com/static//ydnRedesign/images/facebook.png"></a> <a target="_blank" href="http://www.twitter.com/YaleDailyNews"><img alt="Follow us on Twitter" src="http://yaledailynews.media.clients.ellingtoncms.com/static//ydnRedesign/images/twitter.png"></a> &bull; <a href="/contact/">Contact Us</a> | <a href="/advertising/">Advertise</a> | <a href="http://alumni.yaledailynews.com/">Alumni</a> | <a href="/subscribe/">Subscribe</a> | <a href="/alerts/manage">Subscribe to e-mail headlines</a></div>
    <div class="pull-right"><?php get_search_form(); ?></div>
  </div>
	<header class="site-header" role="banner">
    <div id="masthead" class="container">
      <a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
    </div>

		<nav role="navigation" class="site-navigation main-navigation">
			<h1 class="assistive-text"><?php _e( 'Menu', 'ydn' ); ?></h1>
			<div class="assistive-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'ydn' ); ?>"><?php _e( 'Skip to content', '_s' ); ?></a></div>
      <div class="navbar">
        <div class="navbar-inner">
          <div class="menu-primary-container container">
            <ul id="menu-primary-menu" class="nav">
              <li id="menu-item-41" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home active menu-item-41"><a href="http://localhost/" class="active">Home</a></li>
              <li id="menu-item-75" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-75"><a href="http://localhost/blog/category/city/">City</a></li>
              <li id="menu-item-76" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-76"><a href="http://localhost/blog/category/culture/">Culture</a></li>
              <li id="menu-item-77" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-77"><a href="http://localhost/blog/category/opinion/">Opinion</a></li>
              <li id="menu-item-78" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-78"><a href="http://localhost/blog/category/scitech/">Sci/Tech</a></li>
              <li id="menu-item-79" class="menu-item menu-item-type-taxonomy menu-item-object-category dropdown menu-item-79"><a href="http://localhost/blog/category/sports/" class="dropdown-toggle" data-toggle="dropdown">Sports <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li id="menu-item-80" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-80"><a href="http://localhost/blog/category/sports/sports-fall/">Fall</a></li>
                  <li id="menu-item-81" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-81"><a href="http://localhost/blog/category/sports/sports-spring/">Spring</a></li>
                  <li id="menu-item-82" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-82"><a href="http://localhost/blog/category/sports/sports-winter/">Winter</a></li>
              </ul>
            </li>
            <li id="menu-item-83" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-83"><a href="http://localhost/blog/category/university/">University</a></li>
            <li id="menu-item-84" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-84"><a href="http://localhost/blog/category/weekend/">WEEKEND</a></li>
          </ul></div>

        </div>
      </div>
		</nav>
	</header><!-- #masthead .site-header -->

	<div id="main" class="container">
    <?php if (!is_home() && function_exists('dynamic_sidebar')) { dynamic_sidebar('leaderboard'); } ?>
    <div class="row">
