<?php

/*

  FILE STRUCTURE:

- Custom post type icons
- Custom Post Types Init
- Columns for post types
- Custom Post Type Filters
- Custom Post Type Metabox Setup

*/

/* Custom post type icons */
/*------------------------------------------------------------------*/
function bizz_bookings_post_types_icons() {
?>
	<style type="text/css" media="screen">
        #menu-posts-bizz_bookings .wp-menu-image, #menu-posts-bizzbookings .wp-menu-image {
			background: url(<?php echo get_template_directory_uri() ?>/lib_theme/booking/icons-bookings.png) no-repeat 6px -17px !important;
		}
		#menu-posts-bizz_bookings:hover .wp-menu-image, #menu-posts-bizzbookings.wp-has-current-submenu .wp-menu-image {
			background-position:6px 7px!important;
		}
    </style>
<?php 
}
add_action( 'admin_head', 'bizz_bookings_post_types_icons' );

/* Custom post type init */
/*------------------------------------------------------------------*/
function bizz_bookings_post_types_init() {

	register_post_type( 'bizz_bookings',
        array(
        	'label' 				=> __('Bookings'),
			'labels' 				=> array(	
				'name_admin_bar' 		=> __('Booking', 'add new on admin bar'),
				'name' 					=> __('Bookings'),
				'singular_name' 		=> __('Bookings'),
				'add_new' 				=> __('Add New'),
				'add_new_item' 			=> __('Add New booking'),
				'edit' 					=> __('Edit'),
				'edit_item' 			=> __('Edit booking'),
				'new_item' 				=> __('New booking'),
				'view_item'				=> __('View booking'),
				'search_items' 			=> __('Search bookings'),
				'not_found' 			=> __('No bookings found'),
				'not_found_in_trash' 	=> __('No bookings found in trash'),
				'parent' 				=> __('Parent booking' ),
			),
            'description' => __( 'This is where you can create new bookings for your site.' ),
            'public' => false,
            'show_ui' => true,
			'show_in_nav_menus' => false,
            'capability_type' => 'post',
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'hierarchical' => false,
            'rewrite' => array( 'slug' => apply_filters( 'booking_slug', 'booking'), 'with_front' => false ),
            'query_var' => true,
            'has_archive' => apply_filters( 'booking_archive_slug', 'booking'),
            'supports' => array( 
				'title',
				// 'custom-fields'
			),
        )
    );
	
}
add_action( 'init', 'bizz_bookings_post_types_init' );

/* Columns for post types */
/*------------------------------------------------------------------*/
function bizz_bookings_edit_columns($columns){
	$columns = array(
		'cb' 						=> '<input type=\'checkbox\' />',
		'title' 					=> __('Title'),
		'bizz_book_track' 					=> __('Tracking ID'),
		'bizz_book_customer' 		=> __('Customer'),
		'bizz_book_start' 			=> __('Start Date'),
		'bizz_book_return' 			=> __('Return Date'),
		'bizz_book_car' 			=> __('Car'),
		'bizz_book_status' 			=> __('Status'),
		// 'bizz_book_payment' 		=> __('Payment'),
		'date' 						=> __('Published')
	);
	return $columns;
}
add_filter('manage_edit-bizz_bookings_columns','bizz_bookings_edit_columns');

