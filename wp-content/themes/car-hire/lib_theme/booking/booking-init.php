<?php

/* BOOKING: Custom Post Types */
/*------------------------------------------------------------------*/
locate_template( 'lib_theme/booking/post-type-cars.php', true );
locate_template( 'lib_theme/booking/post-type-locations.php', true );
locate_template( 'lib_theme/booking/post-type-pricing.php', true );
locate_template( 'lib_theme/booking/post-type-bookings.php', true );
locate_template( 'lib_theme/booking/post-type-bookings-settings.php', true );

/* BOOKING: Widgets */
/*------------------------------------------------------------------*/
add_action( 'widgets_init', 'bizz_booking_widgets' );
function bizz_booking_widgets() {
	locate_template( 'lib_theme/booking/widget-booking.php', true );
}

/* BOOKINGS: Scripts */
/*------------------------------------------------------------------*/

// Add Theme Javascript
if (!is_admin()) add_action( 'wp_print_scripts', 'bizz_book_javascript' );
function bizz_book_javascript() {

	$opt_s = get_option('booking_options');

	// online
	wp_enqueue_script( 'jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js' ); #header

	/* offline
	wp_enqueue_script( 'jquery-ui-dialog' ); #header
	wp_enqueue_script( 'jquery-ui-datepicker' ); #header
	*/
	
		
	wp_enqueue_script( 'booking-js', get_template_directory_uri() .'/lib_theme/booking/booking.js', array( 'jquery' ) ); # header
	wp_localize_script( 'booking-js', 'bizzlang', array(
		'menu_select' => __( 'Select a page', 'bizzthemes' ),
		'book_empty' => __( 'Select a location first, then pick a date and time.', 'bizzthemes' ),
		'book_closed' => __( 'We are closed on this date, pick another one.', 'bizzthemes' ),
		'book_past' => __( 'We cannot book you for the past date, pick another one.', 'bizzthemes' ),
		'book_success' => __( 'Thanks, your booking has been received. Expect confirmation email shortly after someone reviews your booking.', 'bizzthemes' ),
		'book_nocars' => __( 'No cars found.', 'bizzthemes' ),
		'book_noextra' => __( 'No extras selected.', 'bizzthemes' ),
		'book_noextras' => __( 'No extras available for this car.', 'bizzthemes' ),
		'book_required' => __( 'Required.', 'bizzthemes' ),
		'email_required' => __( 'Email is not valid.', 'bizzthemes' ),
		'thankyou_page' => $opt_s['pay_thankyou'],
		'date_format' => get_option( 'date_format' )
	));

}

