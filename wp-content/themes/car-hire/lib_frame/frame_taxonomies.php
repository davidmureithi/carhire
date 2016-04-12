<?php

/**
 * Initiate all tax boxes
 */
 
function taxboxes_admin_init() {
	$tax_boxes = array();
	$tax_boxes = apply_filters ( 'bizz_tax_boxes' , $tax_boxes );
	foreach ( $tax_boxes as $tax_box ) {
		$my_box = new Bizz_Tax_Box( $tax_box );
	}
}
add_action( 'init', 'taxboxes_admin_init' );

/**
 * Validate value of tax fields
 * Define ALL validation methods inside this class and use the names of these 
 * methods in the definition of tax boxes (key 'validate_func' of each field)
 */

class Bizz_Tax_Box_Validate {
	function check_text( $text ) {
		if ($text != 'hello') {
			return false;
		}
		return true;
	}
}

/**
 * Create tax boxes
 */

class Bizz_Tax_Box {
	protected $_tax_box;

	function __construct( $tax_box ) {
		if ( !is_admin() ) return;

		$this->_tax_box = $tax_box;

		$upload = false;
		foreach ( $tax_box['fields'] as $field ) {
			if ( $field['type'] == 'file' || $field['type'] == 'file_list' ) {
				$upload = true;
				break;
			}
		}
		
		$current_page = substr(strrchr($_SERVER['PHP_SELF'], '/'), 1, -4);
		
		if ( $upload && ( $current_page == 'edit-tags' ) )
			add_action( 'admin_head', array(&$this, 'add_post_enctype') );
		
		// Add taxboxes
		foreach ( $this->_tax_box['taxonomies'] as $tax ) {
			add_action( $tax . '_edit_form_fields', array( $this, 'show' ), 10, 1 );
			add_action( $tax . '_add_form_fields', array( $this, 'show' ), 10, 1 );
			add_action( 'created_term',  array( $this, 'save' ), 10, 1 );
			add_action( 'edit_term',  array( $this, 'save' ), 10, 1 );
			add_action( 'delete_' . $tax,  array( $this, 'save' ), 10, 1 );
		}
	}

