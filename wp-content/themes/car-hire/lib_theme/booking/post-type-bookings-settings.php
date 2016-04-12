<?php

/*

  FILE STRUCTURE:

- Custom post type icons
- Custom Post Types Init
- Columns for post types
- Custom Post Type Filters
- Custom Post Type Metabox Setup

*/

/* Set-up Hooks */
/*------------------------------------------------------------------*/
add_action('admin_init', 'booking_add_defaults');
add_action('admin_init', 'booking_init' );
add_action('admin_menu', 'booking_add_options_page');

/* Define default option settings */
/*------------------------------------------------------------------*/
function booking_add_defaults() {
	
	$tmp = get_option('booking_options');
	$admin_user = get_user_by('email', get_option('admin_email'));
	
	if( isset($tmp['chk_default_options_db']) || !is_array($tmp) ) {
	
		$array = array(	
			"pay_currency" => "$",
			"pay_deposit" => "20",
			"pay_tax" => "0",
			// "pay_allow" => 0,
			// "pay_paypal" => "",
			"pay_thankyou" => get_bloginfo('wpurl'),
			"admin_email" => get_option('admin_email'),
			"admin_name" => $admin_user->display_name,
			"admin_phone" => get_the_author_meta( 'phone', $admin_user->ID ),
			"admin_notifications" => "",
			"customer_email_subject" => esc_html( "Booking Sent" ),
			"customer_email_body" => esc_html( "Hi "."[CUSTOMER_FULLNAME]"."<br/><p>Your booking has been submitted and is pendind approval.<br/>If your car is not available, we will get back to you, otherwise, expect a confirmation email with all the details.</p><p>Details: [BOOK_DETAILS]</p><p>In case you want to cancel it, write us an email at [ADMIN_EMAIL] and reference Tracking ID: [TRACKING_ID] for your booking.</p><p>Thank you,</p><p>"."[ADMIN_NAME]"."</p>" ),
			"approved_email_subject" => esc_html( "Booking Approved" ),
			"approved_email_body" => esc_html( "Hi "."[CUSTOMER_FULLNAME]"."<br/><p>Your booking has been confirmed.</p><p>Details: [BOOK_DETAILS]</p><p>In case you want to cancel it, write us an email at [ADMIN_EMAIL] and reference Tracking ID: [TRACKING_ID] for your booking.</p><p>Thank you,</p><p>"."[ADMIN_NAME]"."</p>" ),
			"cancelled_email_subject" => esc_html( "Booking Cancelled" ),
			"cancelled_email_body" => esc_html( "Hi "."[CUSTOMER_FULLNAME]"."<br/><p>Your booking has been cancelled, however, you can schedule your booking again at any other time.</p><p>Thank you,</p><p>"."[ADMIN_NAME]"."</p>" ),
			"terms_conditions" => "",
			"chk_default_options_db" => ""
		);
		update_option('booking_options', $array);

	}
	
}

/* Init plugin options to white list our options */
/*------------------------------------------------------------------*/
function booking_init(){
	register_setting( 'booking_plugin_options', 'booking_options', 'booking_validate_options' );
}

/* Add menu page */
/*------------------------------------------------------------------*/
function booking_add_options_page() {
	add_submenu_page( 'edit.php?post_type=bizz_bookings', __('bookings'), __('Settings'), 'manage_options', 'booking-settings','booking_render_form');
}

