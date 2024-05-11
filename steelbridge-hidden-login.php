<?php
/**
 * Plugin Name: steelbridge Hidden Login
 * Plugin URI: http://steelbridge.io
 * Description: This plugin provides a hidden login functionality.
 * Author: Chris Parsons
 * Author URI: http://steelbridge.io
 * Version: 1.0
 * License: GPL2
 */


/**
 * Appends a cookie to the response if the current request URI matches '/hidden-login' or '/hidden-login/'.
 *
 * The cookie name is 'custom_login_page'.
 *
 * The cookie will expire after 300 seconds.
 *
 * The cookie will be available on all paths (COOKIEPATH) and all subdomains (COOKIE_DOMAIN).
 *
 * @return void
 */
function append_cookie() {
	/* Line 25 */ if ($_SERVER['REQUEST_URI'] === '/hidden-login' ||
	                  $_SERVER['REQUEST_URI'] === '/hidden-login/') {
		setcookie( 'custom_login_page', 1, time() + 300, COOKIEPATH, COOKIE_DOMAIN );
	}
}
add_action( 'init', 'append_cookie' );

function check_custom_page() {
	$pagenow = $GLOBALS['pagenow'];
	
	// Add a check for the 'action' query string.
	// If 'action' is set and its value is 'logout', let the request through
	if($pagenow === 'wp-login.php' && $_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_COOKIE['custom_login_page']) && (!isset($_GET['action']) || $_GET['action'] !== 'logout')) {
		wp_redirect(home_url('/404'));
		exit();
	}
	else if(($pagenow === 'wp-login.php' && $_SERVER['REQUEST_METHOD'] == 'POST') || is_admin()) {
		if(is_admin() && !is_user_logged_in() && !isset($_COOKIE['custom_login_page'])) {
			wp_redirect(home_url('/404'));
			exit();
		}
		
		// Only unset the cookie if it exists and the user logs out
		if (isset($_COOKIE['custom_login_page']) && isset($_GET['action']) && $_GET['action'] == 'logout') {
			unset($_COOKIE['custom_login_page']);
			setcookie('custom_login_page', null, -1, '/');
			return false;
		}
	}
}

add_action('init', 'check_custom_page');

function unset_on_logout() {
	if (isset($_COOKIE['custom_login_page'])) {
		unset($_COOKIE['custom_login_page']);
		setcookie('custom_login_page', '', time() - 3600, '/');
	}
}
add_action('wp_logout', 'unset_on_logout');

function redirect_after_logout(){
	wp_redirect( home_url('/') );
	exit();
}
add_action('wp_logout','redirect_after_logout');