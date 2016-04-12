<?php

/*

  FILE STRUCTURE:

- Custom post type icons
- Custom Post Types Init
- Columns for post types
- Custom Post Type Metabox Setup

*/

/* Custom post type icons */
/*------------------------------------------------------------------*/
function bizz_slides_post_types_icons() {
?>
	<style type="text/css" media="screen">
        #menu-posts-bizz_slides .wp-menu-image, #menu-posts-bizzslides .wp-menu-image {
			background: url(<?php echo get_template_directory_uri() ?>/lib_theme/cpt/icons-slides.png) no-repeat 6px -17px !important;
		}
		#menu-posts-bizz_slides:hover .wp-menu-image, #menu-posts-bizzslides.wp-has-current-submenu .wp-menu-image {
			background-position:6px 7px!important;
		}
    </style>
<?php 
}
add_action( 'admin_head', 'bizz_slides_post_types_icons' );

/* Custom post type init */
/*------------------------------------------------------------------*/
function bizz_slides_post_types_init() {

	register_post_type( 'bizz_slides',
        array(
        	'label' 				=> __('Slides'),
			'labels' 				=> array(	
				'name' 					=> __('Slides'),
				'singular_name' 		=> __('Slides'),
				'add_new' 				=> __('Add New'),
				'add_new_item' 			=> __('Add New Slide'),
				'edit' 					=> __('Edit'),
				'edit_item' 			=> __('Edit Slide'),
				'new_item' 				=> __('New Slide'),
				'view_item'				=> __('View Slide'),
				'search_items' 			=> __('Search Slides'),
				'not_found' 			=> __('No Slides found'),
				'not_found_in_trash' 	=> __('No Slides found in trash'),
				'parent' 				=> __('Parent Slide' ),
			),
            'description' => __( 'This is where you can create new slides for your site.' ),
            'public' => true,
            'show_ui' => true,
            'capability_type' => 'page',
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'hierarchical' => true,
            'rewrite' => array( 'slug' => 'slides', 'with_front' => false ),
            'query_var' => true,
            'has_archive' => 'slides',
            'supports' => array( 'title', 'page-attributes' ),
        )
    );

}
add_action( 'init', 'bizz_slides_post_types_init' );

/* Columns for post types */
/*------------------------------------------------------------------*/
function bizz_slides_edit_columns($columns){
	$columns = array(
		'cb' 						=> '<input type=\'checkbox\' />',
		'title' 					=> __('Slide Title'),
		'date' 						=> __('Date')
	);
	return $columns;
}
add_filter('manage_edit-bizz_slides_columns','bizz_slides_edit_columns');

function bizz_slides_custom_columns($column){
	global $post;
	switch ($column){
		// empty
	}
}
add_action('manage_posts_custom_column', 'bizz_slides_custom_columns', 2);

