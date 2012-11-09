<?php
//Name: propagate-users.php
//Author: Michael DiScala
//Description: Adds a registration hook that adds a user to all subblogs in the
//network based on their current role

class YDN_Propagate_Users {
  private static $instance = NULL;
  const plugin_name = "YDN_Propagate_Users";
  const default_role = "subscriber";

  public static function get_instance() {
    self::$instance === NULL and self::$instance = new self;
    return self::$instance;
  }

  public function user_registration_hook($user_id,$password,$meta) {
    $user = new WP_User($user_id);

    //decide on the appropriate role that should be on the account
    $role = $meta["new_role"];
    if (empty($role)) {
      $role = YDN_Propagate_Users::default_role;
    }

    //determine if we can skip any sites
    $init_site = $meta["add_to_blog"];
    if(empty($init_site)) {
      $init_site = NULL;
    }

    //add user to all blogs that they dont' already belong to
    foreach ($this->get_blogs() as $blog) {
      if ($blog == $init_site) {
        continue;  //nothing to do
      }
      add_user_to_blog($blog, $user_id, $role);
    }
    restore_current_blog();
  }

  private function get_blogs() {
    global $wpdb;
    $blogs = wp_cache_get('blogs', YDN_Propagate_Users::plugin_name);
    if ($blogs) {
      $blogs = explode(',',$blogs);  //undo string collapsing that's necessary for caching
    } else {
      $query = $wpdb->prepare("SELECT blog_id FROM $wpdb->blogs WHERE site_id = %d AND public = 1 AND archived = '0' AND mature = '0' AND spam = 0 AND deleted = '0'", $wpdb->siteid );
      $blogs = $wpdb->get_col($query);
      wp_cache_set("blogs", implode(',',$blogs), YDN_Propagate_Users::plugin_name);   //cache the query results
    }

    $blogs = array_map(intval, $blogs);
    return $blogs;
  }
}
add_action("wpmu_activate_user", array(YDN_Propagate_Users::get_instance(), "user_registration_hook"),1000,3);
?>
