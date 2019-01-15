<?php
/*
Plugin Name: Hide Login+
Description: This plugin allows you to create custom URLs for user's Log in, Log out, Sign up and Admin page.
Author: Mohammad Hossein Aghanabi
Version: 3.5.1
*/
/*
This is a new version of Stealth Login plguin by skullbit
*/
/**
 * [hideOptions Sets plugin default options on activation]
 * @return [void]
 */

function hideOptions()
{
    delete_option("hide_login_slug");
	delete_option("hide_admin_slug");
	delete_option("hide_logout_slug");
	delete_option("hide_register_slug");
	delete_option("hide_forgot_slug");
	delete_option("hide_wplogin");
	delete_option("hide_wpadmin");
	delete_option("hide_rules");
	add_option("hide_login_slug", "login");
	add_option("hide_admin_slug", "");
	add_option("hide_logout_slug", "logout");
	add_option("hide_register_slug", "register");
	add_option("hide_forgot_slug", "forgot");
	add_option("hide_wplogin", 0);
	add_option("hide_wpadmin", 0);
	add_option("hide_rules", "");
}
register_activation_hook( __FILE__ , 'hideOptions' );

define("LOGIN_SLUG", get_option("hide_login_slug", "login"));
define("ADMIN_SLUG", get_option("hide_admin_slug"));
define("LOGOUT_SLUG", get_option("hide_logout_slug", "logout"));
define("REGISTER_SLUG", get_option("hide_register_slug", "register"));
define("FORGOT_SLUG", get_option("hide_forgot_slug", "forgot"));

/**
 * [_setup controls access over wp-login.php, logout and wp-admin URLs]
 * @return [void]
 */
function _setup() {

	global $current_user;

	if(get_option("hide_wplogin") == 1) {
		if(requestURI() == 'wp-login.php') {
			wp_redirect(get_option('siteurl'), 302);
			exit;
		}
	}
	if(requestURI() == LOGOUT_SLUG) {
		if(ADMIN_SLUG != "")
			setcookie( is_ssl() ? SECURE_AUTH_COOKIE : AUTH_COOKIE, ' ', time() - YEAR_IN_SECONDS, SITECOOKIEPATH . ADMIN_SLUG, COOKIE_DOMAIN );
		wp_logout();
		wp_redirect(get_option('siteurl'));
		exit;
	}
	if(get_option("hide_wpadmin") == 1 && !user_can( $current_user, "edit_posts" )) {
		if(requestURI() == 'wp-admin') {
			wp_redirect(get_option('siteurl'));
			exit;
		}
	}
}
add_action('init', '_setup');

// Hooked to Wordpress for changing cookie on new admin page
function setAdminCookie($auth_cookie, $expire) {
	setcookie(is_ssl() ? SECURE_AUTH_COOKIE : AUTH_COOKIE, $auth_cookie, $expire, SITECOOKIEPATH . ADMIN_SLUG, COOKIE_DOMAIN, is_ssl(), true);
}

// Changes wp-admin slug everywhere
function changeAdminURL( $url ) {
	return str_replace("wp-admin", ADMIN_SLUG, $url);
}

if(ADMIN_SLUG != "") {
	add_action("set_auth_cookie", "setAdminCookie", 10, 2);
	add_filter('site_url',  'changeAdminURL', 10, 1);
}
/**
 * [requestURI Returns URL path]
 * @return string $part
 */
function requestURI()
{
	$part = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$part = trim($part, "/");
	$part = strtolower($part);
	$part = explode("/", $part);
	return $part[0];
}

/**
 * [addPanel Adds Hide Login+ menu]
 */
function addPanel()
{
	add_menu_page('Hide Login+', 'Hide Login+', 'manage_options', 'hide_settings', 'hideSettings');
}
add_action('admin_menu','addPanel');

/**
 * [updateSettings Saves changes on submitting form]
 */
