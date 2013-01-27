<?php
/*
Plugin Name: Really Simple Events
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Simple event module, just a title and start date/time needed!  You can, of course, provide extra information about the event if you wish.  This plugin was created for a bands/performers who do one off shows lasting a couple of hours rather than a few days, so event date ranges, custom post type and so on are not included.
Version: 1.2.8
Author: Huntly Cameron
Author URI: http://www.huntlycameron.co.uk
License: GPL2
*/
////////////////////////////////////////////////////////////////////////////////
/*  Copyright 2012 Huntly Cameron (email : huntly.cameron@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
////////////////////////////////////////////////////////////////////////////////


//Let's go!
load_plugin_textdomain('hc_rse', '', 'really-simple-events/translations');
/*
 * Following install update code based heavily on the examples provided
 * in the wordpress codex:
 * http://codex.wordpress.org/Creating_Tables_with_Plugins
 */
global $hc_rse_db_version;
$hc_rse_db_version = "1.2";

define( 'HC_RSE_TABLE_NAME' , 'reallysimpleevents' );

//When the plugin is activated, install the database
register_activation_hook( __FILE__ , 'hc_rse_plugin_install' );

//When the plugin is loaded, check for DB updates and first run
add_action( 'plugins_loaded' , 'hc_rse_update_db_check' );
add_action( 'plugins_loaded' , 'hc_rse_first_run_check' );
add_action( 'admin_menu' , 'hc_rse_build_admin_menu' );
add_action( 'admin_init' , 'hc_rse_setup_custom_assets' );

//Add Sidebar widget

//Create a sidebar widget
function widget_hc_rse_event_widget($args) {
    global $wpdb;
    extract($args);
	$showevents = '';
	$table_name = $wpdb->prefix . HC_RSE_TABLE_NAME;

	//By default include the custom CSS and JS

	wp_enqueue_style( "hc_rse_styles" ,
					  plugin_dir_url( __FILE__ ) . "style.css" );
	wp_enqueue_script( "hc_rse_event_table" ,
					   plugin_dir_url( __FILE__ ) . "js/event-table.js" ,
					   array( 'jquery' ) ,
					   '1' ,
					   true );
	wp_localize_script( "hc_rse_event_table" ,
					    'objectL10n' ,
					    array( 'MoreInfo' => __( 'More Info' , 'hc_rse' ),
						 	   'HideInfo' => __( 'Hide Info' , 'hc_rse' )
							     )
					  );

	$eventQuery = "SELECT * FROM $table_name WHERE start_date >= NOW() ORDER BY start_date ASC";
	$upcoming_events = $wpdb->get_results( $eventQuery );

	$eventHTML = "";

	if( $upcoming_events ){
		$eventHTML .= '<ul>';
		foreach($upcoming_events as $event){

			$eventHTML .= '    <li>';
			$eventHTML .=          date( get_option( 'hc_rse_date_format' ) ,
					                     strtotime( $event->start_date ) ) . ' - ' . stripslashes( $event->title );
			$eventHTML .= '    </li>';

		}
		$eventHTML .= '</ul>';
	}else{
		$eventHTML = __("No Events", 'hc_rse');
	}



    echo $before_widget;
    echo $before_title . __('Upcoming Events', 'hc_rse') . $after_title;
    echo $eventHTML;
    echo $after_widget;

}
wp_register_sidebar_widget('HC_RSE_EVENT_WIDGET', 'Upcoming Events', 'widget_hc_rse_event_widget', array(                  // options
        'description' => __('Shows upcoming events', 'hc_rse')
    ));

add_shortcode( 'hc_rse_events' , 'hc_rse_display_events' );
/**
 * Parses the shortcode and displays the events.  The defalt is to only show
 * events which are happening from the current time onwards.  To change this
 * the user can suppy the 'showevents' attribute with one of the following
 * values:
 *
 * 'all' - past AND upcoming events will be displayed
 * 'past' - past events only will be displayed
 * 'upcoming' only upcoming events will be displayed (the default action)
 *
 * For advanced useers there's also the 'noassets' attribute which when set to
 * 'true' will not include the custom js and css to make the showing and hiding
 * of the extra info work.
 *
 * @global type $wpdb
 * @param type $attibutes
 * @return string
 */
