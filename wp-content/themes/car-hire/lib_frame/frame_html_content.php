<?php
/**
 * POST CONTENT
 *
 * output post content, based on predefined arguments
 * @since 7.0
 */
function bizz_post_content( $args = '', $post_count = false ) {
	global $wp_query, $post;
	
	bizz_hook_before_post($post_count); #hook
		
	echo "<".apply_filters('bizz_html5_section', "div")." class=\"format_text\">\n";
	
	$selflink = (isset($args['thumb_selflink']) && $args['thumb_selflink'] == true) ? true : false;
	$cropp = (isset($args['thumb_cropp']) && $args['thumb_cropp'] != '') ? $args['thumb_cropp'] : 'c';
	if ( isset($GLOBALS['opt']['bizzthemes_thumb_show']['value']) && $args['thumb_display'] && !is_page() ) {
	    if ( is_single() ) { # is single post
		    if ( $args['thumb_single'] ) # show
				bizz_image('width='.$args['thumb_width'].'&height='.$args['thumb_height'].'&class=thumbnail '.$args['thumb_align'].'&cropp='.$cropp.'&selflink='.$selflink.'&filter='.$args['thumb_filter'].'&sharpen='.$args['thumb_sharpen'].'');
		} 
		else
			bizz_image('width='.$args['thumb_width'].'&height='.$args['thumb_height'].'&class=thumbnail '.$args['thumb_align'].'&cropp='.$cropp.'&selflink='.$selflink.'&filter='.$args['thumb_filter'].'&sharpen='.$args['thumb_sharpen'].'');
	}
		
	if ( $args['remove_posts'] == '1' && (is_archive() || is_front_page() || $wp_query->is_posts_page || is_search() || is_home()) )
		echo '';
	else {
		if ($args['full_posts']=='0' && ( is_archive() || is_front_page() || $wp_query->is_posts_page || is_search() || is_home() )){
			the_excerpt();
			if ( $args['read_more'] )
				echo apply_filters('bizz_read_more', '<span class="read-more"><a href="' . get_permalink() . '" class="url fn" rel="nofollow">' . $args['read_more_text'] . '</a></span>');
		}
		else
			the_content($args['read_more_text']);
		
		wp_link_pages( array( 'before' => '<div class="page-link">', 'after' => '</div>' ) );
	}
	
	echo '<div class="fix"><!----></div>';
	echo "</".apply_filters('bizz_html5_section', "div").">\n";

	bizz_hook_after_post($post_count); #hook
}

/**
 * POST CONTENT - query posts
 *
 * output post content for Query Posts widget, based on predefined arguments
 * @since 7.0
 */
function bizz_post_content_query($args = '', $post_count = false) {
	global $wp_query, $post;
	
	bizz_hook_before_post($post_count); #hook
	
	echo "<".apply_filters('bizz_html5_section', "div")." class=\"format_text\">\n";
	
	$selflink = (isset($args['thumb_selflink']) && $args['thumb_selflink'] == true) ? true : false;
	$cropp = (isset($args['thumb_cropp']) && $args['thumb_cropp'] != '') ? $args['thumb_cropp'] : 'c';
	if (isset($GLOBALS['opt']['bizzthemes_thumb_show']['value']) && $args['thumb_display']){
		bizz_image('width='.$args['thumb_width'].'&height='.$args['thumb_height'].'&class=thumbnail '.$args['thumb_align'].'&cropp='.$cropp.'&selflink='.$selflink.'&filter='.$args['thumb_filter'].'&sharpen='.$args['thumb_sharpen'].'');
	}
		
	if ($args['remove_posts']=='0'){
		if ($args['full_posts']=='0' && ( is_archive() || $wp_query->is_posts_page || is_search() || is_home() )){
			the_excerpt();
			if ( $args['read_more'] )
				echo apply_filters('bizz_read_more', '<span class="read-more"><a href="' . get_permalink() . '" class="url fn" rel="nofollow">' . $args['read_more_text'] . '</a></span>');
		}
		else
			the_content($args['read_more_text']);
		
		wp_link_pages( array( 'before' => '<div class="page-link">', 'after' => '</div>' ) );
	}
	
	echo '<div class="fix"><!----></div>';
	echo "</".apply_filters('bizz_html5_section', "div").">\n";

	bizz_hook_after_post($post_count); #hook
}

/**
 * HEADLINE AREA - archives
 *
 * output headlines for arhives, based on predefined arguments
 * @since 7.0
 */