/* BOOKINGS: Feature Pointers */
/*------------------------------------------------------------------*/
add_action( 'admin_head', 'bizz_book_pointers' );
function bizz_book_pointers() {
	global $themeid, $wpdb;
	
	if( !is_admin() || version_compare(get_bloginfo('version'), '3.2.3', '<=') )
		return;
	
	if ( apply_filters( 'show_wp_pointer_admin_bar', TRUE ) && get_user_setting( 'b_step_1', 0 ) && get_user_setting( 'b_step_2', 0 ) && get_user_setting( 'b_step_3', 0 ) && get_user_setting( 'b_step_4', 0 )  )
		return;

	// Using Pointers
	wp_enqueue_style( 'wp-pointer' );
	wp_enqueue_script( 'wp-pointer' );

	// step 1?
	$step_1 = '<h3>' . __( 'Add Car Type' ) . '</h3>';
	$step_1 .= '<p>' . sprintf(__('It appears you have no car types, which are required for booking to work properly.<br/><br/><a href="%1$s">Add car type</a>'), wp_nonce_url(admin_url('edit-tags.php?taxonomy=bizz_cars_type&post_type=bizz_bookings'))) . '</p>';
	$step_1_hide = get_user_setting( 'b_step_1', 0 ); // check settings on user
	$step_1_count = wp_count_terms('bizz_cars_type');
	
	// step 2?
	$step_2 = '<h3>' . __( 'Add Car Location' ) . '</h3>';
	$step_2 .= '<p>' . sprintf(__('It appears you have no car locations, which are required for booking to work properly.<br/><br/><a href="%1$s">Add car location</a>'), wp_nonce_url(admin_url('post-new.php?post_type=bizz_locations'))) . '</p>';
	$step_2_hide = get_user_setting( 'b_step_2', 0 ); // check settings on user
	$step_2_count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'bizz_locations'");
	
	// step 3?
	$step_3 = '<h3>' . __( 'Add Cars' ) . '</h3>';
	$step_3 .= '<p>' . sprintf(__('It appears you have no cars, which are required for booking to work properly.<br/><br/><a href="%1$s">Add a car</a>'), wp_nonce_url(admin_url('post-new.php?post_type=bizz_cars'))) . '</p>';
	$step_3_hide = get_user_setting( 'b_step_3', 0 ); // check settings on user
	$step_3_count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'bizz_cars'");
	
	// step 4?
	$step_4 = '<h3>' . __( 'Add Prices' ) . '</h3>';
	$step_4 .= '<p>' . sprintf(__('It appears you have no prices set for your cars, which are required for booking to work properly.<br/><br/><a href="%1$s">Set a price</a>'), wp_nonce_url(admin_url('post-new.php?post_type=bizz_pricing'))) . '</p>';
	$step_4_hide = get_user_setting( 'b_step_4', 0 ); // check settings on user
	$step_4_count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'bizz_pricing'");
?>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		<?php if ( !$step_1_hide && !$step_1_count && apply_filters( 'show_wp_pointer_admin_bar', TRUE ) ) { ?>
		jQuery('#menu-posts-bizz_bookings').pointer({
			content    : '<?php echo $step_1; ?>',
			position   : {
				edge: 'left',
				align: 'center'
			},
			close: function() {
				setUserSetting( 'b_step_1', '1' );
			}
		}).pointer('open');
		<?php } ?>
		<?php if ( !$step_2_hide && !$step_2_count && ($step_1_count) && apply_filters( 'show_wp_pointer_admin_bar', TRUE ) ) { ?>
		jQuery('#menu-posts-bizz_bookings').pointer({
			content    : '<?php echo $step_2; ?>',
			position   : {
				edge: 'left',
				align: 'center'
			},
			close: function() {
				setUserSetting( 'b_step_2', '1' );
			}
		}).pointer('open');
		<?php } ?>
		<?php if ( !$step_3_hide && !$step_3_count && ($step_1_count && $step_2_count) && apply_filters( 'show_wp_pointer_admin_bar', TRUE ) ) { ?>
		jQuery('#menu-posts-bizz_bookings').pointer({
			content    : '<?php echo $step_3; ?>',
			position   : {
				edge: 'left',
				align: 'center'
			},
			close: function() {
				setUserSetting( 'b_step_3', '1' );
			}
		}).pointer('open');
		<?php } ?>
		<?php if ( !$step_4_hide && !$step_4_count && ($step_1_count && $step_2_count && $step_3_count) && apply_filters( 'show_wp_pointer_admin_bar', TRUE ) ) { ?>
		jQuery('#menu-posts-bizz_bookings').pointer({
			content    : '<?php echo $step_4; ?>',
			position   : {
				edge: 'left',
				align: 'center'
			},
			close: function() {
				setUserSetting( 'b_step_4', '1' );
			}
		}).pointer('open');
		<?php } ?>
	});
	</script>
<?php
}

/* BOOKINGS: CSS */
/*------------------------------------------------------------------*/
add_action('wp_print_styles', 'bizz_book_styles');
function bizz_book_styles() {

	// online
	wp_enqueue_style( 'jquery_ui_style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/blitzer/jquery-ui.css'); #header
	wp_enqueue_script( 'jquery_datepicker', BIZZ_FRAME_SCRIPTS . '/ui.datepicker.js', array( 'jquery' ) ); #header
	
	// offline
	// wp_enqueue_style( 'wp-jquery-ui-dialog'); #header
	
	
}

/* BOOKINGS: VALIDATE */
/*------------------------------------------------------------------*/
					
//for logged-in users
add_action('wp_ajax_validate_booking', 'bizz_booking_validate');

//for none logged-in users
add_action('wp_ajax_nopriv_validate_booking', 'bizz_booking_validate');	
	
