<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package ydn
 * @since ydn 1.0
 */
?>
    </div> <!-- everything row -->
	</div><!-- #main -->

	<footer id="colophon" class="site-footer container" role="contentinfo">
    <div class="double-border"></div>
		<div class="site-info">
      &copy; <?php printf( date('Y') ); ?> Yale Daily News &bull; All Rights Reserved<br>
      <a href="#">Rights and Permissions</a>
		</div><!-- .site-info -->
	</footer><!-- .site-footer .site-footer -->
</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>

</body>
</html>