	function add_post_enctype() {
		echo '
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("#addtag, #edittag").attr("enctype", "multipart/form-data");
			jQuery("#addtag, #edittag").attr("encoding", "multipart/form-data");
		});
		</script>';
	}
	
	// Show fields
	function show( $tag ) {
	// $wp_version used for compatibility with new wp_editor() function
		global $post, $wp_version;

		// Use nonce for verification
		echo '<input type="hidden" name="wp_tax_box_nonce" value="', wp_create_nonce( basename(__FILE__) ), '" />';
		echo '<table class="form-table bizz_metabox">';

		foreach ( $this->_tax_box['fields'] as $field ) {
			// Set up blank or default values for empty ones
			if ( !isset( $field['name'] ) ) $field['name'] = '';
			if ( !isset( $field['desc'] ) ) $field['desc'] = '';
			if ( !isset( $field['std'] ) ) $field['std'] = '';
			if ( 'file' == $field['type'] && !isset( $field['allow'] ) ) $field['allow'] = array( 'url', 'attachment' );
			if ( 'file' == $field['type'] && !isset( $field['save_id'] ) )  $field['save_id']  = false;
						
			if ( isset( $tag ) && is_object( $tag ) ) {
				$tax_id = $tag->term_id;
				$tax = get_option( "taxonomy_" . $tax_id . "_" . $field['id'] );
			}
			else {
				$tax_id = '';
				$tax = '';
			}
						
			if ( is_object( $tag ) ) {
				echo '<tr>';
				if( $this->_tax_box['show_names'] == true && $field['type'] != 'title' )
					echo '<th scope="row" valign="top"><label for="', $field['id'], '">', $field['name'], '</label></th>';
				echo '<td>';
				$desc_html = "span class=\"bizz_metabox_description\" style=\"display:block;padding-top:5px;\"";
			}
			else {
				echo '<tr>','<td>';
				if( $this->_tax_box['show_names'] == true && $field['type'] != 'title' )
					echo '<label for="', $field['id'], '">', $field['name'], '</label>';
				$desc_html = "p class=\"bizz_metabox_description\"";
			}
						
			switch ( $field['type'] ) {

				case 'text':
					echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $tax ? $tax : $field['std'], '" style="width:97%" />',"<$desc_html class=\"bizz_taxbox_description\">", $field['desc'], "</$desc_html>";
					break;
				case 'text_small':
					echo '<input class="bizz_text_small" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $tax ? $tax : $field['std'], '" />',"<$desc_html class=\"bizz_taxbox_description\">", $field['desc'], "</$desc_html>";
					break;
				case 'text_medium':
					echo '<input class="bizz_text_medium" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $tax ? $tax : $field['std'], '" />',"<$desc_html class=\"bizz_taxbox_description\">", $field['desc'], "</$desc_html>";
					break;
				case 'text_counter':
					echo '<input class="char_counter" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $tax ? $tax : $field['std'], '" style="width:97%" />';
					echo '<input readonly class="counter" type="text" name="char_count" size="3" maxlength="3" value="'.strlen($tax ? $tax : $field['std']).'" />';
					echo "<$desc_html class=\"bizz_taxbox_description\">", $field['desc'], "</$desc_html>";
					break;
				case 'text_date':
					echo '<input class="bizz_text_small bizz_datepicker" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $tax ? $tax : $field['std'], '" />',"<$desc_html class=\"bizz_taxbox_description\">", $field['desc'], "</$desc_html>";
					break;
				case 'text_date_timestamp':
					echo '<input class="bizz_text_small bizz_datepicker" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $tax ? date( 'm\/d\/Y', $tax ) : $field['std'], '" />',"<$desc_html class=\"bizz_taxbox_description\">", $field['desc'], "</$desc_html>";
					break;
				case 'text_time':
					echo '<input class="bizz_timepicker text_time" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $tax ? $tax : $field['std'], '" />',"<$desc_html class=\"bizz_taxbox_description\">", $field['desc'], "</$desc_html>";
					break;
				case 'date_time':
					echo '<input class="bizz_text_small bizz_datepicker" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $tax ? $tax : $field['std'], '" />';
					$options = array('00:00', '00:30', '01:00', '01:30', '02:00', '02:30', '03:00', '03:30', '04:00', '04:30', '05:00', '05:30', '06:00', '06:30', '07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00', '22:30', '23:00', '23:30');
					$default = '12:00';
					$existing_value_time = get_option("taxonomy_$tax_id"."_time", true);
					echo '<select name="', $field['id'], '_time">';
					foreach ($options as $option) {
						if ($existing_value_time)
							$checked = ($existing_value_time == $option) ? ' selected="selected"' : '';
						elseif ($option == $default)
							$checked = ' selected="selected"';
						else
							$checked = '';
						echo '<option value="', $option, '"', $checked, '>', $option, '</option>';
					}
					echo '</select>';
					echo "<$desc_html class=\"bizz_taxbox_description\">", $field['desc'], "</$desc_html>";
					break;
				case 'text_money':
					echo '$ <input class="bizz_text_money" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $tax ? $tax : $field['std'], '" />',"<$desc_html class=\"bizz_taxbox_description\">", $field['desc'], "</$desc_html>";
					break;
				case 'textarea':
					echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="10" style="width:97%">', $tax ? $tax : $field['std'], '</textarea>',"<$desc_html class=\"bizz_taxbox_description\">", $field['desc'], "</$desc_html>";
					break;
				case 'textarea_small':
					echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $tax ? $tax : $field['std'], '</textarea>',"<$desc_html class=\"bizz_taxbox_description\">", $field['desc'], "</$desc_html>";
					break;
				case 'textarea_code':
					echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="10" style="width:97%">', $tax ? $tax : $field['std'], '</textarea>',"<$desc_html class=\"bizz_taxbox_description\">", $field['desc'], "</$desc_html>";
					break;
				case 'textarea_counter':
					echo '<textarea class="char_counter" name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="3" style="width:97%">', $tax ? $tax : $field['std'], '</textarea>';
					echo '<input readonly class="counter" type="text" name="char_count" size="3" maxlength="3" value="'.strlen($tax ? $tax : $field['std']).'" />';
					echo '<span class="bizz_taxbox_description">', $field['desc'], '</span>';
					break;
				case 'select':
					echo '<select name="', $field['id'], '" id="', $field['id'], '">';
					foreach ($field['options'] as $option) {
						echo '<option value="', $option['value'], '"', $tax == $option['value'] ? ' selected="selected"' : '', '>', $option['name'], '</option>';
					}
					echo '</select>';
					echo "<$desc_html class=\"bizz_taxbox_description\">", $field['desc'], "</$desc_html>";
					break;
				case 'radio_inline':
					if( empty( $tax ) && !empty( $field['std'] ) ) $tax = $field['std'];
					echo '<div class="bizz_radio_inline">';
					foreach ($field['options'] as $option) {
						echo '<div class="bizz_radio_inline_option"><input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $tax == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'], '</div>';
					}
					echo '</div>';
					echo "<$desc_html class=\"bizz_taxbox_description\">", $field['desc'], "</$desc_html>";
					break;
				case 'radio':
					if( empty( $tax ) && !empty( $field['std'] ) ) $tax = $field['std'];
					foreach ($field['options'] as $option) {
						echo '<p><input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $tax == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'].'</p>';
					}
					echo "<$desc_html class=\"bizz_taxbox_description\">", $field['desc'], "</$desc_html>";
					break;
				case 'checkbox':
					echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $tax ? ' checked="checked"' : '', ' />';
					echo '<span class="bizz_taxbox_description">', $field['desc'], '</span>';
					break;	
				case 'title':
					echo '<h3 class="bizz_taxbox_title">', $field['name'], '</h3>';
					echo "<$desc_html class=\"bizz_taxbox_description\">", $field['desc'], "</$desc_html>";
					break;
				case 'wysiwyg':
					if( function_exists( 'wp_editor' ) )
						wp_editor( $tax ? $tax : $field['std'], $field['id'], isset( $field['options'] ) ? $field['options'] : array() );
					else {
						echo '<div id="poststuff" class="tax_mce">';
						echo '<div class="customEditor"><textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="7" style="width:97%">', $tax ? wpautop($tax, true) : '', '</textarea></div>';
						echo '</div>';
					}
			        	echo "<$desc_html class=\"bizz_taxbox_description\">", $field['desc'], "</$desc_html>";
					break;
				case 'taxonomy_select':
					echo '<select name="', $field['id'], '" id="', $field['id'], '">';
					$names= wp_get_object_terms( $post->ID, $field['taxonomy'] );
					$terms = get_terms( $field['taxonomy'], 'hide_empty=0' );
					foreach ( $terms as $term ) {
						if (!is_wp_error( $names ) && !empty( $names ) && !strcmp( $term->slug, $names[0]->slug ) ) {
							echo '<option value="' . $term->slug . '" selected>' . $term->name . '</option>';
						} else {
							echo '<option value="' . $term->slug . '  ' , $tax == $term->slug ? $tax : ' ' ,'  ">' . $term->name . '</option>';
						}
					}
					echo '</select>';
					echo "<$desc_html class=\"bizz_taxbox_description\">", $field['desc'], "</$desc_html>";
					break;
				case 'taxonomy_radio':
					$names= wp_get_object_terms( $post->ID, $field['taxonomy'] );
					$terms = get_terms( $field['taxonomy'], 'hide_empty=0' );
					foreach ( $terms as $term ) {
						if ( !is_wp_error( $names ) && !empty( $names ) && !strcmp( $term->slug, $names[0]->slug ) )
							echo '<p><input type="radio" name="', $field['id'], '" value="'. $term->slug . '" checked>' . $term->name . '</p>';
						else
							echo '<p><input type="radio" name="', $field['id'], '" value="' . $term->slug . '  ' , $tax == $term->slug ? $tax : ' ' ,'  ">' . $term->name .'</p>';
					}
					echo "<$desc_html class=\"bizz_taxbox_description\">", $field['desc'], "</$desc_html>";
					break;
				case 'taxonomy_multicheck':
					echo '<ul>';
					$names = wp_get_object_terms( $post->ID, $field['taxonomy'] );
					$terms = get_terms( $field['taxonomy'], 'hide_empty=0' );
					foreach ($terms as $term) {
						echo '<li><input type="checkbox" name="', $field['id'], '[]" id="', $field['id'], '" value="', $term->name , '"'; 
						foreach ($names as $name) {
							if ( $term->slug == $name->slug ){ echo ' checked="checked" ';};
						}
						echo' /><label>', $term->name , '</label></li>';
					}
				break;
				case 'file':
					$input_type_url = "hidden";
					if ( 'url' == $field['allow'] || ( is_array( $field['allow'] ) && in_array( 'url', $field['allow'] ) ) )
						$input_type_url="text";
					echo '<input class="upload_file" type="' . $input_type_url . '" size="45" id="', $field['id'], '" name="', $field['id'], '" value="', $tax, '" />';
					echo '<input class="upload_button button" type="button" value="'.__('Upload File').'" />';
					echo '<input class="upload_file_id" type="hidden" id="', $field['id'], '_id" name="', $field['id'], '_id" value="', get_option( "taxonomy_$tax_id" . "_id", true), '" />';					
					echo "<$desc_html class=\"bizz_taxbox_description\">", $field['desc'], "</$desc_html>";
					echo '<div id="', $field['id'], '_status" class="bizz_upload_status">';	
						if ( $tax != '' ) { 
							$check_image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $tax );
							if ( $check_image ) {
								echo '<div class="img_status">';
								echo '<img src="', $tax, '" alt="" />';
								echo '<a href="#" class="remove_file_button" title="'.__('Remove file').'" rel="', $field['id'], '">'.__('Remove file').'</a>';
								echo '</div>';
							} else {
								$parts = explode( "/", $tax );
								for( $i = 0; $i < sizeof( $parts ); ++$i ) {
									$title = $parts[$i];
								} 
								echo ''.__('File:').' <strong>', $title, '</strong>&nbsp;&nbsp;&nbsp; (<a href="', $tax, '" target="_blank" rel="external">'.__('Download').'</a> / <a href="# title="'.__('Remove').'" class="remove_file_button" rel="', $field['id'], '">'.__('Remove').'</a>)';
							}	
						}
					echo '</div>'; 
				break;
				default:
					do_action('bizz_render_' . $field['type'] , $field, $tax);
			}
			
			echo '</td>','</tr>';
		}
		echo '</table>';
	}

	// Save data from taxbox
	function save( $term_id)  {
		global $wp_version;
						
		// verify nonce
		if ( ( !isset( $_POST['wp_tax_box_nonce'] ) || !wp_verify_nonce( $_POST['wp_tax_box_nonce'], basename(__FILE__) ) ) && $_POST['action'] != 'delete-tag' )
			return $term_id;

		foreach ( $this->_tax_box['fields'] as $field ) {
			$name = $field['id'];
			$old = get_option( "taxonomy_" . $term_id . "_" . $name );
			// $old = $old[$name];
			$new = isset( $_POST[$field['id']] ) ? $_POST[$field['id']] : null;

			// wpautop() should not be needed with version 3.3 and later
			if ( $field['type'] == 'wysiwyg' && !function_exists( 'wp_editor' ) )
				$new = wpautop($new);
			
			if ( in_array( $field['type'], array( 'taxonomy_select', 'taxonomy_radio', 'taxonomy_multicheck' ) ) )
				$new = wp_set_object_terms( $term_id, $new, $field['taxonomy'] );	

			if ( ($field['type'] == 'textarea') || ($field['type'] == 'textarea_small') )
				$new = htmlspecialchars( $new );

			if ( ($field['type'] == 'textarea_code') )
				$new = htmlspecialchars_decode( $new );
			
			if ( $field['type'] == 'text_date_timestamp' )
				$new = strtotime( $new );
			
			$new = apply_filters('bizz_validate_' . $field['type'], $new, $term_id, $field);
			
			// validate tax value
			if ( isset( $field['validate_func']) ) {
				$ok = call_user_func( array( 'Bizz_Tax_Box_Validate', $field['validate_func']), $new );
				if ( $ok === false ) // pass away when tax value is invalid
					continue;		
			} 
			elseif ( $new && $new != $old && !is_wp_error($new) )
				update_option( "taxonomy_" . $term_id . "_" . $name, $new );
			elseif ( $_POST['action'] == 'delete-tag' )
				delete_option( "taxonomy_" . $term_id . "_" . $name );
			
			if ( 'file' == $field['type'] ) {
				$name = $field['id'] . "_id";
				$old = get_option( "taxonomy_" . $term_id . "_" . $name );
				if ( isset($field['save_id']) )
					$new = isset( $_POST[$name] ) ? $_POST[$name] : null;
				else
					$new = "";

				if ( $new && $new != $old && !is_wp_error($new) )
					update_option( "taxonomy_" . $term_id . "_" . $name, $new );
				elseif ( $_POST['action'] == 'delete-tag' )
					delete_option( "taxonomy_" . $term_id . "_" . $name );
				
			}
			
			if ( 'date_time' == $field['type'] ) {
				$name = $field['id'] . "_time";
				$old = get_option( "taxonomy_" . $term_id . "_" . $name );
				$new = isset( $_POST[$name] ) ? $_POST[$name] : null;

				if ( $new && $new != $old && !is_wp_error($new) )
					update_option( "taxonomy_" . $term_id . "_" . $name, $new );
				elseif ( $_POST['action'] == 'delete-tag' )
					delete_option( "taxonomy_" . $term_id . "_" . $name );
				
			}
		}
				
	}
}