function bizz_booking_validate() {
	global $wpdb;

	$params = $_GET["params"];
	$opt_s = get_option('booking_options');
	
	if (isset($_GET["step"])) {

		$qs_step = $_GET["step"];
		
		// step 2 : user inserts on form date, time & location of pickup and return --> create cookie with date, time & location selection and return available cars
		if ($qs_step == "2") {
			
			$date1 = strtotime( $params["date_of_pickup"] . ' ' . $params["hour_of_pickup"] );
			$date2 = strtotime( $params["date_of_return"] . ' ' . $params["hour_of_return"] );
			$days = bizz_count_days($date1, $date2);
			
			// Page ID by page name
			$location_of_pickup = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$params["location_of_pickup"]."'");
			$location_of_pickup = ($location_of_pickup) ? $location_of_pickup : null;
			// Page ID by page name
			$location_of_return = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$params["location_of_return"]."'");
			$location_of_return = ($location_of_return) ? $location_of_return : null;

			$carhire_cookie = array(
				"date_of_pickup" => $params["date_of_pickup"],
				"hour_of_pickup" => $params["hour_of_pickup"],
				"date_of_return" => $params["date_of_return"],
				"hour_of_return" => $params["hour_of_return"],
				"location_of_pickup" => $location_of_pickup,
				"location_of_pickup_name" => get_the_title( $location_of_pickup ) . ', ' . get_post_meta($params["location_of_pickup"], 'bizzthemes_location_address', true),
				"location_of_return" => $location_of_return,
				"location_of_return_name" => get_the_title( $location_of_return ) . ', ' . get_post_meta($params["location_of_return"], 'bizzthemes_location_address', true),
				"count_days" => $days,
				"currency" => $opt_s['pay_currency']
			);
			
			// set cookie
			bizz_clear_booking_cookie();
			bizz_fill_booking_cookie($carhire_cookie);
			
			print bizz_return_cars($carhire_cookie);
		}
		// validating step 3 : user selects car --> update cookie with car selection and return selected car extras
		elseif ($qs_step == "3") {	
			$carhire_cookie = array();
			$carhire_cookie = json_decode(stripslashes($_COOKIE['carhire']));
			$carhire_cookie->car_id = $params["car_id"];
			$carhire_cookie->car_name = get_the_title( $params["car_id"] );
			$carhire_cookie->car_image = get_post_meta($params["car_id"], 'bizzthemes_car_image', true);
			$carhire_cookie->car_cost = $params["car_cost"];
			
			// set cookie
			bizz_clear_booking_cookie();
			bizz_fill_booking_cookie($carhire_cookie);
			
			print bizz_return_car_extras($params["car_id"]);
		}
		// validating step 4 : user selects extras --> update cookie with car extras selection and return checkout form 
		elseif ($qs_step == "4") {
			$carhire_cookie = array();
			$carhire_cookie = json_decode(stripslashes($_COOKIE['carhire']));
			
			// extras pricing
			$array_extras_qs = ( $params["car_extras"] ) ? explode("|", $params["car_extras"]) : array();
			$extras_total = array();
			$array_extras = array();
			$count = count($array_extras_qs);
			if ( $count ) {
				for ($i = 0; $i < $count; $i++) {
					$array_extras_inner =  explode(",", $array_extras_qs[$i]);
					array_push($array_extras, $array_extras_inner);
					if ( isset($array_extras_inner[2]) )
						$extras_total[] = $array_extras_inner[2];
				}
			}
			$extras_total = array_sum($extras_total);
			$carhire_cookie->car_extras = array();
			$carhire_cookie->car_extras = $array_extras;
			
			// tax and deposit
			$tax_percentage = ($opt_s['pay_tax'] / 100);
			$tax_total = (($extras_total + $carhire_cookie->car_cost) * $tax_percentage);
			$deposit_percentage = ($opt_s['pay_deposit'] / 100);
			$deposit_total = (($extras_total + $carhire_cookie->car_cost + $tax_total) * $deposit_percentage); 
			
			// DO NOT USE FOR PAYMENT TRANSACTION, READ IT FROM DATABASE!!!
			$carhire_cookie->car_total_payment = array();
			$carhire_cookie->car_total_payment = array( 
				"car_total" => $carhire_cookie->car_cost,
				"extras_total" => $extras_total, 
				"tax_percentage" => $tax_percentage,
				"deposit_percentage" => $deposit_percentage, 				
				"tax_total" => $tax_total,
				"deposit" => $deposit_total,
				"total" => ($extras_total + $carhire_cookie->car_cost + $tax_total),
			);
			
			// set cookie
			bizz_clear_booking_cookie();
			bizz_fill_booking_cookie($carhire_cookie);
			
			print json_encode($carhire_cookie);
		}
		// validating step 5 : user checkouts to payment --> read cookie
		else if ($qs_step == "5") {
			global $wpdb;
			
			// read form
			foreach ( $params as $param ) {
				$form_data[$param['name']] = $param['value'];
			}

			// read cookie
			$carhire_cookie = array();
			$carhire_cookie = json_decode(stripslashes($_COOKIE['carhire']));
			
			// Create post object
			$count_bookings = wp_count_posts('bizz_bookings');
			$title_bookings = __('Booking #') . ($count_bookings->publish + 1);
			$booking_post = array(
				'post_title' => $title_bookings,
				'post_status' => 'publish',
				'post_type' => 'bizz_bookings'
			);

			// Insert the post into the database
			$this_post_id = wp_insert_post( $booking_post );
			
			// Build variables array
			$bookopts['tracking_id'] = bizz_rand_sha1(9);
			$bookopts['pay_total'] = $carhire_cookie->car_total_payment->total;
			$bookopts['pay_deposit'] = $carhire_cookie->car_total_payment->deposit;
			$bookopts['pay_car'] = $carhire_cookie->car_total_payment->car_total;
			$bookopts['pay_extras'] = $carhire_cookie->car_total_payment->extras_total;
			$bookopts['pay_tax'] = $carhire_cookie->car_total_payment->tax_total;
			$bookopts['car'] = $carhire_cookie->car_id;
			$bookopts['extras'] = $carhire_cookie->car_extras;
			$bookopts['pickup_location'] = $carhire_cookie->location_of_pickup;
			$bookopts['return_location'] = $carhire_cookie->location_of_return;
			$bookopts['pickup_date'] = $carhire_cookie->date_of_pickup;
			$bookopts['pickup_hour'] = $carhire_cookie->hour_of_pickup;
			$bookopts['return_date'] = $carhire_cookie->date_of_return;
			$bookopts['return_hour'] = $carhire_cookie->hour_of_return;
			$bookopts['flight'] = $form_data['flight'];
			$bookopts['customer_title'] = $form_data['customer_title'];
			$bookopts['customer_fname'] = $form_data['first_name'];
			$bookopts['customer_lname'] = $form_data['last_name'];
			$bookopts['customer_fullname'] = $form_data['first_name'].' '.$form_data['last_name'];
			$bookopts['customer_email'] = $form_data['email'];
			$bookopts['customer_phone'] = $form_data['phone'];
			$bookopts['customer_contact_option'] = $form_data['contact_option'];
			$bookopts['customer_country'] = $form_data['countries'];
			$bookopts['customer_state'] = $form_data['state_or_province'];
			$bookopts['customer_zip'] = $form_data['postcode'];
			$bookopts['customer_address'] = $form_data['address'];
			$bookopts['customer_comments'] = $form_data['comms'];
			
			// Add post meta
			add_post_meta($this_post_id, 'bizzthemes_bookings_track', $bookopts['tracking_id']);
			add_post_meta($this_post_id, 'bizzthemes_car_pay_total', $bookopts['pay_total']);
			add_post_meta($this_post_id, 'bizzthemes_car_pay_deposit', $bookopts['pay_deposit']);
			add_post_meta($this_post_id, 'bizzthemes_car_pay_car', $bookopts['pay_car']);
			add_post_meta($this_post_id, 'bizzthemes_car_pay_extras', $bookopts['pay_extras']);
			add_post_meta($this_post_id, 'bizzthemes_car_pay_tax', $bookopts['pay_tax']);
			// add_post_meta($this_post_id, 'bizzthemes_car_payment_method', $form_data['payment_method']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_car', $bookopts['car']); #car id
			foreach ( (array) $bookopts['extras'] as $key => $value ) {
				if ( isset($value[0]) )
					add_post_meta($this_post_id, 'bizzthemes_bookings_extras', $value[0]); #extras
			}
			add_post_meta($this_post_id, 'bizzthemes_bookings_pickup', $bookopts['pickup_location']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_return', $bookopts['return_location']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_date1', $bookopts['pickup_date']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_date1_time', $bookopts['pickup_hour']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_date2', $bookopts['return_date']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_date2_time', $bookopts['return_hour']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_flight', $bookopts['flight']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_ctitle', $bookopts['customer_title']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_fname', $bookopts['customer_fname']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_lname', $bookopts['customer_lname']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_email', $bookopts['customer_email']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_phone', $bookopts['customer_phone']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_scontact', $bookopts['customer_contact_option']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_country', $bookopts['customer_country']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_state', $bookopts['customer_state']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_zip', $bookopts['customer_zip']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_address', $bookopts['customer_address']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_comm_que', $bookopts['customer_comments']);
			
			// Date Time Format
			$pickup_date_format = date(get_option( 'date_format' ), strtotime($bookopts['pickup_date']));
			$pickup_time_format = date(get_option( 'time_format' ), strtotime($bookopts['pickup_hour']));
			$return_date_format = date(get_option( 'date_format' ), strtotime($bookopts['return_date']));
			$return_time_format = date(get_option( 'time_format' ), strtotime($bookopts['return_hour']));

			// Send via email
			$opt_b = get_option('booking_options');
			$your_email = $opt_b['admin_email'];
			$customer_email = $bookopts['customer_email'];
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html; charset=UTF-8" . "\r\n";
			$headers .= 'From: "'.$bookopts['customer_fullname'].'" <'.$customer_email.'>' . "\r\n";
			$emailTo = $your_email; 
			$subject = html_entity_decode( $title_bookings, ENT_QUOTES, 'UTF-8' );
			$body = '<html><body>';
			$body .= '<table rules="all" style="border-color:#dddddd;" cellpadding="10">';
			$body .= "<tr><td colspan='2'><strong>".__('Customer?')."</strong> </td></tr>";
			$body .= "<tr><td>".__('Tracking ID')." </td><td>".$bookopts['tracking_id']."</td></tr>";
			$body .= "<tr><td>".__('Customer Title')." </td><td>".$bookopts['customer_title']."</td></tr>";
			$body .= "<tr><td>".__('First Name')." </td><td>".$bookopts['customer_fname']."</td></tr>";
			$body .= "<tr><td>".__('Last Name')." </td><td>".$bookopts['customer_lname']."</td></tr>";
			$body .= "<tr><td>".__('Email')." </td><td>".$bookopts['customer_email']."</td></tr>";
			$body .= "<tr><td>".__('Phone')." </td><td>".$bookopts['customer_phone']."</td></tr>";
			$body .= "<tr><td>".__('Contact Option')." </td><td>".$bookopts['customer_contact_option']."</td></tr>";
			$body .= "<tr><td>".__('Country')." </td><td>".$bookopts['customer_country']."</td></tr>";
			$body .= "<tr><td>".__('State/Province')." </td><td>".$bookopts['customer_state']."</td></tr>";
			$body .= "<tr><td>".__('Postcode/ZIP')." </td><td>".$bookopts['customer_zip']."</td></tr>";
			$body .= "<tr><td>".__('Address')." </td><td>".$bookopts['customer_address']."</td></tr>";
			$body .= "<tr><td>".__('Comments/Questions')." </td><td>".$bookopts['customer_comments']."</td></tr>";
			$body .= "<tr><td colspan='2'><strong>".__('Car?')."</strong> </td></tr>";
			$body .= "<tr><td>".__('Car Name')." </td><td>".get_the_title($bookopts['car'])."</td></tr>";
			$body .= "<tr><td colspan='2'><strong>".__('When and Where?')."</strong> </td></tr>";
			$body .= "<tr><td>".__('Pickup Location')." </td><td>".get_the_title($bookopts['pickup_location'])."</td></tr>";
			$body .= "<tr><td>".__('Return Location')." </td><td>".get_the_title($bookopts['return_location'])."</td></tr>";
			$body .= "<tr><td>".__('Start Date and Time')." </td><td>".$pickup_date_format.' @ '.$pickup_time_format."</td></tr>";
			$body .= "<tr><td>".__('Return Date and Time')." </td><td>".$return_date_format.' @ '.$return_time_format."</td></tr>";
			$body .= "<tr><td>".__('Flight Number')." </td><td>".$bookopts['flight']."</td></tr>";
			$body .= "<tr><td colspan='2'><strong>".__('Payment?')."</strong> </td></tr>";
			$body .= "<tr><td>".__('Total')." </td><td>".$opt_b['pay_currency'].$bookopts['pay_total']."</td></tr>";
			$body .= "<tr><td>".__('Deposit')." </td><td>".$opt_b['pay_currency'].$bookopts['pay_deposit']."</td></tr>";
			$body .= "<tr><td>".__('Car')." </td><td>".$opt_b['pay_currency'].$bookopts['pay_car']."</td></tr>";
			$body .= "<tr><td>".__('Extras')." </td><td>".$opt_b['pay_currency'].$bookopts['pay_extras']."</td></tr>";
			$body .= "<tr><td>".__('Tax')." </td><td>".$opt_b['pay_currency'].$bookopts['pay_tax']."</td></tr>";
			$body .= "<tr><td colspan='2'><strong>".__('Next?')."</strong> </td></tr>";
			$body .= "<tr><td>".__('Action')." </td><td><a href='".home_url('/')."wp-admin/post.php?post=".$this_post_id."&action=edit'>".__('Accept or Cancel this booking')."</a></td></tr>";
			$body .= "</table>";
			$body .= "</body></html>";
			$body = html_entity_decode( $body, ENT_QUOTES, 'UTF-8' );
			
			if ( !isset($opt_b['admin_notifications']) || $opt_b['admin_notifications'] != '1' )
				wp_mail($your_email, $subject, $body, $headers); //you
			
			// notification function inside post-type-bookings.php
			booking_send_notification('customer', $bookopts);
			
			// Success			
			print json_encode('success');
		}
		else if ($qs_step == "dc") {
			bizz_clear_booking_cookie();
			print "cookie cleared";
		}
		else if ($qs_step == "so") { // selected special offer
			// nothing here yet
		}
		else {
			header("HTTP/1.0 400 Bad request");
			print "Bad request! (unknown step)";
		}
	}
	/*
	else {
		header("HTTP/1.0 400 Bad request");
		print "Bad request! (step not defined)";
	}
	*/
	
	exit();
	
}