function bizz_archive_headline() {
	global $wp_query; #wp
	$output = "<".apply_filters('bizz_html5_header', "div")." class=\"headline_area archive_headline\">\n";
		
	if ($wp_query->is_category || $wp_query->is_tax || $wp_query->is_tag) { #wp
		$headline = $wp_query->queried_object->name; #wp
		$output .= "\t<h1>" . apply_filters('bizz_archive_intro_headline', $headline) . "</h1>\n"; #filter
	}
	elseif ($wp_query->is_author) #wp
		$output .= "\t<h1>" . apply_filters('bizz_archive_intro_headline', get_the_author_meta('display_name', $wp_query->query_vars['author'])) . "</h1>\n"; #wp
	elseif ($wp_query->is_day) #wp
		$output .= "\t<h1>" . apply_filters('bizz_archive_intro_headline', get_the_time('l, F j, Y')) . "</h1>\n"; #wp
	elseif ($wp_query->is_month) #wp
		$output .= "\t<h1>" . apply_filters('bizz_archive_intro_headline', get_the_time('F Y')) . "</h1>\n"; #wp
	elseif ($wp_query->is_year) #wp
		$output .= "\t<h1>" . apply_filters('bizz_archive_intro_headline', get_the_time('Y')) . "</h1>\n"; #wp
	elseif ($wp_query->is_search) #wp
		$output .= "\t<h1>" . __('Search:', 'bizzthemes') . ' ' . apply_filters('bizz_archive_intro_headline', esc_html($wp_query->query_vars['s'])) . "</h1>\n"; #wp

	$output .= "</".apply_filters('bizz_html5_header', "div").">\n";
	echo apply_filters('bizz_archive_headline', $output);
}

/**
 * HEADLINE AREA - main
 *
 * output main headlines, based on predefined arguments
 * @since 7.0
 */
function bizz_headline_area() {

    if (apply_filters('bizz_show_headline_area', true)) {
	(is_paged()) ? $ispaged = ' paged' : $ispaged = '';
	
		echo "<".apply_filters('bizz_html5_header', "div")." class='headline_area'>\n";

	    if (is_404())
		    echo "<h1 class='entry-title'>" . stripslashes(__('Error 404 | Nothing found!', 'bizzthemes')) . "</h1>\n";
		elseif (is_page())
		    echo (is_front_page()) ? "<h2 class='entry-title".$ispaged."'>" . get_the_title() . "</h2>\n" : "<h1 class='entry-title title'>" . get_the_title() . "</h1>\n";
		else {
		    if (is_single())
			    echo "<h1 class='entry-title title'>" . get_the_title() . "</h1>\n";
			else
			    echo "<h2 class='entry-title".$ispaged."'><a href='" . get_permalink() . "' rel='bookmark' title='" . get_the_title() . "'>" . get_the_title() . "</a></h2>\n";
		}
	
		echo "</".apply_filters('bizz_html5_header', "div").">\n";
	}

}

/**
 * 404 ERROR
 *
 * output 404 error message
 * @since 7.0
 */
function bizz_404_error() {
?>
<h2><?php echo stripslashes(__('Sorry, but you are looking for something that is not here.', 'bizzthemes')); ?></h2>
<p><?php _e('Surfin&#8217; ain&#8217;t easy, and right now, you&#8217;re lost at sea. But don&#8217;t worry; simply pick an option from the list below, and you&#8217;ll be back out riding the waves of the Internet in no time.', 'bizzthemes'); ?></p>
<ul>
	<li><?php _e('Hit the &#8220;back&#8221; button on your browser. It&#8217;s perfect for situations like this!', 'bizzthemes'); ?></li>
	<li><?php printf(__('Head on over to the <a href="%s" rel="nofollow">home page</a>.', 'bizzthemes'), home_url()); ?></li>
	<li><?php _e('You will find what you are looking for.', 'bizzthemes'); ?></li>
</ul>
<?php	
}

/**
 * POST META
 *
 * output post meta data, based on arguments
 * @since 7.0
 */
