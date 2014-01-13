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
    <?php dynamic_sidebar('bottom-advertisements'); ?>
	</div><!-- #main -->

	<footer id="colophon" class="site-footer container" role="contentinfo">
    
    <div class="double-border"></div>
		<div class="pull-left"> 
			<a href="/contact/">Contact Us</a> | <a href="/advertising/">Advertise</a> | <a href="/archives/">Archives</a> | <a href="http://subscribe.yaledailynews.com/">Subscribe</a> | <a href="http://yaledailynews.com/wp-login.php">Login</a></div>
		<div id="footer" class="pull-right">
      &copy; <?php printf( date('Y') ); ?> Yale Daily News &bull; <a href="http://yaledailynews.com/rights-permissions/">All Rights Reserved</a>
		</div><!-- .site-info -->
	</footer><!-- .site-footer .site-footer -->
</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>

</body>
</html>
