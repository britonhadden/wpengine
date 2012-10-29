<?php
/**
 * nrelate Popular Widget
 *
 * @package nrelate
 * @subpackage Widget
 */


 // Let's build a widget
class nrelate_Widget_Popular extends WP_Widget {

	function nrelate_Widget_Popular() {
		$widget_ops = array( 'classname' => 'nrelate-popular-widget', 'description' => __('Show Most Popular Content.', 'nrelate') );
		$control_ops = array( 'width' => 230, 'height' => 350, 'id_base' => 'nrelate-popular' );
		$this->WP_Widget( 'nrelate-popular', __('nrelate Most Popular Content', 'nrelate'), $widget_ops, $control_ops );
	}
	

	function widget( $args, $instance ) {
		extract( $args );
		
		echo "\n\t\t\t" . $before_widget;
		
		//Load the main function
		echo nrelate_popular(true);
		
		echo "\n\t\t\t" . $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['style'] = $new_instance['style'];
		
		return $instance;
	}

	function form( $instance ) {

		//Defaults
		$defaults = array( 'title' => __('Popular Posts:', 'nrelate') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<div style="float:left;width:98%;"></div>
		<p>
		<a href="admin.php?page=<?php echo NRELATE_POPULAR_ADMIN_SETTINGS_PAGE?>"><?php _e( 'Adjust your settings here >','nrelate')?></a>
		</p>
		<div style="float:left;width:48%;">
				
		</div>
		<div style="clear:both;">&nbsp;</div>
	<?php
	}
}

?>