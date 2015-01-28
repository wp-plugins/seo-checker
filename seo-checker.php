<?php
/*
Plugin Name: SEO Checker
Plugin URI: https://www.ecommnet.uk/seo-checker/
Description: Simple plugin to detect if search engines are blocked from indexing your site, displays a warning on the admin panel and sends regular email notifications.
Version: 0.4
Author: Scott Salisbury
Author URI: https://www.ecommnet.uk/
Text Domain: seo-checker
*/

defined('ABSPATH') or die('Plugin file cannot be accessed directly.');

if (!class_exists('SEO_Checker')) {

	class SEO_Checker {

		/**
		 * Plugin version number
		 * @var string
		 */
		public $version = '0.1';

		/**
		 * Single instance of the class
		 */
		protected static $_instance = null;

		/**
		 * List of plugin settings
		 * @var array
		 */
		private $options = array();

		/**
		 * List of default plugin settings
		 * @var array
		 */
		private $defaults = array(
			'disable_notifications' => 0,
			'notifications_frequency' => 'seo_checker_weekly'
		);

		/**
		 * Instance of the class
		 */
		public static function instance() {
			if (is_null(self::$_instance)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Initiate the plugin by setting up actions and filters
		 */
		public function __construct() {

			// Actions
			add_action('admin_menu', array($this, 'plugin_page_create'));
			add_action('admin_init', array($this, 'plugin_page_form'));
			add_action('admin_notices', array($this, 'admin_notice'));

			// Filters
			add_filter('cron_schedules', array($this, 'cron_schedules'));

			// Load plugin options if available
			$this->options = get_option('seo_checker_options');

			// Look after the cron jobs
			$this->cron_manager();

			// Any additional included files
			$this->includes();

			// Deactivation scripts
			register_deactivation_hook(__FILE__, array($this, 'deactivate_plugin'));
		}

		/**
		 * Additional includes
		 */
		public function includes() {
			include_once('includes/notifications.php');
		}

		/**
		 * Get option method for returning plugin options or returning the default value if not set
		 * @param string $option Name of option to look up
		 * @return mixed
		 */
		public function get_option($option) {
			if(isset($this->options[$option])) {
				return $this->options[$option];
			}

			return $this->defaults[$option];
		}

		/**
		 * Display the SEO warning on admin panel when search engine indexes are blocked
		 */
		public function admin_notice() {
			if(get_option('blog_public') != 1) {
				$this->get_template_part('admin', 'notice');
			}
		}

		/**
		 * Set up plugin options page and conditionally load required scripts
		 */
		public function plugin_page_create() {
			$plugin_page = add_options_page('SEO Checker', 'SEO Checker', 'manage_options', 'seo-checker-settings', array($this, 'plugin_page_content'));

			// Load plugin specific actions
			add_action('load-' . $plugin_page, array($this, 'load_plugin_actions'));

			// Load other styles
			add_action('admin_enqueue_scripts', array($this, 'enqueue_reading_styles'));
		}

		/**
		 * Set up plugin settings HTML and other containers
		 */
		public function plugin_page_content() {
			$this->get_template_part('admin', 'settings');
		}

		/**
		 * Set up plugin settings options page with settings and form fields
		 */
		public function plugin_page_form() {

			// Register plugin settings
			register_setting('seo_checker_option_group', 'seo_checker_options');

			// Create section group
			add_settings_section('seo_checker_section_1', 'Notifications', array($this, 'display_section_info'), 'seo-checker-settings');

			// Plugin setting fields
			add_settings_field('disable_notifications', 'E-mail Notifications', array( $this, 'disable_notifications_callback'), 'seo-checker-settings', 'seo_checker_section_1');
			add_settings_field('notifications_frequency', 'Notification Frequency', array( $this, 'notifications_frequency_callback'), 'seo-checker-settings', 'seo_checker_section_1');
		}

		/**
		 * Display plugin description
		 */
		public function display_section_info() {
			$this->get_template_part('admin', 'info');
		}

		/**
		 * Callback for displaying disable notifications checkbox
		 */
		public function disable_notifications_callback() {
			$this->get_template_part('field', 'disable-notifications');
		}

		/**
		 * Callback for displaying notifications frequency select box
		 */
		public function notifications_frequency_callback() {
			$this->get_template_part('field', 'frequency');
		}

		public function plugin_page_footer() {
			$this->get_template_part('admin', 'footer');
		}

		/**
		 * Load specific actions
		 */
		public function load_plugin_actions() {
			add_action('in_admin_footer', array($this, 'plugin_page_footer'));
			add_action('admin_enqueue_scripts', array($this, 'enqueue_plugin_styles'));
		}

		/**
		 * Enqueue plugin styles
		 * @param string $hook Name of current admin page
		 */
		public function enqueue_plugin_styles($hook) {
			wp_enqueue_style('seo-checker-styles', $this->plugin_url() . '/css/seo-checker.css', array(), $this->version);
		}

		/**
		 * Enqueue plugin styles for reading settings page to add red box around SEO option
		 * @param string $hook Name of current admin page
		 */
		public function enqueue_reading_styles($hook) {
			if ($hook == 'options-reading.php' && get_option('blog_public') != 1) {
				wp_enqueue_style('seo-checker-reading-styles', $this->plugin_url() . '/css/seo-checker-reading.css', array(), $this->version);
			}
		}

		/**
		 * Create custom cron schedules in addition to built in ones
		 * @param array $schedules Existing schedules
		 * @return mixed
		 */
		public function cron_schedules($schedules) {

			// Set up custom cron schedules
			$schedules['seo_checker_weekly'] = array(
				'interval' => 7 * 24 * 60 * 60,
				'display' => 'Weekly'
			);

			$schedules['seo_checker_monthly'] = array(
				'interval' => (365 * 24 * 60 * 60) / 12,
				'display' => 'Monthly'
			);

			return $schedules;
		}

		/**
		 * Schedule a new notification cron job based on settings, or remove it if it is no longer needed
		 */
		public function cron_manager() {

			// If blog is not public and notifications are not disabled, schedule the cron job with specified frequency
			if(get_option('blog_public') != 1 && $this->get_option('disable_notifications') != 1) {

				$current_schedule = wp_get_schedule('seo_checker_email_notification');

				// If the frequency has changed
				if($this->get_option('notifications_frequency') != $current_schedule) {

					// Remove existing cron before rescheduling it
					$this->cron_remove();

					// Schedule new cron based on settings
					wp_schedule_event(time(), $this->get_option('notifications_frequency'), 'seo_checker_email_notification');
				}
			}

			else {
				// If we don't need notifications, remove the cron job
				$this->cron_remove();
			}
		}

		/**
		 * Remove the cron job
		 */
		public function cron_remove() {
			wp_clear_scheduled_hook('seo_checker_email_notification');
		}

		/**
		 * On plugin deactivation, remove cron jobs and any saved settings
		 */
		public function deactivate_plugin() {

			// Remove saved options
			delete_option('seo_checker_options');

			// Remove any scheduled cron jobs
			$this->cron_remove();
		}

		/**
		 * Custom template loader to look in plugins directory first to make templates overridable
		 * @param string $slug Slug of page
		 * @param string $name Name of page
		 */
		public function get_template_part($slug, $name = '') {
			$template = '';

			// If a name is set, look in theme or child theme
			if ($name) {
				$template = locate_template(array("{$slug}-{$name}.php", $this->template_path() . "{$slug}-{$name}.php"));
			}

			// If not found, load our default template
			if (!$template && $name && file_exists($this->plugin_path() . "/templates/{$slug}-{$name}.php")) {
				$template = $this->plugin_path() . "/templates/{$slug}-{$name}.php";
			}

			// If not found, look for just the slug in theme or child theme
			if (!$template) {
				$template = locate_template(array("{$slug}.php", $this->template_path() . "{$slug}.php"));
			}

			// Load it
			if ($template) {
				load_template($template, false);
			}
		}

		/**
		 * Return the plugin URL
		 * @return string
		 */
		public function plugin_url() {
			return untrailingslashit(plugins_url('/', __FILE__ ));
		}

		/**
		 * Return the plugin directory path
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit(plugin_dir_path(__FILE__));
		}

		/**
		 * Return the plugin template path
		 * @return string
		 */
		public function template_path() {
			return apply_filters('seo_checker_template_path', 'seo-checker/');
		}

	}

}

/**
 * Returns the main instance of SEO Checker
 */
function SEO_Checker() {
	return SEO_Checker::instance();
}

SEO_Checker();