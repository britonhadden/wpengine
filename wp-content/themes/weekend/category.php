<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package ydn
 * @since ydn 1.0
 */

get_header(); ?>
      <?php if ( have_posts() ) : ?>

          <div id="weekend">
            <?php require get_stylesheet_directory() . '/topnav.php'; ?>
            <div class="blocks"> 
                <?php /* Start the Loop */ ?>
                <?php while ( have_posts() ) : the_post(); ?>

                <?php
                  weekend_render_block($post, 'small');              
                ?>

              <?php endwhile; ?>
            </div> <!-- end .blocks -->
            <?php ydn_content_nav( 'nav-below' ); ?>
          </div> <!-- #weekend -->
			<?php else : ?>
        <section id="primary" class="site-content">
            <div id="content" role="main">


              <?php get_template_part( 'no-results', 'archive' ); ?>
            </div><!-- #content -->
        </section><!-- #primary .site-content -->
        <?php get_sidebar(); ?>

			<?php endif; ?>
<?php get_footer(); ?>
