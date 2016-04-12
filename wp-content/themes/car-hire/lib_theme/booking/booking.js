/*
*	@name							Valid8
*	@descripton						An input field validation plugin for Jquery
*	@version						1.3
*	@requires						Jquery 1.3.2+
*
*	@author							Jan Jarfalk
*	@author-email					jan.jarfalk@unwrongest.com
*	@author-website					http://www.unwrongest.com
*
*	@licens							MIT License - http://www.opensource.org/licenses/mit-license.php
*/
(function($){$.fn.extend({valid8:function(b){return this.each(function(){$(this).data('valid',false);var a={regularExpressions:[],ajaxRequests:[],jsFunctions:[],validationEvents:['keyup','blur'],validationFrequency:1000,values:null,defaultErrorMessage:'Required'};if(typeof b=='string')a.defaultErrorMessage=b;if(this.type=='checkbox'){a.regularExpressions=[{expression:/^true$/,errormessage:a.defaultErrorMessage}];a.validationEvents=['click']}else a.regularExpressions=[{expression:/^.+$/,errormessage:a.defaultErrorMessage}];$(this).data('settings',$.extend(a,b));initialize(this)})},isValid:function(){var a=true;this.each(function(){validate(this);if($(this).data('valid')==false)a=false});return a}});function initializeDataObject(a){$(a).data('loadings',new Array());$(a).data('errors',new Array());$(a).data('valids',new Array());$(a).data('keypressTimer',null)}function initialize(a){initializeDataObject(a);activate(a)};function activate(b){var c=$(b).data('settings').validationEvents;if(typeof c=='string')$(b)[c](function(e){handleEvent(e,b)});else{$.each(c,function(i,a){$(b)[a](function(e){handleEvent(e,b)})})}};function validate(a){initializeDataObject(a);var b;if(a.type=='checkbox')b=a.checked.toString();else b=a.value;regexpValidation(b.replace(/^[ \t]+|[ \t]+$/,''),a)};function regexpValidation(b,c){$.each($(c).data('settings').regularExpressions,function(i,a){if(!a.expression.test(b))$(c).data('errors')[$(c).data('errors').length]=a.errormessage;else if(a.validmessage)$(c).data('valids')[$(c).data('valids').length]=a.validmessage});if($(c).data('errors').length>0)onEvent(c,'error',false);else if($(c).data('settings').jsFunctions.length>0){functionValidation(b,c)}else if($(c).data('settings').ajaxRequests.length>0){fileValidation(b,c)}else{onEvent(c,'valid',true)}};function functionValidation(c,d){$.each($(d).data('settings').jsFunctions,function(i,a){var v;if(a.values){if(typeof a.values=='function')v=a.values()}var b=v||c;handleLoading(d,a);if(a['function'](b).valid)$(d).data('valids')[$(d).data('valids').length]=a['function'](b).message;else $(d).data('errors')[$(d).data('errors').length]=a['function'](b).message});if($(d).data('errors').length>0)onEvent(d,'error',false);else if($(d).data('settings').ajaxRequests.length>0){fileValidation(c,d)}else{onEvent(d,'valid',true)}};function fileValidation(e,f){$.each($(f).data('settings').ajaxRequests,function(i,c){var v;if(c.values){if(typeof c.values=='function')v=c.values()}var d=v||{value:e};handleLoading(f,c);$.post(c.url,d,function(a,b){if(a.valid){$(f).data('valids')[$(f).data('valids').length]=a.message||c.validmessage||""}else{$(f).data('errors')[$(f).data('errors').length]=a.message||c.errormessage||""}if($(f).data('errors').length>0)onEvent(f,'error',false);else{onEvent(f,'valid',true)}},"json")})};function handleEvent(e,a){if(e.keyCode&&$(a).attr('value').length>0){clearTimeout($(a).data('keypressTimer'));$(a).data('keypressTimer',setTimeout(function(){validate(a)},$(a).data('settings').validationFrequency))}else if(e.keyCode&&$(a).attr('value').length<=0)return false;else{validate(a)}};function handleLoading(a,b){if(b.loadingmessage){$(a).data('loadings')[$(a).data('loadings').length]=b.loadingmessage;onEvent(a,'loading',false)}};function onEvent(a,b,c){var d=b.substring(0,1).toUpperCase()+b.substring(1,b.length),messages=$(a).data(b+'s');$(a).data(b,c);setStatus(a,b);setParentClass(a,b);setMessage(messages,a);$(a).trigger(b,[messages,a,b])}function setParentClass(a,b){var c=$(a).parent();c[0].className=(c[0].className.replace(/(^\s|(\s*(loading|error|valid)))/g,'')+' '+b).replace(/^\s/,'')}function setMessage(a,b){var c=$(b).parent();var d=b.id+"ValidationMessage";var e='validationMessage';if(!$('#'+d).length>0){c.append('<span id="'+d+'" class="'+e+'"></span>')}$('#'+d).html("");$('#'+d).text(a[0])};function setStatus(a,b){if(b=='valid'){$(a).data('valid',true)}else if(b=='error'){$(a).data('valid',false)}}})(jQuery);

