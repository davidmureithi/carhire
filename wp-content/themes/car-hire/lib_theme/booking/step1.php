<div class="step_wrapper" data-step="1">
	<form action="#" method="post" id="book_form" class="form-inline">
	<table class="pick_table">
		<tbody>
			<tr>
				<td class="lbl"><?php _e('Pickup Location'); ?><span class="req">*</span></td>
				<td colspan="2">
				<?php
				$sposts = get_posts( array( 'post_type' => 'bizz_locations', 'post_status' => 'publish', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => -1 ) );
				echo '<select name="location_pickup" class="location" required="required">' . "\n";
				echo '<option value="">' . __('-- select pick-up location --','bizzthemes') . '</option>' . "\n";
				foreach ($sposts as $key => $post) {
					$address = get_post_meta($post->ID, 'bizzthemes_location_address', true);
					echo '<option value="' . $post->post_name . '">' . $post->post_title . ', ' . $address . '</option>' . "\n";
				}
				echo '</select>' . "\n";
				?>
				</td>
			</tr>
			<tr>
				<td class="lbl"><?php _e('Pickup Date'); ?><span class="req">*</span></td>
				<td><input class="booking_date" type="text" name="date_pickup" size="10" required="required" /></td>
				<td>
					<select class="time_field" name="time_pickup" required="required">
						<option></option>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="return_table">
		<tbody>
			<tr>
				<td class="lbl"><?php _e('Return Location'); ?><span class="req">*</span></td>
				<td colspan="2">
				<?php
				$sposts = get_posts( array( 'post_type' => 'bizz_locations', 'post_status' => 'publish', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => -1 ) );
				echo '<select name="location_return" class="location" required="required">' . "\n";
				echo '<option value="">' . __('-- select return location --','bizzthemes') . '</option>' . "\n";
				foreach ($sposts as $key => $post) {
					$address = get_post_meta($post->ID, 'bizzthemes_location_address', true);
					echo '	<option value="' . $post->post_name . '">' . $post->post_title . ', ' . $address . '</option>' . "\n";
				}
				echo '</select>' . "\n";
				?>
				</td>
			</tr>
			<tr>
				<td class="lbl"><?php _e('Return Date'); ?><span class="req">*</span></td>
				<td><input class="booking_date" type="text" name="date_return" size="10" required="required" /></td>
				<td>
					<select class="time_field" name="time_return" required="required">
						<option></option>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="sbmt ar">
		<input type="text" name="is_spam" id="is_spam" class="spamprevent" />
		<div class="loading"><!----></div>
		<button class="btn btn-success" name="submit" type="submit" value="<?php _e('Submit'); ?>"><?php _e('Submit'); ?></button>&nbsp;&nbsp;&nbsp;
		<button class="btn" name="reset" type="reset" value="<?php _e('Reset Form'); ?>"><?php _e('Reset Form'); ?></button>
	</div>
	</form>
</div>