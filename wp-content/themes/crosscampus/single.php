<?php
/**
 * The Template for displaying all single posts.
 *
 * @package ydnxc
 * @since ydnxc 1.0
 */

get_header(); ?>

		<div id="primary" class="site-content">
			<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'single' ); ?>
      

				<?php
					// If comments are open or we have at least one comment, load up the comment template
          if ( comments_open() || '0' != get_comments_number() ) {
            if ( function_exists('dsq_is_installed') &&  dsq_is_installed() ) {
              echo '<div class="divider section"><div>Comments</div></div>';
            }
            comments_template( '', true );
          }
        ?>

				<?php ydnxc_content_nav( 'nav-below' ); ?>
			<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary .site-content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
