<?php 


//Add thumbnail, automatic feed links and title tag support
add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( "title-tag" );

//Add content width (desktop default)
if ( ! isset( $content_width ) ) {
	$content_width = 768;
}

//Add menu support and register main menu
if ( function_exists( 'register_nav_menus' ) ) {
  	register_nav_menus(
  		array(
  		  'main_menu' => 'Main Menu'
  		)
  	);
}


// filter the Gravity Forms button type
add_filter("gform_submit_button", "form_submit_button", 10, 2);
function form_submit_button($button, $form){
    return "<button class='button btn' id='gform_submit_button_{$form["id"]}'><span>{$form['button']['text']}</span></button>";
}

// Register sidebar
add_action("widgets_init", "theme_register_sidebar");
function theme_register_sidebar() {
	if ( function_exists('register_sidebar') ) {
		register_sidebar(array(
			'id' => 'sidebar-1',
		    'before_widget' => '<div id="%1$s" class="widget %2$s">',
		    'after_widget' => '</div>',
		    'before_title' => '<h4>',
		    'after_title' => '</h4>',
		 ));
	}
}

// Bootstrap_Walker_Nav_Menu setup

add_action( 'after_setup_theme', 'bootstrap_setup' );
 
if ( ! function_exists( 'bootstrap_setup' ) ):
 
	function bootstrap_setup(){
 
		add_action( 'init', 'register_menu' );
			
		function register_menu(){
			register_nav_menu( 'top-bar', 'Bootstrap Top Menu' ); 
		}
 
		class Bootstrap_Walker_Nav_Menu extends Walker_Nav_Menu {
 
			
			function start_lvl( &$output, $depth = 0, $args = array() ) {
 
				$indent = str_repeat( "\t", $depth );
				$output	   .= "\n$indent<ul class=\"dropdown-menu\">\n";
				
			}
 
			function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
				
				if (!is_object($args)) {
					return; // menu has not been configured
				}

				$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
 
				$li_attributes = '';
				$class_names = $value = '';
 
				$classes = empty( $item->classes ) ? array() : (array) $item->classes;
				$classes[] = ($args->has_children) ? 'dropdown' : '';
				$classes[] = ($item->current || $item->current_item_ancestor) ? 'active' : '';
				$classes[] = 'menu-item-' . $item->ID;
 
 
				$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
				$class_names = ' class="' . esc_attr( $class_names ) . '"';
 
				$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
				$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
 
				$output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';
 
				$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
				$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
				$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
				$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
				$attributes .= ($args->has_children) 	    ? ' class="dropdown-toggle" data-toggle="dropdown"' : '';
 
				$item_output = $args->before;
				$item_output .= '<a'. $attributes .'>';
				$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
				$item_output .= ($args->has_children) ? ' <b class="caret"></b></a>' : '</a>';
				$item_output .= $args->after;
 
				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
			}
 
			function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
				
				if ( !$element )
					return;
				
				$id_field = $this->db_fields['id'];
 
				//display this element
				if ( is_array( $args[0] ) ) 
					$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
				else if ( is_object( $args[0] ) ) 
					$args[0]->has_children = ! empty( $children_elements[$element->$id_field] ); 
				$cb_args = array_merge( array(&$output, $element, $depth), $args);
				call_user_func_array(array(&$this, 'start_el'), $cb_args);
 
				$id = $element->$id_field;
 
				// descend only when the depth is right and there are childrens for this element
				if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {
 
					foreach( $children_elements[ $id ] as $child ){
 
						if ( !isset($newlevel) ) {
							$newlevel = true;
							//start the child delimiter
							$cb_args = array_merge( array(&$output, $depth), $args);
							call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
						}
						$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
					}
						unset( $children_elements[ $id ] );
				}
 
				if ( isset($newlevel) && $newlevel ){
					//end the child delimiter
					$cb_args = array_merge( array(&$output, $depth), $args);
					call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
				}
 
				//end this element
				$cb_args = array_merge( array(&$output, $element, $depth), $args);
				call_user_func_array(array(&$this, 'end_el'), $cb_args);
			}
		}
 	}
endif;

/*Custom scripts*/

/**
 * @param  string $title
 * @param  string $user_email
 * @param  string $user_country
 * @return int $post_id
 */
function create_parent_post($title, $user_country) {
		$author_id = 3;
		return $post_id = wp_insert_post(
			array(
				'post_author'		=>	$author_id,
				'post_title'		=>	sanitize_text_field($title),
				'post_excerpt'		=>	sanitize_text_field($user_country),
				'post_status'		=>	'publish',
				'post_type'		=>	'banned-product'
			)
		);
}

