<div class="item">
  <div class="section"><?php echo ydn_get_top_level_cat(); ?></div>
  <a class="headline" href="<?php echo get_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
  <div class="datetime"><?php the_time(); ?></div>
  <div class="teaser"><?php echo get_post_meta($post->ID,'ydn_homepage_excerpt',true); ?> <a href="<?php echo get_permalink(); ?>">››</a></div>
</div>
