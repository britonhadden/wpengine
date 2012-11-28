<?php
/*
Template Name: Archives
*/
get_header(); ?>

<div id="primary" class="site-content">
	<div id="content" class="archive" role="main">

		<?php the_post(); ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		
		<?php get_search_form(); ?>
	
		<select name="archive-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
  <option value=""><?php echo esc_attr( __( 'Select Day' ) ); ?></option> 
  <?php wp_get_archives( 'type=daily&format=option&show_post_count=1' ); ?>
</select>
	
		<h2>Archives by Year:</h2>
		<ul>
			<?php wp_get_archives('type=yearly'); ?>
		</ul>
		
		<?php the_content(); ?>		

	</div><!-- #content -->
</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
