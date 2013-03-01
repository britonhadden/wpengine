<?php
/*
Template Name: Importer
*/
?>
<?php
echo("Importer! An endpoint to import k4 into the wordpress dbs.\n");
if( ! empty($_POST) || ! empty($_GET)) {
    echo("Request received.");
    wp_mail("akshay.nathan08@gmail.com", "REQUEST!", "GET:" . implode(",", $_GET) . "POST:" . implode(",", $_POST));
}
?>


