=== nrelate Most Popular ===
Contributors: nrelate, slipfire, sbruner
Tags: popular posts, most popular content, popular, popularity, feeds, feed, rss, page, pages, post, posts, thumbnails, nrelate
Tested up to: 3.4.1
Requires at least: 2.9
Stable tag: 0.51.2


The best way to display Popular content: Thumbnails or Text.

== Description ==
The best way to display your most popular content from your website.

Installing this plugin is as simple as activating it, and you can leave the rest to nrelate.  Once activated, the nrelate servers will immediately begin monitoring your website and start displaying your most popular content.

There are four ways to display popular content:<br>
1. Automatically display before or after each post.<br>
2. Use the [nrelate-popular] shortcode in your post.<br>
3. Use our widget in any widget area in your theme<br>
4. Place the nrelate_popular() function in your theme files.<br>

nrelate's style gallery allows you to customize the look of our plugin by choosing one of our set styles, or designing your own.<br>
<a href="http://wordpress.org/extend/plugins/nrelate-most-popular/screenshots/">Check out the screenshots.</a>

Advertising is also possible with the plugin. Ads come with the same display options as the popular content and are a great way to earn a little extra income from your blog.

Because all of the processing and analyzing runs on our servers and not yours, nrelate doesn't cause any additional load on your hosting account (especially if you're using shared hosting).

<a href="http://www.nrelate.com" title="nrelate home page">Learn more about nrelate</a> or <a href="http://profiles.wordpress.org/users/nrelate/">View other nrelate plugins.</a>


== Installation ==

1. Activate the nrelate Most Popular Content plugin
2. Head on over to the nrelate settings page and adjust your settings.
3. Sit back and relax... nrelate is monitoring your website and will display popular content within two hours.

**AUTO PLACEMENT**<br>
nrelate can automatically place our popular content at the "Top of your Post" or the "Bottom of your Post"... or both. Just check the appropriate box on our settings page.

**WIDGET**<br>
Drag our widget to any widget area in your theme, to automatically display popular content.

**TEMPLATE TAG**<br>
If you don't want to have our plugin automatically show our popular content, you can use the nrelate_popular template tag to place it anywhere in your theme. For example, if you want your popular content to show in the sidebar of your site, you may want to place the template tag in your sidebar.php file.
It's best practice to use code like this:<br>
<em>&lt;?php if (function_exists('nrelate_popular')) nrelate_popular(); ?&gt;</em><br>

**SHORTCODE**<br>
You can also use the nrelate-popular shortcode to manually place popular content into your posts:<br>
1. Create or edit a Post.<br>
2. Wherever you want the popular content to show up enter the shortcode: [nrelate-popular]<br>

Shortcode Configuration Options:<br>
float = left, right or center<br>
width = any valid CSS value (100%, 50px, etc)<br>

Shortcode Defaults:<br>
float = left<br>
width = 100%<br>

Shortcode Examples:<br>
[nrelate-popular] Will use defaults<br>
[nrelate-popular float='right']<br>
[nrelate-popular width='50%']<br>
[nrelate-popular float='right' width='50%']<br>


== Frequently Asked Questions ==

= What does this plugin do? =
The nrelate Most Popular Content plugin monitors your website, and returns a list of your most popular posts.

= Can I change the time period for the popular content? =
You can choose to show popular content from the last hour, up to 20 years... and anything in between.

= Does this plugin slow down my website? =
Absolutely not.  Since the nrelate servers are doing all the hard work, your website can focus on what it does best... show content. 

= What are my display choices? =
You can show popular content as cool image thumbnails (choose from six image sizes), or simple text with bullets. When choosing thumbnails we will look in your post to find the first image and use that. You can also choose a default image to show when your post contains none.  In the plugin options page you can enter your default image url. If your post has no image, and you have not set a default, we will show a random one from our image library.<br>

= Is advertising optional? =
Yes, you always have the option to display or not display ads.

= What ad display options do you offer? =
If you sign up for advertising, you will be able to display up to ten advertisements within the plugin. If you have selected the thumbnail view, then thumbnails will show up. If you have selected text links, then text ads will show up. You can show ads either at the front, end, or mixed within your content links.  As of version 0.51.0, you can display ads totally separate from your content links as well.

= Does nrelate offer a revenue share on ads? =
Yes, its your blog, you should be making money on it!

= Where do I sign up for ads? =
After installing the plugin, you can <a href="http://nrelate.com/partners/content-publishers/sign-up-for-advertising/">sign up for advertising here.</a>

= Will it look like the rest of my site? =
Many of your website styles will automatically be used by the plugin so it will blend in nicely with your website.  We do need to set some of our own styles to make it work properly. However, you can makes changes to our styles by including your own CSS in your stylesheet.

= I just activated the plugin and I don't see anything, what's up? =
Once you activate the plugin, the nrelate server will start monitoring your website.  Popular content should show up within two hours.

