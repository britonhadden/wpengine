<?php
/*
Template Name: Multimedia
*/
get_header(); ?>

<div id="primary" class="site-content">
	<div id="content" class="archive" role="main">
	<?php the_post();?>		

		
	
		<?php the_content(); ?>		

	</div><!-- #content -->
</div><!-- #container -->

<?php get_footer(); ?>

