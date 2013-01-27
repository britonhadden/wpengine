<?php


/**
 * User class
 *
 */
class User{


private $table;
private $error = array();

public $id;
public $name;
public $email;
//public $returning;

/**
 * @method constructor
 */
function __construct(){
	global $wpdb;
	$this->table = $wpdb->prefix."eom_users";
}


/**
 * save data to object
 *
 * @param array $data
 */
public function put($data){
	$this->name = sanitize_text_field(stripslashes($data['name']));
	$this->email = is_email(stripslashes($data['email']));
	//if($this->exists($this->email)){$this->returning=true;}else{$this->returning=false;}
	//if($this->name && $this->email){return true;}else{return false;}
}


/**
 * @method get
 * retrive user from db based on email
 *
 * @param string $email
 * @return true, false on failure
 */
public function get($email){
	global $wpdb;
	$sql = $wpdb->prepare("SELECT * FROM ".$this->table." WHERE email = %s", $email);
	$user = $wpdb->get_row($sql);
	if($user){
		$this->id = $user->id;
		$this->name = $user->name;
		$this->email = $user->email;
		return true;
	}else{return false;}
}


/**
 * Get users from db
 *
 * @param array $var = array('limit','count','order','like')
 * @return object $results or int count
 */
public function getAll($var){
	global $wpdb;
	if(isset($var['order'])){$orderSQL='ORDER BY '.$var['order'];}else{$orderSQL='';}
	if(isset($var['limit'])){$limitSQL=" LIMIT ".$var['limit']." ";}else{$limitSQL='';}
	if(isset($var['like'])){$likeSQL=" WHERE name LIKE '%%".$var['like']."%%' ";}else{$likeSQL='';}
	if(isset($var['count'])){$select="COUNT(*)";}else{$select="*";}
	$sql=$wpdb->prepare("SELECT ".$select." FROM ".$this->table." ".$likeSQL." ".$orderSQL." ".$limitSQL, 'unneed');
	//if count, use get_var
	if(isset($var['count'])){$results = $wpdb->get_var($sql);}else{
		$results=$wpdb->get_results($sql);
	}
	//if($this->debug){print_r(__CLASS__.' -> '.__FUNCTION__.' at '.__LINE__.'<pre>'.$wpdb->last_query.'</pre>');}
	return $results;
}


/**
 * @method save
 * save validated data to db
 *
 * @return true on success, false on failure
 */
public function save(){
	global $wpdb;
	$user=$wpdb->get_row($wpdb->prepare("SELECT id, email FROM ".$this->table." WHERE email=%s",$this->email),ARRAY_A);
	if($user){
		//add user->id
		$this->id = $user['id'];
		return true;
	}else{
		//insert
		$wpdb->insert($this->table, array('name'=>$this->name,'email'=>$this->email), array('%s','%s'));
		$this->id = $wpdb->insert_id;
		return true;
	}
	return false;
}


/**
 * @method exists
 * check for existence of user based on $email
 *
 * @param string $email
 * @return id on success, NULL on fail
 */
public function exists($email){
	global $wpdb;
	$sql=$wpdb->prepare("SELECT id FROM ".$this->table." WHERE email = %s", $email);
	return $wpdb->get_var($sql);
}




}//END Class User
?>