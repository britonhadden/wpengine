<?php
/*
Template Name: Importer
*/
?>
<?php
global $wpdb;
echo("Importer! An endpoint to import k4 into the wordpress dbs.\n");
$url = $_GET['NITFurl'];
if($url) {
    $xml = file_get_contents($url);
    $xml_object = new SimpleXMLElement($xml);
    $title = $xml->head->body->{'body.head'}->hedline->hl1;
    echo $title;
}
?>


