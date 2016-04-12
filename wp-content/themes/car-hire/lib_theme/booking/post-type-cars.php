<?php

/*

  FILE STRUCTURE:

	- Custom post type icons
	- Custom Post Types Init
	- Columns for post types
	- Custom Post Type Filters
	- Custom Post Type Metabox Setup
	- Custom Post Type Taxonomy Setup
	- Custom Taxonomy Columns Setup

*/

/* Custom post type icons */
/*------------------------------------------------------------------*/
function bizz_cars_post_types_icons() {
?>
	<style type="text/css" media="screen">
        #menu-posts-bizz_cars .wp-menu-image, #menu-posts-bizzcars .wp-menu-image {
			background: url(<?php echo get_template_directory_uri() ?>/lib_theme/booking/icons-cars.png) no-repeat 6px -17px !important;
		}
		#menu-posts-bizz_cars:hover .wp-menu-image, #menu-posts-bizzcars.wp-has-current-submenu .wp-menu-image {
			background-position:6px 7px!important;
		}
    </style>
<?php 
}
add_action( 'admin_head', 'bizz_cars_post_types_icons' );

/* Custom post type init */
/*------------------------------------------------------------------*/
function bizz_cars_post_types_init() {

	register_post_type( 'bizz_cars',
        array(
        	'label' 				=> __('Cars'),
			'labels' 				=> array(	
				'name' 					=> __('Cars'),
				'singular_name' 		=> __('Cars'),
				'add_new' 				=> __('Add New'),
				'add_new_item' 			=> __('Add New Car'),
				'edit' 					=> __('Edit'),
				'edit_item' 			=> __('Edit Car'),
				'new_item' 				=> __('New Car'),
				'view_item'				=> __('View Car'),
				'search_items' 			=> __('Search Cars'),
				'not_found' 			=> __('No Cars found'),
				'not_found_in_trash' 	=> __('No Cars found in trash'),
				'parent' 				=> __('Parent Car' ),
			),
            'description' => __( 'This is where you can create new cars for your site.' ),
            'public' => true,
            'show_ui' => true,
			'show_in_menu' => 'edit.php?post_type=bizz_bookings',
			'show_in_nav_menus' => true,
            'capability_type' => 'page',
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'hierarchical' => true,
            'rewrite' => array( 'slug' => apply_filters( 'car_slug', 'car'), 'with_front' => false ),
            'query_var' => true,
            'has_archive' => apply_filters( 'car_archive_slug', 'car'),
            'supports' => array(	
				'title'
			)
        )
    );

}
add_action( 'init', 'bizz_cars_post_types_init' );

/* Columns for post types */
/*------------------------------------------------------------------*/
function bizz_cars_edit_columns($columns){
	$columns = array(
		'cb' 						=> '<input type=\'checkbox\' />',
		'title' 					=> __('Car Name'),
		'bizz_car_type_select' 		=> __('Type'),
		'bizz_car_seats' 			=> __('Seats'),
		'bizz_car_doors' 			=> __('Doors'),
		'bizz_car_transmission' 	=> __('Transmission'),
		'bizz_car_location' 		=> __('Location'),
		'bizz_car_availability' 	=> __('Availability'),
		'bizz_car_image' 			=> __('Image')
	);
	return $columns;
}
add_filter('manage_edit-bizz_cars_columns','bizz_cars_edit_columns');

function bizz_cars_custom_columns($column){
	global $post;
	$custom = get_post_custom();
	$custom_car = get_post_custom($post->ID);
	$car_current_location = ( isset($custom_car["bizzthemes_car_location"][0]) ) ? $custom_car["bizzthemes_car_location"][0] : '';
	$car_current_location = get_page_by_path( $car_current_location, 'OBJECT', 'bizz_locations' );
	$car_current_location = ($car_current_location) ? $car_current_location->ID : null;
	switch ($column){
		case "bizz_car_type_select":
			$name = $custom["bizzthemes_car_type"][0];
			$terms = get_terms( 'bizz_cars_type', 'hide_empty=0' );
			foreach ( $terms as $term ) {
				if (!is_wp_error( $name ) && !empty( $name ) && !strcmp( $term->slug, $name ) ) {
					echo $term->name;
				}
			}
		break;
		case "bizz_car_seats":
			$seats = $custom["bizzthemes_car_seats"][0];
			if ($seats != '') { echo $seats; }
		break;
		case "bizz_car_doors":
			$doors = $custom["bizzthemes_car_doors"][0];
			if ($doors != '') { echo $doors; }
		break;
		case "bizz_car_transmission":
			$transmission = $custom["bizzthemes_car_transmission"][0];
			if ($transmission != '') { echo $transmission; }
		break;
		case "bizz_car_location":
			$location = get_the_title($car_current_location);
			if ($location != '') { echo $location; }
		break;
		case "bizz_car_availability":
			$avail = bizz_column_availablity( $post->ID );
			echo ($avail['date'] != 'ok') ? '<span style="color: red;">'.$avail['date'].'</span>' : '<a class="button" href="post-new.php?post_type=bizz_bookings">'.__('Available Now').'</a>';
		break;
		case "bizz_car_image":
			$image = $custom["bizzthemes_car_image"][0];
			if ($image != '') { echo '<a href="'.$image.'"><img height="75" alt="" src="'.$image.'"></a>'; }
		break;
	}
}
add_action('manage_pages_custom_column', 'bizz_cars_custom_columns', 1); # 'manage_pages_custom_column' for pages, 'manage_posts_custom_column' for posts

