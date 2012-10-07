<?php
$posts = z_get_posts_in_zone('homepage');
/* $posts should be an array of 10 posts. The first 5 go clockwise in the top half of the layout,
 * the second 5 go clockwise in the bottom half of the layout.  I tried to give some orientation to it
 * so that the editors can layout the page easier. That's why the order of the indicies seems so crazy
 * on the bottom of the page */
?>
<?php get_header(); ?>

  <div id="weekend">
    <?php require get_stylesheet_directory() . '/topnav.php' ?>
    <div class="blocks">
    
      <div class="block-group big">
        <?php weekend_render_block($posts[0], 'big'); ?>      
        <?php weekend_render_block($posts[4], 'medium'); ?>      
      </div> <!-- .block-group -->

      <div class="block-group small"> 

        <?php weekend_render_block($posts[1], 'small'); ?>      
        <?php weekend_render_block($posts[2], 'small'); ?>      
        <?php weekend_render_block($posts[3], 'small'); ?>      
      </div> <!-- .block group -->

      <div class="block-group small"> 
        <?php weekend_render_block($posts[4], 'small'); ?>      
        <?php weekend_render_block($posts[7], 'small'); ?>      
        <?php weekend_render_block($posts[8], 'small'); ?>      
      </div> <!-- .block group -->

      <div class="block-group big">
        <?php weekend_render_block($posts[5], 'big'); ?>      
        <?php weekend_render_block($posts[6], 'medium'); ?>      
      </div> <!-- .block-group -->


    </div>
  </div>

<?php get_footer(); ?>
