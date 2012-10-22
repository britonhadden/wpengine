<?php
/**
 * Register the custom post type for magazine issues
 */

class YDN_Mag_Issue_Type {

  const type_slug = 'mag-issue';
  const num_elts_selected = 20; //number to pull into each drop down

  function __construct() {
    //bind actions
    add_action( 'init', array($this, 'register_post_type'));
    add_action( 'add_meta_boxes', array($this, 'register_meta_boxes') );
  }

  function register_post_type() {
      $labels = array(
          'name' => _x('Issues', 'ydn'),
          'singular_name' => _x('Issue', 'ydn'),
          'add_new' => _x('Add New', 'ydn'),
          'add_new_item' => __('Add New Issue'),
          'edit_item' => __('Edit Issue'),
          'new_item' => __('New Issue'),
          'all_items' => __('All Issues'),
          'view_item' => __('View Issue'),
          'search_items' => __('Search Issues'),
          'not_found' =>  __('No issues found'),
          'not_found_in_trash' => __('No issues found in Trash'), 
          'parent_item_colon' => '',
          'menu_name' => __('Issues')
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
            'supports' => array( 'title' )
      ); 
      register_post_type(YDN_Mag_Issue_Type::type_slug,$args);
      flush_rewrite_rules(); //magic sauce that makes permalinks work
  }

  function register_meta_boxes() {
    add_meta_box('top_content','Top Content', array($this, 'draw_meta_box'), YDN_Mag_Issue_Type::type_slug, 'normal', 'default', array(3));
  }

  function draw_meta_box($post,$args) {
    //used to draw all of the metaboxes on the admin page
    if (count($args['args']) != 1) {
      //this should never happen
      die('invalid number of arguments passed');
    }
    $num_elts = $args['args'][0];
    $content_type = $arg['id'];
    $story_list = $this->fetch_content_for($content_type);

    for($i = 0; $i < $num_elts; $i++) {
      $field_name = "ydn_issue_" . $content_type . "_" . $i;
      ?>
      <div>
        <label for="<?php echo $field_name; ?>">Element <?php echo $i + 1; ?>:</label> 
        <?php $this->create_dropdown($story_list, $field_name, 0); ?>
      </div>
      <?php
    }
    
  }

  private function fetch_content_for($content_id) {
    //gets a content_id and fetches the most recent 20 stories in the relevant categories
    $query_args = array( 'posts_per_page' => YDN_Mag_Issue_Type::num_elts_selected );

    switch ($content_id) {

    }
  }

  private function create_dropdown($story_list, $name, $post_id) {
    //renders a drop down, setting its value to $post_id unless nothing is passed
    echo "<select id=\"". $name . "name=\"". $name . "\">";
    print_r($story_list);
    echo "</select>";
  }

}

$ydn_mag_issue = new YDN_Mag_Issue_Type();

?>
