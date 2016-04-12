<div class="step_wrapper hidden" data-step="4">
	
	<form action="#" method="post" id="check_form">
	
	<div class="checkout_form_wrapper row">
	<div class="left_check span6">
		<fieldset>
			<legend><?php _e('Your Personal Information'); ?></legend>
			<table class="checkout_table">
				<tr>
					<th><label for="customer_title"><?php _e('Customer Title'); ?><span class="req">*</span></label></th>
					<td>
						<select id="customer_title" name="customer_title" class="validate">
							<option value="mr"><?php _e('Mr'); ?></option>
							<option value="mrs"><?php _e('Mrs'); ?></option>
							<option value="miss"><?php _e('Miss'); ?></option>
							<option value="dr"><?php _e('Dr'); ?></option>
							<option value="prof"><?php _e('Prof'); ?></option>
							<option value="rev"><?php _e('Rev'); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="first_name"><?php _e('First Name'); ?><span class="req">*</span></label></th>
					<td><input type="text" id="first_name" name="first_name" class="validate" /></td>
				</tr>
				<tr>
					<th><label for="last_name"><?php _e('Last Name'); ?><span class="req">*</span></label></th>
					<td><input type="text" id="last_name" name="last_name" class="validate" /></td>
				</tr>
				<tr>
					<th><label for="email"><?php _e('Email'); ?><span class="req">*</span></label></th>
					<td><input type="text" id="email" name="email" class="validate" /></td>
				</tr>
				<tr>
					<th><label for="phone"><?php _e('Phone'); ?><span class="req">*</span></label></th>
					<td><input type="text" id="phone" name="phone" class="validate" /></td>
				</tr>
				<tr>
					<th><label for="contact_option"><?php _e('Contact Option'); ?><span class="req">*</span></label></th>
					<td>
						<select id="contact_option" name="contact_option" class="validate">
							<option value="email"><?php _e('Email'); ?></option>
							<option value="sms"><?php _e('Phone (SMS)'); ?></option>
							<option value="call"><?php _e('Phone (Call)'); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="countries"><?php _e('Country From'); ?><span class="req">*</span></label></th>
					<td>
						<select id="countries" name="countries" class="validate">
<?php
							$countries = bizz_country_list();
							foreach ( $countries as $country) {
								echo '<option value="'.$country['value'].'">'.$country['name'].'</option>';
							}
?>
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="state_or_province"><?php _e('State/Province'); ?></label></th>
					<td><input type="text" id="state_or_province" name="state_or_province" /></td>
				</tr>
				<tr>
					<th><label for="postcode"><?php _e('Postcode/ZIP'); ?><span class="req">*</span></label></th>
					<td><input type="text" id="postcode" name="postcode" class="validate" /></td>
				</tr>
				<tr>
					<th><label for="address"><?php _e('Address'); ?><span class="req">*</span></label></th>
					<td><input type="text" id="address" name="address" class="validate" /></td>
				</tr>
				<tr>
					<th><label for="flight"><?php _e('Flight Number (like BA2244)'); ?></label></th>
					<td><input type="text" id="flight" name="flight" /></td>
				</tr>
				<tr>
					<th><label for="comms"><?php _e('Comments/Questions'); ?></label></th>
					<td><textarea rows="6" id="comms" name="comms"></textarea></td>
				</tr>
<?php 
				$opt_b = get_option('booking_options'); 
				if ( isset($opt_b['terms_conditions']) && $opt_b['terms_conditions'] != '' ) {
?>
				<tr>
					<th><label for="terms"><?php _e('Terms/Conditions'); ?></label></th>
					<td><input type="checkbox" id="terms" name="terms" checked="checked" value="1" class="validate" rev="<?php _e('You have to agree to the Booking Conditions'); ?>">&nbsp;&nbsp;<?php _e('I agree to the Terms/Conditions.'); ?></td>
				</tr>
				<tr>
					<th></th>
					<td><a href="#" class="toggled"><?php _e('View the terms and conditions'); ?></a><div class="car_details_tooltip"><?php echo $opt_b['terms_conditions']; ?></div></td>
				</tr>
<?php 
				} 
?>
			</table>
		</fieldset>
		<div class="ar">
			<div class="loading"><!----></div>
			<input class="btn btn-success" type="submit" value="<?php _e('Checkout'); ?>" id="submit_checkout" />
		</div>
	</div>
	<div class="right_check span5">
		<fieldset>
			<legend><?php _e('Location / Time'); ?><a href="#" class="chng" data-rel="1"><?php _e('Change'); ?></a></legend>
			<table class="checkout_table">
			<tbody>
				<tr>
					<th><?php _e('Pickup location'); ?></th>
					<td id="pickup_location"></td>
				</tr>
				<tr>
					<th><?php _e('Pickup date'); ?></th>
					<td id="pickup_date"></td>
				</tr>
				<tr>
					<th><?php _e('Return location'); ?></th>
					<td id="return_location"></td>
				</tr>
				<tr>
					<th><?php _e('Return date'); ?></th>
					<td id="return_date"></td>
				</tr>
				<tr>
					<th><?php _e('Days'); ?></th>
					<td id="days_details"></td>
				</tr>
			</tbody>
			</table>
		</fieldset>
		<fieldset>
			<legend><?php _e('Selected Car'); ?><a href="#" class="chng" data-rel="2"><?php _e('Change'); ?></a></legend>
			<table class="checkout_table">
			<tbody>
				<tr>
					<th><?php _e('Car image'); ?></th>
					<td><img class="car_image" src="<?php echo get_template_directory_uri() . '/lib_theme/images/no-img.jpg'; ?>" width="75" /></td>
				</tr>
				<tr>
					<th><?php _e('Car name'); ?></th>
					<td id="car_name"></td>
				</tr>
			</tbody>
			</table>
		</fieldset>
		<fieldset>
			<legend><?php _e('Selected Extras'); ?><a href="#" class="chng" data-rel="3"><?php _e('Change'); ?></a></legend>
			<table class="checkout_table">
			<tbody class="selected_extras">
				<tr class="dummy">
					<th class="extra_name"></th>
					<td class="extra_cost"></td>
				</tr>
			</tbody>
			</table>
		</fieldset>
		<fieldset>
			<legend><?php _e('Payment'); ?></legend>
			<table class="checkout_table">
			<tbody>
				<!--
				<tr>
					<th><label for="payment_method"><?php _e('Payment Method:'); ?></label></th>
					<td>
						<select id="payment_method" name="payment_method">
							<option value="cash">Cash</option>
							<option value="paypal">PayPal</option>
						</select>
					</td>
				</tr>
				-->
				<tr>
					<th><?php _e('Car'); ?></th>
					<td id="car_pay"></td>
				</tr>
				<tr>
					<th><?php _e('Extras'); ?></th>
					<td id="extras_pay"></td>
				</tr>
				<tr>
					<th><?php _e('Tax'); ?></th>
					<td id="tax_pay"></td>
				</tr>
				<tr>
					<th><?php _e('Total'); ?></th>
					<td id="total_pay"></td>
				</tr>
				<tr>
					<th><?php _e('Deposit'); ?></th>
					<td id="deposit_pay"></td>
				</tr>
			</tbody>
			</table>
		</fieldset>
	</div>
	</div>
	
	</form>
</div>