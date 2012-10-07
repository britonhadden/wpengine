<?php
$test = new YDN_homepage_content(array(), array(), z_get_posts_in_zone("homepage-top-stories") );
print_r($test->get_content_for_cat("university"));

?>
