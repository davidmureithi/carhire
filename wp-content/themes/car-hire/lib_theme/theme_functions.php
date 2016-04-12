<?php
/* Include custom post types */
/*------------------------------------------------------------------*/
locate_template( 'lib_theme/cpt/post-type-slides.php', true );
locate_template( 'lib_theme/booking/booking-init.php', true );

/* Include Bootstrap. */
/*------------------------------------------------------------------*/
add_filter('bizz_bootstrap', 'enable_bizz_bootstrap');
function enable_bizz_bootstrap() {
	return true;
}

/* Set the content width based on the theme's design and stylesheet. */
/*------------------------------------------------------------------*/
if ( ! isset( $content_width ) )
	$content_width = 630;
	
/* Uregister default widgets. */
/*------------------------------------------------------------------*/
add_action( 'widgets_init', 'custom_unregister_widgets' );
function custom_unregister_widgets() {
	unregister_widget( 'Bizz_Widget_Authors' );
	unregister_widget( 'Bizz_Widget_Tags' );
	unregister_widget( 'Bizz_Widget_Categories' );
	unregister_widget( 'Bizz_Widget_Calendar' );
	// unregister_widget( 'Bizz_Widget_Flickr' );
	// unregister_widget( 'Bizz_Widget_Pages' );
	unregister_widget( 'WP_Widget_Recent_Comments' );
	unregister_widget( 'Bizz_Widget_Rich_Textarea' );
}

/* Additional FOOTER HTML elements. */
/*------------------------------------------------------------------*/
add_action( 'bizz_sidebar_grid_after', 'bizz_theme_footer_logo' ); # $tag, $function_to_add, $priority, $accepted_args
function bizz_theme_footer_logo( $grid ) {
	global $wp_query;
		
	if ( $grid == 'footer_four' )
		echo '<div class="foot-logo">'.apply_filters('bizz_footer_logo', bizz_footer_branding( true )).'</div>';
		
}

/* HTML5 conversion. */
/*------------------------------------------------------------------*/
add_filter('bizz_doctype', 'bizz_new_doctype');
function bizz_new_doctype() {
	return '<!DOCTYPE html>';
}
add_filter('bizz_head_profile', 'bizz_new_head_profile');
function bizz_new_head_profile() {
	return;
}
add_filter('bizz_html5_article', 'bizz_html5_article_return');
function bizz_html5_article_return() {
	return 'article';
}
add_filter('bizz_html5_section', 'bizz_html5_section_return');
function bizz_html5_section_return() {
	return 'section';
}
add_filter('bizz_html5_nav', 'bizz_html5_nav_return');
function bizz_html5_nav_return() {
	return 'nav';
}
add_filter('bizz_html5_header', 'bizz_html5_header_return');
function bizz_html5_header_return() {
	return 'header';
}
add_filter('bizz_html5_aside', 'bizz_html5_aside_return');
function bizz_html5_aside_return() {
	return 'aside';
}


