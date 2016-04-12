jQuery.noConflict();
jQuery(document).ready(function($) {
		
	// Shortcode Toggle
	$(".toggle_content").hide(); 
	$("h3.toggle").toggle(function(){
		$(this).addClass("active");
	}, function () {
		$(this).removeClass("active");
	});
	$("h3.toggle").click(function(){
		$(this).next(".toggle_content").slideToggle();
	});
	
	// Shortcode Tabs
	$(".tabs-container ul.tabs").tabs(".panes > div");
	
	// AJAX pagination
	$('.ajax-pagination ul.lpag a').live('click', function(e){
		e.preventDefault();
		var link = $(this).attr('href'),
			content = $(this).parents('.content_area').attr('id');
			loading = $(this).parents('.pagination_area').find('.pagination_loading');
		// start loading
			loading.css({ display: 'block' });
		// load new content
		$('#'+content+'').css({ opacity: 0.8 }).load(link + ' #'+content+'', function(){ 
			$('#'+content+'').fadeIn(500);
			$('#'+content+'').css({ opacity: 1 });
			loading.css({ display: 'none' });
			// Refresh Lightbox
			if ( bizz_localize.lightbox ) {
				$("a[href*='.jpg'],a[href*='.gif'],a[href*='.png'],a[href*='.bmp'],a[href*='.swf'],a[href*='.mov'],a[rel^='prettyPhoto']").fancybox({
					'transitionIn'			:	'elastic',
					'transitionOut'			:	'elastic',
					'SpeedIn'				:	600, 
					'SpeedOut'				:	100, 
					'overlayShow'			:	true,
					'hideOnContentClick'	:	true,
					'titlePosition'  		: 	'over'
				});
			}
		});
					
	});
	
	// Lightbox
	if ( bizz_localize.lightbox ) {
		$("a[href*='.jpg'],a[href*='.gif'],a[href*='.png'],a[href*='.bmp'],a[href*='.swf'],a[href*='.mov'],a[rel^='prettyPhoto']").fancybox({
			'transitionIn'			:	'elastic',
			'transitionOut'			:	'elastic',
			'SpeedIn'				:	600, 
			'SpeedOut'				:	100, 
			'overlayShow'			:	true,
			'hideOnContentClick'	:	true,
			'titlePosition'  		: 	'over'
		});
	}
	
	// Feedback
	// Initiate looped feedback
	$(".c-form").each(function() {
	    		
		// load saved options
		var cform_id = $(this).find('input[class][class="cform-id"]').val(),
			cform_trans9 = $(this).find('input[class][class="cform-trans9"]').val(),
			cform_trans10 = $(this).find('input[class][class="cform-trans10"]').val(),
			cform_trans11 = $(this).find('input[class][class="cform-trans11"]').val();						
		
			$('form#contactForm'+cform_id).submit(function() {
				$('form#contactForm'+cform_id+' .error').remove();
				var hasError = false;
				$(this).find('.requiredField').each(function() {
					if($.trim($(this).val()) == '') {
						var labelText = $(this).prev('label').text();
						$(this).parent().append('<span class="error">'+cform_trans9+' '+labelText+'.</span>');
						$(this).addClass('inputError');
						hasError = true;
					} else if($(this).hasClass('email')) {
						var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
						if(!emailReg.test($.trim($(this).val()))) {
							var labelText = $(this).prev('label').text();
							$(this).parent().append('<span class="error">'+cform_trans10+' '+labelText+'.</span>');
							$(this).addClass('inputError');
							hasError = true;
						}
					}
				});
				if(!hasError) {
					var formInput = $(this).serialize();
					$.post($(this).attr('action'),formInput, function(data){
						$('form#contactForm'+cform_id+'').slideUp("fast", function() {				   
							$(this).before('<p class="tick">'+cform_trans11+'</p>');
						});
					});
				}
				
				return false;
				
			});
	
	});
	
});

/*
 * Superfish v1.4.8 - jQuery menu widget
 * Copyright (c) 2008 Joel Birch
 *
 * Dual licensed under the MIT and GPL licenses:
 * 	http://www.opensource.org/licenses/mit-license.php
 * 	http://www.gnu.org/licenses/gpl.html
 *
 * CHANGELOG: http://users.tpg.com.au/j_birch/plugins/superfish/changelog.txt
 */

