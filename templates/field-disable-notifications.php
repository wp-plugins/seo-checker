<?php defined('ABSPATH') or die('Plugin file cannot be accessed directly.'); ?>

<label for="disable_notifications"><input name="seo_checker_options[disable_notifications]" type="checkbox" id="disable_notifications" value="1" <?php echo checked(1, SEO_Checker()->get_option('disable_notifications'), false); ?>>Disable E-mail Notifications</label>
<p class="description">Stop sending email reminders to the <a href="<?php echo get_admin_url() ?>options-general.php">site admin</a>.</p>