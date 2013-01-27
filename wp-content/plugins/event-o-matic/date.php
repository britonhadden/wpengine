<?php


/**
 * @class Date
 *
 */
class Date{

public $table;
public $id;
public $eventId;

public $start; //event start date and time saved in unix timestamp
public $end; //end saved in unix timestamp

public $error = array();


/**
 * @method constructor
 */
function __construct(){
	global $wpdb;
	$this->table = $wpdb->prefix."eom_dates";
	$this->start = time();
	$this->end = time();
}


/**
 * @method put
 * save data to object
 *
 * @param array $data - date and time data
 * 
 * @return true on save, false on fail
 */
public function put($data){
	if(isset($data['eventId'])){$this->eventId = (int) $data['eventId'];}
	if(isset($data['date_start'], $data['time_start'], $data['date_end'], $data['time_end'])){
		$start = strtotime($data['date_start'].' '.$data['time_start']);
		$end = strtotime($data['date_end'].' '.$data['time_end']);
		if($start >= $end){
			$this->error[] = __('Start date cannot occur after end date.','event-o-matic');
			return false;
		}
		$this->start = $start;
		$this->end = $end;
	}
}

/**
 * @method save
 * save to database
 *
 * @return true, false on failure
 */
public function save(){
	global $wpdb;
	$id = $wpdb->get_var($wpdb->prepare("SELECT id FROM ".$this->table." WHERE event_id = %d", $this->eventId));
	//prepare dates for database format: timestamp to yyyy-mm-dd hh:mm:ss
	$start = date('Y-m-d H:i:s',$this->start); 
	$end = date('Y-m-d H:i:s', $this->end);
	if($id){ // update
		$data = array( 'start'=>$start, 'end'=>$end );
		$type = array('%s','%s');
		$wpdb->update( $this->table, $data, array('id'=>$id), $type, '%d');
	}else{ //insert
		$data = array('start'=>$start, 'end'=>$end, 'event_id'=>$this->eventId);
		$type = array('%s','%s','%d');
		$wpdb->insert($this->table, $data, $type);
	}
}


/**
 * Get dates from database, save to object
 *
 * @param int $event_id
 * @return false on failure
 */
public function get($event_id){
	global $wpdb;
	$sql=$wpdb->prepare("SELECT * FROM ".$this->table." WHERE event_id = %s", $event_id);
	if($return = $wpdb->get_row($sql)){
		$this->id = $return->id;
		$this->start = strtotime($return->start);
		$this->end = strtotime($return->end);
		return true;
	}else{return false;}
}


/**
 * Create time selection form
 *
 * @param string name - name of field
 * @param int $val - selected value of date as unix timestamp
 * @return time field
 */
public function select_time($name, $val){
	$form = '<select name="'.$name.'" >';
	for ($i = 0; $i < 24; $i++){
		$osel='';
		$hsel='';
		$time = date('G:i',$val);
		if(($i.':00')==$time){$osel='selected="selected"';}
		if(($i.':30')==$time){$hsel='selected="selected"';}
		$j=$i;
		if($i==0){$j=12;$ap='am';}
		if($i>12){$j=$j-12;$ap='pm';}
		if($i==12){$ap='pm';}
		$form.='<option value="'.$i.':00" '.$osel.'>'.$j.':00 '.$ap.' </option>';
		$form.='<option value="'.$i.':30" '.$hsel.'>'.$j.':30 '.$ap.' </option>';
	}
	$form.='</select>';
	return $form;
}




}?>