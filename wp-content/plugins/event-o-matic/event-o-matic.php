<?php
/**
Plugin Name: Event-O-Matic
Plugin URI: http://garvinmedia.com/eom
Description: The Awesome Event Submission and Management Engine. Manage submissions to build a community-driven event listing. Allow users to submit events that you can easily review and moderate. Add lists of upcoming and past events and venues around your community. Integrated with Google Maps for simple address verification. 
Version: 4.7.1
Author: Matthew Garvin
Author URI: http://garvinmedia.com


THANK YOU
-Character Count copyright 2007 Tom Deater (http://www.tomdeater.com)
-Validator copyright 2006 Jörn Zaefferer (http://docs.jquery.com/Plugins/Validation)

The problem with designing a system that is absolutely foolproof is that one invariably underestimates the ingenuity of absolute fools
*/

/*****************
Define some tables
*****************/
global $wpdb;
define("EOMEVENTS", $wpdb->prefix."eom_events");
define("EOMVENUES", $wpdb->prefix."eom_venues");
define("EOMUSERS", $wpdb->prefix."eom_users");
define("EOMDATES", $wpdb->prefix."eom_dates");

define('MAX_UPLOAD_SIZE', (int)(ini_get('upload_max_filesize'))); //max size
define('TYPE_WHITELIST', serialize(array('image/jpeg', 'image/png', 'image/gif'))); //file types


/*****************
CLASS Event_O_Matic
*****************/
if(!class_exists("Event_O_Matic")) {class Event_O_Matic{


protected $status=array('approved'=>'A','pending'=>'P');


/**
 * @method constructor
 */
public function __construct(){
	if(get_option('eom_map_w')){$this->map_dimentions['w'] = get_option('eom_map_w');}
	if(get_option('eom_map_h')){$this->map_dimentions['h'] = get_option('eom_map_h');}
	
	include_once('user.php');
	include_once('venue.php');
	include_once('event.php');
	include_once('date.php');
	include_once('pagination.class.php');
}


/**
 * @method install
 * start up plugin
 *
 */
public function install(){
	global $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); // need for dbDelta
	
	add_option("eom_db_version", '0.3'); //add db version
	add_option('eom_description_max', 1000); //default max chars
	add_option('eom_events_url', get_bloginfo('url')); //default events url
	add_option("eom_map_w", 600);
	add_option("eom_map_h", 400);
	if(!get_option('eom_event_paginate')){add_option('eom_event_paginate', true);}
	
	if($wpdb->get_var("SHOW TABLES LIKE '".EOMEVENTS."'") != EOMEVENTS){
		$sql = "CREATE TABLE ".EOMEVENTS." (
		id int(10) NOT NULL AUTO_INCREMENT,
		venue_id int(10) DEFAULT '0' NOT NULL,
		user_id int(10) DEFAULT '0' NOT NULL,
		name VARCHAR(150) NOT NULL,
		timestamp TIMESTAMP NOT NULL,
		type CHAR(1) NOT NULL,
		status CHAR(1) NOT NULL,
		description TEXT NOT NULL,
		price DECIMAL(5,2) NOT NULL,
		flyer VARCHAR(200) NOT NULL,
		website VARCHAR(200) NOT NULL,
		UNIQUE KEY id (id)
		);";
		dbDelta($sql);
	}
	if($wpdb->get_var("SHOW TABLES LIKE '".EOMDATES."'") != EOMDATES){
		$sql = "CREATE TABLE ".EOMDATES." (
		id int(10) NOT NULL AUTO_INCREMENT,
		event_id int(10) DEFAULT '0' NOT NULL,
		timestamp TIMESTAMP NOT NULL,
		start DATETIME NOT NULL,
		end DATETIME NOT NULL,
		UNIQUE KEY id (id)
		);";
		dbDelta($sql);
	}
	if($wpdb->get_var("SHOW TABLES LIKE '".EOMVENUES."'") != EOMVENUES){
		$sql = "CREATE TABLE ".EOMVENUES." (
		id int(10) NOT NULL AUTO_INCREMENT,
		timestamp TIMESTAMP NOT NULL,
		name VARCHAR(100) NOT NULL,
		street VARCHAR(100) NOT NULL,
		city VARCHAR(75) NOT NULL,
		status CHAR(1) NOT NULL,
		fullAddress VARCHAR(200) NOT NULL,
		lat DECIMAL(12,7) NOT NULL, 
		lon DECIMAL(12,7) NOT NULL,
		UNIQUE KEY id (id)
		);";
		dbDelta($sql);
	}	
	if($wpdb->get_var("SHOW TABLES LIKE '".EOMUSERS."'") != EOMUSERS){
		$sql = "CREATE TABLE ".EOMUSERS." (
		id int(10) NOT NULL AUTO_INCREMENT,
		timestamp TIMESTAMP NOT NULL,
		name VARCHAR(75) NOT NULL,
		email VARCHAR(75) NOT NULL,
		UNIQUE KEY id (id)
		);";
		dbDelta($sql);
	}
}