/*
 *	Booking pop-up dialog
 */
jQuery.noConflict();
jQuery(document).ready(function($) {
	
	// AJAX on initial load
	var ajax_url = bizz_localize.ajaxurl,
		data = { action: 'booking_filters' },
		cookie = { action: 'booking_cookie' };
		
	// filters
	$.getJSON(ajax_url, data, function(response) {
		var filters = response;

		// type
		$.each(filters.type, function() {
			var filter = this;
			$('#car_type').append(
				$('<option></option>').val(filter.value).html(filter.name)
			);
		});

		// transmission
		$.each(filters.transmission, function() {
			var filter = this;
			$('#car_transmission').append(
				$('<option></option>').val(filter.value).html(filter.name)
			);
		});

	});

	// cookie (if available)
	$.getJSON(ajax_url, cookie, function(response) {
		var cookie = response,
			pickup_f = jQuery("table.pick_table"),
			return_f = jQuery("table.return_table");
						
		if ( cookie != 'nocookie') {
			
			// pickup
			pickup_f.find(".location option[value='" + cookie.location_of_pickup +"']").attr("selected", "selected") ;
			pickup_f.find(".booking_date").val(cookie.date_of_pickup);
			pickup_f.find(".time_field")
				.append($("<option></option>")
				.attr("value", cookie.hour_of_pickup)
				.attr("selected", "selected")
				.text(cookie.hour_of_pickup));
			// return
			return_f.find(".location option[value='" + cookie.location_of_return +"']").attr("selected", "selected") ;
			return_f.find(".booking_date").val(cookie.date_of_return);
			return_f.find(".time_field")
				.append($("<option></option>")
				.attr("value", cookie.hour_of_return)
				.attr("selected", "selected")
				.text(cookie.hour_of_return));
			
			// alert(cookie.location_of_return);
		
		}
		
		return false;
		
	});
	
	// Tab links
	$("a.tablink, a.chng").live('click', function (e) {
		e.preventDefault();
		
		var thistab = this,
			current_rel = thistab.getAttribute("data-rel");
			
		if ( $(this).hasClass('disabled') )
			return;
			
		$('html, body').animate({scrollTop:$('#booktop').position().top}, 'slow');
		
		if (current_rel == '1') {
			$(".step_wrapper[data-step='1']").show().removeClass("hidden").fadeIn("slow");
			$(".step_wrapper[data-step='2']").hide().addClass("hidden");
			$(".step_wrapper[data-step='3']").hide().addClass("hidden");
			$(".step_wrapper[data-step='4']").hide().addClass("hidden");
			$(".steps_tabs li").removeClass("selected");
			$(".step1_tab").addClass("selected");
		}
		else if (current_rel == '2') {
			$(".step_wrapper[data-step='1']").hide().addClass("hidden");
			$(".step_wrapper[data-step='2']").show().removeClass("hidden").fadeIn("slow");
			$(".step_wrapper[data-step='3']").hide().addClass("hidden");
			$(".step_wrapper[data-step='4']").hide().addClass("hidden");
			$(".steps_tabs li").removeClass("selected");
			$(".step2_tab").addClass("selected");
		}
		else if (current_rel == '3') {
			$(".step_wrapper[data-step='1']").hide().addClass("hidden");
			$(".step_wrapper[data-step='2']").hide().addClass("hidden");
			$(".step_wrapper[data-step='3']").show().removeClass("hidden").fadeIn("slow");
			$(".step_wrapper[data-step='4']").hide().addClass("hidden");
			$(".steps_tabs li").removeClass("selected");
			$(".step3_tab").addClass("selected");
		}
		else if (current_rel == '4') {
			$(".step_wrapper[data-step='1']").hide().addClass("hidden");
			$(".step_wrapper[data-step='2']").hide().addClass("hidden");
			$(".step_wrapper[data-step='3']").hide().addClass("hidden");
			$(".step_wrapper[data-step='4']").show().removeClass("hidden").fadeIn("slow");
			$(".steps_tabs li").removeClass("selected");
			$(".step4_tab").addClass("selected");
		}

	});
	
	// Ajax submission | step 1
	$('form#book_form button[type=submit]').live('click', function(e) {
		e.preventDefault();
		
		// validate form
		var form = $(this).parents('form#book_form'),
			form_data = $(this).parents('form#book_form').serialize(),
			ajax_url = bizz_localize.ajaxurl,
			loading = $(this).parents('form#book_form').find('.loading'),
			messages = $(this).parents('.bookwrap').find('.messages'),
			data = {
				action: 'booking_form_action',
				data: form_data
			};
			
		// loading show
		loading.css('display', 'inline');
		
		$.post(ajax_url, data, function(response) {
			// alert(response);
			
			// loading hide
			loading.css('display', 'none');
			
			// load step 2
			if (response == "SUCCESS") {
				messages.empty();
				// $("#book_form").dialog("open");
				var params_array = {
					'date_of_pickup': form.find("input[name='date_pickup']").val(), 
					'hour_of_pickup': form.find("select[name='time_pickup'] option:selected").val(),
					'date_of_return': form.find("input[name='date_return']").val(), 
					'hour_of_return': form.find("select[name='time_return'] option:selected").val(), 
					'location_of_pickup': form.find("select[name='location_pickup'] option:selected").val(), 
					'location_of_return': form.find("select[name='location_return'] option:selected").val()
				};
												
				loadStepTwo(params_array);
			}
			// error
			else {
				messages.html('<div class="alert alert-error">' + response + '</div>');
			}
			
		});

		return false;
		
	});
	
	// Reset form
	$('form#book_form button[type=reset]').live('click', function() {
		$(this).parents('form').find(':input',':select').val('').removeAttr('checked').removeAttr('selected');
		$(this).parents('.bookwrap').find('.messages, .time_field').empty();
		$(this).parents('.bookwrap').find('.time_field').append($("<option></option>"));
		clearUserCookie();
		return false;
	});
	
	// Add date picker
	$('.booking_date').datepicker({
		firstDay: 1,
		minDate: 0,
		dateFormat: 'yy-mm-dd',
		onSelect: function(dateText, inst) {
									
			var newDate = $(this).datepicker('getDate');
			var startDate = new Date(newDate);
			var selDay = startDate.getDay();
			var timeSelect = $(this).parents('tr').find('.time_field');
			var locationSelect = $(this).parents('table').find('.location').val();
						
			// ajaxed time selector								
			var ajax_url = bizz_localize.ajaxurl,
				data = {
					action: 'booking_time_action',
					dature: dateText,
					day: selDay,
					date: newDate,
					location: locationSelect
					
				};
			
			$.post(ajax_url, data, function(response) {
				// alert("Data Loaded: " + response);
				
				if (response == "EMPTY") {
					alert(bizzlang.book_empty);
				}
				else if (response == "CLOSED") {
					alert(bizzlang.book_closed);
					timeSelect.find("option").remove();
				}
				else if (response == "PAST") {
					alert(bizzlang.book_past);
					timeSelect.find("option").remove();
				}
				else {
					timeSelect.find("option").remove();
					timeSelect.append(response);
				}
				
			});

		}
	});
	
	// Reset date and time on location change
	$("select.location").live('change', function() {
		$(this).parents('table').find('.booking_date').val('');
		$(this).parents('table').find('.time_field option').empty();
		$(this).parents('table').find('.time_field option').append($("<option></option>"));
		return false;
	});
	
	// Car details
	$("a.toggled").live('click', function (e) {
		e.preventDefault();
		var toggled = $(this).data('toggled');
		$(this).data('toggled', !toggled);
		if (!toggled)
			$(this).next("div").slideDown('fast');
		else
			$(this).next("div").slideUp('fast');
	});
	
	// Filter cars
	$("#car_type").live('change',function(){
		filterCarList();
	});
	$("#car_transmission").live('change',function(){
		filterCarList();
	});
	$(".car_extras_check").live("click", function() {
		fillCarExtrasArray();
	});
	
	// load step 3
	$(".button_select_car").live("click", function(e) {
		e.preventDefault();
		var $selected_car_li = $(this).parent("div").parent("li"),
			params_array = {
			'car_id': $selected_car_li.find(".car_id").val(), 
			'car_cost': $selected_car_li.find(".car_cost").val()
		};
		
		$("#car_list > li").removeClass("selected");
		$selected_car_li.addClass('selected');
						
		loadStepThree(params_array);
		
		return false;
	});
	
	// load step 4
	$("#submit_car_extras").live("click", function(e) {
		e.preventDefault();
		var params_array = {
			'car_extras': $("#selected_extras").val()
		};
						
		loadStepFour(params_array);
	});
	
	// validate step 4
	$('.validate').valid8(bizzlang.book_required);
	$('#email').valid8({
		regularExpressions: [
			{expression: /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(aero|asia|biz|cat|com|coop|edu|gov|info|int|jobs|mil|mobi|museum|name|net|org|pro|tel|travel.ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|ee|eg|er|es|et|eu|fi|fj|fk|fm|.fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|.il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)\b$/, errormessage: bizzlang.email_required}
		]
	});
	
	// load step 5
	$("#submit_checkout").live("click", function(e) {
		e.preventDefault();
		$('.validate').isValid();
		var params_array = $('form#check_form').serializeArray();
		
		// all fine?
		if ( $('.validate').isValid() )
			loadStepFive(params_array);
	});
	
});

