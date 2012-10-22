<?php
/**
 * Register the custom post type for magazine issues
 */

class YDN_Mag_Issue_Type {

  const type_slug = 'mag-issue';
  const num_elts_selected = 20; //number to pull into each drop down
  const week_range = 2; //number of weeks before/after pub date to pull into drop downs

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
    add_meta_box('top_content','Top Content', array($this, 'draw_meta_box'), YDN_Mag_Issue_Type::type_slug, 'normal', 'default', array(4));
    add_meta_box('essays','Essays', array($this, 'draw_meta_box'), YDN_Mag_Issue_Type::type_slug, 'normal', 'default', array(3));
    add_meta_box('small_talk','Small Talk', array($this, 'draw_meta_box'), YDN_Mag_Issue_Type::type_slug, 'normal', 'default', array(3));
    add_meta_box('shorts','Shorts', array($this, 'draw_meta_box'), YDN_Mag_Issue_Type::type_slug, 'normal', 'default', array(4));
    add_meta_box('poetry','Poetry', array($this, 'draw_meta_box'), YDN_Mag_Issue_Type::type_slug, 'normal', 'default', array(4));
    add_meta_box('photo_essay','Photo Essay', array($this, 'draw_meta_box'), YDN_Mag_Issue_Type::type_slug, 'normal', 'default', array(1));
  }

  function draw_meta_box($post,$args) {
    //used to draw all of the metaboxes on the admin page
    $this->post = $post;
    if (count($args['args']) != 1) {
      //this should never happen
      die('invalid number of arguments passed');
    }
    $num_elts = $args['args'][0];
    $content_type = $arg['id'];
    if (!isset($this->story_list) ) {
      $this->fetch_content();
    }

    for($i = 0; $i < $num_elts; $i++) {
      $field_name = "ydn_issue_" . $content_type . "_" . $i;
      ?>
      <div>
        <label for="<?php echo $field_name; ?>">Element <?php echo $i + 1; ?>:</label> 
        <?php $this->create_dropdown($field_name, 0); ?>
      </div>
      <?php
    }
    
  }

  private function fetch_content() {
    //gets a content_id and fetches the stories within the week_range from pub date
    $query_args = array( 'posts_per_page' => YDN_Mag_Issue_Type::num_elts_selected );
    add_filter('posts_where', array($this, 'fetch_content_where_filter')); 
    $results = new WP_Query($query_args);
    remove_filter('posts_where', array($this, 'fetch_content_where_filter'));

    $this->story_list = $results->get_posts(); 
  }

  function fetch_content_where_filter($where = '') {
    //necessary to allow WP to select posts published +- week_range weeks from pub date
    $current_time = strtotime($this->post->post_date);
    $start_date = strtotime( '-' . YDN_Mag_Issue_Type::week_range . " weeks", $current_time);
    $end_date = strtotime( '+' . YDN_Mag_Issue_Type::week_range . " weeks", $current_time);
    $where .= " AND post_date >= '" . date('Y-m-d', $start_date) . "' AND post_date <= '" . date('Y-m-d',$end_date). "'";
  }


  private function create_dropdown($name, $post_id) {
    //renders a drop down, setting its value to $post_id unless nothing is passed
    echo "<select id=\"". $name . "name=\"". $name . "\">";
    foreach($this->story_list as $post) {
     if ($post->ID == $post_id) {
       $selected = " selected=\"selected\" ";
     } else {
       $selected = "";
     }
     echo "<option value=\"" . $post->ID . "\"" . $selected . ">" . $post->post_title . "</option>"; 
    }
    echo "</select>";
  }

}

$ydn_mag_issue = new YDN_Mag_Issue_Type();

?>