function hc_rse_display_events( $attibutes ){
	global $wpdb;
	$showevents = '';
	$noassets = '';
	$columns = '';
	extract( shortcode_atts( array( 'showevents' => 'upcoming' ,
		                            'noassets' => 'false',
		                            'columns' => 'date,time,title,moreinfo'
		                          ) ,
			                 $attibutes
			               )
		   );

	$table_name = $wpdb->prefix . HC_RSE_TABLE_NAME;

	//By default include the custom CSS and JS
	if($noassets == 'false'){
		wp_enqueue_style( "hc_rse_styles" ,
						  plugin_dir_url( __FILE__ ) . "style.css" );
		wp_enqueue_script( "hc_rse_event_table" ,
						   plugin_dir_url( __FILE__ ) . "js/event-table.js" ,
						   array( 'jquery' ) ,
						   '1' ,
						   true );
		wp_localize_script( "hc_rse_event_table" ,
						    'objectL10n' ,
						    array( 'MoreInfo' => __( 'More Info' , 'hc_rse' ),
							 	   'HideInfo' => __( 'Hide Info' , 'hc_rse' )
 							     )
						  );
	}

	switch($showevents){
		case 'all':
			$eventQuery = "SELECT * FROM $table_name ORDER BY start_date ASC";
			break;
		case 'past':
			$eventQuery = "SELECT * FROM $table_name WHERE start_date < NOW() ORDER BY start_date DESC";
			break;
		default:
			$eventQuery = "SELECT * FROM $table_name WHERE start_date >= NOW() ORDER BY start_date ASC";
			break;
	}

	//If the user has passed something into the columns argument, use it
	$useDefaultColumns = false;

	if($columns !== ''){
		//If we've got a list of cols, split them out
		if(mb_strpos($columns, ',') === false){
			//We might only have one column to show, make sure it's valid
			if(!in_array($columns, array('date','time','title','moreinfo'))){
				$useDefaultColumns = true;
			}
		}elseif(mb_strpos($columns, ',') !== 0){
			$columns = explode(',', $columns);
		}else{
			$useDefaultColumns = true;
		}
	}else{ //Default to showing everything
		$useDefaultColumns = true;
	}

	//Something's not right, just show the defaults
	if($useDefaultColumns){
		$columns = explode(',', 'date,time,title,moreinfo');
	}

	$upcoming_events = $wpdb->get_results( $eventQuery );

	$eventHTML = "";

	if( $upcoming_events ){
		$eventHTML .= '<table class="hc_rse_events_table">';
		$isShowingATime = false;

		//Loop through and see if we're showing a time
		foreach($upcoming_events as $event){
			foreach($columns as $column){
				if($column == 'time' && $event->show_time == 1){
					$isShowingATime = true;
				}
			}
		}

		foreach($upcoming_events as $event){
			//Show only the relevent columns
			$showMoreInfo = false;
			$eventHTML .= '<tr>';
			foreach($columns as $column) {
				switch($column){
					case 'date':
						$eventHTML .= '    <td class="hc_rse_date">';
						$eventHTML .=          date( get_option( 'hc_rse_date_format' ) ,
								                     strtotime( $event->start_date ) );
						$eventHTML .= '    </td>';
						break;
					case 'time':
						//Add column if we're showing a time
						if($isShowingATime) $eventHTML .= '    <td class="hc_rse_time">';
						//Only show time if it has been set in the event settings
						if($event->show_time == 1){

							$eventHTML .=          date( get_option( 'hc_rse_time_format' ) ,
									                     strtotime( $event->start_date ) );
						}
						//close column if we're showing a time
						if($isShowingATime) $eventHTML .= '    </td>';
						break;
					case 'title':
						$eventHTML .= '    <td class="hc_rse_title">';
						$eventHTML .=          stripslashes( $event->title );
						$eventHTML .= '    </td>';
						break;
					case 'moreinfo':
						$showMoreInfo = true;
						$eventHTML .= '    <td>';
						$eventHTML .=          ($event->extra_info != "") ? '<a id="' . $showevents . '_more_' . $event->id . '" class="hc_rse_more_info" href="#more">' . __('More Info', 'hc_rse') . '</a>': '&nbsp';
						$eventHTML .= '    </td>';
						break;
				}
			}
			//Add the info if we're showing it...
			if($showMoreInfo){
				$eventHTML .= '</tr>';
				$eventHTML .= '<tr>';
				$eventHTML .= '    <td colspan="4" id="hc_rse_extra_info_' . $showevents . '_' . $event->id . '" class="hc_rse_extra_info hidden">';
				$eventHTML .=          apply_filters( 'the_content' , stripslashes($event->extra_info ) );
				$eventHTML .= '    </td>';
			}
			$eventHTML .= '</tr>';
		}
		$eventHTML .= '</table>';
	}
	return $eventHTML;
}



