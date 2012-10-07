<?php
/**
 * ydnxc functions and definitions
 *
 * @package ydnxc
 * @since ydnxc 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since ydnxc 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'ydnxc_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since ydnxc 1.0
 */
function ydnxc_setup() {

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	//require( get_template_directory() . '/inc/tweaks.php' );

	/**
	 * Custom Theme Options
	 */
	//require( get_template_directory() . '/inc/theme-options/theme-options.php' );

	/**
	 * WordPress.com-specific functions and definitions
	 */
	//require( get_template_directory() . '/inc/wpcom.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on ydnxc, use a find and replace
	 * to change 'ydnxc' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'ydnxc', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
  add_image_size('home-entry-list',250,150,true);
  add_image_size('featured-story-list',150,150,true);
  add_image_size('entry-featured-image',660,999999); //max-width 630; height unconstrained

	register_nav_menus( array(
		'top' => __( 'Top Menu', 'ydnxc' ),
		'bottom' => __( 'Bottom Menu', 'ydnxc' ),
	) );

}
endif; // ydnxc_setup
add_action( 'after_setup_theme', 'ydnxc_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since ydnxc 1.0
 */
function ydnxc_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Home Sidebar Tabs', 'ydnxc' ),
		'id' => 'home-sidebar-tabs',
		'before_widget' => '<aside id="%1$s" class="tab widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
  ) );	
  
  register_sidebar( array(
		'name' => __( 'Content Sidebar Tabs', 'ydnxc' ),
		'id' => 'content-sidebar-tabs',
		'before_widget' => '<aside id="%1$s" class="tab widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

  register_sidebar( array(
		'name' => __( 'Sidebar Advertisement', 'ydnxc' ),
		'id' => 'sidebar-advertisement',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'ydnxc_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function ydnxc_scripts() {
	global $post;

	wp_enqueue_style( 'style', get_stylesheet_uri() );
  wp_enqueue_style( 'ydnxc_style', get_template_directory_uri() . '/cross_campus.css');

	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );
  wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array('jquery') );
  wp_enqueue_script( 'ydnxc', get_template_directory_uri() . '/js/cross_campus.js', array('jquery','bootstrap') );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image( $post->ID ) ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'ydnxc_scripts' );

/**
  *  * Register custom metadata fields in the admin interface
  *   */

function ydnxc_register_custom_metadata() {
    if( function_exists( 'x_add_metadata_field' ) && function_exists( 'x_add_metadata_group' ) ) {
          x_add_metadata_group( 'ydn_metadata', array('post'), array('label' => "YDN Metadata") );
          x_add_metadata_field( 'ydn_homepage_excerpt', array( 'post' ), array( 'label' => "Homepage Excerpt (~ 5 words)",
                                                                                 'group' => 'ydn_metadata' ) );
          x_add_metadata_field( "ydn_legacy_password", array('user'), array( 'label' => "YDN Legacy Password Hash" ) );
      }
}

add_action('admin_menu', 'ydnxc_register_custom_metadata');

function ydnxc_excerpt_read_more($more) {
         global $post;
          return ' <a href="'. get_permalink($post->ID) . '">&raquo;</a>';
}
add_filter('excerpt_more', 'ydnxc_excerpt_read_more');

/**
 * Implement the Custom Header feature
 */
//require( get_template_directory() . '/inc/custom-header.php' );
