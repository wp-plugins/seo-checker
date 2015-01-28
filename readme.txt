=== Plugin Name ===
Contributors: ecommnet, scottsalisbury
Tags: seo, search engine optimisation, search engine optimization, robots, indexing, google, searching
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Displays a clear warning when search engines are being blocked and sends regular email reminders to the site admin.

== Description ==

When developing WordPress sites, it is often common practice to disable search engines from indexing your site. This is important to make sure that search engines aren't indexing any sample data or content that is not finished yet.

This setting can sometimes be unintentionally enabled on your live website, or your web developer might forget to turn it off, causing issues for search engine optimisation.

= Configurable options =
* Disable automatic email reminders
* Configure automatic email reminder intervals, choices are twice daily, daily, weekly and monthly

== Installation ==

This section describes how to install the plugin and get it working.

1. Unzip package contents
1. Upload the "`seo-checker`" directory to the "`/wp-content/plugins/`" directory
1. Activate the plugin through the "`Plugins`" menu in WordPress
1. Configure the plugin by going to "`Settings > SEO Checker`". Note: the plugin will work out of the box with default settings
1. If your WordPress instance isn't set up properly to send emails, reminder emails will not be sent
1. Test and enjoy :)

== Frequently Asked Questions ==

= The plugin doesn't appear to be sending emails, what's wrong? =
Sending emails is done using WordPress's built in mail function. If emails aren't sending and your email address has been configured, it is likely that your entire WordPress site is unable to send emails. This could be due to an incorrectly set up mail server, or emails getting lost in your spam folders.

If you're still having trouble then you will have to get in contact with your server host.

== Changelog ==

= 0.1 =
* First version of the plugin