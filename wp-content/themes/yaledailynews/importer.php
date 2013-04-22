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
        echo $fn;
        echo $ln;
        $query = "SELECT user_id
            FROM $wpdb->usermeta
            WHERE 'meta_key'='first_name' AND 'meta_value'=$fn 
            INTERSECT
            SELECT user_id
            FROM $wpdb->usermeta
            WHERE 'meta_key'='last_name' AND 'meta_value'=$ln;";
        echo $query;
        $real_authors[$i] = $wpdb->get_var($query);
        $i = $i + 1;
    }
    echo var_dump($real_authors);
    $content = $body->{'body.content'};
}
?>


