<div class="step_wrapper hidden" data-step="3">
	<div class="extras_list_wrapper">
		<ul id="extras_list" class="clist">
			<li class="clearfix dummy">
				<input type="checkbox" class="left car_extras_check" />
				<input type="hidden" class="extras_price" value="" />
				<div class="left data_wrapper">
					<img class="extras_image left" src="<?php echo get_template_directory_uri() . '/lib_theme/images/no-img.jpg'; ?>" width="100" />
					<div class="left">
						<h2 class="extras_name"></h2>
						<input type="hidden" class="car_extras_slug" value="" />
						<div class="extras_details"></div> 
					</div>
				</div>
				<div class="left price_wrapper">
					<span class="extras_cost"></span>
					<div class="clearfix"></div>
					<div class="extras_range right"></div>
				</div>
			</li>
		</ul>
		<input type="hidden" id="selected_extras"/>
		<div class="ar">
			<input class="btn btn-success" type="button" value="<?php _e('Proceed to checkout'); ?>" id="submit_car_extras"/>
		</div>
	</div>	
</div>