<?php
/**
 * Categories Widget Class
 *
 * The Categories widget replaces the default WordPress Categories widget. This version gives total
 * control over the output to the user by allowing the input of all the arguments typically seen
 * in the wp_list_categories() function.
 *
 * @since 0.6
 * @link http://codex.wordpress.org/Template_Tags/wp_list_categories
 * @link http://themebizz.com/themes/bizz/widgets
 *
 * @package Bizz
 * @subpackage Classes
 */

// WIDGET CLASS
class Bizz_Widget_Categories extends WP_Widget {

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 0.6
	 */
	function Bizz_Widget_Categories() {
		$widget_ops = array( 'classname' => 'categories', 'description' => __( 'Control the output of your category links.' ) );
		$control_ops = array( 'width' => 550, 'height' => 350, 'id_base' => "widgets-reloaded-bizz-categories" );
		$this->WP_Widget( "widgets-reloaded-bizz-categories", __( 'Categories' ), $widget_ops, $control_ops );
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 * @since 0.6
	 */
	function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );

		$args = array();

		$args['taxonomy'] = $instance['taxonomy'];
		$args['style'] = $instance['style'];
		$args['orderby'] = $instance['orderby'];
		$args['order'] = $instance['order'];
		$args['include'] = ( isset($instance['include']) && is_array( $instance['include'] ) ? join( ', ', $instance['include'] ) : '' );
		$args['exclude'] = ( isset($instance['exclude']) && is_array( $instance['exclude'] ) ? join( ', ', $instance['exclude'] ) : '' );
		$args['exclude_tree'] = $instance['exclude_tree'];
		$args['depth'] = intval( $instance['depth'] );
		$args['number'] = intval( $instance['number'] );
		$args['child_of'] = intval( $instance['child_of'] );
		$args['current_category'] = intval( $instance['current_category'] );
		$args['feed'] = $instance['feed'];
		$args['feed_type'] = $instance['feed_type'];
		$args['feed_image'] = esc_url( $instance['feed_image'] );
		$args['search'] = $instance['search'];
		$args['hierarchical'] = isset( $instance['hierarchical'] ) ? $instance['hierarchical'] : false;
		$args['use_desc_for_title'] = isset( $instance['use_desc_for_title'] ) ? $instance['use_desc_for_title'] : false;
		$args['show_last_update'] = isset( $instance['show_last_update'] ) ? $instance['show_last_update'] : false;
		$args['show_count'] = isset( $instance['show_count'] ) ? $instance['show_count'] : false;
		$args['hide_empty'] = isset( $instance['hide_empty'] ) ? $instance['hide_empty'] : false;
		$args['title_li'] = false;
		$args['echo'] = false;

		echo $before_widget;

		if ( $instance['title'] )
			echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;

		$categories = str_replace( array( "\r", "\n", "\t" ), '', wp_list_categories( $args ) );

		if ( 'list' == $args['style'] )
			$categories = '<ul class="xoxo categories">' . $categories . '</ul><!-- .xoxo .categories -->';

		echo $categories;

		echo $after_widget;
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 * @since 0.6
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = $new_instance;

