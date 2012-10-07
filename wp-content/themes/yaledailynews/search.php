<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package ydn
 * @since ydn 1.0
 */

get_header(); ?>

		<section id="primary" class="site-content">
			<div id="content" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'ydn' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				</header>

        <div class="content-list archive"> 
          <?php /* Start the Loop */ ?>
          <?php while ( have_posts() ) : the_post(); ?>

            <?php get_template_part( 'list', ydn_get_post_format() ); ?>

          <?php endwhile; ?>
        </div><!-- .content-list --> 
				<?php ydn_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<?php get_template_part( 'no-results', 'search' ); ?>

			<?php endif; ?>

			</div><!-- #content -->
		</section><!-- #primary .site-content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
