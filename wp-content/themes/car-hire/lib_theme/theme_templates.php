<?php

/* Custom templates: Contact Form */
/*------------------------------------------------------------------*/
add_action( 'bizz_sidebar_grid_before', 'bizz_template_cform' );
function bizz_template_cform( $grid ) {
		
	if ( is_page_template( 'template-contact.php' ) ) {
	
		// remove all widgets for this grid
		if ( in_array($grid, array('main_one')) )
			remove_action( 'bizz_sidebar_grid', 'bizz_dynamic_sidebar', 10, 2 );
		
		// add custom code
		if ( in_array($grid, array('main_one')) ) {
						
			$defaults = array(
				'title' => get_the_title(),
				'wid_email' => apply_filters( 'cform_template_email',  get_option('admin_email') ),  
				'wid_trans1' => __( 'This field is required. Please enter a value.', 'bizzthemes' ),
				'wid_trans2' => __( 'Invalid email address.', 'bizzthemes' ),
				'wid_trans3' => __( 'Contact Form Submission from ', 'bizzthemes' ),
				'wid_trans7' => __( 'You emailed ', 'bizzthemes' ),
				'wid_trans9' => __( 'You forgot to enter your', 'bizzthemes' ),
				'wid_trans10' => __( 'You entered an invalid', 'bizzthemes' ),
				'wid_trans11' => __( '<b>Thanks!</b> Your email was successfully sent.', 'bizzthemes' ),
				'wid_trans12' => __( 'There was an error submitting the form.', 'bizzthemes' ),
				'wid_trans13' => __( 'E-mail has not been setup properly. Please add your contact e-mail!', 'bizzthemes' ),
				'wid_trans14' => __( 'Name<span>*</span>', 'bizzthemes' ),
				'wid_trans15' => __( 'Email<span>*</span>', 'bizzthemes' ),
				'wid_trans16' => __( 'Message<span>*</span>', 'bizzthemes' ),
				'wid_trans17' => __( 'Send a copy to yourself', 'bizzthemes' ),
				'wid_trans18' => __( 'If you want to submit this form, do not enter anything in this field', 'bizzthemes' ),
				'wid_trans19' => __( 'Submit', 'bizzthemes' )
			);
			the_widget('Bizz_Widget_Contact', $defaults, array( 'before_widget' => '<div class="widget">', 'after_widget' => '</div>', 'before_title' => '<h3 class="widget-title"><span>', 'after_title' => '</span></h3>' ));
		}
	
	}
	
}

/* Custom templates: Sitemap */
/*------------------------------------------------------------------*/
add_action( 'bizz_sidebar_grid_before', 'bizz_template_sitemap' );
function bizz_template_sitemap( $grid ) {
		
	if ( is_page_template( 'template-sitemap.php' ) ) {
	
		// remove all widgets for this grid
		if ( in_array($grid, array('main_one')) )
			remove_action( 'bizz_sidebar_grid', 'bizz_dynamic_sidebar', 10, 2 );
		
		// add custom code
		if ( in_array($grid, array('main_one')) ) {
						
?>
			<div class="widget">
				<div class="headline_area">
					<h1 class="title"><?php the_title(); ?></h1>
				</div>
				<div class="format_text">
					<div class="one_half">
						<?php if (have_posts()) : the_post(); ?>
						<?php the_content(); ?>
						<?php endif; ?>  																  
						<h3><?php _e( 'Pages', 'bizzthemes' ) ?></h3>
						<ul>
							<?php wp_list_pages( 'depth=0&sort_column=menu_order&title_li=' ); ?>		
						</ul>
					</div>
					<div class="one_half last">
						<h3><?php _e( 'Services', 'bizzthemes' ); ?></h3>
						<ul>
						<?php
							$args = array( 'post_type' => 'bizz_services', 'posts_per_page' => 100 );
							$loop = new WP_Query( $args );
							while ( $loop->have_posts() ) : $loop->the_post();
						?>
							<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
						<?php endwhile; ?>
						</ul>
						<h3><?php _e( 'Testimonials', 'bizzthemes' ); ?></h3>
						<ul>
						<?php
							$args = array( 'post_type' => 'bizz_feedback', 'posts_per_page' => 100 );
							$loop = new WP_Query( $args );
							while ( $loop->have_posts() ) : $loop->the_post();
						?>
							<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
						<?php endwhile; ?>
						</ul>
					</div>
				</div>
	        </div>
<?php		
		}
	
	}
	
}

