<?php
/*
Template Name: Importer
*/
?>
<?php get_header(); ?>
HELLO
<?php
    wp_mail("akshay.nathan08@gmail.com", "REQUEST!", "GET:" . implode(",", $_GET) . "POST:" . implode(",", $_POST));
?>

<?php get_footer(); ?>

