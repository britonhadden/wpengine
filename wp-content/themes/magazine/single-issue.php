<?php
  $issue_content = YDN_Mag_Issue_Type::get_content(get_the_id());
  $issue_content = ($issue_content == null) ? array() : $issue_content;
  get_header();
?>
  <div class="span24">
    <!-- Top layer containing the top-stories from the issue -->
    <div class="row" id="top-content">
      <div id="cover">
        <?PHP
        if($issue_content["top_content"] !== null && array_key_exists(0,$issue_content["top_content"])) {
          $temp_post = $post;
          $post = $issue_content["top_content"][0];
          setup_postdata($post);
          ?>
          <a href="<?php echo get_permalink();?>"><?php the_post_thumbnail('magazine_cover_image'); ?></a>
          <?php
          $post = $temp_post;
        }
        ?>
      </div>
      <div class="content-list essays">
        <!-- the three large essay blocks at the top of the page -->
        <?php
          $content = $issue_content["top_content"] == null ? array() : array_slice($issue_content["top_content"],1);
          foreach($content as $post): setup_postdata($post);
        ?>
        <div class="item">
          <a href="<?php echo get_permalink(); ?>"><?php the_post_thumbnail('magazine_top_long'); ?></a>
          <div class="overlay">
            <?php ydn_mag_post_info(); ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <!-- Lower half of the page: essays, cross-campus, etc. -->
    <h1 class="mag-section">Essays</h1>
    <div class="row">
      <div class="span18">
       <!-- begin left column: mag content -->
        <div class="content-list essays" id="bottom-essays">
          <div class="dominant item">
            <?php
              if($issue_content["essays"] !== null && array_key_exists(0, $issue_content["essays"])):
                $post = $issue_content["essays"][0];
                setup_postdata($post);
            ?>
                <a href="<?php echo get_permalink(); ?>"><?php the_post_thumbnail('magazine_bottom_long'); ?></a>
                <div class="overlay"><?php ydn_mag_post_info(false) ?></div>
            <?php endif; ?>
          </div>
          <div class="row">
            <?php
              $content = $issue_content["essays"] == null ? array() : array_slice($issue_content["essays"],1);
              foreach($content as $post): setup_postdata($post);
            ?>
              <div class="item">
                <a href="<?php echo get_permalink(); ?>" class="thumb" ><?php the_post_thumbnail('magazine_span4'); ?></a>
                <div class="wrapper">
                  <?php ydn_mag_post_info(true); ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <!-- end of bottom essay block -->
        <!-- beginning of smalltalk/shorts/poetry -->
        <div class="row">
          <div class="span6">
            <!-- small talks -->
            <h1 class="mag-section border">Small Talk</h1>
            <div class="content-list">
                <?php
                $content = $issue_content["small_talk"] == null ? array() : $issue_content["small_talk"];
                foreach($content as $post): setup_postdata($post);
                ?>
                <div class="item">
                  <a href="<?php echo get_permalink(); ?>"><?php the_post_thumbnail('magazine_small_talk'); ?></a>
                  <a class="headline" href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
                  <div class="meta">
                    <span class="bylines">By <?php coauthors_posts_links(); ?></span>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="span11 offset1">
            <!-- contains shorts, poetry, and photo gallery -->
            <div class="row" id="shorts-poetry">
              <div class="span5">
                <!-- shorts -->
                <h1 class="mag-section border">Shorts</h1>
                <div class="content-list">
                  <?php
                  $content = $issue_content["shorts"] == null ? array() : $issue_content["shorts"];
                  foreach($content as $post): setup_postdata($post);
                  ?>
                    <h5 class="item"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h5>
                  <?php endforeach; ?>
                </div>
              </div>
              <div class="span5 offset1">
                <!-- poetry -->
                <h1 class="mag-section border">Poetry</h1>
                <div class="content-list">
                  <?php
                  $content = $issue_content["poetry"] == null ? array() : $issue_content["poetry"];
                  foreach($content as $post): setup_postdata($post);
                  ?>
                    <h5 class="item"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h5>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="span11" style="height:300px; background-color:brown;">
                <!-- standalone/gallery image -->
              </div>
            </div>
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
