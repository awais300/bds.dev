<?php
require_once(preg_replace('/wp-content.+/','',dirname(__FILE__)) . '/wp-config.php');
require_once 'parsecsv.lib.php';


if(isset($_GET['alt']) && $_GET['alt'] == 'yes') {
    global $wpdb;
    $query = "SELECT pm.post_excerpt AS Country, COUNT(*) AS 'Alternative Product Submissions' 
            FROM $wpdb->posts AS p, $wpdb->postmeta AS pm
            WHERE p.ID = pm.post_id
            AND pm.meta_key = '_wpcf_belongs_banned-product_id'
            AND p.post_type = 'alternative-product'
            AND p.post_status = 'publish'";
        $search_response = $wpdb->get_results($query, OBJECT);
        $search_response = $search_response[0];
        if(count($search_response) > 0) {
            echo "<pre>";
            print_r($search_response);
        }
}

?>