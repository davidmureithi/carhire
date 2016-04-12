<?php
/**
 * Tags Widget Class
 *
 * The Tags widget replaces the default WordPress Tag Cloud widget. This version gives total
 * control over the output to the user by allowing the input of all the arguments typically seen
 * in the wp_tag_cloud() function.
 *
 * @since 0.6
 * @link http://codex.wordpress.org/Template_Tags/wp_tag_cloud
 * @link http://themebizz.com/themes/bizz/widgets
 *
 * @package Bizz
 * @subpackage Widgets
 */

// WIDGET CLASS 
class Bizz_Widget_Tags extends WP_Widget {

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 0.6
	 */
	function Bizz_Widget_Tags() {
		$widget_ops = array( 'classname' => 'tags', 'description' => __( 'Control the output of your tag links.','bizzthemes' ) );
		$control_ops = array( 'width' => 550, 'height' => 350, 'id_base' => "widgets-reloaded-bizz-tags" );
		$this->WP_Widget( "widgets-reloaded-bizz-tags", __( 'Tags' ), $widget_ops, $control_ops );
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 * @since 0.6
	 */
	function widget( $args, $instance ) {
		
		extract( $args, EXTR_SKIP );

		$args = array();

		$args['taxonomy'] = $instance['taxonomy'];
		$args['largest'] = ( ( $instance['largest'] ) ? absint( $instance['largest'] ) : 22 );
		$args['smallest'] = ( ( $instance['smallest'] ) ? absint( $instance['smallest'] ) : 8 );
		$args['number'] = intval( $instance['number'] );
		$args['child_of'] = intval( $instance['child_of'] );
		$args['parent'] = ( $instance['parent'] ) ? intval( $instance['parent'] ) : '';
		$args['separator'] = ( $instance['separator'] ) ? $instance['separator'] : "\n";
		$args['pad_counts'] = isset( $instance['pad_counts'] ) ? $instance['pad_counts'] : false;
		$args['hide_empty'] = isset( $instance['hide_empty'] ) ? $instance['hide_empty'] : false;
		$args['unit'] = $instance['unit'];
		$args['format'] = $instance['format'];
		$args['include'] = isset( $instance['include'] ) ? $instance['include'] : '';
		$args['exclude'] = isset( $instance['exclude'] ) ? $instance['exclude'] : '';
		$args['order'] = $instance['order'];
		$args['orderby'] = $instance['orderby'];
		$args['link'] = $instance['link'];
		$args['search'] = $instance['search'];
		$args['name__like'] = $instance['name__like'];
		$args['echo'] = false;

		/* Open the output of the widget. */
		echo $before_widget;

		/* If there is a title given, add it along with the $before_title and $after_title variables. */
		if ( $instance['title'] )
			echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;

		/* Get the tag cloud. */
		$tags = str_replace( array( "\r", "\n", "\t" ), ' ', wp_tag_cloud( $args ) );

		/* If $format should be flat, wrap it in the <p> element. */
		if ( 'flat' == $instance['format'] )
			$tags = '<p class="' . $instance['taxonomy'] . '-cloud term-cloud">' . $tags . '</p>';

		/* Output the tag cloud. */
		echo $tags;

		/* Close the output of the widget. */
		echo $after_widget;
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 * @since 0.6
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Set the instance to the new instance. */
		$instance = $new_instance;

		/* If new taxonomy is chosen, reset includes and excludes. */
		if ( $instance['taxonomy'] !== $old_instance['taxonomy'] ) {
			$instance['include'] = array();
			$instance['exclude'] = array();
		}

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['smallest'] = strip_tags( $new_instance['smallest'] );
		$instance['largest'] = strip_tags( $new_instance['largest'] );
		$instance['number'] = strip_tags( $new_instance['number'] );
		$instance['separator'] = strip_tags( $new_instance['separator'] );
		$instance['name__like'] = strip_tags( $new_instance['name__like'] );
		$instance['search'] = strip_tags( $new_instance['search'] );
		$instance['child_of'] = strip_tags( $new_instance['child_of'] );
		$instance['parent'] = strip_tags( $new_instance['parent'] );
		$instance['unit'] = $new_instance['unit'];
		$instance['format'] = $new_instance['format'];
		$instance['orderby'] = $new_instance['orderby'];
		$instance['order'] = $new_instance['order'];
		$instance['taxonomy'] = $new_instance['taxonomy'];
		$instance['link'] = $new_instance['link'];
		$instance['pad_counts'] = ( isset( $new_instance['pad_counts'] ) ? 1 : 0 );
		$instance['hide_empty'] = ( isset( $new_instance['hide_empty'] ) ? 1 : 0 );

		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 * @since 0.6
	 */
	function form( $instance ) {

		/* Set up some defaults for the widget. */
		$defaults = array(
			'title' => __( 'Tags' ),
			'include' => '',
			'exclude' => '',
			'separator' => '',
			'child_of' => '',
			'parent' => '',
			'search' => '',
			'name__like' => '',
			'pad_counts' => '',
			'order' => 'ASC',
			'orderby' => 'name',
			'format' => 'flat',
			'unit' => 'pt', 'smallest' => 8,
			'largest' => 22,
			'link' => 'view',
			'number' => 45,
			'taxonomy' => 'post_tag',
			'hide_empty' => 1
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		/* <select> element options. */
		$taxonomies = get_taxonomies( array( 'show_tagcloud' => true ), 'objects' );
		$terms = get_terms( $instance['taxonomy'] );
		$link = array( 'view' => __( 'View' ), 'edit' => __( 'Edit' ) );
		$format = array( 'flat' => __( 'Flat' ), 'list' => __( 'List' ) );
		$order = array( 'ASC' => __( 'Ascending' ), 'DESC' => __( 'Descending' ), 'RAND' => __( 'Random' ) );
		$orderby = array( 'count' => __( 'Count' ), 'name' => __( 'Name' ) );
		$unit = array( 'pt' => 'pt', 'px' => 'px', 'em' => 'em', '%' => '%' );

		?>

		<div class="bizz-widget-controls columns-3">
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'taxonomy' ); ?>">Taxonomy</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'taxonomy' ); ?>" name="<?php echo $this->get_field_name( 'taxonomy' ); ?>">
				<?php foreach ( $taxonomies as $taxonomy ) { ?>
					<option value="<?php echo $taxonomy->name; ?>" <?php selected( $instance['taxonomy'], $taxonomy->name ); ?>><?php echo $taxonomy->labels->singular_name; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>">Link</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>">
				<?php foreach ( $link as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['link'], $option_value ); ?>><?php echo $option_label; ?></option>
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
		<p>
			<label for="<?php echo $this->get_field_id( 'order' ); ?>">Order</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>">
				<?php foreach ( $order as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['order'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'orderby' ); ?>">Order by</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
				<?php foreach ( $orderby as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['orderby'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		</div>

		<div class="bizz-widget-controls columns-3">
		<p>
			<label for="<?php echo $this->get_field_id( 'include' ); ?>">Include</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'include' ); ?>" name="<?php echo $this->get_field_name( 'include' ); ?>[]" size="4" multiple="multiple">
				<?php foreach ( $terms as $term ) { ?>
					<option value="<?php echo $term->term_id; ?>" <?php echo ( in_array( $term->term_id, (array) $instance['include'] ) ? 'selected="selected"' : '' ); ?>><?php echo $term->name; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'exclude' ); ?>">Exclude</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'exclude' ); ?>" name="<?php echo $this->get_field_name( 'exclude' ); ?>[]" size="4" multiple="multiple">
				<?php foreach ( $terms as $term ) { ?>
					<option value="<?php echo $term->term_id; ?>" <?php echo ( in_array( $term->term_id, (array) $instance['exclude'] ) ? 'selected="selected"' : '' ); ?>><?php echo $term->name; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>">Limit (number)</label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'largest' ); ?>">Largest</label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'largest' ); ?>" name="<?php echo $this->get_field_name( 'largest' ); ?>" value="<?php echo $instance['largest']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'smallest' ); ?>">Smallest</label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'smallest' ); ?>" name="<?php echo $this->get_field_name( 'smallest' ); ?>" value="<?php echo $instance['smallest']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'unit' ); ?>">Unit</label> 
			<select class="smallfat" id="<?php echo $this->get_field_id( 'unit' ); ?>" name="<?php echo $this->get_field_name( 'unit' ); ?>">
				<?php foreach ( $unit as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['unit'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		</div>

		<div class="bizz-widget-controls columns-3 column-last">
		<p>
			<label for="<?php echo $this->get_field_id( 'separator' ); ?>">Separator</label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'separator' ); ?>" name="<?php echo $this->get_field_name( 'separator' ); ?>" value="<?php echo $instance['separator']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'child_of' ); ?>">Child of (ID)</label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'child_of' ); ?>" name="<?php echo $this->get_field_name( 'child_of' ); ?>" value="<?php echo $instance['child_of']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'parent' ); ?>">Parent (ID)</label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'parent' ); ?>" name="<?php echo $this->get_field_name( 'parent' ); ?>" value="<?php echo $instance['parent']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'search' ); ?>">Search</label>
			<input type="text" class="widefat code" id="<?php echo $this->get_field_id( 'search' ); ?>" name="<?php echo $this->get_field_name( 'search' ); ?>" value="<?php echo $instance['search']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'name__like' ); ?>">Name (name__like)</label>
			<input type="text" class="widefat code" id="<?php echo $this->get_field_id( 'name__like' ); ?>" name="<?php echo $this->get_field_name( 'name__like' ); ?>" value="<?php echo $instance['name__like']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pad_counts' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['pad_counts'], true ); ?> id="<?php echo $this->get_field_id( 'pad_counts' ); ?>" name="<?php echo $this->get_field_name( 'pad_counts' ); ?>" /> <?php _e( 'Pad counts?' ); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'hide_empty' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['hide_empty'], true ); ?> id="<?php echo $this->get_field_id( 'hide_empty' ); ?>" name="<?php echo $this->get_field_name( 'hide_empty' ); ?>" /> <?php _e( 'Hide empty?' ); ?></label>
		</p>
		</div>
		<div style="clear:both;">&nbsp;</div>
	<?php
	}
}

// INITIATE WIDGET
register_widget( 'Bizz_Widget_Tags' );