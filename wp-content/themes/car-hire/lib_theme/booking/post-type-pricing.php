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
function bizz_pricing_post_types_icons() {
?>
	<style type="text/css" media="screen">
        #menu-posts-bizz_pricing .wp-menu-image, #menu-posts-bizzpricing .wp-menu-image {
			background: url(<?php echo get_template_directory_uri() ?>/lib_theme/booking/icons-pricing.png) no-repeat 6px -17px !important;
		}
		#menu-posts-bizz_pricing:hover .wp-menu-image, #menu-posts-bizzpricing.wp-has-current-submenu .wp-menu-image {
			background-position:6px 7px!important;
		}
    </style>
<?php 
}
add_action( 'admin_head', 'bizz_pricing_post_types_icons' );

/* Custom post type init */
/*------------------------------------------------------------------*/
function bizz_pricing_post_types_init() {

	register_post_type( 'bizz_pricing',
        array(
        	'label' 				=> __('Pricing'),
			'labels' 				=> array(	
				'name' 					=> __('Pricing'),
				'singular_name' 		=> __('Pricing'),
				'add_new' 				=> __('Add New'),
				'add_new_item' 			=> __('Add New Price'),
				'edit' 					=> __('Edit'),
				'edit_item' 			=> __('Edit Price'),
				'new_item' 				=> __('New Price'),
				'view_item'				=> __('View Price'),
				'search_items' 			=> __('Search Pricing'),
				'not_found' 			=> __('No Pricing found'),
				'not_found_in_trash' 	=> __('No Pricing found in trash'),
				'parent' 				=> __('Parent Price' ),
			),
            'description' => __( 'This is where you can create new pricing for your site.' ),
            'public' => false,
            'show_ui' => true,
			'show_in_menu' => 'edit.php?post_type=bizz_bookings',
			'show_in_nav_menus' => false,
            'capability_type' => 'page',
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'hierarchical' => true,
            'rewrite' => array( 'slug' => apply_filters( 'price_slug', 'price'), 'with_front' => false ),
            'query_var' => true,
            'has_archive' => apply_filters( 'price_archive_slug', 'price'),
            'supports' => array(	
				'title',
				// 'custom-fields'
			)
        )
    );

}
add_action( 'init', 'bizz_pricing_post_types_init' );

/* Columns for post types */
/*------------------------------------------------------------------*/
function bizz_pricing_edit_columns($columns){
	$columns = array(
		'cb' 					=> '<input type=\'checkbox\' />',
		'title' 				=> __('Price Title'),
		'bizz_type' 			=> __('Car Type'),
		'bizz_range' 			=> __('Day Range'),
		'bizz_season' 			=> __('Season'),
		'bizz_price' 			=> __('Price (per day)')
	);
	return $columns;
}
add_filter('manage_edit-bizz_pricing_columns','bizz_pricing_edit_columns');

function bizz_pricing_custom_columns($column){
	global $post;

	$custom = get_post_custom();
	switch ($column){

		case "bizz_type":
			$name = $custom["bizzthemes_price_type"][0];
			$terms = get_terms( 'bizz_cars_type', 'hide_empty=0' );
			foreach ( $terms as $term ) {
				if (!is_wp_error( $name ) && !empty( $name ) && !strcmp( $term->slug, $name ) ) {
					echo $term->name;
				}
			}
		break;
		case "bizz_range":
			$custom1 = $custom["bizzthemes_price_range_from"][0];
			$custom2 = $custom["bizzthemes_price_range_to"][0];
			if ($custom1 != '') { echo __('day ') . $custom1; }
			if ($custom2 != '') { echo __(' - day ') . $custom2; }
		break;
		case "bizz_season":
			$custom1 = ( isset($custom["bizzthemes_price_season_from"][0]) ) ? $custom["bizzthemes_price_season_from"][0] : '';
			$custom2 = ( isset($custom["bizzthemes_price_season_to"][0]) ) ? $custom["bizzthemes_price_season_to"][0] : '';
			if ($custom1 != '') { echo $custom1; }
			if ($custom2 != '') { echo ' - ' . $custom2; }
		break;
		case "bizz_price":
			$opt_s = get_option('booking_options');
			$custom = $custom["bizzthemes_price_daily"][0];
			if ($custom != '') { echo $opt_s['pay_currency'] . $custom; }
		break;
	}
}
add_action('manage_pages_custom_column', 'bizz_pricing_custom_columns', 1); # 'manage_pages_custom_column' for pages, 'manage_posts_custom_column' for posts

/* Remove links from post actions */
/*------------------------------------------------------------------*/
add_filter( 'page_row_actions', 'pricing_remove_row_actions', 10, 2 );
function pricing_remove_row_actions( $actions, $post ) {
	global $current_screen;
	
	if( $current_screen->post_type == 'bizz_pricing' ) {
		// unset( $actions['edit'] );
		unset( $actions['view'] );
		// unset( $actions['trash'] );
		unset( $actions['inline hide-if-no-js'] );
		//$actions['inline hide-if-no-js'] .= __( 'Quick&nbsp;Edit' );
	}

	return $actions;
}

