<?php
  $issue_content = YDN_Mag_Issue_Type::get_content(get_the_id());
  get_header();
?>
  <div class="span24">
    <!-- Top layer containing the top-stories from the issue -->
    <div class="row" id="top-content">
      <div id="cover">
        <?PHP
        if(array_key_exists(0,$issue_content["top_content"])) {
          $temp_post = $post;
          $post = $issue_content["top_content"][0];
          setup_postdata($post);
          the_post_thumbnail('magazine_cover_image');
          $post = $temp_post;
        }
        ?>
      </div>
      <div class="content-list essays">
        <?php
          $content = array_slice($issue_content["top_content"],1);
          foreach($content as $post): setup_postdata($post);
        ?>
        <div class="item">
          <a href="<?php echo get_permalink(); ?>"><?php the_post_thumbnail('magazine_top_long'); ?></a>
          <div class="overlay">
            <div class="section"><?php  echo ydn_get_top_level_cat(); ?></div>
            <a class="headline" href="<?php echo get_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
            <div class="meta"><span class="bylines">By <?php coauthors_posts_links(); ?></span></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <!-- Lower half of the page: essays, cross-campus, etc. -->
    <h1>Essays</h1>
    <div class="row">
     <!-- begin left column: mag content -->
      <div id="bottom-content">
        <div class="content-list essays">
          <div class="dominant item">&ensp;</div>
          <div class="row">
            <?php
              $content = array_slice($issue_content["essays"],1);
              foreach($content as $post): setup_postdata($post);
            ?>
              <div class="item">
                <a href="<?php echo get_permalink(); ?>" class="thumb" ><?php the_post_thumbnail('magazine_span4'); ?></a>
                <div class="wrapper">
                  <div class="section"><?php  echo ydn_get_top_level_cat(); ?></div>
                  <a class="headline" href="<?php echo get_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
                  <div class="meta"><span class="bylines">By <?php coauthors_posts_links(); ?></span></div>
                  <div class="teaser"><?php echo get_the_excerpt(); ?></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
     <!-- begin sidebar -->
     <div class="offset1 span5 sidebar-widgets">
        <?php if(function_exists('dynamic_sidebar')) { dynamic_sidebar('magazine_home'); } ?>
     </div>
    </div>
  </div>
<?php get_footer(); ?>
