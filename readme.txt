=== Plugin Name ===
Contributors: ecommnet, scottsalisbury
Tags: seo, search engine optimisation, search engine optimization, robots, indexing, google, searching
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: 0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Displays a clear warning when search engines are being blocked and sends regular email reminders to the site admin.

== Description ==

Do you build WordPress websites? Do you sometimes turn on the option to "Discourage search engines from indexing this site" and forget to turn it off again once the site goes live? Then this is the plugin for you.

When developing WordPress sites, it is often common practice to disable search engines from indexing your site. This can be important to make sure search engines aren't indexing any sample data or content that is not finished yet.

Blocking search engines can sometimes be unintentionally enabled on your live website, or your web developer might forget to turn it off, causing issues for search engine optimisation.

SEO Checker is a very simple plugin that you can install on all of your WordPress projects and will clearly remind you when search engines are blocked. Never forget about it ever again!

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

= The plugin appears to be sending emails at the wrong times =

This plugin uses WordPress's built in WP-Cron mechanisms to send the regular email reminders, which only works when you have traffic going to your website. For example, if you set email reminders to weekly, but nobody visits your site in three weeks, you will get the next reminder email at the time the next visitor accesses your site. 

= The plugin doesn't appear to be sending emails at all, what's wrong? =

Sending emails is done using WordPress's built in mail function. If emails aren't sending and your email address has been configured, it is likely that your entire WordPress site is unable to send emails. This could be due to an incorrectly set up mail server, or emails getting lost in your spam folders.

If you're still having trouble then you will have to get in contact with your server host.

== Screenshots ==

1. Screen shot of the dashboard warning message you see when search engines are blocked.
2. Screen shot of the additional styling added to the Search Engine Visibility option to draw attention to it.
3. Screen shot of the plugin options panel and notification settings.
4. Screen shot of an example email notification from the plugin.

== Changelog ==

= 0.1 =
* First version of the plugin

= 0.2 =
* Updated readme

= 0.3 =
* Updated readme

= 0.4 =
* Updated readme