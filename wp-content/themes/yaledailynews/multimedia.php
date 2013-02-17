<?php
/*
Template Name: Multimedia
*/
get_header(); ?>

	<div class="span24">
		<div class="row">
			<div id="main-theater" class="span24">weekly broadcast</div>
		</div>
		<div class="row">
			<div id="slider" class="span24">individual packages</div>
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

