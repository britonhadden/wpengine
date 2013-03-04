<?php
  /* grab the content for the page */
  $home_content = YDN_homepage_content::get_instance();
?>
<?php get_header(); ?>
	<div class="span24" id="recruit-banner"><a class="bentonsanscondmedium" href="https://docs.google.com/spreadsheet/viewform?fromEmail=true&formkey=dGI1NGJ0UDRQYTR4VVlUVi1tX0VMR2c6MQ">Join the YDN!</a> </div>
    <div class="span19"> <!-- contains all content except right most column -->
      <div class="row border7">
        <div class="span7 content-list narrow borders" id="top-three">
        <?php
          $ydn_suppress_thumbnails = true; // ugly hack, but necessary to pass variables to template

          foreach ($home_content->get_top_three_content() as $post):
            setup_postdata($post);

            get_template_part('list', ydn_get_post_format());
          endforeach;
        ?>

        </div> <!-- #top-three -->
        <div class="span12" id="slideshow-multimedia">
          <?php new YDN_Carousel( $home_content->get_slideshow_content(), "home-carousel" ); ?>
          <div class="row" id="video-thumbnails">
            <?php
            foreach ( $home_content->get_videos() as $post ) : setup_postdata($post);
            ?>
              <div class="span4 item">
                <a href="<?php echo get_permalink(); ?>" class="image"><?php the_post_thumbnail('video-thumbnail'); ?><span></span></a>
                <a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
              </div>
            <?php endforeach; ?>
          </div> <!-- #video-thumbnails -->
          <div id="more-videos"><a href="/blog/video">More Videos &raquo;</a></div>
        </div><!-- #sldieshow-multimedia -->
      </div>
      <!-- starting the most-read/opinion section -->
      <div class="double-border"></div>

      <div class="row border6">
        <div class="span6" id="ydn-popular-posts">
          <!-- most popular/most viewed -->
          <?php if (function_exists('nrelate_popular')) nrelate_popular(); ?>
        </div>

        <div class="span13 print-section" id="opinion-section">
          <!-- opinion -->
          <h1>Opinion</h1>
          <div class="content-list">
            <?php
              foreach ($home_content->get_post_list("opinion", 5, null,686) as $post):
                setup_postdata($post);
            ?>
            <div class="item">
              <a class="headline" href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
              <span class="meta">&bull; <span class="bylines"><?php coauthors_posts_links(); ?></span> &bull; <?php ydn_comment_count(); ?> </span>
            </div>
            <?php endforeach;  ?>
          </div>
        </div>
      </div> <!-- end row.border6 -->

      <div class="double-border"></div>

      <!-- starting individual news sections on bottom of page -->
      <div class="row border6">
        <div class="span6">
          <!-- column with sports/weekend/today's paper -->
          <div class="print-section">
            <h1>Sports</h1>
            <?php
              $sports_content = $home_content->get_content_for_cat("sports",2); //this is the list/featrued array
              $sports_stories = $sports_content["list"]; //this is a flat array of stories to be looped through
              array_unshift( $sports_stories, $sports_content["featured"] );
            ?>
            <a href="<?php echo get_permalink($sports_content["featured"]->ID); ?>">
            <?php
              echo get_the_post_thumbnail($sports_content["featured"]->ID, 'home-print-section-narrow');
            ?>
            </a>
            <div class="content-list">
              <?php foreach( $sports_stories as $post ): setup_postdata($post); ?>
                <div class="item">
                  <a href="<?php the_permalink(); ?>" class="headline"><?php the_title(); ?></a>
                  <span class="meta"><a href="<?php the_permalink(); ?>" class="comment-count"><?php ydn_comment_count(); ?></a></span>
                </div>
              <?php endforeach; ?>
            </div><!-- content-list -->
          </div><!-- print-section -->
					<div class="print-section">
						<h1>Comics</h1>
			<?php 
							$comic_content= $home_content->get_content_for_cat("comics");?>
							<a href="<?php echo get_permalink($comic_content["featured"]->ID); ?>">
							<?php echo get_the_post_thumbnail($comic_content["featured"]->ID, 'home-print-section-narrow'); ?>
							</a>
					</div><!-- content-list -->

          <div class="print-section">
            <h1>Today's Paper</h1>
            <a href="http://issuu.com/yaledailynews" id="todayspaper" target="_blank"><?php ydn_get_special_image("front_page","home-print-section-narrow"); ?></a>
          </div><!-- print-section -->
          <div class="print-section">
            <h1>WEEKEND</h1>
            <a href="/weekend"><?php ydn_get_special_image("weekend_cover","home-print-section-narrow"); ?></a>
          </div><!-- print-section -->
          <div class="print-section">
            <h1>Magazine</h1>
            <a href="/magazine"><?php ydn_get_special_image("magazine_cover","home-print-section-narrow"); ?></a>
          </div><!-- print-section -->

        </div><!-- .span6 -->

        <div class="span13">
          <!-- column with content for most of the sections of the paper -->
          <?php ydn_home_print_section($home_content, "university"); ?>
          <div class="double-border"></div>
          <?php ydn_home_print_section($home_content, "city"); ?>
          <div class="double-border"></div>
          <?php ydn_home_print_section($home_content, "culture"); ?>
          <div class="double-border"></div>
          <?php ydn_home_print_section($home_content, "sci-tech"); ?>
        </div>

      </div><!-- end row.border6 -->

    </div> <!-- end all content except right most column -->

    <div class="span5"> <!-- right most column -->
      <div id="cross-campus" class="widget">
        <a id="cross-campus-widget-header" href="/crosscampus"><h2>CROSS</h2> <h1>CAMPUS</h1></a>
        <div class="content-list borders">
          <?php
            switch_to_blog(XC_BLOG_ID);
            foreach ($home_content->get_xc_posts() as $post):
              setup_postdata($post);
              get_template_part('list','xc');
            endforeach;
            restore_current_blog();
          ?>
        </div>
        <a class="more" href="/crosscampus">More from the XC blog</a>
      </div> <!-- end #cross campus -->
      <div id="wknd-widget" class="widget">
		<a id="wknd-widget-header" href="/weekend/category/blog"><h1>WKND</hw> <h2>BLOG</h2></a>
        <div class="content-list borders">
          <?php
            switch_to_blog(WKND_BLOG_ID);
            foreach ($home_content->get_WKND_posts() as $post):
              setup_postdata($post);
              get_template_part('list','wknd');
            endforeach;
            restore_current_blog();
          ?>
        </div>
        <a class="more" href="/weekend/category/blog">More from WEEKEND</a>
      </div> <!-- end #cross campus -->
      <div class="sidebar-widgets">
        <?php dynamic_sidebar('home-advertisements'); ?>
      </div>
    </div> <!-- end right most column -->
<?php get_footer(); ?>

