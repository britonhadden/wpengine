<?php
/**
Plugin Name: nrelate Most Popular
Plugin URI: http://www.nrelate.com
Description: Easily display the most popular content from your website. Click on <a href="admin.php?page=nrelate-popular">nrelate &rarr; Most Popular</a> to configure your settings.
Author: <a href="http://www.nrelate.com">nrelate</a> and <a href="http://www.slipfire.com">SlipFire</a>
Version: 0.51.2
Author URI: http://nrelate.com/


// Copyright (c) 2010 nrelate, All rights reserved.
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// This is a plugin for WordPress
// http://wordpress.org/
//
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// **********************************************************************
**/

/**
 * Define Plugin constants
 */
define( 'NRELATE_POPULAR_PLUGIN_VERSION', '0.51.2' );
define( 'NRELATE_POPULAR_ADMIN_SETTINGS_PAGE', 'nrelate-popular' );
define( 'NRELATE_POPULAR_ADMIN_VERSION', '0.05.1' );
define( 'NRELATE_POPULAR_NAME' , __('Most Popular','nrelate'));
define( 'NRELATE_POPULAR_DESCRIPTION' , sprintf( __('Display the Most Popular Posts on your website.','nrelate')));

if(!defined('NRELATE_CSS_URL')) { define( 'NRELATE_CSS_URL', 'http://static.nrelate.com/common_wp/' . NRELATE_POPULAR_ADMIN_VERSION . '/' ); }
if(!defined('NRELATE_BLOG_ROOT')) { define( 'NRELATE_BLOG_ROOT', urlencode(str_replace(array('http://','https://'), '', get_bloginfo( 'url' )))); }
if(!defined('NRELATE_JS_DEBUG')) { define( 'NRELATE_JS_DEBUG', isset($_REQUEST['nrelate_debug']) ? true : false ); }

/**
 * Define Path constants
 */
