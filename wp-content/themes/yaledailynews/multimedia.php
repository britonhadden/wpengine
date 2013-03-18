<?php
/*
Template Name: Multimedia
*/
get_header(); ?>

<?php
    global $post;
    $args = array( 'numberposts' => 21, 'post_type' => 'video', 'category' => 3034 ); // 3034 is the category id for a package, we should really have numberposts = infinity and get all the posts and only display a certain amount at a time. 
    $myposts = get_posts( $args );
    for ($i = 0; $post = $myposts[$i]; $i++) :
      setup_postdata($post);
      $url = strtok(get_the_content(), '\n');     
      $content = strtok('');
      $youtubeURL = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
      if (preg_match($youtubeURL, $url, $match))
        $video_id = $match[1];
      $active = ($i == 0 ? "active" : "");
?>
<?php if ($i == 0) : ?>
  <div class="span24">
    <div class="row">
            <div id="main-theater" class="span24">
            <iframe id="video-player" src="http://www.youtube.com/embed/<?= $video_id ?>" frameborder="0">
                </iframe>
            </div>
    </div>
    <div class="row">
      <div id="slider" class="span24">
        <div class="carousel slide">
          <div class="carousel-inner">
<?php endif; ?>
<?php if ($i % 7 == 0) : ?>
            <div class="item <?= $active ?>">
              <ul>
<?php endif; ?>
                <li>
                  <p class="crop" title="<?php the_title() ?>">
                    <a href="#" data-videoid="<?= $video_id?>" data-author="<?php the_author() ?>" rel="tooltip" class="thumbnail-video" title="<?= the_title() ?>">
                    <p data-videoid="<?= $video_id?>" class="video-content"><?php echo $content ?></p>
                          <img class="thumbnail-youtube" src="http://img.youtube.com/vi/<?= $video_id;?>/0.jpg"/>
                      </a>
                  </p>
                </li>
<?php if (($i + 1) % 7 == 0) : ?>
              </ul>
            </div>
<?php endif;
endfor; ?>
          </div>
        </div>
      <a href="#" class="left carousel-control">‹</a>
      <a href="#" class="right carousel-control">›</a>
    </div> <!-- end of carousel -->
  </div> <!-- end of slider -->
</div> <!-- end of row-->
<?php setup_postdata($myposts[0]) ?>
<div class="row">
  <div class="span12"><h6 id="theatre-video-title"><?php the_title() ?></h6></br>
    <p id="theatre-video-author">by <?php the_author() ?></p>
    <p id="theatre-video-excerpt">
<?php 
strtok(get_the_content(), '\n');
echo strtok('');
?>
    </p>
  
  </div>
  <div class="span12 archives-box">archives two</div>
</div>
</div>
</div><!-- #container -->

<?php get_footer(); ?>