/**
 * @method process_image
 *
 */
function process_image($file){  
	require_once(ABSPATH . "wp-admin" . '/includes/image.php');  
 	require_once(ABSPATH . "wp-admin" . '/includes/file.php');  
	require_once(ABSPATH . "wp-admin" . '/includes/media.php');  
	return media_handle_upload($file, 0);  //return attachment_id
}  


/**
 * @method parse_file_errors
 *
 */
function parse_file_errors($file = ''){  
	if($file['error']){  
		return  __('No file uploaded or there was an upload error.', 'event-o-matic');
  	}  
	$max_bytes = MAX_UPLOAD_SIZE * 1048576;
  	$image_data = @getimagesize($file['tmp_name']);  
  	if(!in_array($image_data['mime'], unserialize(TYPE_WHITELIST))){  
    	return __('Image must be a jpeg, png or gif.','event-o-matic');  
  	}elseif(($file['size'] > $max_bytes)){  
		return printf(__('Image is %d bytes and can not exceed %d bytes.', 'event-o-matic'), $file['size'], $max_bytes); 
  	}  
}  



/**
 * @method form
 * create submission form, uses shortcode [eom-public-form]
 *
 */
public function form(){

//create objects
$venue = new Venue();
$event = new Event();
$date = new Date();
$user = new User();
$location_flag = '';
$errors = array();
	
// form submitted
if($_POST && wp_verify_nonce($_POST['eom_event_nonce'],'event_create')){
	
	$user->put(array( 'name' => $_POST['user_name'], 'email' => $_POST['user_email'])); //add user
	
	if(!empty($_POST['venue_address']) && !empty($_POST['venue_name'])){ // add venue (address or id)
		$venue->put(array('name' => $_POST['venue_name'])); //add venue name
		try{
			$results = $venue->lookup($_POST['venue_address']);
			if(count($results) > 1){ //multiple results
				$multiple_venues = $results;
				$errors[] = __('Multiple results found for submitted address. Please select one.','event-o-matic');
			}elseif(count($results) == 1 ){ //one result, add to object
				$city = ''; //may not get back a city
				if(isset($results[0]['components']['locality'])){
					$city = $results[0]['components']['locality'];
				}elseif(isset($results[0]['components']['sublocality'])){
					$city = $results[0]['components']['sublocality'];
				}
				$venue->put(array(
					'address' => $results[0]['full_address'],
					'city' => $city,
					'lat' => $results[0]['lat'],
					'lon' => $results[0]['lng'],
				));
			}else{ //zero results, error!
				$errors[] = __('No results found for submitted address','event-o-matic');
			}
		} catch (Exception $e) {
			$errors[] = __('Error processing address: ','event-o-matic') . $e->getMessage();
		}
	}elseif($_POST['venue_id']){ //add venue id
		$venue->put(array( 'id' => $_POST['venue_id'] ));
		$venue->get_by_id(); //get address from id
	}else{ //error, no id or address
		$errors[] = __('Please select or add a venue name and address.','event-o-matic');
	}
	
	//error check event image
	if(isset($_FILES['image_file']['name']) && $_FILES['image_file']['name']){
		if($file_error = $this->parse_file_errors($_FILES['image_file'])){
			$errors[] = $file_error;
		}
	}
	
	$event->put(array( //add event
		'name' => $_POST['event_name'],
		'description' => $_POST['event_description'],
		'price' => $_POST['event_price'],
		//'image' => $_POST['event_image'],
		'url' => $_POST['event_url'],
		'status' => $event->statusCode['pending'] 
	));
	$date->put(array( //add date
		'date_start' => $_POST['date_start'],
		'time_start' => $_POST['time_start'],
		'date_end'=> $_POST['date_end'],
		'time_end' => $_POST['time_end'] 
	));
	
	//check all required data is present
	if(!$user->name || !$user->email || !$event->name || !$event->description || !$date->start || !$date->end){
		$errors[] = __('Please enter required information and resubmit.');
	}
	//combine errors
	$errors = array_merge($date->error, $errors);

	if( count($errors) == 0 ){ //no errors?
	
		//attach image, only if file exists and attach does not exist
		if(isset($_FILES['image_file']['name']) && $_FILES['image_file']['name'] && !isset($_POST['attach_id'])){
			$attach_id = $this->process_image('image_file'); //process image
		}elseif(isset($_FILES['image_file']['name']) && $_FILES['image_file']['name'] && isset($_POST['attach_id'])){
			//delete existing image and add new one
			
			//TO DO HERE
		}elseif(isset($_POST['attach_id'])){
			$attach_id = (int) $_POST['attach_id'];
		}
	
		if(isset($_POST['submit_commit'])){ //commit, add to database

			$user->save(); //save user
			if(!$venue->id){$venue->save();} //save venue
			$event->userId=$user->id;
			$event->venueId=$venue->id;
			$event->save(); //save event
			$date->eventId = $event->id;
			$date->save(); //save date
			//update attachment with event id
			update_post_meta($attach_id, 'eom_event_id', $event->id);
			
			//success, email admin
			$this->send_mail($event->name, $event->description);
			
			$location_flag = 'event_success';
		}
		if(isset($_POST['submit_event'])){ // show confirm page
			$location_flag='event_confirm';
		}
		
	}
}

//FORM
$r = '<form id="event-o-matic" method="post" action="" enctype="multipart/form-data" >';
$r.=  wp_nonce_field('event_create','eom_event_nonce'); //nonce

switch ($location_flag):
case 'event_confirm': //event comfirmation
	$r.='<h1>'.__('Confirm Information','event-o-matic').'</h1>';
	$r.='<p>'.esc_html($user->name).', '.__('Confirm event information is correct. Click "confirm event" to submit.','event-o-matic').'</p>';
	
	$r.='<h2>'.esc_html($event->name).'</h2>';
	//if($event->image){$r.='<img src="'.esc_url($event->image).'" class="alignright" style="width:200px;float:right;" />';}
	$r.='<p>'.date('F jS \a\t g:ia',$date->start).' to '.date('F jS \a\t g:ia',$date->end).'</p>';
	$r.='<p><em>'.$event->description.'</em></p>';
	if(isset($attach_id)){$r.= wp_get_attachment_image( $attach_id, 'large' );}
	if($event->price){$r.='<p>'.__('Price:','event-o-matic').' '.esc_html($event->price).'</p>';}
	if($event->url){$r.='<p><a href="'.esc_url($event->url).'">'.esc_url($event->url).'</a></p>';}
	$r.='<h2>'.esc_html($venue->name).'</h2>';
	$r.='<p>'.esc_html($venue->address).'</p>';
	$r.='<div><img src ="http://maps.googleapis.com/maps/api/staticmap?zoom=13&size='.$this->map_dimentions['w'].'x'.$this->map_dimentions['h'].'&maptype=roadmap
&markers=color:blue%7C%7C'.$venue->lat.','.$venue->lon.'&sensor=false" /></div>';
	
	$r.='<input type="hidden" name="user_email" value="'.esc_attr($user->email).'" />';
	$r.='<input type="hidden" name="user_name" value="'.esc_attr($user->name).'" />';
	$r.='<input type="hidden" name="event_name" value="'.esc_attr($event->name).'" />';
	$r.='<input type="hidden" name="event_description" value="'.esc_attr($event->description).'" />';
	$r.='<input type="hidden" name="event_url" value="'.esc_attr($event->url).'" />';
	if(isset($attach_id)){$r.='<input type="hidden" name="attach_id" value="'.esc_attr($attach_id).'" />';}
	//$r.='<input type="hidden" name="event_image" value="'.esc_attr($event->image).'" />';
	$r.='<input type="hidden" name="event_price" value="'.esc_attr($event->price).'" />';
	$r.='<input type="hidden" name="date_start" value="'.date('n/j/Y',$date->start).'" />';
	$r.='<input type="hidden" name="date_end" value="'.date('n/j/Y',$date->end).'" />';
	$r.='<input type="hidden" name="time_start" value="'.date('G:i',$date->start).'" />';
	$r.='<input type="hidden" name="time_end" value="'.date('G:i',$date->end).'" />';
	if($venue->id):
		$r.='<input type="hidden" name="venue_id" value="'.$venue->id.'" />';
	else:
		$r.='<input type="hidden" name="venue_name" value="'.esc_attr($venue->name).'" />';
		$r.='<input type="hidden" name="venue_address" value="'.esc_attr($venue->address).'" />';
	endif;
	$r.='<input type="submit" value="'.__('Edit Event','event-o-matic').'" />';
	$r.='<input type="submit" name="submit_commit" value="'.__('Confirm Event','event-o-matic').'" />';
break; //END eventConfirm

case 'event_success': // event submitted successfully 
	$r.= '<h2>'.__('Thank You','event-o-matic').'</h2>';
	$r.= '<p>'.__('Your event has been submitted for review.','event-o-matic').'</p>';
	$r.= '<p><a href="'.get_bloginfo('url').'">'.sprintf(__('Return to %s'), get_bloginfo('name')).'</a></p>';
break;

default: // Event submission form
	if($venue->id){$venue->name = ''; $venue->address = '';} // Unset venue fields if id is present
	if($errors){ // Display errors
		$r .= '<div id="message" class="error"><ul>';
		foreach($errors as $error){$r.='<li>'.$error.'</li>';}
		$r.='</ul></div>';
	}
	
	$r.='<p><label for="user_email">'.__('Your Email:','event-o-matic').'</label><br />';
	$r.='<input type="text" name="user_email" class="required email" maxlength="200" value="'.esc_attr($user->email).'" />';
	
	$r.='<p><label for="user_name">'.__('Your Name:','event-o-matic').'</label><br />';
	$r.='<input type="text" name="user_name" class="required" maxlength="200" value="'.esc_attr($user->name).'" />';
	
	$r.='<p><label for="event_name">'.__('Event Name:','event-o-matic').'</label><br />';
	$r.='<input type="text" name="event_name" class="required" maxlength="200" value="'.esc_attr($event->name).'" />';
	
	$r.='<p><label for="event_description">'.__('Event Description:','event-o-matic').'</label><br />';
	$r.='<textarea name="event_description" id="event_description" class="required" cols="50" rows="5">'.esc_textarea($event->description).'</textarea>';
	$r.='<small>'.__('Allowed HTML tags: ','event-o-matic').allowed_tags().'</small>';
	
	$r.='<p><label for="date_start">'.__('Start Date:','event-o-matic').'</label><br />';
	$r.='<input type="text" class="datepicker required" name="date_start" value="'.date('n/j/Y',$date->start).'" /> ';
	$r.= $date->select_time('time_start', $date->start).'</p>';
	$r.='<p><label for="date_end">'.__('End Date:','event-o-matic').'</label><br />';
	$r.='<input type="text" class="datepicker required" name="date_end" value="'.date('n/j/Y',$date->end).'" /> ';
	$r.= $date->select_time('time_end', $date->end).'</p>';
	
	if(isset($multiple_venues)){ //multiple venue select form
		$r.='<input type="hidden" name="venue_name" value="'.esc_attr($venue->name).'" />';
		$r.= '<p><label for="venue_select">'.printf(__('Select an address for %s.', 'event-o-matic'), esc_html($venue->name)).'</label><br />';
		foreach($multiple_venues as $v){
			$r .= '<input type="radio" name="venue_address" value="'.esc_attr($v['full_address']).'">'.esc_html($v['full_address']).'<br />';
		}
	}else{ //venue select or add form
		$r.= '<p>'.__('Select a venue or enter a new venue name and address.','event-o-matic').'</p>';
		$r.='<table><tr><td valign="top"><p><label for="venue_id">'.__('Select Venue:','event-o-matic').'</label><br />';
		$r.= $venue->select($venue->id).'</p></td>';
		$r.='<td valign="top"><p><label for="venue_name">'.__('Venue Name:','event-o-matic').'</label><br />';
		$r.='<input type="text" name="venue_name" maxlength="200" value="'.esc_attr($venue->name).'" /></p>';
		$r.='<p><label for="venue_address">'.__('Venue Address:','event-o-matic').'</label><br />';
		$r.='<input type="text" name="venue_address" value="'.esc_attr($venue->address).'" /></p></td></tr></table>';
	}
	
	if(isset($_POST['attach_id']) && is_numeric($_POST['attach_id'])){
		$r .= wp_get_attachment_image( $_POST['attach_id'], 'large' );
		$r .= '<input type="hidden" name="attach_id" value="'.esc_attr($_POST['attach_id']).'" />';
	}else{
		$r .= '<p align="left"><label for="image_file">'.__('Event Image','event-o-matic').'</label><br/>';  
		$r .= '<input type="file" size="60" name="image_file" id="image_file">';
		$r .= '<small>'.__('Maximum upload file size: ','event-o-matic').' '.MAX_UPLOAD_SIZE.'MB</small>';
	}
	
	$r.='<p align="left"><label for="event_url">'.__('Website:','event-o-matic').'</label><br />';
	$r.='<input type="text" name="event_url" class="url" maxlength="200" value="'.esc_attr($event->url).'" /></p>';
	$r.='<p align="left"><label for="event_price">'.__('Price:','event-o-matic').'</label><br />';
	$r.='<input type="text" name="event_price" class="number" maxlength="10" value="'.esc_attr($event->price).'" /></p>';
	$r.='<p><input type="submit" name="submit_event" value="'.__('Submit Event','event-o-matic').'" /></p>';
break;
endswitch;

$r.='</form>'.$this->link_love();
return $r;
} //END form