= Can I use your plugin with WordPress Multisite? =
Absolutely. You must activate our plugin on each individual website in your Multi-site install. You cannot use "Network Activate".

= Does plugin support external images, e.g. uploaded on Flickr? =
Absolutely! If you have images in your post, nrelate will find them and auto-create thumbnails.

= Does nrelate work with WordPress "Post Thumbnails"? =
Yes, our plugin automatically detects if you are using post thumbnails.

= Does nrelate work if I use custom fields for my images? =
Yes. Just go to our settings page, and fill in the name of the custom field you use.

= How does the nrelate plugin get my website content? =
Our plugin creates an additional nrelate specific RSS feed.  We use this feed so that we don't run into issues if your regular RSS feed is set to "Summary" or if you use a service like Feedburner.

= What is in the nrelate specific RSS feed and how is it used? =
The nrelate specific RSS feed is very similar to your standard RSS feed if you set it to full feed.  Since we had some users that had their feed to just show a summary and others that used Feedburner, we set this up.  The nrelate specific feed can only be accessed by using a random key that is generated upon install.  To make sure this feed is not used for other purposes, we hired WordPress lead developer and security expert, Mark Jaquith, to build it for us.

= How does nrelate know when new content is published? =
When you activate an nrelate plugin, our Pinghost is automatically added to your list of Update Services, so we are automatically notified when you publish a new post. This allows us to index your new content quickly. You can learn more about the WordPress Update Services at the <a href="http://codex.wordpress.org/Update_Services">WordPress Codex</a>.

= My website is not in English, will nrelate work? =
Our plugin will work on websites in the following languages: Dutch, English, French, German, Indonesian, Italian, Polish, Portuguese, Russian, Spanish, Swedish and Turkish.  If you do not see your language on the list or you think that we could improve the relevancy of our plugin in your language, please <a href="http://nrelate.com/forum/">contact us</a> and we will work with you to configure the plugin accordingly.

== Screenshots ==

1. nrelate Default style
2. Bloginity style
3. LinkWithin style
4. Huffington Post style
5. Trendland style
6. Polaroid style
7. Text style
8. Engadget style
9. Advertising mixed into content
10. Hovering on an advertisement


== Changelog ==

= 0.51.2 =
* Thesis information message on dashboard.
* Bug: issue with service status message in dashboard.
* Bug: Only Published posts should show up in nrelate feed.
* Bug: $nr_mp_counter wasn't incrementing

= 0.51.1 =
* Fixed CSS path
* Bug fix: issue with NONE style

= 0.51.0 =
* Allow for advertising to appear separately from content.
* Eighteen(18) new styles for separate advertising.

= 0.50.6 =
* Update wp_Http calls to WordPress HTTP functions.
* Switch to plugins_url() function.
* Fixed category exclusion bug in nrelate feed.
* Fixed Thumbshots plugin support in nrelate feed.
* Support oEmbed in nrelate feed.

= 0.50.3 =
* Fixed bug on nrelate dashboard and TOS.
* Fixed error with nrelate debug.

= 0.50.2 =
* Fixed clickthrough iframe bug.
* Include/Exclude Post types in data pool.
* Post Type added to nrelate custom feed.
* Change wp_print_styles to wp_enqueue_scripts for WordPress 3.3 compatibility.
* Changed get_permalink($post->ID) to get_permalink($wp_query->post->ID), so we can accurately pull the correct url.

= 0.50.1 =
* Fixed file_get_contents error.

= 0.50.0 =
* The most efficient version yet. Tons of functions are now common to all nrelate plugins!
* New Engadget style!
* 404 Page support!
* Better explaination of advertising opportunities for publishers.
* Add more CSS classes to Text.
* nrelate product check notice.
* nrelate product array now holds the timestamp.
* Fix bug with Text stylesheet handle is incorrect.
* Elimnated reindexing trigger for non-index option changes.
* Fixed some PHP warning errors.
* Ad animation fix. Animation now on a per plugin basis.
* JS & CSS Script Optimizer compatibility warning message
* load css and jquery only when required.
* Fix nrelate_title_url not getting post ID.
* Fixed issue with WP Super Cache flush not working properly.
* Flush cache on plugin activation.
* Avoid feed search engine indexation.

= 0.49.4 =
* Javascript change to open ads in a new tab/window
* Bug fix for Thumbshots plugin

= 0.49.3 =
* New Polaroid style
* Added is_home as a display option.
* Grab proper image for Thumbshots plugin.
* Bug fix for sticky posts.

= 0.49.2 =
* Compatibility fix for nrelate Flyout plugin.

= 0.49.1 =
* Initial Version

== Upgrade Notice ==

= 0.51.0 =
18 new styles! Ads can be displayed separately.

= 0.50.0 =
Two new styles added to style Gallery: Polaroid and Bold Numbers.