// Generic: will be assigned to the first nrelate plugin that loads
if (!defined( 'NRELATE_PLUGIN_BASENAME')) { define( 'NRELATE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); }
if (!defined( 'NRELATE_PLUGIN_NAME')) { define( 'NRELATE_PLUGIN_NAME', trim( dirname( NRELATE_PLUGIN_BASENAME ), '/' ) ); }
if (!defined( 'NRELATE_PLUGIN_DIR')) { define( 'NRELATE_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . NRELATE_PLUGIN_NAME ); }
if (!defined('NRELATE_ADMIN_DIR')) { define( 'NRELATE_ADMIN_DIR', NRELATE_PLUGIN_DIR .'/admin'); }
if (!defined('NRELATE_ADMIN_URL')) { define( 'NRELATE_ADMIN_URL', WP_PLUGIN_URL . '/' . NRELATE_PLUGIN_NAME .'/admin'); }
if (!defined('NRELATE_API_URL')) { define ('NRELATE_API_URL', is_ssl() ? 'https://api.nrelate.com' : 'http://api.nrelate.com'); }
if (!defined('NRELATE_EXTENSIONS')) { define ('NRELATE_EXTENSIONS', NRELATE_ADMIN_DIR . '/extensions' ); }
	
// Plugin specific
define( 'NRELATE_POPULAR_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'NRELATE_POPULAR_PLUGIN_NAME', trim( dirname( NRELATE_POPULAR_PLUGIN_BASENAME ), '/' ) );
define( 'NRELATE_POPULAR_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . NRELATE_POPULAR_PLUGIN_NAME );
define( 'NRELATE_POPULAR_PLUGIN_URL', WP_PLUGIN_URL . '/' . NRELATE_POPULAR_PLUGIN_NAME );
define( 'NRELATE_POPULAR_SETTINGS_DIR', NRELATE_POPULAR_PLUGIN_DIR . '/popular_settings' );
define( 'NRELATE_POPULAR_SETTINGS_URL', NRELATE_POPULAR_PLUGIN_URL . '/popular_settings' );
define( 'NRELATE_POPULAR_ADMIN_DIR', NRELATE_POPULAR_PLUGIN_DIR . '/admin' );
define( 'NRELATE_POPULAR_IMAGE_DIR', NRELATE_POPULAR_PLUGIN_URL . '/images' );

// Load WP_Http
if( !class_exists( 'WP_Http' ) )
	include_once( ABSPATH . WPINC. '/class-http.php' );
	
// Load Language
load_plugin_textdomain('nrelate-popular', false, NRELATE_POPULAR_PLUGIN_DIR . '/language');

/**
 * Get the product status of all nrelate products.
 *
 * @since 0.49.0
 */
if ( !defined( 'NRELATE_PRODUCT_STATUS' ) ) { require_once ( NRELATE_POPULAR_ADMIN_DIR . '/product-status.php' ); }

/**
 * Load plugin styles if another nrelate plugin has not loaded it yet.
 *
 * @since 0.46.0
 */
if (!isset($nrelate_thumbnail_styles)) { require_once ( NRELATE_POPULAR_ADMIN_DIR . '/styles.php' ); }

/**
 * Check related version to make sure it is compatible with MP
 */
$related_settings = get_option('nrelate_related_options');
$related_version = $related_settings['related_version'];
if($related_version!='' &&version_compare("0.47.4", $related_version)>0){
	$plugin = NRELATE_POPULAR_PLUGIN_BASENAME;
	$warning = "<p><strong>".__('nrelate Warning(s):', 'nrelate')."</strong></p>";
	$message .= "<li>".sprintf(__('You\'re running Related Content plugin version %1$s. The Most Popular plugin requires Related Content version to be 0.47.4 or higher.<br/>Please upgrade to the latest release of Related Content plugin before installing the Most Popular plugin.', 'nrelate' ), $related_version ) . "</li>";
	$closing = "<p>".__('The nrelate Most Popular plugin has been deactivated.','nrelate')."<br/><br/><a href=\"/wp-admin\">".__('Click here to return to your WordPress dashboard.','nrelate')."</a></p>";
	deactivate_plugins($plugin);
	wp_die( $warning . "<ol>" . $message . "<ol>" . $closing );
	return;
}

/**
 * Initializes the plugin and it's features.
 *
 * @since 0.1
 */
if (is_admin()) {

		//load common admin files if not already loaded from another nrelate plugin
		if ( !defined('NRELATE_COMMON_LOADED') ) {require_once ( NRELATE_POPULAR_ADMIN_DIR . '/common.php' );}
		if ( ! defined( 'NRELATE_COMMON_50_LOADED' ) ) { require_once ( NRELATE_POPULAR_ADMIN_DIR . '/common-50.php' ); }

		//load plugin status
		require_once ( NRELATE_POPULAR_SETTINGS_DIR . '/popular-plugin-status.php' );
		
		//load popular menu
		require_once ( NRELATE_POPULAR_SETTINGS_DIR . '/popular-menu.php' );
		
		// Load Tooltips
		if (!isset($nrelate_tooltips)) { require_once ( NRELATE_POPULAR_ADMIN_DIR . '/tooltips.php' ); }
		
		// temporary file for 0.50.0 upgrades
		require_once ( 'nrelate-abstraction.php' );
}
/** Load common frontend functions **/
if ( ! defined( 'NRELATE_COMMON_FRONTEND_LOADED' ) ) { require_once ( NRELATE_POPULAR_ADMIN_DIR . '/common-frontend.php' );}
if ( ! defined( 'NRELATE_COMMON_FRONTEND_50_LOADED' ) ) { require_once ( NRELATE_POPULAR_ADMIN_DIR . '/common-frontend-50.php' ); }

// temporary file for 0.50.0 upgrades
require_once ( 'nrelate-abstraction-frontend.php' );

/*
 * Load popular styles
 *
 * since v.44.0
 * updated v46.0
 */
function nrelate_popular_styles() {
	if ( nrelate_popular_is_loading() ) {

		global $nrelate_thumbnail_styles, $nrelate_thumbnail_styles_separate, $nrelate_text_styles, $nrelate_text_styles_separate, $mp_styleclass, $mp_layout;
		$options = get_option('nrelate_popular_options');
		$style_options = get_option('nrelate_popular_options_styles');
		$ad_options = get_option('nrelate_popular_options_ads');

		// Are we loading separate ads?
		if ($ad_options['popular_ad_placement']=='Separate') {
			$style_suffix = '_separate';
		} else {
			$style_suffix = '';
		}

		// Thumbnails or Text?
		if ($options['popular_thumbnail']=='Thumbnails') {
			$style_type = 'popular_thumbnails_style' . $style_suffix;
			$style_array = 'nrelate_thumbnail_styles' . $style_suffix;
		} else {
			$style_type = 'popular_text_style' . $style_suffix;
			$style_array = 'nrelate_text_styles' . $style_suffix;
		}
		
		// Get style name (i.e. Default)
		$style_name = $style_options [$style_type];
				
		// Get the style sheet and class from STYLES.PHP
		$style_array_convert = ${$style_array};
		$stylesheet = $style_array_convert[$style_name]['stylesheet'] ? $style_array_convert[$style_name]['stylesheet'] : "nrelate-panels-default";
		$mp_styleclass = $style_array_convert[$style_name]['styleclass'];
		$mp_layout = $style_array_convert[$style_name]['layout'];


		// Get full stylesheet url
		$nr_css_url = NRELATE_CSS_URL . $stylesheet . '.min.css';
		
		/* For local development */
		//$nr_css_url = NRELATE_POPULAR_PLUGIN_URL . '/' . $stylesheet . '.css';
		
		// Only load if style not set to NONE
		if ('none'!=$style_options[$style_type]) {
			nrelate_ie6_thumbnail_style();		
			wp_register_style('nrelate-style-'. $style_name . "-" . str_replace(".","-",NRELATE_POPULAR_ADMIN_VERSION), $nr_css_url, false, null );
			wp_enqueue_style( 'nrelate-style-'. $style_name . "-" . str_replace(".","-",NRELATE_POPULAR_ADMIN_VERSION) );
		}
	}
}
add_action('wp_enqueue_scripts', 'nrelate_popular_styles');

/*
 * Check if nrelate is loading (frontend only)
 *
 * 
 */
function nrelate_popular_is_loading() {
	$is_loading = false;
   
    if ( !is_admin() ) {   
        $options = get_option('nrelate_popular_options');
       
        if ( isset($options['popular_where_to_show']) ) {
            foreach ( (array)$options['popular_where_to_show'] as $cond_tag ) {
                if ( function_exists( $cond_tag ) && call_user_func( $cond_tag ) ) {
                    $is_loading = true;
                    break;
                }
            }
        }
    }
   
    return apply_filters( 'nrelate_popular_is_loading', $is_loading);
}

/**
 * Inject popular posts into the content
 *
 * Stops injection into themes that use get_the_excerpt in their meta description
 *
 * @since 0.1
 */
function nrelate_popular_inject($content) {

	global $post;
	
	if ( nrelate_should_inject('popular') ) {

		$nrelate_popular_options = get_option( 'nrelate_popular_options' );

		$popular_loc_top = $nrelate_popular_options['popular_loc_top'];
		$popular_loc_bottom = $nrelate_popular_options['popular_loc_bottom'];
		$popular_where = $nrelate_popular_options['popular_where_to_show'];

		if ($popular_loc_top == "on"){
			$content_top = nrelate_popular(true);
		} else {
			$content_top = '';
		};

		if ($popular_loc_bottom == "on"){
			$content_bottom = nrelate_popular(true);
		} else {
			$content_bottom = '';
		};
		$increment_str = '';
		if(is_single() && (!nrelate_popular_is_loading() || ( $popular_loc_top!='on' && $popular_loc_bottom !='on') ) )
			$increment_str=nrelate_popular_counter();
		
		$original = $content;

		$content  = $content_top;
		$content .= $original;
		$content .= $content_bottom;
		$content .= $increment_str;

	}
	return $content;
}
add_filter( 'the_content', 'nrelate_popular_inject', 10 );
add_filter( 'the_excerpt', 'nrelate_popular_inject', 10 );

// Added 06/09/11 YK: if nrelate_popular is not shown and is single page, increment counter
function nrelate_popular_counter(){
	global $wp_query;
	$post_link = get_permalink($wp_query->post->ID);
	$post_urlencoded = urlencode($post_link);
	$nr_mp_url="http://api.nrelate.com/mpw_wp/".NRELATE_POPULAR_PLUGIN_VERSION."/loadcounter.php?tag=nrelate_popular";
	$nr_mp_url.="&domain=".NRELATE_BLOG_ROOT."&url=".$post_urlencoded;
	$increment_str = <<<EOD
<script type="text/javascript">
/* <![CDATA[ */
		var entity_decoded_nr_mp_url = jQuery('<span/>').html("$nr_mp_url").text();
		nRelate.getNrelatePosts(entity_decoded_nr_mp_url);
/* ]]> */
</script>
EOD;
	return $increment_str;
}

/**
 * nrelate popular shortcode
 *
 * @since 0.1
 */
function nrelate_popular_shortcode ($atts) {
	extract(shortcode_atts(array(
		"float" => 'left',
		"width" => '100%',
	), $atts));

    return '<div class="nr-mp-shortcode" style="float:'.$float.';width:'.$width.';\">'.nrelate_popular(true).'</div>';
}
add_shortcode('nrelate-popular', 'nrelate_popular_shortcode');

/**
 * Register the widget.
 *
 * @uses register_widget() Registers individual widgets.
 * @link http://codex.wordpress.org/WordPress_Widgets_Api
 *
 * @written in 0.1
 * @live in 0.41.0
 */
function nrelate_popular_load_widget() {

	//Load widget file.
	require_once( 'popular-widget.php' );

	// Register widget.
	register_widget( 'nrelate_Widget_Popular' );
};
add_action( 'widgets_init', 'nrelate_popular_load_widget' );



/**
 * Primary function
 *
 * Gets options and passes to nrelate via Javascript
 * 
 * @since 0.1
 */

$nr_mp_counter=0;
function nrelate_popular($opt=false) {
	global $post, $nr_mp_counter, $mp_styleclass, $mp_layout;
	
	$animation_fix = $nr_mp_nonjsbody = $nr_mp_nonjsfix = $nr_mp_js_str = '';
	
	if (nrelate_popular_is_loading())  {
		$nr_mp_counter++;
		
		// Assign options
		$nrelate_popular_options = get_option( 'nrelate_popular_options' );
		$mp_style_options = get_option('nrelate_popular_options_styles');
		$mp_style_code = 'nrelate_' . ($mp_styleclass ? $mp_styleclass : "default");
		$mp_layout_code = 'nr_' . ($mp_layout ? $mp_layout : "1col");
		$mp_width_class = 'nr_' . (($nrelate_popular_options['popular_thumbnail']=='Thumbnails') ? $nrelate_popular_options['popular_thumbnail_size'] : "text");
		$p_max_age = $nrelate_popular_options['popular_max_age_num'];
		$p_max_frame = $nrelate_popular_options['popular_max_age_frame'];
		switch ($p_max_frame){
		case 'Hour(s)':
		  $maxageposts = $p_max_age * 60;
		  break;
		case 'Day(s)':
		  $maxageposts = $p_max_age * 1440;
		  break;
		case 'Week(s)':
		  $maxageposts = $p_max_age * 10080;
		  break;
		case 'Month(s)':
		  $maxageposts = $p_max_age * 44640;
		  break;
		case 'Year(s)':
		  $maxageposts = $p_max_age * 525600;
		  break;
		}
		$nr_mp_counter+=1;
		
		// Get the page title and url array
		$nrelate_title_url = nrelate_title_url();
		
		$nonjs=$nrelate_popular_options['popular_nonjs'];
		
		$nr_url = "http://api.nrelate.com/mpw_wp/" . NRELATE_POPULAR_PLUGIN_VERSION . "/?tag=nrelate_popular";
		$nr_url .= "&domain=" . NRELATE_BLOG_ROOT . "&url=$nrelate_title_url[post_urlencoded]&nr_div_number=".$nr_mp_counter."&maxageposts=".$maxageposts;
		$nr_url .= is_home() ? '&source=hp' : '';
		$nr_url .= is_single() ? '&increment=1' : '&increment=0';
		
		$nr_url = apply_filters('nrelate_api_url', $nr_url, $post->ID);
		
		//is loaded only once per page for popular
		if (!defined('NRELATE_POPULAR_HOME')) {
			define('NRELATE_POPULAR_HOME', true);
			// Added to create disjoint between keeping count and displaying
			// If this page is single, $increment=1
			// If this page is single, send increment as 1. Call this only once
			
			$nrelate_popular_options_ads = get_option('nrelate_popular_options_ads');
			$animation_fix = '<style type="text/css">.nrelate_popular .nr_sponsored{ left:0px !important; }</style>';
			if (!empty($nrelate_popular_options_ads['popular_ad_animation'])) {
				$animation_fix = '';
			}
		}
		//is loaded only once per page for nrelate
		if (!defined('NRELATE_HOME')) {
			define('NRELATE_HOME', true);
			$domain = addslashes(NRELATE_BLOG_ROOT);
			$nr_domain_init = "nRelate.domain = \"{$domain}\";";
		} else {
			$nr_domain_init = '';
		}
		
		if($nonjs){
		    $args=array("timeout"=>2);
			$response = wp_remote_get( $nr_url."&nonjs=1",$args);


		    if( !is_wp_error( $response ) ){
			    if($response['response']['code']==200 && $response['response']['message']=='OK'){
				    $nr_mp_nonjsbody=$response['body'];
			   		$nr_mp_nonjsfix='<script type="text/javascript">'.$nr_domain_init.'nRelate.fixHeight("nrelate_popular_'.$nr_mp_counter.'");';
			   		$nr_mp_nonjsfix.='nRelate.adAnimation("nrelate_popular_'.$nr_mp_counter.'");';
					$nr_mp_nonjsfix.='nRelate.tracking("mp");</script>';
			    }else{
			    	$nr_mp_nonjsbody="<!-- nrelate server not 200. -->";
			    }
		    }else{
		    	$nr_mp_nonjsbody="<!-- WP-request to nrelate server failed. -->";
		    }
		}
		else{
			$nr_mp_js_str= <<<EOD
<script type="text/javascript">
	/* <![CDATA[ */
		$nr_domain_init
		var entity_decoded_nr_mp_url = jQuery('<span/>').html("$nr_url").text();
		nRelate.getNrelatePosts(entity_decoded_nr_mp_url);
	/* ]]> */
	</script>
EOD;
		}
		$markup = <<<EOD
$animation_fix
<div class="nr_clear"></div>	
	<div id="nrelate_popular_{$nr_mp_counter}" class="nrelate nrelate_popular $mp_style_code $mp_layout_code $mp_width_class">$nr_mp_nonjsbody</div>
	<!--[if IE 6]>
		<script type="text/javascript">jQuery('.$mp_style_code').removeClass('$mp_style_code');</script>
	<![endif]-->
	$nr_mp_nonjsfix
	$nr_mp_js_str
<div class="nr_clear"></div>
EOD;

		if ($opt){
			return $markup;
		}else{
			echo $markup;
		}
	}
};


//Activation and Deactivation functions
//Since 0.47.4, added uninstall hook again
register_activation_hook(__FILE__, 'nr_mp_add_defaults');
register_deactivation_hook(__FILE__, 'nr_mp_deactivate');
register_uninstall_hook(__FILE__, 'nr_mp_uninstall');
?>
