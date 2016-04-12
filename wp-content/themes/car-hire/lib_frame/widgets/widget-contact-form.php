<?php
/**
 * Contact Form Widget Class
 *
 * Simple Contact Form widget.
 *
 */

// WIDGET CLASS
class Bizz_Widget_Contact extends WP_Widget {

	var $prefix;
	var $textdomain;

	function Bizz_Widget_Contact() {		
		$widget_ops = array( 'classname' => 'c-form', 'description' => __( 'Simple Contact Form widget for your site.' ) );
		$this->WP_Widget( "widgets-reloaded-bizz-c-form", __( 'Contact Form' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		global $widget_id;
		
		extract( $args );

		$args = array();

		$args['wid_email'] = $instance['wid_email']; 
		$args['wid_trans1'] = $instance['wid_trans1'];
		$args['wid_trans2'] = $instance['wid_trans2'];
		$args['wid_trans3'] = $instance['wid_trans3'];
		$args['wid_trans7'] = $instance['wid_trans7'];
		$args['wid_trans9'] = $instance['wid_trans9'];
		$args['wid_trans10'] = $instance['wid_trans10'];
		$args['wid_trans11'] = $instance['wid_trans11'];
		$args['wid_trans12'] = $instance['wid_trans12'];
		$args['wid_trans13'] = $instance['wid_trans13'];
		$args['wid_trans14'] = $instance['wid_trans14'];
		$args['wid_trans15'] = $instance['wid_trans15'];
		$args['wid_trans16'] = $instance['wid_trans16'];
		$args['wid_trans17'] = $instance['wid_trans17'];
		$args['wid_trans18'] = $instance['wid_trans18'];
		$args['wid_trans19'] = $instance['wid_trans19'];

		echo $before_widget;

		if ( $instance['title'] )
			echo $before_title . apply_filters( 'widget_title', $instance['title'] ) . $after_title;
			
		echo '<div class="cform">';
		    echo bizz_contact_form($instance['wid_email'],$instance['wid_trans1'],$instance['wid_trans2'],$instance['wid_trans3'],$instance['wid_trans7'],$instance['wid_trans9'],$instance['wid_trans10'],$instance['wid_trans11'],$instance['wid_trans12'],$instance['wid_trans13'],$instance['wid_trans14'],$instance['wid_trans15'],$instance['wid_trans16'],$instance['wid_trans17'],$instance['wid_trans18'],$instance['wid_trans19'],$widget_id);
		echo '</div>';
			
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance = $new_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['wid_email'] = strip_tags($new_instance['wid_email']);
		$instance['wid_trans1'] = strip_tags($new_instance['wid_trans1']);
		$instance['wid_trans2'] = strip_tags($new_instance['wid_trans2']);
		$instance['wid_trans3'] = strip_tags($new_instance['wid_trans3']);
		$instance['wid_trans7'] = strip_tags($new_instance['wid_trans7']);
		$instance['wid_trans9'] = strip_tags($new_instance['wid_trans9']);
		$instance['wid_trans10'] = strip_tags($new_instance['wid_trans10']);
		$instance['wid_trans11'] = strip_tags($new_instance['wid_trans11']);
		$instance['wid_trans12'] = strip_tags($new_instance['wid_trans12']);
		$instance['wid_trans13'] = strip_tags($new_instance['wid_trans13']);
		$instance['wid_trans14'] = strip_tags($new_instance['wid_trans14']);
		$instance['wid_trans15'] = strip_tags($new_instance['wid_trans15']);
		$instance['wid_trans16'] = strip_tags($new_instance['wid_trans16']);
		$instance['wid_trans17'] = strip_tags($new_instance['wid_trans17']);
		$instance['wid_trans18'] = strip_tags($new_instance['wid_trans18']);
		$instance['wid_trans19'] = strip_tags($new_instance['wid_trans19']);

		return $instance;
	}

	function form( $instance ) {

		//Defaults
		$defaults = array(
			'title' => __( 'Contact Me' ),
			'wid_email' => '',  
			'wid_trans1' => 'This field is required. Please enter a value.',
			'wid_trans2' => 'Invalid email address.',
			'wid_trans3' => 'Contact Form Submission from ',
			'wid_trans7' => 'You emailed ',
			'wid_trans9' => 'You forgot to enter your',
			'wid_trans10' => 'You entered an invalid',
			'wid_trans11' => '<b>Thanks!</b> Your email was successfully sent.',
			'wid_trans12' => 'There was an error submitting the form.',
			'wid_trans13' => 'E-mail has not been setup properly. Please add your contact e-mail!',
			'wid_trans14' => 'Name<span>*</span>',
			'wid_trans15' => 'Email<span>*</span>',
			'wid_trans16' => 'Message<span>*</span>',
			'wid_trans17' => 'Send a copy to yourself',
			'wid_trans18' => 'If you want to submit this form, do not enter anything in this field',
			'wid_trans19' => 'Submit'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
        <p>
		    <label for="<?php echo $this->get_field_id('wid_email'); ?>"><b>Your Email</b>: </label>
			<input class="widefat" id="<?php echo $this->get_field_id('wid_email'); ?>" name="<?php echo $this->get_field_name('wid_email'); ?>" type="text" value="<?php echo esc_attr($instance['wid_email']); ?>" />
		</p>
		<p>
			<label><b>Form Labels</b>: </label>
			<input class="widefat spb" id="<?php echo $this->get_field_id('wid_trans15'); ?>" name="<?php echo $this->get_field_name('wid_trans15'); ?>" type="text" value="<?php echo esc_attr($instance['wid_trans15']); ?>" />
			<input class="widefat spb" id="<?php echo $this->get_field_id('wid_trans16'); ?>" name="<?php echo $this->get_field_name('wid_trans16'); ?>" type="text" value="<?php echo esc_attr($instance['wid_trans16']); ?>" />
			<input class="widefat spb" id="<?php echo $this->get_field_id('wid_trans17'); ?>" name="<?php echo $this->get_field_name('wid_trans17'); ?>" type="text" value="<?php echo esc_attr($instance['wid_trans17']); ?>" />
			<input class="widefat spb" id="<?php echo $this->get_field_id('wid_trans18'); ?>" name="<?php echo $this->get_field_name('wid_trans18'); ?>" type="text" value="<?php echo esc_attr($instance['wid_trans18']); ?>" />
			<input class="widefat spb" id="<?php echo $this->get_field_id('wid_trans19'); ?>" name="<?php echo $this->get_field_name('wid_trans19'); ?>" type="text" value="<?php echo esc_attr($instance['wid_trans19']); ?>" />
		</p>
		<p>
			<label><span class="translate">Translations</span></label>
		</p>
		<p>
			<label class="tog"><b>Email Template Translations</b>: </label>
			<input class="widefat spb tog" id="<?php echo $this->get_field_id('wid_trans7'); ?>" name="<?php echo $this->get_field_name('wid_trans7'); ?>" type="text" value="<?php echo esc_attr($instance['wid_trans7']); ?>" />
		</p>
		<p>
			<label class="tog"><b>Error Translations</b>:</label>
			<input class="widefat spb tog" id="<?php echo $this->get_field_id('wid_trans1'); ?>" name="<?php echo $this->get_field_name('wid_trans1'); ?>" type="text" value="<?php echo esc_attr($instance['wid_trans1']); ?>" />
			<input class="widefat spb tog" id="<?php echo $this->get_field_id('wid_trans2'); ?>" name="<?php echo $this->get_field_name('wid_trans2'); ?>" type="text" value="<?php echo esc_attr($instance['wid_trans2']); ?>" />
			<input class="widefat spb tog" id="<?php echo $this->get_field_id('wid_trans3'); ?>" name="<?php echo $this->get_field_name('wid_trans3'); ?>" type="text" value="<?php echo esc_attr($instance['wid_trans3']); ?>" />
		</p>
		<p>
			<label class="tog"><b>Live Error Translations</b>:</label>
			<input class="widefat spb tog" id="<?php echo $this->get_field_id('wid_trans9'); ?>" name="<?php echo $this->get_field_name('wid_trans9'); ?>" type="text" value="<?php echo esc_attr($instance['wid_trans9']); ?>" />
			<input class="widefat spb tog" id="<?php echo $this->get_field_id('wid_trans10'); ?>" name="<?php echo $this->get_field_name('wid_trans10'); ?>" type="text" value="<?php echo esc_attr($instance['wid_trans10']); ?>" />
			<input class="widefat spb tog" id="<?php echo $this->get_field_id('wid_trans11'); ?>" name="<?php echo $this->get_field_name('wid_trans11'); ?>" type="text" value="<?php echo esc_attr($instance['wid_trans11']); ?>" />
			<input class="widefat spb tog" id="<?php echo $this->get_field_id('wid_trans12'); ?>" name="<?php echo $this->get_field_name('wid_trans12'); ?>" type="text" value="<?php echo esc_attr($instance['wid_trans12']); ?>" />
			<input class="widefat spb tog" id="<?php echo $this->get_field_id('wid_trans13'); ?>" name="<?php echo $this->get_field_name('wid_trans13'); ?>" type="text" value="<?php echo esc_attr($instance['wid_trans13']); ?>" />
			<input class="widefat spb tog" id="<?php echo $this->get_field_id('wid_trans14'); ?>" name="<?php echo $this->get_field_name('wid_trans14'); ?>" type="text" value="<?php echo esc_attr($instance['wid_trans14']); ?>" />
		</p>
		<div style="clear:both;">&nbsp;</div>
	<?php
	}
}

// INITIATE WIDGET
register_widget( 'Bizz_Widget_Contact' );