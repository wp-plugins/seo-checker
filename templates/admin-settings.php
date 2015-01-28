<?php defined('ABSPATH') or die('Plugin file cannot be accessed directly.'); ?>

<div class="wrap seo-checker-container">
	<h2>SEO Checker</h2>
	<p class="seo-checker">When developing WordPress sites, it is often common practice to disable search engines from indexing your site. This is important to make sure that search engines aren't indexing any sample data or content that is not finished yet.</p>
	<p class="seo-checker">This setting can sometimes be unintentionally enabled on your live website, or your web developer might forget to turn it off, causing issues for search engine optimisation.</p>

	<form method="post" action="options.php">
		<?php settings_fields('seo_checker_option_group'); ?>
		<?php do_settings_sections('seo-checker-settings'); ?>
		<?php submit_button(); ?>
	</form>
</div>