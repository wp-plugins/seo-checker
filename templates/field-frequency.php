<?php defined('ABSPATH') or die('Plugin file cannot be accessed directly.'); ?>

<select name="seo_checker_options[notifications_frequency]" id="notifications_frequency">
	<option <?php echo selected(SEO_Checker()->get_option('notifications_frequency'), 'twicedaily', false); ?> value="twicedaily">Twice Daily</option>
	<option <?php echo selected(SEO_Checker()->get_option('notifications_frequency'), 'daily', false); ?> value="daily">Daily</option>
	<option <?php echo selected(SEO_Checker()->get_option('notifications_frequency'), 'seo_checker_weekly', false); ?> value="seo_checker_weekly">Weekly</option>
	<option <?php echo selected(SEO_Checker()->get_option('notifications_frequency'), 'seo_checker_monthly', false); ?> value="seo_checker_monthly">Monthly</option>
</select>
<p class="description">How often to send reminders</p>