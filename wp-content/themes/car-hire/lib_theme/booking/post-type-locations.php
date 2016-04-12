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
function bizz_locations_post_types_icons() {
?>
	<style type="text/css" media="screen">
        #menu-posts-bizz_locations .wp-menu-image, #menu-posts-bizzlocations .wp-menu-image {
			background: url(<?php echo get_template_directory_uri() ?>/lib_theme/booking/icons-locations.png) no-repeat 6px -17px !important;
		}
		#menu-posts-bizz_locations:hover .wp-menu-image, #menu-posts-bizzlocations.wp-has-current-submenu .wp-menu-image {
			background-position:6px 7px!important;
		}
    </style>
<?php 
}
add_action( 'admin_head', 'bizz_locations_post_types_icons' );

/* Custom post type init */
/*------------------------------------------------------------------*/
function bizz_locations_post_types_init() {

	register_post_type( 'bizz_locations',
        array(
        	'label' 				=> __('Locations'),
			'labels' 				=> array(	
				'name' 					=> __('Locations'),
				'singular_name' 		=> __('Locations'),
				'add_new' 				=> __('Add New'),
				'add_new_item' 			=> __('Add New Location'),
				'edit' 					=> __('Edit'),
				'edit_item' 			=> __('Edit Location'),
				'new_item' 				=> __('New Location'),
				'view_item'				=> __('View Location'),
				'search_items' 			=> __('Search Locations'),
				'not_found' 			=> __('No Locations found'),
				'not_found_in_trash' 	=> __('No Locations found in trash'),
				'parent' 				=> __('Parent Location' ),
			),
            'description' => __( 'This is where you can create new locations for your site.' ),
            'public' => true,
            'show_ui' => true,
			'show_in_menu' => 'edit.php?post_type=bizz_bookings',
			'show_in_nav_menus' => true,
            'capability_type' => 'page',
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'hierarchical' => true,
            'rewrite' => array( 'slug' => apply_filters( 'location_slug', 'location'), 'with_front' => false ),
            'query_var' => true,
            'has_archive' => apply_filters( 'location_archive_slug', 'location'),
            'supports' => array(	
				'title'
			)
        )
    );

}
add_action( 'init', 'bizz_locations_post_types_init' );

/* Columns for post types */
/*------------------------------------------------------------------*/
function bizz_locations_edit_columns($columns){
	$columns = array(
		'cb' 						=> '<input type=\'checkbox\' />',
		'title' 					=> __('Location Title'),
		'bizz_location_address' 	=> __('Location Address'),
		'bizz_location_city' 		=> __('City'),
		'bizz_location_email' 		=> __('Contact Email'),
		'bizz_location_phone' 		=> __('Contact Phone'),
		'bizz_location_cars' 		=> __('Available Cars')
	);
	return $columns;
}
add_filter('manage_edit-bizz_locations_columns','bizz_locations_edit_columns');

function bizz_locations_custom_columns($column){
	global $post;

	$custom = get_post_custom();
	switch ($column){
		case "bizz_location_address":
			$address = $custom["bizzthemes_location_address"][0];
			if ($address != '') { echo $address; }
		break;
		case "bizz_location_city":
			$city = ( isset($custom["bizzthemes_location_city"][0]) ) ? $custom["bizzthemes_location_city"][0] : '';
			if ($city != '') { echo $city; }
		break;
		case "bizz_location_email":
			$email = $custom["bizzthemes_location_email"][0];
			if ($email != '') { echo $email; }
		break;
		case "bizz_location_phone":
			$phone = $custom["bizzthemes_location_phone"][0];
			if ($phone != '') { echo $phone; }
		break;
		case "bizz_location_cars":
			$args = array(
				'post_type' => 'bizz_cars',
				'meta_query' => array(
					array(
						'key' => 'bizzthemes_car_location',
						'value' => $post->ID,
					)
				)
			 );
			$return_cars = get_posts( $args );
			echo count($return_cars);
		break;
	}
}
add_action('manage_pages_custom_column', 'bizz_locations_custom_columns', 1); # 'manage_pages_custom_column' for pages, 'manage_posts_custom_column' for posts

/* Remove links from post actions */
/*------------------------------------------------------------------*/
add_filter( 'page_row_actions', 'location_remove_row_actions', 10, 2 );
function location_remove_row_actions( $actions, $post ) {
	global $current_screen;
	
	if( $current_screen->post_type == 'bizz_locations' ) {
		// unset( $actions['edit'] );
		unset( $actions['view'] );
		// unset( $actions['trash'] );
		unset( $actions['inline hide-if-no-js'] );
		//$actions['inline hide-if-no-js'] .= __( 'Quick&nbsp;Edit' );
	}

	return $actions;
}