/* Custom Post Type Metabox Setup 1 */
/*------------------------------------------------------------------*/
// CREATE
add_action( 'bizz_render_days_range', 'custom_bizz_render_days_range', 10, 2 );
function custom_bizz_render_days_range( $field, $meta ) {
	global $post;

	for ($i = 1; $i <= 365; $i += 1) {
		$time[$i] = $i;
	}

	// default
	$default_from = (isset($field['from_default'])) ? $field['from_default'] : '1';
	$default_to = (isset($field['to_default'])) ? $field['to_default'] : '1';

	// saved
	$existing_from = get_post_meta($post->ID, $field['id'].'_from', true);
		if ( !$existing_from ) update_post_meta($post->ID, $field['id'].'_from', $default_to);
	$existing_to = get_post_meta($post->ID, $field['id'].'_to', true);
		if ( !$existing_to ) update_post_meta($post->ID, $field['id'].'_to', $default_to);
	
	// output
	echo '<table>';
	echo '<tr>';
	
	echo '<td style="padding: 0 15px 0 0;">';
	echo '<select name="', $field['id'], '_from" id="', $field['id'], '_from">';
	foreach ($time as $option_value => $label) {
		$checked = ($existing_from) ? (($existing_from == $label) ? ' selected="selected"' : '') : (($default_from == $label) ? ' selected="selected"' : '');
		echo '	<option value="' . $label . '" ' . $checked . ' />' . __('day ') . $label . '</option>' . "\n";
	}
	echo '</select>';
	echo '<p class="bizz_metabox_description" style="padding: 0 0 0 10px;">', $field['desc_from'], '</p>';
	echo '</td>';
	
	echo '<td style="padding: 0 15px 0 0;">';
	echo '<select name="', $field['id'], '_to" id="', $field['id'], '_to">';
	foreach ($time as $option_value => $label) {
		$checked = ($existing_to) ? (($existing_to == $label) ? ' selected="selected"' : '') : (($default_to == $label) ? ' selected="selected"' : '');
		echo '	<option value="' . $label . '" ' . $checked . ' />' . __('day ') . $label . '</option>' . "\n";
	}
	echo '</select>';
	echo '<p class="bizz_metabox_description" style="padding: 0 0 0 10px;">', $field['desc_to'], '</p>';
	echo '</td>';
	
	echo '</tr>';
	echo '</table>';

}

// VALIDATE and SAVE
add_filter( 'bizz_validate_days_range', 'custom_bizz_validate_days_range', 10, 3 );
function custom_bizz_validate_days_range( $new, $post_id, $field ) {
    
	$array_name[] = $field['id'] . "_from";
	$array_name[] .= $field['id'] . "_to";
	
	foreach ( $array_name as $key => $name ) {
		
		$old = get_post_meta( $post_id, $name );
		$new = isset( $_POST[$name] ) ? $_POST[$name] : null;

		if ( $new && $new != $old )
			update_post_meta( $post_id, $name, $new );
		elseif ( '' == $new && $old )
			delete_post_meta( $post_id, $name );
		
	}

}

/* Custom Post Type Metabox Setup 2 */
/*------------------------------------------------------------------*/
// CREATE
add_action( 'bizz_render_calendar_range', 'custom_bizz_render_calendar_range', 10, 2 );
function custom_bizz_render_calendar_range( $field, $meta ) {
	global $post;
	
	// default
	$meta_from = get_post_meta( $post->ID, $field['id'] . '_from', true );
	$meta_to = get_post_meta( $post->ID, $field['id'] . '_to', true );
	
	// output
	echo '<table>';
	echo '<tr>';
	
	echo '<td style="padding: 0;">';
	echo '<input class="bizz_text_small bizz_datepicker" type="text" name="', $field['id'], '_from" id="', $field['id'], '_from" value="', $meta_from ? $meta_from : $field['std'], '" />';
	echo '<p class="bizz_metabox_description" style="padding: 0 0 0 10px;">', $field['desc_from'], '</p>';
	echo '</td>';
	
	echo '<td style="padding: 0;">';
	echo '<input class="bizz_text_small bizz_datepicker" type="text" name="', $field['id'], '_to" id="', $field['id'], '_to" value="', $meta_to ? $meta_to : $field['std'], '" />';
	echo '<p class="bizz_metabox_description" style="padding: 0 0 0 10px;">', $field['desc_to'], '</p>';
	echo '</td>';
	
	echo '</tr>';
	echo '</table>';
	
}

// VALIDATE and SAVE
add_filter( 'bizz_validate_calendar_range', 'custom_bizz_validate_calendar_range', 10, 3 );
function custom_bizz_validate_calendar_range( $new, $post_id, $field ) {

	$array_name[] = $field['id'] . "_from";
	$array_name[] .= $field['id'] . "_to";

	foreach ( $array_name as $key => $name ) {

		$old = get_post_meta( $post_id, $name );
		$new = isset( $_POST[$name] ) ? $_POST[$name] : null;

		if ( $new && $new != $old )
			update_post_meta( $post_id, $name, $new );
		elseif ( empty($new) && $old )
			delete_post_meta( $post_id, $name );

	}

}

add_filter( 'bizz_meta_boxes', 'bizz_pricing_metaboxes' );
function bizz_pricing_metaboxes( $meta_boxes ) {
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

	$meta_boxes[] = array(
		'id' => 'bizzthemes_pricing_meta',
		'title' => __('Price Details'),
		'pages' => array( 'bizz_pricing' ), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __('Car Type'),
				'id' => $prefix . 'price_type',
				'type' => 'select',
				'options' => $type_options
			),
			array(
	            'name' => __('Day Range'),
	            'id' => $prefix . 'price_range',
				'from_default' => '1',
				'to_default' => '1',
				'desc_from' => __('From'),
				'desc_to' => __('To (including)'),
	            'type' => 'days_range'
	        ),
			array(
	            'name' => __('Season &nbsp; (optional)'),
	            'id' => $prefix . 'price_season',
				'desc_from' => __('From'),
				'desc_to' => __('To (including)'),
	            'type' => 'calendar_range'
	        ),
			array(
				'name' => __('Price'),
				'desc' => __('per day'),
				'id' => $prefix . 'price_daily',
				'currency' => $opt_s['pay_currency'],
				'type' => 'text_money'
			),
		)
	);
		
	return $meta_boxes;
}