function bizz_bookings_custom_columns($column){
	global $post;
	switch ($column){
		case "bizz_book_track":
			$custom = get_post_custom();
			$custom = ( isset($custom["bizzthemes_bookings_track"][0]) ) ? $custom["bizzthemes_bookings_track"][0] : '';
			if ($custom != '') { echo $custom; }
		break;
		case "bizz_book_customer":
			$custom = get_post_custom();
			$custom1 = $custom["bizzthemes_bookings_fname"][0];
			$custom2 = $custom["bizzthemes_bookings_lname"][0];
			if ($custom != '') { echo $custom1.' '.$custom2; }
		break;
		case "bizz_book_start":
			$custom = get_post_custom();
			$custom1 = $custom["bizzthemes_bookings_date1"][0];
			$custom2 = $custom["bizzthemes_bookings_date1_time"][0];
			if ($custom1 != '') { echo $custom1; }
			if ($custom2 != '') { echo ', '.$custom2; }
		break;
		case "bizz_book_return":
			$custom = get_post_custom();
			$custom1 = $custom["bizzthemes_bookings_date2"][0];
			$custom2 = $custom["bizzthemes_bookings_date2_time"][0];
			if ($custom1 != '') { echo $custom1; }
			if ($custom2 != '') { echo ', '.$custom2; }
		break;
		case "bizz_book_car":
			$custom = get_post_custom();
			$custom = $custom["bizzthemes_bookings_car"][0];
			if ($custom != '') { echo '<a href="post.php?post='.$custom.'&action=edit" title="'.get_the_title($custom).'">'.get_the_title($custom).'</a>'; }
		break;
		case "bizz_book_status":
			$custom = get_post_custom();
			$custom = ( isset($custom["bizzthemes_bookings_status"][0]) ) ? $custom["bizzthemes_bookings_status"][0] : 'pending';
			if ($custom == 'pending')
				echo "<span style='color: #FF8000;'>".__('Pending')."</span>";
			elseif ($custom == 'approved')
				echo "<span style='color: green;'>".__('Approved')."</span>";
			elseif ($custom == 'cancelled')
				echo "<span style='color: red;'>".__('Cancelled')."</span>";
		break;
		/*
		case "bizz_book_payment":
			$opt_s = get_option('booking_options');
			$custom = get_post_custom();
			$custom = $custom["bizzthemes_car_pay_total"][0];
			if ($custom != '') { echo $opt_s['pay_currency'] . $custom; }
		break;
		*/
	}
}
add_action('manage_posts_custom_column', 'bizz_bookings_custom_columns', 2);

/* Remove links from post actions */
/*------------------------------------------------------------------*/
add_filter( 'post_row_actions', 'booking_remove_row_actions', 10, 2 );
function booking_remove_row_actions( $actions, $post ) {

	if( $post->post_type == 'bizz_bookings' ) {
		// unset( $actions['edit'] );
		unset( $actions['view'] );
		// unset( $actions['trash'] );
		unset( $actions['inline hide-if-no-js'] );
		//$actions['inline hide-if-no-js'] .= __( 'Quick&nbsp;Edit' );
	}
	
	return $actions;
}

/* Hook into save post action */
/*------------------------------------------------------------------*/
function booking_save_post($id) {
    global $post;
	
	$post_type = ( isset($post) ) ? get_post_type( $post->ID ) : '';
	
	if ( $post_type == 'bizz_bookings' ) {
	
		// Build variables array
		$bookopts['tracking_id'] = $_POST['bizzthemes_bookings_track'];
		$bookopts['pay_total'] = $_POST['bizzthemes_car_pay_total'];
		$bookopts['pay_deposit'] = $_POST['bizzthemes_car_pay_deposit'];
		$bookopts['pay_car'] = $_POST['bizzthemes_car_pay_car'];
		$bookopts['pay_extras'] = $_POST['bizzthemes_car_pay_extras'];
		$bookopts['pay_tax'] = $_POST['bizzthemes_car_pay_tax'];
		$bookopts['car'] = $_POST['bizzthemes_bookings_car'];
		$bookopts['extras'] = $_POST['bizzthemes_bookings_extras'];
		$bookopts['pickup_location'] = $_POST['bizzthemes_bookings_pickup'];
		$bookopts['return_location'] = $_POST['bizzthemes_bookings_return'];
		$bookopts['pickup_date'] = $_POST['bizzthemes_bookings_date1'];
		$bookopts['pickup_hour'] = $_POST['bizzthemes_bookings_date1_time'];
		$bookopts['return_date'] = $_POST['bizzthemes_bookings_date2'];
		$bookopts['return_hour'] = $_POST['bizzthemes_bookings_date2_time'];
		$bookopts['flight'] = $_POST['bizzthemes_bookings_flight'];
		$bookopts['customer_title'] = $_POST['bizzthemes_bookings_ctitle'];
		$bookopts['customer_fname'] = $_POST['bizzthemes_bookings_fname'];
		$bookopts['customer_lname'] = $_POST['bizzthemes_bookings_lname'];
		$bookopts['customer_fullname'] = $_POST['bizzthemes_bookings_fname'].' '.$_POST['bizzthemes_bookings_lname'];
		$bookopts['customer_email'] = $_POST['bizzthemes_bookings_email'];
		$bookopts['customer_phone'] = $_POST['bizzthemes_bookings_phone'];
		$bookopts['customer_contact_option'] = $_POST['bizzthemes_bookings_scontact'];
		$bookopts['customer_country'] = $_POST['bizzthemes_bookings_country'];
		$bookopts['customer_state'] = $_POST['bizzthemes_bookings_state'];
		$bookopts['customer_zip'] = $_POST['bizzthemes_bookings_zip'];
		$bookopts['customer_address'] = $_POST['bizzthemes_bookings_address'];
		$bookopts['customer_comments'] = $_POST['bizzthemes_bookings_comm_que'];
		
		$status = $_POST['bizzthemes_bookings_status'];
		
		if ( $status == 'approved' || $status == 'cancelled' )
			booking_send_notification($status, $bookopts);
	}
	 
}
add_action('post_updated', 'booking_save_post');

