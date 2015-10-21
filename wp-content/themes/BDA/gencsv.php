<?php
require_once(preg_replace('/wp-content.+/','',dirname(__FILE__)) . '/wp-config.php');
require_once 'parsecsv.lib.php';


if(isset($_GET['alt']) && $_GET['alt'] == 'yes') {
    global $wpdb;
    $query = "SELECT p.post_excerpt AS Country, COUNT(*) AS 'Alternative Product Submissions' 
            FROM $wpdb->posts AS p
            WHERE p.post_type = 'alternative-product'
            AND p.post_status = 'publish'
            GROUP BY p.post_excerpt";
            $search_response = $wpdb->get_results($query, ARRAY_A);
      
        if(count($search_response) > 0) {
            $static_array = array();
            $static_array[0]['Country'] = 'Country';
            $static_array[0]['Alternative Product Submissions'] = 'Alternative Product Submissions';

            $static_array[1]['Country'] = 'string';
            $static_array[1]['Alternative Product Submissions'] = 'number';

            $search_response = array_merge($static_array, $search_response);

            $file_name = 'geo_alt.csv';
            $file_path = ABSPATH . "uploads/" . $file_name;
            $csv = new parseCSV();
            @unlink($file_path);
            $csv->data = $search_response;
            $csv->save($file_path, $search_response);
            //trace($csv->data);
        }
}


if(isset($_GET['boycott']) && $_GET['boycott'] == 'yes') {
    global $wpdb;
    $query = "SELECT p.post_excerpt AS Country, COUNT(*) AS 'Boycott Product Submissions' 
            FROM $wpdb->posts AS p
            WHERE p.post_type = 'banned-product'
            AND p.post_status = 'publish'
            GROUP BY p.post_excerpt";
            $search_response = $wpdb->get_results($query, ARRAY_A);
      
        if(count($search_response) > 0) {
            $static_array = array();
            $static_array[0]['Country'] = 'Country';
            $static_array[0]['Boycott Product Submissions'] = 'Boycott Product Submissions';

            $static_array[1]['Country'] = 'string';
            $static_array[1]['Boycott Product Submissions'] = 'number';

            $search_response = array_merge($static_array, $search_response);

            $file_name = 'geo_boycott.csv';
            $file_path = ABSPATH . "uploads/" . $file_name;
            @unlink($file_path);
            $csv = new parseCSV();
            $csv->data = $search_response;
            $csv->save($file_path, $search_response);
            //trace($csv->data);
        }
}

?>