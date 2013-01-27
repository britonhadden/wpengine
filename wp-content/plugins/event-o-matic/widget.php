<?php 

//include
require_once('venue.php');
require_once('event.php');


/**
 * Event-O-Matic widget class
 *
 */
class eom_widget extends WP_Widget{


/**
 * @method constructor
 */
function eom_widget() {
	$widget_ops = array( 'classname' => 'eom_widget', 'description' => __( "List of upcoming events" ) );
	$this->WP_Widget('widget', __('Event-O-Matic'), $widget_ops);
}


/**
 * Create widget
 *
 */
function widget($args, $instance){
	extract($args);
	$event = new Event;
	$args=array(
		'when'=>'upcoming',
		'status'=>$event->statusCode['approved'],
		'order'=>'dateStart',
		'limit'=>$instance['limit']);
	//only get venue if selected
	if($instance['vid'] != 'x'){ $args['venue'] = $instance['vid'];}	
	$events = $event->getAll($args);
	if($events){
		echo $before_widget;
		if(!empty($instance['title'])){ echo $before_title.$instance['title'].$after_title; }
		echo '<ul>';
		$url = get_option('eom_events_url'); //get submitted event listings page URL
		foreach ($events as $event){
			if($url){
				echo '<li><a href="'.$url.'?id='.$event['id'].'&vid='.$event['venueId'].'">'.esc_html($event['name']).'</a> - '.date("F jS g:i a",strtotime($event['dateStart'])).'</a></li>';
			}
			else{echo '<li>'.esc_html($event['name']).' - '.date("F jS g:i a",strtotime($event['dateStart'])).'</li>'; }
		}
	echo '</ul>'.$after_widget;
	}
}


/**
 * Update widget admin form
 * 
 */
function update($new_instance, $old_instance){return $new_instance;}
 

/**
 * Create widget admin form
 *
 */
function form($instance) {
	$venue = new Venue;
	$venues = $venue->get_all(array('order'=>'name'));		
	echo '<div id="eomwidget-admin-panel">';
	echo '<label for="'. $this->get_field_id("title").'">'.__('Title:','event-o-matic').'</label>';
	echo '<input type="text" class="widefat" name="'.$this->get_field_name("title").'" ';
	echo 'id="'.$this->get_field_id("title").'" value="'.$instance["title"].'" />';
	echo '<label for="'.$this->get_field_id("limit").'">'.__('Number of Events:','event-o-matic').'</label>';
	echo '<input type="text" class="widefat" name="'.$this->get_field_name("limit").'" ';
	echo 'id="'.$this->get_field_id("limit").'" value="'.$instance["limit"].'" />';
	echo '<label for="'.$this->get_field_id("vid").'">'.__('Venue:','event-o-matic').'</label>';
	echo '<select id="'.$this->get_field_id("vid").'" name="'.$this->get_field_name("vid").'" ';
	echo 'class="widefat" style="width:100%;">  '; 
	echo '<option value="x">'.__('All Venues','event-o-matic').'</option>';
	foreach($venues as $v){
		$sel = '';
		if( $v->id == $instance['vid']){
			$sel = 'selected="selected"'; 
		}
		echo '<option value="'.$v->id.'" '.$sel.'>'.esc_html($v->name).'</option>';
	}
	echo '</select></div>';
}

	
}//END class


/**
 * add_action widgets_init
 *
 */
add_action('widgets_init', create_function('', 'return register_widget("eom_widget");'));
?>