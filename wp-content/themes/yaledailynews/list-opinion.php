<div class="item opinion">
  <?php if (!( isset($GLOBALS['ydn_suppress_thumbnails']) &&  $GLOBALS['ydn_suppress_thumbnails']) && has_post_thumbnail() ): ?><div class="thumbnail  span2"><?php the_post_thumbnail('thumbnail'); ?></div><?php endif ?>
  <div class="section">
    <?php echo get_post_meta($post->ID, "ydn_opinion_column", true); ?>
  </div>
  <a class="headline" href="<?php echo get_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
  <div class="meta">
    <span class="bylines">By <?php coauthors_posts_links(); ?></span>
    <span class="datetime"><?php echo get_the_date(); ?> &bull; <?php ydn_comment_count(); ?></span>
  </div>
  <div class="teaser"><?php echo get_the_excerpt(); ?></div>
</div> 