function filterCarList() {
	var selected_type = jQuery("#car_type").val();
	var selected_transmission = jQuery("#car_transmission").val();
	
	
	// show | hide
	jQuery("#car_list > li:not(.dummy)").removeClass("hidden");
	jQuery("#car_list > li:not(.dummy)").each(function() {
		var jQueryli_car_type = jQuery(this).find(".car_type").val();
		var jQueryli_car_transmission = jQuery(this).find(".car_transmission").val();
		
		if ((selected_type == "-1") && (selected_transmission == "-1")) {
		
			jQuery(this).removeClass("hidden");
			
		}
		else if ((selected_type == "-1") || (selected_transmission == "-1")) {
			
			if (selected_type == "-1") {
			
				if (jQueryli_car_transmission == selected_transmission)
					jQuery(this).removeClass("hidden");
				else
					jQuery(this).addClass("hidden");

			}
			
			if (selected_transmission == "-1") {
			
				if (jQueryli_car_type == selected_type)
					jQuery(this).removeClass("hidden");
				else
					jQuery(this).addClass("hidden");

			}
			
		}
		else {
		
			if ((jQueryli_car_transmission == selected_transmission) && (jQueryli_car_type == selected_type))
				jQuery(this).removeClass("hidden");
			else
				jQuery(this).addClass("hidden");
		}

	});
	
	// no cars?
	if (jQuery("#car_list > li:not(.hidden)").size() == 0)
		jQuery("#car_list").append("<li class=\"nocars\">"+bizzlang.book_nocars+"</li>");
	else
		jQuery("#car_list > li.nocars").remove();
		
	// last list
	var lastLisNoX = jQuery('ul.clist').map(function() {
		return jQuery(this).children('li:not(.hidden)').get(-1);
	});
	jQuery(".clist > li").css('border', '');
	lastLisNoX.css( 'border', 'none' )	
	
}

