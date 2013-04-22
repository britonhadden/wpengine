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
    $status = 'draft';
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
        $query = "SELECT t1.user_id
                    FROM $wpdb->usermeta t1
                    JOIN $wpdb->usermeta t2 
                    ON t1.user_id = t2.user_id
                    WHERE t1.meta_key = 'first_name' AND t2.meta_key = 'last_name'
                    AND t1.meta_value LIKE '$fn' AND t2.meta_value LIKE '$ln'";
        echo $query;
        $real_authors[$i] = $wpdb->get_var($query);
        $i = $i + 1;
    }
    $content = $body->{'body.content'};
    $_author = $real_authors[0];
    $post = array(
        'post_author' => $_author,
        'post_status' => $status,
        'post_name' => $name,
        'post_content' => (string)$content
        );
    echo var_dump($post);
    $id = wp_insert_post($post);
}
?>


