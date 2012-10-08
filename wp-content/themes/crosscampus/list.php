<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
  <header class="entry-header">
    <?php  ydnxc_post_header(); ?>
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'ydnxc' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
  </header>
  <?php if (has_post_thumbnail() ): ?>
    <div class="entry-thumbnail">
      <?php the_post_thumbnail('home-entry-list'); ?>
    </div>
  <?php endif; ?> 
  <div class="entry-summary">
    <?php the_excerpt(); ?>
		<?php edit_post_link( __( 'Edit', 'ydnxc' ), '<span class="sep"> | </span><span class="edit-link">', '</span>' ); ?>
	</div><!-- .entry-summary -->
</article>
