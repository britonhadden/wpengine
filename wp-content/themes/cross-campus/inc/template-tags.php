<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package ydnxc
 * @since ydnxc 1.0
 */

if ( ! function_exists( 'ydnxc_content_nav' ) ):
/**
 * Display navigation to next/previous pages when applicable
 *
 * @since ydnxc 1.0
 */
function ydnxc_content_nav( $nav_id ) {
	global $wp_query;

	$nav_class = 'site-navigation paging-navigation';
	if ( is_single() )
		$nav_class = 'site-navigation post-navigation';

	?>
	<nav role="navigation" id="<?php echo $nav_id; ?>" class="<?php echo $nav_class; ?>">
		<h1 class="assistive-text"><?php _e( 'Post navigation', 'ydnxc' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>
    <div class="clearfix">
      <?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'ydnxc' ) . '</span> %title' ); ?>
      <?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'ydnxc' ) . '</span>' ); ?>
    </div>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
    <?php if ( get_next_posts_link() || get_previous_posts_link() ) : ?>
      <div class="clearfix">
        <?php if ( get_next_posts_link() ) : ?>
        <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'ydnxc' ) ); ?></div>
        <?php endif; ?>

        <?php if ( get_previous_posts_link() ) : ?>
        <div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'ydnxc' ) ); ?></div>
        <?php endif; ?>
      </div>
    <?php endif; ?>

	<?php endif; ?>
  <?php if ( $nav_id == "nav-below" ): //the bottom navigation lists the featured stories in addition to the next/previous links ?>
    <div id="featured-posts">
      <ul>
      <?php
        global $post;
        $temp_post = $post;
        $featured_posts = z_get_zone_query('cross-campus-featured-posts');
        $featured_index = 0;
        while ( $featured_posts->have_posts() && $featured_index < 4 ):
          $featured_posts->the_post(); 
        ?><li><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('featured-story-list'); ?></a><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li><?php
        //make sure there are no extra spaces around the LI
          $featured_posts++;
        endwhile; 
        $post = $temp_post;
      ?>
      </ul>
    </div> 
  <?php endif; ?>

	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php
}
endif; // ydnxc_content_nav

if ( ! function_exists( 'ydnxc_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since ydnxc 1.0
 */
function ydnxc_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'ydnxc' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', '_s' ), ' ' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header>
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 40 ); ?>
					<?php printf( __( '%s:', 'ydnxc' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				</div><!-- .comment-author .vcard -->
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'ydnxc' ); ?></em>
					<br />
				<?php endif; ?>
			</header>

			<div class="comment-content"><?php comment_text(); ?></div>
      <footer class="clearfix">
        <a class="pull-left" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( 'Posted on %1$s at %2$s', 'ydnxc' ), get_comment_date(), get_comment_time() ); ?>
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
endif; // ends check for ydnxc_comment()

if ( ! function_exists( 'ydnxc_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since ydnxc 1.0
 */
function ydnxc_posted_on() {
	printf( __( 'Posted on <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="byline"> by <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'ydnxc' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'ydnxc' ), get_the_author() ) ),
		esc_html( get_the_author() )
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category
 *
 * @since ydnxc 1.0
 */
function ydnxc_categorized_blog() {
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
		// This blog has more than 1 category so ydnxc_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so ydnxc_categorized_blog should return false
		return false;
	}
}

// this function renders the header that tops off posts throughout XC
if (!function_exists('ydnxc_post_header') ):
function ydnxc_post_header() {
  global $post;
  $pieces = array();
  if ( is_attachment() ) {
   //if it's an attachment show time | date | credit 
    array_push($pieces, get_the_time());
    array_push($pieces, get_the_date());
    array_push($pieces, 'By ' . get_media_credit_html());
  } else {
    //if it's a regular post, show category | time | date | authors
    $cats = get_the_category();
    if(!empty($cats)) {
      array_push($pieces, $cats[0]->name); //there should only be one category per post, which is used as the primary tag
    }

    array_push($pieces, get_the_time() );
    array_push($pieces, get_the_date() );
    array_push($pieces, 'By ' . coauthors_posts_links(null,null,null,null,false)); //the false makes it return the value instead of echoing it
  }
  
  ?>
  <div class="divider">
    <div>
      <?php echo implode($pieces,' | '); ?>
    </div>
  </div>
  <?php
}
endif;

/**
 * Returns a div with the post's featured image and associated metadata (e.g. caption, authors..)
 * meant to be used within the loop. uses global $post
 */
if (! function_exists( 'ydnxc_get_featured_image') ):
function ydnxc_get_featured_image() {
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
 * Flush out the transients used in ydnxc_categorized_blog
 *
 * @since ydnxc 1.0
 */
function ydnxc_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'ydnxc_category_transient_flusher' );
add_action( 'save_post', 'ydnxc_category_transient_flusher' );
