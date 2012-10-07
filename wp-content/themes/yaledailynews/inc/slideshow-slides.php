<?php
/**
 * Register the custom post type for slideshow panels -- this allows editors to create panels that link
 * to arbitrary urls 
 */
function ydn_register_slideshow_slide() {
    $labels = array(
          'name' => _x('Slideshow Slides', 'ydn'),
          'singular_name' => _x('Slideshow Slide', 'ydn'),
          'add_new' => _x('Add New', 'ydn'),
          'add_new_item' => __('Add New Slide'),
          'edit_item' => __('Edit Slide'),
          'new_item' => __('New Slide'),
          'all_items' => __('All Slides'),
          'view_item' => __('View Slide'),
          'search_items' => __('Search Slides'),
          'not_found' =>  __('No slides found'),
          'not_found_in_trash' => __('No slides found in Trash'), 
          'parent_item_colon' => '',
          'menu_name' => __('Slideshow Slides')
        );
      $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true, 
            'show_in_menu' => true, 
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'has_archive' => true, 
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array( 'title', 'thumbnail', 'excerpt', 'author'  )
      ); 
      register_post_type('slideshow-slide',$args);
     
}
add_action( 'init', 'ydn_register_slideshow_slide',1);//register early so we beat zoninator

//add in the custom field that tracks the URL
function ydn_register_slideshow_metadata() {
  x_add_metadata_group( 'ydn_slideshow_data', array( 'slideshow-slide'), array( 'label' => "Slide Options")  );
  x_add_metadata_field( 'ydn_slideshow_url', array( 'slideshow-slide' ), array( 'label' => "Slide target URL:", "group" => 'ydn_slideshow_data' ) );
  x_add_metadata_field( 'ydn_slideshow_cat', array( 'slideshow-slide' ), array( 'label' => "Slide category text:", "group" => 'ydn_slideshow_data') );
}

add_action('admin_menu', 'ydn_register_slideshow_metadata');

//allow the zoninator plugin to index slideshow-slide posts
function ydn_slideshow_zoninator() {
  add_post_type_support( 'slideshow-slide', 'zoninator_zones');
}
add_action('zoninator_pre_init','ydn_slideshow_zoninator'); 
?>
