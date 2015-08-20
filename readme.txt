=== Hide Login+ ===
Contributors: Mohammad Hossein Aghanabi
Author URI: https://koder.io/
Tags: login, logout, htaccess, custom, url, wp-admin, admin, change, hide, stealth, security, hide, register, sign in, sign up
Requires at least: 2.3
Tested up to: 4.3
Stable tag: 3.5.1

Have a secure login and admin page. Allows you to create custom URLs for user's Log in, Log out, Sign up and Admin page.

== Description ==

= A must have plugin for wordpress blogs =

By using Hide Login+ you can **simply** change most important URLs that are being accessed every day, keeping them safe and secret.

**For those upgrading from previous version: please check installation steps**

New features:

* **No need to modify `wp-config.php` any more** (see installation steps)
* **`.htaccess` backup file is created before changes take effect (named `.htaccess.backup`)**
* **Recoded to be latest Wordpress version compatible**

Benefits:

* Have secured and hidden login page
* Customized URLs for the most important parts of your Wordpress installation
* Control access over `wp-login.php` and `wp-admin` pages
* Easy back-to-defaults ability without frustration

Features:

* Define custom URL slug for login, logout, registration, lost password & admin pages
* Able to prevent access to `wp-login.php` and `wp-admin` directly
* See your `.htaccess` content after changes have been successfully updated.
* Revert to default configurations on plugin deactivation (or easily via a second method)


 *This won't secure your website perfectly, but if someone does manage to crack your password, it can make it difficult for them to find where to actually login.  This also prevents any bots that are used for malicious intents from accessing your `wp-login.php` file and attempting to break in.*

== Installation ==

1. Upload the `hide-login` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Set the options in the Hide Login+ settings page
4. If you are upgrading from previous versions please remove below lines from `wp-config.php` if there are any:

`define('WP_ADMIN_DIR', 'YOUR_ADMIN_SLUG');
define('ADMIN_COOKIE_PATH', SITECOOKIEPATH . WP_ADMIN_DIR);`

== Changelog ==
= 3.5.1 =
	* Added delete_option on plugin activation to avoid later confliction
= 3.5 =
	* No more need to modify `wp-config.php` for changing admin URL slug
	* Reduced and optimized `.htaccess` rules
	* `.htaccess` backup file is created in the same directory
	* All plugin options are cleared completely on deactivation
	* Many more bug & minor fixes and compatibilty issues done
= 3.1 =
	* Changed some default options at activation to avoid 500 Server internal error
	* Restrictions on using default slugs like `wp-admin` for admin slug that made confliction
	* Optimized code readablity and stability
	* Solved fatal error caused by `check_admin_referer()`
	* Tested over wordpress 3.6
= 3.0 =
	* Completely rewrote.
	* All rewrite rules will apply with wordpress buil-in functions
	* Remove plugin rewrite rules automatically on deactivation to wordpres default rules
	* Works with all permalink structures
	* Droped some useless options and codes and improved functionality
	* Now Setting page menu is at root
	* Tested Over the latest Wordpress version(3.5.1)
= 2.1 =
	* Fix an issue with hide mode capability
= 2.0 =
	* Fix .htaccess query commands
	* Automatic removing and adding htaccess output to .htaccess file
	* Strong security key function
	* Added compatibility fix with WordPress installations in a directory like www.blog.com/wordpress/
	* Added ability to disable plugin from its setting page
	* Added ability to attempt to change .htaccess permissions to make writeable
	* Added wp-admin slug option (can't login with it yet though)
	* htaccess Output rules will always show even if htaccess is not writeable
	* added ability to create custom htaccess rules
	* Added Register slug option so you can still allow registrations with the hide-login. (If registration is not allowed, this option will not be available.)
	* Security Key now seperate for each slug so that those registering cannot reuse the key for use on login or logout
	* Added better rewrite rules for a hidden login system.
	* Removed wp-login.php refresh redirect in favor of using rewrite rules for prevention of direct access to the file.

== Frequently Asked Questions ==

= Is something gone horribly wrong and your site went down? =

* There are 2 methods and you **only** need to go with one:

1- Deactivate plugin. Done!

2- There is a backup file of `.htaccess` in the root directory of your Wordpress installation named `.htaccess.backup`. You only need to remove your `.htaccess` file and rename backup file from `.htaccess.backup` to `.htaccess`. Also rename `hide-login` folder within `plugins` directory to something else. Done!

= Are you interested to collaborate with me on Hide Login+? =

* Find me on `https://github.com/churiart/hide-login`

== Screenshots ==

1. Settings