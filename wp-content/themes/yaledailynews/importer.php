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
    $body = $xml_object->body;
    $name = $body->{'body.head'}->hedline->hl1;
    $status = 'post_status';
    $authors = $body->{'body.head'}->byline->person;
    $author_title = $body->{'body.head'}->byline->byttl;
    $real_authors = array();
    $i = 0;
    foreach ($authors as $author) {
        $tmp = explode(" ", $author);
        $fn = $tmp[0];
        $ln = end($tmp);
        $real_authors[i] = $wpdb->get_var( "SELECT user_id 
            FROM $wpdb->usermeta
            WHERE first_name LIKE $fn AND last_name LIKE $ln" );
        $i = $i + 1;
    }
    echo var_dump($real_authors);
    $content = $body->{'body.content'};
}
?>


