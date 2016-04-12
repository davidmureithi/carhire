<?php

function bizzthemes_layout() {
	global $wpdb, $wp_version, $wp_registered_widget_updates, $wp_registered_sidebars, $wp_registered_widgets, $bizz_package, $sidebars_widgets;
		
	// Permissions Check
	if ( ! current_user_can('edit_theme_options') )
		wp_die( __( 'Cheatin&#8217; uh?' ) );
		
	// PHPurchase fix
	remove_action( 'admin_head', 'admin_register_styles' );
		
	// WordPress Administration Widgets API
	load_template(ABSPATH . 'wp-admin/includes/widgets.php');
	
	// These are the widgets grouped by sidebar
	$sidebars_widgets = wp_get_sidebars_widgets();
	if ( empty( $sidebars_widgets ) )
		$sidebars_widgets = wp_get_widget_defaults();
		
	// Do sidebar action
	do_action( 'sidebar_admin_setup' );
				
	// options header
	bizzthemes_options_header( $options_title = __('Template Builder', 'bizzthemes'), $toggle = false );

	if (isset($messages)){
		foreach( $messages as $message ) :
			echo $message . "\n";
		endforeach;
	}
	
	// register the inactive_widgets area as sidebar
	register_sidebar(array(
		'name' => __('Inactive Widgets'),
		'id' => 'wp_inactive_widgets',
		'class' => 'inactive-sidebar',
		'description' => __( 'Drag widgets here to remove them from the sidebar but keep their settings.' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
		'grid' => ''
	));

	if( function_exists('retrieve_widgets') ) retrieve_widgets();
	
	do_action( 'widgets_admin_page' );
	
	$pre32 = (version_compare($wp_version, '3.1.3', '<=')) ? 'pre32' : 'aft32';
?>
	<div id="widget-frame" class="<?php echo $pre32; ?>">
	<div class="widget-liquid-left<?php if ( !isset($_REQUEST['tab']) ){ echo ' liquid-left-disabled'; } ?>">
	<div id="widgets-left">
		<div id="template-menu" class="widgets-holder-wrap<?php if ( isset($_REQUEST['tab']) ){ echo ' closed'; } ?>">
			<div class="sidebar-name">
				<div class="sidebar-name-arrow"><br /></div>
				<h3><?php _e('Select a Template to Edit', 'bizzthemes'); ?>
					<span><img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" class="ajax-feedback" title="" alt="" /></span>
				</h3>
			</div>
			<div class="widget-holder inactive">
				<p class="description"><?php _e('Click on template below and add Available Widgets to template areas on right.', 'bizzthemes'); ?></p>
				<div class="menu-tabs">
<?php 
					$layout_tabs = bizz_layout_tabs();
					bizz_menu_tabs( $layout_tabs ); 
?>
				</div>
				<br class="clear" />
			</div>
		</div>
		<?php if ( isset($_REQUEST['tab']) ){ ?>
		<div class="bizz-menu-separator"><!----></div>
		<div id="available-widgets" class="widgets-holder-wrap<?php if ( !isset($_REQUEST['tab']) ){ echo ' closed'; } ?>">
			<div class="sidebar-name">
			   <div class="sidebar-name-arrow"><br /></div>
			   <h3><?php _e('Available Widgets', 'bizzthemes'); ?> <span id="removing-widget"><?php _e('Deactivate', 'bizzthemes'); ?> <span></span></span></h3>
			</div>
			<div class="widget-holder">
				<p class="description">
					<?php _e('Drag widgets from here to a layout on the right to activate them. Drag widgets back here to deactivate them.', 'bizzthemes'); ?>
				</p>
				<div id="widget-list">
					<?php wp_list_widgets(); ?>
				</div>
				<br class='clear' />
			</div>
		</div>
		<div class="bizz-menu-separator"><!----></div>
<?php
foreach ( $wp_registered_sidebars as $sidebar => $registered_sidebar ) {
	if ( false !== strpos( $registered_sidebar['class'], 'inactive-sidebar' ) || 'orphaned_widgets' == substr( $sidebar, 0, 16 ) ) {
		$wrap_class = 'widgets-holder-wrap closed';
		if ( !empty( $registered_sidebar['class'] ) )
			$wrap_class .= ' ' . $registered_sidebar['class'];

?>

		<div class="<?php esc_attr_e( $wrap_class ); ?>">
			<div class="sidebar-name">
				<div class="sidebar-name-arrow"><br /></div>
				<h3><?php esc_html_e( $registered_sidebar['name'] ); ?>
					<span><img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" class="ajax-feedback" title="" alt="" /></span>
				</h3>
			</div>
			<div class="widget-holder inactive">
				<?php wp_list_widget_controls( $registered_sidebar['id'] ); ?>
				<br class="clear" />
			</div>
		</div>
<?php
	}
}
?>
		<?php } ?>
	</div>
	</div>
	<div class="widget-liquid-right<?php if ( !isset($_REQUEST['tab']) ){ echo ' liquid-right-disabled'; } ?>">
	<div id="widgets-right">
		<div id="post-body-content" class="sortme<?php if ( !isset($_REQUEST['tab']) ){ echo ' sortme-disabled'; } ?>">
			<?php echo bizz_browsing_tabs(); ?>
			<div class="layouts-toggle"><a href="#" class="button master_toggle"><?php _e('Show All Widgets [&#43;]', 'bizzthemes'); ?></a></div>
			<div class="clear"><!----></div>
<?php
			// conditions and items (administration tabs)
			(isset($_REQUEST['condition'])) ? ($bizz_condition = $_REQUEST['condition']) : ($bizz_condition = 'is_index');
			(isset($_REQUEST['id'])) ? ($bizz_item = $_REQUEST['id']) : ($bizz_item = 'all');
			(isset($_REQUEST['tab'])) ? ($bizz_tab = $_REQUEST['tab']) : ($bizz_tab = 'is_index');
			(isset($_REQUEST['subtab'])) ? ($bizz_subtab = $_REQUEST['subtab']) : ($bizz_subtab = 'all');
			(isset($_REQUEST['subtabsub'])) ? ($bizz_subtabsub = $_REQUEST['subtabsub']) : ($bizz_subtabsub = 'all');
			
			// define condition logic
			$condition_logic['bizz_tab'] = $bizz_tab;
			$condition_logic['bizz_subtab'] = $bizz_subtab;
			$condition_logic['bizz_subtabsub'] = $bizz_subtabsub;
			$condition_logic['bizz_condition'] = $bizz_condition;
			$condition_logic['bizz_item'] = $bizz_item;
			
			// bake condition and item
			echo "\t<input type=\"hidden\" class=\"cond_item\" name=\"empty\" value=\"empty\" />\n";
			echo "\t<input type=\"hidden\" class=\"cond_item\" name=\"condition\" value=\"$bizz_condition\" />\n";
			echo "\t<input type=\"hidden\" class=\"cond_item\" name=\"item\" value=\"$bizz_item\" />\n";
			echo "\t<input type=\"hidden\" class=\"cond_item\" name=\"empty\" value=\"empty\" />\n";
			echo "\t<input type=\"hidden\" class=\"empty_parent\" name=\"empty\" value=\"empty\" />\n";
			echo "\t<input type=\"hidden\" class=\"is_parent\" name=\"parent\" value=\"true\" />\n";
			echo "\t<input type=\"hidden\" class=\"not_parent\" name=\"parent\" value=\"false\" />\n";
			echo "\t<input type=\"hidden\" class=\"cond_item\" name=\"empty\" value=\"empty\" />\n";
			echo "\t<input type=\"hidden\" class=\"empty_parent\" name=\"empty\" value=\"empty\" />\n";
			echo "\t<input type=\"hidden\" class=\"is_enabled\" name=\"show\" value=\"true\" />\n";
			echo "\t<input type=\"hidden\" class=\"not_enabled\" name=\"show\" value=\"false\" />\n";
			
			$widget_logic = bizz_frame_widget_logic($condition_logic); #widget logic
			// print_r($widget_logic);
			// print_r('<br/><br/>');
			
			$grid_logic = bizz_frame_grid_logic($condition_logic); #grid logic
			// print_r($grid_logic);
			// print_r('<br/><br/>');
			
			$main_area_exists = (array_key_exists("main_area", $grid_logic)) ? true : false; #main area exists?
			
			// loop through registred grids	
			$i = 0;
			foreach ( $grid_logic as $container => $registered_container ) {
			
				// $closed = ( $i && !isset($_REQUEST['tab']) ) ? ' closed' : (( $i && $registered_container['show']=='true' ) ? ' closed' : ''); # open first container
				// $closed = ( $registered_container['id']=='main_area' ) ? '' : (( $i && !isset($_REQUEST['tab']) ) ? ' closed' : (( $i && $registered_container['show']=='true' ) ? ' closed' : '')); # open first container
				$closed = ( $main_area_exists ) ? ( ($registered_container['id']=='main_area') ? '' : ' closed') : (( $i && !isset($_REQUEST['tab']) ) ? ' closed' : (( $i && $registered_container['show']=='true' ) ? ' closed' : '')); # open first container
				
				if ($registered_container['show']=='true') {
					$box='enabled-box'; $taction=__('Disable', 'bizzthemes'); $show='true'; # show grid
				}
				elseif (!isset($_REQUEST['tab'])) {
					$box='enabled-box'; $taction=__('Disable', 'bizzthemes'); $show='true'; # show grid
				}
				else {
					$box='disabled-box closed'; $taction=__('Enable', 'bizzthemes'); $show='false'; # hide grid
				}
				
				if ( !isset($_REQUEST['tab']) ){ $taction=''; } # hide enable/disable for first builder page
?>													
				<div class="widgets-holder-wrap container-area <?php echo $box; ?> <?php echo esc_html( $registered_container['container'] ); ?><?php echo $closed; ?>">
					<div class="sidebar-name container-name">
					<div class="sidebar-name-arrow container-arrow"><br /></div>
					<h3 class="container-name">
						<?php echo esc_html( $registered_container['name'] ); ?>
						<span><img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" class="ajax-feedback" title="" alt="" /></span>
						<span class="title-action"><a href="#"><?php echo $taction; ?></a></span>
					</h3>
					</div>
					<div class="widget-holder">
<?php						
						bizz_grid_tree($registered_container['grids'])
?>
					<br class="clear" />
					</div><!-- END .widget-holder -->
					
					<input type="hidden" class="accepted" name="<?php echo $registered_container['id']; ?>[id]" value="<?php echo $registered_container['id']; ?>" />
					<input type="hidden" class="accepted" name="<?php echo $registered_container['id']; ?>[name]" value="<?php echo $registered_container['name']; ?>" />
					<input type="hidden" class="accepted" name="<?php echo $registered_container['id']; ?>[show]" value="<?php echo $show; ?>" />
					<input type="hidden" class="accepted" name="<?php echo $registered_container['id']; ?>[condition]" value="<?php isset($_REQUEST['condition']) ? esc_attr_e($_REQUEST['condition']) : esc_attr_e('is_index', 'bizzthemes'); ?>" />
					<input type="hidden" class="accepted" name="<?php echo $registered_container['id']; ?>[item]" value="<?php isset($_REQUEST['id']) ? esc_attr_e($_REQUEST['id']) : esc_attr_e('all', 'bizzthemes'); ?>" />
				
				</div><!-- END .container-area -->
<?php
			$i++;
			}
?>
		</div><!-- /#post-body-content -->
	</div>
	</div>
	</div>
	<form action="" method="post"><?php wp_nonce_field( 'save-sidebar-widgets', '_wpnonce_widgets', false ); ?></form>
	<br class="clear" />
<?php
		// options footer
		bizzthemes_options_footer();

	do_action( 'sidebar_admin_page' );

}

function bizz_grid_tree($grid_array,$tree=''){	
    global $wp_registered_widget_updates, $wp_registered_sidebars, $wp_registered_widgets;				
	
	// get the first key
    $first_array = array_shift(array_keys($grid_array));
	// get the last key
	$last_array = array_pop(array_keys($grid_array));
	
	// print_r($grid_array);
	
	$grid_count = count($grid_array);
	if ($grid_count == 2)
		$grid_class = 'half';
	elseif ($grid_count == 3)
		$grid_class = 'third';
	elseif ($grid_count == 4)
		$grid_class = 'fourth';
	elseif ($grid_count == 5)
		$grid_class = 'fifth';
	elseif ($grid_count == 6)
		$grid_class = 'sixth';
	else
		$grid_class = 'full';

	// loop through all grids
	foreach ( $grid_array as $grid => $registered_grid ) {
	
	$aog = ($tree && $first_array == $grid) ? ' alpha' : ''; #first
	$aog .= ($tree && $last_array == $grid) ? ' omega' : ''; #last
	$aog .= ($tree && $first_array == $grid && $last_array == $grid) ? ' gama' : ''; #first is last
	$grid_class = ( isset($registered_grid['full']) ) ? 'full' : $grid_class; #full nested grid?
	$grid_class .= ( isset($registered_grid['full']) && $first_array == $grid ) ? ' first' : ''; #full nested grid?	
?>
		<div class="<?php echo $grid_class.$aog; ?>">
<?php	
			// loop through all sidebars			
			$i = 0;
			foreach ( $wp_registered_sidebars as $sidebar => $registered_sidebar ) {
				if ( 'bizz_inactive_widgets' == $sidebar )
					continue;
				$closed = ' closed';
														
				if ($grid == $registered_sidebar['grid']) {
?>
					<div class="widgets-holder-wrap sidebar-area">
						<div class="sidebar-name">
							<div class="sidebar-name-arrow"><br /></div>
							<h3>
								<?php echo esc_html( $registered_sidebar['name'] ); ?>
								<span><img src="<?php echo esc_url( admin_url( 'images/wpspin_dark.gif' ) ); ?>" class="ajax-feedback" title="" alt="" /></span>
							</h3>
						</div>
						<?php wp_list_widget_controls( $sidebar ); ?>
					</div><!-- /.widgets-holder-wrap -->
<?php
				}

			$i++;
			}

			// nested sidebars
			if (is_array($registered_grid['tree']))
			    bizz_grid_tree($registered_grid['tree'],true);

?>
		</div><!-- /.grid class -->
<?php
	}

}

// WIDGET LOGIC
function bizz_frame_widget_logic($condition_logic_array = ''){
	global $themeid;
	
	$condition_logic = $condition_logic_array;
		
	// query available widgets
	$args = array(
		'post_type' 	=> 'bizz_widget',
		'numberposts' 	=> -1,
		'orderby' 		=> 'modified',
		'order' 		=> 'ASC',
		'post_status' 	=> 'publish'
	);
	$old_widgetlogics = get_posts($args);
	foreach ($old_widgetlogics as $grids) :
		$registered_theme 	= $grids->post_content_filtered;
		$current_theme		= $themeid;
		if ( ( isset($registered_theme) ) && ( $registered_theme == $themeid ) || ( $registered_theme == '' ) ) { # different theme?
			$_old_widgetlogic = bizz_reverse_escape( $grids->post_content );
			$old_widgetlogic[] = unserialize($_old_widgetlogic); # array of available widgets
		}
	endforeach;
		
	// build available widget logic
	if (!empty($old_widgetlogic)){
		foreach ( $old_widgetlogic as $_key => $_value) {
			$_key = $_key.',';
			// Show all widgets: start
			if (!isset($_REQUEST['tab'])){ 
				(!isset($_widget)) ? $_widget=$_key : $_widget.=$_key;
				(!isset($_parent)) ? $_parent='false,' : $_parent.='false,'; // parent widget?
			}
			// Show all widgets: end
			elseif (isset($_value['condition']) && $_value['condition']==$condition_logic['bizz_condition'] && $_value['item']==$condition_logic['bizz_item'] ){
				(!isset($_widget)) ? $_widget=$_key : $_widget.=$_key;
				(!isset($_parent)) ? $_parent='false,' : $_parent.='false,'; // parent widget?
			}
			elseif (isset($_value['condition']) && $_value['condition']==$condition_logic['bizz_subtabsub'] && $_value['item']==$condition_logic['bizz_subtab']){
				(!isset($_widget)) ? $_widget=$_key : $_widget.=$_key;
				(!isset($_parent)) ? $_parent='true,' : $_parent.='true,'; // parent widget?
			}
			elseif (isset($_value['condition']) && $_value['condition']==$condition_logic['bizz_subtab'] && $_value['item']==$condition_logic['bizz_item']){
				(!isset($_widget)) ? $_widget=$_key : $_widget.=$_key;
				(!isset($_parent)) ? $_parent='true,' : $_parent.='true,'; // parent widget?
			}
			elseif (isset($_value['condition']) && $_value['condition']==$condition_logic['bizz_tab'] && $_value['item']==$condition_logic['bizz_item']){
				(!isset($_widget)) ? $_widget=$_key : $_widget.=$_key;
				(!isset($_parent)) ? $_parent='true,' : $_parent.='true,'; // parent widget?
			}
			elseif (isset($_value['condition']) && $_value['condition']==$condition_logic['bizz_tab'] && $_value['item']=='all'){
				(!isset($_widget)) ? $_widget=$_key : $_widget.=$_key;
				(!isset($_parent)) ? $_parent='true,' : $_parent.='true,'; // parent widget?
			}
			elseif (isset($_value['condition']) && $_value['condition']=='is_index' && $_value['item']=='all'){
				(!isset($_widget)) ? $_widget=$_key : $_widget.=$_key;
				(!isset($_parent)) ? $_parent='true,' : $_parent.='true,'; // parent widget?
			}
		}

		if(isset($_widget)){
			$_parent = substr_replace($_parent ,"",-1); // remove last comma
			$available_parents = explode(",",$_parent);
			$available_widgets = split(",",$_widget);
			$available_widgets = array_diff($available_widgets, array(""));
			$available_widgets = array_combine($available_widgets,$available_parents);
			foreach ( $available_widgets as $key => $value) {
				if($old_widgetlogic[$key]['parent']=='true' && isset($old_widgetlogic[$key]['show']) && $old_widgetlogic[$key]['show']!='true'){
					(!isset($hidden)) ? $hidden=$key.',' : $hidden.=$key.',';
				}
			}
			
			// print_r($available_widgets);
			
			// FILTER CUSTOM DEFINES WIDGETS: Start
			$bizz_custom_widgets_filters = apply_filters('bizz_custom_widgets_filter', '');
			foreach( (array) $bizz_custom_widgets_filters as $key => $val)
				echo "\t<input type=\"hidden\" class=\"available_widget\" name=\"false\" value=\"" .  $val . "\" />\n";
			// FILTER CUSTOM DEFINES WIDGETS: End
			
			if(isset($hidden)){
				$_hidden = substr_replace($hidden ,"",-1); // remove last comma
				$_hidden = explode(",",$_hidden);
				$flip_hidden = array_flip($_hidden);
				// get hidden widgets IDs
				foreach ( $old_widgetlogic as $key => $value) {
					if(isset($flip_hidden[$key])){
						(!isset($_excluded)) ? $_excluded = $value['widget-id'].',' : $_excluded .= $value['widget-id'].',';
					}
				}
				$_excluded = substr_replace($_excluded ,"",-1); // remove last comma
				$_excluded = explode(",",$_excluded);
			}
			// spit out available widgets for this template			
			foreach ( $old_widgetlogic as $key => $value) {
				
				// set ID for excluded widgets
				$_exclude = (isset($hidden) && in_array($value['widget-id'], $_excluded)) ? $value['widget-id'] : '999999999'; # just some impossible number
				
				// available widgets
				if (array_key_exists($key, $available_widgets) && $value['widget-id'] != $_exclude )
					echo "\t<input type=\"hidden\" class=\"available_widget\" name=\"" . $available_widgets[$key] . "\" value=\"" . $value['widget-id'] . "\" />\n";
				// closed widgets
				if (array_key_exists($key, $available_widgets) && $value['widget-id'] == $_exclude )
					echo "\t<input type=\"hidden\" class=\"hidden_widget\" name=\"" . $available_widgets[$key] . "\" value=\"" . $value['widget-id'] . "\" />\n";

			}
		}
		else
			return false;

	} 
	else
		return false;

}

// GRID LOGIC
function bizz_frame_grid_logic($condition_logic_array = ''){
	global $bizz_registered_grids, $themeid;

	$condition_logic = $condition_logic_array;
	$args = array(
		'post_type'		=> 'bizz_grid',
		'numberposts'	=> -1,
		'orderby'		=> 'modified',
		'order'			=> 'ASC',
		'post_status'	=> 'publish'
	);
	$bizz_old_grids = get_posts($args);
	foreach ($bizz_old_grids as $grids) :
		(!isset($_condition)) ? $_condition=$grids->post_excerpt.',' : $_condition.=$grids->post_excerpt.',';
		(!isset($_item)) ? $_item=$grids->post_title.',' : $_item.=$grids->post_title.',';
	endforeach;
	
	// print_r($bizz_old_grids);
								
	if (!empty($bizz_old_grids)){
		
		// define all saved conditions and items
		$_condition 	= substr_replace($_condition ,"",-1); // remove last comma
		$_condition 	= explode(",",$_condition);
		$_item 			= substr_replace($_item ,"",-1); // remove last comma
		$_item 			= explode(",",$_item);
		
		/* Define which templates are active: (array)conditions, (array)items, condition, item */
		$level_one 		= bizz_is_template($_condition, $_item, $condition_logic['bizz_condition'], $condition_logic['bizz_item']);
		$level_two_one 	= bizz_is_template($_condition, $_item, $condition_logic['bizz_subtabsub'], $condition_logic['bizz_item']);
		$level_two_two 	= bizz_is_template($_condition, $_item, $condition_logic['bizz_subtabsub'], $condition_logic['bizz_subtab']);
		$level_three 	= bizz_is_template($_condition, $_item, $condition_logic['bizz_subtab'], $condition_logic['bizz_item']);
		$level_four 	= bizz_is_template($_condition, $_item, $condition_logic['bizz_tab'], 'all');
		$level_five 	= in_array('is_index', $_condition) && in_array('all', $_item);
				
		// list condition and item for current template
		if ( $level_one ){
		    $this_condition  = $condition_logic['bizz_condition'];
			$this_item       = $condition_logic['bizz_item'];
			// print_r( 'level one<br/>' );
		}
		elseif ( $level_two_one ){
		    $this_condition  = $condition_logic['bizz_subtabsub'];
			$this_item       = $_item[array_search($condition_logic['bizz_subtab'], $_item)];
			// print_r( 'level two<br/>' );
		}
		elseif ( $level_two_two ){
		    $this_condition  = $condition_logic['bizz_subtabsub'];
			$this_item       = $_item[array_search($condition_logic['bizz_subtab'], $_item)];
			// print_r( 'level two<br/>' );
		}
		elseif ( $level_three ){
		    $this_condition  = $condition_logic['bizz_subtab'];
			$this_item       = $_item[array_search($condition_logic['bizz_subtab'], $_condition)];
			// print_r( 'level three<br/>' );
		}
		elseif ( $level_four ){
		    $this_condition  = $condition_logic['bizz_tab'];
			$this_item       = $_item[array_search($condition_logic['bizz_tab'], $_condition)];
			// print_r( 'level four<br/>' );
		}
		elseif ( $level_five ){
		    $this_condition  = 'is_index';
			$this_item       = $_item[array_search('is_index', $_condition)];
			// print_r( 'level five<br/>' );
		}
		
		/*
		echo '<br/>';
		print_r($_condition);
		echo '<br/>';
		print_r($_item);		
		echo '--------------------------<br/>';
		print_r($this_condition);
		echo '<br/>';
		print_r($this_item);
		*/
									
		if(isset($this_condition)){
			foreach ($bizz_old_grids as $grids) :
				if($grids->post_excerpt==$this_condition && $grids->post_title==$this_item){
					$_layout 	= bizz_reverse_escape( $grids->post_content );
					$_layout 	= unserialize( $_layout );
					$_theme 	= $grids->post_content_filtered;
				}
			endforeach;
		}
		if(isset($_layout)){
			$old_containers = $_layout;
		} else {
			$old_containers = $bizz_registered_grids;
		}
		
		// print_r($old_containers);
		
		// unset customized & unregistered containers / areas
		$array_keys1 		= (isset($old_containers) && is_array($old_containers)) ? array_keys($old_containers) : '';
		$array_keys2 		= (isset($bizz_registered_grids) && is_array($bizz_registered_grids)) ? array_keys($bizz_registered_grids) : '';
		$array_keys_match 	= (is_array($array_keys1) && is_array($array_keys2)) ? array_diff($array_keys1, $array_keys2) : '';
		if ( !empty($array_keys_match) ){
			foreach ( (array) $array_keys_match as $value)
				unset( $old_containers[$value] ); #unset unregistered areas
		}
		
		// check if arrays match (different theme?)
		$registered_theme 	= ( isset($_theme) ) ? $_theme : '';
		$current_theme		= $themeid;
		if ( ( $registered_theme != $current_theme ) && ( $registered_theme != '' ) )
			$old_containers = $bizz_registered_grids;
									
	}

	if (empty($old_containers))
		$bizz_registered_grids = $bizz_registered_grids; // default grid
	else {
		$array1 = $bizz_registered_grids;
		$array2 = $old_containers;
		$array3 = bizz_array_merge_recursive_distinct($array1, $array2);
		$array4 = array_merge($array2,$array3);
		$bizz_registered_grids = $array4; // saved grid
		
		/*
		print_r($array1);
		print_r('<br/>--1--<br/>');
		print_r($array2);
		print_r('<br/>--2--<br/>');
		print_r($array3);
		print_r('<br/>--3--<br/>');
		print_r($array4);
		print_r('<br/>--4--<br/>');
		*/
		
	}
							
	return $bizz_registered_grids;
}

// REGISTER LAYOUTS POST TYPES
add_action( 'init', 'bizz_layouts_post_type' );
function bizz_layouts_post_type() {
	
	$args = array(
        'public'				=> false,
        'show_ui'				=> false,
        'capability_type'		=> 'page',
        'hierarchical'			=> false,
		'publicly_queryable'	=> false,
		'exclude_from_search'	=> true,
		'can_export'			=> false
    );
	
	register_post_type( 'bizz_widget' , $args );
	register_post_type( 'bizz_grid' , $args );
   
}

// REGISTER GRIDS
global $bizz_registered_grids;
$bizz_registered_grids = array();

function bizz_register_grids($args = array()) {
    global $bizz_registered_grids;
	
	$grids = wp_parse_args($args, $args);
	$bizz_registered_grids[$grids['id']] = $grids;
	return $grids['id'];
	
}

// UNREGISTER GRIDS
function bizz_unregister_grids( $name ) {
    global $bizz_registered_grids;
	if ( isset( $bizz_registered_grids[$name] ) )
	    unset( $bizz_registered_grids[$name] );
}

/* Is template hierarchy check */
/*------------------------------------------------------------------*/
function bizz_is_template($_condition = '', $_item = '', $condition = 'is_index', $item = 'all') {
    $one_keys = array_keys($_item, $item);
	foreach ($one_keys as $key => $value){
		$one_keyx[] = $_condition[$value];
	}
	if ( isset($one_keyx) && is_array($one_keyx) )
	    return in_array($condition, $one_keyx);
}

/* Menu Hierarchy tabs */
/*------------------------------------------------------------------*/
function bizz_menu_tabs( $layout_tabs = '' ){
	global $bizz_package, $wp_query, $wp_rewrite;
	
	echo '<ul>';
	foreach( (array) $layout_tabs as $key => $value ) {
		$url_arg = array(
			'tab' 		=> ( isset($value['tab']) ) ? $value['tab'] : '',
			'subtab' 	=> ( isset($value['subtab']) ) ? $value['subtab'] : '',
			'subtabsub' => ( isset($value['subtabsub']) ) ? $value['subtabsub'] : '',
			'condition' => ( isset($value['condition']) ) ? $value['condition'] : '',
			'id' 		=> ( isset($value['id']) ) ? $value['id'] : ''
		);
		if ( !isset($value['tab']) )
			unset($url_arg['tab']);
		if ( !isset($value['subtab']) )
			unset($url_arg['subtab']);
		if ( !isset($value['subtabsub']) )
			unset($url_arg['subtabsub']);
		if ( !isset($value['condition']) )
			unset($url_arg['condition']);
		if ( !isset($value['id']) )
			unset($url_arg['id']);
		
		$post_title = ( isset($value['title']) ) ? ' title="'.$value['title'].'"' : '';
		$active_css = ( isset($_REQUEST['id']) && isset($value['id']) && $value['id'] == $_REQUEST['id'] && $value['condition'] == $_REQUEST['condition'] ) ? 'class="active"' : '';
		$tab_class	= ( isset($value['class']) ) ? ' '.$value['class'] : '';
		$tab_key	= ( isset($key) ) ? ' key_'.$key : '';
		$tab_id		= ( isset($value['tab_id']) ) ? ' id="'.$value['tab_id'].'"' : '';
		$tab_name	= ( isset($value['name']) ) ? $value['name'] : '';
		
		if ($bizz_package != 'ZnJlZQ==')
			$tab_url = add_query_arg( $url_arg, admin_url( 'admin.php?page=bizz-layout' ) );
		else
			$tab_url = site_url() . '/wp-admin/admin.php?page=bizz-license" onclick="return confirm(\'To edit specific templates, please Upgrade to Standard or Agency theme version.\');"';
			
		echo '<li class="menu-tab'.$tab_class.$tab_key.'"'.$tab_id.'>';
		echo '<a '.$active_css.$post_title.' href="'.$tab_url.'">'.$tab_name.'</a>';
		
		// nested
		if ( isset($value['tree']) && is_array( $value['tree'] ) )
			bizz_menu_tabs( $value['tree'] );
			
		echo '</li>';
		
	}
	echo '</ul>';
	
}

// LAYOUT TABS
function bizz_layout_tabs(){
    $layout_tabs = array(
	    'index' => array(
		    'condition'	=> 'is_index',
			'tab'		=> 'is_index',
			'name'		=> __('Site-wide', 'bizzthemes'),
			'id'		=> 'all',
			'tree' 		=> array(
				'home' => array(
					'condition' => 'is_front_page',
					'tab'		=> 'is_front_page',
					'name'		=> __('Home', 'bizzthemes'),
					'id'		=> 'all',
					'class'		=> 'home_tab',
					'tree' 		=> ''
				),
				'single' => array(
					'condition' => 'is_single',
					'tab'		=> 'is_single',
					'name'		=> __('Single', 'bizzthemes'),
					'id'		=> 'all',
					'class'		=> 'single_tab',
					'tree' 		=> bizz_layout_tabs_type('is_single')
				),
				'archive' => array(
					'condition' => 'is_archive',
					'tab'		=> 'is_archive',
					'name'		=> __('Archive', 'bizzthemes'),
					'id'		=> 'all',
					'class'		=> 'archive_tab',
					'tree' 		=> bizz_layout_tabs_type('is_archive')
				),
				'search' => array(
					'condition' => 'is_search',
					'tab'		=> 'is_search',
					'name'		=> __('Search', 'bizzthemes'),
					'id'		=> 'all',
					'class'		=> 'search_tab',
					'tree' 		=> ''
				),
				'404' => array(
					'condition' => 'is_404',
					'tab'		=> 'is_404',
					'name'		=> __('404', 'bizzthemes'),
					'id'		=> 'all',
					'class'		=> 'fourofour_tab',
					'tree' 		=> ''
				)
			)
		)
	);
	
	return $layout_tabs;
}

// LAYOUT TYPES
function bizz_layout_tabs_type( $option = '' ) {
	global $wp_version;
	
	if ($option == 'is_archive') {
		// Post type archives (3.1+ only)
		if (version_compare($wp_version, '3.0.9', '>=')) {
			$post_types = get_post_types(array(),'objects'); 
			$post_typex = Array();
			$i = 0;
			foreach ($post_types as $post_type ) {
				$pt_labels = $post_type->labels;
				if (
					$post_type->name == 'revision' 		|| 
					$post_type->name == 'nav_menu_item'	|| 
					$post_type->name == 'page'			|| 
					$post_type->name == 'attachment'	|| 
					$post_type->name == 'bizz_widget'	|| 
					$post_type->name == 'bizz_grid'
				)
				continue;
				$post_typex[$i]['tab']			= 'is_archive';
				$post_typex[$i]['subtab']		= $post_type->name;
				$post_typex[$i]['condition']	= 'is_pt_archive';
				$post_typex[$i]['id']			= $post_type->name;
				$post_typex[$i]['name']			= 'Post type: ' . $pt_labels->name;
				$post_typex[$i]['tree']			= bizz_layout_archive('is_archive', $post_type->name);
				$layout_tabs 					= $post_typex;
			$i++;
			}
		}
	}
	elseif ($option == 'is_single') {
		$post_types = get_post_types(array(),'objects'); 
		$post_typex = Array();
		$i = 0;
		foreach ($post_types as $post_type ) {
			$pt_labels = $post_type->labels;
			if ($post_type->name=='revision' || $post_type->name=='nav_menu_item' || $post_type->name=='bizz_widget' || $post_type->name=='bizz_grid')
				continue;
			$post_typex[$i]['tab']			= 'is_single';
			$post_typex[$i]['subtab']		= $post_type->name;
			$post_typex[$i]['condition']	= 'is_singular';
			$post_typex[$i]['id']			= $post_type->name;
			$post_typex[$i]['name']			= 'Post type: ' . $pt_labels->name;
			if ( isset($_REQUEST['condition']) && (($_REQUEST['condition']=='is_singular' && $_REQUEST['id'] == $post_type->name) || ($_REQUEST['condition']=='is_single' && isset($_REQUEST['subtab']) && $_REQUEST['subtab'] == $post_type->name)) )
				$post_typex[$i]['tree']			= bizz_layout_single('is_single', $post_type->name);
			else
				$post_typex[$i]['tree']			= array( 'ajax' => array( 'tab'	=> 'ajaxed' ));
			$post_typex[$i]['tab_id']		= $post_type->name;
			$layout_tabs 					= $post_typex;
		$i++;
		}
	}
	return $layout_tabs;

}

// LAYOUT SINGLE POSTS
function bizz_layout_single( $posts_tab = '', $posts_subtab = '', $posts_paged = 1 ) {

	$post_status = ($posts_subtab == 'attachment') ? 'inherit' : 'publish';
	$args = array(
		'post_type'			=> $posts_subtab,
		'post_status'		=> $post_status,
		'posts_per_page'	=> 200,
		'paged'				=> $posts_paged,
		'orderby'			=> 'title',
		'order'				=> 'ASC'
	);
	$layout_posts = query_posts( $args );
	$i = 0;
	foreach($layout_posts as $post) {
		setup_postdata($post);
		$post_name = (strlen($post->post_title) > 23) ? substr($post->post_title, 0, 23) . '...' : $post->post_title;
		$post_mime_type = ( !empty($post->post_mime_type) ) ? ' ('.$post->post_mime_type.')' : '';
			
		$layout_post[$i]['tab']			= 'is_single';
		$layout_post[$i]['subtab']		= $posts_subtab;
		$layout_post[$i]['subtabsub']	= 'is_singular';
		$layout_post[$i]['condition']	= 'is_single';
		$layout_post[$i]['id']			= $post->ID;
		$layout_post[$i]['name']		= $post_name;
		$layout_post[$i]['title']		= $post->post_title . $post_mime_type;
	$i++;
	}
	if ( isset($layout_post) )
		return $layout_post;
		
	wp_reset_query();

}

// LAYOUT ARCHIVE POSTS
function bizz_layout_archive($archives_tab = '',$archives_subtab = '') {

	if ( $archives_subtab == 'post' ) {
		$layout_archive = array(
			'date' => array(
				'tab'		=> 'is_archive',
				'subtab'	=> 'post',
				'subtabsub'	=> 'is_pt_archive',
				'condition' => 'is_date',
				'name'		=> __('Date archives', 'bizzthemes'),
				'id'		=> 'all'
			),
			'author' => array(
				'tab'		=> 'is_archive',
				'subtab'	=> 'post',
				'subtabsub'	=> 'is_pt_archive',
				'condition' => 'is_author',
				'name'		=> __('Author archives', 'bizzthemes'),
				'tree'		=> bizz_layout_author(),
				'id'		=> 'all'
			),
			'category' => array(
				'tab'		=> 'is_archive',
				'subtab'	=> 'post',
				'subtabsub'	=> 'is_pt_archive',
				'condition' => 'is_category',
				'name'		=> __('Category archives', 'bizzthemes'),
				'tree'		=> bizz_layout_category(),
				'id'		=> 'all'
			),
			'tag' => array(
				'tab'		=> 'is_archive',
				'subtab'	=> 'post',
				'subtabsub'	=> 'is_pt_archive',
				'condition' => 'is_tag',
				'name'		=> __('Tag archives', 'bizzthemes'),
				'id'		=> 'all'
			)
		);
	}
	else {
		$layout_archives = get_taxonomies('','objects'); 
		$i = 0;
		foreach($layout_archives as $taxonomy) {
			if (in_array($archives_subtab, $taxonomy->object_type)) {
				$tax_labels = $taxonomy->labels;
				$layout_archive[$i]['tab']			= 'is_archive';
				$layout_archive[$i]['subtab']		= $archives_subtab;
				$layout_archive[$i]['subtabsub']	= 'is_pt_archive';
				$layout_archive[$i]['condition']	= 'is_tax';
				$layout_archive[$i]['id']			= $taxonomy->name;
				$layout_archive[$i]['name']			= $tax_labels->name;
			}
		$i++;
		}
	}
	
	if ( isset($layout_archive) )
		return $layout_archive;

}

// SINGLE CATEGORY SELECT LAYOUT
function bizz_layout_category() {

	$layout_categories = get_categories('show_post_count=1');
	$i = 0;
	foreach($layout_categories as $category) {
		$category_name = (strlen($category->name) > 25) ? substr($category->name, 0, 25) . '...' : $category->name;
			
		$layout_category[$i]['tab']			= 'is_archive';
		$layout_category[$i]['subtab']		= 'post';
		$layout_category[$i]['subtabsub']	= 'is_category';
		$layout_category[$i]['condition']	= 'is_category';
		$layout_category[$i]['id']			= $category->term_id;
		$layout_category[$i]['name']		= $category->name;
		$layout_category[$i]['title']		= $category->name;
	$i++;
	}
	if ( isset($layout_category) )
		return $layout_category;
	
}

// SINGLE AUTHOR SELECT LAYOUT
function bizz_layout_author() {

	if (function_exists('get_users')) {
		$args = array(
			'blog_id' => $GLOBALS['blog_id'],
			'orderby' => 'nicename'
		);
		$layout_authors = get_users( $args );
		$i = 0;
		foreach($layout_authors as $user) {
			$user_name = (strlen($user->display_name) > 25) ? substr($user->display_name, 0, 25) . '...' : $user->display_name;
				
			$layout_user[$i]['tab']			= 'is_archive';
			$layout_user[$i]['subtab']		= 'post';
			$layout_user[$i]['subtabsub']	= 'is_author';
			$layout_user[$i]['condition']	= 'is_author';
			$layout_user[$i]['id']			= $user->ID;
			$layout_user[$i]['name']		= $user->display_name;
			$layout_user[$i]['title']		= $user->display_name;
		$i++;
		}
	}
	if ( isset($layout_user) )
		return $layout_user;
	
}

/* Browsing menu tabs position */
/*------------------------------------------------------------------*/
function bizz_browsing_tabs( $browsing_tabs = '' ){
		
	$tab 		= ( isset($_REQUEST['tab']) ) ? $_REQUEST['tab'] : '';
	$subtab 	= ( isset($_REQUEST['subtab']) ) ? $_REQUEST['subtab'] : '';
	$subtabsub 	= ( isset($_REQUEST['subtabsub']) ) ? $_REQUEST['subtabsub'] : '';
	$condition 	= ( isset($_REQUEST['condition']) ) ? $_REQUEST['condition'] : '';
	$id 		= ( isset($_REQUEST['id']) ) ? $_REQUEST['id'] : '';
		
	$return = '<span class="current-template">';
	// currently editing text
	$return .= '<span class="c-edit">';
	$return .= __('Currently editing: ', 'bizzthemes');
	$return .= '</span>';
	// current template
	$return .= '<span class="t-edit">';
	if ( $tab == '' )
		$return .= __('All site widgets', 'bizzthemes');
	elseif ( $condition == 'is_index' )
		$return .= __('Main template (index.php)', 'bizzthemes');
	elseif ( $condition == 'is_front_page' )
		$return .= __('Home page template', 'bizzthemes');
	elseif ( $condition == 'is_single' && $id != 'all' )
		$return .= sprintf(__('Template for single post type: %s, ID: %s', 'bizzthemes'), $subtab, $id);
	elseif ( $condition == 'is_single' )
		$return .= __('Template for all single posts', 'bizzthemes');
	elseif ( $condition == 'is_singular' )
		$return .= sprintf(__('Template for single post type: %s', 'bizzthemes'), $id);
	elseif ( $condition == 'is_archive' )
		$return .= __('Template for all archives', 'bizzthemes');
	elseif ( $condition == 'is_pt_archive' )
		$return .= sprintf(__('Template for all archives of post type: %s', 'bizzthemes'), $id);
	elseif ( $condition == 'is_date' )
		$return .= sprintf(__('Template for all date archives, post type: %s', 'bizzthemes'), $subtab);
	elseif ( $condition == 'is_author' && $id != 'all' )
		$return .= sprintf(__('Template for author ID: %s, post type: %s', 'bizzthemes'), $id, $subtab);
	elseif ( $condition == 'is_author' )
		$return .= sprintf(__('Template for all author archives, post type: %s', 'bizzthemes'), $subtab);
	elseif ( $condition == 'is_category' && $id != 'all' )
		$return .= sprintf(__('Template for category ID: %s, post type: %s', 'bizzthemes'), $id, $subtab);
	elseif ( $condition == 'is_category' )
		$return .= sprintf(__('Template for all category archives, post type: %s', 'bizzthemes'), $subtab);
	elseif ( $condition == 'is_tag' )
		$return .= sprintf(__('Template for all tag archives, post type: %s', 'bizzthemes'), $subtab);
	elseif ( $condition == 'is_tax' )
		$return .= sprintf(__('Template for archive post type: %s, taxonomy: %s', 'bizzthemes'), $subtab, $id);
	elseif ( $condition == 'is_search' )
		$return .= __('Search results template', 'bizzthemes');
	elseif ( $condition == 'is_404' )
		$return .= __('404 error template', 'bizzthemes');
	$return .= '</span>';
	
	// preview link
	$href = '';
	if ( $condition == 'is_index' )
		$href .= site_url();
	elseif ( $condition == 'is_front_page' )
		$href .= site_url();
	elseif ( $condition == 'is_single' && $id != 'all' )
		$href .= get_permalink( $id );
	elseif ( $condition == 'is_single' ) {
		$post_args = array( 'numberposts' => 1, 'orderby' => 'rand', 'post_type' => 'post' );
		$rand_post = get_posts( $post_args );
		if ($rand_post)
			$href .= get_permalink( $rand_post[0]->ID );
	}
	elseif ( $condition == 'is_singular' ) {
		$post_args = array( 'numberposts' => 1, 'orderby' => 'rand', 'post_type' => $id );
		$rand_post = get_posts( $post_args );
		if ($rand_post)
			$href .= get_permalink( $rand_post[0]->ID );
	}
	elseif ( $condition == 'is_archive' ){
		$date = get_month_link('', '');
		if ($date)
			$href .= $date;
	}
	elseif ( $condition == 'is_pt_archive' ) {
		if (function_exists('get_post_type_archive_link')) {
			$pt_link = get_post_type_archive_link( $id );
			if ($pt_link)
				$href .= $pt_link;
		}
	}
	elseif ( $condition == 'is_date' ){
		$date = get_month_link('', '');
		if ($date)
			$href .= $date;
	}
	elseif ( $condition == 'is_author' ){
		$author = get_author_posts_url(1);
		if ($author)
			$href .= $author;
	}
	elseif ( $condition == 'is_category' ){
		$terms = get_categories();
		if ($terms)
			$href .= get_category_link( intval($terms[0]->term_id) );
	}
	elseif ( $condition == 'is_tag' ){
		$terms = get_tags();
		if ($terms)
			$href .= get_tag_link( intval($terms[0]->term_id), $subtab );
	}
	elseif ( $condition == 'is_tax' ) {
		$terms = get_terms($id);
		if ($terms)
			$href .= get_term_link( intval($terms[0]->term_id), $id );
	}
	elseif ( $condition == 'is_search' )
		$href .= site_url() . '/?s=lorem';
	elseif ( $condition == 'is_404' )
		$href .= site_url() . '/?page_id=9999999999999999';
	
	if ( $href != '' )
		$return .= '<a href="'.$href.'" class="button t-preview" target="_blank" title="Live template preview">'.__('Preview &raquo;' , 'bizzthemes').'</a>';
	$return .= '</span>';
	
	return $return;
	
}

/* Widgets with no condition under Site-wide by default */
/*------------------------------------------------------------------*/
add_action( 'sidebar_admin_setup', 'bizz_default_widget_screen');
function bizz_default_widget_screen() {
	global $themeid, $wp_registered_widgets, $wp_registered_widget_controls;
	
	// DELETE WIDGET LOGIC
	if ( isset($_POST['delete_widget']) && $_POST['delete_widget'] ) {
	
		$args = array(
			'post_type' 	=> 'bizz_widget',
			'numberposts' 	=> -1,
			'post_status' 	=> 'publish'
		);
		$bizz_old_widgets = get_posts( $args );
		
		foreach ($bizz_old_widgets as $widgets) :
			$old_widget_id = unserialize( $widgets->post_content );
			if( $old_widget_id['widget-id'] == $_POST['widget-id'] ){
				wp_delete_post( $widgets->ID, true );
			}
		endforeach;
			
	}
	// ADD WIDGET LOGIC
	elseif ( !empty($_POST['add_new']) ) {

		$bizz_new_widget['widget-id'] = $_POST['widget-id'];
		$bizz_new_widget['condition'] = 'is_index';
		$bizz_new_widget['item'] = 'all';
		$bizz_new_widget['parent'] = 'false';
		$bizz_new_widget['show'] = 'true';
		
		$args = array(
			'post_type' 				=> 'bizz_widget',
			'post_title' 				=> 'all',
			'post_excerpt' 				=> 'is_index',
			'post_content' 				=> serialize($bizz_new_widget),
			'post_content_filtered' 	=> $themeid,
			'ping_status' 				=> get_option('default_ping_status'), 
			'post_status' 				=> 'publish'
		);
		// wp_insert_post( $args );
				
	}
	
}