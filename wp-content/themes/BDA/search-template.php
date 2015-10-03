<?php
/**
* template name: Search
*/

?>

<?php
/**
 * get message
 * @param  string $msg
 * @param  string $val
 * @return string
 */
function get_message($msg, $val, $test = '') {
$site_url = get_site_url();
$site_url = $site_url . "/submit-product/?prod={$val}";

$messages_array['notFoundParent'] = "Our system could not found '{{VAL}}'. You can still submit this produdct/brand by clicking <a id='notfoundp' class='notfound' href='$site_url' title='Submit {{VAL}}'>Here</a>.";

$messages_array['notFoundChild'] = "Currently we don't know any alternative of '{{VAL}}'. Please contribute by submitting an alternavtie to {{VAL}} by clicking <a id='notfoundc' class='notfound' href='$site_url' title='Submit {{VAL}}'>Here</a>.";

$message = '';
$message = $messages_array[$msg];
$message_r = str_replace("{{VAL}}", $val , $message);

$html_message .= "<div id='messages' class='msgs alt-search'>";
$html_message .= "<p>{$message_r}</p>";
$html_message .= "</div>";
return $html_message;
}


/*Search Request while typing*/
if( isset($_GET['search']) )
{
    $stitle = wp_strip_all_tags($_GET['search']);
    $search = $_GET['search'];
    $search = esc_sql( like_escape( $search ) );
    $search = strtolower($search);
      
    $query = "SELECT p.ID AS id, LOWER(p.post_title) AS title
        FROM $wpdb->posts AS p
        WHERE p.post_title LIKE '$search%'
        AND p.post_type = 'banned-product'
        AND p.post_status = 'publish'
        ORDER BY p.post_title";

    $search_response = $wpdb->get_results($query, OBJECT);
    $html = '';
    if(count($search_response) > 0) {
    foreach ($search_response as $key => $search) {
        $html .= "<a href='javscript:void();' title='{$search->title}' id='{$search->id}' class='search-term'>{$search->title}</a>";
        $html .= "";
    }
    echo $html; 
    }
    else {
         echo $html = get_message('notFoundParent', $stitle);
    }  
} else {
    //wrong request
}



/*Search Request on selecting a search term*/
if(isset($_POST['search_term']) && $_POST['search_term'] == 'Yes') {
    if( isset($_POST['pid'])) {
        $stitle = wp_strip_all_tags($_POST['ptitle']);
        $parent_id = intval($_POST['pid']);
        $childargs = array(
        'post_type' => 'alternative-product',
        'numberposts' => -1,
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_query' => array(array('key' => '_wpcf_belongs_banned-product_id', 'value' => $parent_id ))
        );
        $child_posts = get_posts($childargs);

        $html = "<ul id='alt-search' class='alt-search'>";
        if(count($child_posts) > 0) {
        foreach ($child_posts as $key => $search) {
            $html .= "<li itle='{$search->post_title}' id='{$search->ID}' class='alt-search-term'>{$search->post_title}</li>";
            $html .= "<br/>";
        }
        $html .= "</ul>";
        echo $html; 
        }
        else {
            echo $html = get_message('notFoundChild', $stitle);
        }  

        } else {
            //echo "pid not found";
        }

} else {
    //wrong request
}


