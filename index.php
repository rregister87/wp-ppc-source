<?php
	
/*
Plugin Name: Wordpress PPC Lead Source
Description: A plugin to help track PPC conversion sources by converting incoming query strings to session variables
Author: Rob Register
Version: 1.0
Author URI: http://rregister.net
*/

/*
	break the query string down to session variables,
	excluding common WordPress query strings
*/

function wp_ppc_src() {
	$query_string = $_SERVER['QUERY_STRING'];
	parse_str($query_string, $queries);
	wp_ppc_session($queries);
}
add_action('init', 'wp_ppc_src', 1);

function wp_ppc_session($queries) {
	if(!session_id()) {
		session_start();
	}
	foreach($queries as $term=>$value) {
		$to_exclude = array('action', 'page', 'paged', 'plugin_status', 'post', 'post_type', 's', 'message');
		if(!in_array($term, $to_exclude)) {
			$_SESSION['wp_ppc_' . $term] = $value;
		}
	}
}


/*
	dump the session variables created from query strings to the JS console,
	for debugging purposes
*/

function wp_ppc_js_console() {
	$to_log = array();
	foreach($_SESSION as $session_var=>$value) {
		if(preg_match('/wp_ppc_/', $session_var)) {
			$to_log[$session_var] = $value;
		}
	}
	if(count($to_log > 0)) {
		echo '<script type="text/javascript">console.log(' . json_encode($to_log). ');</script>';
	}
}
add_action('wp_footer', 'wp_ppc_js_console');


/*
	create a shortcode for displaying a specific query string,
	ex. [wp_ppc term="test" input_name="utm_source"],
	outputs a hidden field to be used inside form code.
*/

function wp_ppc_shortcode($atts, $content = null) {
	if(!empty($atts['term']) && !empty($atts['input_name'])) {
		if(!empty($_SESSION['wp_ppc_' . $atts['term']])) {
			return '<input name="' . $atts['input_name'] . '" type="hidden" value="' . $_SESSION['wp_ppc_' . $atts['term']] . '">';
		}
	}
}
add_shortcode('wp-ppc', 'wp_ppc_shortcode');
add_shortcode('wp_ppc', 'wp_ppc_shortcode');

?>