function hc_rse_setup_custom_assets(){
	wp_enqueue_style( "hc_rse_styles" ,
			          plugin_dir_url( __FILE__ ) . "style.css" );
	wp_enqueue_style( "jquery-ui-custom" ,
			          plugin_dir_url( __FILE__ ) . "css/jquery-ui-1.8.22.custom.css" );
	wp_enqueue_script("time-picker-addon" ,
			          plugin_dir_url( __FILE__ ) . "js/jquery-ui-timepicker-addon.js" ,
			          array( 'jquery' ,
						     'jquery-ui-core' ,
						     'jquery-ui-slider' ,
						     'jquery-ui-datepicker'
 						   ) ,
			          '1' ,
			          true);
	wp_enqueue_script("hc_rse_js" ,
			          plugin_dir_url( __FILE__ ) . "js/script.js" ,
			          array( 'jquery' ,
						     'jquery-ui-core' ,
						     'jquery-ui-datepicker' ,
						     'time-picker-addon'
 						   ) ,
			          '1' ,
			          true);
	wp_localize_script("hc_rse_js" ,
			           'objectL10n' ,
					   array('UpcomingEvents' => __('Upcoming Events', 'hc_rse'),
						     'EventsUpcoming' => __('Events (Upcoming)', 'hc_rse'),
						     'PastEvents' => __('Past Events', 'hc_rse'),
						     'EventsPast' => __('Events (Past)', 'hc_rse'),
 						     'DeleteConfirm' => __('Are you sure you want to delete this event?', 'hc_rse'))
			          );

	wp_enqueue_script("hc_rse_options_js" ,
			          plugin_dir_url( __FILE__ ) . "js/options.js" ,
			          array( 'jquery' ) ,
			          '1' ,
			          true);
}

/**
 * Checks if we need to update the db schema
 *
 * If the site_option value doesn't match the version defined at the top of
 * this file, the install routine is run.
 *
 * @global string $hc_rse_db_version
 */
function hc_rse_update_db_check() {
	global $hc_rse_db_version;
	if ( get_site_option( 'hc_rse_db_version' ) != $hc_rse_db_version ) {
		hc_rse_plugin_install();
	}
}

/**
 * Checks for first run
 */
function hc_rse_first_run_check(){
	if ( get_site_option( 'hc_rse_first_run' , 'fasly' ) === 'fasly' ) {
		//Set site option so show we've run this plugin at least once.
		add_site_option( 'hc_rse_first_run' , 'woot' );
	}
}

/**
 * Creates the db schema
 *
 * @global type $wpdb
 * @global string $hc_rse_db_version
 */
function hc_rse_plugin_install(){
	global $wpdb;
	global $hc_rse_db_version;
	$table_name = $wpdb->prefix . HC_RSE_TABLE_NAME;
	$sql = "CREATE TABLE $table_name (
		    id mediumint(9) NOT NULL AUTO_INCREMENT,
		    start_date TIMESTAMP DEFAULT NOW() NOT NULL,
		    show_time int(1),
		    title varchar(255) NOT NULL,
		    extra_info text,
		    UNIQUE KEY id (id)
		    );";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'hc_rse_db_version' , $hc_rse_db_version );
	add_option( 'hc_rse_date_format' , 'jS M Y' );
	add_option( 'hc_rse_time_format' , 'H:i' );
}

////////////////////  ADMIN MENU FUNCTIONALITY ////////////////////////////////
function hc_rse_build_admin_menu(){
	$user_capability = 'manage_options';
	$menu_position = 21; //just below Pages menu option


	//Add Events main admin menu page
	add_menu_page( __( 'Events' , 'hc_rse' ) ,
		           __( 'Events' , 'hc_rse' ) ,
		           $user_capability ,
		           'hc_rse_event' ,
		           'hc_rse_events' ,
		           plugins_url( 'images/icon.png' , __FILE__ ) ,
		           $menu_position
		         );

	//Add view events page to main admin menu.
	add_submenu_page( 'hc_rse_event' ,
		              __( 'View Events' , 'hc_rse' ) ,
		              __( 'All Events' , 'hc_rse' ) ,
		              $user_capability ,
		              'hc_rse_event',
		              'hc_rse_events'
		            );

	//The add event page to main admin menu.
	add_submenu_page( 'hc_rse_event' ,
		              __( 'Add Event' , 'hc_rse') ,
		              __( 'Add New' , 'hc_rse' ) ,
		              $user_capability ,
		              'hc_rse_add_event' ,
		              'hc_rse_add_event'
		            );
	add_submenu_page( 'hc_rse_event' ,
		              __( 'Events Settings' , 'hc_rse' ) ,
		              __( 'Settings' , 'hc_rse' ) ,
		              $user_capability ,
		              'hc_rse_settings' ,
		              'hc_rse_settings'
		            );
	add_submenu_page( 'hc_rse_event' ,
		              __( 'Help/Usage' , 'hc_rse' ) ,
		              __( 'Help/Usage' , 'hc_rse' ) ,
		              $user_capability ,
		              'hc_rse_help' ,
		              'hc_rse_help'
		            );
}

/**
 * Shows all upcoming events
 */
function hc_rse_events(){
	require_once plugin_dir_path( __FILE__ ) . 'admin/view_events.php';
}

function hc_rse_add_event(){
	require_once plugin_dir_path( __FILE__ ) . 'admin/add_event.php';
}

function hc_rse_settings(){
	require_once plugin_dir_path( __FILE__ ) . 'admin/options.php';
}

function hc_rse_help(){
	require_once plugin_dir_path( __FILE__ ) . 'admin/help.php';
}
