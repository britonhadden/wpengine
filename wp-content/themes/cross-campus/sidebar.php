<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package ydnxc
 * @since ydnxc 1.0
 */
?>
		<div id="secondary" class="widget-area" role="complementary">
			<?php do_action( 'before_sidebar' ); ?>
      <div id="sidebar">
         <aside id="search" class="widget widget_search">
            <?php get_search_form(); ?>
          </aside>
       <div id="tabbed">  
          <div class="tab-content">
           <?php
            if ( is_single() ) {
              dynamic_sidebar('content-sidebar-tabs');
            } else {
              dynamic_sidebar('home-sidebar-tabs');
            }
            ?>
          </div>
        </div>

        <?php
          dynamic_sidebar('sidebar-advertisement');
        ?>

      </div> <!-- #sidebar -->
		</div><!-- #secondary .widget-area -->