/* Custom post meta. */
/*------------------------------------------------------------------*/
remove_action( 'bizz_hook_loop_content', 'bizz_post_meta_loop' );
remove_action( 'bizz_hook_query_content', 'bizz_post_meta_query' );
add_action( 'bizz_hook_after_headline', 'responsive_post_meta_loop' );
add_action( 'bizz_hook_query_after_headline', 'responsive_post_meta_loop' );
function responsive_post_meta_loop($args) {
	global $post;
	
	if (isset($args[0])) $args = $args[0]; #[0] array level
	
	$post_type = get_post_type( $post );
	
	if ( $post_type == 'post' ) {

		echo "<aside class=\"headline_meta\">";
		
		if ($args['post_author'])
			echo '<span class="auth"><a href="' . get_author_posts_url(get_the_author_meta('ID') ) . '" class="auth" rel="nofollow">' . get_the_author() . '</a></span>';
		if ($args['post_comments']) {
			echo '<span class="comm"><a href="' . get_permalink() . '#comments" rel="nofollow">';
			$num_comments = get_comments_number(); // get_comments_number returns only a numeric value

			if ( comments_open() ) {
			
				if ( $num_comments == 0 )
					$comments = __('No Comments');
				elseif ( $num_comments > 1 )
					$comments = sprintf(__('%d Comments'), $num_comments);
				else
					$comments = __('One Comment');

				echo '<a href="' . get_comments_link() .'">'. $comments.'</a>';
			} 
			else
				echo __('Comments are closed.');
			
			echo '</a></span>';
		}
		if ($args['post_categories'])	
			echo seo_post_cats();
		if ($args['post_tags'])		
			echo seo_post_tags();
		if ($args['post_edit']) {	
			if (current_user_can('manage_options') && is_user_logged_in())
				edit_post_link(__('Edit'), '<span class="edit">', '</span>');
		}
				
		echo "</aside>\n";
	
	}

}
// post date
add_action( 'bizz_hook_post_box_top', 'responsive_date_meta_loop' );
function responsive_date_meta_loop($args) {
	global $post;
	
	if (isset($args[0])) $args = $args[0]; #[0] array level
	
	$post_type = get_post_type( $post );
	
	if ( $args['post_date'] && $post_type == 'post' ) {
		echo "<div class=\"post_date\">";
		echo '<span class="month" title="' . get_the_time('Y-m-d') . '">' . get_the_time('M') . '</span>';
		echo '<span class="day" title="' . get_the_time('Y-m-d') . '">' . get_the_time('d') . '</span>';
		echo "</div>\n";
		echo "<div class=\"post_content\">";
	}

}
add_action( 'bizz_hook_post_box_bottom', 'responsive_close_post_content' );
function responsive_close_post_content($args) {
	global $post;
	
	$post_type = get_post_type( $post );
	
	if ( $args['post_date'] && $post_type == 'post' )
		echo "</div>\n";

}

