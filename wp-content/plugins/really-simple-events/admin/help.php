<div class="wrap">
	<h2><?php _e( 'Help and Usage Instructions' , 'hc_rse' ); ?></h2>
	<p><?php _e( 'Here you should find everything you need to get up and running with this plugin.  For any extra help, use the Wordpress forums or send me an email' , 'hc_rse' ); ?>: <a href="mailto:huntly.cameron@gmail.com" target="_blank">huntly.cameron@gmail.com</a></p>
	<h3><?php _e( 'Getting Started' , 'hc_rse' ); ?></h3>
	<p><?php _e( 'Adding and editing events should be quite straight forward, it&#39;s worthwhile to note that if you don&#39;t provide any extra information for an event, the link will not show on the front end, although the empty table cell will remain' , 'hc_rse' ); ?></p>
	<h4><?php _e( 'Short Codes' , 'hc_rse' ); ?></h4>
	<p><?php _e( 'To show your upcoming events on a page or post just use this short code' , 'hc_rse' ); ?>: <strong>[hc_rse_events]</strong>.  <?php _e( 'If you wish to show past events only, you can use the &#39;showevents&#39; attribute with one of the following values.' , 'hc_rse' ); ?></p>
	<ul>
		<li><strong>all</strong> - <?php _e( 'show both past and upcoming events.' , 'hc_rse' ); ?></li>
		<li><strong>past</strong> - <?php _e( 'only show the events that have happened already.' , 'hc_rse' ); ?></li>
		<li><strong>upcoming</strong> - <?php _e( 'show only upcoming events (note this is the default behaviour when the showevents attribute is omitted).' , 'hc_rse' ); ?></li>
	</ul>
	<p><?php _e( 'For example, to show all past and upcoming events use this short code' , 'hc_rse' ); ?>: <strong>[hc_rse_events showevents='all']</strong></p>
	<h3><?php _e( 'Advanced' , 'hc_rse' ); ?></h3>
	<p><?php _e( 'Be careful with these things, you may break stuff!' , 'hc_rse' ); ?></p>
	<h4><?php _e( 'Choosing columns and their order' , 'hc_rse' ); ?></h4>
	<p><?php _e( 'You can choose and order the columns by using the columns attribute like so' , 'hc_rse'); ?>: <strong>[hc_rse_events columns="date,title"]</strong></p>
	<p><?php _e( 'Valid column values are:' , 'hc_rse' ); ?></p>
	<ul>
		<li><strong>date</strong> - <?php _e( 'Date of the event, for instance: 21st December.' , 'hc_rse' ); ?></li>
		<li><strong>time</strong> - <?php _e( 'Time of the event, for instance: 23:56.' , 'hc_rse' ); ?></li>
		<li><strong>title</strong> - <?php _e( 'Title of the event, for instance: My Super Awesome Party!' , 'hc_rse' ); ?></li>
		<li><strong>moreinfo</strong> - <?php _e( 'Shows the more info link which the user can view the extra details you entered.' , 'hc_rse' ); ?></li>
	</ul>
	<h4><?php _e( 'CSS Styling' , 'hc_rse' ); ?></h4>
	<p><?php _e( 'For theme developers, here are the event table css classes you can use to style the output' , 'hc_rse' ); ?></p>
	<ul>
		<li><strong>table.hc_rse_events_table</strong> - <?php _e( 'the main event table' , 'hc_rse' ); ?>.</li>
		<li><strong>td.hc_rse_date</strong> - <?php _e( 'the table cell which holds the event date' , 'hc_rse' ); ?>.</li>
		<li><strong>td.hc_rse_time</strong> - <?php _e( 'the table cell which holds the start time' , 'hc_rse' ); ?>.</li>
		<li><strong>td.hc_rse_title</strong> - <?php _e( 'the table cell which holds the event title' , 'hc_rse' ); ?>.</li>
		<li><strong>a.hc_rse_more_info</strong> - <?php _e( 'the more info link' , 'hc_rse' ); ?>.</li>
		<li><strong>td.hc_rse_extra_info</strong> - <?php _e( 'the table cell which holds the event information' , 'hc_rse' ); ?>.</li>
	</ul>
	<p><?php _e( 'You can also stop the custom CSS and JavaScript files being included in the page by using the &#39;noassets&#39; attribute. e.g. ' , 'hc_rse' ); ?> <strong>[hc_rse_events showevents='past' noassets='true']</strong></p>
</div>
