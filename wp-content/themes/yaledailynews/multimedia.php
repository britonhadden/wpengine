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
    $args = array( 'numberposts' => 5, 'post_type' => 'video', 'category' => 3034 );
    $myposts = get_posts( $args );
    foreach( $myposts as $post ) :
        setup_postdata($post);
        $xse = new SimpleXMLElement(the_content());
        $url = $xse->p[0]->iframe["src"];
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

