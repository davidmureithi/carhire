<?php
/*	
	
	WIDGET FILTERS:

    widget_title			- widget title
	widget_intro	 		- intro text
	
*/

/*---------------------------------------------------------------------------------*/
/* Book Online Widget */
/*---------------------------------------------------------------------------------*/
class Bizz_Booking extends WP_Widget {

	function Bizz_Booking() {
		$widget_ops = array('classname' => 'widget_booking', 'description' => __('Add registration form for setting appointments with customers.','bizzthemes'));
		$this->WP_Widget('bizz_booking', __('Book Online','bizzthemes'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

		echo $before_widget;
?>
			<div id="booktop"></div>
			<div class="bspr top"></div>
				<?php if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ; ?>
				<div class="steps_tabs_container">
					<ul class="steps_tabs clearfix">
						<li class="step1_tab selected">
							<span class="number">1</span>
							<a href="#" data-rel="1" class="tablink disabled">
								<span class="text"><?php _e('Date'); ?></span>
							</a>
						</li>
						<li class="step2_tab">
							<span class="number">2</span>
							<a href="#" data-rel="2" class="tablink disabled">
								<span class="text"><?php _e('Car'); ?></span>
							</a>
						</li>
						<li class="step3_tab">
							<span class="number">3</span>
							<a href="#" data-rel="3" class="tablink disabled">
								<span class="text"><?php _e('Extras'); ?></span>
							</a>
						</li>
						<li class="step4_tab">
							<span class="number">4</span>
							<a href="#" data-rel="4" class="tablink disabled">
								<span class="text"><?php _e('Checkout'); ?></span>
							</a>
						</li>
					</ul>
				</div>
				<div class="bookwrap">
					<div class="messages"><!----></div>
<?php
					locate_template('lib_theme/booking/step1.php', true );
					locate_template('lib_theme/booking/step2.php', true );
					locate_template('lib_theme/booking/step3.php', true );
					locate_template('lib_theme/booking/step4.php', true );
?>
					<div class="loading_wrapper clearfix"><!----></div>
				</div><!-- /.bookingwrap -->
			<div class="bspr bottom"></div>
<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 
			'title' => 'Rent a car'
		));
		$title = strip_tags($instance['title']);
		$intro = format_to_edit($instance['intro']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
	}
}

register_widget('Bizz_Booking');

/*---------------------------------------------------------------------------------*/
/* Detect Ajax */
/*---------------------------------------------------------------------------------*/
if (!function_exists('bizz_is_ajax')) {
	function bizz_is_ajax() {
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') return true;
		return false;
	}
}

/*---------------------------------------------------------------------------------*/
/* Validate Dates */
/*---------------------------------------------------------------------------------*/

//for logged-in users
add_action('wp_ajax_booking_time_action', 'bizz_booking_process_time');

//for none logged-in users
add_action('wp_ajax_nopriv_booking_time_action', 'bizz_booking_process_time');	
	
function bizz_booking_process_time() {
	global $wpdb;
	
	// Page ID by page name
	$location = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$_POST['location']."'");
	
	if ( $_POST['day'] == '1' ) {
		$start = get_post_meta($location, 'bizzthemes_hours_monday_open', true);
		$end = get_post_meta($location, 'bizzthemes_hours_monday_close', true);
		$closed = get_post_meta($location, 'bizzthemes_hours_monday_closed', true);
		
	} elseif ( $_POST['day'] == '2' ) {
		$start = get_post_meta($location, 'bizzthemes_hours_tuesday_open', true);
		$end = get_post_meta($location, 'bizzthemes_hours_tuesday_close', true);
		$closed = get_post_meta($location, 'bizzthemes_hours_tuesday_closed', true);
		
	} elseif ( $_POST['day'] == '3' ) {
		$start = get_post_meta($location, 'bizzthemes_hours_wednesday_open', true);
		$end = get_post_meta($location, 'bizzthemes_hours_wednesday_close', true);
		$closed = get_post_meta($location, 'bizzthemes_hours_wednesday_closed', true);
		
	} elseif ( $_POST['day'] == '4' ) {
		$start = get_post_meta($location, 'bizzthemes_hours_thursday_open', true);
		$end = get_post_meta($location, 'bizzthemes_hours_thursday_close', true);
		$closed = get_post_meta($location, 'bizzthemes_hours_thursday_closed', true);
		
	} elseif ( $_POST['day'] == '5' ) {
		$start = get_post_meta($location, 'bizzthemes_hours_friday_open', true);
		$end = get_post_meta($location, 'bizzthemes_hours_friday_close', true);
		$closed = get_post_meta($location, 'bizzthemes_hours_friday_closed', true);
		
	} elseif ( $_POST['day'] == '6' ) {
		$start = get_post_meta($location, 'bizzthemes_hours_saturday_open', true);
		$end = get_post_meta($location, 'bizzthemes_hours_saturday_close', true);
		$closed = get_post_meta($location, 'bizzthemes_hours_saturday_closed', true);
		
	} elseif ( $_POST['day'] == '0' ) {
		$start = get_post_meta($location, 'bizzthemes_hours_sunday_open', true);
		$end = get_post_meta($location, 'bizzthemes_hours_sunday_close', true);
		$closed = get_post_meta($location, 'bizzthemes_hours_sunday_closed', true);
	}
		
