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
<link rel="icon" 
      type="image/gif" 
      href="<?php bloginfo('stylesheet_directory'); ?>/ydn-logo.gif">
<link href="//cloud.webtype.com/css/4596b2de-7ff9-443c-a183-c8e0e32196e1.css" rel="stylesheet" type="text/css" />
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

<?php 
// This loads the static metadata if homepage.

if ( is_home() || is_front_page() )
{
  echo '
  <meta property="og:title" content= "The Yale Daily News" />
  <meta property="og:type" content="article"/>
  <meta property="og:url" content= "http://yaledailynews.com" />
  <meta property="og:image" content= "http://yaledailynews.com/wp-content/themes/yaledailynews/ydn-logo-fb.jpg"/>
  <meta property="og:site_name" content="Yale Daily News"/>
  <meta property="og:description"
        content=  "Daily newspaper for Yale University and New Haven. Includes coverage of university and city news, sports, culture, opinion, and more. Publishes Monday through Friday in print and online." />
    ';
}
 
else {

  $gtt = '"'.get_the_title().'"';
  $perma = '"'.get_permalink().'"';
  $excerpt = '"'.get_the_excerpt().'"';

  echo "
  <meta property='og:title' content= {$gtt} />
  <meta property='og:type' content='article'/>
  <meta property='og:url' content= {$perma} />
  <meta property='og:image' content= 'http://yaledailynews.com/wp-content/themes/yaledailynews/ydn-logo-fb.jpg'/>
  <meta property='og:site_name' content='Yale Daily News'/>
  <meta property='og:description'
      content=  {$excerpt} />
      ";
}
?>
 
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<!-- Google Analytics -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-38103759-1']);
  _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

  </script>