/* Send notification email */
/*------------------------------------------------------------------*/
function booking_send_notification($status='', $bookopts='') {
	
	if ( !empty($status) ) {
	
		// Date Time Format
		$pickup_date_format = date(get_option( 'date_format' ), strtotime($bookopts['pickup_date']));
		$pickup_time_format = date(get_option( 'time_format' ), strtotime($bookopts['pickup_hour']));
		$return_date_format = date(get_option( 'date_format' ), strtotime($bookopts['return_date']));
		$return_time_format = date(get_option( 'time_format' ), strtotime($bookopts['return_hour']));
		
		// Extras
		$extras = '';
		foreach ( (array) $bookopts['extras'] as $key => $value ) {
			if ( isset($value[1]) )
				$extras[] = get_the_title($value[1]);
		}
		$extras = implode(', ', $extras);
			
		$opt_b = get_option('booking_options');
		$admin_email = $opt_b['admin_email'];
		$admin_name = $opt_b['admin_name'];
		$approved_subject = $opt_b['approved_email_subject'];
		$approved_content = $opt_b['approved_email_body'];
		$cancelled_subject = $opt_b['cancelled_email_subject'];
		$cancelled_content = $opt_b['cancelled_email_body'];
		$customer_subject = $opt_b['customer_email_subject'];
		$customer_content = $opt_b['customer_email_body'];
		$customer_email = $bookopts['customer_email'];
		$replacements  = array(
			'[ADMIN_NAME]' => $admin_name,
			'[ADMIN_EMAIL]' => $admin_email,
			'[TRACKING_ID]' => $bookopts['tracking_id'], 
			'[PAY_TOTAL]' => $opt_b['pay_currency'].$bookopts['pay_total'], 
			'[PAY_DEPOSIT]' => $opt_b['pay_currency'].$bookopts['pay_deposit'], 
			'[PAY_CAR]' => $opt_b['pay_currency'].$bookopts['pay_car'],
			'[PAY_EXTRAS]' => $opt_b['pay_currency'].$bookopts['pay_extras'], 
			'[PAY_TAX]' => $opt_b['pay_currency'].$bookopts['pay_tax'], 
			'[CAR]' => get_the_title($bookopts['car']), 
			// '[EXTRAS]' => $extras,
			'[PICKUP_LOCATION]' => get_the_title($bookopts['pickup_location']), 
			'[RETURN_LOCATION]' => get_the_title($bookopts['return_location']), 
			'[PICKUP_DATE]' => $pickup_date_format, 
			'[PICKUP_HOUR]' => $pickup_time_format,
			'[RETURN_DATE]' => $return_date_format, 
			'[RETURN_HOUR]' => $return_time_format, 
			'[FLIGHT]' => $bookopts['flight'], 
			'[CUSTOMER_TITLE]' => $bookopts['customer_title'],
			'[CUSTOMER_FNAME]' => $bookopts['customer_fname'],
			'[CUSTOMER_LNAME]' => $bookopts['customer_lname'],
			'[CUSTOMER_FULLNAME]' => $bookopts['customer_fullname'],
			'[CUSTOMER_EMAIL]' => $bookopts['customer_email'],
			'[CUSTOMER_PHONE]' => $bookopts['customer_phone'],
			'[CUSTOMER_CONTACT_OPTION]' => $bookopts['customer_contact_option'],
			'[CUSTOMER_COUNTRY]' => $bookopts['customer_country'],
			'[CUSTOMER_STATE]' => $bookopts['customer_state'],
			'[CUSTOMER_ZIP]' => $bookopts['customer_zip'],
			'[CUSTOMER_ADDRESS]' => $bookopts['customer_address'],
			'[CUSTOMER_COMMENTS]' => $bookopts['customer_comments'],
			'[BOOK_DETAILS]' => '
				<table rules="all" style="border-color:#dddddd;" cellpadding="10">
				<tr><td colspan="2"><strong>'.__('Customer?').'</strong> </td></tr>
				<tr><td>'.__('Tracking ID').' </td><td>'.$bookopts['tracking_id'].'</td></tr>
				<tr><td>'.__('Customer Title').' </td><td>'.$bookopts['customer_title'].'</td></tr>
				<tr><td>'.__('First Name').' </td><td>'.$bookopts['customer_fname'].'</td></tr>
				<tr><td>'.__('Last Name').' </td><td>'.$bookopts['customer_lname'].'</td></tr>
				<tr><td>'.__('Email').' </td><td>'.$bookopts['customer_email'].'</td></tr>
				<tr><td>'.__('Phone').' </td><td>'.$bookopts['customer_phone'].'</td></tr>
				<tr><td>'.__('Contact Option').' </td><td>'.$bookopts['customer_contact_option'].'</td></tr>
				<tr><td>'.__('Country').' </td><td>'.$bookopts['customer_country'].'</td></tr>
				<tr><td>'.__('State/Province').' </td><td>'.$bookopts['customer_state'].'</td></tr>
				<tr><td>'.__('Postcode/ZIP').' </td><td>'.$bookopts['customer_zip'].'</td></tr>
				<tr><td>'.__('Address').' </td><td>'.$bookopts['customer_address'].'</td></tr>
				<tr><td>'.__('Comments/Questions').' </td><td>'.$bookopts['customer_comments'].'</td></tr>
				<tr><td colspan="2"><strong>'.__('Car?').'</strong> </td></tr>
				<tr><td>'.__('Car Name').' </td><td>'.get_the_title($bookopts['car']).'</td></tr>
				<tr><td colspan="2"><strong>'.__('When and Where?').'</strong> </td></tr>
				<tr><td>'.__('Pickup Location').' </td><td>'.get_the_title($bookopts['pickup_location']).'</td></tr>
				<tr><td>'.__('Return Location').' </td><td>'.get_the_title($bookopts['return_location']).'</td></tr>
				<tr><td>'.__('Start Date and Time').' </td><td>'.$pickup_date_format.' @ '.$pickup_time_format.'</td></tr>
				<tr><td>'.__('Return Date and Time').' </td><td>'.$return_date_format.' @ '.$return_time_format.'</td></tr>
				<tr><td>'.__('Flight Number').' </td><td>'.$bookopts['flight'].'</td></tr>
				<tr><td colspan="2"><strong>'.__('Payment?').'</strong> </td></tr>
				<tr><td>'.__('Total').' </td><td>'.$opt_b['pay_currency'].$bookopts['pay_total'].'</td></tr>
				<tr><td>'.__('Deposit').' </td><td>'.$opt_b['pay_currency'].$bookopts['pay_deposit'].'</td></tr>
				<tr><td>'.__('Car').' </td><td>'.$opt_b['pay_currency'].$bookopts['pay_car'].'</td></tr>
				<tr><td>'.__('Extras').' </td><td>'.$opt_b['pay_currency'].$bookopts['pay_extras'].'</td></tr>
				<tr><td>'.__('Tax').' </td><td>'.$opt_b['pay_currency'].$bookopts['pay_tax'].'</td></tr>
				</table>
			'
		);
	}
	
	if ( $status == 'approved' ) {

		
		$subject = str_replace(array_keys($replacements), $replacements, $approved_subject);
		$subject = html_entity_decode( $subject, ENT_QUOTES, 'UTF-8' );
		$content = str_replace(array_keys($replacements), $replacements, $approved_content);
		$content = html_entity_decode( $content, ENT_QUOTES, 'UTF-8' );
		
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html; charset=UTF-8" . "\r\n";
		$headers .= 'From: "'.$admin_name.'" <'.$admin_email.'>' . "\r\n";

		$body = '<html><body>';
		$body .= $content;
		$body .= "</body></html>";
		
		wp_mail($customer_email, $subject, $body, $headers); //email
				
	}
	elseif ( $status == 'cancelled' ) {
		
		$subject = str_replace(array_keys($replacements), $replacements, $cancelled_subject);
		$subject = html_entity_decode( $subject, ENT_QUOTES, 'UTF-8' );
		$content = str_replace(array_keys($replacements), $replacements, $cancelled_content);
		$content = html_entity_decode( $content, ENT_QUOTES, 'UTF-8' );
		
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html; charset=UTF-8" . "\r\n";
		$headers .= 'From: "'.$admin_name.'" <'.$admin_email.'>' . "\r\n";

		$body = '<html><body>';
		$body .= $content;
		$body .= "</body></html>";
		
		wp_mail($customer_email, $subject, $body, $headers); //email
		
	}
	elseif ( $status == 'customer' ) {
		
		$subject = str_replace(array_keys($replacements), $replacements, $customer_subject);
		$subject = html_entity_decode( $subject, ENT_QUOTES, 'UTF-8' );
		$content = str_replace(array_keys($replacements), $replacements, $customer_content);
		$content = html_entity_decode( $content, ENT_QUOTES, 'UTF-8' );
		
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html; charset=UTF-8" . "\r\n";
		$headers .= 'From: "'.$admin_name.'" <'.$admin_email.'>' . "\r\n";

		$body = '<html><body>';
		$body .= $content;
		$body .= "</body></html>";
		
		wp_mail($customer_email, $subject, $body, $headers); //email
		
	}

}

