<?php
/**
 * Pages Widget
 *
 * Replaces the default WordPress Pages widget.
 * @link http://themebizz.com/themes/bizz/widgets
 *
 * In 0.6, converted functions to a class that extends WP 2.8's widget class.
 *
 * @package Bizz
 * @subpackage Widgets
 */

/**
 * Output of the Pages widget.
 * Arguments are parameters of the wp_list_pages() function.
 * @link http://codex.wordpress.org/Template_Tags/wp_list_pages
 *
 * @since 0.6
 */

// WIDGET CLASS
class Bizz_Widget_Pages extends WP_Widget {

	function Bizz_Widget_Pages() {
		$widget_ops = array( 'classname' => 'pages', 'description' => __( 'Control the output of your page links.') );
		$control_ops = array( 'width' => 550, 'height' => 350, 'id_base' => "widgets-reloaded-bizz-pages" );
		$this->WP_Widget( "widgets-reloaded-bizz-pages", __( 'Pages'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );

		$args = array();

		$args['sort_column'] = $instance['sort_column'];
		$args['sort_order'] = $instance['sort_order'];
		$args['depth'] = intval( $instance['depth'] );
		$args['child_of'] = intval( $instance['child_of'] );
		$args['meta_key'] = $instance['meta_key'];
		$args['meta_value'] = $instance['meta_value'];
		$args['authors'] = ( isset($instance['authors']) && is_array( $instance['authors'] ) ? join( ', ', $instance['authors'] ) : '' );
		$args['include'] = ( isset($instance['include']) && is_array( $instance['include'] ) ? join( ', ', $instance['include'] ) : '' );
		$args['exclude'] = ( isset($instance['exclude']) && is_array( $instance['exclude'] ) ? join( ', ', $instance['exclude'] ) : '' );
		$args['exclude_tree'] = $instance['exclude_tree'];
		$args['link_before'] = $instance['link_before'];
		$args['link_after'] = $instance['link_after'];
		$args['date_format'] = $instance['date_format'];
		$args['show_date'] = $instance['show_date'];
		$args['number'] = intval( $instance['number'] );
		$args['offset'] = intval( $instance['offset'] );
		$args['hierarchical'] = isset( $instance['hierarchical'] ) ? $instance['hierarchical'] : false;
		$args['title_li'] = false;
		$args['echo'] = false;

		/* Open the output of the widget. */
		echo $before_widget;

		/* If there is a title given, add it along with the $before_title and $after_title variables. */
		if ( $instance['title'] )
			echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;

		/* Output the page list. */
		echo '<ul class="xoxo pages">' . str_replace( array( "\r", "\n", "\t" ), '', wp_list_pages( $args ) ) . '</ul>';

		/* Close the output of the widget. */
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = $new_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['depth'] = strip_tags( $new_instance['depth'] );
		$instance['child_of'] = strip_tags( $new_instance['child_of'] );
		$instance['meta_key'] = strip_tags( $new_instance['meta_key'] );
		$instance['meta_value'] = strip_tags( $new_instance['meta_value'] );
		$instance['exclude_tree'] = strip_tags( $new_instance['exclude_tree'] );
		$instance['date_format'] = strip_tags( $new_instance['date_format'] );
		$instance['number'] = strip_tags( $new_instance['number'] );
		$instance['offset'] = strip_tags( $new_instance['offset'] );
		$instance['sort_column'] = $new_instance['sort_column'];
		$instance['sort_order'] = $new_instance['sort_order'];
		$instance['show_date'] = $new_instance['show_date'];
		$instance['link_before'] = $new_instance['link_before'];
		$instance['link_after'] = $new_instance['link_after'];

		$instance['hierarchical'] = ( isset( $new_instance['hierarchical'] ) ? 1 : 0 );

		return $instance;
	}

	function form( $instance ) {

		//Defaults
		$defaults = array(
			'title' => __( 'Pages'),
			'depth' => '',
			'number' => '',
			'offset' => '',
			'child_of' => '',
			'include' => '',
			'exclude' => '',
			'exclude_tree' => '',
			'meta_key' => '',
			'meta_value' => '',
			'authors' => '',
			'show_date' => '',
			'link_before' => '',
			'link_after' => '',
			'hierarchical' => true,
			'sort_column' => 'post_title',
			'sort_order' => 'ASC',
			'date_format' => get_option( 'date_format' )
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$posts = get_posts( array( 'post_type' => 'page', 'post_status' => 'any', 'post_mime_type' => '', 'orderby' => 'title', 'order' => 'ASC', 'numberposts' => -1 ) );
		$authors = array();
		foreach ( $posts as $post )
			$authors[$post->post_author] = get_the_author_meta( 'display_name', $post->post_author );

		$sort_order = array( 'ASC' => __( 'Ascending' ), 'DESC' => __( 'Descending' ) );
		$sort_column = array( 'post_author' => __( 'Author' ), 'post_date' => __( 'Date' ), 'ID' => __( 'ID' ), 'menu_order' => __( 'Menu Order' ), 'post_modified' => __( 'Modified' ), 'post_name' => __( 'Slug' ), 'post_title' => __( 'Title' ) );
		$show_date = array( '' => '', 'created' => __( 'Created' ), 'modified' => __( 'Modified' ) );
		$meta_key = array_merge( array( '' ), (array) get_meta_keys() );
		?>

		<div class="bizz-widget-controls columns-3">
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'sort_order' ); ?>">Sort order</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'sort_order' ); ?>" name="<?php echo $this->get_field_name( 'sort_order' ); ?>">
				<?php foreach ( $sort_order as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['sort_order'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'sort_column' ); ?>">Sort column</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'sort_column' ); ?>" name="<?php echo $this->get_field_name( 'sort_column' ); ?>">
				<?php foreach ( $sort_column as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['sort_column'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'depth' ); ?>">Depth</label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'depth' ); ?>" name="<?php echo $this->get_field_name( 'depth' ); ?>" value="<?php echo $instance['depth']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>">Number</label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'offset' ); ?>">Offset</label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'offset' ); ?>" name="<?php echo $this->get_field_name( 'offset' ); ?>" value="<?php echo $instance['offset']; ?>"  />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'child_of' ); ?>">Child of (ID)</label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'child_of' ); ?>" name="<?php echo $this->get_field_name( 'child_of' ); ?>" value="<?php echo $instance['child_of']; ?>" />
		</p>
		</div>

		<div class="bizz-widget-controls columns-3">

		<p>
			<label for="<?php echo $this->get_field_id( 'include' ); ?>">Include</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'include' ); ?>" name="<?php echo $this->get_field_name( 'include' ); ?>[]" size="4" multiple="multiple">
				<?php foreach ( $posts as $post ) { ?>
					<option value="<?php echo $post->ID; ?>" <?php echo ( in_array( $post->ID, (array) $instance['include'] ) ? 'selected="selected"' : '' ); ?>><?php echo esc_attr( $post->post_title ); ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'exclude' ); ?>">Exclude</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'exclude' ); ?>" name="<?php echo $this->get_field_name( 'exclude' ); ?>[]" size="4" multiple="multiple">
				<?php foreach ( $posts as $post ) { ?>
					<option value="<?php echo $post->ID; ?>" <?php echo ( in_array( $post->ID, (array) $instance['exclude'] ) ? 'selected="selected"' : '' ); ?>><?php echo esc_attr( $post->post_title ); ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'exclude_tree' ); ?>">Exclude tree (ID)</label>
			<input type="text" class="widefat code" id="<?php echo $this->get_field_id( 'exclude_tree' ); ?>" name="<?php echo $this->get_field_name( 'exclude_tree' ); ?>" value="<?php echo $instance['exclude_tree']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'meta_key' ); ?>">Meta key</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'meta_key' ); ?>" name="<?php echo $this->get_field_name( 'meta_key' ); ?>">
				<?php foreach ( $meta_key as $meta ) { ?>
					<option value="<?php echo $meta; ?>" <?php selected( $instance['meta_key'], $meta ); ?>><?php echo $meta; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'meta_value' ); ?>">Meta value</label>
			<input type="text" class="widefat code" id="<?php echo $this->get_field_id( 'meta_value' ); ?>" name="<?php echo $this->get_field_name( 'meta_value' ); ?>" value="<?php echo $instance['meta_value']; ?>" />
		</p>
		</div>

