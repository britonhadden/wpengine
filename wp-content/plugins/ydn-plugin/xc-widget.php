<?php
/* Name: xc-widget.php
 * Author: Michael DiScala
 * Implements the XC widget for sidebars
 */
class YDN_XC_Widget extends WP_Widget {
  /* This class defines the widget for rendering the rotating Cross-Campus widget that appears
   * on several subsites */

  public function __construct() {
    /* Register the widget w/ wp */
    parent::__construct(
      'ydn_xc_widget',
      'YDN XC Widget',
      array('description' => __('A widget for rendering the small XC widget.','ydn'))
    );

    /* Registers javascript for the plugin and sets up the actual load. If the widget is used, then the script
     * is rendered into the footer otherwise it's not included on the page */
    add_action('init',array($this, 'register_scripts'));
    add_action('wp_footer', array($this, 'print_scripts'));
    $this->is_used = false;
  }

  public function form($instance) {
    /* renders the options form in admin */
    ?>
    <p><label for="<?php echo $this->get_field_name; ?>">Category:</label>
    <?php
    switch_to_blog(YDN_XC_ID);
    $dropdown_args = array("id" => $this->get_field_id('cat'),
                           "name" => $this->get_field_name('cat') );
    if(isset($instance["cat"])) {
      $dropdown_args["selected"] = $instance["cat"];
    }
    wp_dropdown_categories($dropdown_args);
    restore_current_blog();
  }

  public function update($new_instance, $old_instance) {
    /* saves new settings to the database */
    $instance = array("cat" =>  (int) $new_instance["cat"] );
    return $instance;
  }

  public function widget($args, $instance) {
    /* renders the actual widget */

    /* do some setup */
    $this->is_used = true; /* triggers JS include */
    global $post;
    switch_to_blog(YDN_XC_ID); //necessary so we can query
    $temp_post = $post;

    /* fetch my content */
    $this->xc_posts = new WP_Query(array("cat" => $instance["cat"],
                                   "posts_per_page" => 5)
                            );

    /* if no content is returned, print nothing */
    if(!$this->xc_posts->have_posts()) { $this->is_used = false; return; }

    /* render the no-js version */
    echo $before_widget;
    ?>
    <div id="cross-campus" class="ydn-plugin widget no-js">
      <a id="cross-campus-widget-header" href="<?php echo get_bloginfo('url'); ?>">
<h2>CROSS</h2><h1>CAMPUS</h1>
</a>
      <div class="content-list borders">
        <?php
          $first_post = $this->xc_posts->get_posts();
          setup_postdata($first_post[0]);
          get_template_part('list','xc');
        ?>
      </div>
    </div> <!-- end #cross campus -->
    <?php
    echo $after_widget;

    /* clean up */
    restore_current_blog();
    $post = $temp_post;
  }

  public function register_scripts() {
    /* queue the javascript necessary for this specific plugin */
    wp_register_script('ydn-plugin-scripts', plugins_url('ydn-plugin.js', __FILE__), array('jquery'), '1.0',true);
  }

  public function print_scripts() {
    if($this->is_used) {
      $this->render_panels();
      wp_print_scripts('ydn-plugin-scripts');
    }
  }

  private function render_panels() {
    /* renders the story panels at in a script tag at the bottom of the page. if the browser supports JS,
     * then these panels will be inserted into the DOM and the user can use next/prev buttons to cycle through */
    global $post;
    $temp_post = $post;
    $this->xc_posts->rewind_posts();
    switch_to_blog(YDN_XC_ID);
    ?>
      <script id="ydn-xc-widget-posts" type="text/html">
        <div class="content-list">
          <?php
            while($this->xc_posts->have_posts()) {
              $this->xc_posts->the_post();
              get_template_part('list','xc');
            }
          ?>
        </div>
        <div class="controls clearfix">
          <a href="#" class="pull-left prev">&laquo; Prev</a>
          <a href="#" class="pull-right next">Next &raquo;</a>
        </div>
      </script>
    <?php

    $post = $temp_post;
    restore_current_blog();
  }
}
add_action('widgets_init', create_function('', 'register_widget("YDN_XC_Widget");'));
?>