function bizz_post_meta($args = '') {
	
	$args = (isset($args[0])) ? $args[0] : $args; #[0] array level
	$show_meta = ( is_singular() && !is_singular('post') ) ? (( isset($args['post_meta']) && $args['post_meta'] ) ? true : false) : true;
	
	$return_meta = '';
	
	if ( $show_meta ) {

		$return_meta .= "<".apply_filters('bizz_html5_aside', "p")." class=\"headline_meta\">";
		
		if ($args['post_date'])
			$return_meta .= '<span class="date"><abbr class="published" title="' . get_the_time('Y-m-d') . '">' . get_the_time(get_option('date_format')) . '</abbr></span>';
		if ($args['post_author'])
			$return_meta .= '<span class="auth"><a href="' . get_author_posts_url(get_the_author_meta('ID') ) . '" class="auth" rel="nofollow">' . get_the_author() . '</a></span>';
		if ($args['post_comments']) {
			$return_meta .= '<span class="comm">';
			$num_comments = get_comments_number(); // get_comments_number returns only a numeric value

			if ( comments_open() ) {
			
				if ( $num_comments == 0 )
					$comments = __('No Comments', 'bizzthemes');
				elseif ( $num_comments > 1 )
					$comments = sprintf(__('%d Comments'), $num_comments);
				else
					$comments = __('One Comment', 'bizzthemes');

				$return_meta .= '<a href="' . get_comments_link() .'" rel="nofollow">'. $comments.'</a>';
			} 
			else
				$return_meta .=  __('Comments are closed.', 'bizzthemes');
			
			$return_meta .= '</span>';
		}
		if ($args['post_categories'])	
			$return_meta .= seo_post_cats();
		if ($args['post_tags'])		
			$return_meta .= seo_post_tags();
		if ($args['post_edit']) {	
			if (current_user_can('manage_options') && is_user_logged_in())
				$return_meta .= '<span class="edit">' . get_edit_post_link() . '</span>';
		}
		
		$return_meta .= "</".apply_filters('bizz_html5_aside', "p").">\n";
	}
	
	echo apply_filters('bizz_post_meta', $return_meta);

}

/**
 * POST TAGS
 *
 * output post tags
 * @since 7.0
 */
function seo_post_tags() {

    global $post;
	$post_tags = get_the_tags();
		
	if ($post_tags) {
	
		$return = '<span class="tag">';
		$num_tags = count($post_tags);
		$tag_count = 1;
		
		if ( isset($GLOBALS['opt']['bizzthemes_nofollow_tags']['value']) ) { $nofollow = ' nofollow'; } else { $nofollow = ''; }

		foreach ($post_tags as $tag) {			
			$html_before = '<a href="' . get_tag_link($tag->term_id) . '" rel="tag' . $nofollow . '">';
			$html_after = '</a>';
			
			if ($tag_count < $num_tags)
				$sep = ', ' . "\n";
			elseif ($tag_count == $num_tags)
				$sep = "\n";
			
			$return .= $html_before . $tag->name . $html_after . $sep;
			$tag_count++;
		}
		$return .= '</span>';
		
		return $return;
		
	}
		
}

/**
 * POST CATEGORIES
 *
 * output post categories, seo opzimized
 * @since 7.0
 */
function seo_post_cats() {
    
	global $post;
	$post_cats = get_the_category();
		
	if ($post_cats) {
		    
		$return = '<span class="cat">';
		$num_cats = count($post_cats);
		$cat_count = 1;
			
		if ( isset($GLOBALS['opt']['bizzthemes_nofollow_cats']['value']) ) { $nofollow = ' nofollow'; } else { $nofollow = ''; }

		foreach ($post_cats as $cat) {			
			$html_before = '<a href="' . get_category_link($cat->term_id) . '" rel="cat' . $nofollow . '">';
			$html_after = '</a>';
				
			if ($cat_count < $num_cats)
				$sep = ', ' . "\n";
			elseif ($cat_count == $num_cats)
				$sep = "\n";
				
			$return .= $html_before . $cat->name . $html_after . $sep;
			$cat_count++;
		}
		$return .= '</span>';
		
		return $return;
			
	}
	
}

/**
 * PAGINATION
 *
 * output custom pagination
 * @Original Author: Lester 'GaMerZ' Chan, 2.50
 * @since 6.0
 */