// dummy funkcija ki vraèa dummy listo dodatkov za avto ki so na voljo
function bizz_column_availablity( $car_id = '' ) {	
	
	// read cookie
	$now = date('Y-m-d, H:i');
	
	// strtotime
	$now = strtotime( $now );
			
	// read bookings
	$args = array(
		'post_type' => 'bizz_bookings',
		'meta_key' => 'bizzthemes_bookings_car',
		'meta_value' => $car_id,
		'numberposts' => -1
	);
	$booking_posts = get_posts( $args );
	$avail_error['date'] = 'ok';
	$avail_error['location'] = 'ok';
	if ( $booking_posts ) {
		foreach ($booking_posts as $booking_post) {
			$custom = get_post_custom($booking_post->ID);
			$booking["status"] = ( isset($custom["bizzthemes_bookings_status"][0]) ) ? $custom["bizzthemes_bookings_status"][0] : 'pending';
			$booking["date_of_pickup"] = $custom["bizzthemes_bookings_date1"][0];
			$booking["hour_of_pickup"] = $custom["bizzthemes_bookings_date1_time"][0];
			$booking["date_of_return"] = $custom["bizzthemes_bookings_date2"][0];
			$booking["hour_of_return"] = $custom["bizzthemes_bookings_date2_time"][0];
			
			// strtotime
			$pickup_btime = strtotime( $booking["date_of_pickup"] . ', ' . $booking["hour_of_pickup"] );
			$return_btime = strtotime( $booking["date_of_return"] . ', ' . $booking["hour_of_return"] );
			
			// check status
			$avail_error['status'] = ($booking["status"] == 'approved' || $booking["status"] == 'pending') ? true : false;
					
			// check dates
			if ($now >= $pickup_btime && $now <= $return_btime && $avail_error['status'])
				$avail_error['date'] = sprintf(__('Car already booked <br/> from %1$s, %2$s <br/> to %3$s, %4$s'), $booking["date_of_pickup"], $booking["hour_of_pickup"], $booking["date_of_return"], $booking["hour_of_return"]);
			else
				$avail_error['date'] = 'ok';
							
			// already booked?
			if ($avail_error['date'] != 'ok' || $avail_error['location'] != 'ok')
				break;
			
		}
	}
	else {
	
		// check dates
		$avail_error['date'] = 'ok';
		
	}
	
	return $avail_error;
		
}

