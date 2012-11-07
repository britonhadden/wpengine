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
  const table_suffix = "legacy_urls"; //DON'T USE THIS WITHOUT PREFIXING
  const plugin_version = "1.0";
  const plugin_version_option = "YDN_URL_Rewrites_version";

  public static function get_instance() {
    NULL === self::$instance and self::$instance = new self;
    return self::$instance;
  }

  public function init() {
    //register hooks & such
    global $wpdb;
    $this->table_name = $wpdb->prefix . YDN_URL_Rewrites::table_suffix;
  }

  public function add_rewrite($legacy_url, $new_url) {
    if (empty($legacy_url) || empty($new_url))
      return;   //silly case, but don't add empty rewrites

    global $wpdb;

    //keep everything tidy
    $legacy_url = $this->sanitize_url($legacy_url);
    $new_url = $this->sanitize_url($new_url);

    $wpdb->insert($this->table_name, array('legacy_url' => $legacy_url, 'new_url' => $new_url));
  }

  private function sanitize_url($url) {
    //ensures that all URLs are formatted appropriately and that our lookups
    //match exactly
    //
    $url = trim($url);   //ensure no bad whitespace
    $url = trim($url, '/'); //remove leading/trailing slash

    return $url;
  }

  public function install() {
    //creates the database tables where rewrites can be stored
    global $wpdb;

    //check currently installed version
    $prev_version = get_option(YDN_URL_Rewrites::plugin_version_option, false);
    if ($prev_version == YDN_URL_Rewrites::plugin_version){
      //if the same version of the plugin is already installed, do nothing
      return;
    }

    //actually install the plugin
    //step1: database migration
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');   //loads the dbdelta util
    $this->table_name = $wpdb->prefix . YDN_URL_Rewrites::table_suffix;
    $sql = "CREATE TABLE wp_legacy_urls (
      legacy_url VARCHAR(90) DEFAULT '' NOT NULL,
      new_url VARCHAR(90) DEFAULT '' NOT NULL,
      UNIQUE KEY legacy_url (legacy_url)
    );";
    dbDelta($sql);

    //step2: save option
    update_option(YDN_URL_Rewrites::plugin_version_option, YDN_URL_Rewrites::plugin_version);
  }
}
//hook into wordpress
add_action('init', array(YDN_URL_Rewrites::get_instance(), 'init'));
//install the plugin if it's a first activation
?>
