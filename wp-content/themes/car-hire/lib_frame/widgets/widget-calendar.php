<?php
/**
 * Calendar Widget Class
 *
 * The calendar widget was created to give users the ability to show a post calendar for their blog 
 * using all the available options given in the get_calendar() function. It replaces the default WordPress
 * calendar widget.
 *
 * @since 0.6
 * @link http://codex.wordpress.org/Function_Reference/get_calendar
 * @link http://themebizz.com/themes/bizz/widgets
 *
 * @package Bizz
 * @subpackage Classes
 */

// WIDGET CLASS
class Bizz_Widget_Calendar extends WP_Widget {

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 0.6
	 */
	function Bizz_Widget_Calendar() {
		$widget_ops = array( 'classname' => 'calendar', 'description' => __( 'Control the output of your calendar.' ) );
		$this->WP_Widget( "widgets-reloaded-bizz-calendar", __( 'Calendar' ), $widget_ops );
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 * @since 0.6
	 */
	function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );

		$initial = isset( $instance['initial'] ) ? $instance['initial'] : false;

		echo $before_widget;

		if ( $instance['title'] )
			echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;

		echo '<div class="calendar-wrap">';
			get_calendar( $initial );
		echo '</div><!-- .calendar-wrap -->';

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
		$instance['initial'] = ( isset( $new_instance['initial'] ) ? 1 : 0 );
		
		return $instance;
	}
	
	/**
	 * Displays the widget control options in the Widgets admin screen.
	 * @since 0.6
	 */
	function form( $instance ) {

		//Defaults
		$defaults = array(
			'title' => __( 'Calendar' ),
			'initial' => false
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<div class="bizz-widget-controls columns-1">
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['initial'], true ); ?> id="<?php echo $this->get_field_id( 'initial' ); ?>" name="<?php echo $this->get_field_name( 'initial' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'initial' ); ?>"><?php _e( 'One-letter abbreviation?' ); ?> <code>initial</code></label>
		</p>
		</div>
	<?php
	}
}

// INITIATE WIDGET
register_widget( 'Bizz_Widget_Calendar' );