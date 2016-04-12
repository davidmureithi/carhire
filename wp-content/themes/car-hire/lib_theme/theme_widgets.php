<?php

/* LOAD and REGISTER ALL WIDGETS from WIDGETS FOLDER */
/*------------------------------------------------------------------*/
add_action( 'widgets_init', 'bizz_load_widgets' );
	
function bizz_load_widgets() {

	/* Load each widget file. */
	locate_template( 'lib_theme/widgets/widget-slider.php', true );
	locate_template( 'lib_theme/widgets/widget-ads.php', true );
	locate_template( 'lib_theme/widgets/widget-social.php', true );
	locate_template( 'lib_theme/widgets/widget-contact-info.php', true );

}

/* REGISTER WIDGETIZED GRID */
/*------------------------------------------------------------------*/
if ( function_exists('bizz_register_grids') ){
	bizz_register_grids(array(
		'id' => 'header_area',
		'name' => __('Header Area', 'bizzthemes'),
		'container' => 'container',
		'before_container' => '',
		'after_container' => '',
		'show' => 'true',
		'grids' => array(
			'header_full' => array(
				'class' => 'row',
				'before_grid' => '<div class="span12 head_area">',
				'after_grid' => '</div>',
				'full' => false,
				'tree' => array(
					'logo_area' => array(
						'class' => 'span6 alpha',
						'before_grid' => '',
						'after_grid' => '',
						'tree' => ''
					),
					'contact_area' => array(
						'class' => 'span6 omega',
						'before_grid' => '',
						'after_grid' => '',
						'tree' => ''
					)
				)
			),
			'tnav_full' => array(
				'class' => '',
				'before_grid' => '<div class="span12 tnav_area">',
				'after_grid' => '</div>',
				'full' => true,
				'tree' => ''
			)
		)
	));
	bizz_register_grids(array(
		'id' => 'featured_area',
		'name' => __('Featured Area', 'bizzthemes'),
		'container' => 'container',
		'before_container' => '',
		'after_container' => '',
		'show' => 'true',
		'grids' => array(
			'featured_full' => array(
				'class' => 'row',
				'before_grid' => '<div class="span12 feat_area">',
				'after_grid' => '</div>',
				'full' => false,
				'tree' => array(
					'book_area' => array(
						'class' => 'span4 alpha',
						'before_grid' => '',
						'after_grid' => '',
						'tree' => ''
					),
					'slide_area' => array(
						'class' => 'span8 omega',
						'before_grid' => '',
						'after_grid' => '',
						'tree' => ''
					)
				)
			),
		)
	));
	bizz_register_grids(array(
		'id' => 'main_area',
		'name' => __('Main Area', 'bizzthemes'),
		'container' => 'container',
		'before_container' => '',
		'after_container' => '',
		'show' => 'true',
		'grids' => array(
			'main_full' => array(
				'class' => '',
				'before_grid' => '<div class="span12 main_area">',
				'after_grid' => '</div>',
				'full' => true,
				'tree' => ''
			),
			'main_col' => array(
				'class' => 'row',
				'before_grid' => '<div class="span12 col_area">',
				'after_grid' => '</div>',
				'full' => false,
				'tree' => array(
					'main_one' => array(
						'class' => 'span9 alpha',
						'before_grid' => '',
						'after_grid' => '',
						'tree' => ''
					),
					'main_two' => array(
						'class' => 'span3 omega',
						'before_grid' => '',
						'after_grid' => '',
						'tree' => ''
					)
				)
			)
		)
	));
	bizz_register_grids(array(
		'id' => 'footer_area',
		'name' => __('Footer Area', 'bizzthemes'),
		'container' => 'container',
		'before_container' => '',
		'after_container' => '',
		'show' => 'true',
		'grids' => array(
			'footer_full' => array(
				'class' => 'row',
				'before_grid' => '<div class="span12 foot_area">',
				'after_grid' => '</div>',
				'full' => false,
				'tree' => array(
					'footer_one' => array(
						'class' => 'span2 alpha',
						'before_grid' => '',
						'after_grid' => '',
						'tree' => ''
					),
					'footer_two' => array(
						'class' => 'span2 columns',
						'before_grid' => '',
						'after_grid' => '',
						'tree' => ''
					),
					'footer_three' => array(
						'class' => 'span2 columns',
						'before_grid' => '',
						'after_grid' => '',
						'tree' => ''
					),
					'footer_four' => array(
						'class' => 'span6 omega',
						'before_grid' => '',
						'after_grid' => '',
						'tree' => ''
					)
				)
			)
		)
	));
	
}
	
