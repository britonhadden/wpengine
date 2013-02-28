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

<?php
    global $post;
    $args = array( 'numberposts' => 5, 'post_type' => 'video', 'category' => 3034 ); // 3034 is the category id for a package, we should really have numberposts = infinity and get all the posts and only display a certain amount at a time. 
    $myposts = get_posts( $args );
    foreach( $myposts as $post ) :
        setup_postdata($post);
        $dom = new DOMDocument(get_the_content());
        $iframe = $dom->getElementsByTagName('iframe');
        $iframe = $iframe->item(0);  // Theres only one iframe object per post
        $url = $iframe->getAttribute('src');
        echo $url;
        //if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
        //    $video_id = $match[1];
        //}
    endforeach;
?>
            </div>
		</div>
		<div class="row">
			<div class="span12 archives-box">archives one</div>
			<div class="span12 archives-box">archives two</div>
		</div>
	</div>

		<?php the_post();?>		
	
		<?php the_content(); ?>		

</div><!-- #container -->

<?php get_footer(); ?>

