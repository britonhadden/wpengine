<?php
/**
 * @package ydn
 * @since ydn 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
    <?php ydn_get_featured_image(); ?>
		<div class="entry-meta">
      <div class="entry-authors">By <?php ydn_authors_with_type(); ?></div>
      <div class="entry-pubdate"><?php ydn_posted_on(); ?></div>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
    <?php ydn_single_pre_content(); ?>
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'ydn' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