/**
 * Adding scripts and styles
 */

function bizz_taxbox_scripts( $hook ) {
  	if ( $hook == 'edit-tags.php' ) {
		wp_register_script( 'bizz-taxbox', BIZZ_FRAME_SCRIPTS . '/metaboxes.js', array( 'jquery','media-upload','thickbox' ) );
		wp_enqueue_script( 'bizz-taxbox' );
  	}
}
add_action( 'admin_enqueue_scripts', 'bizz_taxbox_scripts', 10, 1 );

function editor_tax_admin_init( $hook ) {
	if ( $hook == 'edit-tags.php' ) {
		wp_enqueue_script( 'word-count' );
		wp_enqueue_script( 'post' );
		wp_enqueue_script( 'editor' );
	}
}
add_action( 'admin_init', 'editor_tax_admin_init' );

function editor_tax_admin_head( $hook ) {
	if ( $hook == 'edit-tags.php' )
  		wp_editor();
}
add_action( 'admin_head', 'editor_tax_admin_head' );

// Force 'Insert into Post' button from Media Library 
add_filter( 'get_media_item_args', 'bizz_tax_force_send' );
function bizz_tax_force_send( $args ) {
		
	// if the Gallery tab is opened from a custom tax box field, add Insert Into Post button	
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

// Include & setup custom taxbox and fields
$prefix = '_bizz_'; // start with an underscore to hide fields from custom fields list
add_filter( 'bizz_tax_boxes', 'be_sample_taxboxes' );
function be_sample_taxboxes( $tax_boxes ) {
	global $prefix;
	$tax_boxes[] = array(
		'id' => 'test_taxbox',
		'title' => 'Test Taxbox',
		'taxonomies' => array('page'), // post type
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
				'taxonomy' => '', //Enter Taxonomy Slug
				'type' => 'taxonomy_radio',	
			),
			array(
				'name' => 'Test Taxonomy Select',
				'desc' => 'Description Goes Here',
				'id' => $prefix . 'text_taxonomy_select',
				'taxonomy' => '', //Enter Taxonomy Slug
				'type' => 'taxonomy_select',	
			),
			array(
				'name' => 'Test Checkbox',
				'desc' => 'field description (optional)',
				'id' => $prefix . 'test_checkbox',
				'type' => 'checkbox'
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
				'name' => 'Date Time',
				'desc' => 'Sample date time fields.',
				'id' => $prefix . 'date_time',
				'type' => 'date_time'
			),
		)
	);

	$tax_boxes[] = array(
		'id' => 'about_page_taxbox',
		'title' => 'About Page Taxbox',
		'taxonomies' => array('page'), // post type
		'show_on' => array( 'key' => 'id', 'value' => array( 2 ) ), // specific post ids to display this taxbox
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
	
	return $tax_boxes;
}
*/