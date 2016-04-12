<?php

/*

  FILE STRUCTURE:

- THEME SCRIPTS

*/

/* THEME SCRIPTS */
/*------------------------------------------------------------------*/

// Add Theme Javascript
if (!is_admin()) add_action( 'wp_print_scripts', 'bizz_add_javascript' );
function bizz_add_javascript() {

	$opt_s = get_option('booking_options');

	/* online
	wp_deregister_script( 'jquery' ); #deregister current jquery
	wp_deregister_script( 'jquery-color' ); #deregister current jquery color
	wp_enqueue_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js'); #header
	*/

	// offline
	wp_enqueue_script( 'jquery'); #header
	
	wp_enqueue_script( 'theme-js', BIZZ_THEME_JS .'/theme.js', array( 'jquery' ) ); # header

}

// Add Theme Meta Tags
add_action('wp_head', 'bizzthemes_theme_head_meta');
function bizzthemes_theme_head_meta() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">'."\n";
}
