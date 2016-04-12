<?php
/*	
	
	WIDGET FILTERS:

    widget_title			- widget title
	wslider_args	 		- slider options
	wslider_args	 		- arguments for get_posts for slider
	wslider_post_title		- slider post title
	
*/

// WIDGET CLASS
class Bizz_Widget_Slider extends WP_Widget {

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 0.6
	 */
	function Bizz_Widget_Slider() {
		
		$widget_ops = array( 'classname' => 'widget_slider', 'description' => __( 'Displaying any of your posts inside slider.' ) );
		$control_ops = array( 'id_base' => "bizz_slider" );
		$this->WP_Widget( "bizz_slider", __( 'Slider' ), $widget_ops, $control_ops );
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 * @since 0.6
	 */
	function widget( $args, $instance ) {
		global $post;
		
		extract( $args );

		$args = array();
		
		$args['post_type'] = $instance['post_type'];
		$args['include'] = isset( $instance['include'] ) ? $instance['include'] : array();
		$args['include'] = ( !is_array($args['include']) && !empty($args['include']) ) ? explode(",", $args['include']) : $args['include'];
		$args['exclude'] = isset( $instance['exclude'] ) ? $instance['exclude'] : array();
		$args['exclude'] = ( !is_array($args['exclude']) && !empty($args['exclude']) ) ? explode(",", $args['exclude']) : $args['exclude'];
		$args['order'] = $instance['order'];
		$args['orderby'] = $instance['orderby'];
		$args['number'] = intval( $instance['number'] );
				
		$args['height'] = isset( $instance['height'] ) ? $instance['height'] : 365;
		$args['start'] = isset( $instance['start'] ) ? ($instance['start']-1) : 0;
		$args['slidespeed'] = isset( $instance['slidespeed'] ) ? $instance['slidespeed'] : 7;
		$args['animationspeed'] = isset( $instance['animationspeed'] ) ? $instance['animationspeed'] : 6;
		$args['pausehover'] = isset( $instance['pausehover'] ) ? $instance['pausehover'] : true;
		$args['nextprev'] = isset( $instance['nextprev'] ) ? $instance['nextprev'] : true;
		$args['slidecontrols'] = isset( $instance['slidecontrols'] ) ? $instance['slidecontrols'] : true;
		
		/* START
		------------------------------------------------------ */

		echo $before_widget;
		
		// slider options
		$slide_options = array(
			'featured_entries' => $args['number'],
			'featured_height' => $args['height'],
			'featured_tags' => '',
			'featured_sliding_direction' => 'horizontal',
			'featured_effect' => 'fade',
			'featured_start' => $args['start'],
			'featured_speed' => $args['slidespeed'],
			'featured_hover' => $args['pausehover'],
			'featured_touchswipe' => 'true',
			'featured_animation_speed' => $args['animationspeed'],
			'featured_slidecontrols' => $args['slidecontrols'],
			'featured_nextprev' => $args['nextprev']
		);
		$settings = apply_filters( 'wslider_options',  $slide_options );
		
		// query options
		$slide_args = array(
		    'post_type'     	=> $args['post_type'],
			'include'       	=> $args['include'],
			'exclude'       	=> $args['exclude'],
			'order'         	=> $args['order'],
			'orderby'       	=> $args['orderby'],
			'numberposts'   	=> $args['number'],
			'suppress_filters' 	=> 0
		); 
		$slides = get_posts( apply_filters( 'wslider_args',  $slide_args ) );
		
		// Set up variables
		$height = $settings['featured_height'];
		
		echo "<div class=\"slide-container\">\n";
		echo "<section class=\"bizz_fs\">\n";
			echo "<ul class=\"slides clearfix\">\n";
			
			$exclude = array();
			$count = 0;
			foreach ($slides as $post) {
				setup_postdata($post); $exclude[$count] = $post->ID; $count++;
				
				// Set up variables
				$has_embed = bizz_embed('key=bizzthemes_slide_vid&width=600&height='.$height.'&class=slide-video&id='.$post->ID);
				$has_image = get_post_meta($post->ID, "bizzthemes_slide_img", true);
				$has_content = get_post_meta($post->ID, "bizzthemes_slide_textarea", true);
				$is_image = bizz_image('key=bizzthemes_slide_img&width=980&class=slide-image&link=img&return=true&cropp=c');
				$not_video = ( !$has_embed ) ? 'not-video' : '';
				
				// Get image data
				$argst = array( 
					'post_parent' => $post->ID,
					'post_type' => 'attachment',
					'post_mime_type' => 'image',
					'post_status' => 'inherit',
					'posts_per_page' => '-1',
					'guid' => get_post_meta( $post->ID, 'bizzthemes_slide_img', true )
				);
				$get_children_array = get_children($argst, ARRAY_A);
				if ( $get_children_array ) :
					$rekeyed_array = array_values($get_children_array);
					$child_image = $rekeyed_array[0];
					// img data
					$img_caption = $child_image['post_excerpt'];
					$img_description = $child_image['post_content'];
					$img_custom_link = get_post_meta( $child_image['ID'], 'be_custom_url', true );
					$img_custom_link = ( isset($img_custom_link) ) ? $img_custom_link : bizz_image( 'link=source&return=true' );
					$img_overlay_position = ' position_'.get_post_meta( $child_image['ID'], 'be_overlay_position', true );;
					
					$is_image_html = "<div class=\"slide-img-wrap{$img_overlay_position}\">";
					$is_image_html .= "<a href=\"{$img_custom_link}\" title=\"".the_title_attribute (array('echo' => 0) )."\">{$is_image}</a>";
					if ( ($img_caption || $img_description) && $img_overlay_position != 4 ) {
						$is_image_html .= "<div class=\"overlay\">&nbsp;</div>";
						$is_image_html .= "<div class=\"overlay-content\">";
						if ( $img_caption )
							$is_image_html .= "<h4>".$child_image['post_excerpt']."</h4>";
						if ( $img_description )
							$is_image_html .= "<div class=\"overlay-text\">".$child_image['post_content']."</div>";
						$is_image_html .= "<div class=\"read-more\"><a href=\"{$img_custom_link}\">Read more</a></div>";
						$is_image_html .= "</div>";
					}
					$is_image_html .= "</div>";
				endif;
				
				echo "<li id=\"slide-".$count."\" class=\"slide post slide-id-".get_the_ID()."\">\n";
					
					echo "<article class=\"slide-content ".$not_video."\">\n";
					
					// Don't output content if there is a image or a video
					if ( !$has_embed && !$has_image ) {
						echo "<section class=\"entry\">\n";
							echo apply_filters('the_content', $has_content);
							if ( empty($has_content) )
								the_content( __( 'Continue Reading &rarr;' ) );
						echo "</section>\n";
					}
					
					echo "</article>\n";
					
					// If there is an image or video 
					if ( $has_embed || $has_image ) {
						echo "<div class=\"slide-img\">\n";

	    	    		if ( $has_embed && $has_image )
	        				echo $is_image_html;
						elseif ( $has_embed )
	        				echo $has_embed;
	        			else
	        				echo $is_image_html;
						
						echo "</div>\n";
					}
					
				echo "</li>\n";
				
			}
			
			echo "</ul>\n";			

		echo "</section>\n";
		echo "</div>\n";
		
		// Slider Settings
		$slideDirection 	= $settings['featured_sliding_direction'];
		$animation 			= $settings['featured_effect'];
		$slideshow 			= ( $settings['featured_speed'] == 0 ) ? 'false' : 'true';
		$slideToStart 		= $settings['featured_start'];
		$pauseOnHover 		= $settings['featured_hover'];
		$touchSwipe 		= $settings['featured_touchswipe'];
		$slideshowSpeed 	= $settings['featured_speed'] * 1000; // milliseconds
		$animationDuration 	= $settings['featured_animation_speed'] * 100; // milliseconds
		$slidecontrols 		= $settings['featured_slidecontrols'];
		$nextprev 			= $settings['featured_nextprev']; 
		
		$slideshow_js = '
<script type="text/javascript">
	jQuery(window).load(function() {
		jQuery(".bizz_fs").flexslider({
			
			controlsContainer: ".slide-container",
			slideDirection: "'.$slideDirection.'",
			animation: "'.$animation.'",
			slideshow: '.$slideshow.',
			slideToStart: '.$slideToStart.',
			directionNav: '.$nextprev.',
			controlNav: '.$slidecontrols.',
			pauseOnHover: '.$pauseOnHover.',
			slideshowSpeed: '.$slideshowSpeed.', 
			animationDuration: '.$animationDuration.'
		
		});
		
		// Slider loaded
		jQuery(".bizz_fs").addClass("loaded");
		
		// Feedback forward/back show/hide
		jQuery(".slide-container").mouseover(function() {
		    jQuery(this).find(".flex-direction-nav").stop().fadeTo(300, 1);
		});
		jQuery(".slide-container").mouseout(function() {
		    jQuery(this).find(".flex-direction-nav").stop().fadeTo(800, 0);
		});
	});
</script>
		';
		echo apply_filters( 'wslider_js',  $slideshow_js );

		echo $after_widget;
		
		/* END
		------------------------------------------------------ */
		
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 * @since 0.6
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = $new_instance;
		
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['post_type'] = $new_instance['post_type'];
		if ( $instance['post_type'] !== $old_instance['post_type'] ) {
			$instance['include'] = array();
			$instance['exclude'] = array();
		}
		$instance['orderby'] = $new_instance['orderby'];
		$instance['order'] = $new_instance['order'];
		$instance['number'] = strip_tags( $new_instance['number'] );
			
		$instance['height'] = strip_tags( $new_instance['height'] );
		$instance['start'] = strip_tags( $new_instance['start'] );
		$instance['slidespeed'] = strip_tags( $new_instance['slidespeed'] );
		$instance['animationspeed'] = strip_tags( $new_instance['animationspeed'] );
		$instance['pausehover'] = ( isset( $new_instance['pausehover'] ) ? 1 : 0 );
		$instance['nextprev'] = ( isset( $new_instance['nextprev'] ) ? 1 : 0 );
		$instance['slidecontrols'] = ( isset( $new_instance['slidecontrols'] ) ? 1 : 0 );	

		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 * @since 0.6
	 */
	function form( $instance ) {

		//Defaults
		$defaults = array(
			'post_type' => 'bizz_slides',
			'include' => '',
			'exclude' => '',
			'order' => 'ASC',
			'orderby' => 'menu_order',
			'number' => 10,
			'height' => 365,
			'start' => 1,
			'slidespeed' => 7,
			'animationspeed' => 6,
			'pausehover' => true,
			'nextprev' => true,
			'slidecontrols' => true
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$post_types = get_post_types(array(),'objects');
		$count_posts = wp_count_posts($instance['post_type']);
		if ( $count_posts->publish <= 500 ) {
			$posts = get_posts( array(
				'post_type' => $instance['post_type'], 
				'post_status' => 'publish', 
				'post_mime_type' => '', 
				'orderby' => 'menu_order', 
				'order' => 'ASC', 
				'numberposts' => 500			
			) );
		}

		$num_array['0'] = 'Off';
		for ($i = 1; $i <= 20; $i += 1){
			$num_array[$i] = $i.' sec';
		}
		
		for ($i = 1; $i <= 20; $i += 1){
			$num_array_two[$i] = $i.' sec';
		}
		
		for ($i = 1; $i <= 20; $i += 1){
			$num_array_three[$i] = $i;
		}
		
		$order = array( 'ASC' => __( 'Ascending' ), 'DESC' => __( 'Descending' ) );
		$orderby = array(
		    'author' => __( 'Author' ), 
			'category' => __( 'Category' ),
			'content' => __( 'Content' ),
			'date' => __( 'Date' ),
			'ID' => __( 'ID' ),
			'menu_order' => __( 'Menu order' ),
			'mime_type' => __( 'Mime type (attachments)' ),
			'modified' => __( 'Modified date' ),
			'name' => __( 'Name' ),
			'parent' => __( 'Parent ID' ),
			'rand' => __( 'Randomly' ),
			'status' => __( 'Status' ),
			'title' => __( 'Title' ),
			'category' => __( 'Category' ),
		);
?>		
		<div class="bizz-widget-controls columns-2">
		<small class="section"><?php _e('Select slides','bizzthemes'); ?></small>
		<p>
			<label for="<?php echo $this->get_field_id( 'post_type' ); ?>"><?php _e('Post type','bizzthemes'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>">
				<option value="any" <?php selected( $instance['post_type'], 'any' ); ?>><?php _e('All post types','bizzthemes'); ?></option>
<?php 
				foreach ( $post_types as $post_type ) {
					$pt_labels = $post_type->labels;
				    if ( $post_type->name != 'revision' && $post_type->name != 'nav_menu_item' && $post_type->name != 'bizz_widget' && $post_type->name != 'bizz_grid' ){
?>
					<option value="<?php echo $post_type->name; ?>" <?php selected( $instance['post_type'], $post_type->name ); ?>><?php echo $pt_labels->name; ?></option>
<?php
				    }
				}
?>
			</select>
		</p>
<?php
		if ( $count_posts->publish <= 500 ) {
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'include' ); ?>"><?php _e('Include slides','bizzthemes'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'include' ); ?>" name="<?php echo $this->get_field_name( 'include' ); ?>[]" size="4" multiple="multiple">
<?php 
				foreach ( $posts as $post ) {
				    if ( $post->post_type != 'revision' && $post->post_type != 'nav_menu_item' && $post->post_type != 'bizz_widget' && $post->post_type != 'bizz_grid' ){
					$instance['include'] = ( !is_array($instance['include']) ) ? explode(",", $instance['include']) : $instance['include'];
?>
					<option value="<?php echo $post->ID; ?>" <?php echo ( in_array( $post->ID, (array) $instance['include'] ) ? 'selected="selected"' : '' ); ?>><?php echo esc_attr( $post->post_title ); ?></option>
<?php 
				    }
				} 
?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'exclude' ); ?>"><?php _e('Exclude slides','bizzthemes'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'exclude' ); ?>" name="<?php echo $this->get_field_name( 'exclude' ); ?>[]" size="4" multiple="multiple">
<?php 
				foreach ( $posts as $post ) {
				    if ( $post->post_type != 'revision' && $post->post_type != 'nav_menu_item' && $post->post_type != 'bizz_widget' && $post->post_type != 'bizz_grid' ){
					$instance['exclude'] = ( !is_array($instance['exclude']) ) ? explode(",", $instance['exclude']) : $instance['exclude'];
?>
					<option value="<?php echo $post->ID; ?>" <?php echo ( in_array( $post->ID, (array) $instance['exclude'] ) ? 'selected="selected"' : '' ); ?>><?php echo esc_attr( $post->post_title ); ?></option>
<?php 
				    }
				} 
?>
			</select>
		</p>
<?php
		} else {
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'include' ); ?>"><?php _e('Include posts (IDs, separated by comma)','bizzthemes'); ?></label>
			<input type="text" class="widefat code" id="<?php echo $this->get_field_id( 'include' ); ?>" name="<?php echo $this->get_field_name( 'include' ); ?>" value="<?php echo ( is_array($instance['include']) ) ? implode(",", $instance['include']) : $instance['include']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'exclude' ); ?>"><?php _e('Exclude posts (IDs, separated by comma)','bizzthemes'); ?></label>
			<input type="text" class="widefat code" id="<?php echo $this->get_field_id( 'exclude' ); ?>" name="<?php echo $this->get_field_name( 'exclude' ); ?>" value="<?php echo ( is_array($instance['exclude']) ) ? implode(",", $instance['exclude']) : $instance['exclude']; ?>" />
		</p>
<?php
		}
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e('Order','bizzthemes'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>">
				<?php foreach ( $order as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['order'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e('Order by','bizzthemes'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
				<?php foreach ( $orderby as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['orderby'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Limit','bizzthemes'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>">
				<?php foreach ( $num_array_three as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['number'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		</div>

		<div class="bizz-widget-controls columns-2 column-last">
		<small class="section"><?php _e('Animation options','bizzthemes'); ?></small>
		<p>
			<label for="<?php echo $this->get_field_id( 'slidecontrols' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['slidecontrols'], true ); ?> id="<?php echo $this->get_field_id( 'slidecontrols' ); ?>" name="<?php echo $this->get_field_name( 'slidecontrols' ); ?>" /> <?php _e( 'Add paging dots?'); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pausehover' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['pausehover'], true ); ?> id="<?php echo $this->get_field_id( 'pausehover' ); ?>" name="<?php echo $this->get_field_name( 'pausehover' ); ?>" /> <?php _e( 'Pause on hover?'); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'nextprev' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['nextprev'], true ); ?> id="<?php echo $this->get_field_id( 'nextprev' ); ?>" name="<?php echo $this->get_field_name( 'nextprev' ); ?>" /> <?php _e( 'Next/Prev button?'); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e('Height','bizzthemes'); ?></label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $instance['height']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'start' ); ?>"><?php _e('Start with slide #','bizzthemes'); ?></label> 
			<select class="smallfat" id="<?php echo $this->get_field_id( 'start' ); ?>" name="<?php echo $this->get_field_name( 'start' ); ?>">
				<?php foreach ( $num_array_three as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['start'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'slidespeed' ); ?>"><?php _e('Slideshow speed','bizzthemes'); ?></label> 
			<select class="smallfat" id="<?php echo $this->get_field_id( 'slidespeed' ); ?>" name="<?php echo $this->get_field_name( 'slidespeed' ); ?>">
				<?php foreach ( $num_array as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['slidespeed'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'animationspeed' ); ?>"><?php _e('Animation duration','bizzthemes'); ?></label> 
			<select class="smallfat" id="<?php echo $this->get_field_id( 'animationspeed' ); ?>" name="<?php echo $this->get_field_name( 'animationspeed' ); ?>">
				<?php foreach ( $num_array_two as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['animationspeed'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		</div>
		<div style="clear:both;">&nbsp;</div>
	<?php
	}
}

// INITIATE WIDGET
register_widget( 'Bizz_Widget_Slider' );