		<div class="bizz-widget-controls columns-3 column-last">

		<p>
			<label for="<?php echo $this->get_field_id( 'authors' ); ?>">Authors</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'authors' ); ?>" name="<?php echo $this->get_field_name( 'authors' ); ?>[]" size="4" multiple="multiple">
				<?php foreach ( $authors as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php echo ( in_array( $option_value, (array) $instance['authors'] ) ? 'selected="selected"' : '' ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link_before' ); ?>">Before link</label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'link_before' ); ?>" name="<?php echo $this->get_field_name( 'link_before' ); ?>" value="<?php echo $instance['link_before']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link_after' ); ?>">After link</label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'link_after' ); ?>" name="<?php echo $this->get_field_name( 'link_after' ); ?>" value="<?php echo $instance['link_after']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_date' ); ?>">Show date</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>">
				<?php foreach ( $show_date as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['show_date'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'date_format' ); ?>">Date format</label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'date_format' ); ?>" name="<?php echo $this->get_field_name( 'date_format' ); ?>" value="<?php echo $instance['date_format']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'hierarchical' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['hierarchical'], true ); ?> id="<?php echo $this->get_field_id( 'hierarchical' ); ?>" name="<?php echo $this->get_field_name( 'hierarchical' ); ?>" /> <?php _e( 'Hierarchical?'); ?></label>
		</p>
		</div>
		<div style="clear:both;">&nbsp;</div>
	<?php
	}
}

// INITIATE WIDGET
register_widget( 'Bizz_Widget_Pages' );