/*Search Request on selecting a search term by form*/
if(isset($_POST['search_term_form']) && $_POST['search_term_form'] == 'Yes') {
    $found = false;
    if( isset($_POST['ptitle']) && !empty($_POST['ptitle']) ) {
        $stitle = wp_strip_all_tags($_POST['ptitle']);
        $search = $stitle = $_POST['ptitle'];
        $search = esc_sql( like_escape( $search ) );
        $search = strtolower($search);
          
        $query = "SELECT p.ID AS id, LOWER(p.post_title) AS title
            FROM $wpdb->posts AS p
            WHERE p.post_title = '$search'
            AND p.post_type = 'banned-product'
            AND p.post_status = 'publish'
            ORDER BY p.post_title";

        $search_response = $wpdb->get_results($query, OBJECT);
        $search_response = $search_response[0];
        if(count($search_response) > 0) {
            $found = true;
            $parent_id = $search_response->id;
        } 
        else {
            echo $html = get_message('notFoundParent', $stitle);
        }
    } else {
        //Search title not found;
    }  

    if($found) {
        $childargs = array(
        'post_type' => 'alternative-product',
        'numberposts' => -1,
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_query' => array(array('key' => '_wpcf_belongs_banned-product_id', 'value' => $parent_id ))
        );
        $child_posts = get_posts($childargs);

        $html = "<ul id='alt-search' class='alt-search'>";
        if(count($child_posts) > 0) {
        foreach ($child_posts as $key => $search) {
            $html .= "<li itle='{$search->post_title}' id='{$search->ID}' class='alt-search-term'>{$search->post_title}</li>";
            $html .= "<br/>";
        }
        $html .= "</ul>";
        echo $html; 
        }
        else {
            echo $html = get_message('notFoundChild', $stitle);
        } 
    }
} else {
    //wrong request
}

/*product submit parent*/

/*Search Request for parent product*/
if(isset($_POST['search_parent']) && $_POST['search_parent'] == 'Yes') {
    $found = false;
    $child_found = false;
    $html = '';

    $response = array();
    $response['message'] = '';
    $response['has_data'] = 'No';
    $response['has_child'] = 'No';
    $response['parent_id'] = '0';

    if( isset($_POST['ptitle']) && !empty($_POST['ptitle']) ) {
        $stitle = wp_strip_all_tags($_POST['ptitle']);
        $search = $stitle = $_POST['ptitle'];
        $search = esc_sql( like_escape( $search ) );
        $search = strtolower($search);
          
        $query = "SELECT p.ID AS id, LOWER(p.post_title) AS title
            FROM $wpdb->posts AS p
            WHERE p.post_title = '$search'
            AND p.post_type = 'banned-product'
            AND p.post_status = 'publish'
            ORDER BY p.post_title";

        $search_response = $wpdb->get_results($query, OBJECT);
        $search_response = $search_response[0];
        if(count($search_response) > 0) {
            $found = true;
            $parent_id = $search_response->id;
        }
    }    

    if($found) {
        $childargs = array(
        'post_type' => 'alternative-product',
        'numberposts' => -1,
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_query' => array(array('key' => '_wpcf_belongs_banned-product_id', 'value' => $parent_id ))
        );
        $child_posts = get_posts($childargs);

        if(count($child_posts) > 0) {
            $html = "<ul id='alt-search' class='alt-search'>";
            $child_found = true;
            foreach ($child_posts as $key => $search) {
                $html .= "<li itle='{$search->post_title}' id='{$search->ID}' class='alt-search-term'>{$search->post_title}</li>";
                $html .= "";
            }
                $html .= "</ul>"; 
        }
    }

    if($found == true && $child_found == false) {
        $response['message'] = "<div id='buycott' class='buycott fixheight-cs' style='display:none'><p class='parent-found'>'{$stitle}' already exist. You can still submit this form if you know any alternative products/brand against '{$stitle}'</p></div>";
        $response['has_data'] = 'Yes';
        $response['has_child'] = 'No';
        $response['parent_id'] = $parent_id;
    }

    if($found == true && $child_found == true) {
        $response['message'] = "<div id='buycott' class='buycott' style='display:none'><p class='child-found'>'{$stitle}' already exist. Below are the alternative product/brand against '{$stitle}'. You can still submit this form by providing additional alternative products/brands.</p> {$html}</div>";
        $response['has_data'] = 'Yes';
        $response['has_child'] = 'Yes';
        $response['parent_id'] = $parent_id;
    }
    echo json_encode($response);
}


