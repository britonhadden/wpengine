<?php 


/**
 * Hook admin bar, display only if events are in need of approval
 * 
 */
function eom_admin_bar_init() {
	// Is the user sufficiently leveled, or has the bar been disabled?
	if (!is_super_admin() || !is_admin_bar_showing() ){ return; }
	add_action('admin_bar_menu', 'eom_admin_bar_links', 500);
}
add_action('admin_bar_init', 'eom_admin_bar_init'); 


/**
 * Create admin bar
 * 
 */
function eom_admin_bar_links() {
	//are there pending events?
 	$event = new Event();
	$events_pending = $event->get_count(array('status'=>$event->statusCode['pending']));
	if($events_pending){ //only create the admin bar if pending events exist
		global $wp_admin_bar;
		$admin_link = get_bloginfo('url').'/wp-admin/admin.php?page=';
		$main_link = array(
			'title' => 'Event-O-Matic ('.$events_pending.')',
			'href' => $admin_link.'event-o-matic',
			'id' => 'eom_links'
		);
		$wp_admin_bar->add_menu($main_link);
		$links = array(
			'Events' => $admin_link.'eom-events',
			'Venues' => $admin_link.'eom-venues',
			'Users' => $admin_link.'eom-users',
		);
		//drop them into the submenu
		foreach ($links as $label => $url){
			$wp_admin_bar->add_menu( array(
				'id' => strtolower($label),
				'title' => $label,
				'href' => $url,
				'parent' => 'eom_links',
			));
		}
	}
}


?>