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
      <?php  switch_to_blog(YDN_MAIN_SITE_ID); //this is necessary so that the menu and header link will be shared among all the children themes ?>
      <a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
    </div>

    <nav role="navigation" class="site-navigation main-navigation">
      <h1 class="assistive-text"><?php _e( 'Menu', 'ydn' ); ?></h1>
      <div class="assistive-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'ydn' ); ?>"><?php _e( 'Skip to content', '_s' ); ?></a></div>
      <div class="navbar">
        <div class="navbar-inner">
            <?php
            wp_nav_menu( array( 'theme_location' => 'primary',
                                    'container_class' => 'menu-primary-container container',
                                    'walker' => new Bootstrap_Walker_Nav_Menu,
                                    'menu_class' => 'nav'
                                 ));
            restore_current_blog(); //no longer necessary
            ?>

        </div>
      </div>
    </nav>
  </header><!-- #masthead .site-header -->

  <div id="main" class="container">
    <?php if (!is_home() && function_exists('dynamic_sidebar')) { dynamic_sidebar('leaderboard'); } ?>
    <div class="row">