/* Custom comments. */
/*------------------------------------------------------------------*/
add_action('init', 'remove_comments_rewrite');
function remove_comments_rewrite() {
    remove_action('comment_container', 'bizz_comment_container', 10, 3);
}
add_action('comment_container', 'custom_comment_container', 10, 3);
function custom_comment_container( $comment, $args, $depth ) {

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
	
?>
	
	<div id="div-comment-<?php comment_ID(); ?>" class="comment-container">
	
	    <div class="avatar-wrap">
			<?php echo get_avatar( $comment, 48 ); ?>
		</div><!-- /.meta-wrap -->
		
		<div class="text-right">
		
			<div class="comm-meta <?php if (1 == $comment->user_id) echo "authcomment"; ?>">
				<?php echo bizz_comment_meta( $args['comment_meta'] ); ?>
			</div><!-- /.comm-meta -->
							
			<div class="comment-entry">
			    <?php comment_text() ?>
				<?php if ( '0' == $comment->comment_approved ) : ?>
				    <p class="comment-moderation"><?php _e( $args['comment_moderation'] ); ?></p>
				<?php endif; ?>
			</div><!-- /.comment-entry -->
			
			<?php if ( $args['enable_reply'] ): ?>
				<div class="comm-reply">
				<?php comment_reply_link( array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'])) ); ?>
				</div><!-- /.comm-reply -->
			<?php endif; ?>
			
		</div><!-- /.text-right -->
			
	</div><!-- /.comment-container -->
	
<?php

}

/* DEFAULT LAYOUT OPTIONS */
/*------------------------------------------------------------------*/

// set default layouts
$default_layouts_array = 'a:4:{s:8:"theme_id";s:8:"car-hire";s:13:"frame_version";s:5:"7.8.3";s:10:"options_id";s:7:"layouts";s:13:"options_value";a:4:{s:11:"all_widgets";a:21:{i:0;a:3:{s:11:"option_name";s:0:"";s:12:"option_value";b:0;s:4:"type";s:6:"widget";}i:1;a:3:{s:11:"option_name";s:11:"widget_meta";s:12:"option_value";a:2:{i:3;a:1:{s:5:"title";s:0:"";}s:12:"_multiwidget";i:1;}s:4:"type";s:6:"widget";}i:2;a:3:{s:11:"option_name";s:11:"widget_text";s:12:"option_value";a:2:{i:3;a:3:{s:5:"title";s:8:"About Us";s:4:"text";s:353:"At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.";s:6:"filter";b:0;}s:12:"_multiwidget";i:1;}s:4:"type";s:6:"widget";}i:3;a:3:{s:11:"option_name";s:10:"widget_rss";s:12:"option_value";a:0:{}s:4:"type";s:6:"widget";}i:4;a:3:{s:11:"option_name";s:37:"widget_widgets-reloaded-bizz-archives";s:12:"option_value";b:0;s:4:"type";s:6:"widget";}i:5;a:3:{s:11:"option_name";s:38:"widget_widgets-reloaded-bizz-bookmarks";s:12:"option_value";a:3:{i:2;a:22:{s:8:"title_li";s:9:"Bookmarks";s:14:"category_order";s:3:"ASC";s:16:"category_orderby";s:5:"count";s:5:"class";s:7:"linkcat";s:5:"limit";s:0:"";s:5:"order";s:3:"ASC";s:7:"orderby";s:2:"id";s:6:"search";s:0:"";s:7:"between";s:0:"";s:11:"link_before";s:6:"<span>";s:10:"link_after";s:7:"</span>";s:10:"categorize";i:1;s:14:"hide_invisible";i:1;s:8:"category";N;s:7:"include";N;s:7:"exclude";N;s:12:"show_private";i:0;s:11:"show_rating";i:0;s:12:"show_updated";i:0;s:11:"show_images";i:0;s:9:"show_name";i:0;s:16:"show_description";i:0;}i:3;a:22:{s:8:"title_li";s:9:"Bookmarks";s:14:"category_order";s:3:"ASC";s:16:"category_orderby";s:5:"count";s:5:"class";s:7:"linkcat";s:5:"limit";s:0:"";s:5:"order";s:3:"ASC";s:7:"orderby";s:2:"id";s:6:"search";s:0:"";s:7:"between";s:0:"";s:11:"link_before";s:6:"<span>";s:10:"link_after";s:7:"</span>";s:10:"categorize";i:1;s:14:"hide_invisible";i:1;s:8:"category";N;s:7:"include";N;s:7:"exclude";N;s:12:"show_private";i:0;s:11:"show_rating";i:0;s:12:"show_updated";i:0;s:11:"show_images";i:0;s:9:"show_name";i:0;s:16:"show_description";i:0;}s:12:"_multiwidget";i:1;}s:4:"type";s:6:"widget";}i:6;a:3:{s:11:"option_name";s:25:"widget_bizz-comments-loop";s:12:"option_value";a:2:{i:2;a:23:{s:4:"type";s:3:"all";s:14:"comment_header";s:2:"h3";s:12:"comment_meta";s:86:"[author] [date before="&middot; "] [link before="&middot; "] [edit before="&middot; "]";s:9:"max_depth";s:1:"5";s:17:"enable_pagination";b:1;s:12:"enable_reply";b:1;s:18:"comment_moderation";s:36:"Your comment is awaiting moderation.";s:10:"reply_text";s:5:"Reply";s:10:"login_text";s:15:"Log in to Reply";s:13:"password_text";s:18:"Password Protected";s:19:"pass_protected_text";s:59:"is password protected. Enter the password to view comments.";s:17:"sing_comment_text";s:7:"comment";s:16:"plu_comment_text";s:8:"comments";s:19:"sing_trackback_text";s:9:"trackback";s:18:"plu_trackback_text";s:10:"trackbacks";s:18:"sing_pingback_text";s:8:"pingback";s:17:"plu_pingback_text";s:9:"pingbacks";s:14:"sing_ping_text";s:4:"ping";s:13:"plu_ping_text";s:5:"pings";s:7:"no_text";s:2:"No";s:7:"to_text";s:2:"to";s:17:"reverse_top_level";b:0;s:15:"comments_closed";s:0:"";}s:12:"_multiwidget";i:1;}s:4:"type";s:6:"widget";}i:7;a:3:{s:11:"option_name";s:35:"widget_widgets-reloaded-bizz-c-form";s:12:"option_value";b:0;s:4:"type";s:6:"widget";}i:8;a:3:{s:11:"option_name";s:35:"widget_widgets-reloaded-bizz-flickr";s:12:"option_value";b:0;s:4:"type";s:6:"widget";}i:9;a:3:{s:11:"option_name";s:33:"widget_widgets-reloaded-bizz-logo";s:12:"option_value";a:2:{i:2;a:3:{s:11:"custom_logo";s:8:"def_logo";s:11:"upload_logo";s:0:"";s:11:"custom_link";s:7:"http://";}s:12:"_multiwidget";i:1;}s:4:"type";s:6:"widget";}i:10;a:3:{s:11:"option_name";s:33:"widget_widgets-reloaded-bizz-loop";s:12:"option_value";a:2:{i:2;a:23:{s:9:"post_date";i:1;s:13:"post_comments";i:1;s:15:"post_categories";i:1;s:9:"post_tags";i:1;s:12:"post_columns";s:1:"1";s:9:"read_more";i:1;s:14:"read_more_text";s:16:"Continue reading";s:17:"enable_pagination";i:1;s:11:"thumb_width";s:3:"150";s:12:"thumb_height";s:3:"150";s:11:"thumb_align";s:10:"alignright";s:11:"thumb_cropp";s:1:"c";s:12:"thumb_filter";s:0:"";s:13:"thumb_sharpen";s:0:"";s:11:"post_author";i:0;s:9:"post_edit";i:0;s:9:"post_meta";i:0;s:13:"thumb_display";i:0;s:12:"thumb_single";i:0;s:14:"thumb_selflink";i:0;s:12:"remove_posts";i:0;s:10:"full_posts";i:0;s:15:"ajax_pagination";i:0;}s:12:"_multiwidget";i:1;}s:4:"type";s:6:"widget";}i:11;a:3:{s:11:"option_name";s:37:"widget_widgets-reloaded-bizz-nav-menu";s:12:"option_value";a:2:{i:2;a:16:{s:5:"title";s:0:"";s:4:"menu";s:2:"13";s:9:"container";s:3:"div";s:12:"container_id";s:0:"";s:15:"container_class";s:0:"";s:7:"menu_id";s:0:"";s:10:"menu_class";s:8:"nav-menu";s:5:"depth";s:1:"0";s:6:"before";s:0:"";s:5:"after";s:0:"";s:11:"link_before";s:0:"";s:10:"link_after";s:0:"";s:11:"fallback_cb";s:12:"wp_page_menu";s:6:"walker";s:0:"";s:18:"use_desc_for_title";i:0;s:8:"vertical";i:0;}s:12:"_multiwidget";i:1;}s:4:"type";s:6:"widget";}i:12;a:3:{s:11:"option_name";s:34:"widget_widgets-reloaded-bizz-pages";s:12:"option_value";b:0;s:4:"type";s:6:"widget";}i:13;a:3:{s:11:"option_name";s:23:"widget_bizz-query-posts";s:12:"option_value";b:0;s:4:"type";s:6:"widget";}i:14;a:3:{s:11:"option_name";s:35:"widget_widgets-reloaded-bizz-search";s:12:"option_value";a:2:{i:2;a:2:{s:5:"title";s:0:"";s:11:"search_text";s:0:"";}s:12:"_multiwidget";i:1;}s:4:"type";s:6:"widget";}i:15;a:3:{s:11:"option_name";s:14:"widget_twitter";s:12:"option_value";b:0;s:4:"type";s:6:"widget";}i:16;a:3:{s:11:"option_name";s:18:"widget_bizz_slider";s:12:"option_value";a:2:{i:2;a:14:{s:9:"post_type";s:11:"bizz_slides";s:5:"order";s:3:"ASC";s:7:"orderby";s:10:"menu_order";s:6:"number";s:2:"10";s:13:"slidecontrols";i:1;s:10:"pausehover";i:1;s:8:"nextprev";i:1;s:6:"height";s:3:"365";s:5:"start";s:1:"1";s:10:"slidespeed";s:1:"7";s:14:"animationspeed";s:1:"6";s:5:"title";s:0:"";s:7:"include";a:0:{}s:7:"exclude";a:0:{}}s:12:"_multiwidget";i:1;}s:4:"type";s:6:"widget";}i:17;a:3:{s:11:"option_name";s:19:"widget_bizz_adspace";s:12:"option_value";a:1:{s:12:"_multiwidget";i:1;}s:4:"type";s:6:"widget";}i:18;a:3:{s:11:"option_name";s:18:"widget_bizz_social";s:12:"option_value";a:2:{i:2;a:9:{s:5:"title";s:12:"Social Links";s:8:"facebook";s:21:"http://bizzthemes.com";s:7:"twitter";s:21:"http://bizzthemes.com";s:6:"flickr";s:21:"http://bizzthemes.com";s:7:"youtube";s:21:"http://bizzthemes.com";s:8:"linkedin";s:21:"http://bizzthemes.com";s:6:"google";s:21:"http://bizzthemes.com";s:8:"dribbble";s:21:"http://bizzthemes.com";s:6:"tumblr";s:21:"http://bizzthemes.com";}s:12:"_multiwidget";i:1;}s:4:"type";s:6:"widget";}i:19;a:3:{s:11:"option_name";s:17:"widget_bizz_cinfo";s:12:"option_value";a:2:{i:2;a:7:{s:5:"title";s:0:"";s:10:"small_meta";s:5:"Email";s:5:"small";s:19:"info@bizzthemes.com";s:10:"large_meta";s:7:"Call Us";s:5:"large";s:15:"+386 31 333 555";s:10:"small_link";s:26:"mailto:info@bizzthemes.com";s:10:"large_link";s:15:"tel:38631333555";}s:12:"_multiwidget";i:1;}s:4:"type";s:6:"widget";}i:20;a:3:{s:11:"option_name";s:19:"widget_bizz_booking";s:12:"option_value";a:2:{i:2;a:2:{s:5:"title";s:11:"Rent a car:";s:5:"intro";s:161:"Even through walk-ins are ok, we encourage your to contact us via the form below to set your appointment. You will be contacted within 24 hours for confirmation.";}s:12:"_multiwidget";i:1;}s:4:"type";s:6:"widget";}}s:12:"widget_posts";a:14:{i:0;a:7:{s:10:"post_title";s:3:"all";s:12:"post_excerpt";s:8:"is_index";s:11:"post_status";s:7:"publish";s:9:"post_type";s:11:"bizz_widget";s:12:"post_content";s:141:"a:5:{s:9:"widget-id";s:12:"bizz_cinfo-2";s:9:"condition";s:8:"is_index";s:4:"item";s:3:"all";s:6:"parent";s:5:"false";s:4:"show";s:4:"true";}";s:21:"post_content_filtered";s:8:"car-hire";s:4:"type";s:7:"widgets";}i:1;a:7:{s:10:"post_title";s:3:"all";s:12:"post_excerpt";s:8:"is_index";s:11:"post_status";s:7:"publish";s:9:"post_type";s:11:"bizz_widget";s:12:"post_content";s:134:"a:5:{s:9:"widget-id";s:6:"text-3";s:9:"condition";s:8:"is_index";s:4:"item";s:3:"all";s:6:"parent";s:5:"false";s:4:"show";s:4:"true";}";s:21:"post_content_filtered";s:8:"car-hire";s:4:"type";s:7:"widgets";}i:2;a:7:{s:10:"post_title";s:3:"all";s:12:"post_excerpt";s:8:"is_index";s:11:"post_status";s:7:"publish";s:9:"post_type";s:11:"bizz_widget";s:12:"post_content";s:157:"a:5:{s:9:"widget-id";s:28:"widgets-reloaded-bizz-loop-2";s:9:"condition";s:8:"is_index";s:4:"item";s:3:"all";s:6:"parent";s:5:"false";s:4:"show";s:4:"true";}";s:21:"post_content_filtered";s:8:"car-hire";s:4:"type";s:7:"widgets";}i:3;a:7:{s:10:"post_title";s:3:"all";s:12:"post_excerpt";s:8:"is_index";s:11:"post_status";s:7:"publish";s:9:"post_type";s:11:"bizz_widget";s:12:"post_content";s:159:"a:5:{s:9:"widget-id";s:30:"widgets-reloaded-bizz-search-2";s:9:"condition";s:8:"is_index";s:4:"item";s:3:"all";s:6:"parent";s:5:"false";s:4:"show";s:4:"true";}";s:21:"post_content_filtered";s:8:"car-hire";s:4:"type";s:7:"widgets";}i:4;a:7:{s:10:"post_title";s:3:"all";s:12:"post_excerpt";s:8:"is_index";s:11:"post_status";s:7:"publish";s:9:"post_type";s:11:"bizz_widget";s:12:"post_content";s:162:"a:5:{s:9:"widget-id";s:33:"widgets-reloaded-bizz-bookmarks-2";s:9:"condition";s:8:"is_index";s:4:"item";s:3:"all";s:6:"parent";s:5:"false";s:4:"show";s:4:"true";}";s:21:"post_content_filtered";s:8:"car-hire";s:4:"type";s:7:"widgets";}i:5;a:7:{s:10:"post_title";s:3:"all";s:12:"post_excerpt";s:8:"is_index";s:11:"post_status";s:7:"publish";s:9:"post_type";s:11:"bizz_widget";s:12:"post_content";s:157:"a:5:{s:9:"widget-id";s:28:"widgets-reloaded-bizz-logo-2";s:9:"condition";s:8:"is_index";s:4:"item";s:3:"all";s:6:"parent";s:5:"false";s:4:"show";s:4:"true";}";s:21:"post_content_filtered";s:8:"car-hire";s:4:"type";s:7:"widgets";}i:6;a:7:{s:10:"post_title";s:3:"all";s:12:"post_excerpt";s:8:"is_index";s:11:"post_status";s:7:"publish";s:9:"post_type";s:11:"bizz_widget";s:12:"post_content";s:161:"a:5:{s:9:"widget-id";s:32:"widgets-reloaded-bizz-nav-menu-2";s:9:"condition";s:8:"is_index";s:4:"item";s:3:"all";s:6:"parent";s:5:"false";s:4:"show";s:4:"true";}";s:21:"post_content_filtered";s:8:"car-hire";s:4:"type";s:7:"widgets";}i:7;a:7:{s:10:"post_title";s:3:"all";s:12:"post_excerpt";s:8:"is_index";s:11:"post_status";s:7:"publish";s:9:"post_type";s:11:"bizz_widget";s:12:"post_content";s:134:"a:5:{s:9:"widget-id";s:6:"meta-3";s:9:"condition";s:8:"is_index";s:4:"item";s:3:"all";s:6:"parent";s:5:"false";s:4:"show";s:4:"true";}";s:21:"post_content_filtered";s:8:"car-hire";s:4:"type";s:7:"widgets";}i:8;a:7:{s:10:"post_title";s:3:"all";s:12:"post_excerpt";s:8:"is_index";s:11:"post_status";s:7:"publish";s:9:"post_type";s:11:"bizz_widget";s:12:"post_content";s:162:"a:5:{s:9:"widget-id";s:33:"widgets-reloaded-bizz-bookmarks-3";s:9:"condition";s:8:"is_index";s:4:"item";s:3:"all";s:6:"parent";s:5:"false";s:4:"show";s:4:"true";}";s:21:"post_content_filtered";s:8:"car-hire";s:4:"type";s:7:"widgets";}i:9;a:7:{s:10:"post_title";s:3:"all";s:12:"post_excerpt";s:8:"is_index";s:11:"post_status";s:7:"publish";s:9:"post_type";s:11:"bizz_widget";s:12:"post_content";s:142:"a:5:{s:9:"widget-id";s:13:"bizz_social-2";s:9:"condition";s:8:"is_index";s:4:"item";s:3:"all";s:6:"parent";s:5:"false";s:4:"show";s:4:"true";}";s:21:"post_content_filtered";s:8:"car-hire";s:4:"type";s:7:"widgets";}i:10;a:7:{s:10:"post_title";s:3:"all";s:12:"post_excerpt";s:13:"is_front_page";s:11:"post_status";s:7:"publish";s:9:"post_type";s:11:"bizz_widget";s:12:"post_content";s:149:"a:5:{s:9:"widget-id";s:14:"bizz_booking-2";s:9:"condition";s:13:"is_front_page";s:4:"item";s:3:"all";s:6:"parent";s:5:"false";s:4:"show";s:4:"true";}";s:21:"post_content_filtered";s:8:"car-hire";s:4:"type";s:7:"widgets";}i:11;a:7:{s:10:"post_title";s:3:"all";s:12:"post_excerpt";s:13:"is_front_page";s:11:"post_status";s:7:"publish";s:9:"post_type";s:11:"bizz_widget";s:12:"post_content";s:148:"a:5:{s:9:"widget-id";s:13:"bizz_slider-2";s:9:"condition";s:13:"is_front_page";s:4:"item";s:3:"all";s:6:"parent";s:5:"false";s:4:"show";s:4:"true";}";s:21:"post_content_filtered";s:8:"car-hire";s:4:"type";s:7:"widgets";}i:12;a:7:{s:10:"post_title";s:4:"post";s:12:"post_excerpt";s:11:"is_singular";s:11:"post_status";s:7:"publish";s:9:"post_type";s:11:"bizz_widget";s:12:"post_content";s:154:"a:5:{s:9:"widget-id";s:20:"bizz-comments-form-2";s:9:"condition";s:11:"is_singular";s:4:"item";s:4:"post";s:6:"parent";s:5:"false";s:4:"show";s:4:"true";}";s:21:"post_content_filtered";s:8:"car-hire";s:4:"type";s:7:"widgets";}i:13;a:7:{s:10:"post_title";s:4:"post";s:12:"post_excerpt";s:11:"is_singular";s:11:"post_status";s:7:"publish";s:9:"post_type";s:11:"bizz_widget";s:12:"post_content";s:154:"a:5:{s:9:"widget-id";s:20:"bizz-comments-loop-2";s:9:"condition";s:11:"is_singular";s:4:"item";s:4:"post";s:6:"parent";s:5:"false";s:4:"show";s:4:"true";}";s:21:"post_content_filtered";s:8:"car-hire";s:4:"type";s:7:"widgets";}}s:10:"grid_posts";a:1:{i:0;a:7:{s:10:"post_title";s:3:"all";s:12:"post_excerpt";s:13:"is_front_page";s:11:"post_status";s:7:"publish";s:9:"post_type";s:9:"bizz_grid";s:12:"post_content";s:656:"a:4:{s:11:"header_area";a:5:{s:2:"id";s:11:"header_area";s:4:"name";s:11:"Header Area";s:4:"show";s:4:"true";s:9:"condition";s:13:"is_front_page";s:4:"item";s:3:"all";}s:13:"featured_area";a:5:{s:2:"id";s:13:"featured_area";s:4:"name";s:13:"Featured Area";s:4:"show";s:4:"true";s:9:"condition";s:13:"is_front_page";s:4:"item";s:3:"all";}s:9:"main_area";a:5:{s:2:"id";s:9:"main_area";s:4:"name";s:9:"Main Area";s:4:"show";s:5:"false";s:9:"condition";s:13:"is_front_page";s:4:"item";s:3:"all";}s:11:"footer_area";a:5:{s:2:"id";s:11:"footer_area";s:4:"name";s:11:"Footer Area";s:4:"show";s:4:"true";s:9:"condition";s:13:"is_front_page";s:4:"item";s:3:"all";}}";s:21:"post_content_filtered";s:8:"car-hire";s:4:"type";s:5:"grids";}}s:16:"sidebars_widgets";a:1:{i:0;a:3:{s:11:"option_name";s:16:"sidebars_widgets";s:12:"option_value";a:15:{s:19:"wp_inactive_widgets";a:0:{}s:9:"sidebar-1";a:1:{i:0;s:28:"widgets-reloaded-bizz-logo-2";}s:9:"sidebar-2";a:1:{i:0;s:12:"bizz_cinfo-2";}s:9:"sidebar-3";a:1:{i:0;s:32:"widgets-reloaded-bizz-nav-menu-2";}s:9:"sidebar-4";a:1:{i:0;s:14:"bizz_booking-2";}s:9:"sidebar-5";a:1:{i:0;s:13:"bizz_slider-2";}s:9:"sidebar-6";a:0:{}s:9:"sidebar-7";a:2:{i:0;s:28:"widgets-reloaded-bizz-loop-2";i:1;s:20:"bizz-comments-loop-2";}s:9:"sidebar-8";a:2:{i:0;s:30:"widgets-reloaded-bizz-search-2";i:1;s:33:"widgets-reloaded-bizz-bookmarks-2";}s:9:"sidebar-9";a:1:{i:0;s:6:"meta-3";}s:10:"sidebar-10";a:1:{i:0;s:33:"widgets-reloaded-bizz-bookmarks-3";}s:10:"sidebar-11";a:1:{i:0;s:13:"bizz_social-2";}s:10:"sidebar-12";a:1:{i:0;s:6:"text-3";}s:21:"bizz_inactive_widgets";a:0:{}s:13:"array_version";i:3;}s:4:"type";s:16:"sidebars_widgets";}}}}';