<?php
/*
Template Name: Multimedia
*/
get_header(); ?>

	<div class="span24">
		<div class="row">
            <div id="main-theater" class="span24">
                <iframe src="http://www.youtube.com/embed/bPXTcgS8xZc" frameborder="0">
                </iframe>
            </div>
		</div>
		<div class="row">
			<div id="slider" class="span24">
				<div class="carousel slide">
					<div class="carousel-inner">
<?php
    global $post;
    $args = array( 'numberposts' => 21, 'post_type' => 'video', 'category' => 3034 ); // 3034 is the category id for a package, we should really have numberposts = infinity and get all the posts and only display a certain amount at a time. 
    $myposts = get_posts( $args );
			for ($i = 0; $post = $myposts[$i]; $i++) {
				setup_postdata($post);
				$url = strtok(get_the_content(), '\n');			
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            $video_id = $match[1];
        }
				$active = ($i == 0 ? "active" : "");
				if ($i % 7 == 0) {
?>
	<div class="item <?= $active ?>">
					<ul>
<?php
				}
?>
								<li>
									<p class="crop" title="<?php the_title(); ?>">
										<a href="#" rel="tooltip" class="thumbnail-video"  title="<?php the_title(); ?>">
													<img class="thumbnail-youtube" src="http://img.youtube.com/vi/<?php echo $video_id;?>/0.jpg"/>
											</a>
									</p>
								</li>
<?php 
				if (($i + 1) % 7 == 0) {
?>
					</ul>
					</div>
<?php
				}
			}
?>
</div>
</div>
						<a href="#" class="left carousel-control">‹</a>
						<a href="#" class="right carousel-control">›</a>
            </div> <!-- end of carousel -->
		</div> <!-- end of slider -->
		</div> <!-- end of row-->
		<div class="row">
			<div class="span12 archives-box">archives one</div>
			<div class="span12 archives-box">archives two</div>
		</div>
	</div>

		<?php the_post();?>		
	
		<?php the_content(); ?>		
</div><!-- #container -->

<?php get_footer(); ?>
