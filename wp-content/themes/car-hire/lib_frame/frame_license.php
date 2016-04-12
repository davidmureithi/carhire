<?php

/* LICENSE PAGE HEAD */
/*------------------------------------------------------------------*/
function bizzthemes_license_update_head(){
	global $themeid, $themenum;
	
    if(isset($_REQUEST['page']) && $_REQUEST['page'] == 'bizz-license'){
	if(isset($_REQUEST['bizz_update_save']) && $_REQUEST['bizz_update_save'] == 'save'){
              
		// API: start
		global $wpdb;
		
		$body = array(
		    'api' => $_POST['key'],
			'themeid' => $themenum,
			'domain' => $_SERVER['HTTP_HOST'],
			'ip' => $_SERVER['REMOTE_ADDR'],
			'php' => phpversion(),
			'db' => $wpdb->db_version(),
			'wp' => get_bloginfo("version")
		);
		$url = 'http://bizzthemes.com/api/';
		$result = wp_remote_get( $url, array( 'method' => 'POST', 'body' => $body) );
		
		// update if ok or error if not
		if( is_wp_error( $result ) ) { # error
			function bizzthemes_license_response_fail_warning() {
				echo "<div class='updated fade'><p>Something went wrong! Try again in a few minutes.</p></div>";
			}
			add_action('admin_notices', 'bizzthemes_license_response_fail_warning');
			return;
		} 
		else {
		    $response = unserialize($result['body']); # underialize server response
			// same theme? (check only if not agancy package)
			$themenum = $themenum.',1';
			$pieces = explode(",", $themenum);
			if ( empty($response['product']) && empty($response['package'])) { # valid?
			    function bizzthemes_license_valid_fail_warning() {
				    echo "<div class='updated fade'><p>This license key is not valid.</p></div>";
				}
				add_action('admin_notices', 'bizzthemes_license_valid_fail_warning');
				delete_option('key_'.$themeid); # delete key
				return;
			}
			if (!in_array($response['product'], $pieces)) { # correct theme?
				function bizzthemes_license_package_fail_warning() {
				    echo "<div class='updated fade'><p>This license key is valid, but does not support this theme.</p></div>";
				}
				add_action('admin_notices', 'bizzthemes_license_package_fail_warning');
				delete_option('key_'.$themeid); # delete key
				return;
			}
			if ($response['expired'] == 'true') { # expired?
			    function bizzthemes_license_expired_fail_warning() {
				    echo "<div class='updated fade'><p>This license key has expired. Renew or buy new theme package</p></div>";
				}
				add_action('admin_notices', 'bizzthemes_license_expired_fail_warning');
				delete_option('key_'.$themeid); # delete key
				return;
			}
			
			// key is valid
			$option_key['themeid'] = $themeid;
			$option_key['package'] = $response['package'];
			$option_key['api'] = $response['api'];
			$option_key['valid'] = 'true';
			$option_key = serialize($option_key);
			update_option('key_'.$themeid, $option_key);
			
		}
		// API: end

	} //End update
	} //End user input save part of the update

}
                             
add_action('admin_head','bizzthemes_license_update_head');

/* LICENSE PAGE CONTENT */
/*------------------------------------------------------------------*/
function bizzthemes_license_page(){
	
	$theme_api = bizzthemes_license_api();
		
	echo "\t<div class=\"wrap themes-page\">\n";
	
	    // options header
		bizzthemes_options_header( $options_title = 'License Control', $toggle = false );
		
		echo "\t<h2>".__('License API Key', 'bizzthemes')."</h2>\n";
		
		echo "\t\t<form method=\"post\"  enctype=\"multipart/form-data\" id=\"bizzform\" action=\"\">\n";
            
			wp_nonce_field('update-options');
	
            if(!isset($theme_api) || $theme_api['valid'] != 'true') {
			    
				echo "\t\t\t<div class=\"bizzalert_true\"><p>" . __('Validate theme API key and open all of your themes potential.', 'bizzthemes') . "</p></div>\n";
				echo "\t\t\t<p><input id=\"key\" name=\"key\" type=\"text\" size=\"35\" maxlength=\"45\" value=\"\" /> (<a href=\"http://bizzthemes.com/pricing/\" id=\"license-what\">What is this?</a>)</p>\n"; 
				echo "\t\t\t<input class=\"button\" type=\"submit\" value=\"Validate API key\" />\n";
			
			} 
			else { 
			    
				$key_val = (isset($theme_api['api'])) ? $theme_api['api'] : '';
				$package_val = (isset($theme_api['package'])) ? '<b>'.$theme_api['package'].'</b> ' : ' ';
				$license_val = (isset($theme_api['package']) && $theme_api['package'] == 'club') ? '<b>'.$theme_api['package'].'</b> ' : '<b>'.$theme_api['themeid'].' '.$theme_api['package'].'</b> ';
				echo "\t\t\t<div class=\"bizzalert_false\"><p>" . __('Your '.$license_val.'API key has been successfully validated.', 'bizzthemes') . "</p></div>\n"; 
				echo "\t\t\t<p><input id=\"key\" name=\"key\" type=\"password\" size=\"35\" maxlength=\"45\" value=\"$key_val\" /> (<a href=\"http://bizzthemes.com/pricing/\" id=\"license-what\">What is this?</a>)</p>\n"; 
				echo "\t\t\t<input class=\"button\" type=\"submit\" value=\"Update API key\" />\n";
			}
			
			echo "\t\t\t<input type=\"hidden\" name=\"bizz_update_save\" value=\"save\" />\n";
			
		echo "\t\t</form>\n";
		
		// options footer
		bizzthemes_options_footer();
		
    echo "\t</div>\n";

	
}

/* LICENSE API */
/*------------------------------------------------------------------*/
function bizzthemes_license_api(){
	global $themeid;
	
	$theme_api = get_option('key_'.$themeid);
	if (is_serialized($theme_api))
		$theme_api = unserialize($theme_api);
	else {
		$theme_api = base64_decode($theme_api);
		$theme_api = unserialize($theme_api);
	}
	
	return $theme_api;
	
}

/**
* Check a string of base64 encoded data to make sure it has actually
* been encoded.
*
* @param $encodedString string Base64 encoded string to validate.
* @return Boolean Returns true when the given string only contains 
* base64 characters; returns false if there is even one non-base64 character.
*/
function bizz_base64encoded($encodedString) {
	$length = strlen($encodedString);

	// Check every character.
	for ($i = 0; $i < $length; ++$i) {
		$c = $encodedString[$i];
		if (
		($c < '0' || $c > '9')
		&& ($c < 'a' || $c > 'z')
		&& ($c < 'A' || $c > 'Z')
		&& ($c != '+')
		&& ($c != '/')
		&& ($c != '=')
		) {
			// Bad character found.
			return false;
		}
	}
	// Only good characters found.
	return true;
}

/* DEFINE THEME LICENSE VARIABLES */
/*------------------------------------------------------------------*/
	global $themeid;
	
	$theme_api = bizzthemes_license_api();
	
	if(isset($theme_api) && isset($theme_api['package']) && $theme_api['valid'] == 'true' && $theme_api['themeid'] == $themeid){
	    if ($theme_api['package'] == 'standard')
			$bizz_package = 'c3RhbmRhcmQ=';
		elseif ($theme_api['package'] == 'agency')
			$bizz_package = 'YWdlbmN5';
		elseif ($theme_api['package'] == 'club')
			$bizz_package = 'Y2x1Yg==';
		else
			$bizz_package = 'other';
	} 
	else {
	    $bizz_package = 'ZnJlZQ==';
	}
	


