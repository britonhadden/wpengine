<?php
include('inc/mag-issue-post-type.php');

function ydn_widgets_init() {
    register_sidebar( array(
      'name' => __( 'Sidebar', 'ydn' ),
      'id' => 'sidebar-1',
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget' => "</aside>",
      'before_title' => '<h1 class="widget-title">',
      'after_title' => '</h1>',
    ) );

    register_sidebar( array(
      'name' => __( 'Leaderboard', 'ydn' ),
      'id' => 'leaderboard',
      'class' => 'sidebar-widgets',
      'before_widget' => '<div id="leaderboard">',
      'after_widget' => '</div>',
      'before_title' => '',
      'after_title' => ''
    ) );
    register_sidebar( array(
      'name' => __( 'Magazine Home', 'ydn' ),
      'id' => 'magazine_home',
      'class' => 'sidebar-widgets',
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget' => "</aside>",
      'before_title' => '<h1 class="widget-title">',
      'after_title' => '</h1>',
    ) );
}


?>



