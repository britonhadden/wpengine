<?php
	global $wpdb;

	$pageURL = get_bloginfo( 'url' ) . '/wp-admin/admin.php?page=hc_rse_add_event';

	//Event variable
	$title = "";
	$startDate = "";
	$showTime = 0;
	$extraInfo = "";

	$table_name = $wpdb->prefix . HC_RSE_TABLE_NAME;
	$dateFormatPattern = "#(\d{4})-(\d{2})-(\d{2})\s(\d{2})\:(\d{2})#";
	$errorMsg = "";
	$updateMsg = "";

	//If editing get values from database
	if( isset( $_GET['edit_id'] ) && is_numeric( $_GET['edit_id'] ) ){
		$pageURL .= "&edit_id=" . $_GET['edit_id'];
		$event = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id=%d" , $_GET['edit_id'] ) );
		$title = $event->title;
		$startDate = date( 'Y-m-d H:i' , strtotime( $event->start_date ) );
		$showTime = $event->show_time;
		$extraInfo = $event->extra_info;
	}

	//Check post for other variables
	if( isset($_POST['formsubmit']) && $_POST['formsubmit'] == 1 ){
		//Setup vars from post
		if( isset( $_POST['title'] ) ) $title = $_POST['title'];
		if( isset( $_POST['start_date'] ) ) $startDate = $_POST['start_date'];
		$showTime = (isset( $_POST['show_time'] ) && $_POST['show_time'] === "on" ) ? 1 : 0;
		if( isset( $_POST['extra_info'] ) ) $extraInfo = $_POST['extra_info'];


		if( $title === "" ) $errorMsg .= __( 'Please enter a title' , 'hc_rse' ) . '<br/>';
		if(  ! preg_match( $dateFormatPattern , $startDate ) ) $errorMsg .= __( 'Date/Time should be in the following format: yyyy-mm-dd HH:MM' , 'hc_rse' ) . '<br/>';

		//If all is valid, add to our database
		if( $errorMsg === "" ){
			$tableCols = array(
							    "title" => $title ,
							    "start_date" => $startDate ,
							    "show_time" => $showTime ,
							    "extra_info" => $extraInfo
							  );

			if( isset( $_GET['edit_id'] ) && is_numeric( $_GET['edit_id'] ) ){
				$isInserted = $wpdb->update( $table_name ,
						                     $tableCols ,
											 array('ID' => $_GET['edit_id'])
										   );
			}else{ //new record
				$isInserted = $wpdb->insert( $table_name , $tableCols );

				//Horrible way to redirect! @TODO fix this rubbish...
				?>
				<script type="text/javascript">
					window.location="<?php echo $pageURL . "&edit_id=" . $wpdb->insert_id . '&msg=added'?>";
				</script>
				<?php
				exit();
			}

			if( ! $isInserted ){
				$errorMsg .= __( 'Could not create event! HELP!!' , 'hc_rse' );
			}else{
				$updateMsg .= __( 'Event Updated: ' , 'hc_rse' ) . stripslashes($title);
			}
		}
	}
?>
<div class="wrap">
	<h2><?php echo ( isset( $_GET['edit_id'] ) ) ?  __( 'Edit Event' , 'hc_rse' ) :  __( 'Add Event' , 'hc_rse' ); ?></h2>
	<?php if( $errorMsg != "" ): ?>
		<div class="error">
			<p>
				<?php echo $errorMsg; ?>
			</p>
		</div>
	<?php endif; ?>

	<?php if(isset($_GET['msg']) || $updateMsg != ""): ?>
		<div class="updated">
			<p>
				<strong><?php echo ( isset( $_GET['msg'] ) && $_GET['msg'] == 'added' ) ?  __( 'Event Added: ' , 'hc_rse' ) . stripslashes($title) : $updateMsg; ?></strong>
			</p>
		</div>
	<?php endif; ?>

	<form method="post" action="">
		<input type="hidden" name="formsubmit" value="1"/>
		<?php if( isset( $_GET['edit_id'] ) && is_numeric( $_GET['edit_id'] ) ): ?>
			<input type="hidden" name="edit" value="1"/>
		<?php endif; ?>
		<table id="past-events" class="form-table">
			<tbody>
				<tr>
					<th><label for="title"><?php _e( 'Event Title' , 'hc_rse'); ?></label></th>
					<td><input class="regular-text ltr" type="text" id="title" name="title" value="<?php echo stripslashes( $title ) ?>"/></td>
				</tr>
				<tr>
					<th><label for="start_date"><?php _e( 'Event Date/Time' , 'hc_rse'); ?></label></th>
					<td>
						<input class="regular-text ltr date_pick" type="text" id="start_date" name="start_date" value="<?php echo $startDate; ?>"/>
					</td>
				</tr>
				<tr>
					<th><label for="show_time"><?php _e( 'Show Event Time?' , 'hc_rse'); ?></label></th>
					<td>
						<input class="" type="checkbox" id="show_time" name="show_time" <?php echo ($showTime == 1) ? 'checked="checked"' : ''; ?>/>
						<p class="description"><?php _e( '(Keep un-checked if you are just concerned with dates and do not wish to show the time value for this event)' , 'hc_rse' ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="extra_info"><?php _e( 'Extra Event Info' , 'hc_rse'); ?></label></th>
					<td>
						<?php wp_editor( stripslashes( $extraInfo ) , 'extra_info' , array( 'media_buttons' => true ) ); ?>
					</td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" class="button-primary" value="<?php echo ( isset($_GET['edit_id'] ) && is_numeric( $_GET['edit_id'] ) ) ?  __( 'Update Event' , 'hc_rse' ) : __( 'Add Event' , 'hc_rse' ); ?>"/>
				</tr>
			</tbody>
		</table>
	</form>
</div>

