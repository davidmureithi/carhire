<?php
/**
 * Rich Textarea Widget Class
 *
 * The Rich Textarea widget allows you to edit widget text with wysiwyg editor.
 *
 */

// WIDGET CLASS
class Bizz_Widget_Rich_Textarea extends WP_Widget {

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 0.6
	 */
	function Bizz_Widget_Rich_Textarea() {
		$widget_ops = array( 'classname' => 'richtext', 'description' => __( 'Visual editing of your widget text.', 'bizzthemes' ) );
		$control_ops = array( 'width' => 500, 'height' => 350, 'id_base' => "widgets-reloaded-bizz-richtext" );
		$this->WP_Widget( "widgets-reloaded-bizz-richtext", __( 'Rich Textarea', 'bizzthemes' ), $widget_ops, $control_ops );
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 * @since 0.6
	 */
	function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );
		
		$args = array();

		$args['title'] = $instance['title'];
		$args['icon'] = $instance['icon'];
		$args['content'] = $instance['content'];
		$args['button_text'] = $instance['button_text'];
		$args['button_link'] = $instance['button_link'];
		
		$wid_wysiwyg_c = str_replace('<br>', ''.addslashes('<br/>').'', ''.stripslashes($instance['content']).'');
		if (!empty($instance['icon'])) {
			$title_icon = '<img src="'.$instance['icon'].'" alt="" />';
		}
		else {
		    $title_icon = '';
		}
		if (!empty($instance['title_link']) && $instance['title_link'] != 'http://') {
			$title_link_before = '<a href="'.$instance['title_link'].'">';
			$title_link_after = '</a>';
		}
		else {
		    $title_link_before = '';
			$title_link_after = '';
		}

		echo $before_widget;

		/* If there is a title given, add it along with the $before_title and $after_title variables. */
		if ( $instance['title'] )
			echo $before_title . $title_icon . $title_link_before . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $title_link_after . $after_title;

		echo '<div class="editor_content format_text">'.$wid_wysiwyg_c.'</div>';
		
		if (!empty($instance['button_text'])) {
			echo '<a class="btn rich_btn" href="'.$instance['button_link'].'" title="'.$instance['button_text'].'"><span><span>'.$instance['button_text'].'</span></span></a>';
		}

		echo $after_widget;
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 * @since 0.6
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['icon'] = strip_tags( $new_instance['icon'] );
		$instance['title_link'] = strip_tags( $new_instance['title_link'] );
		$instance['content'] = $new_instance['content'];
		$instance['button_text'] = strip_tags( $new_instance['button_text'] );
		$instance['button_link'] = strip_tags( $new_instance['button_link'] );
		
		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 * @since 0.6
	 */
	function form( $instance ) {
		
		//Defaults
		$defaults = array(
			'title' => __( 'Rich Textarea', 'bizzthemes' ), 
			'icon' => '', 
			'title_link' => 'http://',
			'content' => '',
			'button_text' => '',
			'button_link' => 'http://'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'bizzthemes' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
		    <label for="<?php echo $this->get_field_id( 'title_link' ); ?>"><?php _e( 'Title Link:', 'bizzthemes' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title_link' ); ?>" name="<?php echo $this->get_field_name( 'title_link' ); ?>" value="<?php echo $instance['title_link']; ?>" />
		</p>
		<p>
			<div class="wid_upload_wrap">
			    <label for="<?php echo $this->get_field_id( 'icon' ); ?>"><?php _e( 'Title Icon:', 'bizzthemes' ); ?></label>
				<div class="wid_upload_button" id="<?php echo $this->get_field_id('icon'); ?>">Choose File</div>
			    <input type="text" class="widefat wid_upload_input" id="<?php echo $this->get_field_id('icon'); ?>" name="<?php echo $this->get_field_name('icon'); ?>" value="<?php echo $instance['icon']; ?>" />
			</div>
		</p>
		<p>
		    <label for="<?php echo $this->get_field_id('content'); ?>" class="wysiwyg_label">Widget Content: <span class="richtext">Rich Textarea</span>
			<textarea class="xwysiwyg widefat <?php echo $this->get_field_id('content'); ?>" id="<?php echo $this->get_field_id('content'); ?>" name="<?php echo $this->get_field_name('content'); ?>" type="text" cols="20" rows="12"><?php echo esc_attr($instance['content']); ?></textarea>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php _e( 'Button text:', 'bizzthemes' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" value="<?php echo $instance['button_text']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'button_link' ); ?>"><?php _e( 'Button link:', 'bizzthemes' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'button_link' ); ?>" name="<?php echo $this->get_field_name( 'button_link' ); ?>" value="<?php echo $instance['button_link']; ?>" />
		</p>

		<div style="clear:both;">&nbsp;</div>
	<?php
	}
}

// INITIATE WIDGET
register_widget( 'Bizz_Widget_Rich_Textarea' );