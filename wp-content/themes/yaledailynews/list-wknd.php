<div class="item">
  <?php $cat = ydn_get_top_level_cat(); ?>
  <a class="headline" href="<?php echo get_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
  <div class="datetime"><?php echo ydn_timestamp(); ?></div>
  <div class="teaser"><?php echo get_post_meta($post->ID,'ydn_homepage_excerpt',true); ?> <a href="<?php echo get_permalink(); ?>">››</a></div>
</div>
