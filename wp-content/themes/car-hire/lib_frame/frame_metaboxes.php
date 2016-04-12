<?php
/*
Originally developed by: 	Andrew Norcross (@norcross / andrewnorcross.com)
							Jared Atchison (@jaredatch / jaredatchison.com)
							Bill Erickson (@billerickson / billerickson.net)
*/

/**
 * Initiate all meta boxes
 */
 
function metaboxes_admin_init() {
	$meta_boxes = array();
	$meta_boxes = apply_filters ( 'bizz_meta_boxes' , $meta_boxes );
	foreach ( $meta_boxes as $meta_box ) {
		$my_box = new Bizz_Meta_Box( $meta_box );
	}
}
add_action( 'init', 'metaboxes_admin_init' );

/**
 * Validate value of meta fields
 * Define ALL validation methods inside this class and use the names of these 
 * methods in the definition of meta boxes (key 'validate_func' of each field)
 */

class Bizz_Meta_Box_Validate {
	function check_text( $text ) {
		if ($text != 'hello') {
			return false;
		}
		return true;
	}
}

/**
 * Create meta boxes
 */

class Bizz_Meta_Box {
	protected $_meta_box;

	function __construct( $meta_box ) {
		if ( !is_admin() ) return;

		$this->_meta_box = $meta_box;

		$upload = false;
		foreach ( $meta_box['fields'] as $field ) {
			if ( $field['type'] == 'file' || $field['type'] == 'file_list' ) {
				$upload = true;
				break;
			}
		}
		
		$current_page = substr(strrchr($_SERVER['PHP_SELF'], '/'), 1, -4);
		
		if ( $upload && ( $current_page == 'page' || $current_page == 'page-new' || $current_page == 'post' || $current_page == 'post-new' ) )
			add_action( 'admin_head', array(&$this, 'add_post_enctype') );

		add_action( 'admin_menu', array(&$this, 'add') );
		add_action( 'save_post', array(&$this, 'save') );

		add_filter( 'bizz_show_on', array(&$this, 'add_for_id' ), 10, 2 );
		add_filter( 'bizz_show_on', array(&$this, 'add_for_page_template' ), 10, 2 );
	}

