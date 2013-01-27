<?php


/**
 * @class Venue
 *
 */
class Venue{


private $table;
private $debug = false;

public $id;
public $name;
public $address;
//public $street;
public $city;
//public $state;
//public $country;
public $lat;
public $lon;
//public $error = array();


/**
 * @method constructor
 */
function __construct(){
	global $wpdb;
	$this->table = $wpdb->prefix."eom_venues";
}


/**
 * Add venue to object
 *
 */
public function put($data){
	if(isset($data['id'])){$this->id = (int) $data['id'];}
	if(isset($data['name'])){$this->name = sanitize_text_field(stripslashes($data['name']));}
	if(isset($data['address'])){$this->address = sanitize_text_field($data['address']);}
	if(isset($data['city'])){$this->city = sanitize_text_field($data['city']);}
	if(isset($data['lat'])){$this->lat = $data['lat'];}
	if(isset($data['lon'])){$this->lon = $data['lon'];}
}



/**
 * Lookup address via google and return results
 *
 * @param string $search - address to search
 * @param bool $sensor - location sensor
 * @return array of results 
 */
function lookup($search, $sensor=false){
	if($sensor){$sensor='sensor=true';}else{$sensor='sensor=false';}
	$url = sprintf('http://maps.googleapis.com/maps/api/geocode/json?address=%s&'.$sensor, urlencode($search));
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	curl_close($ch);
	$response = json_decode($response);
	switch ($response->status) {
		case 'OK': //response is good, parse responses
			foreach ($response->results as $key => $result){
				$found_address[$key]['full_address'] = $result->formatted_address; //full postal address
				$found_address[$key]['lat'] = $result->geometry->location->lat;
				$found_address[$key]['lng'] = $result->geometry->location->lng;
				foreach($result->address_components as $key2 => $result2){ //grab each address component
					$found_address[$key]['components'][$result2->types[0]] = $result2->long_name;
				}
			}
			return $found_address;
		break;
		case 'ZERO_RESULTS':
			return array(); //returns empty array of results
		break;
		default:
			throw new Exception("Error in address lookup service. (".$response->status.")");
		break;
	}
}


/**
 * Add to database
 *
 * @return  Return 2 if update, 1 if insert, false on fail
 */
public function save(){
	global $wpdb;
	$data=array('name'=>$this->name, 'city'=>$this->city, 'fullAddress'=>$this->address, 'lat'=>$this->lat, 'lon'=>$this->lon);
	$datatype=array('%s','%s','%s','%F','%F' );
	if($this->id){ //update
		$wpdb->update( $this->table, $data, array('id'=>$this->id), $datatype, '%d');
		return 2;
	}else{ //insert
		$wpdb->insert($this->table, $data, $datatype);
		$this->id = (int) $wpdb->insert_id;
		return 1;
	}
	return false;
}



/**
 * Get all venues based on parameters
 *
 * @return object $results
 */
public function get_all($var=array('limit'=>false,'count'=>false,'order'=>false,'like'=>false)){
	global $wpdb;
	if(isset($var['like'])){$likeSQL=" WHERE name LIKE '%%".$var['like']."%%' ";}else{$likeSQL='';}
	if(isset($var['order'])){$orderSQL='ORDER BY '.$var['order'];}else{$orderSQL='';}
	if(isset($var['limit'])){$limitSQL=" LIMIT ".$var['limit']." ";}else{$limitSQL='';}
	if(isset($var['count'])){$select="COUNT(*)";}else{$select="*";}
	$sql=$wpdb->prepare("SELECT ".$select." FROM ".$this->table." ".$likeSQL." ".$orderSQL." ".$limitSQL , 'unneeded_argument');
	//if count, use get_var
	if(isset($var['count'])){
		$results=$wpdb->get_var($sql);
	}else{
		$results=$wpdb->get_results($sql);
	}
	return $results;
}

//populate object based on address
public function get($address){
	global $wpdb;
	$sql=$wpdb->prepare("SELECT * FROM ".$this->table." WHERE fullAddress = %s", $address);
	$venue=$wpdb->get_row($sql, ARRAY_A);
	if($this->debug){print_r(__CLASS__.' -> '.__FUNCTION__.' at '.__LINE__.'<pre>'.$wpdb->last_query.'</pre>');}
	if($venue){
		$this->id = (int) $venue['id'];
		$this->name = $venue['name'];
		$this->address = $venue['fullAddress'];
	}else{return false;}
}



//populate object based on id - depreicated
/*public function getById(){
	global $wpdb;
	$sql=$wpdb->prepare("SELECT * FROM ".$this->table." WHERE id = %d;",$this->id);
	$venue=$wpdb->get_row($sql, ARRAY_A);
	if($this->debug){print_r(__CLASS__.'->'.__FUNCTION__.' at '.__LINE__.'<pre>'.$wpdb->last_query.'</pre>');}
	if($venue){
		$this->name = $venue['name'];
		$this->address = $venue['fullAddress'];
		$this->lat = $venue['lat'];
		$this->lon = $venue['lon'];
		return true;
	}else{return false;}
}*/



/**
 * Get venue based on id
 * 
 */
public function get_by_id(){
	global $wpdb;
	$sql = $wpdb->prepare("SELECT * FROM ".$this->table." WHERE id = %d;",$this->id);
	$venue = $wpdb->get_row($sql);
	if($venue){
		$this->name = $venue->name;
		$this->address = $venue->fullAddress;
		$this->lat = $venue->lat;
		$this->lon = $venue->lon;
		return true;
	}else{return false;}
}



//delete venue
public function delete(){
	global $wpdb;
	if($wpdb->query( $wpdb->prepare("DELETE FROM ".$this->table." WHERE id = %d", $this->id))){
	if($this->debug){print_r(__CLASS__.' -> '.__FUNCTION__.' at '.__LINE__.'<pre>'.$wpdb->last_query.'</pre>');}
	return true;
	}
	return false;
}

//check address in db. return id if exists, false on failure
/*public function exists($address){
	global $wpdb;
	$sql=$wpdb->prepare("SELECT id FROM ".$this->table." WHERE fullAddress = %s", $address);
	$venue=$wpdb->get_row($sql, ARRAY_A);
	if($this->debug){print_r(__CLASS__.'->'.__FUNCTION__.' at '.__LINE__.'<pre>'.$wpdb->last_query.'</pre>');}
	if($venue){
		return $venue['id'];
	}else{return false;}
}*/


/**
 * Return events that use this venue
 * 
 */
public function in_use(){
	global $wpdb;
	$sql = $wpdb->prepare("SELECT id, name FROM ".EOMEVENTS." WHERE venue_id = %d", $this->id);
	return $wpdb->get_results($sql);
	//if($this->debug){print_r(__CLASS__.'->'.__FUNCTION__.' at '.__LINE__.'<pre>'.$wpdb->last_query.'</pre>');}
	//if($events){return $events;}else{return false;}
}



/**
 * @method select
 * 
 * return select field of approved venues
 */
public function select($venue_id=false){
	$venues = $this->get_all(array('order'=>'name'));
    $return = '<select name="venue_id">';
	$return .= '<option value="" >'.__('Select...','event-o-matic').'</option>';
	foreach ($venues as $key => $value) {
		$selected = '';
    	if ($venue_id == $value->id) {
			$selected = 'selected="selected"';
        }
		$return .= '<option value="'.$value->id.'" '.$selected.' >'.esc_html($value->name).'</option>';
    }
	$return .= '</select>';
	return $return;
}

}?>