function fillCarExtrasArray() {
	var selected_extras = [];
	
	jQuery(".car_extras_check").each(function() {
		if (jQuery(this).is(":checked"))
			selected_extras.push([
				jQuery(this).parent("li").find(".car_extras_slug").val(), 
				jQuery(this).parent("li").find(".extras_name").html(), 
				jQuery(this).parent("li").find(".extras_price").val()
			]);
	});
	
	jQuery("#selected_extras").val(selected_extras.join("|"));
}

function clearUserCookie() { // clears the user selection entirely
	var ajax_url = bizz_localize.ajaxurl,
		data = {
			action: 'validate_booking',
			step: 'dc',
			params: ''
		};
	jQuery.getJSON(ajax_url, data, function(response) {
		// no response
	}).error(function(response){});
}

function loadStepTwo(params_array) { // Choose car
	jQuery('html, body').animate({scrollTop:jQuery('#booktop').position().top}, 'slow');
	jQuery(".step_wrapper").hide();
	jQuery(".steps_tabs li").removeClass("selected");
	jQuery(".loading_wrapper").show();
	jQuery("a.tablink").addClass("disabled");
	jQuery("a.tablink[data-rel=1],[data-rel=2]").removeClass("disabled");
	
	// .attr("class").match(/span_([\d]+)/)
	
	// widen booking container	
	jQuery(".widget_booking").parents(".row").children("div[class*='span']").animate('slow', function() {
		var span_class = jQuery(this).attr("class").match(/span([\d])/),
			span_num = parseInt(span_class[1],10);
		jQuery(this).removeClass("span"+span_num).addClass("span12").fadeIn("fast");
	});
	
	// reset car filters
	jQuery('#car_type, #car_transmission').find("option:first").attr("selected", true);

	// prevent duplicates
	jQuery(".clist > li:not(.dummy)").remove();
	jQuery(".clist > li.dummy").removeClass("hidden");
	
	// ajaxed time selector								
	var ajax_url = bizz_localize.ajaxurl,
		data = {
			action: 'validate_booking',
			step: '2',
			params: params_array
		};
	
	jQuery.getJSON(ajax_url, data, function(response) {
		var json_cars = response;
		
		jQuery(".loading_wrapper").fadeOut('fast', function() {
			jQuery(".step_wrapper[data-step=2]").hide().removeClass("hidden").fadeIn("slow");
		});
		jQuery(".step2_tab").addClass("selected");
		
		// remove all but dummy
		jQuery("#car_list > li:not(.dummy)").remove();
		
		// loop cars
		if ( !jQuery.isEmptyObject(json_cars.cars) ) { // empty?
			jQuery.each(json_cars.cars, function() {
				var $car = this;
				var li_element = jQuery("ul#car_list li.dummy:first").clone();
				var li_element_equipment = li_element.find("ul#car_properties_"+$car.id);
				li_element.attr("id", "li_car_"+$car.id);
				li_element.removeClass("dummy");
				li_element.removeClass("hidden");
				li_element.find("ul.car_properties").attr("id", "car_properties_"+$car.id);
				li_element.find(".car_image").attr("src", $car.picture_src);
				li_element.find(".car_name").html($car.name);
				li_element.find(".car_id").val($car.id);
				li_element.find(".car_type").val($car.type);
				li_element.find(".car_transmission").val($car.equipment.transmission);
				li_element.find(".car_details_tooltip").html($car.description);
				li_element.find(".car_cost").val($car.cost);
				li_element.find(".car_price").html($car.currency+$car.cost);
				
				// availability
				if ( $car.avail_date != 'ok' ) {
					li_element.find(".car_availability").html($car.avail_date);
					li_element.find(".button_select_car").remove();
					li_element.find(".avail.no").removeClass('hidden');
				} else if ( $car.avail_location != 'ok' ) {
					li_element.find(".car_availability").html($car.avail_location);
					li_element.find(".button_select_car").remove();
					li_element.find(".avail.no").removeClass('hidden');
				} else {
					li_element.find(".avail.yes").removeClass('hidden');
				}
				
				// loop equipment
				jQuery.each($car.equipment, function() {
					var $equipment = this;
					var li_element_equipment = li_element.find("ul#car_properties_"+$car.id);				
					li_element_equipment.find("li.seats .eq_value").html($car.equipment.seats);
					li_element_equipment.find("li.doors .eq_value").html($car.equipment.doors);
					li_element_equipment.find("li.transmission .eq_value").html($car.equipment.transmission);
					jQuery("#car_properties_"+$car.id).append(jQuery(li_element_equipment));
				});			
				
				jQuery("#car_list").append(jQuery(li_element));
				
			});
		}
		
		// hide dummy
		jQuery("#car_list li.dummy").addClass("hidden");
		
		// last list
		var lastLisNoX = jQuery('ul.clist').map(function() {
			return jQuery(this).children('li:not(.hidden)').get(-1);
		});
		jQuery(".clist > li").css('border', '');
		lastLisNoX.css( 'border', 'none' );
		
	}).error(function(response){});

}