function bizz_wp_pagenavi($before = '', $after = '') {
    global $wpdb, $wp_query;
    if (!is_single()) {
        $request = $wp_query->request;
        $posts_per_page = intval(get_query_var('posts_per_page'));
        $paged = intval(get_query_var('paged'));
        $pagenavi_options = get_option('pagenavi_options');
        $numposts = $wp_query->found_posts;
        $max_page = $wp_query->max_num_pages;
		
        if(empty($paged) || $paged == 0) {
            $paged = 1;
        }
        $pages_to_show = intval($pagenavi_options['num_pages']);
        $pages_to_show_minus_1 = $pages_to_show-1;
        $half_page_start = floor($pages_to_show_minus_1/2);
        $half_page_end = ceil($pages_to_show_minus_1/2);
        $start_page = $paged - $half_page_start;
        if($start_page <= 0) {
            $start_page = 1;
        }
        $end_page = $paged + $half_page_end;
        if(($end_page - $start_page) != $pages_to_show_minus_1) {
            $end_page = $start_page + $pages_to_show_minus_1;
        }
        if($end_page > $max_page) {
            $start_page = $max_page - $pages_to_show_minus_1;
            $end_page = $max_page;
        }
        if($start_page <= 0) {
            $start_page = 1;
        }
        if($max_page > 1 || intval($pagenavi_options['always_show']) == 1) {
            echo "<div class='fix'><!----></div>\n";
			echo "<div class='pagination_area clearfix'>\n";
			echo $before.'<ul class="lpag">'."\n";
            switch(intval($pagenavi_options['style'])) {
                case 1:                   
                    if ($start_page >= 2 && $pages_to_show < $max_page) {
                        $first_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), '&laquo; '.stripslashes(__('First', 'bizzthemes')));
                        echo '<li><a href="'.esc_url(get_pagenum_link()).'" title="'.$first_page_text.'">'.$first_page_text.'</a></li>';
                        if(!empty($pagenavi_options['dotleft_text'])) {
                            echo '<li>'.$pagenavi_options['dotleft_text'].'</li>';
                        }
                    }
					echo '<li>'."\n";
                    previous_posts_link($pagenavi_options['prev_text']);
					echo '</li>'."\n";
                    for($i = $start_page; $i  <= $end_page; $i++) {                        
                        if($i == $paged) {
                            $current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
                            echo '<li class="current"><span>'.$current_page_text.'</span></li>';
                        } else {
                            $page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
                            echo '<li><a href="'.esc_url(get_pagenum_link($i)).'" title="'.$page_text.'">'.$page_text.'</a></li>';
                        }
                    }
					echo '<li>'."\n";
                    next_posts_link($pagenavi_options['next_text'], $max_page);
					echo '</li>'."\n";
                    if ($end_page < $max_page) {
                        if(!empty($pagenavi_options['dotright_text'])) {
                            echo '<li>'.$pagenavi_options['dotright_text'].'</li>';
                        }
                        $last_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), ''.stripslashes(__('Last', 'bizzthemes')).' &raquo;');
                        echo '<li><a href="'.esc_url(get_pagenum_link($max_page)).'" title="'.$last_page_text.'">'.$last_page_text.'</a></li>';
                    }
                    break;
                case 2;
                    echo '<form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="get">'."\n";
                    echo '<select size="1" onchange="document.location.href = this.options[this.selectedIndex].value;">'."\n";
                    for($i = 1; $i  <= $max_page; $i++) {
                        $page_num = $i;
                        if($page_num == 1) {
                            $page_num = 0;
                        }
                        if($i == $paged) {
                            $current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
                            echo '<option value="'.esc_url(get_pagenum_link($page_num)).'" selected="selected" class="current">'.$current_page_text."</option>\n";
                        } else {
                            $page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
                            echo '<option value="'.esc_url(get_pagenum_link($page_num)).'">'.$page_text."</option>\n";
                        }
                    }
                    echo "</select>\n";
                    echo "</form>\n";
                    break;
            }
            echo '</ul>'.$after."\n";
			echo "<div class='pagination_loading'><!----></div>\n";
			echo "</div>\n";
        }
    }
}

add_action('init', 'bizz_wp_pagenavi_init');
function bizz_wp_pagenavi_init() {
    // Add Options
    $pagenavi_options = array();
    $pagenavi_options['current_text'] = '%PAGE_NUMBER%';
    $pagenavi_options['page_text'] = '%PAGE_NUMBER%';
    $pagenavi_options['first_text'] = __('&laquo; First','bizzthemes');
    $pagenavi_options['last_text'] = __('Last &raquo;','bizzthemes');
    $pagenavi_options['next_text'] = __('&raquo;','bizzthemes');
    $pagenavi_options['prev_text'] = __('&laquo;','bizzthemes');
    $pagenavi_options['dotright_text'] = __('...','bizzthemes');
    $pagenavi_options['dotleft_text'] = __('...','bizzthemes');
    $pagenavi_options['style'] = 1;
    $pagenavi_options['num_pages'] = 5;
    $pagenavi_options['always_show'] = 0;
	add_option('pagenavi_options', $pagenavi_options);
}

/**
 * VIDEO EMBED
 *
 * output custom video embed
 * @Original Addon Author: WooThemes
 * @since 7.4.8
 */