/**
 * @method venues
 * venue listings, uses shortcode [eom-venues]
 *
 */
public function venues(){
	$venue = new Venue;
	if(isset($_GET['vid'])){ // Display single venue
		$venue->put( array('id'=>$_GET['vid']) );
		$venue->get_by_id();
		$out = '<h1>'.esc_html($venue->name).'</h1><p>'.esc_html($venue->address).'</p>';
		$out .= '<a href="https://maps.google.com/maps?q='.urlencode($venue->address).'&hl=en&sll='.$venue->lat.','.$venue->lon.'t=w&hnear='.urlencode($venue->address).'&z=17" target="_blank">
		<img src ="http://maps.googleapis.com/maps/api/staticmap?zoom=13&size='.$this->map_dimentions['w'].'x'.$this->map_dimentions['h'].'&maptype=roadmap
&markers=color:blue%7C%7C'.$venue->lat.','.$venue->lon.'&sensor=false&" /></a>';
		return $out;
	}else{ // Display venue list
		$p = new pagination;
		$venue_count = $venue->get_all(array('count'=>true));
		$p->items($venue_count); //get all records as count
		$p->limit(30); // Limit entries per page
		if(isset($_GET['paging'])){$p->currentPage($_GET['paging']);} // Gets and validates the current page
		$p->calculate(); // Calculates what to show
		$p->parameterName('paging');
		if(!isset($_GET['paging'])) {$p->page=1;}else{$p->page=$_GET['paging'];}
		$limit = ($p->page - 1) * $p->limit.", ".$p->limit; //Query for limit paging
		$venues = $venue->get_all(array('limit'=>$limit,'order'=>'name'));
		if( $venue_count > 0 ):
			$r='<p class="venues_count">'.$venue_count.' '.__('venues.','event-o-matic').'</p>';
			$r.='<div class="tablenav"><div class="tablenav-pages">'.$p->getOutput().'</div></div><ul>';
			foreach($venues as $v){
				$r.='<li><a href="'.add_query_arg('vid', $v->id).'">'.esc_html($v->name).'</a></li>';
			}
			$r.='</ul><div class="tablenav"><div class="tablenav-pages">'.$p->getOutput().'</div></div>'.$this->link_love();
			return $r;
		else:
			return '<p>'.__('No venues available.','event-o-matic').'</p>';
		endif;	
	}
}//END venues



