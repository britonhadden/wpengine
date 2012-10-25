<?php
function ydn_mag_post_info($teaser = false) {
  global $post;
?>
  <div class="section"><?php  echo ydn_get_top_level_cat(); ?></div>
  <a class="headline" href="<?php echo get_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
  <div class="meta"><span class="bylines">By <?php coauthors_posts_links(); ?></span></div>
  <?php if ($teaser):?>
  <div class="teaser"><?php echo get_the_excerpt(); ?></div>
  <?php endif;
}

?>
