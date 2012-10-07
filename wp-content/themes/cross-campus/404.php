<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package ydnxc
 * @since ydnxc 1.0
 */

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<article id="post-0" class="post error404 not-found">
				<header class="entry-header">
					<h1 class="entry-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'ydnxc' ); ?></h1>
				</header>

				<div class="entry-content">
					<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'ydnxc' ); ?></p>

					<?php get_search_form(); ?>
				</div><!-- .entry-content -->
			</article><!-- #post-0 -->

    <?php ydnxc_content_nav( 'nav-below' ); ?>
		</div><!-- #content -->
	</div><!-- #primary .site-content -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
