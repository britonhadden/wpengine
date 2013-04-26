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
<meta property="og:title" content= "<?php echo get_the_title(); ?>" />
<meta property="og:type" content="article"/>
<meta property="og:url" content= "<?php echo get_permalink(); ?>" />
<meta property="og:image" content= "http://yaledailynews.com/wp-content/themes/yaledailynews/ydn-logo-fb.jpg"/>
<meta property="og:site_name" content="Yale Daily News"/>
<meta property="og:description"
      content=  "<?php echo get_the_excerpt(); ?>" />
 
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
			<a target="_blank" href="http://www.facebook.com/YaleDailyNews"><img alt="Follow us on Facebook" class="social-media-icon" src="<?php echo get_template_directory_uri(); ?>/img/facebook-64.png"></a> 
			<a target="_blank" href="http://www.twitter.com/YaleDailyNews"><img alt="Follow us on Twitter" class="social-media-icon" src="<?php echo get_template_directory_uri(); ?>/img/twitter-64.png"></a> 
			<a target="_blank" href="http://yaledailynews.com/feed/"><img alt="Subscribe to RSS" class="social-media-icon" src="<?php echo get_template_directory_uri() ?>/img/rss-64.png"></a> 
			<?php get_search_form(); ?>
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
  } else if (is_home() && get_current_blog_id() == 1){
    // The query reset fixes a navbar bug where the navbar won't show up on category pages.
    // $backup_query = $wp_query;
    // $wp_query = NULL;
    // $wp_query = new WP_Query(array('post_type' => 'post')); 
    wp_nav_menu( array( 'theme_location' => 'primary',
      'container_class' => 'menu-primary-container container',
      'walker' => new Bootstrap_Walker_Nav_Menu,
      'menu_class' => 'nav'
    ));
  } else {
?>  <!-- The following is a sloppy but temporary hack to make top menu navbar work on category pages. -->
<div class="menu-primary-container container"><ul id="menu-primary" class="nav"><li id="menu-item-91845" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home active menu-item-91845" style="margin-right: 6px;"><a href="http://yaledailynews.com/" class="active">Home</a></li>
<li id="menu-item-89389" class="menu-item menu-item-type-custom menu-item-object-custom dropdown menu-item-89389" style="margin-right: 6px;"><a href="#" class="dropdown-toggle" data-toggle="dropdown">News <b class="caret"></b></a>
<ul class="dropdown-menu">
  <li id="menu-item-89394" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-89394"><a href="http://yaledailynews.com/blog/category/university/">University</a></li>
  <li id="menu-item-89390" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-89390"><a href="http://yaledailynews.com/blog/category/city/">City</a></li>
  <li id="menu-item-89391" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-89391"><a href="http://yaledailynews.com/blog/category/culture/">Culture</a></li>
  <li id="menu-item-89392" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-89392"><a href="http://yaledailynews.com/blog/category/features/">Features</a></li>
  <li id="menu-item-89393" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-89393"><a href="http://yaledailynews.com/blog/category/sci-tech/">Sci-Tech</a></li>
  <li id="menu-item-90186" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-90186"><a href="http://yaledailynews.com/archives/">Archives</a></li>
</ul>
</li>
<li id="menu-item-89395" class="menu-item menu-item-type-taxonomy menu-item-object-category dropdown menu-item-89395" style="margin-right: 6px;"><a href="http://yaledailynews.com/blog/category/sports/" class="dropdown-toggle" data-toggle="dropdown">Sports <b class="caret"></b></a>
<ul class="dropdown-menu">
  <li id="menu-item-91939" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-91939"><a href="http://yaledailynews.com/blog/category/sports/spring-sports/baseball/">Baseball</a></li>
  <li id="menu-item-91947" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-91947"><a href="http://yaledailynews.com/blog/category/sports/winter-sports/basketball/">Basketball</a></li>
  <li id="menu-item-91940" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-91940"><a href="http://yaledailynews.com/blog/category/sports/spring-sports/crew/">Crew</a></li>
  <li id="menu-item-91934" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-91934"><a href="http://yaledailynews.com/blog/category/sports/fall-sports/cross-country/">Cross Country</a></li>
  <li id="menu-item-91948" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-91948"><a href="http://yaledailynews.com/blog/category/sports/winter-sports/fencing/">Fencing</a></li>
  <li id="menu-item-91935" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-91935"><a href="http://yaledailynews.com/blog/category/sports/fall-sports/field-hockey/">Field Hockey</a></li>
  <li id="menu-item-91936" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-91936"><a href="http://yaledailynews.com/blog/category/sports/fall-sports/football/">Football</a></li>
  <li id="menu-item-91941" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-91941"><a href="http://yaledailynews.com/blog/category/sports/spring-sports/golf/">Golf</a></li>
  <li id="menu-item-91949" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-91949"><a href="http://yaledailynews.com/blog/category/sports/winter-sports/gymnastics/">Gymnastics</a></li>
  <li id="menu-item-91950" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-91950"><a href="http://yaledailynews.com/blog/category/sports/winter-sports/ice-hockey/">Ice Hockey</a></li>
  <li id="menu-item-91942" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-91942"><a href="http://yaledailynews.com/blog/category/sports/spring-sports/lacrosse/">Lacrosse</a></li>
  <li id="menu-item-91943" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-91943"><a href="http://yaledailynews.com/blog/category/sports/spring-sports/sailing/">Sailing</a></li>
  <li id="menu-item-91937" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-91937"><a href="http://yaledailynews.com/blog/category/sports/fall-sports/soccer/">Soccer</a></li>
  <li id="menu-item-91944" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-91944"><a href="http://yaledailynews.com/blog/category/sports/spring-sports/softball/">Softball</a></li>
  <li id="menu-item-91951" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-91951"><a href="http://yaledailynews.com/blog/category/sports/winter-sports/squash/">Squash</a></li>
  <li id="menu-item-91952" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-91952"><a href="http://yaledailynews.com/blog/category/sports/winter-sports/swimming-diving/">Swimming &amp; Diving</a></li>
  <li id="menu-item-91945" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-91945"><a href="http://yaledailynews.com/blog/category/sports/spring-sports/tennis/">Tennis</a></li>
  <li id="menu-item-91946" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-91946"><a href="http://yaledailynews.com/blog/category/sports/spring-sports/track-field-multi-season-sports/">Track &amp; Field</a></li>
  <li id="menu-item-91938" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-91938"><a href="http://yaledailynews.com/blog/category/sports/fall-sports/volleyball/">Volleyball</a></li>
</ul>
</li>
<li id="menu-item-89399" class="menu-item menu-item-type-taxonomy menu-item-object-category dropdown menu-item-89399" style="margin-right: 6px;"><a href="http://yaledailynews.com/blog/category/opinion/" class="dropdown-toggle" data-toggle="dropdown">Opinion <b class="caret"></b></a>
<ul class="dropdown-menu">
  <li id="menu-item-89405" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-89405"><a href="http://yaledailynews.com/blog/category/opinion/the-news-views/">The News’ Views</a></li>
  <li id="menu-item-89404" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-89404"><a href="http://yaledailynews.com/blog/category/opinion/staff-columns/">Staff Columns</a></li>
  <li id="menu-item-89401" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-89401"><a href="http://yaledailynews.com/blog/category/opinion/guest-columns/">Guest Columns</a></li>
  <li id="menu-item-89402" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-89402"><a href="http://yaledailynews.com/blog/category/opinion/letters/">Letters</a></li>
  <li id="menu-item-89505" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-89505"><a href="http://yaledailynews.com/blog/category/opinion/comics/">Comics</a></li>
  <li id="menu-item-89506" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-89506"><a href="http://yaledailynews.com/blog/category/opinion/oped-live/">OpEd Live</a></li>
  <li id="menu-item-90596" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-90596"><a href="http://yaledailynews.com/about-us/submissions/">Submit</a></li>
</ul>
</li>
<li id="menu-item-89406" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-89406" style="margin-right: 6px;"><a href="/weekend">WEEKEND</a></li>
<li id="menu-item-89407" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-89407" style="margin-right: 6px;"><a href="/magazine">Magazine</a></li>
<li id="menu-item-92690" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-92690" style="margin-right: 6px;"><a href="/ytv">YTV</a></li>
<li id="menu-item-89408" class="menu-item menu-item-type-custom menu-item-object-custom dropdown menu-item-89408" style="margin-right: 6px;"><a href="/crosscampus" class="dropdown-toggle" data-toggle="dropdown">Blog <b class="caret"></b></a>
<ul class="dropdown-menu">
  <li id="menu-item-91060" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-91060"><a href="/crosscampus">Cross Campus</a></li>
  <li id="menu-item-91059" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-91059"><a href="http://yaledailynews.com/weekend/category/blog/">WKND Blog</a></li>
</ul>
</li>
<li id="menu-item-91058" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-91058" style="margin-right: 6px;"><a href="http://yaledailynews.com/events/">Events</a></li>
<li id="menu-item-91801" class="menu-item menu-item-type-post_type menu-item-object-page dropdown menu-item-91801" style="margin-right: 6px;"><a href="http://yaledailynews.com/advertising/" class="dropdown-toggle" data-toggle="dropdown">Advertise <b class="caret"></b></a>
<ul class="dropdown-menu">
  <li id="menu-item-91805" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-91805"><a href="http://yaledailynews.com/advertising/legal-information/">Legal Information</a></li>
  <li id="menu-item-91807" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-91807"><a href="http://yaledailynews.com/advertising/terms-conditions/">Terms &amp; Conditions</a></li>
</ul>
</li>
<li id="menu-item-89409" class="menu-item menu-item-type-post_type menu-item-object-page dropdown menu-item-89409"><a href="http://yaledailynews.com/about-us/" class="dropdown-toggle" data-toggle="dropdown">About Us <b class="caret"></b></a>
<ul class="dropdown-menu">
  <li id="menu-item-89410" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-89410"><a href="http://yaledailynews.com/about-us/a-day-at-the-news/">A Day at the News</a></li>
  <li id="menu-item-89411" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-89411"><a href="http://yaledailynews.com/about-us/about-the-website/">About the Website</a></li>
  <li id="menu-item-89412" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-89412"><a href="http://yaledailynews.com/about-us/submissions/">Submissions</a></li>
  <li id="menu-item-92401" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-92401"><a href="http://yaledailynews.com/about-us/sjp/">Summer Journalism Project 2013</a></li>
  <li id="menu-item-89413" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-89413"><a href="http://yaledailynews.com/about-us/yaledailynews-com-user-policy/">User Policy</a></li>
  <li id="menu-item-93301" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-93301"><a href="http://yaledailynews.com/rights-permissions/">Rights &amp; Permissions</a></li>
</ul>
</li>
</ul></div>
    <?php
  }
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
