<?php
	global $wpdb;
	$table_name = $wpdb->prefix . HC_RSE_TABLE_NAME;

	if(isset($_GET['delete_id'] ) && is_numeric( $_GET['delete_id'] ) ){
		$wpdb->query( $wpdb->prepare( "DELETE FROM $table_name WHERE id=%d", $_GET['delete_id'] ) );
		?>
		<script type="text/javascript">
			window.location = "<?php bloginfo( 'url' ); ?>/wp-admin/admin.php?page=hc_rse_event&msg=deleted";
		</script>
		<?php
		exit();
	}

	$upcoming_events = $wpdb->get_results( "SELECT * FROM $table_name WHERE start_date >= NOW() ORDER BY start_date ASC" );
	$past_events = $wpdb->get_results( "SELECT * FROM $table_name WHERE start_date < NOW() ORDER BY start_date DESC" );

	/**
	 * Given a WPDB result set for the events table, prints the events table body
	 * @param Object $events - WPDB result set
	 */
	function hs_rse_print_event_rows( $events = array() ){


		//If there's nothing to print, exit function
		if( ! is_array( $events ) || count( $events ) == 0 ) return;

		foreach( $events as $event ): ?>
			<tr>
				<td>
					<?php echo date( get_site_option( 'hc_rse_date_format' ) , strtotime( $event->start_date ) ); ?> - <?php echo date( get_site_option( 'hc_rse_time_format' ) , strtotime( $event->start_date ) ); ?>
				</td>
				<td>
					<?php echo stripslashes($event->title); ?>
					<section class="hidden">
						<?php echo apply_filters( 'the_content' , stripslashes( $event->extra_info ) ); ?>
					</section>
				</td>
				<td class="actions">
					<a href="<?php bloginfo( 'url' ); ?>/wp-admin/admin.php?page=hc_rse_add_event&edit_id=<?php echo $event->id; ?>"><?php _e( 'Edit' , 'hc_rse' ); ?></a>&nbsp;&nbsp;|&nbsp;
					<a class="hc_rse_delete" href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=hc_rse_event&delete_id=<?php echo $event->id; ?>"><?php _e( 'Delete' , 'hc_rse' ); ?></a>
				</td>
			</tr>
		<?php endforeach;
	}
?>
<div class="wrap">
	<h2 id="page-title"><?php _e( 'Events (Upcoming)' , 'hc_rse' ); ?></h2>
	<div class="updated hidden" id="msgbox">
		<p>
			<strong><?php _e( 'Event Deleted' , 'hc_rse' ); ?></strong>
		</p>
	</div>
	<?php if( $past_events ): ?>
		<div id="table-switcher">
			<?php _e( 'Upcoming Events' , 'hc_rse' ); ?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#past-events"><?php _e( 'Past' , 'hc_rse' ); ?></a>
		</div>
	<?php endif; ?>
	<?php if( $upcoming_events ): ?>
		<table id="upcoming-events" class="wp-list-table widefat fixed">
			<thead>
				<tr>
					<th><?php _e( 'Date' , 'hc_rse' ); ?></th><th><?php _e( 'Title' , 'hc_rse' ); ?></th><th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php hs_rse_print_event_rows( $upcoming_events ); ?>
			</tbody>
		</table>
	<?php elseif( $past_events ): ?>
		<p id="no-upcoming"><?php _e( 'No upcoming events to show, go and ' , 'hc_rse' ); ?> <a href="<?php bloginfo( 'url' ); ?>/wp-admin/admin.php?page=hc_rse_add_event"><?php _e( 'add one' , 'hc_rse'); ?></a>.
	<?php endif; ?>


	<p id="no-events-mgs" <?php if($past_events || $upcoming_events) echo 'class="hidden"';?>><?php _e( 'No events to show, go and ' , 'hc_rse' ); ?> <a href="<?php bloginfo( 'url' ); ?>/wp-admin/admin.php?page=hc_rse_add_event"><?php _e( 'add one' , 'hc_rse' ); ?></a>.


	<?php if( $past_events ): ?>
		<table id="past-events" class="wp-list-table widefat fixed hidden">
			<thead>
				<tr>
					<th><?php _e( 'Date' , 'hc_rse' ); ?></th><th><?php _e( 'Title' , 'hc_rse' ); ?></th><th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php hs_rse_print_event_rows( $past_events ); ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>