	function add_post_enctype() {
		echo '
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("#post").attr("enctype", "multipart/form-data");
			jQuery("#post").attr("encoding", "multipart/form-data");
		});
		</script>';
	}

	// Add metaboxes
	function add() {
		$this->_meta_box['context'] = empty($this->_meta_box['context']) ? 'normal' : $this->_meta_box['context'];
		$this->_meta_box['priority'] = empty($this->_meta_box['priority']) ? 'high' : $this->_meta_box['priority'];
		$this->_meta_box['show_on'] = empty( $this->_meta_box['show_on'] ) ? array('key' => false, 'value' => false) : $this->_meta_box['show_on'];
		
		foreach ( $this->_meta_box['pages'] as $page ) {
			if( apply_filters( 'bizz_show_on', true, $this->_meta_box ) )
				add_meta_box( $this->_meta_box['id'], $this->_meta_box['title'], array(&$this, 'show'), $page, $this->_meta_box['context'], $this->_meta_box['priority']) ;
		}
	}
	
	/**
	 * Show On Filters
	 * Use the 'bizz_show_on' filter to further refine the conditions under which a metabox is displayed.
	 * Below you can limit it by ID and page template
	 */
	 
	// Add for ID 
	function add_for_id( $display, $meta_box ) {
		if ( 'id' !== $meta_box['show_on']['key'] )
			return $display;

		// If we're showing it based on ID, get the current ID					
		if( isset( $_GET['post'] ) ) $post_id = $_GET['post'];
		elseif( isset( $_POST['post_ID'] ) ) $post_id = $_POST['post_ID'];
		if( !isset( $post_id ) )
			return $display;
		
		// If value isn't an array, turn it into one	
		$meta_box['show_on']['value'] = !is_array( $meta_box['show_on']['value'] ) ? array( $meta_box['show_on']['value'] ) : $meta_box['show_on']['value'];
		
		// If current page id is in the included array, display the metabox

		if ( in_array( $post_id, $meta_box['show_on']['value'] ) )
			return true;
		else
			return false;
	}
	
	// Add for Page Template
	function add_for_page_template( $display, $meta_box ) {
		if( 'page-template' !== $meta_box['show_on']['key'] )
			return $display;
			
		// Get the current ID
		if( isset( $_GET['post'] ) ) $post_id = $_GET['post'];
		elseif( isset( $_POST['post_ID'] ) ) $post_id = $_POST['post_ID'];
		if( !( isset( $post_id ) || is_page() ) ) return $display;
			
		// Get current template
		$current_template = get_post_meta( $post_id, '_wp_page_template', true );
		
		// If value isn't an array, turn it into one	
		$meta_box['show_on']['value'] = !is_array( $meta_box['show_on']['value'] ) ? array( $meta_box['show_on']['value'] ) : $meta_box['show_on']['value'];

		// See if there's a match
		if( in_array( $current_template, $meta_box['show_on']['value'] ) )
			return true;
		else
			return false;
	}
	
	// Show fields
	function show() {
	// $wp_version used for compatibility with new wp_editor() function
		global $post, $wp_version;

		// Use nonce for verification
		echo '<input type="hidden" name="wp_meta_box_nonce" value="', wp_create_nonce( basename(__FILE__) ), '" />';
		echo '<table class="form-table bizz_metabox">';

		foreach ( $this->_meta_box['fields'] as $field ) {
			// Set up blank or default values for empty ones
			if ( !isset( $field['name'] ) ) $field['name'] = '';
			if ( !isset( $field['desc'] ) ) $field['desc'] = '';
			if ( !isset( $field['std'] ) ) $field['std'] = '';
			if ( 'file' == $field['type'] && !isset( $field['allow'] ) ) $field['allow'] = array( 'url', 'attachment' );
			if ( 'file' == $field['type'] && !isset( $field['save_id'] ) )  $field['save_id']  = false;
						
			$meta = get_post_meta( $post->ID, $field['id'], 'multicheck' != $field['type'] /* If multicheck this can be multiple values */ );

			echo '<tr>';
	
			if ( $field['type'] == "title" )
				echo '<td colspan="2">';
			else {
				if( $this->_meta_box['show_names'] == true )
					echo '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>';		
				echo '<td>';
			}		
						
			switch ( $field['type'] ) {

				case 'text':
					echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" style="width:97%" />','<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'text_small':
					echo '<input class="bizz_text_small" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" /><span class="bizz_metabox_description">', $field['desc'], '</span>';
					break;
				case 'text_medium':
					echo '<input class="bizz_text_medium" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" /><span class="bizz_metabox_description">', $field['desc'], '</span>';
					break;
				case 'text_counter':
					echo '<input class="char_counter" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" style="width:97%" />';
					echo '<input readonly class="counter" type="text" name="char_count" size="3" maxlength="3" value="'.strlen($meta ? $meta : $field['std']).'" />';
					echo '<span class="bizz_metabox_description">', $field['desc'], '</span>';
					break;
				case 'text_date':
					echo '<input class="bizz_text_small bizz_datepicker" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" /><span class="bizz_metabox_description">', $field['desc'], '</span>';
					break;
				case 'text_date_timestamp':
					echo '<input class="bizz_text_small bizz_datepicker" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? date( 'm\/d\/Y', $meta ) : $field['std'], '" /><span class="bizz_metabox_description">', $field['desc'], '</span>';
					break;
				case 'text_time':
					echo '<input class="bizz_timepicker text_time" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" /><span class="bizz_metabox_description">', $field['desc'], '</span>';
					break;
				case 'date_time':
					echo '<input class="bizz_text_small bizz_datepicker" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" />';
					
					$start_time = strtotime('00:00');
					$end_time = strtotime('23:30');
					
					$by = apply_filters('bizz_minutes_increment', '15') . ' mins';
					$current = time(); 
					$add_time = strtotime('+'.$by, $current); 
					$diff = $add_time-$current; 
					
					$options = array(); 
					while ($start_time < $end_time) { 
						$options[] = $start_time; 
						$start_time += $diff; 
					}
					$options[] = $start_time;
					
					$default = '12:00';
					$existing_value_time = get_post_meta($post->ID, $field['id'].'_time', true);
					echo '<select name="', $field['id'], '_time">';
					foreach ($options as $option) {
						$option = date('H:i', $option);
						
						if ($existing_value_time)
							$checked = ($existing_value_time == $option) ? ' selected="selected"' : '';
						elseif ($option == $default)
							$checked = ' selected="selected"';
						else
							$checked = '';
						echo '<option value="', $option, '"', $checked, '>', $option, '</option>';
					}
					echo '</select>';
					echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'text_money':
					$currency = ( !empty($field['currency']) ) ? $field['currency'] : '$';
					echo $currency . ' <input class="bizz_text_money" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" /><span class="bizz_metabox_description">', $field['desc'], '</span>';
					break;
				case 'textarea':
					echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="10" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>','<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'textarea_small':
					echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>','<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'textarea_code':
					echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="10" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>','<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'textarea_counter':
					echo '<textarea class="char_counter" name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="3" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>';
					echo '<input readonly class="counter" type="text" name="char_count" size="3" maxlength="3" value="'.strlen($meta ? $meta : $field['std']).'" />';
					echo '<span class="bizz_metabox_description">', $field['desc'], '</span>';
					break;
				case 'select':
					echo '<select name="', $field['id'], '" id="', $field['id'], '">';
					foreach ($field['options'] as $option) {
						echo '<option value="', $option['value'], '"', $meta == $option['value'] ? ' selected="selected"' : '', '>', $option['name'], '</option>';
					}
					echo '</select>';
					echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'radio_inline':
					if( empty( $meta ) && !empty( $field['std'] ) ) $meta = $field['std'];
					echo '<div class="bizz_radio_inline">';
					foreach ($field['options'] as $option) {
						echo '<div class="bizz_radio_inline_option"><input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'], '</div>';
					}
					echo '</div>';
					echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'radio':
					if( empty( $meta ) && !empty( $field['std'] ) ) $meta = $field['std'];
					foreach ($field['options'] as $option) {
						echo '<p><input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'].'</p>';
					}
					echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'checkbox':
					echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
					echo '<span class="bizz_metabox_description">', $field['desc'], '</span>';
					break;
				case 'multicheck':
					echo '<ul>';
					foreach ( $field['options'] as $value => $name ) {
						echo '<li><input type="checkbox" name="', $field['id'], '[]" id="', $field['id'], '_', $value, '" value="', $value, '"', in_array( $value, $meta ) ? ' checked="checked"' : '', ' /><label for="', $field['id'], '_', $value, '">', $name, '</label></li>';
					}
					echo '</ul>';
					echo '<span class="bizz_metabox_description">', $field['desc'], '</span>';					
					break;		
				case 'title':
					echo '<h5 class="bizz_metabox_title">', $field['name'], '</h5>';
					echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'wysiwyg':
					if( function_exists( 'wp_editor' ) )
						wp_editor( $meta ? $meta : $field['std'], $field['id'], isset( $field['options'] ) ? $field['options'] : array() );
					else {
						echo '<div id="poststuff" class="meta_mce">';
						echo '<div class="customEditor"><textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="7" style="width:97%">', $meta ? wpautop($meta, true) : '', '</textarea></div>';
						echo '</div>';
					}
			        	echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'taxonomy_select':
					echo '<select name="', $field['id'], '" id="', $field['id'], '">';
					$names= wp_get_object_terms( $post->ID, $field['taxonomy'] );
					$terms = get_terms( $field['taxonomy'], 'hide_empty=0' );
					foreach ( $terms as $term ) {
						if (!is_wp_error( $names ) && !empty( $names ) && !strcmp( $term->slug, $names[0]->slug ) ) {
							echo '<option value="' . $term->slug . '" selected>' . $term->name . '</option>';
						} else {
							echo '<option value="' . $term->slug . '  ' , $meta == $term->slug ? $meta : ' ' ,'  ">' . $term->name . '</option>';
						}
					}
					echo '</select>';
					echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'taxonomy_radio':
					$names= wp_get_object_terms( $post->ID, $field['taxonomy'] );
					$terms = get_terms( $field['taxonomy'], 'hide_empty=0' );
					foreach ( $terms as $term ) {
						if ( !is_wp_error( $names ) && !empty( $names ) && !strcmp( $term->slug, $names[0]->slug ) )
							echo '<p><input type="radio" name="', $field['id'], '" value="'. $term->slug . '" checked>' . $term->name . '</p>';
						else
							echo '<p><input type="radio" name="', $field['id'], '" value="' . $term->slug . '  ' , $meta == $term->slug ? $meta : ' ' ,'  ">' . $term->name .'</p>';
					}
					echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'taxonomy_multicheck':
					echo '<ul>';
					$names = wp_get_object_terms( $post->ID, $field['taxonomy'] );
					$terms = get_terms( $field['taxonomy'], 'hide_empty=0' );					
					foreach ($terms as $term) {
						echo '<li><input type="checkbox" name="', $field['id'], '[]" id="', $field['id'], '_', $term->term_id, '" value="', $term->name , '"'; 
						foreach ($names as $name) {
							if ( $term->term_id == $name->term_id ){ echo ' checked="checked" ';};
						}
						echo' /><label for="', $field['id'], '_', $term->term_id, '">', $term->name , '</label></li>';
					}
					echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
				break;
				case 'file_list':
					echo '<input id="upload_file" type="text" size="36" name="', $field['id'], '" value="" />';
					echo '<input class="upload_button button" type="button" value="'.__('Upload File').'" />';
					echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
						$args = array(
								'post_type' => 'attachment',
								'numberposts' => null,
								'post_status' => null,
								'post_parent' => $post->ID
							);
							$attachments = get_posts($args);
							if ($attachments) {
								echo '<ul class="attach_list">';
								foreach ($attachments as $attachment) {
									echo '<li>'.wp_get_attachment_link($attachment->ID, 'thumbnail', 0, 0, __('Download'));
									echo '<span>';
									echo apply_filters('the_title', '&nbsp;'.$attachment->post_title);
									echo '</span></li>';
								}
								echo '</ul>';
							}
						break;
				case 'file':
					$input_type_url = "hidden";
					if ( 'url' == $field['allow'] || ( is_array( $field['allow'] ) && in_array( 'url', $field['allow'] ) ) )
						$input_type_url="text";
					echo '<input class="upload_file" type="' . $input_type_url . '" size="45" id="', $field['id'], '" name="', $field['id'], '" value="', $meta, '" />';
					echo '<input class="upload_button button" type="button" value="'.__('Upload File').'" />';
					echo '<input class="upload_file_id" type="hidden" id="', $field['id'], '_id" name="', $field['id'], '_id" value="', get_post_meta( $post->ID, $field['id'] . "_id",true), '" />';					
					echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
					echo '<div id="', $field['id'], '_status" class="bizz_upload_status">';	
						if ( $meta != '' ) { 
							$check_image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $meta );
							if ( $check_image ) {
								echo '<div class="img_status">';
								echo '<img src="', $meta, '" alt="" />';
								echo '<a href="#" class="remove_file_button" title="'.__('Remove file').'" rel="', $field['id'], '">'.__('Remove file').'</a>';
								echo '</div>';
							} else {
								$parts = explode( "/", $meta );
								for( $i = 0; $i < sizeof( $parts ); ++$i ) {
									$title = $parts[$i];
								} 
								echo ''.__('File:').' <strong>', $title, '</strong>&nbsp;&nbsp;&nbsp; (<a href="', $meta, '" target="_blank" rel="external">'.__('Download').'</a> / <a href="# title="'.__('Remove').'" class="remove_file_button" rel="', $field['id'], '">'.__('Remove').'</a>)';
							}	
						}
					echo '</div>'; 
				break;
				default:
					do_action('bizz_render_' . $field['type'] , $field, $meta);
			}
			
			echo '</td>','</tr>';
		}
		echo '</table>';
	}

	// Save data from metabox
	function save( $post_id)  {
		global $wp_version;
		// verify nonce
		if ( ! isset( $_POST['wp_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['wp_meta_box_nonce'], basename(__FILE__) ) )
			return $post_id;

		// check autosave
		if ( defined('DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		// check permissions
		if ( 'page' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ) )
				return $post_id;
		}
		elseif ( !current_user_can( 'edit_post', $post_id ) )
			return $post_id;

		foreach ( $this->_meta_box['fields'] as $field ) {
			$name = $field['id'];
			$old = get_post_meta( $post_id, $name, 'multicheck' != $field['type'] /* If multicheck this can be multiple values */ );
			$new = isset( $_POST[$field['id']] ) ? $_POST[$field['id']] : null;

			// wpautop() should not be needed with version 3.3 and later
			if ( $field['type'] == 'wysiwyg' && !function_exists( 'wp_editor' ) )
				$new = wpautop($new);
			
			if ( in_array( $field['type'], array( 'taxonomy_select', 'taxonomy_radio', 'taxonomy_multicheck' ) ) )
				$new = wp_set_object_terms( $post_id, $new, $field['taxonomy'] );	

			if ( ($field['type'] == 'textarea') || ($field['type'] == 'textarea_small') )
				$new = htmlspecialchars( $new );

			if ( ($field['type'] == 'textarea_code') )
				$new = htmlspecialchars_decode( $new );
			
			if ( $field['type'] == 'text_date_timestamp' )
				$new = strtotime( $new );
			
			$new = apply_filters('bizz_validate_' . $field['type'], $new, $post_id, $field);			
			
			// validate meta value
			if ( isset( $field['validate_func']) ) {
				$ok = call_user_func( array( 'Bizz_Meta_Box_Validate', $field['validate_func']), $new );
				if ( $ok === false ) // pass away when meta value is invalid
					continue;
				
			} elseif ( 'multicheck' == $field['type'] ) {
				// Do the saving in two steps: first get everything we don't have yet
				// Then get everything we should not have anymore
				if ( empty( $new ) )
					$new = array();

				$aNewToAdd = array_diff( $new, $old );
				$aOldToDelete = array_diff( $old, $new );
				foreach ( $aNewToAdd as $newToAdd )
					add_post_meta( $post_id, $name, $newToAdd, false );
				
				foreach ( $aOldToDelete as $oldToDelete )
					delete_post_meta( $post_id, $name, $oldToDelete );
				
			} 
			elseif ( $new && $new != $old )
				update_post_meta( $post_id, $name, $new );
			elseif ( '' == $new && $old )
				delete_post_meta( $post_id, $name, $old );
			
			if ( 'file' == $field['type'] ) {
				$name = $field['id'] . "_id";
				$old = get_post_meta( $post_id, $name, 'multicheck' != $field['type'] /* If multicheck this can be multiple values */ );
				if ( isset($field['save_id']) )
					$new = isset( $_POST[$name] ) ? $_POST[$name] : null;
				else
					$new = "";

				if ( $new && $new != $old )
					update_post_meta( $post_id, $name, $new );
				elseif ( '' == $new && $old )
					delete_post_meta( $post_id, $name, $old );
				
			}
			
			if ( 'date_time' == $field['type'] ) {
				$name = $field['id'] . "_time";
				$old = get_post_meta( $post_id, $name, 'multicheck' != $field['type'] /* If multicheck this can be multiple values */ );
				$new = isset( $_POST[$name] ) ? $_POST[$name] : null;

				if ( $new && $new != $old )
					update_post_meta( $post_id, $name, $new );
				elseif ( '' == $new && $old )
					delete_post_meta( $post_id, $name, $old );
				
			}
		}
	}
}

/**
 * Adding scripts and styles
 */

function bizz_metabox_scripts( $hook ) {
  	if ( $hook == 'post.php' OR $hook == 'post-new.php' OR $hook == 'page-new.php' OR $hook == 'page.php' ) {
		wp_register_script( 'bizz-metabox', BIZZ_FRAME_SCRIPTS . '/metaboxes.js', array( 'jquery','media-upload','thickbox' ) );
		wp_enqueue_script( 'bizz-metabox' );
  	}
}
add_action( 'admin_enqueue_scripts', 'bizz_metabox_scripts', 10, 1 );

function editor_admin_init( $hook ) {
	if ( $hook == 'post.php' OR $hook == 'post-new.php' OR $hook == 'page-new.php' OR $hook == 'page.php' ) {
		wp_enqueue_script( 'word-count' );
		wp_enqueue_script( 'post' );
		wp_enqueue_script( 'editor' );
	}
}
add_action( 'admin_init', 'editor_admin_init' );

function editor_admin_head( $hook ) {
	if ( $hook == 'post.php' OR $hook == 'post-new.php' OR $hook == 'page-new.php' OR $hook == 'page.php' )
  		wp_editor();
}
add_action( 'admin_head', 'editor_admin_head' );

function bizz_editor_footer_scripts() { ?>
	<script type="text/javascript">
	/* <![CDATA[ */
	jQuery(function($) {
		var i=1;
		$('.customEditor textarea').each(function(e) {
			var id = $(this).attr('id');
			if (!id) {
				id = 'customEditor-' + i++;
				$(this).attr('id',id);
			}
			tinyMCE.execCommand('mceAddControl', false, id);
		});
	});
	/* ]]> */
	</script>
<?php 
	if ( isset( $_GET['bizz_force_send'] ) && 'true' == $_GET['bizz_force_send'] ) {
		$label = $_GET['bizz_send_label']; 
		if ( empty( $label ) ) $label="Select File";
?>	
		<script type="text/javascript">
			jQuery(function($) {
				$('td.savesend input').val('<?php echo $label; ?>');
			});
		</script>
<?php 
	}
}
add_action( 'admin_print_footer_scripts', 'bizz_editor_footer_scripts', 99 );

// Force 'Insert into Post' button from Media Library 
add_filter( 'get_media_item_args', 'bizz_force_send' );
function bizz_force_send( $args ) {
		
	// if the Gallery tab is opened from a custom meta box field, add Insert Into Post button	
	if ( isset( $_GET['bizz_force_send'] ) && 'true' == $_GET['bizz_force_send'] )
		$args['send'] = true;
	
	// if the From Computer tab is opened AT ALL, add Insert Into Post button after an image is uploaded	
	if ( isset( $_POST['attachment_id'] ) && '' != $_POST["attachment_id"] ) {
		
		$args['send'] = true;		

		// TO DO: Are there any conditions in which we don't want the Insert Into Post 
		// button added? For example, if a post type supports thumbnails, does not support
		// the editor, and does not have any bizz file inputs? If so, here's the first
		// bits of code needed to check all that.
		// $attachment_ancestors = get_post_ancestors( $_POST["attachment_id"] );
		// $attachment_parent_post_type = get_post_type( $attachment_ancestors[0] );
		// $post_type_object = get_post_type_object( $attachment_parent_post_type );

	}		
	
	// change the label of the button on the From Computer tab
	if ( isset( $_POST['attachment_id'] ) && '' != $_POST["attachment_id"] ) {

		echo '
			<script type="text/javascript">
				function bizzGetParameterByNameInline(name) {
					name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
					var regexS = "[\\?&]" + name + "=([^&#]*)";
					var regex = new RegExp(regexS);
					var results = regex.exec(window.location.href);
					if(results == null)
						return "";
					else
						return decodeURIComponent(results[1].replace(/\+/g, " "));
				}
							
				jQuery(function($) {
					if (bizzGetParameterByNameInline("bizz_force_send")=="true") {
						var bizz_send_label = bizzGetParameterByNameInline("bizz_send_label");
						$("td.savesend input").val(bizz_send_label);
					}
				});
			</script>
		';
	}
	 
    return $args;

}

// End. That's it, folks! //

/* SAMPLE OPTIONS

// Include & setup custom metabox and fields
$prefix = '_bizz_'; // start with an underscore to hide fields from custom fields list
add_filter( 'bizz_meta_boxes', 'be_sample_metaboxes' );
function be_sample_metaboxes( $meta_boxes ) {
	global $prefix;
	$meta_boxes[] = array(
		'id' => 'test_metabox',
		'title' => 'Test Metabox',
		'pages' => array('page'), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Test Text',
				'desc' => 'field description (optional)',
				'id' => $prefix . 'test_text',
				'type' => 'text'
			),
			array(
				'name' => 'Test Text Small',
				'desc' => 'field description (optional)',
				'id' => $prefix . 'test_textsmall',
				'type' => 'text_small'
			),
			array(
				'name' => 'Test Text Medium',
				'desc' => 'field description (optional)',
				'id' => $prefix . 'test_textmedium',
				'type' => 'text_medium'
			),
			array(
				'name' => 'Test Counter',
				'desc' => 'field description (optional)',
				'id' => $prefix . 'test_counter',
				'type' => 'text_counter'
			),
			array(
				'name' => 'Test Date Picker',
				'desc' => 'field description (optional)',
				'id' => $prefix . 'test_textdate',
				'type' => 'text_date'
			),
			array(
				'name' => 'Test Date Picker (UNIX timestamp)',
				'desc' => 'field description (optional)',
				'id' => $prefix . 'test_textdate_timestamp',
				'type' => 'text_date_timestamp'
			),			
			array(
	            'name' => 'Test Time',
	            'desc' => 'field description (optional)',
	            'id' => $prefix . 'test_time',
	            'type' => 'text_time'
	        ),			
			array(
				'name' => 'Test Money',
				'desc' => 'field description (optional)',
				'id' => $prefix . 'test_textmoney',
				'currency' => '$',
				'type' => 'text_money'
			),
			array(
				'name' => 'Test Text Area',
				'desc' => 'field description (optional)',
				'id' => $prefix . 'test_textarea',
				'type' => 'textarea'
			),
			array(
				'name' => 'Test Text Area Small',
				'desc' => 'field description (optional)',
				'id' => $prefix . 'test_textareasmall',
				'type' => 'textarea_small'
			),
			array(
				'name' => 'Test Text Area Code',
				'desc' => 'field description (optional)',
				'id' => $prefix . 'test_textarea_code',
				'type' => 'textarea_code'
			),
			array(
				'name' => 'Test Text Area Counter',
				'desc' => 'field description (optional)',
				'id' => $prefix . 'test_textarea_counter',
				'type' => 'textarea_counter'
			),
			array(
				'name' => 'Test Title Weeeee',
				'desc' => 'This is a title description',
				'type' => 'title',
				'id' => $prefix . 'test_title'
			),
			array(
				'name' => 'Test Select',
				'desc' => 'field description (optional)',
				'id' => $prefix . 'test_select',
				'type' => 'select',
				'options' => array(
					array('name' => 'Option One', 'value' => 'standard'),
					array('name' => 'Option Two', 'value' => 'custom'),
					array('name' => 'Option Three', 'value' => 'none')				
				)
			),
			array(
				'name' => 'Test Radio inline',
				'desc' => 'field description (optional)',
				'id' => $prefix . 'test_radio',
				'type' => 'radio_inline',
				'options' => array(
					array('name' => 'Option One', 'value' => 'standard'),
					array('name' => 'Option Two', 'value' => 'custom'),
					array('name' => 'Option Three', 'value' => 'none')				
				)
			),
			array(
				'name' => 'Test Radio',
				'desc' => 'field description (optional)',
				'id' => $prefix . 'test_radio',
				'type' => 'radio',
				'options' => array(
					array('name' => 'Option One', 'value' => 'standard'),
					array('name' => 'Option Two', 'value' => 'custom'),
					array('name' => 'Option Three', 'value' => 'none')				
				)
			),
			array(
				'name' => 'Test Taxonomy Radio',
				'desc' => 'Description Goes Here',
				'id' => $prefix . 'text_taxonomy_radio',
				'taxonomy' => '', //Enter Taxonomy ID
				'type' => 'taxonomy_radio',	
			),
			array(
				'name' => 'Test Taxonomy Select',
				'desc' => 'Description Goes Here',
				'id' => $prefix . 'text_taxonomy_select',
				'taxonomy' => '', //Enter Taxonomy ID
				'type' => 'taxonomy_select',	
			),
			array(
				'name' => 'Test Checkbox',
				'desc' => 'field description (optional)',
				'id' => $prefix . 'test_checkbox',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Test Multi Checkbox',
				'desc' => 'field description (optional)',
				'id' => $prefix . 'test_multicheckbox',
				'type' => 'multicheck',
				'options' => array(
					'check1' => 'Check One',
					'check2' => 'Check Two',
					'check3' => 'Check Three',
				)
			),
			array(
				'name' => 'Test wysiwyg',
				'desc' => 'field description (optional)',
				'id' => $prefix . 'test_wysiwyg',
				'type' => 'wysiwyg',
				'options' => array(
					'textarea_rows' => 5,
				)
			),
			array(
				'name' => 'Test Image',
				'desc' => 'Upload an image or enter an URL.',
				'id' => $prefix . 'test_image',
				'type' => 'file'
			),
			array(
				'name' => 'Test Multi Image',
				'desc' => 'Upload images or enter an URL.',
				'id' => $prefix . 'test_images',
				'type' => 'file_list'
			),
			array(
				'name' => 'Date Time',
				'desc' => 'Sample date time fields.',
				'id' => $prefix . 'date_time',
				'type' => 'date_time'
			),
		)
	);

	$meta_boxes[] = array(
		'id' => 'about_page_metabox',
		'title' => 'About Page Metabox',
		'pages' => array('page'), // post type
		'show_on' => array( 'key' => 'id', 'value' => array( 2 ) ), // specific post ids to display this metabox
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Test Text',
				'desc' => 'field description (optional)',
				'id' => $prefix . 'test_text',
				'type' => 'text'
			),
		)
	);
	
	return $meta_boxes;
}
*/