function loadStepThree(params_array) { // Choose extras
	jQuery('html, body').animate({scrollTop:jQuery('#booktop').position().top}, 'slow');
	jQuery("#selected_extras").val("");
	jQuery(".step_wrapper").hide();
	jQuery(".steps_tabs li").removeClass("selected");
	jQuery(".loading_wrapper").show();
	jQuery("a.tablink").addClass("disabled");
	jQuery("a.tablink[data-rel=1],[data-rel=2],[data-rel=3]").removeClass("disabled");
		
	// ajaxed time selector								
	var ajax_url = bizz_localize.ajaxurl,
		data = {
			action: 'validate_booking',
			step: '3',
			params: params_array
		};
	
	jQuery.getJSON(ajax_url, data, function(response) {
		var json_car_extras = response;
		
		jQuery(".loading_wrapper").fadeOut('fast', function() {
			jQuery(".step_wrapper[data-step=3]").hide().removeClass("hidden").fadeIn("slow");
		});
		jQuery(".step3_tab").addClass("selected");
		
		// remove all but dummy
		jQuery("#extras_list > li:not(.dummy)").remove();

		if ( !jQuery.isEmptyObject(json_car_extras.car_extras) ) { // empty?
			jQuery.each(json_car_extras.car_extras, function() {
				var $car_extras = this;
				var li_element = jQuery("ul#extras_list li.dummy:first").clone();
				li_element.attr("id", "li_car_extras_"+$car_extras.id);
				li_element.removeClass("dummy");
				li_element.removeClass("hidden");
				li_element.find(".extras_image").attr("src", $car_extras.picture_src);
				li_element.find(".extras_name").html($car_extras.name);
				li_element.find(".car_extras_slug").val($car_extras.slug);
				
				li_element.find(".extras_details").html($car_extras.description);
				li_element.find(".extras_cost").html($car_extras.currency+$car_extras.cost);
				li_element.find(".extras_price").val($car_extras.cost);
				jQuery("#extras_list").append(jQuery(li_element));			
			});
		}
		else
			jQuery("#extras_list").append("<li class=\"noextras\">"+bizzlang.book_noextras+"</li>");

				
		// hide dummy
		jQuery("#extras_list li.dummy").addClass("hidden");
		
		// last list
		var lastLisNoX = jQuery('ul.clist').map(function() {
			return jQuery(this).children('li:not(.dummy)').get(-1);
		});
		jQuery(".clist > li").css('border', '');
		lastLisNoX.css( 'border', 'none' );
		
	}).error(function(response){});

}

