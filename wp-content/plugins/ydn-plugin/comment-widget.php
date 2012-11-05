<?php
/*
Name: comment-widget.php
Description: This plugin creates a widget that displays the most recent comments AND their text
Author: Michael DiScala
*/

/* this is mostly copied exactly from WP_Widget_Recent_Comments.
 * Only override the widget property so that we can change the output
 * to include the text */
class YDN_Widget_Comments_With_Text extends  WP_Widget {

function __construct() {
    $widget_ops = array('classname' => 'ydn_widget_recent_comments', 'description' => __( 'The most recent comments AND their text' ) );
    parent::__construct('ydn-recent-comments', __('YDN Recent Comments'), $widget_ops);
    $this->alt_option_name = 'ydn_widget_recent_comments';

    if ( is_active_widget(false, false, $this->id_base) )
      add_action( 'wp_head', array(&$this, 'recent_comments_style') );

    add_action( 'comment_post', array(&$this, 'flush_widget_cache') );
    add_action( 'transition_comment_status', array(&$this, 'flush_widget_cache') );
  }

  function recent_comments_style() {
    if ( ! current_theme_supports( 'widgets' ) // Temp hack #14876
      || ! apply_filters( 'show_recent_comments_widget_style', true, $this->id_base ) )
      return;
    ?>
  <style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
<?php
  }

  function flush_widget_cache() {
    wp_cache_delete('widget_recent_comments', 'widget');
  }

  function widget( $args, $instance ) {
    global $comments, $comment;

    $cache = wp_cache_get('widget_recent_comments', 'widget');

    if ( ! is_array( $cache ) )
      $cache = array();

    if ( ! isset( $args['widget_id'] ) )
      $args['widget_id'] = $this->id;

    if ( isset( $cache[ $args['widget_id'] ] ) ) {
      echo $cache[ $args['widget_id'] ];
      return;
    }

    extract($args, EXTR_SKIP);
    $output = '';
    $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Recent Comments' ) : $instance['title'], $instance, $this->id_base );

    if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) )
      $number = 5;

    $comments = get_comments( apply_filters( 'widget_comments_args', array( 'number' => $number, 'status' => 'approve', 'post_status' => 'publish' ) ) );
    $output .= $before_widget;
    if ( $title )
      $output .= $before_title . $title . $after_title;

    $output .= '<ul id="recentcomments">';
    if ( $comments ) {
      foreach ( (array) $comments as $comment) {
        $comment_text = get_comment_text();
        $comment_text = wp_trim_words($comment_text,25,'&hellip;');
        $output .=  '<li class="recentcomments">' . '<span class="comment"><a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' . $comment_text . '</a></span>';
        $output .= sprintf(_x('%1$s on %2$s', 'widgets'), get_comment_author_link(), '<a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' . get_the_title($comment->comment_post_ID) . '</a>') . '</li>';
      }
    }
    $output .= '</ul>';
    $output .= $after_widget;

    echo $output;
    $cache[$args['widget_id']] = $output;
    wp_cache_set('widget_recent_comments', $cache, 'widget');
  }

  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['number'] = absint( $new_instance['number'] );
    $this->flush_widget_cache();

    $alloptions = wp_cache_get( 'alloptions', 'options' );
    if ( isset($alloptions['widget_recent_comments']) )
      delete_option('widget_recent_comments');

    return $instance;
  }

  function form( $instance ) {
    $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
    $number = isset($instance['number']) ? absint($instance['number']) : 5;
?>
    <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

    <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of comments to show:'); ?></label>
    <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
  }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "YDN_Widget_Comments_With_Text");') );

?>
