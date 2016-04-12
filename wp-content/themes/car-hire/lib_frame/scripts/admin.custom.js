// start selectors
jQuery.noConflict();
jQuery(document).ready(function($){
	$('.date_field').datepicker({ firstDay: 1, dateFormat: 'yy-mm-dd' });
	$('form#post').attr('enctype','multipart/form-data');
    $('form#post').attr('encoding','multipart/form-data');
});