function loadStepFour(params_array) { // Personal Data
	jQuery('html, body').animate({scrollTop:jQuery('#booktop').position().top}, 'slow');
	jQuery(".step_wrapper[data-step=3]").hide();
	jQuery(".loading_wrapper").show();
	jQuery(".steps_tabs li").removeClass("selected");
	jQuery("a.tablink[data-rel=1],[data-rel=2],[data-rel=3],[data-rel=4]").removeClass("disabled");
	
	// ajaxed time selector								
	var ajax_url = bizz_localize.ajaxurl,
		data = {
			action: 'validate_booking',
			step: '4',
			params: params_array
		};
	
	jQuery.getJSON(ajax_url, data, function(response) {
		var cookie_data = response;
		
		jQuery(".loading_wrapper").fadeOut('fast', function() {
			jQuery(".step_wrapper[data-step=4]").hide().removeClass("hidden").fadeIn("slow");
		});
		jQuery(".step4_tab").addClass("selected");
		
		// remove all but dummy
		jQuery(".selected_extras > tr:not(.dummy)").remove();
		
		// extras?
		if ( !jQuery.isEmptyObject(cookie_data.car_extras) ) { // empty?
			jQuery.each(cookie_data.car_extras, function() {
				var $car_extras = this;
				var li_element = jQuery(".selected_extras tr:first").clone();
				li_element.removeClass("dummy");
				li_element.removeClass("hidden");
				li_element.find(".extra_name").html($car_extras[1]);
				li_element.find(".extra_cost").html(cookie_data.currency+$car_extras[2]);
				jQuery(".selected_extras").append(jQuery(li_element));			
			});
		}
		else
			jQuery(".selected_extras").append("<tr class=\"noextras\"><th></th><td>"+bizzlang.book_noextra+"</td></tr>");

		
		// hide dummy
		jQuery(".selected_extras tr.dummy").addClass("hidden");
		
		// data
		jQuery("#pickup_location").html(cookie_data.location_of_pickup_name);
		jQuery("#pickup_date").html(cookie_data.date_of_pickup+", "+cookie_data.hour_of_pickup);
		jQuery("#return_location").html(cookie_data.location_of_return_name);
		jQuery("#return_date").html(cookie_data.date_of_return+", "+cookie_data.hour_of_return);
		jQuery("#days_details").html(cookie_data.count_days);
		
		jQuery("#car_name").html(cookie_data.car_name);
		jQuery(".checkout_table .car_image").attr("src", cookie_data.car_image);
		
		jQuery("#car_pay").html(cookie_data.currency+cookie_data.car_total_payment.car_total);
		jQuery("#extras_pay").html(cookie_data.currency+cookie_data.car_total_payment.extras_total);
		jQuery("#deposit_pay").html(cookie_data.currency+cookie_data.car_total_payment.deposit);
		jQuery("#tax_pay").html(cookie_data.currency+cookie_data.car_total_payment.tax_total);
		jQuery("#total_pay").html(cookie_data.currency+cookie_data.car_total_payment.total);
		
	}).error(function(response){});

}

function loadStepFive(params_array) { // Checkout
	
	// ajaxed time selector								
	var ajax_url = bizz_localize.ajaxurl,
		loading = jQuery('form#check_form').find('.loading'),
		messages = jQuery('.bookwrap').find('.messages'),
		delay = 5000, //Your delay in milliseconds
		data = {
			action: 'validate_booking',
			step: '5',
			params: params_array
		};
		
	// loading show
	loading.css('display', 'inline');
	
	jQuery.getJSON(ajax_url, data, function(response) {
		// alert(response);
				
		// loading hide
		loading.css('display', 'none');
		
		if (response == "success") {
			jQuery('html, body').animate({scrollTop:jQuery('#booktop').position().top}, 'slow');
			jQuery('.steps_tabs_container, .step_wrapper').remove();
			messages.empty();
			messages.html('<div class="alert alert-success">' + bizzlang.book_success + '</div>');
			jQuery('.bookwrap').removeClass('bookwrap');
			if ( bizzlang.thankyou_page ) {
				setTimeout(function(){ 
					window.location = bizzlang.thankyou_page; 
				}, delay);
			}
		}
		
	}).error(function(response){});

}