<?php
/**
 * @package ydnxc
 * @since ydnxc 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
    <?php ydnxc_post_header(); ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->
  <?php ydnxc_get_featured_image(); ?>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'ydnxc' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php edit_post_link( __( 'Edit', 'ydnxc' ), '<span class="edit-link">', '</span>' ); ?>

		<?php
			/* translators: used between list items, there is a space after the comma */
			$category_list = get_the_category_list( __( ', ', 'ydnxc' ) );

			/* translators: used between list items, there is a space after the comma */
			$tag_list = get_the_tag_list( '', ', ' );

      if ( $category_list != '' || $tag_list != '' ):
        echo '<div class="filed-under">FILED UNDER: ';
        echo $category_list;
        if ($category_list != '' && $tag_list != '') { echo ', '; }
        echo $tag_list;
        echo '</div>';
      endif;
		?>

	</footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
