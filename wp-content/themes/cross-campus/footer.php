<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package ydnxc
 * @since ydnxc 1.0
 */
?>
    </div><!-- everything row -->
	</div><!-- #main -->

  <footer>
    <nav id="bottom" class="top-bottom">
      <div class="container clearfix">
        <span class="pull-left"><?php wp_nav_menu( array('theme_location' => 'bottom') ); ?></span>
        <span class="pull-right"><a href="#">Login</a> | <a href="#">Logout</a></span>
      </div>
    </nav>
  </footer>
 
</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>

</body>
</html>