<!-- ComScore Analytics Script -->
<script>
  var _comscore = _comscore || [];
  _comscore.push({ c1: "2", c2: "15882552" });
  (function() {
    var s = document.createElement("script"), el = document.getElementsByTagName("script")[0]; s.async = true;
    s.src = (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js";
    el.parentNode.insertBefore(s, el);
  })();
</script>
<noscript>
  <img src="http://b.scorecardresearch.com/p?c1=2&c2=15882552&cv=2.0&cj=1" />
</noscript>
<!-- Google DFP -->
<script type='text/javascript'>
var googletag = googletag || {};
googletag.cmd = googletag.cmd || [];
(function() {
var gads = document.createElement('script');
gads.async = true;
gads.type = 'text/javascript';
var useSSL = 'https:' == document.location.protocol;
gads.src = (useSSL ? 'https:' : 'http:') + 
'//www.googletagservices.com/tag/js/gpt.js';
var node = document.getElementsByTagName('script')[0];
node.parentNode.insertBefore(gads, node);
})();
</script>

<script type='text/javascript'>
googletag.cmd.push(function() {
googletag.defineSlot('/1041068/YDN_Lower_Skyscraper', [160, 600], 'div-gpt-ad-1364346235312-0').addService(googletag.pubads());
googletag.pubads().enableSingleRequest();
googletag.enableServices();
});
</script>
<script type='text/javascript'>
var googletag = googletag || {};
googletag.cmd = googletag.cmd || [];
(function() {
var gads = document.createElement('script');
gads.async = true;
gads.type = 'text/javascript';
var useSSL = 'https:' == document.location.protocol;
gads.src = (useSSL ? 'https:' : 'http:') + 
'//www.googletagservices.com/tag/js/gpt.js';
var node = document.getElementsByTagName('script')[0];
node.parentNode.insertBefore(gads, node);
})();
</script>

<script type='text/javascript'>
googletag.cmd.push(function() {
googletag.defineSlot('/1041068/Top_of_page_small', [222, 90], 'div-gpt-ad-1378345467709-0').addService(googletag.pubads());
googletag.pubads().enableSingleRequest();
googletag.enableServices();
});
</script>

<!-- Homepage leaderboard -->
<script type='text/javascript'>
var googletag = googletag || {};
googletag.cmd = googletag.cmd || [];
(function() {
var gads = document.createElement('script');
gads.async = true;
gads.type = 'text/javascript';
var useSSL = 'https:' == document.location.protocol;
gads.src = (useSSL ? 'https:' : 'http:') + 
'//www.googletagservices.com/tag/js/gpt.js';
var node = document.getElementsByTagName('script')[0];
node.parentNode.insertBefore(gads, node);
})();
</script>

<script type='text/javascript'>
googletag.cmd.push(function() {
googletag.defineSlot('/1041068/YDN_Home_Leaderboard', [728, 90], 'div-gpt-ad-1365622060397-0').addService(googletag.pubads());
googletag.pubads().enableSingleRequest();
googletag.enableServices();
});
</script>

<script type='text/javascript'>
  (function() {
    var cx = '005865341660642744177:4iw9nrk-rsi';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>


<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
  <?php do_action( 'before' ); ?>
  <div id="pre-header" class="container">
  </div>
  <header class="site-header" role="banner">
    <div  class="container">
      <div id="masthead" class="pull-left"><?php  switch_to_blog(YDN_MAIN_SITE_ID); //this is necessary so that the menu and header link will be shared among all the children themes ?>
        <a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
      </div>
    <div id="toolbar" class="pull-right">
<style scoped>
.gsc-control-cse {
background-color: transparent;
border: 0;
}
</style>
<div style="margin-left: 5%;
margin-bottom: 20px;">
      <a target="_blank" href="http://www.facebook.com/YaleDailyNews"><img alt="Follow us on Facebook" class="social-media-icon" src="<?php echo get_template_directory_uri(); ?>/img/facebook-64.png"></a> 
      <a target="_blank" href="http://www.twitter.com/YaleDailyNews"><img alt="Follow us on Twitter" class="social-media-icon" src="<?php echo get_template_directory_uri(); ?>/img/twitter-64.png"></a> 
      <a target="_blank" href="http://yaledailynews.com/feed/"><img alt="Subscribe to RSS" class="social-media-icon" src="<?php echo get_template_directory_uri() ?>/img/rss-64.png"></a> 

</div>
      <gcse:search></gcse:search>
    </div>
    </div>

    <nav role="navigation" class="site-navigation main-navigation">
      <h1 class="assistive-text"><?php _e( 'Menu', 'ydn' ); ?></h1>
      <div class="assistive-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'ydn' ); ?>"><?php _e( 'Skip to content', '_s' ); ?></a></div>
      <div class="navbar">
        <div class="navbar-inner">
            <?php
  if (is_page_template('multimedia.php')) {
    wp_nav_menu( array( 'theme_location' => 'multimedia',
      'container_class' => 'menu-primary-container container',
      'walker' => new Bootstrap_Walker_Nav_Menu,
      'menu_class' => 'nav'
    ));
  } else {
    // Switch query to make sure menu gets populated
    $temp_query = $wp_query;
    $wp_query = NULL;
    $wp_query = new WP_Query(array('post_type' => 'post'));
    wp_nav_menu( array( 'theme_location' => 'primary',
      'menu' => 7,
      'container_class' => 'menu-primary-container container',
      'walker' => new Bootstrap_Walker_Nav_Menu,
      'menu_class' => 'nav'
    ));
    $wp_query = $temp_query;  // Switch back
    $temp_query = NULL;
  } ?>
  </div>
    <?php
            restore_current_blog(); //no longer necessary
            ?>
        </div>
      </div>
    </nav>
  </header><!-- #masthead .site-header -->



  <div id="main" class="container">
<?php 
  if (is_home() && function_exists('dynamic_sidebar'))
    dynamic_sidebar('home_leaderboard');

  if ((!is_home() || $GLOBALS['blog_id'] != 1) 
    && function_exists('dynamic_sidebar') && !is_page_template( 'multimedia.php' )) { 
      dynamic_sidebar('leaderboard'); 
    } ?>
    <div class="row">