/* Custom Post Type Taxonomy Setup */
/*------------------------------------------------------------------*/
function bizz_cars_taxonomy_init() {
	
	// Add new taxonomy, NOT hierarchical (like tags)
	$labels = array(
		'name' => _x( 'Car Types', 'taxonomy general name' ),
		'singular_name' => _x( 'Type', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Types' ),
		'popular_items' => __( 'Popular Types' ),
		'all_items' => __( 'All Types' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit Type' ), 
		'update_item' => __( 'Update Type' ),
		'add_new_item' => __( 'Add New Type' ),
		'new_item_name' => __( 'New Type Name' ),
		'separate_items_with_commas' => __( 'Separate types with commas' ),
		'add_or_remove_items' => __( 'Add or remove types' ),
		'choose_from_most_used' => __( 'Choose from the most used types' ),
		'menu_name' => __( 'Types' ),
	); 
	register_taxonomy('bizz_cars_type', array('bizz_cars', 'bizz_bookings'), array(
		'hierarchical' => false,
		'labels' => $labels,
		'show_ui' => true,
		'show_tagcloud' => false,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array( 'slug' => 'type' ),
	));
	
	// Add new taxonomy, NOT hierarchical (like tags)
	$labels = array(
		'name' => _x( 'Car Extras', 'taxonomy general name' ),
		'singular_name' => _x( 'Extra', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Extras' ),
		'popular_items' => __( 'Popular Extras' ),
		'all_items' => __( 'All Extras' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit Extra' ), 
		'update_item' => __( 'Update Extra' ),
		'add_new_item' => __( 'Add New Extra' ),
		'new_item_name' => __( 'New Extra Name' ),
		'separate_items_with_commas' => __( 'Separate extras with commas' ),
		'add_or_remove_items' => __( 'Add or remove extras' ),
		'choose_from_most_used' => __( 'Choose from the most used extras' ),
		'menu_name' => __( 'Extras' ),
	); 
	register_taxonomy('bizz_cars_extra', array('bizz_cars', 'bizz_bookings'), array(
		'hierarchical' => false,
		'labels' => $labels,
		'show_ui' => true,
		'show_tagcloud' => false,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array( 'slug' => 'extra' ),
	));

}
add_action( 'init', 'bizz_cars_taxonomy_init', 9 );

/* Custom Post Type Metabox Setup */
/*------------------------------------------------------------------*/
add_filter( 'bizz_meta_boxes', 'bizz_cars_metaboxes' );
function bizz_cars_metaboxes( $meta_boxes ) {
	$prefix = 'bizzthemes_';
	$opt_s = get_option('booking_options');
	
	// type
	$type_terms = get_terms( 'bizz_cars_type', array( 'hide_empty' => 0 ) );
	$type_options = array();
	foreach ($type_terms as $type_term) {
		$type_options[] = array(
            'name' => $type_term->name,
            'value' => $type_term->slug
        );
	}
	// extras
	$extras_terms = get_terms( 'bizz_cars_extra', array( 'hide_empty' => 0 ) );
	$extras_options = array();
	foreach ($extras_terms as $extras_term) {
		$extras_options[$extras_term->slug] = $extras_term->name;
	}
	// location
	$location_posts = get_posts( array( 'post_type' => 'bizz_locations', 'numberposts' => -1 ) );
	$location_options = array();
	foreach ($location_posts as $location_post) {
		$location_options[] = array(
            'name' => $location_post->post_title . ', ' . get_post_meta($location_post->ID, 'bizzthemes_location_address', true),
            'value' => $location_post->post_name
        );
	}
	
	$meta_boxes[] = array(
		'id' => 'bizzthemes_cars_meta',
		'title' => __('Car Details'),
		'pages' => array( 'bizz_cars' ), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => '<a href="edit.php?post_type=bizz_locations">'.__('Current Location').'</a>',
				'desc' => '<a href="post-new.php?post_type=bizz_locations">'.__('Add new').'</a>',
				'id' => $prefix . 'car_location',
				'type' => 'select',
				'options' => $location_options
			),
			array(
				'name' => '<a href="edit-tags.php?taxonomy=bizz_cars_type&post_type=bizz_bookings">'.__('Car Type').'</a>',
				'desc' => '<a href="edit-tags.php?taxonomy=bizz_cars_type&post_type=bizz_bookings">'.__('Add new').'</a>',
				'id' => $prefix . 'car_type',
				'type' => 'select',
				'options' => $type_options
			),
			array(
				'name' => __('Seats'),
				'id' => $prefix . 'car_seats',
				'type' => 'radio_inline',
				'options' => array(
					array('name' => '1', 'value' => '1'),
					array('name' => '2', 'value' => '2'),
					array('name' => '3', 'value' => '3'),
					array('name' => '4', 'value' => '4'),
					array('name' => '5', 'value' => '5'),
					array('name' => '6', 'value' => '6'),
					array('name' => '7', 'value' => '7'),
					array('name' => '8', 'value' => '8')					
				)
			),
			array(
				'name' => __('Doors'),
				'id' => $prefix . 'car_doors',
				'type' => 'radio_inline',
				'options' => array(
					array('name' => '2', 'value' => '2'),
					array('name' => '4', 'value' => '4'),
					array('name' => '5', 'value' => '5')					
				)
			),
			array(
				'name' => __('Transmission'),
				'id' => $prefix . 'car_transmission',
				'type' => 'radio_inline',
				'options' => array(
					array('name' => __('Manual'), 'value' => 'manual'),
					array('name' => __('Automatic'), 'value' => 'automatic')
				)
			),
			array(
				'name'    => '<a href="edit-tags.php?taxonomy=bizz_cars_extra&post_type=bizz_bookings">'.__('Available Car Extras').'</a>',
				'desc'    => '<a href="edit-tags.php?taxonomy=bizz_cars_extra&post_type=bizz_bookings">'.__('Add more').'</a>',
				'id'      => $prefix . 'car_extras',
				'type'    => 'multicheck',
				'options' => $extras_options
			),
			array(
				'name' => __('Car Image'),
				'desc' => __('Upload an image or enter an URL.'),
				'id' => $prefix . 'car_image',
				'type' => 'file'
			),
			array(
				'name' => __('Car Description'),
				'id' => $prefix . 'car_description',
				'type' => 'wysiwyg',
				'options' => array(
					'textarea_rows' => 5,
				)
			),
		)
	);
		
	return $meta_boxes;
}

function car_remove_meta_boxes() {
	remove_meta_box( 'tagsdiv-bizz_cars_type', 'bizz_cars', 'side' );
	remove_meta_box( 'tagsdiv-bizz_cars_type', 'bizz_bookings', 'side' );
	remove_meta_box( 'tagsdiv-bizz_cars_extra', 'bizz_cars', 'side' );
	remove_meta_box( 'tagsdiv-bizz_cars_extra', 'bizz_bookings', 'side' );
}
add_action( 'admin_menu', 'car_remove_meta_boxes' );

/* Custom Post Type Metabox Setup */
/*------------------------------------------------------------------*/
add_filter( 'bizz_tax_boxes', 'bizz_cars_taxboxes' );
function bizz_cars_taxboxes( $tax_boxes ) {
	$prefix = 'bizz_';
	
	$tax_boxes[] = array(
		'id' => 'bizzthemes_cars_type',
		'title' => __('Car Types'),
		'taxonomies' => array( 'bizz_cars_type' ), // taxonomies
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __('Extra image'),
				'desc' => '',
				'id' => $prefix . 'type_image',
				'type' => 'file'
			),
		)
	);
	
	$tax_boxes[] = array(
		'id' => 'bizzthemes_cars_extra',
		'title' => __('Car Extras'),
		'taxonomies' => array( 'bizz_cars_extra' ), // taxonomies
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __('Price'),
				'desc' => __('price number (without currency symbol)'),
				'id' => $prefix . 'extra_price',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Price range'),
				'desc' => '',
				'id' => $prefix . 'extra_price_s',
				'type' => 'radio_inline',
				'std' => 'rental',
				'options' => array(
					array('name' => __('per rental'), 'value' => 'rental'),
					array('name' => __('per day'), 'value' => 'day')				
				)
			),
			array(
				'name' => __('Image thumbnail'),
				'desc' => '',
				'id' => $prefix . 'extra_image',
				'type' => 'file'
			),
		)
	);
		
	return $tax_boxes;
}

