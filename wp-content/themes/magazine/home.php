<?php
/* *
 * File: home.php
 *  Description: all this has to do is grab the latest issue of the magazine from the database,
 *  setup the $post variable appropriately, and then include the issue template
 * */
$latest_issue_query = array("post_type" => YDN_Mag_Issue_Type::type_slug,
                            "post_status" => "publish",
                            "posts_per_page" => 1);
$query = new WP_Query($latest_issue_query);
$query->the_post(); //sets up the post variables
include('single-issue.php');
?>
