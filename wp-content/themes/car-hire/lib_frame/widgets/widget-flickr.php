<?php
/**
 * Flickr Widget Class
 *
 * Flickr widget displays your flickr photos.
 *
 */

// WIDGET CLASS
class Bizz_Widget_Flickr extends WP_Widget {

	function Bizz_Widget_Flickr() {
		$widget_ops = array( 'classname' => 'flickr', 'description' => __( 'Flickr widget displays your flickr photos.' ) );
		$this->WP_Widget( "widgets-reloaded-bizz-flickr", __( 'Flickr Photos' ), $widget_ops );
	}

	function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );

		$args = array();

		$args['flickr_id'] = $instance['flickr_id']; 
		$args['flickr_number'] = $instance['flickr_number'];
		$args['flickr_type'] = $instance['flickr_type'];
		$args['flickr_sorting'] = $instance['flickr_sorting'];

		echo $before_widget;

		if ( $instance['title'] )
			echo $before_title . apply_filters( 'widget_title', $instance['title'] ) . $after_title;

		echo '<div class="wrap flickr">';
		echo '<div class="fix"></div>';
		echo '<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count='.$instance['flickr_number'].'&amp;display='.$instance['flickr_sorting'].'&amp;size=s&amp;layout=x&amp;source='.$instance['flickr_type'].'&amp;'.$instance['flickr_type'].'='.$instance['flickr_id'].'"></script>';
		echo '<div class="fix"></div>';
		echo '</div>';

		echo $after_widget;
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 * @since 0.6
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = $new_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['flickr_id'] = strip_tags( $new_instance['flickr_id'] );
		$instance['flickr_number'] = strip_tags( $new_instance['flickr_number'] );
		$instance['flickr_type'] = strip_tags( $new_instance['flickr_type'] );
		$instance['flickr_sorting'] = strip_tags( $new_instance['flickr_sorting'] );

		return $instance;
	}
	
	/**
	 * Displays the widget control options in the Widgets admin screen.
	 * @since 0.6
	 */
	function form( $instance ) {

		//Defaults
		$defaults = array(
			'title' => __( 'Flickr' ),
			'flickr_id' => '38982010@N00',
			'flickr_number' => '6',
			'flickr_type' => '',
			'flickr_sorting' => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
        <p>
            <label for="<?php echo $this->get_field_id('flickr_id'); ?>"><?php _e('Flickr ID (<a href="'.esc_url('www.idgettr.com').'">idGettr</a>):','bizzthemes'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('flickr_id'); ?>" value="<?php echo $instance['flickr_id']; ?>" class="widefat" id="<?php echo $this->get_field_id('flickr_id'); ?>" />
        </p>
       	<p>
            <label for="<?php echo $this->get_field_id('flickr_number'); ?>"><?php _e('Number:','bizzthemes'); ?></label>
            <select name="<?php echo $this->get_field_name('flickr_number'); ?>" class="widefat" id="<?php echo $this->get_field_id('flickr_number'); ?>">
                <?php for ( $i = 1; $i < 11; $i += 1) { ?>
                <option value="<?php echo $i; ?>" <?php if($instance['flickr_number'] == $i){ echo "selected='selected'";} ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('flickr_type'); ?>"><?php _e('Type:','bizzthemes'); ?></label>
            <select name="<?php echo $this->get_field_name('flickr_type'); ?>" class="widefat" id="<?php echo $this->get_field_id('flickr_type'); ?>">
                <option value="user" <?php if($instance['flickr_type'] == "user"){ echo "selected='selected'";} ?>><?php _e('User'); ?></option>
                <option value="group" <?php if($instance['flickr_type'] == "group"){ echo "selected='selected'";} ?>><?php _e('Group'); ?></option>            
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('flickr_sorting'); ?>"><?php _e('Sorting:','bizzthemes'); ?></label>
            <select name="<?php echo $this->get_field_name('flickr_sorting'); ?>" class="widefat" id="<?php echo $this->get_field_id('flickr_sorting'); ?>">
                <option value="latest" <?php if($instance['flickr_sorting'] == "latest"){ echo "selected='selected'";} ?>><?php _e('Latest'); ?></option>
                <option value="random" <?php if($instance['flickr_sorting'] == "random"){ echo "selected='selected'";} ?>><?php _e('Random'); ?></option>            
            </select>
        </p>
		<div style="clear:both;">&nbsp;</div>
	<?php
	}
}

// INITIATE WIDGET
register_widget( 'Bizz_Widget_Flickr' );