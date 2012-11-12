<?php
/*
Plugin Name: YDN Plugin
Plugin URI: http://yaledailynews.com
Description: A collection of YDN specific widgets and feature modifications.
Version: 1.0
Author: Michael DiScala
Author URI: http://yaledailynews.com
*/

//define global constants among our plugins here
define('YDN_XC_ID', 2);

//Legacy login enables backwards support for ellington encrypted passwords
include('legacy-login.php');

//XC widget defines the cross campus widget that runs in several sidebars
//throughout the site
include('xc-widget.php');

//A recent comment widget that matches the styling needed for the XC sidebar
include('comment-widget.php');

//A plugin to handle our legacy URLs and route them to their new end points
include('url-rewrites.php');
//install the plugin if it's a first activation
//has to be in the main file
register_activation_hook(__FILE__, array(YDN_URL_Rewrites::get_instance(), 'install'));

//A plugin to make sure users get registered on all of the subsites
include('propagate-users.php');

// A plugin to redirect wp-login to /login
include('redirect-login.php');

//tells co-authors plus to use a custom capability in its search for
//eligible bylines
add_filter('coauthors_edit_author_cap', function() { return "has_byline"; });
?>