/* Custom Taxonomy Columns Setup */
/*------------------------------------------------------------------*/
add_filter('manage_edit-bizz_cars_type_columns', 'add_bizz_cars_type_columns'); #add_filter( "manage_edit-{screen_id}_columns", "column_header_function" ) );  
function add_bizz_cars_type_columns($columns){
    $columns['image'] = __('Image');
	$columns['cars'] = __('Available Cars');
    return $columns;
}
add_action('manage_bizz_cars_type_custom_column', 'add_bizz_cars_type_column', 10, 3); # add_action( "manage_{tax_slug}_custom_column",  "populate_rows_function"), 10, 3  );
function add_bizz_cars_type_column( $value, $column, $term_id ){
    switch ($column){
		case 'image':
			$term_img = get_option('taxonomy_'.$term_id.'_bizz_type_image');
			echo '<a href="'.$term_img.'"><img height="75" alt="" src="'.$term_img.'"></a>';
		break;
		case "cars":
			$term = get_term( $term_id, 'bizz_cars_type' );
			$args = array(
				'post_type' => 'bizz_cars',
				'meta_query' => array(
					array(
						'key' => 'bizzthemes_car_type',
						'value' => $term->slug,
					)
				)
			 );
			$return_cars = get_posts( $args );
			echo count($return_cars);
		break;
	}
}

add_filter('manage_edit-bizz_cars_extra_columns', 'add_bizz_cars_extra_columns'); #add_filter( "manage_edit-{screen_id}_columns", "column_header_function" ) );  
function add_bizz_cars_extra_columns($columns){
	$columns['price'] = __('Price');
	$columns['range'] = __('Price range');
    $columns['image'] = __('Image');
    return $columns;
}
add_action('manage_bizz_cars_extra_custom_column', 'add_bizz_cars_extra_column', 10, 3); # add_action( "manage_{tax_slug}_custom_column",  "populate_rows_function"), 10, 3  );
function add_bizz_cars_extra_column( $value, $column, $term_id ){
    switch ($column){
		case 'price':
			$opt_s = get_option('booking_options');
			$term_price = get_option('taxonomy_'.$term_id.'_bizz_extra_price');
			echo $opt_s['pay_currency'] . $term_price;
		break;
		case 'range':
			$term_range = get_option('taxonomy_'.$term_id.'_bizz_extra_price_s');
			$term_range = ( $term_range == 'rental' ) ? __('per rental') : __('per day');
			echo $term_range;
		break;
		case 'image':
			$term_img = get_option('taxonomy_'.$term_id.'_bizz_extra_image');
			echo '<a href="'.$term_img.'"><img height="75" alt="" src="'.$term_img.'"></a>';
		break;
	}
}