/* FILTERS */
					
//for logged-in users
add_action('wp_ajax_booking_filters', 'bizz_booking_filters');

//for none logged-in users
add_action('wp_ajax_nopriv_booking_filters', 'bizz_booking_filters');	
	
function bizz_booking_filters() {
	$meta_boxes = array();
	$meta_boxes = apply_filters ( 'bizz_meta_boxes' , $meta_boxes );

	foreach ( $meta_boxes as $meta_box ) {
		if ( $meta_box['id'] == 'bizzthemes_cars_meta' )
			$meta_fields = $meta_box['fields'];
	}
	foreach ( $meta_fields as $meta_field ) {
		if ( $meta_field['id'] == 'bizzthemes_car_transmission' )
			$transmission = $meta_field['options'];
		if ( $meta_field['id'] == 'bizzthemes_car_type' )
			$type = $meta_field['options'];
	}
	
	$filters['type'] = $type;
	$filters['transmission'] = $transmission;
	
	print json_encode($filters);
	
	exit();
	
}

/* COOKIE */
					
//for logged-in users
add_action('wp_ajax_booking_cookie', 'bizz_booking_cookie');

//for none logged-in users
add_action('wp_ajax_nopriv_booking_cookie', 'bizz_booking_cookie');	
	
function bizz_booking_cookie() {
	
	$cookie = ( isset( $_COOKIE['carhire'] ) ) ? json_decode(stripslashes($_COOKIE['carhire'])) : 'nocookie';
	
	print json_encode( $cookie );
	
	exit();
	
}