/* Set HTML type email */
/*------------------------------------------------------------------*/
function booking_set_content_type(){
    return "text/html";
}
add_filter( 'wp_mail_content_type','booking_set_content_type' );

add_filter( 'bizz_meta_boxes', 'bizz_bookings_metaboxes' );
function bizz_bookings_metaboxes( $meta_boxes ) {
	$prefix = 'bizzthemes_';
	$opt_s = get_option('booking_options');
	
	// cars
	$car_args = array( 'post_type' => 'bizz_cars',);
	$car_posts = get_posts( $car_args );
	$car_options = array();
	if ($car_posts) {
		foreach ($car_posts as $car_post) {
			$car_options[] = array(
				'name' => $car_post->post_title,
				'value' => $car_post->ID
			);
		}
	}
	
	// extras
	$extras_terms = get_terms( 'bizz_cars_extra', array( 'hide_empty' => 0 ) );
	$extras_options = array();
	foreach ($extras_terms as $extras_term) {
		$extras_options[$extras_term->slug] = $extras_term->name;
	}
	
	// locations
	$location_posts = get_posts( array( 'post_type' => 'bizz_locations', 'numberposts' => -1 ) );
	$location_options = array();
	foreach ($location_posts as $location_post) {
		$location_options[] = array(
            'name' => $location_post->post_title,
            'value' => $location_post->post_name
        );
	}
		
	$meta_boxes[] = array(
		'id' => 'bizzthemes_bookings_status',
		'title' => __('Booking Status'),
		'pages' => array( 'bizz_bookings' ), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __('Status'),
				'desc' => __('Updating booking with "Approved" or "Cancelled" status will automatically send email notification to the customer.'),
				'id' => $prefix . 'bookings_status',
				'type' => 'select',
				'options' => array(
					array('name' => __('Pending'), 'value' => 'pending'),
					array('name' => __('Approved'), 'value' => 'approved'),
					array('name' => __('Cancelled'), 'value' => 'cancelled')				
				)
			),
			array(
				'name' => __('Tracking ID'),
				'desc' => __('Tracking number for this booking. Same as post ID.'),
				'id' => $prefix . 'bookings_track',
				'type' => 'text_small'
			),
		)
	);
	
	$meta_boxes[] = array(
		'id' => 'bizzthemes_bookings_car',
		'title' => __('Car and Extras'),
		'pages' => array( 'bizz_bookings' ), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __('Car'),
				'id' => $prefix . 'bookings_car',
				'type' => 'select',
				'options' => $car_options
			),
			array(
				'name'    => '<a href="edit-tags.php?taxonomy=bizz_cars_extra&post_type=bizz_bookings">'.__('Car Extras').'</a>',
				'desc'    => '<a href="edit-tags.php?taxonomy=bizz_cars_extra&post_type=bizz_bookings">'.__('Add more').'</a>',
				'id'      => $prefix . 'bookings_extras',
				'type'    => 'multicheck',
				'options' => $extras_options
			),
		)
	);
	
	$meta_boxes[] = array(
		'id' => 'bizzthemes_bookings_date',
		'title' => __('Date and Location'),
		'pages' => array( 'bizz_bookings' ), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __('Pickup Location'),
				'id' => $prefix . 'bookings_pickup',
				'type' => 'select',
				'options' => $location_options
			),
			array(
				'name' => __('Return Location'),
				'id' => $prefix . 'bookings_return',
				'type' => 'select',
				'options' => $location_options
			),
			array(
				'name' => __('Start Date'),
				'id' => $prefix . 'bookings_date1',
				'type' => 'date_time'
			),
			array(
				'name' => __('Return Date'),
				'id' => $prefix . 'bookings_date2',
				'type' => 'date_time'
			),
			array(
				'name' => __('Flight Number'),
				'desc' => __('If available, include both the carrier code and the flight number, like BA2244. This is vital to ensure your vehicle is available if your flight is delayed.'),
				'id' => $prefix . 'bookings_flight',
				'type' => 'text_small'
			),
		)
	);
	
	$meta_boxes[] = array(
		'id' => 'bizzthemes_bookings_customer',
		'title' => __('Customer Details'),
		'pages' => array( 'bizz_bookings' ), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __('Customer Title'),
				'id' => $prefix . 'bookings_ctitle',
				'type' => 'select',
				'options' => array(
					array('name' => __('Mr'), 'value' => 'mr'),
					array('name' => __('Mrs'), 'value' => 'mrs'),
					array('name' => __('Miss'), 'value' => 'miss'),
					array('name' => __('Dr'), 'value' => 'dr'),
					array('name' => __('Prof'), 'value' => 'prof'),
					array('name' => __('Rev'), 'value' => 'rev')
				)
			),
			array(
				'name' => __('First Name'),
				'id' => $prefix . 'bookings_fname',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Last Name'),
				'id' => $prefix . 'bookings_lname',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Email'),
				'id' => $prefix . 'bookings_email',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Phone'),
				'id' => $prefix . 'bookings_phone',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Contact Option'),
				'id' => $prefix . 'bookings_scontact',
				'type' => 'select',
				'options' => array(
					array('name' => __('Email'), 'value' => 'email'),
					array('name' => __('Phone (SMS)'), 'value' => 'sms'),
					array('name' => __('Phone (Call)'), 'value' => 'call')
				)
			),
			array(
				'name' => __('Country'),
				'id' => $prefix . 'bookings_country',
				'type' => 'select',
				'options' => bizz_country_list()
			),
			array(
				'name' => __('State/Province'),
				'id' => $prefix . 'bookings_state',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Postcode/ZIP'),
				'id' => $prefix . 'bookings_zip',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Address'),
				'id' => $prefix . 'bookings_address',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Comments/Questions'),
				'id' => $prefix . 'bookings_comm_que',
				'type' => 'textarea',
				'options' => array(
					'textarea_rows' => 5,
				)
			),
		)
	);
	
	$meta_boxes[] = array(
		'id' => 'bizzthemes_bookings_payment',
		'title' => __('Payment'),
		'pages' => array( 'bizz_bookings' ), // post type
		'context' => 'side',
		'priority' => 'low',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __('Total'),
				'desc' => __('enter the total amount'),
				'id' => $prefix . 'car_pay_total',
				'currency' => $opt_s['pay_currency'],
				'type' => 'text_money'
			),
			array(
				'name' => __('Deposit'),
				'desc' => __('Amount paid before rental'),
				'id' => $prefix . 'car_pay_deposit',
				'currency' => $opt_s['pay_currency'],
				'type' => 'text_money'
			),
			array(
				'name' => __('Car'),
				'desc' => __('enter the car amount'),
				'id' => $prefix . 'car_pay_car',
				'currency' => $opt_s['pay_currency'],
				'type' => 'text_money'
			),
			array(
				'name' => __('Extras'),
				'desc' => __('enter the extras amount'),
				'id' => $prefix . 'car_pay_extras',
				'currency' => $opt_s['pay_currency'],
				'type' => 'text_money'
			),
			array(
				'name' => __('Tax'),
				'desc' => __('enter the tax amount'),
				'id' => $prefix . 'car_pay_tax',
				'currency' => $opt_s['pay_currency'],
				'type' => 'text_money'
			),
			/*
			array(
				'name' => __('Payment Method'),
				'id' => $prefix . 'car_payment_method',
				'type' => 'select',
				'options' => array(
					array('name' => __('PayPal'), 'value' => 'paypal'),
					array('name' => __('Cash'), 'value' => 'cash'),
				)
			),
			*/
		)
	);
		
	return $meta_boxes;
}