/**
 * Display event listings, uses shortcode [eom-listings] or [eom-listings when=archive]
 *
 * @attr array $atts shortcode attributes
 */
public function events($atts){
	extract(shortcode_atts(array('when' => 'upcoming'), $atts));
	if($when=='archive'){$when='archive';}else{$when='upcoming';}
	$event = new Event;
	if(isset($_GET['id'])){ // Display single event
		$event->put(array('id'=>$_GET['id']));
		$event->get(array('status'=>$event->statusCode['approved']));
		//does event have image?
		$args = array(
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'meta_query' => array(
				array(
					'key' => 'eom_event_id',
					'value' => $event->id,
					//'type' => 'NUMERIC'
		)));
		$query = new WP_Query( $args );
		if(isset($query->post->ID)){
			$attach_id = $query->post->ID;
		}else{
			$attach_id = false;
		}
		$r = '<div class="eom_single_event">';
		if($event->image){$r.='<img src="'.esc_url($event->image).'" class="eom_image" />';} //depricated
		$r.= wp_get_attachment_image( $attach_id, 'large', false ,array('class' => 'eom_image'));
		
		$r.= '<h1>'.esc_html($event->name).'</h1>';
		$r.= '<div class="eom_date">'.date("F jS Y - g:ia",strtotime($event->dateStart)).'</div>';
		$r.= '<div class="eom_venue">'.__('at','event-o-matic').' <a href="#eomAddress">'.esc_html($event->venueName).'</a></div>';
		if($event->price!=0.00){$r.='<div class="eom_price"><strong>'.__('Price:','event-o-matic').'</strong> '.$event->price.'</div>';}
		$r.= '<p>'.$event->description.'</p>';
		if($event->url){
			$r.='<p><a href="'.esc_url($event->url).'" target="_blank" />'.__('Event Website', 'event-o-matic').'</a></p>';
		}
		$r.='<div class="eom_single_map"><a name="eomAddress"></a>';
		$r.= '<p><strong>'.esc_html($event->venueName).'</strong><br />'.esc_html($event->venueAddress).'</p>';
		$r.= '<a href="https://maps.google.com/maps?q='.urlencode($event->venueAddress).'&hl=en&sll='.$event->venueLat.','.$event->venueLon.'t=w&hnear='.urlencode($event->venueAddress).'&z=17" target="_blank">
		<img src ="http://maps.googleapis.com/maps/api/staticmap?zoom=13&size='.$this->map_dimentions['w'].'x'.$this->map_dimentions['h'].'&maptype=roadmap
&markers=color:blue%7C%7C'.$event->venueLat.','.$event->venueLon.'&sensor=false&" /></a></div>';
		$r.= '<p><a href="?eom_ical='.$event->id.'" class="eom_ical">'.__('Save event to my calendar', 'event-o-matic').'</a></p>';
		$r.= '</div>';
		return $r;
	}else{ // Display event list
		$p = new pagination;
		$count = $event->get_count(array('status'=>$event->statusCode['approved'],'when'=>$when));
		$p->items($count); //get all records as count
		$p->limit(30); // Limit entries per page
		if(isset($_GET['paging'])){$p->currentPage($_GET['paging']);} // Gets and validates the current page
		$p->calculate(); // Calculates what to show
		$p->parameterName('paging');
		if(!isset($_GET['paging'])){ $p->page=1; }else{ $p->page=$_GET['paging']; }
		if(get_option('eom_event_paginate')=='true'){
			$limit = ($p->page - 1) * $p->limit.", ".$p->limit; //Query for limit paging
		}else{
			$limit = 1000;
		}
		$events=$event->getAll(array('status'=>$event->statusCode['approved'],'limit'=>$limit,'when'=>$when,'order'=>'dateStart ASC'));
		if( $count > 0 ): 
			$r='<p class="events_count">'.$count.' '.$when.' '.__('events','event-o-matic').'.</p>';
			if(get_option('eom_event_paginate')=='true'){ //only show pagination if selected
				$r.='<div class="tablenav"><div class="tablenav-pages">'.$p->getOutput().'</div></div>';
			}
			$r.='<div class="eom_event_list">';
			foreach($events as $event){ //individual events
				//does event have image?
				$args = array('post_status' => 'inherit', 'post_type' => 'attachment', 'meta_query' => array(
				array(	'key' => 'eom_event_id',
						'value' => $event['id'])));
				$img_query = new WP_Query( $args );
				if(isset($img_query->post->ID)){
					$attach_id = $img_query->post->ID;
				}else{
					$attach_id=false;
				}
			
				if($event['preferred']){$r.= '<div class="eom_event preferred_event">';}else{$r.= '<div class="eom_event">';}
				if((get_option('eom_image_display')=='true') && ($event['image'])){ // Display image
					$r.='<a href="'.add_query_arg(array('id'=>$event['id'],'vid'=>$event['venueId'])).'"><img src="'.esc_url($event['image']).'" class="eom_image_thumbnail" /></a>';
				}
				if((get_option('eom_image_display') == 'true' ) && $attach_id){
					$r.='<a href="'.add_query_arg(array('id'=>$event['id'],'vid'=>$event['venueId'])).'">';
					$r.= wp_get_attachment_image( $attach_id, 'thumbnail', false );
					$r.='</a>';
				}
				$r.= '<h1><a href="'.add_query_arg(array('id'=>$event['id'],'vid'=>$event['venueId'])).'">'.esc_html($event['name']).'</a></h1>';
				$r.= '<div class="eom_date">'.date("F jS Y",strtotime($event['dateStart'])).'</div>';
				$r.= '<div class="eom_venue">'.esc_html($event['venueName']).'</div>';
				if($event['price']!=0.00){$r.='<div class="eom_price"><strong>'.__('Price:','event-o-matic').'</strong> '.$event['price'].'</div>';}
				$r.= '<p>'.substr($event['description'],0,200).'...</p></div>';
			}
			$r.='</div>';
			if(get_option('eom_event_paginate')=='true'){
				$r.='<div class="tablenav"><div class="tablenav-pages">'.$p->getOutput().'</div></div>';
			}
			if($when=='upcoming'){
				$r.='<p><a href="?eom_ical=upcoming" class="eom_ical">'.__('Save events to my calendar','event-o-matic').'</a></p>';
			}
			$r .= $this->link_love();
			return $r;
		else:
			return '<p>'.__('No events available.','event-o-matic').'</p>';
		endif;	
	}
}//END events