function bizz_fill_booking_cookie($_array_to_store_to_cookie) {
	
	if (!empty($_COOKIE['carhire']))
		setcookie("carhire", '', time()-28800, '/'); #8 hours
		
	setcookie("carhire", json_encode($_array_to_store_to_cookie), time()+28800, '/'); #8 hours
	
}

function bizz_clear_booking_cookie() {

	if (!empty($_COOKIE['carhire']))
		setcookie("carhire", '', time()-28800, '/'); #8 hours
}

// return available cars
function bizz_return_cars($carhire_cookie = '') {
	$opt_s = get_option('booking_options');
	$args = array( 
		'post_type' => 'bizz_cars', 
		'numberposts' => -1 
	);
	$car_posts = get_posts( $args );
	$car_options["cars"] = array();
	foreach ($car_posts as $car_post) {
		$custom = get_post_custom($car_post->ID);
		$available = bizz_availablity( $car_post->ID, $custom["bizzthemes_car_location"][0], $carhire_cookie );
		$availability = ( $available['date'] == 'ok' && $available['location'] == 'ok') ? true : false; 
		$car_options["cars"][] = array(
            'id' => $car_post->ID,
			'post_name' => $car_post->post_name,
			'name' => $car_post->post_title,
			'description' => $custom["bizzthemes_car_description"][0],
			'picture_src' => $custom["bizzthemes_car_image"][0],
			'type' => $custom["bizzthemes_car_type"][0],
			'currency' => $opt_s['pay_currency'],
			'cost' => bizz_car_price( $custom["bizzthemes_car_type"][0], $carhire_cookie ),
			'equipment' => array(
				'seats' => $custom["bizzthemes_car_seats"][0],
				'doors' => $custom["bizzthemes_car_doors"][0],
				'transmission' => $custom["bizzthemes_car_transmission"][0]
			),
			'availability' => $availability,
			'avail_date' => $available['date'],
			'avail_location' => $available['location']
        );
	}
	
	$car_options["cars"] = bizz_cars_sort($car_options["cars"], 'availability'); 

	return json_encode($car_options);
}

