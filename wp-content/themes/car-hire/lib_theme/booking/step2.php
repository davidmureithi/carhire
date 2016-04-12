<div class="step_wrapper hidden" data-step="2">
	<fieldset>
		<div class="filter first">
		<div class="filterin">
			<label for="car_type"><?php _e('Type:'); ?></label>
			<select id="car_type">
				<option value="-1"><?php _e('Any'); ?></option>
			</select>
		</div>
		</div>
		<div class="filter second">
		<div class="filterin">
			<label for="car_transmission"><?php _e('Transmission:'); ?></label>
			<select id="car_transmission">
				<option value="-1"><?php _e('Any'); ?></option>
			</select>
		</div>
		</div>
	</fieldset>
	<div class="list_wrapper">
		<ul id="car_list" class="clist">
			<li class="clearfix dummy">
				<div class="left data_wrapper">
					<img class="car_image left" src="<?php echo get_template_directory_uri() . '/lib_theme/images/no-img.jpg'; ?>" width="100" />
					<div class="details left">
						<h2 class="car_name"></h2>
						<span class="avail yes label label-success hidden"><?php _e('Available'); ?></span>
						<span class="avail no label label-important hidden"><?php _e('Not Available'); ?></span>
						<input type="hidden" class="car_id" value="" />
						<input type="hidden" class="car_type" value="" />
						<input type="hidden" class="car_transmission" value="" />
						<input type="hidden" class="car_cost" value="" />
						<ul class="car_properties">
							<li class="left seats"><span class="eq_value"></span></li>
							<li class="left doors"><span class="eq_value"></span></li>
							<li class="left transmission"><span class="eq_value"></span></li>
						</ul>
						<div class="fix"></div>
						<a href="#" class="toggled car_details"><?php _e('Details'); ?></a>
						<div class="car_details_tooltip"></div>
					</div>
				</div>
				<div class="left price_wrapper">
					<span class="car_price"></span>
					<input class="btn btn-success button_select_car right" type="button" value="<?php _e('Book Now'); ?>" />
					<div class="clearfix"></div>
					<div class="car_availability right"></div> 
				</div>
			</li>
		</ul>
	</div>
</div>