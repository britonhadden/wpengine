<?php
if (! function_exists("ydn_home_print_section") ):
  function ydn_home_print_section($content, $slug) {
    /* Used to render the print-section boxes on the bottom of the home page.
     * These include a photo on the left and a list of stories on the right.
     *
     * $content should be an initialized YDN_homepage_content object
     * $slug should be a valid slug in the category hierarchy */
    global $post;
    $temp_post = $post;  //we're going to be using the loop, so protect $post
    $section_content = $content->get_content_for_cat($slug);

    //first post we deal with is featured, so pop it into the global
    $post = $section_content["featured"];
    setup_postdata($post);
    ?>
    <div class="print-section content-list narrow">
      <h1><?php echo $slug; ?></h1>
      <div class="row">
        <div class="span6 featured item">
          <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail("home-print-section"); ?></a>
          <a href="<?php the_permalink(); ?>" class="headline"><?php the_title(); ?></a>
          <div class="meta">
            <div class="bylines">By <?php coauthors_posts_links(); ?></div>
            <div class="datetime"><?php echo get_the_date(get_option('date_format')); ?> &bull; <?php ydn_comment_count(); ?></div>
          </div>
          <div class="teaser"><?php echo the_excerpt(); ?></div>
        </div>

        <div class="span7">
          <?php foreach($section_content["list"] as $post): setup_postdata($post); ?>
           <div class="item">
            <a href="<?php the_permalink(); ?>" class="headline"><?php the_title(); ?></a>
            <div class="meta">
              <div class="bylines">By <?php coauthors_posts_links(); ?></div>
              <div class="datetime"><?php echo get_the_date(get_option('date_format')); ?> &bull; <?php ydn_comment_count(); ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div><!-- .row -->
    </div><!-- .print-section <?php echo $slug; ?> -->
    <?php
    $post = $temp_post; //restore post to its real value
  }
endif; // function exists

class YDN_homepage_content {
  const cache_group = "YDN_home_content";
  const cache_expiration = 300; //5 min cache expiration
  /* This class is used to pull content for the category-specific boxes on the homepage */

  private static $instance = NULL;

  public static function get_instance() {
    self::$instance === NULL && self::$instance = new self;
    return self::$instance;
  }

  public function get_top_three_content() {
    return $this->get_cached_zone("homepage-top-three");
  }

  public function get_slideshow_content() {
    return $this->get_cached_zone("homepage-slideshow");
  }

  public function get_featured_content() {
    return $this->get_cached_zone("homepage-featured-stories");
  }

  public function get_videos() {
    $cache_key = "ydn_home_video";
    $cache_value = wp_cache_get($cache_key, self::cache_group, YDN_homepage_content::cache_expiration);
    if ($cache_value) {
      return $cache_value;
    }

    $videos_query = new WP_Query( array( 'post_type' => 'video',
                                         'posts_per_page' => '3',
                                         'orderby' => 'date',
                                         'order' => 'DESC' ) );
    wp_cache_set($cache_key, $videos_query->posts, self::cache_group, self::cache_expiration);
    return $videos_query->posts;
  }

  private function get_cached_zone($zone_name) {
    $cache_key = $zone_name;
    $cache_value = wp_cache_get($cache_key, YDN_homepage_content::cache_group, YDN_homepage_content::cache_expiration);
    if ($cache_value) {
      return $cache_value;
    }

    $content = z_get_posts_in_zone($zone_name);
    if($content == NULL || empty($content)) {
      $content = array();
    } else {
      wp_cache_set($cache_key,$content, YDN_homepage_content::cache_group, YDN_homepage_content::cache_expiration);
    }

    return $content;
  }

