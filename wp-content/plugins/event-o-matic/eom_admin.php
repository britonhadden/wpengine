<?php


/**
 * @class Event_O_Matic_Admin
 * @extends Event_O_Matic
 *
 */
class Event_O_Matic_Admin extends Event_O_Matic{



/**
 * Display general admin page
 *
 */
public function general(){
	if (!current_user_can('manage_options')){
		wp_die( __('You do not have sufficient permissions to access this page.') );
    }
	$event = new Event; //create event
	
	if(isset($_GET['id'])){ //process pending events	
		$event->id=$_GET['id'];
		if( ($_GET['action'] == 'approve') && (check_admin_referer('approve'))){ //approve event
			$event->status = $event->statusCode['approved'];
			if( $event->statusSave() ){ $action = __('Event Approved','event-o-matic'); }
		}
		if( ($_GET['action'] == 'delete') && (check_admin_referer('delete'))){ //delete event
			if( $event->delete() ){$action = __('Event Deleted.','event-o-matic'); }
		}
	}
	
	if ( isset($_POST['action']) && check_admin_referer('eom_settings') ){ //process settings
		if(isset($_POST['eom_image_display'])){ 
			update_option( 'eom_image_display', 'true' );}
		else{update_option( 'eom_image_display', 'false' );}
		
		if(isset($_POST['eom_event_paginate'])){ 
			update_option( 'eom_event_paginate', 'true' );}
		else{ update_option('eom_event_paginate', 'false' );}
		
		if(isset($_POST['eom_link_love'])){ 
			update_option( 'eom_link_love', 'true' );
		}else{update_option( 'eom_link_love', 'false' );}
		
		if($_POST['eom_map_w']){ 
			update_option( 'eom_map_w', $_POST['eom_map_w'] );}
		if($_POST['eom_map_h']){ 
			update_option( 'eom_map_h', $_POST['eom_map_h'] );}
		if($_POST['eom_events_url']){ 
			update_option('eom_events_url', esc_url_raw($_POST['eom_events_url']));}
		if($_POST['eom_description_max']){ 
			update_option( 'eom_description_max', $_POST['eom_description_max'] );}
		$action = __('Settings saved.','event-o-matic');
	}
	//check for pending events
	$pending = $event->getAll(array('status'=>$event->statusCode['pending'],'order'=>'dateStart'));
	?>
	<div class="wrap"><div id="icon-eom" class="icon32"></div>
		<h2><?php _e( 'Event-O-Matic', 'event-o-matic' );?></h2>
		<?php $this->greeting(); ?>
		<?php if(isset($action)): ?>
			<div class="updated fade"><p><?php echo $action;?></p></div>
		<?php endif; ?>
		
		<?php if(count($pending)>0):?>
		<div class="updated fade"><p><?php _e('Pending events need approval.','event-o-matic');?></p></div>
		<h3><?php _e('Events Pending Approval', 'event-o-matic');?></h3>
		<p><em><?php _e('Highlighted events have already occurred.','event-o-matic');?></em></p>
	<table class="widefat">
	<thead><tr><th scope="col"><?php _e('Name','event-o-matic');?></th><th scope="col"><?php _e('Venue','event-o-matic');?></th><th scope="col"><?php _e('Actions','event-o-matic');?></th><th scope="col"><?php _e('Date','event-o-matic');?></th><th scope="col"><?php _e('Submitted By','event-o-matic');?></th></thead>
	<tfoot><tr><th scope="col"><?php _e('Name','event-o-matic');?></th><th scope="col"><?php _e('Venue','event-o-matic');?></th><th scope="col"><?php _e('Actions','event-o-matic');?></th><th scope="col"><?php _e('Date','event-o-matic');?></th><th scope="col"><?php _e('Submitted By','event-o-matic');?></th></tfoot><tbody>
	<?php foreach ($pending as $value):
	if(strtotime($value['dateEnd'])<time()){echo '<tr style="background-color:#FFFFE0">';}else{echo '<tr>';}?>
		<td><a href="?page=eom-event&action=update&id=<?php echo $value['id'];?>">
		<?php echo esc_html($value['name']);?></a></td>
		<td><?php echo esc_html($value['venueName']);?></td>
		<td><a href="<?php echo wp_nonce_url('?page=event-o-matic&id='.$value['id'].'&action=approve', 'approve');?>">Approve</a> | 
		<a onclick="return confirm('Are you sure you want to delete this event?')" href="<?php echo wp_nonce_url('?page=event-o-matic&id='.$value['id'].'&action=delete', 'delete');?>">Delete</a></td>
		<td><?php echo date("F jS Y, g:i a",strtotime($value['dateStart']));?></td>
		<td><a href="mailto:<?php echo esc_attr($value['userEmail']);?>"><?php echo esc_html($value['userEmail']); ?></a></td></tr>
	<?php endforeach; ?>	
	</tbody></table>
	<?php endif;?>
	
	<h3><?php _e('Get Started', 'event-o-matic');?></h3>
	<p>The Event-O-Matic is a simple plugin to list your events or accept submitted events from your users. 
	Add events and venues with the admin forms here, and list events and venues on your site by creating a new page and adding the shortcodes below. 
	<ul>
		<li><strong>[eom-form]</strong> - Use this shortcode to add an event submission form.</li>
		<li><strong>[eom-events]</strong> - Add this to list all upcoming events.</li>
		<li><strong>[eom-events when="archive"]</strong> - Use the archive attribute to list all past events.</li>
		<li><strong>[eom-venues]</strong> - Use this shortcode to list all venues.</li>
	</ul>
	</p>
	<form name="form" method="post" action="" >
		<?php wp_nonce_field('eom_settings'); ?>
		<h3><?php _e('General Settings','event-o-matic'); ?></h3>
		<table class="form-table"><tr valign="top">
		<th scope="row">
		Event Listings Page<br /><em>Add in the full url where you have placed the [eom-listings] shortcode.</em></th>
		<td><input name="eom_events_url" type="text" value="<?php echo get_option('eom_events_url'); ?>" size="50" /></td></tr>
		<th scope="row">
		Map Dimentions<br /><em>Width and height of the map in the venue and event pages.</em></th>
		<td><input name="eom_map_w" type="text" value="<?php echo get_option('eom_map_w'); ?>" size="4" /> x 
		<input name="eom_map_h" type="text" value="<?php echo get_option('eom_map_h'); ?>" size="4" />
		</td></tr>
		<tr valign="top">
		<th scope="row">Display images in event list:</th>
		<td>
		<input type="checkbox" name="eom_image_display" value="true" <?php checked( get_option('eom_image_display'), 'true' ); ?> />
		</td></tr>
		<tr valign="top">
		<th scope="row">Paginate Event Listings:</th>
		<td>
		<input type="checkbox" name="eom_event_paginate" 
		value="true" <?php checked( get_option('eom_event_paginate'), 'true' ); ?> />
		</td></tr>
		<tr valign="top">
		<th scope="row">Event Description Max Characters:</th>
		<td><input name="eom_description_max" type="text" value="<?php echo get_option('eom_description_max'); ?>" size="4" />
		</td></tr>
		<tr valign="top"><th scope="row"><?php _e('Link Love','event-o-matic');?></th>
		<td><input type="checkbox" name="eom_link_love" value="true" <?php checked( get_option('eom_link_love'), 'true' ); ?> />
		</td>
		</tr>
		</table>
		<input type="hidden" name="action" value="update" />
		<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Settings') ?>" />
		</p>
		</form>
	</div><!--.wrap --><?php 
}



/**
 * View, add or edit venue
 *
 */
public function venue(){
	if (!current_user_can('manage_options')){wp_die( __('You do not have sufficient permissions to access this page.'));}
	$venue = new Venue();
	if(isset($_REQUEST['id'])){$venue->put(array('id'=>$_REQUEST['id']));} //add id
	if ( isset($_POST['submit_delete']) && check_admin_referer('venue', 'eom-nonce')){ //delete
		if($venue->delete()){
			$action = __('Venue Deleted.','event-o-matic');
			$venue = new Venue; //new object is created to reset the form
		}
	}
	if ( isset($_POST['submit_venue']) && check_admin_referer('venue', 'eom-nonce')){ //insert, update
		$venue->put(array('name'=>$_POST['name'], 'address'=>$_POST['address'])); //add name and address
		if($venue->name && $venue->address){ //required data
			try{
				$results = $venue->lookup($venue->address);
				if(count($results) > 1){ //multiple results
					$multiple_venues = $results;
					$action = __('Multiple results found for submitted address. Please select one.','event-o-matic');
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
					$res = $venue->save(); 
					$action = __('Venue Saved.','event-o-matic');
				}else{ //zero results, error!
					$action = __('No results found for submitted address','event-o-matic');
				}
			} catch (Exception $e) {
				$action = __('Error processing address: ','event-o-matic') . $e->getMessage();
			}
		}
	}
	$events_at_venue = 0;
	//get venue, events that use this venue
	if($venue->id){
		$venue->get_by_id();
		$events_at_venue = $venue->in_use();
	} ?>
	<div class="wrap"><div id="icon-eom" class="icon32"></div>
		<h2><?php _e( 'Add / Edit Venue', 'event-o-matic' );?></h2>
		<?php if(isset($action)): ?>
			<div class="updated fade"><p><?php echo $action;?></p></div>
		<?php endif; ?>
		<form method="post" action="">
	 	<?php wp_nonce_field('venue','eom-nonce'); ?>
		<table class="form-table">
		<tbody>
		<tr valign="top">
		<th scope="row"><label for="name"><?php _e('Venue Name', 'event-o-matic' ); ?></label></th>
		<td><input name="name" value="<?php echo esc_attr($venue->name);?>" class="regular-text" type="text"></td>
		<td rowspan="2">
		<?php if($venue->address && !isset($multiple_venues)): ?>
		<a href="https://maps.google.com/maps?q=<?php echo urlencode($venue->address); ?>&hl=en&sll=<?php echo $venue->lat;?>,<?php echo $venue->lon;?>t=w&hnear=<?php echo urlencode($venue->address);?>" target="_blank">
		<img src ="http://maps.googleapis.com/maps/api/staticmap?&size=450x350&maptype=roadmap
&markers=color:blue%7C%7C<?php echo $venue->lat;?>,<?php echo $venue->lon;?>&sensor=false&zoom=14" /></a>
		<?php endif; ?>
		</td>
		</tr>
		<tr valign="top">
		<th scope="row"><label for="address"><?php _e('Address', 'event-o-matic' ); ?></label></th>
		<td>
		<?php if(isset($multiple_venues)): foreach($multiple_venues as $v): ?>
			<input type="radio" name="address" value="<?php echo esc_attr($v['full_address']);?>">
			<?php echo esc_html($v['full_address']);?><br />
		<?php endforeach; else: ?>
			<textarea name="address" cols="35" rows="3"><?php echo esc_textarea($venue->address);?></textarea>
		<?php endif; ?>
		</td>
		</tr>
		
		</tbody></table>
	
		<input type="hidden" name="id" value="<?php echo $venue->id;?>" />
		<p class="submit"><input class="button-primary" type="submit" name="submit_venue" value="Save Venue" /> 
	
	<?php if(!$events_at_venue && ($venue->id)):?>
		<input class="button-secondary" type="submit" name="submit_delete" value="Delete Venue" />
	<?php endif; ?>
	</p></form>
	<?php if(isset($events_at_venue) && $events_at_venue): ?>
		<h3><?php _e('Events at this venue', 'event-o-matic');?></h3>
		<table class="widefat">
		<thead><tr><th><?php _e('Event Name','event-o-matic');?></th></tr></thead>
		<tfoot><tr><th><?php _e('Event Name','event-o-matic');?></th></tr></tfoot>
		<tbody>
		<?php foreach($events_at_venue  as $event):?>
			<tr><td><a href="?page=eom-event&id=<?php echo $event->id;?>"><?php echo esc_html($event->name);?></a></td></tr>
		<?php endforeach; ?>
		</tbody></table>
	<?php endif;	
}





/**
 * View, Add or Edit event
 *
 */
public function event(){
	if (!current_user_can('manage_options')){wp_die( __('You do not have sufficient permissions to access this page.'));}
	$venue = new Venue;
	$event = new Event;
	$date = new Date;
	$user = new User;
	if(isset($_REQUEST['id'])){$event->put(array('id'=> $_REQUEST['id']));} //add id
	
	//image delete
	if(isset($_GET['action']) && ( $_GET['action'] == 'img_delete' ) && check_admin_referer('eom-image-remove')){
		$this->delete_image($event->id);
	}
	
	//delete event
	if (isset($_REQUEST['submit_delete']) && check_admin_referer('event', 'eom-nonce')){
		if( $event->delete() ){ 
			$action = __('Event Deleted.','event-o-matic'); 
			$event = new Event; //new object is created to reset the form
		}
	}
	
	//create/update event
	if(isset($_REQUEST['submit_event']) && check_admin_referer('event', 'eom-nonce')){
		if(!isset($_POST['preferred'])){$_POST['preferred']='';}
		if(!isset($_POST['status'])){$_POST['status']='P';}
		$event->put(array(
			'name'=> $_POST['name'],
			'description'=> $_POST['description'],
			'price'=> $_POST['price'],
			//'image'=> $_POST['image'],
			'url'=> $_POST['url'],
			'status'=> $_POST['status'],
			'preferred' => $_POST['preferred'],
			'venue_id'=> $_POST['venue_id'],
			'user_id' => $_POST['user_id']));
		$date->put(array(
			'date_start' => $_POST['date_start'],
			'time_start' => $_POST['time_start'],
			'date_end'=> $_POST['date_end'],
			'time_end' => $_POST['time_end'])); 	
			
		//check image error
		if(isset($_FILES['image']['name']) && $_FILES['image']['name']){
			$image_error = $this->parse_file_errors($_FILES['image']);	
		}
			
		if(!$event->name || !$event->description || !$event->venueId || !$date->start || !$date->end){
			$action = __('Add required information and try again.','event-o-matic');
		}elseif($image_error){ //error uploading image
			$action = $image_error;
		}else{ //no error
			if(!$event->userId){ //add user id if none exists
				global $current_user;
				get_currentuserinfo(); //get current wp admin data 
				$user->put(array('name'=>$current_user->display_name,'email'=>$current_user->user_email));
				$user->save(); //$user->id now exists
				$event->userId = $user->id;	
			}
			//insert event
			if($event->save()){
				$date->put(array('eventId' => $event->id));
				$date->save();
				//insert image
				if(isset($_FILES['image']['name']) && $_FILES['image']['name']){
					$attach_id = $this->process_image('image'); //process image
					update_post_meta($attach_id, 'eom_event_id', $event->id); //insert post meta
				}
				$action = __('Event Saved.','event-o-matic');
			}else{
				$action = __('Error saving event. Please try again.','event-o-matic');
			}
		}
	}
	//get event, date, image data
	$attach_id = '';
	if($event->id){
		$event->get();
		$date->get($event->id);
		$args = array(
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'meta_query' => array(array('key' => 'eom_event_id', 'value' => $event->id))
		);
		$img_query = new WP_Query( $args );
		if(isset($img_query->post->ID)){
			$attach_id = $img_query->post->ID;
		}
	}
	?>
	<div class="wrap"><div id="icon-eom" class="icon32"></div>
	<h2><?php if($event->id){ _e( 'Edit', 'event-o-matic' ); }else{ _e( 'Add', 'event-o-matic' ); }
	_e( ' Event', 'event-o-matic' );?></h2>
	
	<?php if(isset($action)): //notifications ?>
		<div class="updated fade"><p><?php echo $action;?></p></div>
	<?php endif; ?>
	
	<form method="post" enctype="multipart/form-data">
	<?php wp_nonce_field('event','eom-nonce'); ?>
	<table class="form-table"><tbody>
	<tr valign="top">
		<th scope="row"><label for="name"><?php _e('Name:', 'event-o-matic' ); ?></label></th>
		<td><input name="name" value="<?php echo esc_attr($event->name);?>" class="regular-text" type="text"></td></tr>
	<tr valign="top">
		<th scope="row"><label for="description"><?php _e('Description:', 'event-o-matic' ); ?></label></th>
		<td>
		<textarea id="eventDescription" name="description" cols="70" rows="10"><?php echo esc_textarea($event->description);?></textarea><br />
		<span class="description"><?php _e('Allowed HTML tags: ','event-o-matic');echo allowed_tags();?></span>
		</td></tr>
	<tr valign="top">
		<th scope="row"><?php _e('Event Dates:', 'event-o-matic' ); ?></th>
		<td>
			<p><label for="date_start"><?php _e('Start Date:','event-o-matic');?></label>
			<input type="text" class="datepicker" name="date_start" value="<?php echo date('n/j/Y',$date->start);?>" />
			<?php echo $date->select_time('time_start', $date->start);?></p>
			<p><label for="date_end"><?php _e('End Date:','event-o-matic');?></label>
			<input type="text" class="datepicker required" name="date_end" value="<?php echo date('n/j/Y',$date->end);?>" />
			<?php echo $date->select_time('time_end', $date->end);?></p>
		
		</td></tr>
	<tr valign="top">
		<th scope="row"><label for="venue_id"><?php _e('Venue:', 'event-o-matic' ); ?></label></th>
		<td><?php echo $venue->select($event->venueId); ?> <em><a href="?page=eom-venue">Create a new venue</a></em></td></tr>
	<tr valign="top">
		<th scope="row"><label for="url"><?php _e('Website:', 'event-o-matic' ); ?></label></th>
		<td><input name="url" value="<?php echo esc_attr($event->url);?>" class="regular-text" type="text"></td></tr>
	<tr valign="top">
		<th scope="row">
		<label for="image"><?php _e('Event Image:', 'event-o-matic' ); ?></label></th>
		<td>
		<?php if( $attach_id ):
			echo wp_get_attachment_image( $attach_id );
			$link = wp_nonce_url('?page=eom-event&action=img_delete&id='.$event->id, 'eom-image-remove');
			echo '<br /><a href="'.$link.'">'.__('Remove Image','event-o-matic').'</a>';
		else:?>	
			<input type="file" size="60" name="image" id="image">
			<small><?php printf(__('Maximum upload file size: %d MB','event-o-matic'), MAX_UPLOAD_SIZE );?></small>
		<?php endif; ?>
		</td></tr>
	<tr valign="top">
		<th scope="row"><label for="price"><?php _e('Price:', 'event-o-matic' ); ?></label></th>
		<td><input name="price" value="<?php echo esc_attr($event->price);?>" class="small-text" type="text"></td></tr>
	<tr valign="top">
		<th scope="row"><label for="preferred"><?php _e('Preferred Event:', 'event-o-matic' ); ?></label></th>
		<td><input name="preferred" value="P" type="checkbox" <?php checked( $event->preferred, 'P', true ); ?> />
		</td></tr>
	<tr valign="top">
		<th scope="row"><label for="status"><?php _e('Published:', 'event-o-matic' ); ?></label></th>
		<td><input name="status" value="A" type="checkbox"  <?php checked( $event->status, 'A', true ); ?> /></td></tr>
	</tbody></table>
	<input type="hidden" name="user_id" value="<?php echo esc_attr($event->userId);?>" /> 
	<input type="hidden" name="id" value="<?php echo esc_attr($event->id);?>" />
	<p class="submit"><input class="button-primary" type="submit" name="submit_event" value="<?php _e('Save Event','event-o-matic');?>" />
	<?php if($event->id):?>
		<input class="button-secondary" type="submit" name="submit_delete" value="<?php _e('Delete Event','event-o-matic');?>" />
	<?php endif; ?>
	</p>
	</form>
	
	</div>
	<?php
}



/**
 * Display users listing
 *
 */
public function users(){
	if (!current_user_can('manage_options')){
		wp_die( __('You do not have sufficient permissions to access this page.') );
    }
	$p = new pagination;
	$user = new User;
	if(!empty($_POST['s'])){
		$total_count = $user->getAll(array('count'=>true, 'like'=> $_POST['s']));
	}else{$total_count = $user->getAll(array('count'=>true));}
	$p->items($total_count); //get all records as count
	$p->limit(30); // Limit entries per page
	$p->target('?page=eom-users');
	if(isset($_GET['p'])){$p->currentPage($_GET['p']);} 
	$p->calculate(); // Calculates what to show
	$p->parameterName('p');
	if(!isset($_GET['p'])) {$p->page=1;}else{$p->page=$_GET['p'];}
	$limit = ($p->page - 1) * $p->limit.", ".$p->limit; //Query for limit paging
	if(!empty($_POST['s'])){$users = $user->getAll(array('order'=>'timestamp DESC','limit'=>$limit, 'like'=> $_POST['s']));
	}else{$users=$user->getAll(array('order'=>'timestamp DESC','limit'=>$limit));}?>
	<div class="wrap"><div id="icon-eom" class="icon32"></div>
	<h2><?php _e( 'Users', 'event-o-matic' );?>
		<?php if ( ! empty( $_POST['s'] ) )
		printf( '<span class="subtitle">' . __('Search results for &#8220;%s&#8221;') . '</span>', esc_html($_POST['s']) ); ?>
	</h2>
	<ul class='subsubsub'><li class='all'>
		<a href='?page=eom-venues' class="current">All <span class="count">(<?php echo $total_count;?>)</span></a>
	</li></ul>
	<form id="users-filter" action="" method="post"><p class="search-box">
		<label class="screen-reader-text" for="user-search-input">Search Users:</label>
		<input type="search" id="user-search-input" name="s" value="<?php if(isset($_POST['s'])){echo esc_html($_POST['s']);}?>" />
		<input type="submit" name="" id="search-submit" class="button" value="<?php _e('Search Users','event-o-matic');?>"  /></p>
			
    	<div class="tablenav top"><div class='tablenav-pages'>
			<span class="displaying-num"><?php printf(_n('%d user', '%d users', $total_count), $total_count);?></span>
		<?php echo $p->show(); ?></div></div>
		<table class="widefat">
		<thead><tr>
		<th><?php _e('Name','event-o-matic');?></th>
		<th><?php _e('Email','event-o-matic');?></th>
		<th><?php _e('Created','event-o-matic');?></th></tr></thead>
		<tfoot><tr><th><?php _e('Name','event-o-matic');?></th>
		<th><?php _e('Email','event-o-matic');?></th>
		<th><?php _e('Created','event-o-matic');?></th></tr></tfoot>
		<tbody>
		<?php if($total_count > 0):foreach($users as $value):?>
			<tr><td><?php echo get_avatar($value->email, '30');?> <?php echo esc_html($value->name);?></td>
			<td><?php echo esc_html($value->email);?></td>
			<td><?php echo date('n / j / Y',strtotime($value->timestamp));?></td></tr>
		<?php endforeach;else:?>
			<tr class="no-items"><td class="colspanchange" colspan="3"><?php _e('No users found','event-o-matic');?></td></tr>
		<?php endif; ?>
		</tbody></table>
		<div class="tablenav"><div class='tablenav-pages'><?php echo $p->show(); ?></div></div>
	
	</form>
	</div>
	<?php
}


/**
 * Display page of venues
 *
 */
public function venues(){
	if (!current_user_can('manage_options')){
		wp_die( __('You do not have sufficient permissions to access this page.') );
    }
	$venue = new Venue();
	$p = new pagination;
	if(!empty($_POST['s'])){
		$total_count = $venue->get_all(array('count'=>true, 'like'=> $_POST['s']));
	}else{$total_count = $venue->get_all(array('count'=>true));}
	$p->items($total_count); //get all records as count
	$p->limit(50); // Limit entries per page
	$p->target('?page=eom-venues');
	if(isset($_GET['p'])){$p->currentPage($_GET['p']);}
	$p->calculate(); // Calculates what to show
	$p->parameterName('p');
	if(!isset($_GET['p'])) {$p->page=1;}else{$p->page=$_GET['p'];}
	$limit = ($p->page - 1) * $p->limit.", ".$p->limit; //Query for limit paging
	if(isset($_POST['s'])){
		$venues = $venue->get_all(array('order'=>'name','limit'=>$limit, 'like'=> $_POST['s']));
	}else{
		$venues = $venue->get_all(array('order'=>'name','limit'=>$limit));
	}?>
	<div class="wrap"><div id="icon-eom" class="icon32"></div>
		<h2><?php _e( 'Venues', 'event-o-matic' );?> <a href="?page=eom-venue" class="add-new-h2"><?php _e('Add New','event-o-matic');?></a>
		<?php if ( ! empty( $_REQUEST['s'] ) )
		printf( '<span class="subtitle">' . __('Search results for &#8220;%s&#8221;') . '</span>', esc_html($_POST['s']) ); ?>
		</h2>
		<ul class='subsubsub'><li class='all'>
			<a href='?page=eom-venues' class="current">All <span class="count">(<?php echo $total_count;?>)</span></a>
		</li></ul>
		<form id="venues-filter" action="" method="post"><p class="search-box">
		<label class="screen-reader-text" for="venue-search-input"><?php _e('Search Venues:','event-o-matic')?></label>
		<input type="search" id="venue-search-input" name="s" value="<?php if(isset($_POST['s'])){echo esc_html($_POST['s']);}?>" />
		<input type="submit" name="" id="search-submit" class="button" value="<?php _e('Search Venues','event-o-matic');?>"  /></p>
    	
		<div class="tablenav top"><div class='tablenav-pages'>
			<span class="displaying-num"><?php printf(_n('%d venue', '%d venues', $total_count), $total_count);?></span>
		<?php echo $p->show(); ?></div></div>
		
		<table class="widefat">
		<thead><tr><th><?php _e('Venue Name','event-o-matic');?></th>
		<th><?php _e('Address','event-o-matic');?></th>
		<th><?php _e('City','event-o-matic');?></th></tr></thead>
		<tfoot><tr><th><?php _e('Venue Name','event-o-matic');?></th>
		<th><?php _e('Address','event-o-matic');?></th>
		<th><?php _e('City','event-o-matic');?></th></tr></tfoot>
		<tbody>
		<?php if($total_count > 0):foreach($venues as $val):?>
			<tr><td><a href="?page=eom-venue&id=<?php echo $val->id;?>"><?php echo esc_html($val->name);?></a></td>
			<td><?php echo esc_html($val->fullAddress);?></td><td><?php echo esc_html($val->city);?></td></tr>
		<?php endforeach; else: ?>
			<tr class="no-items"><td class="colspanchange" colspan="3"><?php _e('No venues listed.','event-o-matic');?></td></tr>
		<?php endif; ?>
		</tbody></table>
		<div class="tablenav"><div class='tablenav-pages'><?php echo $p->show(); ?></div></div></form>
	
	</div>
	<?php
	
}


/**
 * Display events list page
 *
 */
public function events(){
	if (!current_user_can('manage_options')){
		wp_die( __('You do not have sufficient permissions to access this page.') );
    }
	$event = new Event;
	$p = new pagination;
	$upcoming_count = $event->get_count(array('when'=>'upcoming'));
	$archive_count = $event->get_count(array('when'=>'archive'));
	$p->limit(40); // Limit entries per page
	if(isset($_GET['when'])){
		if($_GET['when'] == 'archive'){
			$p->items($archive_count); //get all records as count
			$p->target('?page=eom-events&when=archive');
			$when = 'archive';
		}
	}else{
		$p->items($upcoming_count); //get all records as count
		$p->target('?page=eom-events');
		$when = 'upcoming';
	}
	if(isset($_GET['p'])){$p->currentPage($_GET['p']);} 
	$p->calculate(); // Calculates what to show
	$p->parameterName('p');
	if(!isset($_GET['p'])) { $p->page=1; }else{ $p->page=$_GET['p']; }
	$limit = ($p->page - 1) * $p->limit.", ".$p->limit; //Query for limit paging
	
	if(!empty($_REQUEST['s'])){
		$events=$event->get_admin(array('order'=>'name','limit'=>$limit, 'when'=>$when, 'like'=> $_REQUEST['s']));
	}else{
		$events = $event->get_admin(array('limit'=>$limit,'when'=>$when,'order'=>'dateStart ASC')); 
	}?>
	<div class="wrap"><div id="icon-eom" class="icon32"></div>
	<h2><?php _e( 'Events', 'event-o-matic' );?> <a href="?page=eom-event" class="add-new-h2"><?php _e('Add New','event-o-matic');?></a>
	<?php if ( ! empty( $_REQUEST['s'] ) )
		printf( '<span class="subtitle">' . __('Search results for &#8220;%s&#8221;') . '</span>', esc_html($_REQUEST['s']) ); ?>
	</h2>
	
	<ul class="subsubsub">
	<li><a href="?page=eom-events" <?php echo ($when == 'upcoming') ? 'class="current"':'';?> ><?php _e('Upcoming','event-o-matic'); ?> <span class="count">(<?php echo $upcoming_count;?>)</span></a>
	</li>
	<li><a href='?page=eom-events&when=archive' <?php echo ( $when == 'archive') ? 'class="current"':'';?> ><?php _e('Archive','event-o-matic');?> <span class="count">(<?php echo $archive_count;?>)</span></a></li>
	</ul>
	
	<form id="events-filter" action="" method="post"><p class="search-box">
	<label class="screen-reader-text" for="event-search-input"><?php _e('Search Events:','event-o-matic');?></label>
	<input type="search" id="event-search-input" name="s" value="<?php if(isset($_POST['s'])){echo esc_attr($_POST['s']);}?>" />
	<input type="submit" name="" id="search-submit" class="button" value="<?php _e('Search Events','event-o-matic');?>" /></p>	
	
	<div class="tablenav"><div class='tablenav-pages'><?php echo $p->show(); ?></div></div>
	<table class="widefat">
	<thead><tr><th><?php _e('Event Name','event-o-matic');?></th>
	<th><?php _e('Venue','event-o-matic');?></th>
	<th><?php _e('Date','event-o-matic');?></th></tr></thead>
	<tfoot><tr><th><?php _e('Event Name','event-o-matic');?></th>
	<th><?php _e('Venue','event-o-matic');?></th>
	<th><?php _e('Date','event-o-matic');?></th></tr></tfoot>
	<tbody>
	<?php if($upcoming_count > 0): foreach($events as $event):?>
		<tr<?php if($event['status'] == 'P'):?> style="background:#CCC;" <?php endif;?> >
		<td><a href="?page=eom-event&id=<?php echo $event['id'];?>"><?php echo esc_html($event['name']);?></a></td>
		<td><?php echo esc_html($event['venueName']);?></td>
		<td><?php echo date("F jS Y - g:i a",strtotime($event['dateStart']));?></td>
		</tr>
	<?php endforeach; else: ?>
		<tr class="no-items"><td class="colspanchange" colspan="3"><?php _e('No events listed. What are you waiting for?','event-o-matic');?> <a href="?page=eom-event"><?php _e('Make some events!','event-o-matic');?></a></td></tr>
	<?php endif; ?>
	</tbody></table>
	
	<div class="tablenav"><div class='tablenav-pages'><?php echo $p->show(); ?></div></div>
	</form>
	<?php $this->html_events(); ?>
	</div>
	<?php
}


/**
 * Display html code of upcoming events
 *
 */
public function html_events(){
	$event = new Event;
	$html = $event->getAll(array('when'=>'upcoming','status'=>$event->statusCode['approved'],'order'=>'dateEnd'));
	if($html):
		$out ='';
		foreach ($html as $ev):
			$out .='<div>';
			//if( $ev['image'] ){ $out .= '<img src="'.esc_url($ev['image']).'" width="200" >';}
			if(get_option('eom_events_url')){ 
				$out.='<h2><a href="'.get_option('eom_events_url').'?id='.$ev['id'].'&vid='.$ev['venueId'].'">'.esc_html($ev['name']).'</a></h2>';
			}else{$out.='<h2>'.esc_html($ev['name']).'</h2>';}
			$out.='<p><strong>'.date("F jS Y, g:i a",strtotime($ev['dateStart'])).' at '.esc_html($ev['venueName']).'</strong></p>';
		//$out .= '<p>'.$ev['description'].'</p>';
		$out .= '</div>';
		endforeach; ?>
		<h3><?php _e('Event HTML Code', 'event-o-matic');?></h3>
		<p><?php _e('Copy the code below to use in online newsletters and email campaigns.','event-o-matic');?></p>
		<textarea name="event" cols="100" rows="20" readonly="readonly" ><?php echo esc_textarea($out); ?></textarea>	
	<?php endif;
	
}



/**
 * Create wordpress admin menu
 * 
 */
public function menu() {
	add_menu_page(__('Event-O-Matic', 'event-o-matic'), __('Event-O-Matic', 'event-o-matic'), 'manage_options', 'event-o-matic', array($this, 'general'), plugin_dir_url( __FILE__ ).'/img/eom_menu.png');
	add_submenu_page( 'event-o-matic', __('Add Venue', 'event-o-matic'), __('Add Venue', 'event-o-matic'), 'manage_options', 'eom-venue', array($this, 'venue'));
	add_submenu_page( 'event-o-matic', __('Add Event', 'event-o-matic'), __('Add Event', 'event-o-matic'), 'manage_options', 'eom-event', array($this, 'event'));
	add_submenu_page( 'event-o-matic', __('Events', 'event-o-matic'), __('Events', 'event-o-matic'), 'manage_options', 'eom-events', array($this, 'events'));
	add_submenu_page( 'event-o-matic', __('Venues', 'event-o-matic'), __('Venues', 'event-o-matic'), 'manage_options', 'eom-venues', array($this, 'venues'));
	add_submenu_page( 'event-o-matic', __('Users', 'event-o-matic'), __('Users', 'event-o-matic'), 'manage_options', 'eom-users', array($this, 'users'));
}



/**
 * Hook wordpress admin dashboard
 *
 */
public function add_dashboard(){
	wp_add_dashboard_widget('eom_dashboard', 'Event-O-Matic', array($this,'dashboard'));
} 



/**
 * Display wordpress admin dashboard pane
 *
 */
public function dashboard() {
	$event = new Event();
	$events_pending=$event->getAll(array('status'=>$event->statusCode['pending'],'order'=>'dateStart'));
	$events_upcoming=$event->getAll(array('when'=>'upcoming','status'=>$event->statusCode['approved'],'order'=>'dateStart','limit'=>10 ));?>
	<p><?php _e('The Event-O-Matic event submission system is collecting events from your users.','event-o-matic');?></p>
	<p><a class="button-secondary" href="admin.php?page=event-o-matic"><?php _e('Event-O-Matic Settings','event-o-matic');?></a></p>
	<?php if(count($events_pending) > 0):?>
		<p><strong><?php _e('Events Pending Approval','event-o-matic');?></strong></p>
		<ul><?php foreach ($events_pending as $value):?>
			<li><strong><a href="admin.php?page=eom-event&id=<?php echo $value['id'];?>"><?php echo esc_html($value['name']);?></a></strong> at <?php echo esc_html($value['venueName']);?> - <?php echo date("F j, g:i a",strtotime($value['dateStart'])) ?></li>
		<?php endforeach;?></ul>
	<?php endif;
	if(count($events_upcoming)):?>
		<p><strong><?php _e('Upcoming Events','event-o-matic');?></strong></p>
		<ul><?php foreach ($events_upcoming as $value):?>
			<li><strong><a href="admin.php?page=eom-event&id=<?php echo $value['id'];?>"><?php echo esc_html($value['name']);?></a></strong> at <?php echo esc_html($value['venueName']);?> - <?php echo date("F j, g:i a",strtotime($value['dateStart']));?></li>
		<?php endforeach;?></ul>
	<?php else: ?>
		<p><strong><?php _e('No Upcoming Events.','event-o-matic');?></strong></p>
	<?php endif;
}


/**
 * delete event images
 *
 */
function delete_image($event_id){ 
	$args = array(
		'post_status' => 'inherit',
		'post_type' => 'attachment',
		'meta_query' => array(array(
			'key' => 'eom_event_id',
			'value' => $event_id,
				
	)));
	$query = new WP_Query( $args );
	if(isset($query->post->ID)){
		if($post_thumbnail_id = get_post_thumbnail_id($query->post->ID)){  
			wp_delete_attachment($post_thumbnail_id);
		}
		wp_trash_post($query->post->ID);  
	}
}  



/**
 * Display getting on admin pages
 *
 */
protected function greeting(){ ?>
	<div style="border:1px solid blue;background:#CDD8EA;margin:10px 0;padding:0px 8px;"><p>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="width:125px;float:right;">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="63XH9FAX72LRU">
	<input type="image" src="https://www.paypalobjects.com/WEBSCR-640-20110401-1/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110401-1/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>
	<h3><?php _e('Event-O-Matic Gets Better!','event-o-matic'); ?></h3>
		File upload capability added. Your users can easily upload their event posters and images without hassle.<br /> 
		Do you like Event-O-Matic? <a href="http://wordpress.org/support/view/plugin-reviews/event-o-matic" target="_blank">Write an awesome review!</a> Having problems? <a href="http://wordpress.org/support/plugin/event-o-matic" target="_blank">Let me know!</a>
		</p>
	</div><?php
	}


} //END class Event_O_Matic_Admin


//CREATE OBJECT
if (class_exists("Event_O_Matic_Admin")) {$eomAdminObject = new Event_O_Matic_Admin();}


/**
 * admin hooks and shortcodes
 *
 */
if (isset($eomAdminObject)) {
	add_action('admin_menu', array($eomAdminObject, 'menu'));
	add_action('wp_dashboard_setup', array($eomAdminObject, 'add_dashboard'));
	add_action('admin_print_scripts', array($eomAdminObject, 'js'));
	add_action('admin_print_styles', array($eomAdminObject, 'css'));
	add_action('admin_head', array($eomAdminObject, 'head'));
}

?>