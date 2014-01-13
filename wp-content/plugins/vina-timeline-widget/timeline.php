<?php
/*
Plugin Name: Vina Timeline Widget
Plugin URI: http://VinaThemes.biz
Description: This simple plugin helps you to give more life to the boring timelines.
Version: 1.0
Author: VinaThemes
Author URI: http://VinaThemes.biz
Author email: mr_hiennc@yahoo.com
Demo URI: http://VinaDemo.biz
Forum URI: http://VinaForum.biz
License: GPLv3+
*/

//Defined global variables
if(!defined('VINA_TIMELINE_DIRECTORY')) 	define('VINA_TIMELINE_DIRECTORY', dirname(__FILE__));
if(!defined('VINA_TIMELINE_INC_DIRECTORY')) define('VINA_TIMELINE_INC_DIRECTORY', VINA_TIMELINE_DIRECTORY . '/includes');
if(!defined('VINA_TIMELINE_URI')) 			define('VINA_TIMELINE_URI', get_bloginfo('url') . '/wp-content/plugins/vina-timeline-widget');
if(!defined('VINA_TIMELINE_INC_URI')) 		define('VINA_TIMELINE_INC_URI', VINA_TIMELINE_URI . '/includes');

//Include library
if(!defined('TCVN_FUNCTIONS')) {
    include_once VINA_TIMELINE_INC_DIRECTORY . '/functions.php';
    define('TCVN_FUNCTIONS', 1);
}
if(!defined('TCVN_FIELDS')) {
    include_once VINA_TIMELINE_INC_DIRECTORY . '/fields.php';
    define('TCVN_FIELDS', 1);
}

class Timeline_Widget extends WP_Widget 
{
	function Timeline_Widget()
	{
		$widget_ops = array(
			'classname' => 'timeline_widget',
			'description' => __("This simple plugin helps you to give more life to the boring timelines.")
		);
		$this->WP_Widget('timeline_widget', __('Vina Timeline Widget'), $widget_ops);
	}
	
