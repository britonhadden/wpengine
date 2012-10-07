<?php
/**
 * Sets up some defaults and registers some WordpressFeatures 
 */

function weekend_setup() {
  /**
	* Custom template tags for this theme.
	*/
	require( get_stylesheet_directory() . '/inc/template-tags.php' ); //this is a silly path solution, but it works..

  /**
   * Register the thumbnail sizes for the weekend grid 
   */
  add_image_size('weekend-entry-featured-image',590,99999999999);
  add_image_size('weekend-small',270,150,true);
  add_image_size('weekend-medium',320,225,true);
  add_image_size('weekend-big',550,396,true);

  /* *
   * Register the WEEKEND menu
   */
	register_nav_menus( array(
		'weekend' => __( 'WEEKEND Menu', 'ydn' ),
	) );
}

add_action( 'after_setup_theme', 'weekend_setup' );

?>