function bizz_cars_sort($a,$subkey) {
	
	foreach($a as $k=>$v)
		$b[$k] = strtolower($v[$subkey]);
		
	arsort($b); // desc
	
	foreach($b as $key=>$val)
		$c[] = $a[$key];
		
	return $c;
}

// calculate the car price
function bizz_car_price( $car_type = '', $carhire_cookie ) {
	// strtotime
	$pickup_btime = strtotime( $carhire_cookie['date_of_pickup'] );
	$return_btime = strtotime( $carhire_cookie['date_of_return'] );
	
	// day
	$days = $carhire_cookie['count_days'];

	// read posts
	$args = array(
		'post_type' => 'bizz_pricing',
		'meta_key' => 'bizzthemes_price_type',
		'meta_value' => $car_type,
		'numberposts' => -1
	);
	$pricing_posts = get_posts( $args );
	
	// loop
	if ( $pricing_posts ) {
		
		// get max day range
		foreach ($pricing_posts as $max_post) {
			$custom = get_post_custom($max_post->ID);
			$range_to[] = $custom["bizzthemes_price_range_to"][0];
		}
		$max_to = max($range_to);
		
		// calculate price
		foreach ($pricing_posts as $pricing_post) {
			$custom = get_post_custom($pricing_post->ID);
			$daily = $custom["bizzthemes_price_daily"][0];
			$from = $custom["bizzthemes_price_range_from"][0];
			$to = $custom["bizzthemes_price_range_to"][0];
			$s_from = ( isset($custom["bizzthemes_price_season_from"][0]) ) ? strtotime($custom["bizzthemes_price_season_from"][0]) : '';
			$s_to = ( isset($custom["bizzthemes_price_season_to"][0]) ) ? strtotime($custom["bizzthemes_price_season_to"][0]) : '';
						
			// days match?
			if ( $from <= $days && $to >= $days) {

				$reg_price[] = $daily; #make array
				
				// check season
				if ( $s_from <= $pickup_btime &&  $s_to > $return_btime )
					$season_price[] = $daily; #make array

			}
			// max day range instead
			else if ( $to == $max_to ) {
				
				$max_price[] = $daily; #make array
				
				// check season
				if ( $s_from <= $pickup_btime &&  $s_to > $return_btime )
					$max_season_price[] = $daily; #make array

			}
			
		}
	}

	// price for all booked days
	if ( isset($reg_price) ) 
		$price = min($reg_price) * $days; #take lowes
	elseif (isset($season_price) )
		$price = min($season_price) * $days; #take lowes
	elseif ( isset($max_price) ) 
		$price = min($max_price) * $days; #take lowes
	elseif (isset($max_season_price) )
		$price = min($max_season_price) * $days; #take lowes
	else
		$price = 0;

	return $price;
}