(function($){
	$.fn.superfish = function(op){

		var sf = $.fn.superfish,
			c = sf.c,
			$arrow = $(['<span class="',c.arrowClass,'"> &#187;</span>'].join('')),
			over = function(){
				var $$ = $(this), menu = getMenu($$);
				clearTimeout(menu.sfTimer);
				$$.showSuperfishUl().siblings().hideSuperfishUl();
			},
			out = function(){
				var $$ = $(this), menu = getMenu($$), o = sf.op;
				clearTimeout(menu.sfTimer);
				menu.sfTimer=setTimeout(function(){
					o.retainPath=($.inArray($$[0],o.$path)>-1);
					$$.hideSuperfishUl();
					if (o.$path.length && $$.parents(['li.',o.hoverClass].join('')).length<1){over.call(o.$path);}
				},o.delay);	
			},
			getMenu = function($menu){
				var menu = $menu.parents(['ul.',c.menuClass,':first'].join(''))[0];
				sf.op = sf.o[menu.serial];
				return menu;
			},
			addArrow = function($a){ $a.addClass(c.anchorClass).append($arrow.clone()); };
			
		return this.each(function() {
			var s = this.serial = sf.o.length,
				o = $.extend({},sf.defaults,op);
			o.$path = $('li.'+o.pathClass,this).slice(0,o.pathLevels).each(function(){
				$(this).addClass([o.hoverClass,c.bcClass].join(' '))
					.filter('li:has(ul)').removeClass(o.pathClass);
			});
			sf.o[s] = sf.op = o;
			
			$('li:has(ul)',this)[($.fn.hoverIntent && !o.disableHI) ? 'hoverIntent' : 'hover'](over,out).each(function() {
				if (o.autoArrows) addArrow( $('>a:first-child',this) );
			}).not('.'+c.bcClass).hideSuperfishUl();
			
			var $a = $('a',this);
			$a.each(function(i){
				var $li = $a.eq(i).parents('li');
				$a.eq(i).focus(function(){over.call($li);}).blur(function(){out.call($li);});
			});
			o.onInit.call(this);
			
		}).each(function() {
			var menuClasses = [c.menuClass];
			if (sf.op.dropShadows  && !($.browser.msie && $.browser.version < 7)) menuClasses.push(c.shadowClass);
			$(this).addClass(menuClasses.join(' '));
		});
	};

	var sf = $.fn.superfish;
	sf.o = [];
	sf.op = {};
	sf.IE7fix = function(){
		var o = sf.op;
		if ($.browser.msie && $.browser.version > 6 && o.dropShadows && o.animation.opacity!=undefined)
			this.toggleClass(sf.c.shadowClass+'-off');
		};
	sf.c = {
		bcClass     : 'sf-breadcrumb',
		menuClass   : 'sf-js-enabled',
		anchorClass : 'sf-with-ul',
		arrowClass  : 'sf-sub-indicator',
		shadowClass : 'sf-shadow'
	};
	sf.defaults = {
		hoverClass	: 'sfHover',
		pathClass	: 'overideThisToUse',
		pathLevels	: 1,
		delay		: 800,
		animation	: {opacity:'show',height:'show'},
		speed		: 'normal',
		autoArrows	: true,
		dropShadows : false,
		disableHI	: false,		// true disables hoverIntent detection
		onInit		: function(){}, // callback functions
		onBeforeShow: function(){},
		onShow		: function(){},
		onHide		: function(){}
	};
	$.fn.extend({
		hideSuperfishUl : function(){
			var o = sf.op,
				not = (o.retainPath===true) ? o.$path : '',
				$ul = $(['li.',o.hoverClass].join(''),this).add(this).not(not).removeClass(o.hoverClass)
					.find('>ul').hide().css('visibility','hidden');
			o.retainPath = false;
			o.onHide.call($ul);
			return this;
		},
		showSuperfishUl : function(){
			var o = sf.op,
				sh = sf.c.shadowClass+'-off',
				$ul = this.addClass(o.hoverClass)
					.find('>ul:hidden').css('visibility','visible');
			sf.IE7fix.call($ul);
			o.onBeforeShow.call($ul);
			$ul.animate(o.animation,o.speed,function(){ sf.IE7fix.call($ul); o.onShow.call($ul); });
			return this;
		}
	});

})(jQuery);


