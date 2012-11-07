<?php
//Name: url-rewrites.php
//Author: Michael DiScala
//Description: Adds support for legacy YDN urls. THe module creates a new
//table in the wordpress database that maps old_urls to new_urls
//
//When a 404 is encounteredon the network, the plugin checks if it was
//triggered by a legacy URL.  If so, it looks up the appropriate end
//point and redirects the user

class YDN_URL_Rewrites {
  //store a reference to the instance (creates a singleton)
  protected static $instance = NULL;

  public static function get_instance() {
    NULL === self::$instance and self::$instance = new self;
    return self::$instance;
  }


  public function init() {
    //register hooks & such
    global $wpdb;
    $this->table_name = $wpdb->prefix . "legacy_url_rewrites";
  }

  public function install() {
    //creates the database tables where rewrites can be stored
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');   //loads the dbdelta util

    $sql = "CREATE TABLE $this->table_name (
      id INT NOT NULL AUTO_INCREMENT,
      legacy_url VARCHAR(200),
      new_url VARCHAR(200),
      UNIQUE KEY id (id),
      KEY legacy_url (legacy_url)
    );";

    dbDelta($sql)
  }
}
//hook into wordpress
add_action('init', array(YDN_URL_Rewrites::get_instance(), 'init'));
//install the plugin if it's a first activation
register_activation_hook(__FILE__, array(YDN_URL_Rewrites::get_instance(), 'install');
?>