// return availability for each car
function bizz_availablity( $car_id = '', $location_id = '', $carhire_cookie = '' ) {	
	global $wpdb;
	// global
	$custom_car = get_post_custom($car_id);
	// Page ID by page name
	$car_current_location = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$custom_car["bizzthemes_car_location"][0]."'");
	$car_current_location = $car_current_location;
	
	// strtotime
	$pickup_ctime = strtotime( $carhire_cookie['date_of_pickup'] . ', ' . $carhire_cookie['hour_of_pickup'] );
	$return_ctime = strtotime( $carhire_cookie['date_of_return'] . ', ' . $carhire_cookie['hour_of_return'] );
			
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
			$booking["status"] = (isset($custom["bizzthemes_bookings_status"][0])) ? $custom["bizzthemes_bookings_status"][0] : 'pending';
			$booking["date_of_pickup"] = (isset($custom["bizzthemes_bookings_date1"][0])) ? $custom["bizzthemes_bookings_date1"][0] : '';
			$booking["hour_of_pickup"] = (isset($custom["bizzthemes_bookings_date1_time"][0])) ? $custom["bizzthemes_bookings_date1_time"][0] : '';
			$booking["date_of_return"] = (isset($custom["bizzthemes_bookings_date1_time"][0])) ? $custom["bizzthemes_bookings_date1_time"][0] : '';
			$booking["hour_of_return"] = (isset($custom["bizzthemes_bookings_date2_time"][0])) ? $custom["bizzthemes_bookings_date2_time"][0] : '';
			$booking["location_of_pickup"] = (isset($custom["bizzthemes_bookings_pickup"][0])) ? $custom["bizzthemes_bookings_pickup"][0] : '';
			$booking["location_of_return"] = (isset($custom["bizzthemes_bookings_return"][0])) ? $custom["bizzthemes_bookings_return"][0] : '';
			
			// strtotime
			$pickup_btime = strtotime( $booking["date_of_pickup"] . ', ' . $booking["hour_of_pickup"] );
			$return_btime = strtotime( $booking["date_of_return"] . ', ' . $booking["hour_of_return"] );
			
			// testing
			/*
			echo 'pickup selected: '. $pickup_ctime . ' - ' . $carhire_cookie['date_of_pickup'] . ', ' . $carhire_cookie['hour_of_pickup'] . '<br/>';
			echo 'pickup booked: '. $pickup_btime . ' - ' . $booking["date_of_pickup"] . ', ' . $booking["hour_of_pickup"] . '<br/>';
			echo 'return selected: '. $return_ctime . ' - ' . $carhire_cookie['date_of_return'] . ', ' . $carhire_cookie['hour_of_return'] . '<br/>';
			echo 'return booked: '. $return_btime . ' - ' . $booking["date_of_return"] . ', ' . $booking["hour_of_return"] . '<br/>';
			echo 'status booked: '. $booking["status"] . '<br/><br/>';
			*/
			
			// check status
			$avail_error['status'] = ($booking["status"] == 'approved' || $booking["status"] == 'pending') ? true : false;
					
			// check dates
			if ($pickup_ctime >= $pickup_btime && $pickup_ctime <= $return_btime && $avail_error['status'])
				$avail_error['date'] = sprintf(__('Car already booked from %1$s, %2$s to %3$s, %4$s'), $booking["date_of_pickup"], $booking["hour_of_pickup"], $booking["date_of_return"], $booking["hour_of_return"]);
			elseif ($return_ctime >= $pickup_btime && $return_ctime <= $return_btime && $avail_error['status'])
				$avail_error['date'] = sprintf(__('Car already booked from %1$s, %2$s to %3$s, %4$s'), $booking["date_of_pickup"], $booking["hour_of_pickup"], $booking["date_of_return"], $booking["hour_of_return"]);
			else
				$avail_error['date'] = 'ok';
				
			// check location
			if ($carhire_cookie['location_of_pickup'] != $car_current_location)
				$avail_error['location'] = sprintf(__('Current car location: %1$s, %2$s'), get_the_title($car_current_location), get_post_meta($car_current_location, 'bizzthemes_location_address', true));
			else
				$avail_error['location'] = 'ok';
			
			// already booked?
			if ($avail_error['date'] != 'ok' || $avail_error['location'] != 'ok')
				break;
			
		}
	}
	else {
	
		// check location
		if ($carhire_cookie['location_of_pickup'] != $car_current_location)
			$avail_error['location'] = sprintf(__('Current car location: %1$s, %2$s'), get_the_title($car_current_location), get_post_meta($car_current_location, 'bizzthemes_location_address', true));
		else
			$avail_error['location'] = 'ok';
		
	}
	
	return $avail_error;
		
}

