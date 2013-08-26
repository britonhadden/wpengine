<?php
/**
 * ydn functions and definitions
 *
 * @package ydn
 * @since ydn 1.0
 */

/**
 * Start by defining some constants that get used throughout the theme
 */

define("WKND_BLOG_ID",3);
define("XC_BLOG_ID",2);
define("YDN_MAIN_SITE_ID",1);

// fix custom post types (video)
add_filter('pre_get_posts', 'query_post_type');
function query_post_type($query) {
  if(is_category() || is_tag()) {
    $post_type = get_query_var('post_type');
	if($post_type)
	    $post_type = $post_type;
	else
	    $post_type = array('post','video'); // replace cpt to your custom post type
    $query->set('post_type',$post_type);
	return $query;
    }
}


/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since ydn 1.0
 */
if ( ! isset( $content_width ) )
  $content_width = 640; /* pixels */

if ( ! function_exists( 'ydn_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since ydn 1.0
 */
function ydn_setup() {

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
   * If you're building a theme based on ydn, use a find and replace
   * to change 'ydn' to the name of your theme in all the template files
   */
  load_theme_textdomain( 'ydn', get_template_directory() . '/languages' );

  /**
   * Add default posts and comments RSS feed links to head
   */
  add_theme_support( 'automatic-feed-links' );

  /** Add menu support */
  add_theme_support( 'menus' );

  register_nav_menus( array(
    'primary' => __( 'Primary Menu', 'ydn'),
    'multimedia' => __( 'Multimedia Menu', 'ydn')
    ));



  /**
   * Enable support for Post Thumbnails
   */
  add_theme_support( 'post-thumbnails' );
  add_image_size('entry-featured-image',630,9999999); /* crop the image so that it's 630px wide, don't care about height */
  add_image_size('modal-photo', 9999999, 675);
  add_image_size('home-carousel',470,350,true);
  add_image_size('home-print-section',230,176,true);
  add_image_size('home-print-section-narrow',230,99999999);
  add_image_size('opinion-featured',550,425,true);
  add_image_size('video-thumbnail',150,100,true);
  add_image_size('featured-cat-img',532,99999);
  /**
   * Add support for the Aside Post Formats
   */
  add_theme_support( 'post-formats', array( 'aside', ) );


}
endif; // ydn_setup
add_action( 'after_setup_theme', 'ydn_setup' );
/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since ydn 1.0
 */
if (!function_exists('ydn_widgets_init')):
  function ydn_widgets_init() {
    register_sidebar( array(
      'name' => __( 'Sidebar', 'ydn' ),
      'id' => 'sidebar-1',
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget' => "</aside>",
      'before_title' => '<h1 class="widget-title">',
      'after_title' => '</h1>',
    ) );

    register_sidebar( array(
      'name' => __( 'Home Leaderboard', 'ydn' ),
      'id' => 'home_leaderboard',
      'class' => 'sidebar-widgets',
      'before_widget' => '<div id="leaderboard">',
      'after_widget' => '</div>',
      'before_title' => '',
      'after_title' => ''
    ) );

    register_sidebar( array(
      'name' => __( 'Leaderboard', 'ydn' ),
      'id' => 'leaderboard',
      'class' => 'sidebar-widgets',
      'before_widget' => '<div id="leaderboard">',
      'after_widget' => '</div>',
      'before_title' => '',
      'after_title' => ''
    ) );

    register_sidebar( array(
      'name' => __( 'Homepage Advertisements', 'ydn' ),
      'id' => 'home-advertisements',
      'class' => 'sidebar-widgets',
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget' => "</aside>",
      'before_title' => '<h1 class="widget-title">',
      'after_title' => '</h1>',
    ) );

    register_sidebar( array(
      'name' => __( 'Opinion Sidebar', 'ydn' ),
      'id' => 'opinion-sidebar',
      'class' => 'sidebar-widgets',
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget' => "</aside>",
      'before_title' => '<h1 class="widget-title">',
      'after_title' => '</h1>',
    ) );
  }
endif; //function_exists
add_action( 'widgets_init', 'ydn_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function ydn_scripts() {
  global $post;

  wp_enqueue_style( 'bootstrap-ydn', get_template_directory_uri() . '/css/ydn.css');

	if ( is_page_template('multimedia.php') ) {
    remove_filter( 'setup_theme', 'wpautop' );
		wp_enqueue_style( 'multimedia', get_template_directory_uri() . '/css/multimedia.css');
    wp_enqueue_script( 'spin', get_template_directory_uri() . '/js/spin.js');
	}

  wp_enqueue_script( 'jquery', get_template_directory_uri() . '/bootstrap/docs/assets/js/jquery.js');
  wp_enqueue_script( 'infinite-scroll', get_template_directory_uri() . '/js/jquery.infinitescroll.js', array('jquery'));
  wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/js/bootstrap.js', array('jquery') );
  wp_enqueue_script( 'throttle-debounce', get_template_directory_uri() . '/js/jquery.ba-throttle-debounce.min.js', array('jquery') );
  wp_enqueue_script( 'fancybox', get_template_directory_uri() . '/js/jquery.fancybox.pack.js', array('jquery'));
  wp_enqueue_script( 'ydn', get_template_directory_uri() . '/js/ydn.js', array('jquery','bootstrap-js','throttle-debounce', 'fancybox') );

  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
  }

  if ( is_singular() && wp_attachment_is_image( $post->ID ) ) {
    wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
  }

}
add_action( 'wp_enqueue_scripts', 'ydn_scripts' );

/**
 * Register custom metadata fields in the admin interface
 */

function ydn_register_custom_metadata() {
  if( function_exists( 'x_add_metadata_field' ) && function_exists( 'x_add_metadata_group' ) ) {
    //story level meta-data
    x_add_metadata_group( 'ydn_metadata', array('post'), array('label' => "YDN Metadata") );
    x_add_metadata_field( 'ydn_reporter_type', array( 'post' ), array( 'label' => "Reporter type (e.g. Staff Reporter)",
                                                                   'group' => 'ydn_metadata' ) );
    x_add_metadata_field( 'ydn_opinion_column', array( 'post'), array ( 'label' => "Subheadline/Opinion column title (for staff columnists)",
                                                                       'group' => 'ydn_metadata') );

    //user level meta
    x_add_metadata_group('ydn_user_metadata', array('user'), array('label' => "YDN Metadata"));
    x_add_metadata_field("ydn_legacy_password", array('user'), array( 'label' => "YDN Legacy Password Hash", "group" => "ydn_user_metadata"));

  }

}
add_action('admin_menu', 'ydn_register_custom_metadata');

/* A fomat function that defaults to standard */
function ydn_get_post_format() {
  global $post;
  $format = get_post_format();
  return ($format == false) ? "standard" : $format;
}

/**
 * Register custom metadata for our attachments.  Unforunately, the custom metadata manager doesn't work with
 * attachments and we have to do these by hand
 *
 * The flags we register here allow images to be marked for the home page as WEEKEND cover/Front Page/Magazine Cover
 */

/**
 * Render a select box on the attachment page that allows users to mark an image
 * as a special type
 */

//start building the html we need to render the select box
//build a simple function to render options on the attachment page
function ydn_build_option($name,$value,$current_value) {
   $o = '<option value="' . $value . '"';
   if ($value == $current_value) {
     $o .= 'selected="selected"';
   }
   $o .= '>' . $name . '</option>';

   return $o;
}

function ydn_attachment_fields_to_edit($form_fields, $post) {
  // $form_fields is a special array of fields to include in the attachment form
  // $post is the attachemnt record in the database $post->post_type == 'attachment'
  // (attachments are treated as posts in the wordpress database)

  //grab what the meta is set to currently so that we can choose the appropriate default for the select box
  $current_value = get_post_meta($post->ID, "_ydn_attachment_special_type", true);



  $field_name = 'attachments[' . $post->ID . '][_ydn_attachment_special_type]';

  $html = '<select name="' . $field_name  . '" id="' . $field_name . '">';
  $html .= ydn_build_option("None","",$current_value);
  $html .= ydn_build_option("Front Page","front_page",$current_value);
  $html .= ydn_build_option("WEEKEND Cover","weekend_cover",$current_value);
  $html .= ydn_build_option("Magazine Cover","magazine_cover",$current_value);
  $html .= ydn_build_option("Featured Opinion Image","opinion_featured_image",$current_value);
  $html .= '</select>';
  //add our custom field to $form fields
  $form_fields["ydn_attachment_special_type"] = array(
      "label" => __("Special Type?"),
      "input" => "html",
      "html" => $html,
  );

  return $form_fields;

}
add_filter("attachment_fields_to_edit", "ydn_attachment_fields_to_edit", 100, 2);



/**
 * A handler to save the attachment fields when the edit form
 * is submitted. */
function ydn_attachment_fields_to_save($post, $attachment) {
  // $attachment part of the form $_POST ($_POST[attachments][postID])
  // $post attachments wp post array -- will be saved after returned
  if ( isset($attachment["_ydn_attachment_special_type"]) ) {
    update_post_meta($post['ID'], '_ydn_attachment_special_type', $attachment["_ydn_attachment_special_type"]);
  }

  return $post;
}
add_filter("attachment_fields_to_save", "ydn_attachment_fields_to_save", null, 2);

/**
 * Add our >> read more button to the end of excerpts
 */
if (! function_exists("ydn_excerpt_read_more") ):
  function ydn_excerpt_read_more($more) {
           global $post;
           return ' <a href="'. get_permalink($post->ID) . '">&raquo;</a>';
  }
  add_filter('excerpt_more', 'ydn_excerpt_read_more');
endif;

/**
 * A function that brings an unknown sized list to the given length by removing extra elements
 * or by fetching additional stories from the given category.
 *
 * $list --> the list of stories to trim/top off
 * $category --> the slug of the category to pull
 * $size --> the desired number of elements in the output
 *
 * returns: the final list of size $size
 */
function ydn_fix_list_size($list, $category, $size, $post_type = 'post') {
  //if the $list already has enough elements, return the first $size elements
  if ($size <= count($list)) {
    return array_slice($list,0,$size);
  }

  //if we're not given a category, there's nothing to do
  if (empty($category)) {
    return $list;
  }

  $category = get_category_by_slug($category);
  //if the category query doesn't return anything, there's nothing to do
  if (empty($category)) {
    return $list;
  }

  //Need the post IDs to avoid repeating elements from the $list
  $excluded_ids = Array();
  foreach($list as $obj) {
    $excluded_ids[] = $obj->ID;
  }

  $query_params = array('posts_per_page' => $size - count($excluded_ids),
                        'cat' => $category->term_id,
                        'post__not_in' => $excluded_ids,
                        'post_type' => $post_type);
  $query = new WP_Query($query_params);
  return array_merge($list, $query->posts);
}

/**
 * Function triggered on activation of the theme to take care of some settings
 */
function ydn_theme_activation() {
  flush_rewrite_rules();
}
add_action('after_switch_theme', 'ydn_theme_activation');

/* For Media-Credit update post WP3.5 */
add_shortcode('caption', 'img_caption_shortcode_mediacredit');
function img_caption_shortcode_mediacredit($attr, $content = null) {
    // New-style shortcode with the caption inside the shortcode with the link and image tags.
    if ( ! isset( $attr['caption'] ) ) {
        if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
            $content = $matches[1];
            $attr['caption'] = trim( $matches[2] );
        }
    }

    // Allow plugins/themes to override the default caption template.
    $output = apply_filters('img_caption_shortcode', '', $attr, $content);
    if ( $output != '' )
        return $output;

    extract(shortcode_atts(array(
        'id'    => '',
        'align' => 'alignnone',
        'width' => '',
        'caption' => ''
    ), $attr));

    if ( 1 > (int) $width || empty($caption) )
        return $content;

    if ( $id ) $id = 'id="' . esc_attr($id) . '" ';

    return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" style="width: ' . (10 + (int) $width) . 'px">'
    . do_shortcode( $content ) . '<p class="wp-caption-text">' . do_shortcode( $caption ) . '</p></div>'; 
}

add_action('rss2_item', 'add_image_rss_node');
// Add featured images to the rss feed
function add_image_rss_node() {
  return 0;
}

/**
 * Include other funciton files
 */
require( get_template_directory() . '/inc/bootstrap-menu-walker.php' );
require( get_template_directory() . '/inc/slideshow-slides.php');
require( get_template_directory() . '/inc/ydn-homepage-content.php');
require( get_template_directory() . '/inc/category-functions.php');
require( get_template_directory() . '/inc/video-post-type.php');
