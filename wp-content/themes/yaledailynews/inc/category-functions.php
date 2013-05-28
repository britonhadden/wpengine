<?php
/* Gets first post with img or featured post w/ img in current category */
function get_first_post_with_img() {
  $cat = get_category(get_query_var('cat'),false);
  $cat_slug = $cat->slug;
  if ( $posts = z_get_posts_in_zone( $cat_slug . '-zone') ) :
    $output = $posts[0];
  else :  // If no featured post, get first post with img
    $query_params = array( 'posts_per_page' => 1,
      'meta_query' => array( array('key' => '_thumbnail_id') ), // this checks for thumb
      'category_name' => $cat_slug, // this param is named poorly, slug is correct
    );
    $query = new WP_Query($query_params);
    if ( empty($query->posts) ) :
      // we weren't able to find any matching posts, so return null and go no further
      echo 'failed';
      return null;
    else :
      // set our featured post for this category to be the query response
      $output =  $query->posts[0];
    endif; 
  endif;
  return $output;
  
}

function get_post_list($cat_slug = null, $cat_union = null, $cat_bisect = null, $n_list = 5, $additional_exclude = null,
$exclude_cat = null) {
  /* pull a list of stories in $cat_slug, excluding any posts in $additional_exclude */

  //first try to pull from cache
  $cache_key = implode(";",array("get_post_list", $cat_slug,$n_list,$additional_exclude));
  $cached_val = wp_cache_get($cache_key);
  if ($cached_val) {
    return $cached_val;
  }

  $query_params = array( 'posts_per_page' => $n_list );

  if ($cat_slug != null)
    $query_params['category_name'] = $cat_slug;

  if ($cat_union != null)
    $query_params['category__in'] = $cat_union;

  if ($cat_bisect != null)
    $query_params['category__and'] = $cat_bisect;

  if ($additional_exclude != null)
    $query_params['post__not_in']  = array($additional_exclude);

  if ($exclude_cat)
    $query_params['category__not_in'] = array($exclude_cat);

  $query = new WP_Query($query_params);

  //fill cache and return
  wp_cache_set($cache_key, $query->posts);
  return $query->posts;
}

/* Creates UL of pagination links 
  a la http://wordpress.stackexchange.com/questions/12456/adding-pagination-to-a-custom-template-that-uses-custom-post-types */
function pagination( $query, $npages = 5 ) {
  global $wp;
  $page = $query->query_vars["paged"];
  $baseURL = site_url( implode( '/', array_slice( explode('/', $wp->request), 0, ($page) ? -1 : null)));
  if ( !$page ) {
    $baseURL .= '/page/';
    $page = 1;
  } else {
    $baseURL .= '/';
  }
  $qs = $_SERVER["QUERY_STRING"] ? "?".$_SERVER["QUERY_STRING"] : "";
  // Only necessary if there's more posts than posts-per-page
  if ( $query->found_posts > $query->query_vars["posts_per_page"] ) {
    echo '<ul>';
    // Previous link?
    if ( $page > 1 ) {
      echo '<li class="previous"><a href="'.$baseURL.($page-1).'/'.$qs.'">« previous</a></li>';
    }
    // Loop through pages
    for ( $i = ($page - floor($npages/2) > 0) ? ($page - floor($npages/2)) : 1; $i <= $page + floor($npages/2) && $i <= $query->max_num_pages; $i++ ) {
      // Current page or linked page?
      if ( $i == $page ) {
        echo '<li class="active"><a href="#">'.$i.'</a></li>';
      } else {
        echo '<li><a href="'.$baseURL.$i.'/'.$qs.'">'.$i.'</a></li>';
      }
    }
    // Next link?
    if ( $page < $query->max_num_pages ) {
      echo '<li><a href="'.$baseURL.($page+1).'/'.$qs.'">next »</a></li>';
    }
    echo '</ul>';
  }
}
?>
