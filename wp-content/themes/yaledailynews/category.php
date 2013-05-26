<?php 
$ydn_suppress_thumbnails = true; // ugly hack, but necessary to pass variables to template
$current_cat_ID = get_category(get_query_var('cat'), false)->cat_ID;
$current_cat_slug = get_category(get_query_var('cat'), false)->slug;
?>
<?php get_header(); ?>

<div class="span19">
  <h1><?php single_cat_title(); ?></h1>
  <div class="row">
    <div class="span19">
      <div class="span5 middle-column">
        <!-- Editorial columns -->
        <?php
        if ($current_cat_slug != 'city' && $current_cat_slug != 'university' && $current_cat_slug != 'features') :?>
        <div class="column">
          <h3>Editorial</h3>
          <?php
          $columns = get_post_list(null, null, array(13, $current_cat_ID));
          foreach( $columns as $post ) : ?>
            <div class="column-box">
              <a href="<?= $post->guid ?>"><h4><?= $post->post_title ?></h4></a>
              <span><?= $post->post_excerpt ?></span>
            </div>
          <?php 
          endforeach; ?>
        </div>
        <?php
        endif; ?>

        <!-- Multimedia clips -->
        <div class="media">
          <h3>Media</h3>
          <?php
          $mult = get_post_list($current_cat_slug . '-multimedia');
          foreach($mult as $clip) : 
$img_src = wp_get_attachment_image_src(get_post_thumbnail_id($clip->ID), 'video-thumbnail'); ?>
            <div class="media-box">
              <a class="media-box-hover" 
                  href="<?= site_url('/ytv#' . basename($clip->guid)); ?>"
                  style="background: url(<?= $img_src[0] ?>);">
                <span class="media-box-hover-text"><?= $clip->post_title?></span>
              </a>
            </div>
          <?php
          endforeach; ?>
        </div>
      </div>

    <!-- Featured article --> 
    <div class="featured-article">
      <?php 
      $featured_post = get_first_post_with_img(); ?>
      <?= get_the_post_thumbnail( $featured_post->ID, 'featured-cat-img'); ?>
      <a href="<?= $featured_post->guid ?>"><h3 class="featured-headline"><?= $featured_post->post_title ?></h3></a>
      <span><?= $featured_post->post_excerpt ?></span>
    </div>

    <!-- Posts list -->
    <div class="content-list">
      <?php
      $post_index = 0;
      while ( have_posts() ) : 
        the_post();
        if ( get_the_ID() != $featured_post->ID ) : ?>
          <div class="content-item">
            <?php
            if ( $is_thumbnail = has_post_thumbnail() )
              the_post_thumbnail( array(100, 100), array('class' => '', 'style' => 'float: left; margin-right: 5px;')); ?>
            <a href="<?= get_permalink(); ?>"><h4><?= get_the_title(); ?></h4></a>
            <div class="meta">
              <span class="bylines">by <?= get_the_author(); ?></span>
              <?= ydn_timestamp(); ?>
            </div>
            <p class="" style="width: 100%;"><?= get_the_excerpt(); ?></p>
          </div>
      <?php   
          $post_index++;
        endif;
      endwhile; ?>
      </div>
    </div>

  </div>
</div>

<div class="span5 sidebar-widgets">
  <?php dynamic_sidebar('opinion-sidebar'); ?>
</div><!-- sidebar wrapper -->

<?php get_footer(); ?>