/*$myid = create_parent_post('testParent', 'awais300@gmail.com', 'Pakistan');
create_child_post('testChild1', 'someother@gmail.com', 'USA', $myid);
create_child_post('testChild2', 'someother@gmail.com', 'USA', $myid);
*/


/**
 * @param  string $title
 * @param  string $user_email
 * @param  string $user_country
 * @return int $post_id
 */
function create_child_post($title, $user_country, $parent_postID) {
		$author_id = 3;
		$meta_key = '_wpcf_belongs_banned-product_id';
		$post_id = wp_insert_post(
			array(
				'post_author'		=>	$author_id,
				'post_title'		=>	sanitize_text_field($title),
				'post_excerpt'		=>	sanitize_text_field($user_country),
				'post_status'		=>	'publish',
				'post_type'		=>	'alternative-product'
			)
		);

	add_post_meta($post_id, $meta_key, $parent_postID, false);

}
/**
 * get a user/client IP
 * @return string|boolean
 */
function get_user_ip() {
	$ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
	} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
	    $ip = $_SERVER['REMOTE_ADDR'];
	}

	if (!filter_var($ip, FILTER_VALIDATE_IP) === false) {
    	return $ip;
	} else {
	    return false;
	}
}

function get_ip_to_country() {
	$country = '';
	$ip = get_user_ip();
	//$ip = '182.185.164.87';
	if(!empty($ip)){
		$api_url = 'http://www.geoplugin.net/json.gp?ip=' . $ip;
		$response = wp_remote_get($api_url);
		if (!is_wp_error($response)) {
				if($response['response']['code'] == 200 && $response['response']['message'] == 'OK') {
				$json = $response['body'];
				$resp_array = json_decode($json, true);
				$country = $resp_array['geoplugin_countryName'];
			}
		}
	}
	return $country; 
}

/**
 * get list of countries
 * @return Array
 */
