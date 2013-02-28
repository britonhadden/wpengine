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
    $args = array( 'numberposts' => 5, 'post_type' => 'video' );
    $myposts = get_posts( $args );
    echo count($myposts);
    foreach( $myposts as $post ) :	setup_postdata($post); ?>
	    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
<?php endforeach; ?>

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