function updateSettings()
{
	if( isset($_POST['action']) && $_POST['action'] == 'hide_login_update' ) {

		$_GET['type'] = "updated";
		$_GET['id'] = 0;

		$error = false;

		array_walk($_POST, function(&$item, $key) use (&$error)
		{
			if(substr($key, 0, 5) == 'hide_' && !$error) {
				$item = preg_replace("/[^A-Za-z0-9_\-]/", "", $item);
				if(strlen($item) < 1 && $key != 'hide_admin_slug')
				{
					$error = true;
				}
				$item = substr($item, 0, 24);
			}
		});

		if(weHaveError($error)) {
			$type = 'error';
			$id = 0;
			wp_safe_redirect("/".(ADMIN_SLUG != "") ? ADMIN_SLUG : "wp-admin"."/admin.php?page=hide_settings&type=$type&id=$id");
			exit;
		}

		update_option("hide_login_slug", $_POST['hide_login_slug']);
		update_option("hide_logout_slug", $_POST['hide_logout_slug']);
		update_option("hide_forgot_slug", $_POST['hide_forgot_slug']);
		update_option("hide_admin_slug", $_POST['hide_admin_slug']);
		update_option("hide_wplogin", in_array($_POST['hide_wplogin'], range(0,1)) ? $_POST['hide_wplogin'] : 0 );

		if(get_option('hide_admin_slug') != "")
			update_option("hide_wpadmin", in_array($_POST['hide_wpadmin'], range(0,1)) ? $_POST['hide_wpadmin'] : 0 );

		if(get_option('users_can_register'))
			update_option("hide_register_slug", $_POST['hide_register_slug']);

		hideLogin();
	}
}
add_action("admin_init", "updateSettings");

// Changes logout URL slug everywhere
add_filter('logout_url', function ($url, $redirect) {
	return home_url("/".LOGOUT_SLUG);
}, 10, 2);

// Changes login URL slug everywhere
add_filter('login_url', function ($url, $redirect) {
	return home_url("/".LOGOUT_SLUG);
}, 10, 2 );

// Changes registration URL slug everywhere
add_filter('register',function ($url) {
	return str_replace(site_url('wp-login.php?action=register', 'login'), site_url(REGISTER_SLUG, 'login'), $url);
});

// Changes lostpassword URL slug everywhere
add_filter('lostpassword_url', function ($url) {
   return str_replace('?action=lostpassword','',str_replace(network_site_url('wp-login.php', 'login'), site_url(FORGOT_SLUG, 'login'), $url));
});

/**
 * [changeURLs Modifies all related forms on initialization accroding to their new set URL]
 * @return void	$form
 */
function changeURLs()
{
	$array = array('register_form' => REGISTER_SLUG,
			'lostpassword_form' => FORGOT_SLUG,
			'resetpass_form' => FORGOT_SLUG,
			'login_form' => LOGIN_SLUG
		);

	$slug = $array[current_filter()];
	$form = ob_get_contents();
	$form = preg_replace( "/wp-login\.php([^\"]*)/", $slug.'$1', $form);
	ob_get_clean();
	echo $form;
}
add_action( 'login_form', 'changeURLs');
add_action( 'register_form', 'changeURLs');
add_action( 'lostpassword_form', 'changeURLs');
add_action( 'resetpass_form', 'changeURLs');

// Where to redirect after a successful login, default redirection is `wp-admin`
add_action('login_redirect', function () {
	global $redirect_to;
	if (!isset($_GET['redirect_to'])) {
		return get_option('siteurl')."/".(ADMIN_SLUG != "" ? ADMIN_SLUG : "wp-admin");
	}
	else
		return $redirect_to;
});

// Redirection URL after submitting lostpassword form
add_filter('lostpassword_redirect', function() {
	return site_url(LOGIN_SLUG."?checkemail=confirm" );
});

// Redirection URL after submitting registration form
add_filter('registration_redirect', function() {
	return site_url(LOGIN_SLUG."?checkemail=registered" );
});

/**
 * [hideLogin Handles new RewriteRules as well as custom ones]
 */