/**
 * @method ical
 * invoke webcal file format
 *
 */
public function ical($wpvarstoreset){
	if(isset($_GET['eom_ical']) ){
		$event = new Event;
		
		$name=preg_replace('/([\\,;])/','\\\\$1',get_bloginfo_rss('name'));
		$filename=preg_replace('/[^0-9a-zA-Z]/','',$name).'.ics'; 
		header("Content-Type: text/calendar; charset=" . get_option('blog_charset'));
		header("Content-Disposition: inline; filename=$filename");
		header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-cache, must-revalidate, max-age=0');
		header('Pragma: no-cache');

		echo "BEGIN:VCALENDAR\r\n";
		echo "VERSION:2.0\r\n";
  		echo "X-WR-CALNAME:$name\r\n";
		
		if(is_numeric($_GET['eom_ical'])){ // one event id
			$event->put(array('id'=>$_GET['eom_ical']));
			$event->get(array('status'=>$event->statusCode['approved']));
		
			$summary = preg_replace('/([\\,;])/','\\\\$1',$event->name);
     		$permalink = get_option('eom_events_url').'?id='.$event->id;	 
			echo "BEGIN:VEVENT\r\n";
      		echo "SUMMARY:$summary\r\n";
      		echo "URL;VALUE=URI:$permalink\r\n";
      		echo "UID:$permalink\r\n";
      		// So just strip out newlines here:
			$description=preg_replace('/[ \r\n]+/',' ',$event->venueName.' - '.$event->venueAddress.' - '.strip_tags($event->description).' ');
        	$description=preg_replace('/([\\,;])/','\\\\$1',$description);
    		$description.='['.sprintf(__('by: %s'),$name).']';
      		echo "DESCRIPTION:$description\r\n";
      		echo "TRANSP:TRANSPARENT\r\n"; // for availability.
		
			echo sprintf("DTSTART;VALUE=DATE-TIME:%s\r\n",gmdate('Ymd\THis\Z',strtotime($event->dateStart)));
        	echo sprintf("DTEND;VALUE=DATE-TIME:%s\r\n",gmdate('Ymd\THis\Z',strtotime($event->dateEnd)));
			echo "END:VEVENT\r\n";
		
		}
		if($_GET['eom_ical']=='upcoming'){ //all upcoming events
			$events=$event->getAll(array('status'=>$event->statusCode['approved'],'when'=>'upcoming','order'=>'dateStart','orderDirection'=>'DESC'));
			foreach($events as $event){
				$summary = preg_replace('/([\\,;])/','\\\\$1',$event['name']);
     			$permalink = get_option('eom_events_url').'?id='.$event['id'];	 
				echo "BEGIN:VEVENT\r\n";
      			echo "SUMMARY:$summary\r\n";
      			echo "URL;VALUE=URI:$permalink\r\n";
      			echo "UID:$permalink\r\n";
      			// So just strip out newlines here:
				$description=preg_replace('/[ \r\n]+/',' ',$event['venueName'].' - '.strip_tags($event['description']).' ');
        		$description=preg_replace('/([\\,;])/','\\\\$1',$description);
    			$description.='['.sprintf(__('by: %s'),$name).']';
      			echo "DESCRIPTION:$description\r\n";
      			echo "TRANSP:TRANSPARENT\r\n"; // for availability.
		
				echo sprintf("DTSTART;VALUE=DATE-TIME:%s\r\n",gmdate('Ymd\THis\Z',strtotime($event['dateStart'])));
        		echo sprintf("DTEND;VALUE=DATE-TIME:%s\r\n",gmdate('Ymd\THis\Z',strtotime($event['dateEnd'])));
				echo "END:VEVENT\r\n";
				
			}
		}
		
		
		echo "END:VCALENDAR\r\n";
  		exit(0);
	}
	return $wpvarstoreset;
}