/* Post type demo posts */
/*------------------------------------------------------------------*/
function bizz_slides_demo_posts() {
	
	if (get_option('bizz_slides_demo_complete') != 'true') {

		// INSERT POSTS
		$demo_post = array(
				"post_title"	=>	'Slider Example 3',
				"post_status"	=>	'publish',
				"post_type"	    =>	'bizz_slides',
				"post_content"	=>	'
		At vero eos et accusamus et iusto odio dignissimos ducimus qui  blanditiis praesentium voluptatum deleniti atque corrupti quos dolores  et quas molestias excepturi sint occaecati cupiditate non provident,  similique sunt in culpa qui officia deserunt mollitia animi, id est  laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita  distinctio. Nam libero tempore, cum soluta nobis est eligendi optio  cumque nihil impedit quo minus id quod maxime placeat facere possimus,  omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem  quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet  ut et voluptates repudiandae sint et molestiae non recusandae. Itaque  earum rerum hic tenetur a sapiente delectus, ut aut reiciendis  voluptatibus maiores alias consequatur aut perferendis doloribus  asperiores repellat.
				'
		);
		$add_demo_post = wp_insert_post( $demo_post );
		$post_meta_data = array(
				"meta_key"		=>	'bizzthemes_slide_textarea',
				"meta_value"	=>	'
		At vero eos et accusamus et iusto odio dignissimos ducimus qui  blanditiis praesentium voluptatum deleniti atque corrupti quos dolores  et quas molestias excepturi sint occaecati cupiditate non provident,  similique sunt in culpa qui officia deserunt mollitia animi, id est  laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita  distinctio. Nam libero tempore, cum soluta nobis est eligendi optio  cumque nihil impedit quo minus id quod maxime placeat facere possimus,  omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem  quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet  ut et voluptates repudiandae sint et molestiae non recusandae. Itaque  earum rerum hic tenetur a sapiente delectus, ut aut reiciendis  voluptatibus maiores alias consequatur aut perferendis doloribus  asperiores repellat.
				'
		);
		add_post_meta($add_demo_post, $post_meta_data['meta_key'], $post_meta_data['meta_value'], true);
		
		$demo_post = array(
				"post_title"	=>	'Slider Example 2',
				"post_status"	=>	'publish',
				"post_type"	    =>	'bizz_slides',
				"post_content"	=>	'
		Id ius dicam aeterno. Et graece saperet euripidis eum, tota labores luptatum eum eu. Usu te brute volutpat, ex scripta intellegebat pro. An per dictas omnium fastidii. Cu nam percipit forensibus.

		Cu has erat idque democritum. Eu his meis numquam, his in bonorum eloquentiam. Meliore vivendum explicari ius ea. His te integre meliore adolescens, sonet dolorem scriptorem ius id.

		Dicit altera efficiendi an duo. Vis no libris bonorum lobortis, facete bonorum nec et, ne enim eruditi sea. Sed audiam debitis an, dicta putant malorum vix et. No quo quod tractatos reprehendunt, mea mundi mollis accumsan ex, inani vivendo signiferumque te sed. Est perpetua reprimique ex, at dicit choro suscipiantur pri, ei vidisse eloquentiam quo.
				'
		);
		$add_demo_post = wp_insert_post( $demo_post );
		$post_meta_data = array(
				"meta_key"		=>	'bizzthemes_slide_textarea',
				"meta_value"	=>	'
		Id ius dicam aeterno. Et graece saperet euripidis eum, tota labores luptatum eum eu. Usu te brute volutpat, ex scripta intellegebat pro. An per dictas omnium fastidii. Cu nam percipit forensibus.

		Cu has erat idque democritum. Eu his meis numquam, his in bonorum eloquentiam. Meliore vivendum explicari ius ea. His te integre meliore adolescens, sonet dolorem scriptorem ius id.

		Dicit altera efficiendi an duo. Vis no libris bonorum lobortis, facete bonorum nec et, ne enim eruditi sea. Sed audiam debitis an, dicta putant malorum vix et. No quo quod tractatos reprehendunt, mea mundi mollis accumsan ex, inani vivendo signiferumque te sed. Est perpetua reprimique ex, at dicit choro suscipiantur pri, ei vidisse eloquentiam quo.
				'
		);
		add_post_meta($add_demo_post, $post_meta_data['meta_key'], $post_meta_data['meta_value'], true);
		
		$demo_post = array(
				"post_title"	=>	'Slider Example 1',
				"post_status"	=>	'publish',
				"post_type"	    =>	'bizz_slides',
				"post_content"	=>	'
		Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Quisque sed felis. Aliquam sit amet felis. Mauris semper, velit semper laoreet dictum, quam diam dictum urna, nec placerat elit nisl in quam.

		Etiam augue pede, molestie eget, rhoncus at,  convallis ut, eros. Aliquam pharetra. Nulla in tellus eget odio  sagittis blandit. Mauris semper, velit semper laoreet dictum, quam diam dictum urna, nec placerat elit nisl in quam.

		Dicit altera efficiendi an duo. Vis no libris bonorum lobortis, facete bonorum nec et, ne enim eruditi sea. Sed audiam debitis an, dicta putant malorum vix et. No quo quod tractatos reprehendunt, mea mundi mollis accumsan ex, inani vivendo signiferumque te sed. Est perpetua reprimique ex, at dicit choro suscipiantur pri, ei vidisse eloquentiam quo.
				'
		);
		$add_demo_post = wp_insert_post( $demo_post );
		$post_meta_data = array(
				"meta_key"		=>	'bizzthemes_slide_textarea',
				"meta_value"	=>	'
		Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Quisque sed felis. Aliquam sit amet felis. Mauris semper, velit semper laoreet dictum, quam diam dictum urna, nec placerat elit nisl in quam.

		Etiam augue pede, molestie eget, rhoncus at,  convallis ut, eros. Aliquam pharetra. Nulla in tellus eget odio  sagittis blandit. Mauris semper, velit semper laoreet dictum, quam diam dictum urna, nec placerat elit nisl in quam.

		Dicit altera efficiendi an duo. Vis no libris bonorum lobortis, facete bonorum nec et, ne enim eruditi sea. Sed audiam debitis an, dicta putant malorum vix et. No quo quod tractatos reprehendunt, mea mundi mollis accumsan ex, inani vivendo signiferumque te sed. Est perpetua reprimique ex, at dicit choro suscipiantur pri, ei vidisse eloquentiam quo.
				'
		);
		add_post_meta($add_demo_post, $post_meta_data['meta_key'], $post_meta_data['meta_value'], true);
		
		//installation complete
		update_option('bizz_slides_demo_complete', 'true');
	}
	
}
add_action( 'init', 'bizz_slides_demo_posts', 0 );