function hideLogin()
{
    global $wp_rewrite;

    // Backup original .htaccess file
	if (!file_exists(ABSPATH."/.htaccess.backup")) {
		copy(ABSPATH."/.htaccess", ABSPATH."/.htaccess.backup");
	}

	add_rewrite_rule( get_option("hide_login_slug", "login").'/?$', 'wp-login.php', 'top' );

	if(get_option("hide_admin_slug") != "")
		add_rewrite_rule(get_option("hide_admin_slug").'/(.*)', "wp-admin/$1?%{QUERY_STRING}", 'top');

	if(get_option('users_can_register'))
		add_rewrite_rule( get_option("hide_register_slug", "register").'/?$', 'wp-login.php?action=register', 'top' );

	add_rewrite_rule( get_option("hide_forgot_slug", "forgot").'/?$', 'wp-login.php?action=lostpassword', 'top' );

	$str = '';
	if(get_option('hide_admin_slug') != '')
	{
		$forerules = array("RewriteRule" => '^'.get_option('hide_admin_slug').'$ '.get_option('hide_admin_slug').'/ [R,L]');

		foreach ($forerules as $cmd => $rules) {
			if(is_array($rules)) {
				foreach ($rules as $rule) {
					$str .= "$cmd $rule\r\n";
				}
			}
			else
				$str .= "$cmd $rules\r\n";
		}
	}

	add_filter('mod_rewrite_rules', function ($rules) use ($str){
		$pattern = "@(^\QRewriteRule ^index\.php$ - [L]\E\s)@m";
		$rules = preg_replace($pattern, "$1".$str, $rules);
		update_option("hide_rules", $rules);
		return $rules;
	});

	$wp_rewrite->flush_rules(true);
}
/**
 * [showMsg Displays message on form submit]
 * @param  [integer] $id  [Text ID]
 * @param  [string] $type [Type of message]
 * @return [string] $display      [HTML output]
 */
function showMsg($id, $type)
{
	$messages = array('error' => array(0 => __('Don\'t leave some fields empty.', 'hidelogin')),
		'updated' => array(0 => __('Settings Updated','hidelogin')));
	$display = '<div id="message" class="'.$type.' fade"><p><strong>' . $messages[$type][$id] . '</strong></p></div>';
	return $display;
}
/**
 * [hideSettings Shows settings page]
 */
function hideSettings()
{
	if(isset($_GET['type']) && isset($_GET['id']))
		echo showMsg($_GET['id'], $_GET['type']);
	require_once(dirname(__file__).'/admin.php');
}
/**
 * [weHaveError Checks if there is any error out there :|]
 * @param  [bool] $var [Boolean value]
 * @return [bool]
 */
function weHaveError($var)
{
	return $var;
}
/**
 * [_deactivate Will rollback all affected changes to their defaults]
 */
function _deactivate()
{
    remove_action( 'generate_rewrite_rules', 'hideLogin' );
    delete_option("hide_login_slug");
	delete_option("hide_admin_slug");
	delete_option("hide_logout_slug");
	delete_option("hide_register_slug");
	delete_option("hide_forgot_slug");
	delete_option("hide_wplogin");
	delete_option("hide_wpadmin");
	delete_option("hide_rules");
    $GLOBALS['wp_rewrite']->flush_rules(true);
}
register_deactivation_hook(__FILE__ , '_deactivate');
/**
 * [redirectOnDeactivation Redirects after deactivation to prevent intrruption of old admin slug]
 * @return [void]
 */
function redirectOnDeactivation($plugin) {
	if($plugin == 'hide-login/hide-login.php')
	{
		$current = get_option( 'active_plugins', array() );
		$key = array_search( $plugin, $current );
		unset( $current[ $key ] );
		update_option('active_plugins', $current);
		exit(wp_safe_redirect('/wp-admin/plugins.php?deactivate=true&plugin_status=all&paged=1&s='));
	}
}
add_action('deactivated_plugin', 'redirectOnDeactivation', 10, 1);
?>