/**
 * @method js
 * call javascript libraries
 *
 */
public function js(){
	wp_enqueue_script('validate', plugin_dir_url( __FILE__ ).'js/jquery.validate.min.js',array('jquery'));  
	wp_enqueue_script('count', plugin_dir_url( __FILE__ ).'js/charactercount.js');
	wp_enqueue_script('jquery-ui-datepicker');
}


/**
 * @method css
 * call css libraries
 *
 */
public function css(){
	wp_enqueue_style('eom',plugin_dir_url( __FILE__ ).'css/event-o-matic.css');
	wp_enqueue_style('jquery-ui', plugin_dir_url( __FILE__ ).'css/jquery.ui.css');
}



/**
 * @method head
 *
 * call head content
 * TODO: internationalize date format
 */
public function head(){?>
	<script type="text/javascript" charset="utf-8">
	jQuery(document).ready(function($){
		$("#event_description").charCounter(<?php echo get_option('eom_description_max');?>);
		$("#event-o-matic").validate();
		$(".datepicker").datepicker({ dateFormat: "m/d/yy" });
	});</script><?php
}



/**
 * Send email to administrator 
 *
 */
private function send_mail($event_name, $event_description){
	$headers = 'From: '.get_bloginfo('name').' <'.get_bloginfo('admin_email').'>' . "\r\n\\";
	$message = __('A new event has been submitted to your website:','event-o-matic').' '.esc_html($event_name).' 
	
	'.esc_html($event_description);
	wp_mail( get_bloginfo('admin_email'), get_bloginfo('name').' - '.__('New Event Submitted','event-o-matic'), $message, $headers);
}


