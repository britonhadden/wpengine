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
  //plugin meta
  const plugin_version = "1.0";
  const plugin_name = "YDN_URL_Rewrites";
  const plugin_version_option = "YDN_URL_Rewrites_version";
  //db settigngs
  const table_suffix = "legacy_urls"; //DON'T USE THIS WITHOUT PREFIXING
  //patterns & mappings
  const staff_regex = "/^staff\/([a-zA-Z\-]*)/";
  const staff_new_prefix = "blog/author/";
  const article_regex = "/^news\/([0-9]{4}\/(jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)\/[0-9]{2}\/[a-zA-Z\-]*)/";
  //misc
  const flag404 = "404ERROR";

  public static function get_instance() {
    NULL === self::$instance and self::$instance = new self;
    return self::$instance;
  }


  public function init() {
    //register hooks & such
    global $wpdb;
    $this->table_name = $wpdb->prefix . YDN_URL_Rewrites::table_suffix;

    add_action('template_redirect', array($this, 'template_redirect_handler'),1);
  }

  public function template_redirect_handler() {
    global $wp;
    global $wp_query;

    if (!$wp_query->is_404)
      return; //redirects only intercepts 404 errors

    //first check if this URL's rewrite is stored in the database -- saves DB
    //hits
    $target_url = wp_cache_get($wp->request, YDN_URL_Rewrites::plugin_name);
    if ($target_url == YDN_URL_Rewrites::flag404) {
      //we've cached a 404, so do nothing
      return;
    } elseif ($target_url) {
      //a real url is in the cache, redirect to it
      $this->redirect_to_relative($target_url);
      return;
    }

    $pat_matches = array();

    //check if we're redirecting a staff URL (e.g yaledailynews.com/staff/author-name)
    preg_match(YDN_URL_Rewrites::staff_regex, $wp->request, $pat_matches);
    if(!empty($pat_matches)) {
      //prepare the author string
      $author = $pat_matches[1];
      $author = str_replace("-","",$author);

      //cache the value
      if(empty($author)) {
        //no where to redirect to -- cache a 404 and pass through
        wp_cache_set($wp->request, YDN_URL_Rewrites::flag404, YDN_URL_Rewrites::plugin_name);
      } else {
        //form the relative URL and cache it
        $target_url = YDN_URL_Rewrites::staff_new_prefix . $author;
        wp_cache_set($wp->request, $target_url, YDN_URL_Rewrites::plugin_name);
        $this->redirect_to_relative($target_url);
      }

      return;  //no more evaluation necessary -- the URL has been resolved if we get here
    }

    //check if we're redirecting an old article URL
    preg_match(YDN_URL_Rewrites::article_regex, $wp->request, $pat_matches);
    if (!empty($pat_matches)) {
      var_dump($pat_matches);
      die();
    }
  }

  private function redirect_to_relative($rel) {
    global $wp_query;
    $wp_query->is_404 = false;
    $abs = get_site_url(1, $rel) . '/';  //necessary so that we don't get double redirects
    wp_redirect($abs, 301);
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
    //ensures that all URLs are formatted appropriately on insert
    //designed to allow some flexibility in add_rewrite -- should *not* be
    //used in handle_redirection. it's not designed to be particularly
    //
    $matches = array();
    $request_regex = '/.com\/(news\/)?([^?]*)/'; //0 = entire match 1 = "" or "news" 2 = the rest of the URL
    preg_match($request_regex, $url, $matches);
    if(empty($matches)) {
      return "";
    }
    $url = $matches[2];  //the good part of the URL
    $url = trim($url);   //ensure no bad whitespace
    $url = trim($url,'\\'); //strips trailing slash

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
