<?php
/**
 * Twitter Widget Class
 *
 * Twitter widget displays your twitter updates.
 *
 */

if ( !function_exists('wpcom_time_since') ) :
/*
 * Time since function taken from WordPress.com
 */

function wpcom_time_since( $original, $do_more = 0 ) {
	// array of time period chunks
	$chunks = array(
			array(60 * 60 * 24 * 365 , 'year'),
			array(60 * 60 * 24 * 30 , 'month'),
			array(60 * 60 * 24 * 7, 'week'),
			array(60 * 60 * 24 , 'day'),
			array(60 * 60 , 'hour'),
			array(60 , 'minute'),
	);

	$today = time();
	$since = $today - $original;

	for ($i = 0, $j = count($chunks); $i < $j; $i++) {
			$seconds = $chunks[$i][0];
			$name = $chunks[$i][1];

			if (($count = floor($since / $seconds)) != 0)
					break;
	}

	$print = ($count == 1) ? '1 '.$name : "$count {$name}s";

	if ($i + 1 < $j) {
			$seconds2 = $chunks[$i + 1][0];
			$name2 = $chunks[$i + 1][1];

			// add second item if it's greater than 0
			if ( (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) && $do_more )
					$print .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
	}
	return $print;
}
endif;

// WIDGET CLASS
if (!class_exists('Wickett_Twitter_Widget')) {

	class Wickett_Twitter_Widget extends WP_Widget {
		var $prefix;
		var $textdomain;

		function Wickett_Twitter_Widget() {
			$this->prefix = bizz_get_prefix();
			$widget_ops = array('classname' => 'widget_twitter', 'description' => __( 'Twitter widget displays your twitter photos.' ) );
			$this->WP_Widget('twitter', __('Twitter Updates'), $widget_ops);
		}
		
		function widget( $args, $instance ) {
			extract( $args );

			$account = urlencode( $instance['account'] );
			if ( empty($account) ) return;
			$title 				= apply_filters('widget_title', $instance['title']);
			if ( empty($title) ) $title = __( 'Twitter Updates' );
			$show 				= absint( $instance['show'] );  // # of Updates to show
			$hidereplies 		= $instance['hidereplies'];
			$before_timesince 	= esc_html($instance['beforetimesince']);
			if ( empty($before_timesince) ) $before_timesince = ' ';
			
			echo $before_widget;

			if ( $instance['title'] )
				echo $before_title . apply_filters( 'widget_title', $instance['title'] ) . $after_title;
				
			echo '<div id="twitter">';
			
			// Save a transient to the database
			//   set_transient($transient, $value, $expiration);
			
			// Fetch a saved transient
			//   get_transient($transient);
			
			// Remove a saved transient
			//   delete_transient($transient);

			$tweet_saved = get_transient( 'widget-twitter-' . $this->number );
			$tweet_saved_stream = get_option( 'widget-twitter-response-' . $this->number );
			if ( empty($tweet_saved) ) {
				$twitter_json_url = esc_url( "http://api.twitter.com/1/statuses/user_timeline.json?include_rts=true&screen_name=$account", null, 'raw' );
				$response = wp_remote_get( $twitter_json_url, array( 'User-Agent' => 'Twitter Updates' ) );
				$response_code = wp_remote_retrieve_response_code( $response );
				if ( 200 == $response_code ) {
					$tweets = wp_remote_retrieve_body( $response );
					$tweets = json_decode( $tweets);
					$expire = 200;
					if ( !is_array( $tweets ) || isset( $tweets['error'] ) ) {
						$tweets = 'error';
						$expire = 30;
					} 
					elseif ( is_array( $tweets ) || !isset( $tweets['error'] ) ) {
						// update only if no error
						update_option( 'widget-twitter-response-' . $this->number, $tweets);
					}
					set_transient( 'widget-twitter-' . $this->number, $tweets, $expire);
				} 
				else {
					$tweets = 'error';
					$expire = 30;
					set_transient( 'widget-twitter-response-code-' . $this->number, $response_code, $expire);
				}

			}
			
			$tweets = $tweet_saved_stream;
			
			if ( 'error' != $tweets ) :
				echo "<ul class='twitter_update_list'>\n";
		
				$tweets_out = 0;

				foreach ( (array) $tweets as $tweet ) {
					if ( $tweets_out >= $show )
						break;

					if ( empty( $tweet->text ) || ($hidereplies && !empty($tweet->in_reply_to_user_id)) )
						continue;

					$text = make_clickable(esc_html($tweet->text));
					$text = preg_replace_callback('/(^|\s)@(\w+)/', array($this, '_widget_twitter_username'), $text);
					$text = preg_replace_callback('/(^|\s)#(\w+)/', array($this, '_widget_twitter_hashtag'), $text);

					// Move the year for PHP4 compat
					$created_at = substr($tweet->created_at, 0, 10) . substr($tweet->created_at, 25, 5) . substr($tweet->created_at, 10, 15);

					echo "<li class='twitter-item'>{$text}{$before_timesince}<span class='date'><a href='" . esc_url( "http://twitter.com/{$account}/statuses/" . urlencode($tweet->id_str) ) . "' class='timesince'>" . str_replace(' ', '&nbsp;', wpcom_time_since(strtotime($created_at))) . "&nbsp;ago</a></span></li>\n";
					$tweets_out++;
				}
		
				echo "</ul>\n";
			else :
				if ( 401 == get_transient( 'widget-twitter-response-code-' . $this->number , 'widget' ) )
					echo "<p>" . __("Error: Please make sure the Twitter account is <a href='http://help.twitter.com/forums/10711/entries/14016'>public</a>.") . "</p>";
				else
					echo "<p>" . __("Error: Twitter did not respond. Please wait a few minutes and refresh this page.") . "</p>";
			endif;
			

				echo '<div class="website">';
					echo '<div class="follow-text"><a href="http://www.twitter.com/'.$instance['account'].'/" title="'.$instance['twitter_follow'].'">'.$instance['twitter_follow'].'</a></div>';
				echo '</div>';
			echo '</div>';
		
			echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['account'] = strip_tags(stripslashes($new_instance['account']));
			$instance['account'] = str_replace('http://twitter.com/', '', $instance['account']);
			$instance['account'] = str_replace('/', '', $instance['account']);
			$instance['account'] = str_replace('@', '', $instance['account']);
			$instance['title'] = strip_tags(stripslashes($new_instance['title']));
			$instance['show'] = absint($new_instance['show']);
			$instance['hidereplies'] = isset($new_instance['hidereplies']);
			$instance['beforetimesince'] = $new_instance['beforetimesince'];
			$instance['twitter_follow'] = $new_instance['twitter_follow'];

			wp_cache_delete( 'widget-twitter-' . $this->number , 'widget' );
			wp_cache_delete( 'widget-twitter-response-code-' . $this->number, 'widget' );

			return $instance;
		}
		
		function form( $instance ) {

			//Defaults
			$defaults = array(
				'title' => __( 'Twitter Updates' ),
				'account' => 'BizzThemes',
				'show' => '3',
				'hidereplies' => false,
				'beforetimesince' => '',
				'twitter_follow' => 'Follow Us on Twitter',
			);
			$instance = wp_parse_args( (array) $instance, $defaults );
			
			$show = array("0","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20");

			?>

			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('account'); ?>"><?php _e('Account:','bizzthemes'); ?></label>
				<input type="text" name="<?php echo $this->get_field_name('account'); ?>" value="<?php echo $instance['account']; ?>" class="widefat" id="<?php echo $this->get_field_id('account'); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'show' ); ?>"><?php _e('# of Updates to Show:','bizzthemes'); ?></label> 
				<select class="widefat" id="<?php echo $this->get_field_id( 'show' ); ?>" name="<?php echo $this->get_field_name( 'show' ); ?>">
					<?php foreach ( $show as $option_value => $option_label ) { ?>
						<option value="<?php echo $option_value; ?>" <?php selected( $instance['show'], $option_value ); ?>><?php echo $option_label; ?></option>
					<?php } ?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'hidereplies' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $instance['hidereplies'], true ); ?> id="<?php echo $this->get_field_id( 'hidereplies' ); ?>" name="<?php echo $this->get_field_name( 'hidereplies' ); ?>" /> <?php _e('Hide replies','bizzthemes'); ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('beforetimesince'); ?>"><?php _e('Text between tweet and timestamp:','bizzthemes'); ?></label>
				<input type="text" name="<?php echo $this->get_field_name('beforetimesince'); ?>" value="<?php echo $instance['beforetimesince']; ?>" class="widefat" id="<?php echo $this->get_field_id('beforetimesince'); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('twitter_follow'); ?>"><?php _e('Follow us Name:','bizzthemes'); ?></label>
				<input type="text" name="<?php echo $this->get_field_name('twitter_follow'); ?>" value="<?php echo $instance['twitter_follow']; ?>" class="widefat" id="<?php echo $this->get_field_id('twitter_follow'); ?>" />
			</p>
			<div style="clear:both;">&nbsp;</div>
		<?php
		}

		function _widget_twitter_username( $matches ) { // $matches has already been through esc_html
			return "$matches[1]@<a href='" . esc_url( 'http://twitter.com/' . urlencode( $matches[2] ) ) . "'>$matches[2]</a>";
		}

		function _widget_twitter_hashtag( $matches ) { // $matches has already been through esc_html
			return "$matches[1]<a href='" . esc_url( 'http://search.twitter.com/search?q=%23' . urlencode( $matches[2] ) ) . "'>#$matches[2]</a>";
		}

	}

	// INITIATE WIDGET
	register_widget('Wickett_Twitter_Widget');

}
