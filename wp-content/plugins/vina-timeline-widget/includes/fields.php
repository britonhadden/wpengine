<?php
/*
# ------------------------------------------------------------------------
# Fields for Wordpress Widget
# ------------------------------------------------------------------------
# Copyright(C) 2008-2012 www.VinaThemes.biz. All Rights Reserved.
# @license http://www.gnu.org/licenseses/gpl-3.0.html GNU/GPL
# Author: VinaThemes.biz
# Websites: http://VinaThemes.biz
# Demo: http://VinaDemo.biz
# Forum:    http://laptrinhvien-vn.com/forum/
# ------------------------------------------------------------------------
*/

# Echo Text Field
function eTextField($field, $name, $title, $value, $desc = null)
{
	$id    = $field->get_field_id($name);
	$name  = $field->get_field_name($name);
	
	$label = '<label'.(isset($desc) ? ' class="tcvn-tooltip" title="' . $desc . '"' : '').' for="' . $name . '">' . _($title) . ':</label> ';
	$text  = '<input class="widefat" id="' . $id . '" name="' . $name . '" type="text" value="' . $value . '" />';
	
	return $label . $text;
}

# Echo Select Option
function eSelectOption($field, $name, $title, $options, $value, $desc = null)
{
	$id    = $field->get_field_id($name);
	$name  = $field->get_field_name($name);
	
	$label = '<label'.(isset($desc) ? ' class="tcvn-tooltip" title="' . $desc . '"' : '').' for="' . $name . '">' . _($title) . ':</label> ';
	$text  = '<select name="' . $name . '" id="' . $id . '" style="width:100%">';
	
	foreach($options as $key => $val) {
		if($value == $key)
			$text .= '<option value="' . $key . '" selected>' . $val . '</option>';
		else
			$text .= '<option value="' . $key . '">' . $val . '</option>';
	}
	
	$text .= '</select>';
	
	return $label . $text;
}

# Echo Select Option
function eRadioButton($field, $name, $title, $options, $value, $desc = null)
{
	$id    = $field->get_field_id($name);
	$name  = $field->get_field_name($name);
	
	$label = '<label'.(isset($desc) ? ' class="tcvn-tooltip" title="' . $desc . '"' : '').' for="' . $name . '">' . _($title) . ':</label> ';
	$text  = '';
	
	foreach($options as $key => $val) {
		if($value == $key)
			$text .= ' <input id="' . $id . '" type="radio" value="' . $key . '" name="' . $name . '" checked> ' . $val;
		else
			$text .= ' <input id="' . $id . '" type="radio" value="' . $key . '" name="' . $name . '"> ' . $val;
	}
	
	return $label . $text;
}
?>
