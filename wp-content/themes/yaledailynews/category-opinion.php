<?php 
  $friday_forum_content = z_get_posts_in_zone("opinion-friday-forum");
  $standard_content = z_get_posts_in_zone("opinion-standard");
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
      <?php if (empty($friday_forum_content)): /* the regular layout */ ?>
        <?php $standard_content = ydn_fix_list_size($standard_content,'opinion',4); ?>
        <div class="content-list">
          <?php $post = array_shift($standard_content);  setup_postdata($post); get_template_part('list','opinion'); ?>
        </div>
      <?php else: /* the friday-forum layout */ ?>

      <?php endif; ?>
    </div>
    <div class="span5 content-list narrow borders">
      <?php $standard_content = ydn_fix_list_size($standard_content,'opinion',3); ?> 
      <?php foreach( $standard_content as $post ): setup_postdata($post); ?>
        <?php get_template_part('list', 'opinion') ?>
      <?php endforeach; ?>
    </div>  
  </div>

  <div class="double-border"></div>

  <div class="row">
    <div class="span19">
      <ul id="opinion-cat-selector" class="nav nav-tabs">
        <li class="active"><a href="#staff-columns" data-toggle="tab">Staff Columns</a></li>
        <li><a href="#guest-columns" data-toggle="tab">Guest Columns</a></li>
        <li><a href="#the-news-views" data-toggle="tab">News' Views</a></li>
        <li><a href="#letters" data-toggle="tab">Letters</a></li>
        <li><a href="#oped-live" data-toggle="tab">Op-Ed Live</a></li>
      </ul>
      <div class="tab-content" id="opinion-cat-content">
        <?php 
          function ydn_opinion_lower_content($slug, $class = ''){ ?>
            <div class="tab-pane content-list <?php echo $class; ?>" id="<?php echo $slug; ?>">
            <?php
            global $ydn_opinion_class, $ydn_show_auth_thumb, $post;

            //grab enough content to fill out the page
            $opinion_lower_content = ydn_fix_list_size(array(),$slug,8);
            $ydn_show_auth_thumb = true;
            $i = 0; //count the number of iterations, so that we can set the even class
            foreach($opinion_lower_content as $post): setup_postdata($post);
              if($i % 2 == 0 ) {
                $ydn_opinion_class = "";
                echo '<div class="row">';
              } else {
                $ydn_opinion_class = "even";
              }
              //$ydn_opinion_class = ($i % 2) ? "even" : "";
              get_template_part('list','opinion');
              if ($i++ % 2 != 0 ) {
                echo '</div>';
              }

            endforeach;

            //set these back to neutral values
            $ydn_opinion_class = '';
            $ydn_show_auth_thumb = false;
            ?>
            </div>
            <?php
          }
        ?>
        <?php ydn_opinion_lower_content('staff-columns','active'); ?>
        <?php ydn_opinion_lower_content('guest-columns'); ?>
        <?php ydn_opinion_lower_content('the-news-views'); ?>
        <?php ydn_opinion_lower_content('letters'); ?>
        <?php ydn_opinion_lower_content('oped-live'); ?>
      </div>
    </div>
  </div>

</div><!-- main column wrapper -->
<div class="span5">
  sidebar
</div><!-- sidebar wrapper -->
<?php get_footer(); ?>