	// EMPTY
	if ( empty($location) ) {
	
		print_r('EMPTY');

	}
	// CLOSED
	elseif ( $closed ) {
	
		print_r('CLOSED');
		
	}
	// PAST
	elseif ( $_POST['dature'] < date('Y-m-d') ) {
	
		print_r('PAST');
	
	}
	// OPENED
	else {
	
		$times = bizz_create_time_range( $start, $end, '30 mins' );
		$return = '';
		foreach ($times as $key => $time)
			$return .= '<option value="' . date('H:i', $time) . '">' . date('H:i', $time) . '</option>';

		print_r($return);
		
	}

	exit();
	
}

/*---------------------------------------------------------------------------------*/
/* Validate Form */
/*---------------------------------------------------------------------------------*/

//for logged-in users
add_action('wp_ajax_booking_form_action', 'bizz_booking_process_form');

//for none logged-in users
add_action('wp_ajax_nopriv_booking_form_action', 'bizz_booking_process_form');	
	
function bizz_booking_process_form() {
	
	// parse data
	$data = $_POST['data'];
	parse_str($data, $output);
	
	// field name
	$name['location_pickup'] = __('Pickup location');
	$name['date_pickup'] = __('Pickup date');
	$name['time_pickup'] = __('Pickup time');
	$name['location_return'] = __('Return location');
	$name['date_return'] = __('Return date');
	$name['time_return'] = __('Return time');
	
	// error string
	$error = '';
	
	// local timezone
	date_default_timezone_set(get_option('timezone_string'));
	
	// EMPTY
	foreach ($output as $key => $value) {
	
		// skip spam
		if ( $key == 'is_spam' )
			continue;
			
		// emtpy?
		if ( empty($value) )
			$error .= $name[$key] . __(' field is empty.') . '<br />';
			
	}
	
	// strtotime
	$pickup_dtime = strtotime( $output['date_pickup'] . $output['time_pickup'] );
	$return_dtime = strtotime( $output['date_return'] . $output['time_return'] );
	
	// PAST TIME?
	if ( date('Y-m-d') == $output['date_pickup'] && date('H:i') > $output['time_pickup'] )
		$error .= sprintf(__('Today, you cannot book before %s.'), date('H:i')) . '<br />';
	
	// CORRECT DATE?
	if (  !empty($output['date_pickup']) && !empty($output['date_return']) && $pickup_dtime > $return_dtime )
		$error .= __('Your return date cannot be before the pickup date.') . '<br />';
	
	// ERROR?
	echo ( empty($error) ) ? 'SUCCESS' : $error;
	
	exit();
	
}

/** 
 * create_time_range  
 *  
 * @param mixed $start start time, e.g., 9:30am or 9:30 
 * @param mixed $end   end time, e.g., 5:30pm or 17:30 
 * @param string $by   1 hour, 1 mins, 1 secs, etc. 
 * @access public 
 * @return void 
 */ 
function bizz_create_time_range($start, $end, $by='30 mins') { 

    $start_time = strtotime($start); 
    $end_time   = strtotime($end); 

    $current    = time(); 
    $add_time   = strtotime('+'.$by, $current); 
    $diff       = $add_time-$current; 

    $times = array(); 
    while ($start_time < $end_time) { 
        $times[] = $start_time; 
        $start_time += $diff; 
    } 
    $times[] = $start_time;
	
    return $times; 
}