  private function initialize_calculations() {
    //setpup the necessary data structures to grab content from the database
    //used in the event of a cache miss
    $slideshow_content = $this->get_slideshow_content();
    $top_three_content = $this->get_top_three_content();
    $featured_content = $this->get_featured_content();


    /* anything included in slideshow/top_three should be excluded from the category specific boxes */
    $this->excluded_ids = array();
    foreach ($slideshow_content as $post) {
      $this->excluded_ids[] =  $post->ID;
    }
    foreach ($top_three_content as $post) {
      $this->excluded_ids[] =  $post->ID;
    }

    /* we need to know the category for content in $featured_content so that we can use them when appropriate */
    /* we build a 2D array: $featured_content_by_cat[cat_slug] = an array of posts in that cat */
    $this->featured_content_by_cat = array();
    foreach ($featured_content as $post) {
      $categories = get_the_category($post->ID);
      foreach ($categories as $category) {
        if ( array_key_exists($category->slug, $this->featured_content_by_cat) ) {
          /* we've never encountered this category before,
           * so we need to build a new array in that bin and add the post to it */
          $this->featured_content_by_cat[$category->slug] = array($post);
        } else {
          /* we've encountered this category before, so add it to the list */
          $this->featured_content_by_cat[$category->slug][] = $post;
        }
      }
    }
  }

  function get_content_for_cat($cat_slug, $n_list = 5) {
    /* takes in a category name and returns home page content for it
     * also takes an optional parameter which specifies the number of posts to return in the list field */
    /* returns an array with two values:
          [featured] => 1) check if something in $featured_content is in the category, has a photo, and is not marked for exclusion. retrun post if found
                        2) return the most recent post in the category that has a photo and is not excluded
                        [list]     => return the most recent stories that are not [featured]
       returns null if there's a problem
    */
    $output = array();
    $output["featured"] = $this->featured_post($cat_slug);
    if ($output["featured"] == null ) {
        $output["featured"] = array();
    }
    $output["list"] = $this->get_post_list($cat_slug, $n_list, $output["featured"]->ID );
    if ($output["list"] == null) {
        $output["list"] == null;
    }

    return $output;
  }

  function get_post_list($cat_slug, $n_list = 5, $additional_exclude = null) {
    /* pull a list of stories in $cat_slug, excluding any posts in $additional_exclude */

    //first try to pull from cache
    $cache_key = implode(";",array("get_post_list", $cat_slug,$n_list,$additional_exclude));
    $cached_val = wp_cache_get($cache_key, YDN_homepage_content::cache_group);
    if ($cached_val) {
      return $cached_val;
    }

    $this->initialize_calculations();
    $query_params = array( 'posts_per_page' => $n_list,
                           'category_name' => $cat_slug,
                         );

    if ($additional_exclude != null) {
      $query_params['post__not_in']  = array($additional_exclude);
    }

    $query = new WP_Query($query_params);

    //fill cache and return
    wp_cache_set($cache_key, $query->posts, YDN_homepage_content::cache_group, YDN_homepage_content::cache_expiration);
    return $query->posts;
  }

  private function featured_post($cat_slug) {
    /* if there's a featured post in $featured_content_by_cat use it. otherwise, get the category's most recent post with a photo */

    //first try to pull from cache
    $cache_key = implode(";",array("featured_post", $cat_slug));
    $cached_val = wp_cache_get($cache_key, YDN_homepage_content::cache_group);
    if ($cached_val) {
      return $cached_val;
    }

    //perform the calculations if necessary
    $this->initialize_calculations();
    if ( array_key_exists( $cat_slug, $this->featured_content_by_cat ) &&
         !empty($this->featured_content_by_cat[$cat_slug])  ) {
        /* we have featured content from this category, so as long as it has a photo we're golden */
        $output = $this->featured_content_by_cat[$cat_slug][0];
    } else {
        /* we don't have any featured content for this category. we need to pull the latest post
         * that's not in excluded_ids, has a photo, and is in this cat */
        $query_params = array( 'posts_per_page' => 1,
                               'meta_query' => array( array('key' => '_thumbnail_id') ), //this checks for thumb
                               'post__not_in' => $this->excluded_ids,
                               'category_name' => $cat_slug, //this param is named poorly, slug is correct
                             );
        $query = new WP_Query($query_params);
        if ( empty($query->posts) ) {
          /* we weren't able to find any matching posts, so return null and go no further */
          /* error state */
          return null;
        } else {
          /* set our featured post for this category to be the query response */
          $output =  $query->posts[0];
        }
    }

    wp_cache_set($cache_key, $output, YDN_homepage_content::cache_group, YDN_homepage_content::cache_expiration);
    return $output;
  }



}
?>
