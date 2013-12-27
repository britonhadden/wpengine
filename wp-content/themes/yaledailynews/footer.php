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

	<!-- Google DFP -->
	<script type='text/javascript'>
	var googletag = googletag || {};
	googletag.cmd = googletag.cmd || [];
	(function() {
	var gads = document.createElement('script');
	gads.async = true;
	gads.type = 'text/javascript';
	var useSSL = 'https:' == document.location.protocol;
	gads.src = (useSSL ? 'https:' : 'http:') + 
	'//www.googletagservices.com/tag/js/gpt.js';
	var node = document.getElementsByTagName('script')[0];
	node.parentNode.insertBefore(gads, node);
	})();
	</script>

	<script type='text/javascript'>
	googletag.cmd.push(function() {
	googletag.defineSlot('/1041068/YDN_Lower_Skyscraper', [160, 600], 'div-gpt-ad-1364346235312-0').addService(googletag.pubads());
	googletag.pubads().enableSingleRequest();
	googletag.enableServices();
	});
	</script>
	<script type='text/javascript'>
	var googletag = googletag || {};
	googletag.cmd = googletag.cmd || [];
	(function() {
	var gads = document.createElement('script');
	gads.async = true;
	gads.type = 'text/javascript';
	var useSSL = 'https:' == document.location.protocol;
	gads.src = (useSSL ? 'https:' : 'http:') + 
	'//www.googletagservices.com/tag/js/gpt.js';
	var node = document.getElementsByTagName('script')[0];
	node.parentNode.insertBefore(gads, node);
	})();
	</script>

	<script type='text/javascript'>
	googletag.cmd.push(function() {
	googletag.defineSlot('/1041068/Top_of_page_small', [222, 90], 'div-gpt-ad-1378345467709-0').addService(googletag.pubads());
	googletag.pubads().enableSingleRequest();
	googletag.enableServices();
	});
	</script>

	<!-- Homepage leaderboard -->
	<script type='text/javascript'>
	var googletag = googletag || {};
	googletag.cmd = googletag.cmd || [];
	(function() {
	var gads = document.createElement('script');
	gads.async = true;
	gads.type = 'text/javascript';
	var useSSL = 'https:' == document.location.protocol;
	gads.src = (useSSL ? 'https:' : 'http:') + 
	'//www.googletagservices.com/tag/js/gpt.js';
	var node = document.getElementsByTagName('script')[0];
	node.parentNode.insertBefore(gads, node);
	})();
	</script>

	<script type='text/javascript'>
	googletag.cmd.push(function() {
	googletag.defineSlot('/1041068/YDN_Home_Leaderboard', [728, 90], 'div-gpt-ad-1365622060397-0').addService(googletag.pubads());
	googletag.pubads().enableSingleRequest();
	googletag.enableServices();
	});
	</script>

	<script type='text/javascript'>
	  (function() {
	    var cx = '005865341660642744177:4iw9nrk-rsi';
	    var gcse = document.createElement('script');
	    gcse.type = 'text/javascript';
	    gcse.async = true;
	    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
	        '//www.google.com/cse/cse.js?cx=' + cx;
	    var s = document.getElementsByTagName('script')[0];
	    s.parentNode.insertBefore(gcse, s);
	  })();
	</script>


	<footer id="colophon" class="site-footer container" role="contentinfo">
	<?php if (function_exists('dynamic_sidebar')) { dynamic_sidebar('footer-advertisements'); } ?>
    <div class="double-border"></div>
		<div class="pull-left"> 
			<a href="/contact/">Contact Us</a> | <a href="/advertising/">Advertise</a> | <a href="/archives/">Archives</a> | <a href="/subscribe/">Subscribe</a> | <a href="http://yaledailynews.com/wp-login.php">Login</a></div>
		<div id="footer" class="pull-right">
      &copy; <?php printf( date('Y') ); ?> Yale Daily News &bull; <a href="http://yaledailynews.com/rights-permissions/">All Rights Reserved</a>
		</div><!-- .site-info -->
	</footer><!-- .site-footer .site-footer -->
</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>

</body>
</html>