/* Custom templates: Archives */
/*------------------------------------------------------------------*/
add_action( 'bizz_sidebar_grid_before', 'bizz_template_archives' );
function bizz_template_archives( $grid ) {
		
	if ( is_page_template( 'template-archives.php' ) ) {
	
		// remove all widgets for this grid
		if ( in_array($grid, array('main_one')) )
			remove_action( 'bizz_sidebar_grid', 'bizz_dynamic_sidebar', 10, 2 );
		
		// add custom code
		if ( in_array($grid, array('main_one')) ) {
						
?>
			<div class="widget">
				<div class="headline_area">
					<h1 class="title"><?php the_title(); ?></h1>
				</div>
				<div class="format_text">
					<div class="post-content">
						<?php get_the_content(); ?>
					</div>
					<div class="archives">
						<?php
						global $wpdb;
						$cdate = date('Y-m-d H:i:s');
						$years = $wpdb->get_results("SELECT DISTINCT MONTH(post_date) AS month, YEAR(post_date) as year FROM $wpdb->posts WHERE post_status = 'publish' and post_date <= \"$cdate\" and post_type = 'post' ORDER BY post_date DESC");
						if($years) {
							foreach($years as $years_obj) {
								$year = $years_obj->year;	
								$month = $years_obj->month;
							?>
							<?php query_posts("showposts=1000&year=$year&monthnum=$month"); ?>
							<div class="arclist">
							<h3> <?php echo  date('F', mktime(0,0,0,$month,1)).', '. $year; ?> </h3>
							<ul>
							  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
							  <li> <a href="<?php the_permalink() ?>">
								<?php the_title(); ?>
								</a> - <span class="arclist_date">
								<?php the_time('M j ') ?>
								</span> </li>
							  <?php endwhile; endif; ?>
							</ul>
							</div>
							<?php
							}
						}
						?>
					</div>
				</div>
	        </div>
<?php		
		}
	
	}
	
}

/* Custom templates: Blog */
/*------------------------------------------------------------------*/
add_action( 'bizz_sidebar_grid_before', 'bizz_template_blog' );
function bizz_template_blog( $grid ) {
		
	if ( is_page_template( 'template-blog.php' ) ) {
	
		// remove all widgets for this grid
		if ( in_array($grid, array('main_one')) )
			remove_action( 'bizz_sidebar_grid', 'bizz_dynamic_sidebar', 10, 2 );
		
		// add custom code
		if ( in_array($grid, array('main_one')) ) {
						
			$defaults = array(
				'title' => get_the_title(),
				'display' => 'ul',
				'post_status' => array( 'publish' ),
				'post_type' => array( 'post' ),
				'post_mime_type' => array( '' ),
				'order' => 'DESC',
				'orderby' => 'date',
				'caller_get_posts' => true,
				'enable_pagination' => true,
				'ajax_pagination' => false,
				'posts_per_page' => get_option( 'posts_per_page' ),
				'offset' => '0',
				'author' => '',
				'wp_reset_query' => true,
				'meta_compare' => '',
				'meta_key' => '',
				'meta_value' => '',
				'year' => '',
				'monthnum' => '',
				'w' => '',
				'day' => '',
				'hour' => '',
				'minute' => '',
				'second' => '',
				'post_parent' => '',
				'entry_container' => 'div',
				'show_entry_title' => true,
				'entry_title' => 'h2',
				'wp_link_pages' => true,
				'error_message' => __( 'Apologies, but no results were found.', 'bizzthemes' ),
				'post_author' => false,
				'post_date' => true,
				'post_comments' => true,
				'post_categories' => false,
				'post_tags' => false,
				'post_edit' => false,
				'thumb_display' => false,
				'thumb_selflink' => false,
				'thumb_width' => 150,
				'thumb_height' => 150,
				'thumb_align' => 'alignright',
				'thumb_cropp' => 'c',
				'thumb_filter' => '',
				'thumb_sharpen' => '',
				'post_columns' => 1,
				'remove_posts' => false,
				'full_posts' => false,
				'read_more' => true,
				'read_more_text' => 'Continue reading'
			);
			the_widget('Bizz_Widget_Query_Posts', $defaults, array( 'before_widget' => '<div class="widget">', 'after_widget' => '</div>', 'before_title' => '<h1 class="title"><span>', 'after_title' => '</span></h1>' ));
		
		}
	
	}
	
}

/* Custom templates: No Sidebar */
/*------------------------------------------------------------------*/

// add
add_action( 'bizz_sidebar_grid_before', 'bizz_template_nosidebar' );
function bizz_template_nosidebar( $grid ) {
	global $classes;
		
	if ( is_page_template( 'template-no-sidebar.php' ) ) {
	
		// remove all widgets for this grid
		if ( in_array($grid, array('main_full', 'main_one', 'main_two')) )
			remove_action( 'bizz_sidebar_grid', 'bizz_dynamic_sidebar', 10, 2 );
		
		// add custom code
		if ( in_array($grid, array('main_full')) ) {
		
			$post_classes = implode(' ', get_post_class($classes, get_the_ID()));
						
?>
			<div class="widget">
				<div class="content_area clearfix">
				<div class="<?php echo $post_classes; ?> post_box">
				<div class="headline_area">
					<h1 class="title"><?php the_title(); ?></h1>
				</div>
				<div class="format_text">
					<?php if (have_posts()) : the_post(); ?>
						<?php the_content(); ?>
					<?php endif; ?>
				</div>
				</div>
				</div>
			</div>
<?php		
		}
	
	}
	
}