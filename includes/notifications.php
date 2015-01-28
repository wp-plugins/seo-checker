<?php

defined('ABSPATH') or die('Plugin file cannot be accessed directly.');

/**
 * Send the notification, called by WP-Cron.
 */
function send_notification() {
	$admin_email = get_option('admin_email');

	if (!empty($admin_email) && strpos($admin_email,'@') !== false) {
		wp_mail($admin_email, "SEO Checker: Blocked Search Engines Email Reminder", "Search engine traffic is currently being blocked from: " . get_site_url() . "\n\nClick the link below to change this setting:\n" . get_admin_url() . "options-reading.php\n\nClick the link below to find out more about this message:\n" . get_admin_url() . "options-general.php?page=seo-checker-settings");
	}
}

add_action('seo_checker_email_notification', 'send_notification');