/* Render the Plugin options form */
/*------------------------------------------------------------------*/
function booking_render_form() {
?>
	<div class="wrap">
		
		<div class="icon32" id="icon-options-general"><br></div>
		<h2><?php _e('Bookings Settings'); ?></h2>
		<form method="post" action="options.php">
		
			<?php settings_fields('booking_plugin_options'); ?>
			<?php $options = get_option('booking_options'); ?>
			
			<table class="form-table">
				<tr><td colspan="2"><div style="margin-top:10px;"><h3><?php _e('Payment Details'); ?></h3></div></td></tr>
				<tr>
					<th scope="row"><?php _e('Currency symbol'); ?></th>
					<td>
						<input type="text" size="57" name="booking_options[pay_currency]" value="<?php echo $options['pay_currency']; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Deposit amount (%)'); ?></th>
					<td>
						<input type="text" size="57" name="booking_options[pay_deposit]" value="<?php echo $options['pay_deposit']; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Tax amount (%)'); ?></th>
					<td>
						<input type="text" size="57" name="booking_options[pay_tax]" value="<?php echo $options['pay_tax']; ?>" />
					</td>
				</tr>
				<!--
				<tr>
					<th scope="row"><?php _e('PayPal email address (primary)'); ?></th>
					<td>
						<input type="text" size="57" name="booking_options[pay_paypal]" value="<?php echo $options['pay_paypal']; ?>" />
					</td>
				</tr>
				-->
				<tr>
					<th scope="row"><?php _e('Thank you page'); ?></th>
					<td>
						<input type="text" size="57" name="booking_options[pay_thankyou]" value="<?php echo $options['pay_thankyou']; ?>" />
					</td>
				</tr>
				<tr><td colspan="2"><div style="margin-top:10px;"><h3><?php _e('Admin Details'); ?></h3></div></td></tr>
				<tr>
					<th scope="row"><?php _e('Set Admin email'); ?></th>
					<td>
						<input type="text" size="57" name="booking_options[admin_email]" value="<?php echo $options['admin_email']; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Set Admin name'); ?></th>
					<td>
						<input type="text" size="57" name="booking_options[admin_name]" value="<?php echo $options['admin_name']; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Set Admin phone'); ?></th>
					<td>
						<input type="text" size="57" name="booking_options[admin_phone]" value="<?php echo $options['admin_phone']; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Admin notifications'); ?></th>
					<td>
						<label><input name="booking_options[admin_notifications]" type="checkbox" value="1" <?php if (isset($options['admin_notifications'])) { checked('1', $options['admin_notifications']); } ?> /> <?php _e('Disable admin email notifications'); ?></label>
					</td>
				</tr>
				<tr><td colspan="2"><div style="margin-top:10px;"><h3><?php _e('Email shortcodes'); ?></h3></div></td></tr>
				<tr>
					<th scope="row"><?php _e('Use in email subject or content.'); ?></th>
					<td>
						[ADMIN_NAME], [ADMIN_EMAIL], [TRACKING_ID], [PAY_TOTAL], [PAY_DEPOSIT],<br/>
						[PAY_CAR], [PAY_EXTRAS], [PAY_TAX], [CAR], [PICKUP_LOCATION],<br/>
						[RETURN_LOCATION], [PICKUP_DATE], [PICKUP_HOUR], [RETURN_DATE],<br/>
						[RETURN_HOUR], [FLIGHT], [CUSTOMER_TITLE], [CUSTOMER_FNAME],<br/>
						[CUSTOMER_LNAME], [CUSTOMER_FULLNAME], [CUSTOMER_EMAIL],<br/>
						[CUSTOMER_PHONE], [CUSTOMER_CONTACT_OPTION],<br/>
						[CUSTOMER_COUNTRY], [CUSTOMER_STATE], [CUSTOMER_ZIP],<br/>
						[CUSTOMER_ADDRESS], [CUSTOMER_COMMENTS], [BOOK_DETAILS]
					</td>
				</tr>
				<tr><td colspan="2"><div style="margin-top:10px;"><h3><?php _e('Confirmation Email'); ?></h3></div></td></tr>
				<tr>
					<th scope="row"><?php _e('Subject'); ?></th>
					<td>
						<input type="text" size="57" name="booking_options[customer_email_subject]" value="<?php echo $options['customer_email_subject']; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Email'); ?></th>
					<td>
						<textarea name="booking_options[customer_email_body]" rows="8" cols="59" type='textarea'><?php echo $options['customer_email_body']; ?></textarea><br />
						<span style="color:#666666;margin-left:2px;"><?php _e('Sent to customer when the booking has been submited.'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2"><div style="margin-top:10px;"><h3><?php _e('Payment Email'); ?></h3></div></td></tr>
				<tr>
					<th scope="row"><?php _e('Subject'); ?></th>
					<td>
						<input type="text" size="57" name="booking_options[approved_email_subject]" value="<?php echo $options['approved_email_subject']; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Email'); ?></th>
					<td>
						<textarea name="booking_options[approved_email_body]" rows="8" cols="59" type='textarea'><?php echo $options['approved_email_body']; ?></textarea><br />
						<span style="color:#666666;margin-left:2px;"><?php _e('Sent to customer when the booking status is set to "Approved".'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2"><div style="margin-top:10px;"><h3><?php _e('Cancellation Email'); ?></h3></div></td></tr>
				<tr>
					<th scope="row"><?php _e('Subject'); ?></th>
					<td>
						<input type="text" size="57" name="booking_options[cancelled_email_subject]" value="<?php echo $options['cancelled_email_subject']; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Email'); ?></th>
					<td>
						<textarea name="booking_options[cancelled_email_body]" rows="8" cols="59" type='textarea'><?php echo $options['cancelled_email_body']; ?></textarea><br />
						<span style="color:#666666;margin-left:2px;"><?php _e('Sent to customer when the booking status is set to "Cancelled".'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2"><div style="margin-top:10px;"><h3><?php _e('Terms and Conditions'); ?></h3></div></td></tr>
				<tr>
					<th scope="row"><?php _e('Text'); ?></th>
					<td>
						<textarea name="booking_options[terms_conditions]" rows="8" cols="59" type='textarea'><?php echo $options['terms_conditions']; ?></textarea><br />
						<span style="color:#666666;margin-left:2px;"><?php _e('Customer needs to approve them before booking is successfully completed.'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2"><div style="margin-top:10px;"></div></td></tr>
				<tr valign="top" style="border-top:#dddddd 1px solid;">
					<th scope="row"><?php _e('Restore Default Options'); ?></th>
					<td>
						<label><input name="booking_options[chk_default_options_db]" type="checkbox" value="1" <?php if (isset($options['chk_default_options_db'])) { checked('1', $options['chk_default_options_db']); } ?> /> <?php _e('Restore default settings'); ?></label>
					</td>
				</tr>
			</table>
			
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
			</p>
			
		</form>

	</div>
<?php
}

/* Sanitize and validate input. Accepts an array, return a sanitized array. */
/*------------------------------------------------------------------*/
function booking_validate_options($input) {
	 // strip html from textboxes
	$input['textarea_one'] =  wp_filter_nohtml_kses($input['textarea_one']); // Sanitize textarea input (strip html tags, and escape characters)
	$input['txt_one'] =  wp_filter_nohtml_kses($input['txt_one']); // Sanitize textbox input (strip html tags, and escape characters)
	return $input;
}
