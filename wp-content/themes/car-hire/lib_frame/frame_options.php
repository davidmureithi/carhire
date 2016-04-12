<?php

/*
*** GLOBAL FRAMEWORK OPTIONS
*/

global $shortname;

$options[] = array(	'type' => 'maintabletop');

	////// General Framework Settings

	$options[] = array(	'name' => __('General Settings'),
						'type' => 'heading');
		
		$options[] = array(	'name' => __('Logo Image &amp; Favicon'),
							'toggle' => 'true',
							'type' => 'subheadingtop');

			$options[] = array(	'name' => __('Choose Your Custom Logo'),
								'desc' => __('Upload your image or paste the full URL address to it next to upload button. <span class="important">Uploaded logo will be applied to Logo widget, which you may edit separately inside Layout control section, but your uploaded logo will be visible by default.</span>'),
								'id' => $shortname.'_logo_url',
								'std' => array(
									'value' => '', 
									'css' => ''
								),
								'type' => 'upload');
								
			$options[] = array(	'name' => __('Choose Your Favicon Image'),
								'desc' => __('Upload your favicon image or paste the full URL address to it next to upload button. Use 16x16px image, if you don&#8217;t have one use free <a href="'.esc_url('www.favicon.cc/').'">Favicon tool</a> and start rocking those browsers. <span class="important">Save your settings after upload is finished.</span>'),
								'id' => $shortname.'_favicon',
								'std' => array(
									'value' => '', 
									'css' => ''
								),
								'type' => 'upload');
		
		$options[] = array(	'type' => 'subheadingbottom');
		
		$options[] = array(	'name' => __('Syndication / Feed'),
							'toggle' => 'true',
							'type' => 'subheadingtop');			
					
			$options[] = array( 'name' => __('RSS Feed Address'),
								'desc' => __('If you are using a service like Feedburner to manage your RSS feed, enter full URL to your feed into box above. If you&#8217;d prefer to use the default WordPress feed, simply leave this box blank.'),
								'id' => $shortname.'_feedburner_url',
								'std' => array(
									'value' => '', 
									'css' => ''
								),
								'type' => 'text');	
					
		$options[] = array(	'type' => 'subheadingbottom');
		
		$options[] = array(	'name' => __('Image Setup'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
					
			$options[] = array(	'name' => __('Display Thumbnails?'),
								'label' => __('Display Thumbnails'),
								'desc' => __('If you want to show image thumbnails, check this option.'),
								'id' => $shortname.'_thumb_show',
								'std' => array(
									'value' => true, 
									'css' => ''
								),
								'type' => 'checkbox');

			$options[] = array(	'name' => __('Resize Images Dynamically?'),
								'label' => __('Resize Images Dynamically'),
								'desc' => __('Resize images with thumb.php script &rarr; smooth pics ;)'),
								'id' => $shortname.'_resize',
								'std' => array(
									'value' => true, 
									'css' => ''
								),
								'type' => 'checkbox');					
								
			$options[] = array(	'name' => __('Automatic Image Handling?'),
								'label' => __('Automatic Image Handling'),
								'desc' => __('If no image in the custom field then first uploaded image is used.'),
								'id' => $shortname.'_auto_img',
								'std' => array(
									'value' => true, 
									'css' => ''
								),
								'type' => 'checkbox');	
								
			$options[] = array(	'name' => __('Show in RSS feed?'),
								'label' => __('Show in RSS feed'),
								'desc' => __('Show thumbnail images in RSS feeds.'),
								'id' => $shortname.'_image_rss',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');
					
		$options[] = array(	'type' => 'subheadingbottom');
		
		$options[] = array(	'name' => __('Stats and Scripts'),
							'toggle' => 'true',
							'type' => 'subheadingtop');	
					
			$options[] = array(	'name' => __('Header Scripts (just before the <code>&lt;/head&gt;</code> tag)'),
								'desc' => __('If you need to add scripts to your header (like <a href="'.esc_url('haveamint.com/').'">Mint</a> tracking code), do so here. These scripts will be included just before the <code>&lt;/head&gt;</code> tag. You may paste multiple scripts.'),
								'id' => $shortname.'_scripts_header',
								'std' => array(
									'value' => '', 
									'css' => ''
								),
								'type' => 'textarea');
								
			$options[] = array(	'name' => __('Body Scripts (just after the <code>&lt;body&gt;</code> tag)'),
								'desc' => __('If you need to add scripts to your body (like <a href="'.esc_url('www.google.com/analytics').'">Google Analytics</a> tracking code), do so here. These scripts will be included just after the <code>&lt;body&gt;</code> tag. You may paste multiple scripts.'),
								'id' => $shortname.'_scripts_body',
								'std' => array(
									'value' => '', 
									'css' => ''
								),
								'type' => 'textarea');
								
			$options[] = array(	'name' => __('Footer Scripts (just before the <code>&lt;/body&gt;</code> tag)'),
								'desc' => __('If you need to add scripts to your footer (like <a href="'.esc_url('www.google.com/analytics').'">Google Analytics</a> tracking code), do so here. These scripts will be included just before the <code>&lt;/body&gt;</code> tag. You may paste multiple scripts.'),
								'id' => $shortname.'_google_analytics',
								'std' => array(
									'value' => '', 
									'css' => ''
								),
								'type' => 'textarea');
		
		$options[] = array(	'type' => 'subheadingbottom');
		
		$options[] = array(	'name' => __('Image Lightbox'),
							'toggle' => 'true',
							'type' => 'subheadingtop');			
					
			if ($bizz_package != 'ZnJlZQ=='){
			
			$options[] = array(	'name' => __('Image Lighbox Effect'),
								'desc' => __('If you want your photos or any other links to pop up with lightbox effect, check this option. To learn more, please <a href="'.esc_url('fancybox.net/howto').'">visit this site</a>.'),
								'label' => __('Enable image lightbox script'),
								'id' => $shortname.'_prettyphoto',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');
			
			} else {
			
			$options[] = array(	'name' => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.'), site_url()),
								'type' => 'help');
								
			}
					
		$options[] = array(	'type' => 'subheadingbottom');
					
	$options[] = array(	'type' => 'maintablebreak');
	
	/// Theme Branding
			
	$options[] = array(	'name' => __('Framework Branding'),
						'type' => 'heading');
					
		$options[] = array(	'name' => __('Footer Logo'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
							
			if ($bizz_package != 'ZnJlZQ==' && $bizz_package != 'c3RhbmRhcmQ='){
								
			$options[] = array(	'name' => __('Front-end Branding Options'),
								'desc' => __('By applying front-end branding options users will acknowledge this website as your own product, with your own logo and optional backlink to theme developer. As this is GPL licensed theme, leave credits in code intact.<span class="important">These options are applied to BizzThemes Branding widget, which you may add/move/remove inside Layout control section.</span>'),
								'type' => 'help');
								
			$options[] = array(	'label' => __('Remove footer credits alltogether'),
								'id' => $shortname.'_branding_front_remove',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');									
								
			$options[] = array(	'name' => __('Custom Image'),
								'desc' => __('Upload your image or paste the full URL address to it next to upload button. Choose small image (recommended dimension within 115x30px limits). <span class="important">Your upload will start after you save changes</span>'),
								'id' => $shortname.'_branding_front_logo',
								'std' => array(
									'value' => '', 
									'css' => ''
								),
								'type' => 'upload');
								
			$options[] = array(	'name' => __('Custom Link'),
								'desc' => __('Add custom link - where your logo points to. Including <code>http://</code>.'),
								'id' => $shortname.'_branding_front_link',
								'std' => array(
									'value' => '', 
									'css' => ''
								),
								'type' => 'text');
												
			} else {
							
			$options[] = array(	'name' => sprintf(__('To use these options, <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Agency Theme Package or Become a Club member</a>.'), site_url()),
								'type' => 'help');
								
			}
					
		$options[] = array(	'type' => 'subheadingbottom');
		
		$options[] = array(	'name' => __('Theme Name'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
							
			if ($bizz_package != 'ZnJlZQ==' && $bizz_package != 'c3RhbmRhcmQ='){
								
			$options[] = array(	'name' => __('Your Icon'),
								'desc' => __('Upload your image or paste the full URL address to it next to upload button. Choose small image (16x16 px limits). <span class="important">Your upload will start after you save changes</span>'),
								'id' => $shortname.'_branding_back_icon',
								'std' => array(
									'value' => '', 
									'css' => ''
								),
								'type' => 'upload');
								
			$options[] = array(	'name' => __('Theme Name'),
								'desc' => __('Rename this theme to whatever name you like.'),
								'id' => $shortname.'_branding_back_name',
								'std' => array(
									'value' => '', 
									'css' => ''
								),
								'type' => 'text');
			
			} else {
							
			$options[] = array(	'name' => sprintf(__('To use these options, <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Agency Theme Package or Become a Club member</a>.'), site_url()),
								'type' => 'help');
			
			}
									
		$options[] = array(	'type' => 'subheadingbottom');
		
		$options[] = array(	'name' => __('Admin Bar'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
							
			if ($bizz_package != 'ZnJlZQ==' && $bizz_package != 'c3RhbmRhcmQ='){
								
			$options[] = array(	'name' => __('Remove Theme Options'),
								'desc' => __('Remove theme options from admin bar.'),
								'label' => __('Remove theme options from admin bar'),
								'id' => $shortname.'_admin_bar_options_remove',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');

			$options[] = array(	'name' => __('Remove Admin Bar'),
								'desc' => __('Remove admin bar altogether for both, logged in and logged out users.'),
								'label' => __('Remove admin bar alltogether'),
								'id' => $shortname.'_admin_bar_remove',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');						
												
			} else {
							
			$options[] = array(	'name' => sprintf(__('To use these options, <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Agency Theme Package or Become a Club member</a>.'), site_url()),
								'type' => 'help');
								
			}
					
		$options[] = array(	'type' => 'subheadingbottom');
		
		$options[] = array(	'name' => __('Login Screen'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
							
			if ($bizz_package != 'ZnJlZQ==' && $bizz_package != 'c3RhbmRhcmQ='){
																	
			$options[] = array(	'name' => __('Login Logo'),
								'desc' => __('Upload your image or paste the full URL address to it next to upload button. Choose small image (recommended dimension within 115x30px limits). <span class="important">Your upload will start after you save changes</span>'),
								'id' => $shortname.'_branding_login_logo',
								'std' => array(
									'value' => '', 
									'css' => ''
								),
								'type' => 'upload');
																					
			} else {
							
			$options[] = array(	'name' => sprintf(__('To use these options, <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Agency Theme Package or Become a Club member</a>.'), site_url()),
								'type' => 'help');
								
			}
					
		$options[] = array(	'type' => 'subheadingbottom');
		
		$options[] = array(	'name' => __('Hide Menu Labels'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
							
			if ($bizz_package != 'ZnJlZQ==' && $bizz_package != 'c3RhbmRhcmQ='){
								
			$options[] = array(	'name' => __('Back-end Menu Labels'),
								'desc' => __('If you are developing sites for your clients, it is important to lock your work after you are done. Hide your layout engine, custom designs, update notifications, API key and disable custom editor.'),
								'type' => 'help');

			$options[] = array(	'label' => __('Hide template builder'),
								'id' => $shortname.'_adminmenu_layout',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');
								
			$options[] = array(	'label' => __('Hide design options'),
								'id' => $shortname.'_adminmenu_design',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');
								
			$options[] = array(	'label' => __('Hide license control'),
								'id' => $shortname.'_adminmenu_license',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');
								
			$options[] = array(	'label' => __('Hide custom editor'),
								'id' => $shortname.'_adminmenu_editor',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');
								
			$options[] = array(	'label' => __('Hide custom tools'),
								'id' => $shortname.'_adminmenu_tools',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');
								
			$options[] = array(	'label' => __('Hide updates control'),
								'id' => $shortname.'_adminmenu_version',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');

			} else {
							
			$options[] = array(	'name' => sprintf(__('To use these options, <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Agency Theme Package or Become a Club member</a>.'), site_url()),
								'type' => 'help');
								
			}
					
		$options[] = array(	'type' => 'subheadingbottom');
		
		$options[] = array(	'name' => __('Hide Notifications'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
							
			if ($bizz_package != 'ZnJlZQ==' && $bizz_package != 'c3RhbmRhcmQ='){
								
			$options[] = array(	'name' => __('Hide notifications'),
								'desc' => __('If you are developing sites for your clients, it is important to hide update notifications and stop them from nagging your clients.'),
								'type' => 'help');
								
			$options[] = array(	'label' => __('Hide WordPress update notifications'),
								'id' => $shortname.'_wp_update_notice_remove',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');
								
			$options[] = array(	'label' => __('Hide all theme notifications'),
								'id' => $shortname.'_theme_notice_remove',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');
												
			} else {
							
			$options[] = array(	'name' => sprintf(__('To use these options, <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Agency Theme Package or Become a Club member</a>.'), site_url()),
								'type' => 'help');
								
			}
					
		$options[] = array(	'type' => 'subheadingbottom');
		
	$options[] = array(	'type' => 'maintablebreak');
					
$options[] = array(	'type' => 'maintablebottom');

$options[] = array(	'type' => 'maintabletop');
	
	/// SEO Options
			
	$options[] = array(	'name' => __('Complete SEO Control'),
						'type' => 'heading');
					
		$options[] = array(	'name' => __('General Options'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
							
			if ($bizz_package != 'ZnJlZQ=='){
								
			$options[] = array(	'name' => __('Deactivate theme SEO?'),
								'label' => __('Disable default Bizz SEO'),
								'desc' => __('In case you want to use another SEO plugin, you can disable the whole theme SEO controls.'),
								'id' => $shortname.'_seo_remove',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');
								
			} else {
			
			$options[] = array(	"name" => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.'), site_url()),
								"type" => "help");
			
			}
					
		$options[] = array(	'type' => 'subheadingbottom');
		
		$options[] = array(	'name' => __('Head <code>&lt;title&gt;</code> tags'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
							
			if ($bizz_package != 'ZnJlZQ=='){

			$options[] = array(	'name' => __('Site name in Title?'),
								'label' => __('Show site name in title (on your homepage). Example: Sitename'),
								'desc' => sprintf(__('You may edit Site name (Blog Title) <a href="%s/wp-admin/options-general.php">here</a>.'), site_url()),
								'id' => $shortname.'_title_title',
								'std' => array(
									'value' => true, 
									'css' => ''
								),
								'type' => 'checkbox');
								
			$options[] = array(	'name' => __('Tagline in Title?'),
								'label' => __('Show site tagline in title (on your homepage). Example: Tagline|Sitename'),
								'desc' => sprintf(__('You may edit Tagline <a href="%s/wp-admin/options-general.php">here</a>.'), site_url()),
								'id' => $shortname.'_title_tagline',
								'std' => array(
									'value' => true, 
									'css' => ''
								),
								'type' => 'checkbox');

			$options[] = array(	'name' => __('Site name in Title across All Pages?'),
								'label' => __('Add site name to all other page titles.'),
								'desc' => __('Add site name in title across all pages. Example: About|Sitename'),
								'id' => $shortname.'_title_other',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');
								
			} else {
			
			$options[] = array(	"name" => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.'), site_url()),
								"type" => "help");
			
			}
					
		$options[] = array(	'type' => 'subheadingbottom');
		
		$options[] = array(	'name' => __('Head <code>&lt;meta&gt;</code> tags'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
							
			if ($bizz_package != 'ZnJlZQ=='){

			$options[] = array(	'name' => __('Meta Description'),
								'desc' => __('You should use meta descriptions to provide search engines with additional information about topics that appear on your site. This only applies to your home page.'),
								'id' => $shortname.'_meta_description',
								'std' => array(
									'value' => '', 
									'css' => ''
								),
								'type' => 'textarea');

			$options[] = array(	'name' => __('Meta Keywords (comma separated)'),
								'desc' => __('Meta keywords are rarely used nowadays but you can still provide search engines with additional information about topics that appear on your site. This only applies to your home page.'),
								'id' => $shortname.'_meta_keywords',
								'std' => array(
									'value' => '', 
									'css' => ''
								),
								'type' => 'text');
								
			$options[] = array(	'name' => __('Meta Author'),
								'desc' => __('You should write your <em>full name</em> here but only do so if this blog is writen only by one outhor. This only applies to your home page.'),
								'id' => $shortname.'_meta_author',
								'std' => array(
									'value' => '', 
									'css' => ''
								),
								'type' => 'text');
								
			} else {
			
			$options[] = array(	"name" => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.'), site_url()),
								"type" => "help");
			
			}
					
		$options[] = array(	'type' => 'subheadingbottom');
		
		$options[] = array(	'name' => __('Head <code>&lt;noindex&gt;</code> tags'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
							
			if ($bizz_package != 'ZnJlZQ=='){

			$options[] = array(	'name' => __('Options for <code>noindex</code> tag'),
								'desc' => __('By adding <code>noindex</code> robot meta tag you are significantly improving your site SEO and prevent search engines from indexing very large database or pages that are very transitory. This way your are preventing spiders from indexing pages that only worsen your search results and keep you from ranking as well as you should.'),
								'type' => 'help');
								
			$options[] = array(	'label' => __('Add <code>&lt;noindex&gt;</code> to category archives.'),
								'id' => $shortname.'_noindex_category',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');
								
			$options[] = array(	'label' => __('Add <code>&lt;noindex&gt;</code> to tag archives.'),
								'id' => $shortname.'_noindex_tag',
								'std' => array(
									'value' => true, 
									'css' => ''
								),
								'type' => 'checkbox');
			
			$options[] = array(	'label' => __('Add <code>&lt;noindex&gt;</code> to author archives.'),
								'id' => $shortname.'_noindex_author',
								'std' => array(
									'value' => true, 
									'css' => ''
								),
								'type' => 'checkbox');
			
			$options[] = array(	'label' => __('Add <code>&lt;noindex&gt;</code> to daily archives.'),
								'id' => $shortname.'_noindex_daily',
								'std' => array(
									'value' => true, 
									'css' => ''
								),
								'type' => 'checkbox');
			
			$options[] = array(	'label' => __('Add <code>&lt;noindex&gt;</code> to monthly archives.'),
								'id' => $shortname.'_noindex_monthly',
								'std' => array(
									'value' => true, 
									'css' => ''
								),
								'type' => 'checkbox');
			
			$options[] = array(	'label' => __('Add <code>&lt;noindex&gt;</code> to yearly archives.'),
								'id' => $shortname.'_noindex_yearly',
								'std' => array(
									'value' => true, 
									'css' => ''
								),
								'type' => 'checkbox');
							
			$options[] = array(	'name' => __('Add <code>&lt;noindex&gt;</code> to checked pages.'),
								'desc' => __('Check all pages you would like to hide from search engine spiders.'),
								'type' => 'help');
											
			$options = pages_exclude_seo($options);
			
			} else {
			
			$options[] = array(	"name" => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.'), site_url()),
								"type" => "help");
			
			}
				
		$options[] = array(	'type' => 'subheadingbottom');
		
		$options[] = array(	'name' => __('Head <code>&lt;noodp&gt;</code> <code>&lt;noydir&gt;</code> attributes'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
							
			if ($bizz_package != 'ZnJlZQ=='){

			$options[] = array(	'name' => __('Options for <code>noodp</code> <code>noydir</code> tag'),
								'desc' => __('By adding <code>noodp</code> <code>noydir</code> robot meta tags you are preventing search engines from displaying Open Directory Project (DMOZ) and Yahoo! Directory listings in your meta descriptions.'),
								'type' => 'help');
								
			$options[] = array(	'label' => __('Add <code>noodp</code> meta tag</code>'),
								'id' => $shortname.'_noodp_meta',
								'std' => array(
									'value' => true, 
									'css' => ''
								),
								'type' => 'checkbox');

			$options[] = array(	'label' => __('Add <code>noydir</code> meta tag</code>'),
								'id' => $shortname.'_noydir_meta',
								'std' => array(
									'value' => true, 
									'css' => ''
								),
								'type' => 'checkbox');
								
			} else {
			
			$options[] = array(	"name" => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.'), site_url()),
								"type" => "help");
			
			}
					
		$options[] = array(	'type' => 'subheadingbottom');
		
		$options[] = array(	'name' => __('Link <code>&lt;nofollow&gt;</code> attributes'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
							
			if ($bizz_package != 'ZnJlZQ=='){

			$options[] = array(	'name' => __('Options for <code>nofollow</code> tag'),
								'desc' => __('By adding <code>nofolow</code> rel attribute to specific links you are reducing the effectiveness of certain types of search engine spam, thereby improving the quality of search engine results and preventing spamdexing from occurring.'),
								'type' => 'help');
								
			$options[] = array(	'label' => __('<code>nofollow</code> Home link</code>'),
								'id' => $shortname.'_nofollow_home',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');

			$options[] = array(	'label' => __('<code>nofollow</code> Author Links</code>'),
								'id' => $shortname.'_nofollow_author',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');
								
			$options[] = array(	'label' => __('<code>nofollow</code> Post Tags</code>'),
								'id' => $shortname.'_nofollow_tags',
								'std' => array(
									'value' => false, 
									'css' => ''
								),
								'type' => 'checkbox');
								
			} else {
			
			$options[] = array(	"name" => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.'), site_url()),
								"type" => "help");
			
			}
					
		$options[] = array(	'type' => 'subheadingbottom');
		
		$options[] = array(	'name' => __('Canonical URLs'),
							'toggle' => 'true',
							'type' => 'subheadingtop');
							
			if ($bizz_package != 'ZnJlZQ=='){

			$options[] = array(	'name' => __('Options for canonical URLs'),
								'desc' => __('Canonical URL: the search engine friendly URL that you want the search engines to treat as authoritative.  In other words, a canonical URL is the URL that you want visitors to see.'),
								'type' => 'help');
								
			$options[] = array(	'label' => __('Enable Canonical URLs'),
								'id' => $shortname.'_canonical_url',
								'std' => array(
									'value' => true, 
									'css' => ''
								),
								'type' => 'checkbox');
								
			} else {
			
			$options[] = array(	"name" => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.'), site_url()),
								"type" => "help");
			
			}
					
		$options[] = array(	'type' => 'subheadingbottom');
		
	$options[] = array(	'type' => 'maintablebreak');
					
$options[] = array(	'type' => 'maintablebottom');

/*
*** GLOBAL SEO OPTIONS
*/

add_filter( 'bizz_meta_boxes', 'bizz_seo_metaboxes' );
function bizz_seo_metaboxes( $meta_boxes ) {
	$prefix = 'bizzthemes_';
	
	$meta_boxes[] = array(
		'id' => 'bizzthemes_seo_meta',
		'title' => __('Bizz &rarr; SEO'),
		'pages' => array( 'page', 'post' ), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __('SEO Title'),
				'desc' => __('Most search engines use a maximum of 60 chars.'),
				'id' => $prefix . 'title',
				'type' => 'text_counter'
			),
			array(
				'name' => __('SEO Meta Description'),
				'desc' => __('Most search engines use a maximum of 160 chars.'),
				'id' => $prefix . 'description',
				'type' => 'textarea_counter'
			),
			array(
				'name' => __('SEO Meta Keywords'),
				'desc' => __('Enter a few keywords for this post/page, separate them by comma (,).'),
				'id' => $prefix . 'keywords',
				'type' => 'text'
			),
			array(
				'name' => __('Noindex this Post/Page'),
				'desc' => __('Prevent search engines from indexing this post/page.'),
				'id' => $prefix . 'noindex',
				'type' => 'checkbox'
			),
			array(
				'name' => __('301 Redirect'),
				'desc' =>  __('Users will get redirected to the <acronym title="Uniform Resource Locator">URL</acronym> above, whenever they visit this post/page.'),
				'id' => $prefix . 'redirect',
				'type' => 'text'
			),
		)
	);
	
	return $meta_boxes;
}

