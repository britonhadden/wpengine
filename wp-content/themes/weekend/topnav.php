<?php
/* This file contains the template for the WEEKEND
 * top nav. It should be required into pages where it's
 * necessary */
?>
<div class="header">
  <div class="wrapper">
    <div class="content">
      <a href="#"><h1 id="weekend-logo">WEEKEND</h1></a>
         <?php wp_nav_menu( array( 'theme_location' => 'weekend',
                                  'walker' => new Bootstrap_Walker_Nav_Menu,
                                  'menu_class' => 'weekend-nav'
            ) ); ?>
   </div>
  </div>
</div>