	function form($instance)
	{
		$instance = wp_parse_args( 
			(array) $instance, 
			array( 
				'title' 			=> '',
				'categoryId' 		=> '',
				'noItem' 			=> '5',
				'ordering' 			=> 'id',
				'orderingDirection' => 'desc',
				
				'width'			=> '800',
				'height'		=> '350',
				'moduleStyle'	=> 'horizontal',
				'dateSpeed'		=> 'normal',
				'issuesSpeed'	=> 'fast',
				'arrowKeys'		=> 'no',
				'startAt'		=> '1',
				'autoPlay'		=> 'no',
				'autoPlayDirection'	=> 'forward',
				'autoPlayPause'		=> '2000',
				
				'showTitle'		=> 'yes',
				'showImage'		=> 'yes',
				'imageWidth'	=> '256',
				'imageHeight'	=> '256',
				'showContent'	=> 'yes',
				'readmore'		=> 'yes',
				'freeLicense'	=> 'yes',
			)
		);

		$title			= esc_attr($instance['title']);
		$categoryId		= esc_attr($instance['categoryId']);
		$noItem			= esc_attr($instance['noItem']);
		$ordering		= esc_attr($instance['ordering']);
		$orderingDirection = esc_attr($instance['orderingDirection']);
		
		$width			= esc_attr($instance['width']);
		$height			= esc_attr($instance['height']);
		$moduleStyle	= esc_attr($instance['moduleStyle']);
		$dateSpeed		= esc_attr($instance['dateSpeed']);
		$issuesSpeed	= esc_attr($instance['issuesSpeed']);
		$startAt			= esc_attr($instance['startAt']);
		$autoPlay			= esc_attr($instance['autoPlay']);
		$autoPlayDirection	= esc_attr($instance['autoPlayDirection']);
		$autoPlayPause		= esc_attr($instance['autoPlayPause']);
	
		$showTitle		= esc_attr($instance['showTitle']);
		$showImage		= esc_attr($instance['showImage']);
		$imageWidth		= esc_attr($instance['imageWidth']);
		$imageHeight	= esc_attr($instance['imageHeight']);
		$showContent	= esc_attr($instance['showContent']);
		$readmore		= esc_attr($instance['readmore']);
		$freeLicense 	= esc_attr($instance['freeLicense']);
		?>
        <div id="tcvn-timeline" class="tcvn-plugins-container">
            <div style="color: red; padding: 0px 0px 10px; text-align: center;">You are using free version ! <a href="http://vinathemes.biz/commercial-plugins/item/23-wordpress-timeline-widget.html" title="Download full version." target="_blank">Click here</a> to download full version.</div>
            <div id="tcvn-tabs-container">
                <ul id="tcvn-tabs">
                    <li class="active"><a href="#basic"><?php _e('Basic'); ?></a></li>
                    <li><a href="#display"><?php _e('Display'); ?></a></li>
                    <li><a href="#advanced"><?php _e('Advanced'); ?></a></li>
                </ul>
            </div>
            <div id="tcvn-elements-container">
                <!-- Basic Block -->
                <div id="basic" class="tcvn-telement" style="display: block;">
                    <p><?php echo eTextField($this, 'title', 'Title', $title); ?></p>
                    <p><?php echo eSelectOption($this, 'categoryId', 'Category', buildCategoriesList('Select all Categories.'), $categoryId); ?></p>
                    <p><?php echo eTextField($this, 'noItem', 'Number of Post', $noItem, 'Number of posts to show. Default is: 5.'); ?></p>
                	<p><?php echo eSelectOption($this, 'ordering', 'Post Field to Order By', 
						array('id'=>'ID', 'title'=>'Title', 'comment_count'=>'Comment Count', 'post_date'=>'Published Date'), $ordering); ?></p>
                    <p><?php echo eSelectOption($this, 'orderingDirection', 'Ordering Direction', 
						array('asc'=>'Ascending', 'desc'=>'Descending'), $orderingDirection, 
						'Select the direction you would like Articles to be ordered by.'); ?></p>
                </div>
                <!-- Display Block -->
                <div id="display" class="tcvn-telement">
                	<p><?php echo eTextField($this, 'width', 'Module Width', $width); ?></p>
                    <p><?php echo eTextField($this, 'height', 'Module Height', $height); ?></p>
                    <p><?php echo eSelectOption($this, 'moduleStyle', 'Module Style', 
						array('horizontal'=>'Horizontal', 'vertical'=>'Vertical'), $moduleStyle); ?></p>
                    <p><?php echo eTextField($this, 'dateSpeed', 'Date Speed', $dateSpeed); ?></p>
                    <p><?php echo eTextField($this, 'issuesSpeed', 'Issues Speed', $issuesSpeed); ?></p>
                    <p><?php echo eSelectOption($this, 'arrowKeys', 'Arrow Keys', 
						array('yes'=>'Yes', 'no'=>'No'), $arrowKeys); ?></p>
                    <p><?php echo eTextField($this, 'startAt', 'Start At', $startAt); ?></p>
                    <p><?php echo eSelectOption($this, 'autoPlay', 'Auto Play', 
						array('yes'=>'Yes', 'no'=>'No'), $autoPlay); ?></p>
                    <p><?php echo eSelectOption($this, 'autoPlayDirection', 'Auto Play Direction', 
						array('forward'=>'Forward', 'backward'=>'Backward'), $autoPlayDirection); ?></p>
                    <p><?php echo eTextField($this, 'autoPlayPause', 'Auto Play Pause', $autoPlayPause); ?></p>
                </div>
                <!-- Advanced Block -->
                <div id="advanced" class="tcvn-telement">
                    <p><?php echo eSelectOption($this, 'showTitle', 'Post Title', 
						array('yes'=>'Show post title', 'no'=>'Hide post title'), $showTitle); ?></p>
                    <p><?php echo eSelectOption($this, 'showImage', 'Show Image', 
						array('yes'=>'Yes', 'no'=>'No'), $showImage); ?></p>
                    <p><?php echo eTextField($this, 'imageWidth', 'Image Width (px)', $imageWidth); ?></p>
                    <p><?php echo eTextField($this, 'imageHeight', 'Image Height (px)', $imageHeight); ?></p>
                    <p><?php echo eSelectOption($this, 'showContent', 'Post Content', 
						array('yes'=>'Show post content', 'no'=>'Hide post content'), $showContent); ?></p>
                    <p><?php echo eSelectOption($this, 'readmore', 'Readmore', 
						array('yes'=>'Show readmore button', 'no'=>'Hide readmore button'), $readmore); ?></p>
                    <p><?php echo eSelectOption($this, 'freeLicense', 'Use Free License', 
						array('yes'=>'Yes', 'no'=>'No'), $readmore); ?></p>
                </div>
            </div>
        </div>
		<script>
			jQuery(document).ready(function($){
				var prefix = '#tcvn-timeline ';
				$(prefix + "li").click(function() {
					$(prefix + "li").removeClass('active');
					$(this).addClass("active");
					$(prefix + ".tcvn-telement").hide();
					
					var selectedTab = $(this).find("a").attr("href");
					$(prefix + selectedTab).show();
					
					return false;
				});
			});
        </script>
		<?php
	}
	
