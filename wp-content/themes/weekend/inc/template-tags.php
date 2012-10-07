<?php
/* *
 * Similar to ydn_get_top_level_cat, but gets WEEKEND subcategory 
 */
if (! function_exists("weekend_get_cat") ):
  function weekend_get_cat($post) {
    $print_cat = get_category_by_slug("print");
    $blog_cat = get_category_by_slug("blog");
    $cats = wp_get_post_categories( $post->ID, array("fields" => "all"));
    foreach ($cats as $cat) {
      if ( $cat->parent == $print_cat->cat_ID ) {
         return $cat->name;
      } else if ( $cat->term_taxonomy_id == $blog_cat->cat_ID ) {
        return $blog_cat->name;
      }
    }
    return null;
  }
endif;

/* *
 * Renders a WEEKEND block for the given post. Allows passing of classes
 */
if (! function_exists("weekend_render_block") ):
  function weekend_render_block($post_in, $size) {
    global $post;
    $temp_post = $post;
    $post = $post_in;

    $visual = get_the_post_thumbnail($post->ID,'weekend-'.$size);
    if ( empty($visual) ) {
      $visual = '<div class="no-image"></div>';
    }


    $visual = '<a href="' . get_permalink($post->ID) . '">'. $visual . '</a>';
?>
        <div class="block pop-out <?php echo $size; ?>">
          <div class="wrapper">
            <div class="right edge"><div></div></div>
            <div class="bottom edge"><div></div></div>
            <div class="content">
              <article id="post-<?php the_ID(); ?>" <?php echo post_class(); ?>>
                <div class="entry-image"><?php echo $visual; ?></div>     
                <div class="entry-meta">
                  <div class="cat-author"><span class="entry-category"><?php echo weekend_get_cat($post); ?></span> // <span class="entry-authors"><?php coauthors_posts_links(); ?></span></div>
                  <h3 class="entry-title"><a href="<?php echo the_permalink(); ?>"><?php the_title(); ?></a></h3>
                </div>
              </article>
            </div>
          </div>
        </div>
<?php
  $post = $temp_post;
  }
endif;

/**
 * THIS OVERRIDES A FUNCTION FROM THE PARENT THEME: It's a lot of code duplication (sucks, I know), but we need to use a different thumbnail size
 * Returns a div with the post's featured image and associated metadata (e.g. caption, authors..)
 * meant to be used within the loop. uses global $post
 */
if (! function_exists( 'ydn_get_featured_image') ):
function ydn_get_featured_image() {
  global $post;
  if(  has_post_thumbnail() ):
    $featured_image_id = get_post_thumbnail_id( $post->ID );
    $featured_image_obj = get_posts( array( 'numberposts' => 1,
                                            'include' => $featured_image_id,
                                            'post_type' => 'attachment',
                                            'post_parent' => $post->ID ) );
    if ( is_array($featured_image_obj) && !empty($featured_image_obj) ) {
      $featured_image_obj = $featured_image_obj[0];
    }

    ?>
    <div class="entry-featured-image">
      <?php  the_post_thumbnail('weekend-entry-featured-image'); ?>
      <?php if($featured_image_obj): ?>
        <div class="image-meta">
          <?php if( $featured_image_obj->post_excerpt): ?>
            <span class="caption"> <?php echo esc_html( $featured_image_obj->post_excerpt ); ?> </span> 
          <?php endif; ?>
          <?php
            $attribution_text = get_media_credit_html($featured_image_obj);
            if(trim($attribution_text) != ''  ): ?>
              <span class="attribution">// <?php echo $attribution_text; ?></span>
          <?php endif; ?>
        </div>
      <?php endif; //end featured_image_obj check ?>
    </div>
    <?php endif; //end has_post_thumbnail condition
}
endif; // end function_exists condition



?>