/* Custom Post Type Metabox Setup */
/*------------------------------------------------------------------*/
add_filter( 'bizz_meta_boxes', 'bizz_slides_metaboxes' );
function bizz_slides_metaboxes( $meta_boxes ) {
	$prefix = 'bizzthemes_';
	
	$meta_boxes[] = array(
		'id' => 'bizzthemes_slides_meta',
		'title' => __('Slide Details'),
		'pages' => array( 'bizz_slides' ), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __('Slide image'),
				'desc' => __('Upload image or enter full image location <acronym title="Uniform Resource Locator">URL</acronym> above.'),
				'id' => $prefix . 'slide_img',
				'type' => 'file'
			),
			array(
				'name' => __('Slide video'),
				'desc' => __('Enter a whole embed video code into this area.'),
				'id' => $prefix . 'slide_vid',
				'type' => 'textarea_small'
			),
			array(
				'name' => __('Slide content'),
				'desc' => __('Enter some content into this area. It will only appear if you do not upload slide image nor embed video code above.'),
				'id' => $prefix . 'slide_textarea',
				'type' => 'wysiwyg',
				'options' => array(
					'textarea_rows' => 5,
				)
			),
		)
	);
		
	return $meta_boxes;
}

/** 
 * Adding our custom fields to the $form_fields array 
 * 
 * @param array $form_fields 
 * @param object $post 
 * @return array 
 */  
function bizz_image_attachment_fields_to_edit($form_fields, $post) {

	// Set up options
	$options = array( '0' => 'Bottom', '1' => 'Top', '2' => 'Right', '3' => 'Left', '4' => 'No overlay', );

	// Get currently selected value
	$selected = get_post_meta( $post->ID, 'be_overlay_position', true );

	// If no selected value, default to 'No'
	if( !isset( $selected ) ) 
		$selected = '2';

	// Display each option	
	foreach ( $options as $value => $label ) {
		$css_id = 'overlay-include-option-' . $value;
		$checked = ( $selected == $value ) ? " checked='checked'" : '';
		$html = "<input type='radio' name='attachments[$post->ID][be-overlay-position]' id='{$css_id}' value='{$value}'$checked />";
		$html .= "<label for='{$css_id}'>$label</label>";
		$out[] = $html;
	}

	// Construct the form fields
	$form_fields['be-custom-url'] = array(
		'label' => __('Custom URL'),
		'input' => 'text',
		'value' => get_post_meta( $post->ID, 'be_custom_url', true ),
		'helps' => __('If provided, image and overlay button will link here'),
	);
	$form_fields['be-overlay-position'] = array(
		'label' => __('Overlay position'),
		'input' => 'html',
		'html'  => join("\n", $out),
	);

	// Return all form fields
	return $form_fields;
	
}
add_filter("attachment_fields_to_edit", "bizz_image_attachment_fields_to_edit", null, 2);

/**
 * Save value of "Include in Rotator" selection in media uploader
 *
 * @param $post array, the post data for database
 * @param $attachment array, attachment fields from $_POST form
 * @return $post array, modified post data
 */
 
function bizz_attachment_field_overlay_save( $post, $attachment ) {
	// save overlay position
	if( isset( $attachment['be-overlay-position'] ) ) 
		update_post_meta( $post['ID'], 'be_overlay_position', $attachment['be-overlay-position'] );
	// save custom url
	if( isset( $attachment['be-custom-url'] ) )
		update_post_meta( $post['ID'], 'be_custom_url', $attachment['be-custom-url'] );

	return $post;
}
add_filter( 'attachment_fields_to_save', 'bizz_attachment_field_overlay_save', 10, 2 );


