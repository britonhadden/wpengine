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
<ul>
<?php
    global $post;
    $args = array( 'numberposts' => 7, 'post_type' => 'video', 'category' => 3034 ); // 3034 is the category id for a package, we should really have numberposts = infinity and get all the posts and only display a certain amount at a time. 
    $myposts = get_posts( $args );
    foreach( $myposts as $post ) :
        setup_postdata($post);
        $url = strtok(get_the_content(), '\n');
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            $video_id = $match[1];
        }
?>
<li>
	<a href="#" rel="tooltip" class="thumbnail-video"  title="<?php the_title(); ?>">
        <img class="thumbnail-youtube" src="http://img.youtube.com/vi/<?php echo $video_id;?>/0.jpg"/>
    </a>
</li>
<?php
    endforeach;
?>
</ul>
            </div>
		</div>
		<div class="row">
			<div class="span12 archives-box">archives one</div>
			<div class="span12 archives-box">archives two</div>
		</div>
	</div>

		<?php the_post();?>		
	
		<?php the_content(); ?>		
 <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-tooltip.js"></script>
				<script type="text/javascript">
				$(document).ready(function() { 
					$('.thumbnail-video').tooltip();
				});

				</script>
</div><!-- #container -->

<?php get_footer(); ?>