// return extras per each car
function bizz_return_car_extras( $car_id = '') {
	$opt_s = get_option('booking_options');
	
	// read cookie
	$carhire_cookie = array();
	$carhire_cookie = json_decode(stripslashes($_COOKIE['carhire']));
	
	// list extras
	$extras = get_terms( 'bizz_cars_extra', array( 'hide_empty' => 0 ) );
	$count = count($extras);
	$car_extras["car_extras"] = array();
	$custom = get_post_custom($car_id); #car id
	$car_extras = isset($custom["bizzthemes_car_extras"]) ? $custom["bizzthemes_car_extras"] : '';
	$car_extras = ( is_array($car_extras) ) ? $car_extras : array();
	if ($count > 0) {
		foreach ($extras as $extra) {
			if ( !in_array($extra->slug, $car_extras) ) #1476 for porsche
				continue;
		
			$car_extras["car_extras"][] = array(
				'id' => $extra->term_id,
				'slug' => $extra->slug,
				'name' => $extra->name,
				'description' => $extra->description,
				'cost' => bizz_extra_price( $extra->term_id, $carhire_cookie ),
				'picture_src' => get_option('taxonomy_'.$extra->term_id.'_bizz_extra_image'),
				'currency' => $opt_s['pay_currency'],
			);
		}
	}
	
	return json_encode($car_extras);
}

// calculate the extra price
function bizz_extra_price( $extra_id = '', $carhire_cookie ) {
	// read
	$price = get_option('taxonomy_'.$extra_id.'_bizz_extra_price');
	$range = get_option('taxonomy_'.$extra_id.'_bizz_extra_price_s');
	
	// per rental or daily?
	$calculate = ( $range == 'rental' ) ? 1 : $carhire_cookie->count_days;

	// price for all booked days
	$price = ($price * $calculate);

	return $price;
}

// return the number of days between the two dates passed in
function bizz_count_days( $a, $b ) {
    // First we need to break these dates into their constituent parts:
    $gd_a = getdate( $a );
    $gd_b = getdate( $b );

    // Now recreate these timestamps, based upon noon on each day
    // The specific time doesn't matter but it must be the same each day
    $a_new = mktime( 12, 0, 0, $gd_a['mon'], $gd_a['mday'], $gd_a['year'] );
    $b_new = mktime( 12, 0, 0, $gd_b['mon'], $gd_b['mday'], $gd_b['year'] );

    // Subtract these two numbers and divide by the number of seconds in a
    //  day. Round the result since crossing over a daylight savings time
    //  barrier will cause this time to be off by an hour or two.
	$count = round( abs( $a_new - $b_new ) / 86400 );
	$count = ( $count == 0 ) ? 1 : $count;
	
    return $count;
}