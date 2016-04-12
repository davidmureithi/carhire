<?php

/* GLOBAL DESIGN OPTIONS */
/*------------------------------------------------------------------*/
global $shortname;

$design[] = array(	'type' => 'maintabletop');

	////// General Styling

	$design[] = array(	'name' => __('General Styling', 'bizzthemes'),
						'type' => 'heading');
					
		$design[] = array(	'name' => __('Layout Control', 'bizzthemes'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
							
			$design[] = array(	'name' => __('Predefined Skins', 'bizzthemes'),
								'desc' => __('Please select the CSS skin for your website here. CSS skin files are located in your theme skins folder.', 'bizzthemes'),
								'id' => $shortname.'_alt_stylesheet',
								'std' => array(
									'value' => '', 
									'css' => ''
								),
								'type' => 'select',
								'show_option_none' => true,
								'options' => $alt_stylesheets);
								
			$design[] = array(	'name' => __('Hide custom.css', 'bizzthemes'),
								'label' => __('Hide Custom Stylesheet', 'bizzthemes'),
								'desc' => sprintf(__('Custom.css file allows you to make custom design changes using CSS. You have option to create your own css skin in skins folder or to simply enable and <a href="%s/wp-admin/theme-editor.php">edit custom.css file</a>.<span class="important">Check this option to disable custom.css file output.</span>', 'bizzthemes'), site_url()),
								'id' => $shortname.'_custom_css',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');	
								
			$design[] = array(	'name' => __('Hide layout.css', 'bizzthemes'),
								'label' => __('Hide Design Control Tweaks', 'bizzthemes'),
								'desc' => __('If you want to hide all CSS design tweaks you&#8217;ve created using theme design control panel, check this option.', 'bizzthemes'),
								'id' => $shortname.'_layout_css',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');
					
		$design[] = array(	'type' => 'subheadingbottom');	

		$design[] = array(	'name' => __('Body Background', 'bizzthemes'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
								
			$design[] = array(  'name' => __('<code>body</code> background', 'bizzthemes'),
								'desc' => __('Specify <code>body</code> background properties. <span class="important">Uploading image is optional, so you may only choose background color if you like.</span>', 'bizzthemes'),
								'id' => $shortname.'_body_img_prop',
								'std' => array(
									'background-image' => '',
									'background-color' => '',
									'background-repeat' => '', 
									'background-position' => '', 
									'css' => 'body'
								),
								'type' => 'bgproperties');
					
		$design[] = array(	'type' => 'subheadingbottom');
		
		$design[] = array(	'name' => __('Body Links', 'bizzthemes'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
								
			$design[] = array(  'name' => __('<code>a</code> link text color', 'bizzthemes'),
								'desc' => __('Pick a custom link color to be applied to <code>body</code> text links.', 'bizzthemes'),
								'id' => $shortname.'_c_links',
								'std' => array(
									'color' => '', 
									'css' => 'a'
								),
								'type' => 'color');
								
			$design[] = array(  'name' => __('<code>a:hover</code> link text color', 'bizzthemes'),
								'desc' => __('Pick a custom onhover link color to be applied to <code>body</code> text links.', 'bizzthemes'),
								'id' => $shortname.'_c_links_onhover',
								'std' => array(
									'color' => '', 
									'css' => 'a:hover'
								),
								'type' => 'color');
					
		$design[] = array(	'type' => 'subheadingbottom');
		
		$design[] = array(	'name' => __('Body Text', 'bizzthemes'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
								
			$design[] = array(  'name' => __('<code>body</code> fonts (all)', 'bizzthemes'),
								'desc' => __('Select the typography you want for all of your texts. <span class="important">* Web-safe font.<br/>G Google font.</span>', 'bizzthemes'),
								'id' => $shortname.'_f_general',
								'std' => array(
									'font-size' => '', 
									'font-family' => '', 
									'font-style' => '', 
									'font-variant' => '',
									'font-weight' => '',
									'color' => '',
									'css' => 'body'
								),
								'type' => 'typography');
								
			$design[] = array(  'name' => __('<code>H1</code> fonts', 'bizzthemes'),
								'desc' => __('Select the typography you want for your text, displayed inside <code>H1</code> tags. <span class="important">* Web-safe font.<br/>G Google font.</span>', 'bizzthemes'),
								'id' => $shortname.'_f_h1',
								'std' => array(
									'font-size' => '', 
									'font-family' => '', 
									'font-style' => '', 
									'font-variant' => '',
									'font-weight' => '', 
									'color' => '',
									'css' => 'h1'
								),
								'type' => 'typography');
								
			$design[] = array(  'name' => __('<code>H2</code> fonts', 'bizzthemes'),
								'desc' => __('Select the typography you want for your text, displayed inside <code>H2</code> tags. <span class="important">* Web-safe font.<br/>G Google font.</span>', 'bizzthemes'),
								'id' => $shortname.'_f_h2',
								'std' => array(
									'font-size' => '', 
									'font-family' => '', 
									'font-style' => '', 
									'font-variant' => '',
									'font-weight' => '', 
									'color' => '',
									'css' => 'h2'
								),
								'type' => 'typography');
								
			$design[] = array(  'name' => __('<code>H3</code> fonts', 'bizzthemes'),
								'desc' => __('Select the typography you want for your text, displayed inside <code>H3</code> tags. <span class="important">* Web-safe font.<br/>G Google font.</span>', 'bizzthemes'),
								'id' => $shortname.'_f_h3',
								'std' => array(
									'font-size' => '', 
									'font-family' => '', 
									'font-style' => '', 
									'font-variant' => '',
									'font-weight' => '', 
									'color' => '',
									'css' => 'h3'
								),
								'type' => 'typography');
								
			$design[] = array(  'name' => __('<code>H4</code> fonts', 'bizzthemes'),
								'desc' => __('Select the typography you want for your text, displayed inside <code>H4</code> tags. <span class="important">* Web-safe font.<br/>G Google font.</span>', 'bizzthemes'),
								'id' => $shortname.'_f_h4',
								'std' => array(
									'font-size' => '', 
									'font-family' => '', 
									'font-style' => '', 
									'font-variant' => '',
									'font-weight' => '', 
									'color' => '',
									'css' => 'h4'
								),
								'type' => 'typography');
								
			$design[] = array(  'name' => __('<code>H5</code> fonts', 'bizzthemes'),
								'desc' => __('Select the typography you want for your text, displayed inside <code>H5</code> tags. <span class="important">* Web-safe font.<br/>G Google font.</span>', 'bizzthemes'),
								'id' => $shortname.'_f_h5',
								'std' => array(
									'font-size' => '', 
									'font-family' => '', 
									'font-style' => '', 
									'font-variant' => '',
									'font-weight' => '', 
									'color' => '',
									'css' => 'h5'
								),
								'type' => 'typography');
								
			$design[] = array(  'name' => __('<code>H6</code> fonts', 'bizzthemes'),
								'desc' => __('Select the typography you want for your text, displayed inside <code>H6</code> tags. <span class="important">* Web-safe font.<br/>G Google font.</span>', 'bizzthemes'),
								'id' => $shortname.'_f_h6',
								'std' => array(
									'font-size' => '', 
									'font-family' => '', 
									'font-style' => '', 
									'font-variant' => '',
									'font-weight' => '', 
									'color' => '',
									'css' => 'h6'
								),
								'type' => 'typography');
					
		$design[] = array(	'type' => 'subheadingbottom');
							
	$design[] = array(	'type' => 'maintablebreak');
							
$design[] = array(	'type' => 'maintablebottom');
	
$design[] = array(	'type' => 'maintabletop');
	
	////// Area Styling

	$design[] = array(	'name' => __('Area Styling', 'bizzthemes'),
						'type' => 'heading');
								
		$design[] = array(	'name' => __('Header Area', 'bizzthemes'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
								
			if ($bizz_package != 'ZnJlZQ=='){
			
			$design[] = array(  'name' => __('Area background', 'bizzthemes'),
								'desc' => __('Specify background properties. <span class="important">Uploading image is optional, so you may only choose background color if you like.</span>', 'bizzthemes'),
								'id' => $shortname.'_bg_header_area',
								'std' => array(
									'background-image' => '',
									'background-color' => '',
									'background-repeat' => '', 
									'background-position' => '', 
									'css' => '#header_area'
								),
								'type' => 'bgproperties');
								
			$design[] = array(  'name' => __('Area font', 'bizzthemes'),
								'desc' => __('Select the typography you want for your <code>#header_area .widget</code> tags. <span class="important">* Web-safe font.<br/>G Google font.</span>', 'bizzthemes'),
								'id' => $shortname.'_f_header_area',
								'std' => array(
									'font-size' => '', 
									'font-family' => '', 
									'font-style' => '', 
									'font-variant' => '',
									'font-weight' => '', 
									'color' => '',
									'css' => '#header_area .widget'
								),
								'type' => 'typography');
								
			$design[] = array(  'name' => __('Area widget title', 'bizzthemes'),
								'desc' => __('Select the typography you want for your <code>#header_area .widget h3</code> tags. <span class="important">* Web-safe font.<br/>G Google font.</span>', 'bizzthemes'),
								'id' => $shortname.'_f_header_area_title',
								'std' => array(
									'font-size' => '', 
									'font-family' => '', 
									'font-style' => '', 
									'font-variant' => '',
									'font-weight' => '', 
									'color' => '',
									'css' => '#header_area .widget h3.widget-title'
								),
								'type' => 'typography');
								
			$design[] = array(  'name' => __('Area widget <code>a</code> link text color', 'bizzthemes'),
								'desc' => __('Pick a custom link color to be applied to text links.', 'bizzthemes'),
								'id' => $shortname.'_c_header_area_a',
								'std' => array(
									'color' => '', 
									'css' => '.header_one .widget a, .header_two .widget a'
								),
								'type' => 'color');
								
			} else {
			
			$design[] = array(	"name" => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.', 'bizzthemes'), site_url()),
								"type" => "help");
			
			}

		$design[] = array(	'type' => 'subheadingbottom');
		
		$design[] = array(	'name' => __('Featured Area', 'bizzthemes'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
								
			if ($bizz_package != 'ZnJlZQ=='){
			
			$design[] = array(  'name' => __('Area background', 'bizzthemes'),
								'desc' => __('Specify background properties. <span class="important">Uploading image is optional, so you may only choose background color if you like.</span>', 'bizzthemes'),
								'id' => $shortname.'_bg_featured_area',
								'std' => array(
									'background-image' => '',
									'background-color' => '',
									'background-repeat' => '', 
									'background-position' => '', 
									'css' => '#featured_area'
								),
								'type' => 'bgproperties');
								
			$design[] = array(  'name' => __('Area font', 'bizzthemes'),
								'desc' => __('Select the typography you want for your <code>#featured_area .widget</code> tags. <span class="important">* Web-safe font.<br/>G Google font.</span>', 'bizzthemes'),
								'id' => $shortname.'_f_featured_area',
								'std' => array(
									'font-size' => '', 
									'font-family' => '', 
									'font-style' => '', 
									'font-variant' => '',
									'font-weight' => '', 
									'color' => '',
									'css' => '#featured_area .widget'
								),
								'type' => 'typography');
								
			$design[] = array(  'name' => __('Area widget title', 'bizzthemes'),
								'desc' => __('Select the typography you want for your <code>#featured_area .widget h3</code> tags. <span class="important">* Web-safe font.<br/>G Google font.</span>', 'bizzthemes'),
								'id' => $shortname.'_f_featured_area_title',
								'std' => array(
									'font-size' => '', 
									'font-family' => '', 
									'font-style' => '', 
									'font-variant' => '',
									'font-weight' => '', 
									'color' => '',
									'css' => '#featured_area .widget h3.widget-title'
								),
								'type' => 'typography');
								
			$design[] = array(  'name' => __('Area widget <code>a</code> link text color', 'bizzthemes'),
								'desc' => __('Pick a custom link color to be applied to text links.', 'bizzthemes'),
								'id' => $shortname.'_c_featured_area_a',
								'std' => array(
									'color' => '', 
									'css' => '.featured_one .widget a, .featured_two .widget a'
								),
								'type' => 'color');
								
			} else {
			
			$design[] = array(	"name" => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.', 'bizzthemes'), site_url()),
								"type" => "help");
			
			}

		$design[] = array(	'type' => 'subheadingbottom');
								
		$design[] = array(	'name' => __('Main Area', 'bizzthemes'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
								
			if ($bizz_package != 'ZnJlZQ=='){
			
			$design[] = array(  'name' => __('Area background', 'bizzthemes'),
								'desc' => __('Specify background properties. <span class="important">Uploading image is optional, so you may only choose background color if you like.</span>', 'bizzthemes'),
								'id' => $shortname.'_bg_main_area',
								'std' => array(
									'background-image' => '',
									'background-color' => '',
									'background-repeat' => '', 
									'background-position' => '', 
									'css' => '#main_area'
								),
								'type' => 'bgproperties');
								
			$design[] = array(  'name' => __('Area font', 'bizzthemes'),
								'desc' => __('Select the typography you want for your <code>#main_area .widget</code> tags. <span class="important">* Web-safe font.<br/>G Google font.</span>', 'bizzthemes'),
								'id' => $shortname.'_f_main_area',
								'std' => array(
									'font-size' => '', 
									'font-family' => '', 
									'font-style' => '', 
									'font-variant' => '',
									'font-weight' => '', 
									'color' => '',
									'css' => '#main_area .widget'
								),
								'type' => 'typography');
								
			$design[] = array(  'name' => __('Area widget title', 'bizzthemes'),
								'desc' => __('Select the typography you want for your <code>#main_area .widget h3</code> tags. <span class="important">* Web-safe font.<br/>G Google font.</span>', 'bizzthemes'),
								'id' => $shortname.'_f_main_area_title',
								'std' => array(
									'font-size' => '', 
									'font-family' => '', 
									'font-style' => '', 
									'font-variant' => '',
									'font-weight' => '', 
									'color' => '',
									'css' => '#main_area .widget h3.widget-title'
								),
								'type' => 'typography');

			} else {
			
			$design[] = array(	"name" => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.', 'bizzthemes'), site_url()),
								"type" => "help");
			
			}

		$design[] = array(	'type' => 'subheadingbottom');
								
		$design[] = array(	'name' => __('Footer Area', 'bizzthemes'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
								
			if ($bizz_package != 'ZnJlZQ=='){
			
			$design[] = array(  'name' => __('Footer background', 'bizzthemes'),
								'desc' => __('Specify background properties. <span class="important">Uploading image is optional, so you may only choose background color if you like.</span>', 'bizzthemes'),
								'id' => $shortname.'_bg_footer_area',
								'std' => array(
									'background-image' => '',
									'background-color' => '',
									'background-repeat' => '', 
									'background-position' => '', 
									'css' => '#footer_area, .foot-logo .powered'
								),
								'type' => 'bgproperties');
								
			$design[] = array(  'name' => __('Area font', 'bizzthemes'),
								'desc' => __('Select the typography you want for your <code>#footer_area .widget</code> tags. <span class="important">* Web-safe font.<br/>G Google font.</span>', 'bizzthemes'),
								'id' => $shortname.'_f_footer_area',
								'std' => array(
									'font-size' => '', 
									'font-family' => '', 
									'font-style' => '', 
									'font-variant' => '',
									'font-weight' => '', 
									'color' => '',
									'css' => '#footer_area .widget'
								),
								'type' => 'typography');
								
			$design[] = array(  'name' => __('Area widget title', 'bizzthemes'),
								'desc' => __('Select the typography you want for your <code>#footer_area .widget h3</code> tags. <span class="important">* Web-safe font.<br/>G Google font.</span>', 'bizzthemes'),
								'id' => $shortname.'_f_footer_area_title',
								'std' => array(
									'font-size' => '', 
									'font-family' => '', 
									'font-style' => '', 
									'font-variant' => '',
									'font-weight' => '', 
									'color' => '',
									'css' => '#footer_area .widget h3.widget-title'
								),
								'type' => 'typography');
								
			$design[] = array(  'name' => __('Area widget <code>a</code> link text color', 'bizzthemes'),
								'desc' => __('Pick a custom link color to be applied to <code>#footer_area a</code> text links.', 'bizzthemes'),
								'id' => $shortname.'_c_footer_area_a',
								'std' => array(
									'color' => '', 
									'css' => '#footer_area a'
								),
								'type' => 'color');
								
			} else {
			
			$design[] = array(	"name" => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.', 'bizzthemes'), site_url()),
								"type" => "help");
			
			}
														
		$design[] = array(	'type' => 'subheadingbottom');
																
	$design[] = array(	'type' => 'maintablebreak');
	
	////// Widget Styling

	$design[] = array(	'name' => __('Widget Styling', 'bizzthemes'),
						'type' => 'heading');
								
		$design[] = array(	'name' => __('Booking Widget', 'bizzthemes'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
								
			if ($bizz_package != 'ZnJlZQ=='){
			
			$design[] = array(  'name' => __('Widget background', 'bizzthemes'),
								'desc' => __('Specify background properties. <span class="important">Uploading image is optional, so you may only choose background color if you like.</span>', 'bizzthemes'),
								'id' => $shortname.'_bg_bookwrap',
								'std' => array(
									'background-image' => '',
									'background-color' => '',
									'background-repeat' => '', 
									'background-position' => '', 
									'css' => '.bookwrap'
								),
								'type' => 'bgproperties');
								
			$design[] = array(  'name' => 'Widget border',
				                    'desc' => 'Specify border properties.',
									'id' => $shortname.'_b_bookwrap',
									'std' => array(
									    'border-position' => 'border',
										'border-width' => '', 
										'border-style' => '', 
										'border-color' => '',
										'css' => '.bookwrap'
									),
									'type' => 'border');
								
			$design[] = array(  'name' => __('Widget font', 'bizzthemes'),
								'desc' => __('Select the typography you want for your <code>.bookwrap .widget</code> tags. <span class="important">* Web-safe font.<br/>G Google font.</span>', 'bizzthemes'),
								'id' => $shortname.'_f_bookwrap',
								'std' => array(
									'font-size' => '', 
									'font-family' => '', 
									'font-style' => '', 
									'font-variant' => '',
									'font-weight' => '', 
									'color' => '',
									'css' => '.bookwrap .widget'
								),
								'type' => 'typography');
								
			$design[] = array(  'name' => 'Control Navigation background color',
					                'desc' => 'Specify background color.',
									'id' => $shortname.'_bg_cnav',
									'std' => array(
									    'background-color' => '', 
									    'css' => '.steps_tabs li .number'
									),
									'type' => 'background-color');
									
			$design[] = array(  'name' => 'Control Navigation active background color',
					                'desc' => 'Specify background color.',
									'id' => $shortname.'_bg_cnav_active',
									'std' => array(
									    'background-color' => '', 
									    'css' => '.steps_tabs li.selected .number'
									),
									'type' => 'background-color');
									
			$design[] = array(  'name' => 'Control Navigation border',
				                    'desc' => 'Specify border properties.',
									'id' => $shortname.'_b_cnav',
									'std' => array(
									    'border-position' => 'border',
										'border-width' => '', 
										'border-style' => '', 
										'border-color' => '',
										'css' => '.steps_tabs li .number'
									),
									'type' => 'border');
									
			$design[] = array(  'name' => __('Control Navigation font', 'bizzthemes'),
								'desc' => __('Select the typography you want. <span class="important">* Web-safe font.<br/>G Google font.</span>', 'bizzthemes'),
								'id' => $shortname.'_f_cnav',
								'std' => array(
									'font-size' => '', 
									'font-family' => '', 
									'font-style' => '', 
									'font-variant' => '',
									'font-weight' => '', 
									'color' => '',
									'css' => '.steps_tabs li'
								),
								'type' => 'typography');
								
			} else {
			
			$design[] = array(	"name" => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.', 'bizzthemes'), site_url()),
								"type" => "help");
			
			}

		$design[] = array(	'type' => 'subheadingbottom');
		
		$design[] = array(	'name' => __('Navigation Menu widget', 'bizzthemes'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
								
			if ($bizz_package != 'ZnJlZQ=='){
			
			$design[] = array(  'name' => __('Menu font', 'bizzthemes'),
								'desc' => __('Select the typography you want for your <code>.widget .nav-menu a</code> tags. <span class="important">* Web-safe font.<br/>G Google font.</span>', 'bizzthemes'),
								'id' => $shortname.'_f_nav_menu',
								'std' => array(
									'font-size' => '', 
									'font-family' => '', 
									'font-style' => '', 
									'font-variant' => '',
									'font-weight' => '', 
									'color' => '',
									'css' => '.widget .nav-menu a'
								),
								'type' => 'typography');
								
			$design[] = array(  'name' => __('Menu background', 'bizzthemes'),
								'desc' => __('Specify <code>.widget .nav-menu</code> background properties. <span class="important">enter "none" if you only want to choose background color and don\'t want to upload custom background image.</span>', 'bizzthemes'),
								'id' => $shortname.'_bg_nav_menu',
								'std' => array(
									'background-image' => '',
									'background-color' => '',
									'background-repeat' => '', 
									'background-position' => '', 
									'css' => '.widget .nav-menu'
								),
								'type' => 'bgproperties');
								
			$design[] = array(  'name' => __('Item <code>:hover</code> background color', 'bizzthemes'),
								'desc' => __('Pick a custom link color to be applied to menu items.', 'bizzthemes'),
								'id' => $shortname.'_bg_nav_menu_a_hover',
								'std' => array(
									'background-color' => '', 
									'css' => '.widget .nav-menu li:hover, .widget .nav-menu li li.current-menu-item a, .widget .nav-menu li li:hover, .widget .nav-menu li li:hover li a'
								),
								'type' => 'background-color');
								
			} else {
			
			$design[] = array(	"name" => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.', 'bizzthemes'), site_url()),
								"type" => "help");
			
			}
														
		$design[] = array(	'type' => 'subheadingbottom');
																
	$design[] = array(	'type' => 'maintablebreak');
							
$design[] = array(	'type' => 'maintablebottom');


/* GLOBAL THEME OPTIONS */
/*------------------------------------------------------------------*/
			
$options[] = array(	"type" => "maintabletop");
	
	/// empty
					
$options[] = array(	"type" => "maintablebottom");