	function update($new_instance, $old_instance) 
	{
		return $new_instance;
	}
	
	function widget($args, $instance) 
	{
		extract($args);
		
		$title 			= getConfigValue($instance, 'title',		'');
		$categoryId		= getConfigValue($instance, 'categoryId',	'');
		$noItem			= getConfigValue($instance, 'noItem',		'5');
		$ordering		= getConfigValue($instance, 'ordering',		'id');
		$orderingDirection = getConfigValue($instance, 'orderingDirection',	'desc');
		
		$width			= getConfigValue($instance, 'width',		'800');
		$height			= getConfigValue($instance, 'height',		'350');
		$moduleStyle	= getConfigValue($instance, 'moduleStyle',	'horizontal');
		$dateSpeed		= getConfigValue($instance, 'dateSpeed',	'normal');
		$issuesSpeed	= getConfigValue($instance, 'issuesSpeed',	'fast');
		$arrowKeys		= getConfigValue($instance, 'arrowKeys',	'no');
		$startAt		= getConfigValue($instance, 'startAt',		'1');
		$autoPlay		= getConfigValue($instance, 'autoPlay',		'no');
		$autoPlayDirection	= getConfigValue($instance, 'autoPlayDirection',	'forward');
		$autoPlayPause		= getConfigValue($instance, 'autoPlayPause',		'2000');
		
		$showTitle		= getConfigValue($instance, 'showTitle',	'yes');
		$showImage		= getConfigValue($instance, 'showImage',	'yes');
		$imageWidth		= getConfigValue($instance, 'imageWidth',	'256');
		$imageHeight	= getConfigValue($instance, 'imageHeight',	'256');
		$showContent	= getConfigValue($instance, 'showContent',	'yes');
		$readmore		= getConfigValue($instance, 'readmore',		'yes');
		$freeLicense	= getConfigValue($instance, 'freeLicense',	'yes');
		
		if($moduleStyle == 'horizontal')
			wp_enqueue_style('vina-timeline-css', VINA_TIMELINE_INC_URI . '/css/style.css', '', '1.0', 'screen');
		else
			wp_enqueue_style('vina-timeline-css', VINA_TIMELINE_INC_URI . '/css/style_v.css', '', '1.0', 'screen');
		
		$params = array(
			'numberposts' 	=> $noItem, 
			'category' 		=> $categoryId, 
			'orderby' 		=> $order,
			'order' 		=> $orderingDirection,
		);
		
		if($categoryId == '') {
			$params = array(
				'numberposts' 	=> $noItem, 
				'orderby' 		=> $order,
				'order' 		=> $orderingDirection,
			);
		}
		
		$dates	 = '';
		$content = '';
		$posts 	 = get_posts($params);
		
		foreach($posts as $post) 
		{
			$thumbnailId 	= get_post_thumbnail_id($post->ID);				
			$thumbnail 		= wp_get_attachment_image_src($thumbnailId , '70x45');	
			$altText 		= get_post_meta($thumbnailId , '_wp_attachment_image_alt', true);
			$commentsNum 	= get_comments_number($post->ID);
			$postDate		= $post->post_date;
			$image 	= VINA_TIMELINE_URI . '/includes/timthumb.php?w='.$imageWidth.'&h='.$imageHeight.'&a=c&q=99&z=0&src=';
			$link 	= get_permalink($post->ID);
			
			
			$dates   .= '<li><a href="#item-'.$post->ID.'">'.date("M d", strtotime($postDate)).'</a></li>';
			
			$content .= '<li id="#item-'.$post->ID.'">';
			$content .= '<div class="'.(($showImage == 'no' || $thumbnail[0] == NULL) ? 'no-image' : 'has-image').'">';
			$content .= ($showImage == 'yes' && $thumbnail[0] != NULL) ? '<img src="'.$image.$thumbnail[0].'" alt="'.$post->post_title.'" />' : '';
			$content .= ($showTitle == 'yes') 	? '<h3>'.$post->post_title.'</h3>' : '';
			$content .= ($showContent == 'yes') ? '<p>'.$post->post_content.'</p>' : '';
			$content .= ($readmore == 'yes') 	? '<a class="buttonlight morebutton" href="'.$link.'">Read more ...</a>' : '';
			$content .= '</div>';
			$content .= '</li>';
		}
		
		
		echo $before_widget;
		
		if($title) echo $before_title . $title . $after_title;
		
		if(!empty($posts)) : 
		?>
        <style type="text/css">
		<?php if($moduleStyle == 'horizontal') { ?>
		#vina-timeline {
			width: <?php echo $width; ?>px !important;
			height: <?php echo $height; ?>px !important;
		}
		#vina-dates {
			width: <?php echo $width; ?>px !important;
		}
		#vina-issues {
			height: <?php echo $height; ?>px !important;
			overflow: visible !important;
		}
		#vina-issues li {
			width: <?php echo $width; ?>px !important;
			height: <?php echo $height; ?>px !important;
		}
		#vina-grad-left,
		#vina-grad-right {
			height: <?php echo $height; ?>px !important;
		}
		<?php } else { ?>
		#vina-timeline {
			width: <?php echo $width; ?>px !important;
			height: <?php echo $height; ?>px !important;
		}
		#vina-dates {
			height: <?php echo $height; ?>px !important;
		}
		#vina-issues {
			width: <?php echo $width - 100; ?>px !important;
		}
		#vina-issues li {
			width: <?php echo $width - 100; ?>px !important;
			height: <?php echo $height; ?>px !important;
		}
		#vina-grad-top,
		#vina-grad-bottom {
			width: <?php echo $width; ?>px !important;
		}
		<?php } ?>
		</style>
        <div id="vina-timeline">
            <ul id="vina-dates">
                <?php echo $dates; ?>
			</ul>
            <ul id="vina-issues">
            	<?php echo $content; ?>
            </ul>
            <?php if($moduleStyle == 'horizontal') { ?>
            <div id="vina-grad-left"></div>
            <div id="vina-grad-right"></div>
            <?php } else { ?>
            <div id="vina-grad-top"></div>
			<div id="vina-grad-bottom"></div>
            <?php } ?>
            <a href="#" id="vina-next">+</a>
            <a href="#" id="vina-prev">-</a>
        </div>
        <div id="tcvn-copyright">
        	<a href="http://vinathemes.biz" title="Free download Wordpress Themes, Wordpress Plugins - VinaThemes.biz">Free download Wordpress Themes, Wordpress Plugins - VinaThemes.biz</a>
        </div>
        <script type="text/javascript">
			jQuery(document).ready(function($) {
				$(function(){
					$().timelinr({
						orientation: 		'<?php echo $moduleStyle; ?>',
						containerDiv: 		'#vina-timeline',
						datesDiv: 			'#vina-dates',
						issuesDiv: 			'#vina-issues',
						datesSpeed: 		'<?php echo $dateSpeed; ?>',
						issuesSpeed: 		'<?php echo $issuesSpeed; ?>',
						arrowKeys: 			'<?php echo ($arrowKeys == 'no') ? 'false' : 'true';?>',
						startAt: 			<?php echo $startAt; ?>,
						autoPlay: 			'<?php echo ($autoPlay == 'no') ? 'false' : 'true';?>',
						autoPlayDirection: 	'<?php echo $autoPlayDirection; ?>',
						autoPlayPause: 		<?php echo $autoPlayPause; ?>,
						prevButton: 		'#vina-prev',
						nextButton: 		'#vina-next',
					})
				});
			});
		</script>
		<?php
		endif;
		
		echo $after_widget;
	}
}

add_action('widgets_init', create_function('', 'return register_widget("Timeline_Widget");'));
wp_enqueue_style('vina-admin-css', VINA_TIMELINE_INC_URI . '/admin/css/style.css', '', '1.0', 'screen');
wp_enqueue_script('vina-tooltips', VINA_TIMELINE_INC_URI . '/admin/js/jquery.simpletip-1.3.1.js', 'jquery', '1.0', true);
wp_enqueue_script('vina-timeline', 	VINA_TIMELINE_INC_URI . '/js/timeline.js', 'jquery', '1.0', true);
?>