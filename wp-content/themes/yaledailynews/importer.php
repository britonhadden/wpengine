<?php
/*
Template Name: Importer
*/
?>
<?php
global $wpdb;
echo("Importer! An endpoint to import k4 into the wordpress dbs.\n");
$url = $_GET['NITFURL'];
if($url) {
    $xml = file_get_contents($url);
    echo $xml;
}
?>