function get_countries() {
	return $countries = array(
    "Afghanistan",
    "Albania",
    "Algeria",
    "American Samoa",
    "Andorra",
    "Angola",
    "Anguilla",
    "Antarctica",
    "Antigua and Barbuda",
    "Argentina",
    "Armenia",
    "Aruba",
    "Australia",
    "Austria",
    "Azerbaijan",
    "Bahamas",
    "Bahrain",
    "Bangladesh",
    "Barbados",
    "Belarus",
    "Belgium",
    "Belize",
    "Benin",
    "Bermuda",
    "Bhutan",
    "Bolivia",
    "Bosnia and Herzegowina",
    "Botswana",
    "Bouvet Island",
    "Brazil",
    "British Indian Ocean Territory",
    "Brunei Darussalam",
    "Bulgaria",
    "Burkina Faso",
    "Burundi",
    "Cambodia",
    "Cameroon",
    "Canada",
    "Cape Verde",
    "Cayman Islands",
    "Central African Republic",
    "Chad",
    "Chile",
    "China",
    "Christmas Island",
    "Cocos (Keeling) Islands",
    "Colombia",
    "Comoros",
    "Congo",
    "Congo, the Democratic Republic of the",
    "Cook Islands",
    "Costa Rica",
    "Cote d'Ivoire",
    "Croatia (Hrvatska)",
    "Cuba",
    "Cyprus",
    "Czech Republic",
    "Denmark",
    "Djibouti",
    "Dominica",
    "Dominican Republic",
    "East Timor",
    "Ecuador",
    "Egypt",
    "El Salvador",
    "Equatorial Guinea",
    "Eritrea",
    "Estonia",
    "Ethiopia",
    "Falkland Islands (Malvinas)",
    "Faroe Islands",
    "Fiji",
    "Finland",
    "France",
    "France Metropolitan",
    "French Guiana",
    "French Polynesia",
    "French Southern Territories",
    "Gabon",
    "Gambia",
    "Georgia",
    "Germany",
    "Ghana",
    "Gibraltar",
    "Greece",
    "Greenland",
    "Grenada",
    "Guadeloupe",
    "Guam",
    "Guatemala",
    "Guinea",
    "Guinea-Bissau",
    "Guyana",
    "Haiti",
    "Heard and Mc Donald Islands",
    "Holy See (Vatican City State)",
    "Honduras",
    "Hong Kong",
    "Hungary",
    "Iceland",
    "India",
    "Indonesia",
    "Iran (Islamic Republic of)",
    "Iraq",
    "Ireland",
    "Israel",
    "Italy",
    "Jamaica",
    "Japan",
    "Jordan",
    "Kazakhstan",
    "Kenya",
    "Kiribati",
    "Korea, Democratic People's Republic of",
    "Korea, Republic of",
    "Kuwait",
    "Kyrgyzstan",
    "Lao, People's Democratic Republic",
    "Latvia",
    "Lebanon",
    "Lesotho",
    "Liberia",
    "Libyan Arab Jamahiriya",
    "Liechtenstein",
    "Lithuania",
    "Luxembourg",
    "Macau",
    "Macedonia, The Former Yugoslav Republic of",
    "Madagascar",
    "Malawi",
    "Malaysia",
    "Maldives",
    "Mali",
    "Malta",
    "Marshall Islands",
    "Martinique",
    "Mauritania",
    "Mauritius",
    "Mayotte",
    "Mexico",
    "Micronesia, Federated States of",
    "Moldova, Republic of",
    "Monaco",
    "Mongolia",
    "Montserrat",
    "Morocco",
    "Mozambique",
    "Myanmar",
    "Namibia",
    "Nauru",
    "Nepal",
    "Netherlands",
    "Netherlands Antilles",
    "New Caledonia",
    "New Zealand",
    "Nicaragua",
    "Niger",
    "Nigeria",
    "Niue",
    "Norfolk Island",
    "Northern Mariana Islands",
    "Norway",
    "Oman",
    "Pakistan",
    "Palau",
    "Panama",
    "Papua New Guinea",
    "Paraguay",
    "Peru",
    "Philippines",
    "Pitcairn",
    "Poland",
    "Portugal",
    "Puerto Rico",
    "Qatar",
    "Reunion",
    "Romania",
    "Russian Federation",
    "Rwanda",
    "Saint Kitts and Nevis",
    "Saint Lucia",
    "Saint Vincent and the Grenadines",
    "Samoa",
    "San Marino",
    "Sao Tome and Principe",
    "Saudi Arabia",
    "Senegal",
    "Seychelles",
    "Sierra Leone",
    "Singapore",
    "Slovakia (Slovak Republic)",
    "Slovenia",
    "Solomon Islands",
    "Somalia",
    "South Africa",
    "South Georgia and the South Sandwich Islands",
    "Spain",
    "Sri Lanka",
    "St. Helena",
    "St. Pierre and Miquelon",
    "Sudan",
    "Suriname",
    "Svalbard and Jan Mayen Islands",
    "Swaziland",
    "Sweden",
    "Switzerland",
    "Syrian Arab Republic",
    "Taiwan, Province of China",
    "Tajikistan",
    "Tanzania, United Republic of",
    "Thailand",
    "Togo",
    "Tokelau",
    "Tonga",
    "Trinidad and Tobago",
    "Tunisia",
    "Turkey",
    "Turkmenistan",
    "Turks and Caicos Islands",
    "Tuvalu",
    "Uganda",
    "Ukraine",
    "United Arab Emirates",
    "United Kingdom",
    "United States",
    "United States Minor Outlying Islands",
    "Uruguay",
    "Uzbekistan",
    "Vanuatu",
    "Venezuela",
    "Vietnam",
    "Virgin Islands (British)",
    "Virgin Islands (U.S.)",
    "Wallis and Futuna Islands",
    "Western Sahara",
    "Yemen",
    "Yugoslavia",
    "Zambia",
    "Zimbabwe"
);
}


/**
 * add custom js to wp_head
 */
function add_custom_js() {
	$site_url = get_site_url();
	echo "<script>
				var site_url = '$site_url';
		 </script>";
}

/**
 * add js and css scripts
 */
function add_header_scripts() {
	$site_url = get_site_url();
	echo "<script>
				var site_url = '$site_url';
		 </script>";
	wp_enqueue_style('live_search_css', get_template_directory_uri() . '/live-search/ls.css' );
    wp_enqueue_script('bootstra_js', get_template_directory_uri() . '/js/bootstrap.min.js');
    wp_enqueue_script('live_search_js', get_template_directory_uri() . '/live-search/ls.js');
	wp_enqueue_script('scroll_js', get_template_directory_uri() . '/js/jquery.nicescroll.js');
	wp_enqueue_script('custom-js', get_template_directory_uri() . '/js/bds.js');
}

//add_action('wp_head', 'add_custom_js');
add_action( 'wp_enqueue_scripts', 'add_header_scripts' );

function trace($mixed) {
    echo "<pre>";
    print_r($mixed);
    echo "</pre>";
}

?>