		/* If new taxonomy is chosen, reset includes and excludes. */
		if ( $instance['taxonomy'] !== $old_instance['taxonomy'] && '' !== $old_instance['taxonomy'] ) {
			$instance['include'] = array();
			$instance['exclude'] = array();
		}

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['taxonomy'] = $new_instance['taxonomy'];
		$instance['exclude_tree'] = strip_tags( $new_instance['exclude_tree'] );
		$instance['depth'] = strip_tags( $new_instance['depth'] );
		$instance['number'] = strip_tags( $new_instance['number'] );
		$instance['child_of'] = strip_tags( $new_instance['child_of'] );
		$instance['current_category'] = strip_tags( $new_instance['current_category'] );
		$instance['feed'] = strip_tags( $new_instance['feed'] );
		$instance['feed_image'] = strip_tags( $new_instance['feed_image'] );
		$instance['search'] = strip_tags( $new_instance['search'] );
		$instance['hierarchical'] = ( isset( $new_instance['hierarchical'] ) ? 1 : 0 );
		$instance['use_desc_for_title'] = ( isset( $new_instance['use_desc_for_title'] ) ? 1 : 0 );
		$instance['show_last_update'] = ( isset( $new_instance['show_last_update'] ) ? 1 : 0 );
		$instance['show_count'] = ( isset( $new_instance['show_count'] ) ? 1 : 0 );
		$instance['hide_empty'] = ( isset( $new_instance['hide_empty'] ) ? 1 : 0 );

		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 * @since 0.6
	 */
	function form( $instance ) {

		// Defaults
		$defaults = array(
			'title' => __( 'Categories' ),
			'taxonomy' => 'category',
			'depth' => '',
			'number' => '',
			'exclude_tree' => '',
			'child_of' => '',
			'current_category' => '',
			'search' => '',
			'feed' => '',
			'feed_image' => '',
			'feed_type' => '',
			'use_desc_for_title' => '',
			'show_last_update' => '',
			'show_count' => '',
			'style' => 'list',
			'include' => array(),
			'exclude' => array(),
			'hierarchical' => true,
			'hide_empty' => true,
			'order' => 'ASC',
			'orderby' => 'name'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		/* <select> element options. */
		$taxonomies = get_taxonomies( array( 'show_tagcloud' => true ), 'objects' );
		$terms = get_terms( $instance['taxonomy'] );
		$style = array( 'list' => __( 'List' ), 'none' => __( 'None' ) );
		$order = array( 'ASC' => __( 'Ascending' ), 'DESC' => __( 'Descending' ) );
		$orderby = array( 'count' => __( 'Count' ), 'ID' => __( 'ID' ), 'name' => __( 'Name' ), 'slug' => __( 'Slug' ), 'term_group' => __( 'Term Group' ) );
		$feed_type = array( '' => '', 'atom' => __( 'Atom' ), 'rdf' => __( 'RDF' ), 'rss' => __( 'RSS' ), 'rss2' => __( 'RSS 2.0' ) );

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
			<label for="<?php echo $this->get_field_id( 'style' ); ?>">Style</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>">
				<?php foreach ( $style as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['style'], $option_value ); ?>><?php echo $option_label; ?></option>
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
			<label for="<?php echo $this->get_field_id( 'orderby' ); ?>">order by</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
				<?php foreach ( $orderby as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['orderby'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'depth' ); ?>">Depth</label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'depth' ); ?>" name="<?php echo $this->get_field_name( 'depth' ); ?>" value="<?php echo $instance['depth']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>">Limit (number)</label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" />
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
			<label for="<?php echo $this->get_field_id( 'exclude_tree' ); ?>">Exclude tree (ID)</label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'exclude_tree' ); ?>" name="<?php echo $this->get_field_name( 'exclude_tree' ); ?>" value="<?php echo $instance['exclude_tree']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'child_of' ); ?>">Child of (ID)</label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'child_of' ); ?>" name="<?php echo $this->get_field_name( 'child_of' ); ?>" value="<?php echo $instance['child_of']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'current_category' ); ?>">Force current category (ID)</label>
			<input type="text" class="widefat code" id="<?php echo $this->get_field_id( 'current_category' ); ?>" name="<?php echo $this->get_field_name( 'current_category' ); ?>" value="<?php echo $instance['current_category']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'search' ); ?>">Search</label>
			<input type="text" class="widefat code" id="<?php echo $this->get_field_id( 'search' ); ?>" name="<?php echo $this->get_field_name( 'search' ); ?>" value="<?php echo $instance['search']; ?>" />
		</p>
		</div>

		<div class="bizz-widget-controls columns-3 column-last">
		<p>
			<label for="<?php echo $this->get_field_id( 'feed' ); ?>">Feed URL address</label>
			<input type="text" class="widefat code" id="<?php echo $this->get_field_id( 'feed' ); ?>" name="<?php echo $this->get_field_name( 'feed' ); ?>" value="<?php echo $instance['feed']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'feed_type' ); ?>">Feed type</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'feed_type' ); ?>" name="<?php echo $this->get_field_name( 'feed_type' ); ?>">
				<?php foreach ( $feed_type as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['feed_type'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'feed_image' ); ?>">Feed image URL address</label>
			<input type="text" class="widefat code" id="<?php echo $this->get_field_id( 'feed_image' ); ?>" name="<?php echo $this->get_field_name( 'feed_image' ); ?>" value="<?php echo $instance['feed_image']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'hierarchical' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['hierarchical'], true ); ?> id="<?php echo $this->get_field_id( 'hierarchical' ); ?>" name="<?php echo $this->get_field_name( 'hierarchical' ); ?>" /> <?php _e( 'Hierarchical?' ); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'use_desc_for_title' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['use_desc_for_title'], true ); ?> id="<?php echo $this->get_field_id( 'use_desc_for_title' ); ?>" name="<?php echo $this->get_field_name( 'use_desc_for_title' ); ?>" /> <?php _e( 'Use description?' ); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_last_update' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_last_update'], true ); ?> id="<?php echo $this->get_field_id( 'show_last_update' ); ?>" name="<?php echo $this->get_field_name( 'show_last_update' ); ?>" /> <?php _e( 'Show last update?' ); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_count' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_count'], true ); ?> id="<?php echo $this->get_field_id( 'show_count' ); ?>" name="<?php echo $this->get_field_name( 'show_count' ); ?>" /> <?php _e( 'Show count?' ); ?></label>
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
register_widget( 'Bizz_Widget_Categories' );