function bizz_get_embed($key = 'embed', $width, $height, $class = 'video', $id = null) {
	// Run new function
	return bizz_embed( 'key='.$key.'&width='.$width.'&height='.$height.'&class='.$class.'&id='.$id );

}

function bizz_embed($args) {

	//Defaults
	$key = 'embed';
	$width = null;
	$height = null;
	$class = 'video';
	$id = null;	
	
	if ( !is_array($args) )
		parse_str( $args, $args );
	
	extract($args);

	if(empty($id)) {
		global $post;
		$id = $post->ID;
    }
	
	$custom_field = get_post_meta($id, $key, true);

	if ($custom_field) : 

		$custom_field = html_entity_decode( $custom_field ); // Decode HTML entities.

		$org_width = $width;
		$org_height = $height;
		$calculated_height = '';
		
		// Get custom width and height
		$custom_width = get_post_meta($id, 'width', true);
		$custom_height = get_post_meta($id, 'height', true);    
		
		//Dynamic Height Calculation
		if ($org_height == '' && $org_width != '') {
			$raw_values = explode( " ", $custom_field);
		
			foreach ($raw_values as $raw) {
				$embed_params = explode( "=",$raw);
				if ($embed_params[0] == 'width') {
					$embed_width = ereg_replace( "[^0-9]", "", $embed_params[1]);
				}
				elseif ($embed_params[0] == 'height') {
					$embed_height = ereg_replace( "[^0-9]", "", $embed_params[1]);
				} 
			}
		
			$float_width = floatval($embed_width);
			$float_height = floatval($embed_height);
			$float_ratio = $float_height / $float_width;
			$calculated_height = intval($float_ratio * $width);
		}
		
		// Set values: width="XXX", height="XXX"
		if ( !$custom_width ) $width = 'width="'.$width.'"'; else $width = 'width="'.$custom_width.'"';
		if ( $height == '' ) { $height = 'height="'.$calculated_height.'"'; } else { if ( !$custom_height ) { $height = 'height="'.$height.'"'; } else { $height = 'height="'.$custom_height.'"'; } }
		$custom_field = stripslashes($custom_field);
		$custom_field = preg_replace( '/width="([0-9]*)"/' , $width , $custom_field );
		$custom_field = preg_replace( '/height="([0-9]*)"/' , $height , $custom_field );    

		// Set values: width:XXXpx, height:XXXpx
		if ( !$custom_width ) $width = 'width:'.$org_width.'px'; else $width = 'width:'.$custom_width.'px';
		if ( $height == '' ) { $height = 'height:'.$calculated_height.'px'; } else { if ( !$custom_height ) { $height = 'height:'.$org_height.'px'; } else { $height = 'height:'.$custom_height.'px'; } }
		$custom_field = stripslashes($custom_field);
		$custom_field = preg_replace( '/width:([0-9]*)px/' , $width , $custom_field );
		$custom_field = preg_replace( '/height:([0-9]*)px/' , $height , $custom_field );     

		// Suckerfish menu hack
		$custom_field = str_replace( '<embed ','<param name="wmode" value="transparent"></param><embed wmode="transparent" ',$custom_field);

		$output = '';
		$output .= '<div class="'. $class .'">' . $custom_field . '</div>';
		
		return $output; 
		
	else :

		return false;
		
	endif;

}

/**
 * THUMBNAILS
 *
 * output custom image thumbnails
 * @Original Addon Author: WooThemes
 * @since 6.0
 */
function bizz_get_image($key = 'image', $width = null, $height = null, $class = "thumbnail", $quality = 90,$id = null,$link = 'src',$repeat = 1,$offset = 0,$before = '', $after = '',$single = false, $force = false, $return = false, $is_auto_image = false, $src = '', $auto_meta = true, $meta = '', $cropp = '', $selflink = false, $filter = '', $sharpen = '', $zc = '', $noheight = '' ) {
	// Run new function
	bizz_image( 'key='.$key.'&width='.$width.'&height='.$height.'&class='.$class.'&quality='.$quality.'&id='.$id.'&link='.$link.'&repeat='.$repeat.'&offset='.$offset.'&before='.$before.'&after='.$after.'&single='.$single.'&force='.$force.'&return='.$return.'&is_auto_image='.$is_auto_image.'&src='.$src.'&auto_meta='.$auto_meta.'&meta='.$meta.'&cropp='.$cropp.'&selflink='.$selflink.'&filter='.$filter.'&sharpen='.$sharpen.'&zc='.$zc.'&noheight='.$noheight );
	return;
}

