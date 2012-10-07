<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package ydn
 * @since ydn 1.0
 */

if ( ! function_exists( 'ydn_content_nav' ) ):
/**
 * Display navigation to next/previous pages when applicable
 *
 * @since ydn 1.0
 */
function ydn_content_nav( $nav_id ) {
	global $wp_query;

	$nav_class = 'site-navigation paging-navigation';
	if ( is_single() )
		$nav_class = 'site-navigation post-navigation';

	?>
	<nav role="navigation" id="<?php echo $nav_id; ?>" class="<?php echo $nav_class; ?>">
		<h1 class="assistive-text"><?php _e( 'Post navigation', 'ydn' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'ydn' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'ydn' ) . '</span>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'ydn' ) ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'ydn' ) ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php
}
endif; // ydn_content_nav

if ( ! function_exists( 'ydn_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since ydn 1.0
 */
function ydn_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'ydn' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', '_s' ), ' ' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header>
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 40 ); ?>
					<?php printf( __( '%s:', 'ydn' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				</div><!-- .comment-author .vcard -->
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'ydn' ); ?></em>
					<br />
				<?php endif; ?>
			</header>

			<div class="comment-content"><?php comment_text(); ?></div>
      <footer class="clearfix">
        <a class="pull-left" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( 'Posted on %1$s at %2$s', 'ydn' ), get_comment_date(), get_comment_time() ); ?>
					</time></a>

        <span class="reply pull-right">
          <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
        </span><!-- .reply -->
      </footer>
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for ydn_comment()

if ( ! function_exists( 'ydn_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since ydn 1.0
 */
function ydn_posted_on() {
	printf( __( '%1$s', 'ydn' ),
		esc_html( get_the_date('l, F j, Y') )
	);
}
endif;

/**
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
      <?php  the_post_thumbnail('entry-featured-image'); ?>
      <?php if($featured_image_obj): ?>
        <div class="image-meta">
          <?php if( $featured_image_obj->post_excerpt): ?>
            <span class="caption"> <?php echo esc_html( $featured_image_obj->post_excerpt ); ?> </span> 
          <?php endif; ?>
          <?php
            $attribution_text = get_media_credit_html($featured_image_obj);
            if(trim($attribution_text) != ''  ): ?>
              <span class="attribution">Photo by <?php echo $attribution_text; ?>.</span>
          <?php endif; ?>
        </div>
      <?php endif; //end featured_image_obj check ?>
    </div>
    <?php endif; //end has_post_thumbnail condition
}
endif; // end function_exists condition


/**
 * Returns formatted author bylines with  the reporter type if available (e.g. staff reporters, contributing reporters)
 * meant to be used within the loop. uses global $post
 */
if (! function_exists('ydn_authors_with_type') ):
  function ydn_authors_with_type() {
    global  $post;
    $reporter_type = get_post_custom_values("reporter_type");
    if (!empty($reporter_type) ) {
       $reporter_type = $reporter_type[0]; //there should only be one key associated with this value
       $reporter_type = '<br>' . $reporter_type;
    } else {
      $reporter_type = '';
    }

    coauthors_posts_links(); //this prints its own output
    echo $reporter_type;
  }
endif; //edn function_exists condition

/**
 * outputs a twitter/facebook share links
 */
if (!function_exists('ydn_facebook_link') ):
  function ydn_facebook_link() {
    global $post;
    $fb_options = get_option('fb_options');
    if (empty($fb_options)) {
      $fb_app = '';
    } else {
      $fb_app = $fb_options["app_id"];
    }
    $fb_params = array( "app_id" => $fb_app,
                        "link" => get_permalink(),
                        "name" => get_the_title(),
                        "description" => get_the_excerpt(),
                        "redirect_uri" => get_permalink() );
    $fb_share_url = "https://www.facebook.com/dialog/feed?" . http_build_query($fb_params);
    printf('<a href="%1$s" target="_blank">Share</a>',$fb_share_url);
 }
endif;

if (!function_exists('ydn_twitter_link') ):
  function ydn_twitter_link() {
    global $post;
    $twitter_params = array( "url" => get_permalink(),
                        "text" => 'Checkout "' . get_the_title() . '"! ' . get_permalink(),
                        "related" => "yaledailynews" );
    $twitter_share_url = "https://twitter.com/share?" . http_build_query($twitter_params);
    printf('<a href="%1$s" target="_blank">Tweet</a>',$twitter_share_url);
 }
endif;

/* *
 * This function is used to get the section for stories.
 * It assumes that each story is in one top-level category,
 * and returns the name of the first top-level cat it encounters.
 * If no category is applied, it will simply return an empty string 
 *
 * Defaults to the current global post if none specified
 * */
if (!function_exists('ydn_get_top_level_cat') ):
  function ydn_get_top_level_cat( ) {
    global $post;
    $cats = wp_get_post_categories( $post->ID, array("fields" => "all" ));
    foreach ($cats as $cat) {
      if ( $cat->parent == 0 ) {
        return $cat->name;
      }
    }
    return '';
  }
endif;


/* *
 * Draws the no-javascript carousel elements in place. Renders the container structure
 * for the javascript-enabled version into a template at the bottom of the page.
 *
 * Function requires an array of posts and an HTML ID.  Allows args array as well.
 */ 
if (!class_exists('YDN_Carousel') ):
  class YDN_Carousel {
    public function __construct($posts, $html_id ) {
      $this->posts = $posts;
      $this->html_id = $html_id;

      $this->render_no_js();
      add_action('wp_print_footer_scripts', array($this, 'render_js_template') );
    } 
    private $posts, $html_id, $args;

    private function render_no_js( ) {
      global $post;
      $temp_post = $post; //preserve the global post variable
      $i = 0;

      $post = $this->posts[0];
      ?>   
      <div id="<?php echo $this->html_id; ?>" class="carousel slide no-js">
        <div class="carousel-inner">
          <div class="item active">
            <?php the_post_thumbnail('home-carousel'); ?>
          </div>
        </div>
     </div>
    <?php
    $post = $temp_post;
    }
    
    //must be public so that it can be called from the callback
    public function render_js_template() {
      global $post;
      $temp_post = $post;
      $i = 0;

      ?>
        <script id="<?php echo $this->html_id; ?>-template" type="text/html">
          <div class="carousel-inner">
          <?php 
                foreach( $this->posts as $post): 
                  setup_postdata($post); 
                  if (get_post_type($post->ID) == 'slideshow-slide') {
                    //setup the variables for the custom posts that link to arbitrary URLs
                    $url = get_post_meta(  $post->ID, 'ydn_slideshow_url', true );
                    $is_custom_post = true;
                  } else {
                    $url = get_permalink($post->ID);
                    $is_custom_post = false;
                  }
            ?>
              <div class="item<?php if ($i == 0): ?> active<?php endif;?>" data-post-id="<?php echo $post->ID; ?>">
                <?php the_post_thumbnail('home-carousel'); ?>
                <div class="carousel-caption">
                  <?php echo $this->render_navlist($i);  ?>
                  <div class="meta">
                    <h3><a href="<?php echo $url; ?>"><?php the_title(); ?></a></h3>
                    <p><span class="bylines"><?php coauthors_posts_links(); ?></span> &bull; <?php echo get_the_excerpt(); ?></p>
                  </div>
                </div>
              </div>
            <?php $i++; endforeach; ?>
          </div>
        </script>

      <?php
      $post = $temp_post;
    }

    private function render_navlist($active_index) {
      global $post;
      $temp_post = $post;
      $output = '<ul class="unstyled navlist">';
      $i = 0;
      foreach ($this->posts as $post) {
        setup_postdata($post); 
        if (get_post_type($post->ID) == 'slideshow-slide') {
          //setup the variables for the custom posts that link to arbitrary URLs
          $cat = get_post_meta( $post->ID, 'ydn_slideshow_cat', true );
        } else {
          $cat = ydn_get_top_level_cat();
        }

        $output = $output .  "<li data-post-id=\"$post->ID\"";
        if ($i == $active_index) {
          $output = $output . " class=\"arrow\"";
        }
        $output = $output . ">" . $cat .  "</li>";
        $i++;
      }

      $output = $output . "</ul>";
      $post = $temp_post;

      return $output;
   }
 }
endif; //class_exists

if (! function_exists("ydn_single_pre_content") ):
  function ydn_single_pre_content() {
    //the content that gets printed before the actual text of an article
    //plugged by the WEEKEND theme to replace social sharing with WEEKEND specific styles
  ?>
    <ul class="social-share unstyled">
      <li class="facebook"><?php ydn_facebook_link(); ?></li>
      <li class="twitter"><?php ydn_twitter_link(); ?></li>
      <li class="discuss"><a href="#comments-title">Discuss</a></li>
    </ul>
  <?php
  }
endif;

if (! function_exists("ydn_comment_count") ):
  function ydn_comment_count() {
    //prints a link to the article w/ the number of comments
    global $post;
    ?>
    <a href="<?php echo get_permalink();?>#comments-title" class="comment-count">(<?php echo $post->comment_count; ?>)</a> 
    <?php
  }
endif;

/**
 * Returns true if a blog has more than 1 category
 *
 * @since ydn 1.0
 */
function ydn_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so ydn_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so ydn_categorized_blog should return false
		return false;
	}
}

/**
 * Returns the most recent attachment that has the specified "special type" metadata 
 */
function ydn_get_special_image($type,$size) {
  //pull the image of special type $type, then render it in $size
  $args = array( 'post_type' => 'attachment',
                 'post_status' => 'any',
                 'posts_per_page' => 1,
                 'order' => 'DESC',
                 'orderby' => 'date',
                 'meta_query' => array( array( 'key' => '_ydn_attachment_special_type', 'value' => $type, 'type' => 'BINARY') )
               );
  $query = new WP_Query($args);

  //rendering time
  if ($query->have_posts()) {
    $attach_id = $query->posts[0]->ID;
    echo wp_get_attachment_image($attach_id, $size);
  }
  
}


/**
 * Flush out the transients used in ydn_categorized_blog
 *
 * @since ydn 1.0
 */
function ydn_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'ydn_category_transient_flusher' );
add_action( 'save_post', 'ydn_category_transient_flusher' );