/* Custom Post Type Metabox Setup */
/*------------------------------------------------------------------*/
// CREATE
add_action( 'bizz_render_hours', 'custom_bizz_render_hours', 10, 2 );
function custom_bizz_render_hours( $field, $meta ) {
	global $post;
	
	$time = array('00:00', '00:30','01:00', '01:30','02:00', '02:30','03:00', '03:30','04:00', '04:30','05:00', '05:30','06:00', '06:30', '07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00', '23:00', '23:30');
	// default
	$default_open = (isset($field['open_default'])) ? $field['open_default'] : '09:00';
	$default_close = (isset($field['close_default'])) ? $field['close_default'] : '17:00';
	$default_closed = (isset($field['closed_default'])) ? $field['closed_default'] : false;
	// saved
	$existing_open = get_post_meta($post->ID, $field['id'].'_open', true);
		if ( !$existing_open ) update_post_meta($post->ID, $field['id'].'_open', $default_open);
	$existing_close = get_post_meta($post->ID, $field['id'].'_close', true);
		if ( !$existing_close ) update_post_meta($post->ID, $field['id'].'_close', $default_close);
	$existing_closed = get_post_meta($post->ID, $field['id'].'_closed', true);
		if ( !$existing_closed ) update_post_meta($post->ID, $field['id'].'_closed', $default_closed);
	
	echo _e('Opening time') . '&nbsp;&nbsp;';
	echo '<select name="', $field['id'], '_open" id="', $field['id'], '">';
	foreach ($time as $option_value => $label) {
		$checked = ($existing_open) ? (($existing_open == $label) ? ' selected="selected"' : '') : (($default_open == $label) ? ' selected="selected"' : '');
		echo '	<option value="' . $label . '" ' . $checked . ' />' . $label . '</option>' . "\n";
	}
	echo '</select>';
	echo '&nbsp;&nbsp;&nbsp;&nbsp;';
	echo _e('Closing time') . '&nbsp;&nbsp;';
	echo '<select name="', $field['id'], '_close" id="', $field['id'], '">';
	foreach ($time as $option_value => $label) {
		$checked = ($existing_close) ? (($existing_close == $label) ? ' selected="selected"' : '') : (($default_close == $label) ? ' selected="selected"' : '');
		echo '	<option value="' . $label . '" ' . $checked . ' />' . $label . '</option>' . "\n";
	}
	echo '</select>';
	echo '&nbsp;&nbsp;&nbsp;&nbsp;';
	echo _e('Closed') . '&nbsp;&nbsp;';
	$checked_closed = ($existing_closed) ? ' checked="checked"' : (($default_closed) ? ' checked="checked"' : false);
	echo '<input type="checkbox" name="', $field['id'], '_closed" id="', $field['id'], '" ', $checked_closed, ' />';
	echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
}

// VALIDATE and SAVE
add_filter( 'bizz_validate_hours', 'custom_bizz_validate_hours', 10, 3 );
function custom_bizz_validate_hours( $new, $post_id, $field ) {
    
	$array_name[] = $field['id'] . "_open";
	$array_name[] .= $field['id'] . "_close";
	$array_name[] .= $field['id'] . "_closed";
	
	foreach ( $array_name as $key => $name ) {
		
		$old = get_post_meta( $post_id, $name );
		$new = isset( $_POST[$name] ) ? $_POST[$name] : null;

		if ( $new && $new != $old )
			update_post_meta( $post_id, $name, $new );
		elseif ( '' == $new && $old )
			delete_post_meta( $post_id, $name, $old );
		
	}

}

add_filter( 'bizz_meta_boxes', 'bizz_locations_metaboxes' );
function bizz_locations_metaboxes( $meta_boxes ) {
	$prefix = 'bizzthemes_';
	
	$meta_boxes[] = array(
		'id' => 'bizzthemes_locations_meta',
		'title' => __('Location Details'),
		'pages' => array( 'bizz_locations' ), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __('Country'),
				'id' => $prefix . 'location_country',
				'type' => 'select',
				'options' => bizz_country_list()
			),
			array(
				'name' => __('State/Province/Region'),
				'id' => $prefix . 'location_state',
				'type' => 'text_medium'
			),
			array(
				'name' => __('City'),
				'id' => $prefix . 'location_city',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Postcode/ZIP'),
				'id' => $prefix . 'location_zip',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Address'),
				'id' => $prefix . 'location_address',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Email'),
				'id' => $prefix . 'location_email',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Phone'),
				'id' => $prefix . 'location_phone',
				'type' => 'text_medium'
			)
		)
	);
	
	$meta_boxes[] = array(
		'id' => 'bizzthemes_hours_meta',
		'title' => __('Business Hours'),
		'pages' => array( 'bizz_locations' ), // post type
		'context' => 'normal',
		'priority' => 'default',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
	            'name' => __('Monday'),
	            'id' => $prefix . 'hours_monday',
				'open_default' => '09:00',
				'close_default' => '22:00',
				'closed_default' => false,
	            'type' => 'hours'
	        ),
			array(
	            'name' => __('Tuesday'),
	            'id' => $prefix . 'hours_tuesday',
				'open_default' => '09:00',
				'close_default' => '22:00',
				'closed_default' => false,
	            'type' => 'hours'
	        ),
			array(
	            'name' => __('Wednesday'),
	            'id' => $prefix . 'hours_wednesday',
				'open_default' => '09:00',
				'close_default' => '22:00',
				'closed_default' => false,
	            'type' => 'hours'
	        ),
			array(
	            'name' => __('Thursday'),
	            'id' => $prefix . 'hours_thursday',
				'open_default' => '09:00',
				'close_default' => '22:00',
				'closed_default' => false,
	            'type' => 'hours'
	        ),
			array(
	            'name' => __('Friday'),
	            'id' => $prefix . 'hours_friday',
				'open_default' => '09:00',
				'close_default' => '22:00',
				'closed_default' => false,
	            'type' => 'hours'
	        ),
			array(
	            'name' => __('Saturday'),
	            'id' => $prefix . 'hours_saturday',
				'open_default' => '09:00',
				'close_default' => '17:00',
				'closed_default' => false,
	            'type' => 'hours'
	        ),
			array(
	            'name' => __('Sunday'),
	            'id' => $prefix . 'hours_sunday',
				'open_default' => '09:00',
				'close_default' => '13:00',
				'closed_default' => true,
	            'type' => 'hours'
	        ),
		)
	);
		
	return $meta_boxes;
}