/* REGISTER WIDGETIZED AREAS */
/*------------------------------------------------------------------*/
if ( function_exists('register_sidebars') ){
	register_sidebars(1,array(
	    'name' => __('Logo Area', 'bizzthemes'),
		'class' => 'logo_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
		'grid' => 'logo_area'
	));
	register_sidebars(1,array(
	    'name' => __('Contact Area', 'bizzthemes'),
		'class' => 'contact_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
		'grid' => 'contact_area'
	));
	register_sidebars(1,array(
	    'name' => __('Navigation Area', 'bizzthemes'),
		'class' => 'tnav_full',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
		'grid' => 'tnav_full'
	));
	register_sidebars(1,array(
	    'name' => __('Booking 1/3', 'bizzthemes'),
		'class' => 'book_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
		'grid' => 'book_area'
	));
	register_sidebars(1,array(
	    'name' => __('Slider 2/3', 'bizzthemes'),
		'class' => 'slide_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
		'grid' => 'slide_area'
	));
	register_sidebars(1,array(
	    'name' => __('Main Full', 'bizzthemes'),
		'class' => 'main_full',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
		'grid' => 'main_full'
	));
	register_sidebars(1,array(
	    'name' => __('Main 2/3', 'bizzthemes'),
		'class' => 'main_one',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
		'grid' => 'main_one'
	));
	register_sidebars(1,array(
	    'name' => __('Main 1/3', 'bizzthemes'),
		'class' => 'main_two',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
		'grid' => 'main_two'
	));
	register_sidebars(1,array(
	    'name' => __('Footer One', 'bizzthemes'),
		'class' => 'footer_one',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
		'grid' => 'footer_one'
	));
	register_sidebars(1,array(
	    'name' => __('Footer Two', 'bizzthemes'),
		'class' => 'footer_two',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
		'grid' => 'footer_two'
	));
	register_sidebars(1,array(
	    'name' => __('Footer Three', 'bizzthemes'),
		'class' => 'footer_three',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
		'grid' => 'footer_three'
	));
	register_sidebars(1,array(
	    'name' => __('Footer Four', 'bizzthemes'),
		'class' => 'footer_four',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
		'grid' => 'footer_four'
	));
	register_sidebars(1,array( #DO NOT REMOVE!!!
	    'name' => __('Inactive Bizz Widgets', 'bizzthemes'),
		'id' => 'bizz_inactive_widgets',
		'description' => '',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => ''
	));
}

/* SET UP DEFAULT WIDGETS */
/*------------------------------------------------------------------*/
/*
function bizz_install_defaults() {
	// Set up default widgets for default theme.
	update_option( 'widget_search', array ( 2 => array ( 'title' => '' ), '_multiwidget' => 1 ) );
	update_option( 'widget_recent-posts', array ( 2 => array ( 'title' => '', 'number' => 5 ), '_multiwidget' => 1 ) );
	update_option( 'widget_recent-comments', array ( 2 => array ( 'title' => '', 'number' => 5 ), '_multiwidget' => 1 ) );
	update_option( 'widget_archives', array ( 2 => array ( 'title' => '', 'count' => 0, 'dropdown' => 0 ), '_multiwidget' => 1 ) );
	update_option( 'widget_categories', array ( 2 => array ( 'title' => '', 'count' => 0, 'hierarchical' => 0, 'dropdown' => 0 ), '_multiwidget' => 1 ) );
	update_option( 'widget_meta', array ( 2 => array ( 'title' => '' ), '_multiwidget' => 1 ) );
	update_option( 'sidebars_widgets', array ( 'wp_inactive_widgets' => array ( ), 'sidebar-1' => array ( 0 => 'search-2', 1 => 'recent-posts-2', 2 => 'recent-comments-2', 3 => 'archives-2', 4 => 'categories-2', 5 => 'meta-2', ), 'sidebar-2' => array ( ), 'sidebar-3' => array ( ), 'sidebar-4' => array ( ), 'sidebar-5' => array ( ), 'array_version' => 3 ) );
}
*/

