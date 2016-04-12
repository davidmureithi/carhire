<?php
/**
 * Archives Widget Class
 *
 * The Archives widget replaces the default WordPress Archives widget. This version gives total
 * control over the output to the user by allowing the input of all the arguments typically seen
 * in the wp_get_archives() function.
 *
 * @since 0.6
 * @link http://codex.wordpress.org/Template_Tags/wp_get_archives
 * @link http://themebizz.com/themes/bizz/widgets
 *
 * @package Bizz
 * @subpackage Classes
 */

// WIDGET CLASS
class Bizz_Widget_Archives extends WP_Widget {

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 0.6
	 */
	function Bizz_Widget_Archives() {
		$widget_ops = array( 'classname' => 'archives', 'description' => __( 'Control the output of your archives.' ) );
		$this->WP_Widget( "widgets-reloaded-bizz-archives", __( 'Archives' ), $widget_ops );
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 * @since 0.6
	 */
	function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );

		$args = array();

		$args['type'] = $instance['type']; 
		$args['format'] = $instance['format'];
		$args['before'] = $instance['before'];
		$args['after'] = $instance['after'];
		$args['show_post_count'] = isset( $instance['show_post_count'] ) ? $instance['show_post_count'] : false;
		$args['limit'] = !empty( $instance['limit'] ) ? intval( $instance['limit'] ) : '';
		$args['echo'] = false;

		echo $before_widget;

		if ( $instance['title'] )
			echo $before_title . apply_filters( 'widget_title', $instance['title'] ) . $after_title;

		$archives = str_replace( array( "\r", "\n", "\t" ), '', wp_get_archives( $args ) );

		if ( 'option' == $args['format'] ) {

			if ( 'yearly' == $args['type'] )
				$option_title = __( 'Select Year' );
			elseif ( 'monthly' == $args['type'] )
				$option_title = __( 'Select Month' );
			elseif ( 'weekly' == $args['type'] )
				$option_title = __( 'Select Week' );
			elseif ( 'daily' == $args['type'] )
				$option_title = __( 'Select Day' );
			elseif ( 'postbypost' == $args['type'] || 'alpha' == $args['type'] )
				$option_title = __( 'Select Post' );

			echo '<select name="archive-dropdown" onchange=\'document.location.href=this.options[this.selectedIndex].value;\'>';
			echo '<option value="">' . esc_attr( $option_title ) . '</option>';
			echo $archives;
			echo '</select>';
		}
		elseif ( 'html' == $args['format'] ) {
			echo '<ul class="xoxo archives">' . $archives . '</ul><!-- .xoxo .archives -->';
		}
		else {
			echo $archives;
		}

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
		$instance['before'] = strip_tags( $new_instance['before'] );
		$instance['after'] = strip_tags( $new_instance['after'] );
		$instance['limit'] = strip_tags( $new_instance['limit'] );
		$instance['show_post_count'] = ( isset( $new_instance['show_post_count'] ) ? 1 : 0 );

		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 * @since 0.6
	 */
	function form( $instance ) {

		//Defaults
		$defaults = array(
			'title' => __( 'Archives' ),
			'limit' => '',
			'type' => 'monthly',
			'format' => 'html',
			'show_post_count' => '',
			'before' => '',
			'after' => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$type = array( 'alpha' => __( 'Alphabetical' ), 'daily' => __( 'Daily' ), 'monthly' => __( 'Monthly' ),'postbypost' => __( 'Post By Post' ), 'weekly' => __( 'Weekly' ), 'yearly' => __( 'Yearly' ) );
		$format = array( 'custom' => __( 'Custom' ), 'html' => __( 'HTML' ), 'option' => __( 'Option' ) );

		?>

		<div class="bizz-widget-controls columns-2">
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>">Limit (max. number)</label>
			<input type="text" class="widefat code" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" value="<?php echo $instance['limit']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'type' ); ?>">Type</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>">
				<?php foreach ( $type as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['type'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'format' ); ?>">Format</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'format' ); ?>" name="<?php echo $this->get_field_name( 'format' ); ?>">
				<?php foreach ( $format as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['format'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		</div>

		<div class="bizz-widget-controls columns-2 column-last">
		<p>
			<label for="<?php echo $this->get_field_id( 'before' ); ?>">Before link</label>
			<input type="text" class="widefat code" id="<?php echo $this->get_field_id( 'before' ); ?>" name="<?php echo $this->get_field_name( 'before' ); ?>" value="<?php echo $instance['before']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'after' ); ?>">After link</label>
			<input type="text" class="widefat code" id="<?php echo $this->get_field_id( 'after' ); ?>" name="<?php echo $this->get_field_name( 'after' ); ?>" value="<?php echo $instance['after']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_post_count' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_post_count'], true ); ?> id="<?php echo $this->get_field_id( 'show_post_count' ); ?>" name="<?php echo $this->get_field_name( 'show_post_count' ); ?>" /> <?php _e( 'Show post count?' ); ?></label>
		</p>
		</div>
		<div style="clear:both;">&nbsp;</div>
	<?php
	}
}

// INITIATE WIDGET
register_widget( 'Bizz_Widget_Archives' );