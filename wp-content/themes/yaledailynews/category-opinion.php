<?php 
  $friday_forum_content = z_get_posts_in_zone("opinion-friday-forum");
  $main_content = z_get_posts_in_zone("opinion-main-posts");
  $ydn_suppress_thumbnails = true; // ugly hack, but necessary to pass variables to template
?>
<?php get_header(); ?>
<h1 class="span24 page-title">Opinion</h1>
<div class="span19">
  <div class="row border14">
    <div class="span14 featured">
      <!-- This is the section with the featured opinion image & main story.  If Friday Forum is available,
           it's drawn here as well -->
      <?php ydn_get_special_image("opinion_featured_image","opinion-featured"); ?>
      <?php if (empty( $friday_forum_content ) ): /* the regular layout */ ?>
        <div class="content-list">
          <?php $post = array_shift($main_content);  setup_postdata($post); get_template_part('list','standard'); ?>
        </div>
      <?php else: /* the friday-forum layout */ ?>

      <?php endif; ?>
    </div>
    <div class="span5 content-list narrow borders">
      <?php foreach( $main_content as $post ): setup_postdata($post); ?>
        <?php get_template_part('list', ydn_get_post_format()) ?>
      <?php endforeach; ?>
    </div>  
  </div>

  <div class="double-border"></div>

  <div class="row">
    <div class="span19">
      <ul id="opinion-cat-selector" class="nav nav-tabs">
        <li class="active"><a href="#staff-columns" data-toggle="tab">Staff Columns</a></li>
        <li><a href="#guest-columns" data-toggle="tab">Guest Columns</a></li>
        <li><a href="#news-views" data-toggle="tab">News' Views</a></li>
        <li><a href="#letters" data-toggle="tab">Letters</a></li>
        <li><a href="#op-ed-live" data-toggle="tab">Op-Ed Live</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="staff-columns">staff cols</div>
        <div class="tab-pane" id="guest-columns">guest cols</div>
        <div class="tab-pane" id="news-views">nvewss</div>
        <div class="tab-pane" id="letters">letters</div>
        <div class="tab-pane" id="op-ed-live">cols</div>
      </div>
    </div>
  </div>

</div><!-- main column wrapper -->
<div class="span5">
  sidebar
</div><!-- sidebar wrapper -->
<?php get_footer(); ?>