/*search request for child product*/
if(isset($_POST['search_child']) && $_POST['search_child'] == 'Yes') {
    $found = false;
    $html = '';

    $response = array();
    $response['message'] = '';

    if( isset($_POST['ctitle']) && !empty($_POST['ctitle']) ) {
        $pid = intval($_POST['pid']);
        if($pid == 0) {
            return;
        }
        $stitle = wp_strip_all_tags($_POST['ctitle']);
        $search = $stitle = $_POST['ctitle'];
        $search = esc_sql( like_escape( $search ) );
        $search = strtolower($search);
          
        $query = "SELECT p.ID AS id, LOWER(p.post_title) AS title
            FROM $wpdb->posts AS p, $wpdb->postmeta AS pm
            WHERE p.ID = pm.post_id
            AND pm.meta_key = '_wpcf_belongs_banned-product_id'
            AND pm.meta_value = '$pid'
            AND p.post_title = '$search'
            AND p.post_type = 'alternative-product'
            AND p.post_status = 'publish'
            ORDER BY p.post_title";

        $search_response = $wpdb->get_results($query, OBJECT);
        $search_response = $search_response[0];
        if(count($search_response) > 0) {
            $found = true;
            $title = $search_response->title;
            $html = "<div id='child-prod' class='child-prod buycott'>'{$stitle}' already exit. Please provide something different.</div>";
            $response['message'] = $html;
        }
    }

    echo json_encode($response);
}


/*submit product*/
if(isset($_POST['submit_product']) && $_POST['submit_product'] == 'Yes') {
        $response = array();
        $response['message'] = '';
        $parent = false;
        $parent_id = 0;
        
        $title = isset($_POST["ptitle"]) ? $_POST["ptitle"] : '';
        $country = isset($_POST["country"]) ? $_POST["country"] : '';
        $alt_products = $_POST["alt-product"];

        if(empty($country)) {
            $response['message'] = 'Please complete the required fields';
        }

        if(!empty($title)) {
            $search = esc_sql( like_escape( $title ) );
            $search = strtolower($search);
              
            $query = "SELECT p.ID AS id, LOWER(p.post_title) AS title
            FROM $wpdb->posts AS p
            WHERE p.post_title = '$search'
            AND p.post_type = 'banned-product'
            AND p.post_status = 'publish'
            ORDER BY p.post_title
            LIMIT 1";

            $search_response = $wpdb->get_results($query, OBJECT);
            $search_response = $search_response[0];

            if(count($search_response) > 0) {
                //parent product exist, add only child proudcts now
                $parent = true;
                $parent_id = $search_response->id;

                //only save new products, so also check if child product already not existing
                if(!empty($alt_products)) {
                   $products =  get_new_products($parent_id, $alt_products);
                   if(!empty($products)) {
                        //create new child posts
                        foreach ($products as $ctitle) {
                            create_child_post($ctitle, $country, $parent_id);
                        }
                   }
                }
            } else {
                //parent don't exist, add parent and child products
                $alt_products = array_filter($alt_products);
                $pid = create_parent_post($title, $country);
                if(!empty($alt_products)) {
                    foreach ($alt_products as $ctitle) {
                            create_child_post($ctitle, $country, $pid);
                    }
                }
            }
        } else {
            $response['message'] = 'Please complete the required fields';
        }

        echo json_encode($response);
}

/**
 * get new products only
 * @param  int $pid
 * @param  array $alt_products
 * @return Array
 */
function get_new_products($pid, $alt_products) {
        global $wpdb;
        $child_titles = array();
        $new_products = array();
        $query = "SELECT p.ID AS id, LOWER(p.post_title) AS title
            FROM $wpdb->posts AS p, $wpdb->postmeta AS pm
            WHERE p.ID = pm.post_id
            AND pm.meta_key = '_wpcf_belongs_banned-product_id'
            AND pm.meta_value = '$pid'
            AND p.post_type = 'alternative-product'
            AND p.post_status = 'publish'
            ORDER BY p.post_title";

        $search_response = $wpdb->get_results($query, OBJECT);
        if(count($search_response) > 0) {
            foreach ($search_response as $value) {
                $child_titles[] = $value->title;
            }

            foreach ($alt_products as $key => $value) {
                if(!in_array($value, $child_titles)) {
                    $new_products[] = $value;
                }
            }
            return array_filter($new_products);
        } else {
            return array_filter($alt_products);
        }
    }
?>