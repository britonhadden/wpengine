<?php
/**
 * Register the custom post type for videos
 */

function ydn_register_video_type() {
      $labels = array(
          'name' => _x('Videos', 'ydn'),
          'singular_name' => _x('Video', 'ydn'),
          'add_new' => _x('Add New', 'ydn'),
          'add_new_item' => __('Add New Video'),
          'edit_item' => __('Edit Video'),
          'new_item' => __('New Video'),
          'all_items' => __('All Videos'),
          'view_item' => __('View Video'),
          'search_items' => __('Search Videos'),
          'not_found' =>  __('No videos found'),
          'not_found_in_trash' => __('No videos found in Trash'), 
          'parent_item_colon' => '',
          'menu_name' => __('Videos')
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
            'taxonomies' => array( 'category' ),
            'supports' => array( 'title', 'thumbnail', 'editor', 'author', 'comments'  )
      ); 
      register_post_type('video',$args);
      flush_rewrite_rules(); //magic sauce that makes permalinks work
     
}
add_action( 'init', 'ydn_register_video_type');


?>