/**
 * Add linkback
 *
 * @return string link
 */
protected function link_love(){
	$style = 'display:none;';
	if(get_option('eom_link_love') == 'true'){ $style = 'font-size:9px;'; }
	$host =  urlencode($_SERVER['SERVER_NAME']);
	return '<a style="'.$style.'" href="http://garvinmedia.com/eom?love='.$host.'" target="_blank">'
	.__('Powered by Event-O-Matic','event-o-matic').'</a>';
}

}}//End Class Event_O_Matic

//CREATE OBJECT
if (class_exists("Event_O_Matic")) {$eomObject = new Event_O_Matic();}

/*****************
HOOKS, SHORTCODE, INSTALL
*****************/
if ( isset($eomObject)  ) {
	add_shortcode("eom-venues", array($eomObject, 'venues'));
	//add_shortcode("eom-listings", array($eomObject, 'events')); //Deprecated, remove in future versions
	add_shortcode("eom-events", array($eomObject, 'events'));
	//add_shortcode("eom-public-form", array($eomObject, 'form')); //Deprecated, remove in future versions
	add_shortcode("eom-form", array($eomObject, 'form'));
	
	add_action('wp_print_scripts', array($eomObject, 'js'));
	add_action('wp_print_styles', array($eomObject, 'css'));
	add_action('wp_head', array($eomObject, 'head'));
	add_filter('query_vars',   array($eomObject,'ical'));
	register_activation_hook( __FILE__, array($eomObject, 'install'));
}

/*****************
WIDGET / ADMIN 
*****************/
include('widget.php');
include('admin_bar.php');
if (is_admin()){include('eom_admin.php');}

?>