function bizz_image($args) {

	//Defaults
	$key 			= 'image'; 		// Custom field key eg. "image"
	$width 			= null; 		// Set width manually without using $type
	$height 		= null; 		// Set height manually without using $type
	$class 			= ''; 			// CSS class to use on the img tag eg. "alignleft". Default is "thumbnail"
	$quality 		= 90;			// Enter a quality between 80-100. Default is 90
	$id 			= null; 		// Assign a custom ID, if alternative is required.
	$link 			= 'src'; 		// Echo with image links ('src'), as image ('img'), as source ('source') or as resized source ('rsource').
	$repeat 		= 1; 			// Auto Img Function. Adjust amount of images to return for the post attachments.
	$offset 		= 0; 			// Auto Img Function. Offset the $repeat with assigned amount of objects.
	$before 		= ''; 			// Auto Img Function. Add Syntax before image output.
	$after 			= ''; 			// Auto Img Function. Add Syntax after image output.
	$single 		= false; 		// Auto Img Function Only. Forces "img" return on images, like on single.php template
	$force 			= false; 		// Force smaller images to not be effected with image width and height dimentions (proportions fix)
	$return 		= false; 		// Return results instead of echoing out.
	$is_auto_image 	= false; 		// A parameter that accepts a img url for resizing. (No anchor)
	$src 			= ''; 			// A parameter that accepts a img url for resizing. (No anchor)
	$auto_meta 		= true; 		// Disables meta generated by the post_id. When src is used, this setting is automatically set to false.
	$meta 			= ''; 			// Add a custom meta text to the image and anchor of the image.
	$cropp 			= ''; 			// Add crop position     
	/*  * a=position; example a=t (crop from the top)
	    * c : position in the center (this is the default)
		* t : align top
		* tr : align top right
		* tl : align top left
		* b : align bottom
		* br : align bottom right
		* bl : align bottom left
		* l : align left
		* r : align right)
	*/
	$alt 			= 'alt=""';		// Add alternative text
	$selflink 		= false; 		// Add crop position
	$filter 		= ''; 			// Add crop position
	$sharpen 		= ''; 			// Add crop position
	$zc 			= '1'; 			// Add zoom crop position
	$noheight 		= '';			// Responsive
	
	$attachment_id = array();
	$attachment_src = array();
	
	if ( !is_array($args) ) 
		parse_str( $args, $args );
	
	extract($args);
	
	if ( empty($id) )
		global $post;
	
    if ( empty($id) )
		$id = $post->ID;
		
	$thumb_id = get_post_meta($id,'_thumbnail_id',true);
	$thumb_url = wp_get_attachment_image_src($thumb_id,'large');  
	$thumb_url = $thumb_url[0];
	$meta_id = get_post_meta($id, $key, true);
		
	if ( $src != '' ) { // When a custom image is sent through
		$custom_field = $src;
		$link = 'img';
		$auto_meta = false;
	} 
	elseif( isset($GLOBALS['opt']['bizzthemes_thumb_show']['value']) && !empty($thumb_url) )
		$thumb_field = $thumb_url;		
	else
    	$custom_field = $meta_id;

	if ( empty($custom_field) && empty($thumb_field) && isset($GLOBALS['opt']['bizzthemes_auto_img']['value']) ) { // Get the image from post attachments
        
        if( $offset >= 1 ) 
			$repeat = $repeat + $offset;
    
        $attachments = get_children( 
			array(	
				'post_parent' => $id,
				'numberposts' => $repeat,
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => 'DESC', 
				'orderby' => 'menu_order date'
			)
		);

		if ( !empty($attachments) ) { // Search for and get the post attachment
       
			$counter = -1;
			$size = 'large';
			foreach ( $attachments as $att_id => $attachment ) {            
				$counter++;
				if ( $counter < $offset ) 
					continue;
			
				$src = wp_get_attachment_image_src($att_id, $size, true);
				$custom_field = $src[0];
				$is_auto_image = true;
				$attachment_id[] = $att_id;
				$src_arr[] = $custom_field;
			}

		} else { // Get the first img tag from the content

			$first_img = '';
			$post = get_post($id); 
			ob_start();
			ob_end_clean();
			$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
			if ( !empty($matches[1][0]) )
				$custom_field = $matches[1][0];

		}
		
	}
		
	// Return if there is no custom field and no thumbnail set (YouTube Thumbnails)
	if ( empty($custom_field) && empty($thumb_field) && version_compare(phpversion(), '5.0', '>=') ) {
		
		// require html dom library to extract objects
		load_template(BIZZ_FRAME_CLASSES . '/simple_html_dom.php');
		
		// check if meta key 'embed' is not empty
		$embed 		= get_post_meta($id, "embed", true);
		$post_dom 	= str_get_html( $post->post_content );
		$first_obj 	= $post_dom->find('object,iframe[src*=youtube]', 0);
				
		// check if oembed video is present
		$meta 		= get_post_custom($id);
		foreach ( (array) $meta as $key => $value)
			if ( false !== strpos($key, 'oembed') ) {
				// print_r($value[0]);
				if( preg_match('/youtube/', $value[0]) )
					$first_obj_oembed = $value[0];
			}
		
		// return if necessary
		if ( $embed )
	    	$custom_field = bizz_get_video_image( $embed );
		elseif ( $first_obj !== null )
			$custom_field = bizz_get_video_image( $first_obj->outertext );
		elseif ( isset( $first_obj_oembed ) )
			$custom_field = bizz_get_video_image( $first_obj_oembed );
		else
			return; #false if there is nothing to show
		
	}
	
	if( empty($src_arr) && empty($custom_field) )
	    $src_arr[] = $thumb_field; 
	elseif( empty($src_arr) )
	    $src_arr[] = $custom_field;
	
    $output = '';

	// Get standard sizes
	if ( !$width && !$height ) {
		$width = '100';
		$height = '100';
	}
	
    $set_width = ' width="' . $width .'" ';
	$set_height = '';
	if ( !$noheight )
    	$set_height = ' height="' . $height .'" '; 
    
    if($height == null OR $height == '')
        $set_height = '';
		
	// Set standard class
	if ( $class )
		$class = 'bizz-thumb ' . $class;
	else 
		$class = 'bizz-thumb';
		
	// Do check to verify if images are smaller then specified.
	if($force == true){  
		$set_width = '';
		$set_height = '';
	}

	// RESIZE IMAGES AUTOMATICALLY
	if ( isset($GLOBALS['opt']['bizzthemes_resize']['value']) && file_exists(TEMPLATEPATH . '/custom') ) {
	
		foreach( $src_arr as $key => $custom_field ){
	
			// Clean the image URL
			$href = $custom_field;
			$custom_field = str_ireplace(get_site_url().'/', '', $custom_field);
			$custom_field = cleanSource( $custom_field );

			// Check if WPMU and set correct path
			if ( function_exists('get_current_site') ) {
				global $blog_id;
				if ( !$blog_id ) {
					global $current_blog;
					$blog_id = $current_blog->blog_id;				
				}
				if ( isset($blog_id) && $blog_id > 0 ) {
					$imageParts = explode( 'files/', $custom_field );
					if ( isset($imageParts[1]) ) 
						$custom_field = '/wp-content/blogs.dir/' . $blog_id . '/files/' . $imageParts[1];
				}
			}
		
			//Set the ID to the Attachment's ID if it is an attachment
			$quick_id = ( $is_auto_image == true ) ? $attachment_id[$key] : $id;
			
			// meta info			
			if( $auto_meta == 'true' ) {
				$alt = 'alt="'. get_the_title($quick_id) .'"';
				$title = ' title="'. get_the_title($id) .'"';
			}
			elseif( $auto_meta == 'false' ) {
				$alt = 'alt="'. $meta.'"';
				$title = ' title="'. $meta .'"';
			} 
			else {
				$alt = 'alt=""';
				$title = '';
			}
			
			// crop position
			$acrop = ($cropp != '') ? $cropp : 'c';
			
			// filter
			$filter = ($filter != '') ? '&amp;f='.$filter : '';
				
			// sharpen
			$sharpen = ($sharpen != '') ? '&amp;s='.$sharpen : '';
			
			// zoom crop
			$zc = ($zc != '') ? $zc : '1';

			$img_rsource = BIZZ_FRAME_SCRIPTS . '/thumb.php?src='. $custom_field .'&amp;w='. $width .'&amp;h='. $height .'&amp;a='. $acrop . $filter . $sharpen .'&amp;zc='.$zc.'&amp;q='. $quality;
			$img_link = '<img src="'. $img_rsource .'" '.$alt . $title .' class="'. stripslashes($class) .'" '. $set_width . $set_height .' />';
			
			if( $link == 'img' ) {  // Just output the image
				$output .= $before; 
				$output .= $img_link;
				$output .= $after;  

			}
			elseif( $link == 'source' ) {  // Just output full image source
				$output .= home_url() . '/' . $custom_field;
				
			}
			elseif( $link == 'rsource' ) {  // Just output resized image source
				$output .= $img_rsource;
				
			} 
			else {  // Default - output with link				

				if ( ( is_single() OR is_page() ) AND $single == false ) {
					$rel = 'rel="lightbox"';
				} 
				elseif ( $selflink == true ) {
				    $rel = 'rel="lightbox"';
				} 
				else { 
					$href = get_permalink($id);
					$rel = '';
				}
			
				$output .= $before; 
				$output .= '<a '.$title.' href="' . $href .'" '.$rel.'>' . $img_link . '</a>';
				$output .= $after;  
			}
		}
		
	// DO NOT RESIZE IMAGES AUTOMATICALLY
	} else {
		
		foreach($src_arr as $key => $custom_field){
		
			//Set the ID to the Attachent's ID if it is an attachment
			if($is_auto_image == true){	
				$quick_id = $attachment_id[$key];
			} else {
			 	$quick_id = $id;
			}
			
			if($auto_meta == true) {
				$alt = 'alt="'. get_the_title($quick_id) .'"';
				$title = 'title="'. get_the_title($quick_id) .'"';
			} elseif($auto_meta == false) {
				$alt = 'alt="'. $meta.'"';
				$title = 'title="'. $meta .'"';
			} else {
				$alt = 'alt=""';
				$title = '';
			}
		
			$img_link =  '<img src="'. $custom_field .'" '. $alt .' '. $set_width . $set_height . ' class="'. stripslashes($class) .'"'. $set_width . $set_height .' />';
		
			if ( $link == 'img' ) {  // Just output the image 
				$output .= $before;                   
				$output .= $img_link; 
				$output .= $after;  
				
			} else {  // Default - output with link
			
				if ( ( is_single() OR is_page() ) AND $single == false ) { 
					$href = $custom_field;
					$rel = 'rel="lightbox"';
				} else { 
					$href = get_permalink($id);
					$rel = '';
				}
				 
				$output .= $before;   
				$output .= '<a '. $alt .' href="' . $href .'" '. $rel .'>' . $img_link . '</a>';
				$output .= $after;   
			}
		}
	}
	
	// Return or echo the output
	if ( $return == TRUE )
		return $output;
	else 
		echo $output; // Done  

}

