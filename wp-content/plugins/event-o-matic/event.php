<?php
class Event{

private $table;
public $statusCode = array('approved'=>'A','pending'=>'P');

public $id;
public $venueId;
public $venueName;
public $venueAddress;
public $venueLat;
public $venueLon;
public $userId;
public $name;
public $description;
public $price;
public $image;
public $url;
public $status;
public $preferred;
public $dateStart;
public $dateEnd;
public $error = array();
//private $debug = true;

function __construct(){
	global $wpdb;
	$this->table = $wpdb->prefix."eom_events";
}

//add data to user object, via array(name, description, price, imageUrl, url, status)
public function put($data){
	if(isset($data['id'])){$this->id = (int) $data['id'];}
	if(isset($data['user_id'])){$this->userId = (int) $data['user_id'];}
	if(isset($data['venue_id'])){$this->venueId = (int) $data['venue_id'];}
	if(isset($data['status'])){$this->status = sanitize_text_field($data['status']);}
	if(isset($data['preferred'])){$this->preferred = sanitize_text_field($data['preferred']);}
	if(isset($data['name'])){$this->name = sanitize_text_field(stripslashes($data['name']));}
	if(isset($data['description'])){ 
		$this->description = stripslashes(wp_filter_kses( substr( $data['description'], 0, get_option('eom_description_max')))); 
	}
	if(isset($data['price']) && is_numeric($data['price'])){ $this->price = $data['price']; }
	if(isset($data['image'])){$this->image = sanitize_text_field($this->valid_url(stripslashes($data['image'])));}
	if(isset($data['url'])){$this->url = sanitize_text_field($this->valid_url(stripslashes($data['url'])));}
	//if(!$this->name){$this->error[] = 'Invalid Event Name';}
	//if(!$this->description){$this->error[] = 'Invalid Event Description';}
}

//add to database. Return 2 if update, 1 if insert, false on fail
public function save(){
	global $wpdb;
	$data=array('type'=>$this->preferred, 'name'=>$this->name, 'description'=>$this->description, 'venue_id'=>$this->venueId, 'user_id'=>$this->userId, 'status'=>$this->status, 'flyer'=>esc_url_raw($this->image), 'price'=>$this->price, 'website'=>esc_url_raw($this->url));
	$datatype=array('%s', '%s', '%s', '%d', '%d', '%s','%s','%f','%s' );
	if($this->id){
		//update
		$wpdb->update( $this->table, $data, array('id'=>$this->id), $datatype, '%d');
		//if($this->debug){print_r(__CLASS__.' -> '.__FUNCTION__.' at '.__LINE__.'<pre>'.$wpdb->last_query.'</pre>');}
		return 2;
	}else{
		//insert
		$wpdb->insert($this->table, $data, $datatype );
		$this->id=$wpdb->insert_id;
		//if($this->debug){print_r(__CLASS__.' -> '.__FUNCTION__.' at '.__LINE__.'<pre>'.$wpdb->last_query.'</pre>');}
		return 1;
	}
}



/**
 * query event counts
 * 
 * @attr array $var array('when', 'status')
 */
public function get_count( $var ){
	global $wpdb;
	$whereSQL = ''; //where SQL
	$joinSQL = ''; //join SQL
	if(isset($var['when'])){
		if($var['when']=='upcoming'){$whereSQL = ' WHERE '.EOMDATES.'.end > NOW()';}
		if($var['when']=='archive'){$whereSQL = ' WHERE '.EOMDATES.'.end < NOW()';}
		$joinSQL = "LEFT JOIN ".EOMDATES." ON ".$this->table.".id=".EOMDATES.".event_id";
	}
	if(isset($var['status'])){
		if(isset($var['when'])){$whereSQL .= ' AND ';}else{$whereSQL .= ' WHERE ';}
		$whereSQL .= $this->table.".status = '".$var['status']."' ";
	}
	$sql=$wpdb->prepare(
	"SELECT COUNT(*) FROM ".$this->table." ".$joinSQL." ".$whereSQL.";", 'unneeded_argument');
	$results=$wpdb->get_var($sql);
	//if($this->debug){print_r(__CLASS__.' -> '.__FUNCTION__.' at '.__LINE__.'<pre>'.$wpdb->last_query.'</pre>');}
	return $results;
}



/**
 * specialized get_all query for admin
 * 
 * @param $var = array('when','status','order','limit', 'like')
 */
public function get_admin($var){
	global $wpdb;
	$likeSQL ='';
	$orderSQL=''; //ordering sql fragment
	$limitSQL=''; //limiting sql fragment
	$whereSQL=''; //where sql fragment
	if(isset($var['like'])){$likeSQL=" AND ".$this->table.".name LIKE '%%".$var['like']."%%' ";}
	if(isset($var['order'])){$orderSQL='ORDER BY '.$var['order'];}
	if(isset($var['limit'])){$limitSQL="LIMIT ".$var['limit']." ";}
	if($var['when']=='upcoming'){$whereSQL=' WHERE '.EOMDATES.'.end > NOW()';}
	elseif($var['when']=='archive'){$whereSQL=' WHERE '.EOMDATES.'.end < NOW()';}
	if(isset($var['status'])){
		if($var['when']){$whereSQL.=' AND ';}else{$whereSQL.=' WHERE ';}
		$whereSQL .= $this->table.".status = '".$var['status']."' ";
	}
	if(isset($var['venue'])){//get all events at select venue
		$whereSQL.=' AND '.$this->table.'.venue_id = '.$var['venue'].' ';
	}
	$select=$this->table.".id AS id,
	".$this->table.".name AS name,
	".$this->table.".status AS status, 
	".EOMDATES.".start AS dateStart, 
	".EOMDATES.".end AS dateEnd, 
	".EOMVENUES.".name AS venueName, 
	".EOMVENUES.".id AS venueId"; 
	$sql=$wpdb->prepare(
	"SELECT ".$select." FROM ".$this->table."
	LEFT JOIN ".EOMVENUES." ON ".$this->table.".venue_id=".EOMVENUES.".id
	LEFT JOIN ".EOMDATES." ON ".$this->table.".id=".EOMDATES.".event_id
	".$whereSQL." ".$likeSQL." ".$orderSQL." ".$limitSQL.";", 'notneeded');
	$results=$wpdb->get_results($sql, ARRAY_A);
	//if($this->debug){print_r(__CLASS__.' -> '.__FUNCTION__.' at '.__LINE__.'<pre>'.$wpdb->last_query.'</pre>');}
	return $results;
}


/**
 * Query events
 * 
 * @attr array $var array('when','status','order','limit','venue')
 * 
 */
public function getAll($var){
	global $wpdb;
	$orderSQL=''; //ordering sql fragment
	$limitSQL=''; //limiting sql fragment
	$whereSQL=''; //where sql fragment
	if(isset($var['order'])){ $orderSQL = 'ORDER BY '.$var['order'];}
	if(isset($var['limit'])){ $limitSQL = "LIMIT ".$var['limit']." ";}else{}
	if(isset($var['when'])){
		if($var['when']=='upcoming'){$whereSQL=' WHERE '.EOMDATES.'.end > NOW()';}
		elseif($var['when']=='archive'){$whereSQL=' WHERE '.EOMDATES.'.end < NOW()';}
	}
	if(isset($var['status'])){
		if(isset($var['when'])){$whereSQL.=' AND ';}else{$whereSQL.=' WHERE ';}
		$whereSQL .= $this->table.".status = '".$var['status']."' ";
	}
	if(isset($var['venue'])){//get all events at select venue
		$whereSQL.=' AND '.$this->table.'.venue_id = '.$var['venue'].' ';
	}
	$select=$this->table.".id AS id,
	".$this->table.".name AS name,
	".$this->table.".description AS description,
	".$this->table.".flyer AS image,
	".$this->table.".status AS status, 
	".$this->table.".type AS preferred, 
	".$this->table.".price AS price, 
	".EOMDATES.".start AS dateStart, 
	".EOMDATES.".end AS dateEnd,
	".EOMVENUES.".name AS venueName, 
	".EOMVENUES.".id AS venueId,
	".EOMUSERS.".email AS userEmail";
	$sql=$wpdb->prepare(
	"SELECT ".$select." FROM ".$this->table."
	LEFT JOIN ".EOMVENUES." ON ".$this->table.".venue_id=".EOMVENUES.".id 
	LEFT JOIN ".EOMDATES." ON ".$this->table.".id=".EOMDATES.".event_id
	LEFT JOIN ".EOMUSERS." ON ".$this->table.".user_id=".EOMUSERS.".id 
	".$whereSQL." ".$orderSQL." ".$limitSQL.";", 'unneeded_argument');
	$results = $wpdb->get_results($sql, ARRAY_A);
	//if($this->debug){print_r(__CLASS__.' -> '.__FUNCTION__.' at '.__LINE__.'<pre>'.$wpdb->last_query.'</pre>');}
	return $results;
}



public function get(){
	global $wpdb;
	$sql=$wpdb->prepare(
		"SELECT ".$this->table.".id AS id,
		".$this->table.".name AS name,
		".$this->table.".description AS description,
		".$this->table.".price AS price,
		".$this->table.".flyer AS image,
		".$this->table.".website AS url,
		".$this->table.".status AS status, 
		".$this->table.".type AS preferred,
		".EOMDATES.".start AS dateStart, 
		".EOMDATES.".end AS dateEnd, 
		".EOMVENUES.".id AS venueId, 
		".EOMVENUES.".name AS venueName,
		".EOMVENUES.".lat AS venueLat,
		".EOMVENUES.".lon AS venueLon,
		".EOMVENUES.".fullAddress AS venueAddress,
		".EOMUSERS.".id AS userId FROM ".$this->table."
		LEFT JOIN ".EOMVENUES." ON ".$this->table.".venue_id=".EOMVENUES.".id 
		LEFT JOIN ".EOMUSERS." ON ".$this->table.".user_id=".EOMUSERS.".id 
		LEFT JOIN ".EOMDATES." ON ".$this->table.".id=".EOMDATES.".event_id
		WHERE ".$this->table.".id = %d",$this->id);
	$event=$wpdb->get_row($sql, ARRAY_A);
	//if($this->debug){print_r(__CLASS__.' -> '.__FUNCTION__.' at '.__LINE__.'<pre>'.$wpdb->last_query.'</pre>');}
	if($event){
		$this->name = $event['name'];
		$this->description = $event['description'];
		$this->price = $event['price'];
		$this->image = $event['image'];
		$this->url = $event['url'];
		$this->status = $event['status'];
		$this->preferred = $event['preferred'];
		$this->venueId = (int) $event['venueId'];
		$this->venueName = $event['venueName'];
		$this->venueLat = $event['venueLat'];
		$this->venueLon = $event['venueLon'];
		$this->venueAddress = $event['venueAddress'];
		$this->userId = (int) $event['userId'];
		$this->dateStart = $event['dateStart'];
		$this->dateEnd = $event['dateEnd'];
		return true;
	}else{return false;}
}

//delete event
public function delete(){
	global $wpdb;
	if($wpdb->query( $wpdb->prepare("DELETE FROM ".$this->table." WHERE id = %d", $this->id))){
		//if($this->debug){print_r(__CLASS__.' -> '.__FUNCTION__.' at '.__LINE__.'<pre>'.$wpdb->last_query.'</pre>');}
		$wpdb->query( $wpdb->prepare("DELETE FROM ".EOMDATES." WHERE event_id = %d", $this->id));
		//if($this->debug){print_r(__CLASS__.' -> '.__FUNCTION__.' at '.__LINE__.'<pre>'.$wpdb->last_query.'</pre>');}
		//also delete images
		$args = array('post_status' => 'inherit','post_type' => 'attachment',
		'meta_query' => array(array(
			'key' => 'eom_event_id',
			'value' => $this->id,
		)));
		$query = new WP_Query( $args );
		if(isset($query->post->ID)){
			if($post_thumbnail_id = get_post_thumbnail_id($query->post->ID)){  
				wp_delete_attachment($post_thumbnail_id);
			}
			wp_trash_post($query->post->ID);  
		}
		unset($query);
		
		return true;
	}
	return false;
}

//update event status
public function statusSave(){
	global $wpdb;
	if($this->id && $this->status){
		$data=array('status'=>$this->status);
		$datatype=array('%s');
		$wpdb->update( $this->table, $data, array('id'=>$this->id), $datatype, '%d');
		//if($this->debug){print_r(__CLASS__.' -> '.__FUNCTION__.' at '.__LINE__.'<pre>'.$wpdb->last_query.'</pre>');}
		return true;
	}
	return false;
}



/**
 * Validate url
 * 
 * @attr string $url
 * @return url or false on fail
 */
function valid_url($url) {
    $v = "/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i";
    if( (bool)preg_match($v, $url)){
		return $url;
	}
	return false;
}

/*
function super_submitter($url, $data, $optional_headers = null){
	$params = array('http' => array('method' => 'post', 'content' => $data ));
	if ($optional_headers!== null) {
		$params['http']['header'] = $optional_headers;
	}
	$ctx = stream_context_create($params);
	$fp = @fopen($url, 'rb', false, $ctx);
	if (!$fp) {
		throw new Exception("Problem with $url, $php_errormsg");
	}
	$response = @stream_get_contents($fp);
	if ($response === false) {
		throw new Exception("Problem reading data from $url, $php_errormsg");
	}
	return $response; 
}*/


}//END Event
?>