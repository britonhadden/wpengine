<?php
/*
# ------------------------------------------------------------------------
# Function for Wordpress Widget
# ------------------------------------------------------------------------
# Copyright(C) 2008-2012 www.VinaThemes.biz. All Rights Reserved.
# @license http://www.gnu.org/licenseses/gpl-3.0.html GNU/GPL
# Author: VinaThemes.biz
# Websites: http://VinaThemes.biz
# Demo: http://VinaDemo.biz
# Forum:    http://laptrinhvien-vn.com/forum/
# ------------------------------------------------------------------------
*/

function getConfigValue($instance, $name, $default)
{
	return apply_filters('widget_' . $name, empty($instance[$name]) ? __($default) : $instance[$name]);
}

function buildCategoriesList()
{
	$categories = get_categories();
	$rows 		= array('' => _('Select category'));
	
	foreach($categories as $cagegory) {
		$rows += array($cagegory->cat_ID => $cagegory->name);
	}
	
	return $rows;
}