// Get thumbnail from Video Embed code
function bizz_get_video_image($embed) { 

	// YouTube - get the video code if this is an embed code (old embed)
	preg_match('/youtube\.com\/v\/([\w\-]+)/', $embed, $match);
 
	// YouTube - if old embed returned an empty ID, try capuring the ID from the new iframe embed
	if( !isset($match[1]) )
		preg_match('/youtube\.com\/embed\/([\w\-]+)/', $embed, $match);
 
	// YouTube - if it is not an embed code, get the video code from the youtube URL	
	if($match[1] == '')
		preg_match('/v\=(.+)&/',$embed ,$match);
 
	// YouTube - get the corresponding thumbnail images	
	if($match[1] != '')
		$video_thumb = "http://img.youtube.com/vi/".$match[1]."/0.jpg";
 
	// return whichever thumbnail image you would like to retrieve
	return $video_thumb;		
}

// Tidy up the image source url
function cleanSource($src) {

	// remove slash from start of string
	if(strpos($src, "/") == 0) {
		$src = substr($src, -(strlen($src) - 1));
	}

	// Check if same domain so it doesn't strip external sites
	$host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
	if ( !strpos($src,$host) )
		return $src;


	$regex = "/^((ht|f)tp(s|):\/\/)(www\.|)" . $host . "/i";
	$src = preg_replace ($regex, '', $src);
    
    // remove slash from start of string
    if (strpos($src, '/') === 0) {
        $src = substr ($src, -(strlen($src) - 1));
    }
	
	return $src;
}

// Show image in RSS feed
if ( isset($GLOBALS['opt']['bizzthemes_image_rss']['value']) && $GLOBALS['opt']['bizzthemes_image_rss']['value'] == "true" )
	add_filter('the_content', 'add_image_RSS');
	
function add_image_RSS( $content ) {
	
	global $post, $id;
	$blog_key = substr( md5( home_url() ), 0, 16 );
	if ( ! is_feed() ) return $content;

	// Get the "image" from custom field
	$image = get_post_meta($post->ID, 'image', $single = true);
	$image_width = '200';

	// If there's an image, display the image with the content
	if($image !== '') {
		$content = '<p style="float:right; margin:0 0 10px 15px; width:'.$image_width.'px;">
		<img src="'.$image.'" width="'.$image_width.'" />
		</p>' . $content;
		return $content;
	} 

	// If there's not an image, just display the content
	else {
		$content = $content;
		return $content;
	}
	
}
