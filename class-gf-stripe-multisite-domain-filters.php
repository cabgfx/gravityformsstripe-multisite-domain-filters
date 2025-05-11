<?php
/**
 * Plugin Name: Gravity Forms Stripe Multisite Domain Filters
 * Plugin URI: https://github.com/yourusername/gravityformsstripe-multisite-domain-filters
 * Description: Fixes domain issues with Gravity Forms Stripe addon in multisite setups
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: gravityformsstripe-multisite-domain-filters
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * Network: true
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('GF_STRIPE_MULTISITE_DOMAIN_FILTERS_VERSION', '1.0.0');
define('GF_STRIPE_MULTISITE_DOMAIN_FILTERS_FILE', __FILE__);
define('GF_STRIPE_MULTISITE_DOMAIN_FILTERS_PATH', plugin_dir_path(__FILE__));
define('GF_STRIPE_MULTISITE_DOMAIN_FILTERS_URL', plugin_dir_url(__FILE__));

/**
 * Main plugin class
 */
class GF_Stripe_Multisite_Domain_Filters {
    /**
     * Instance of this class
     */
    private static $instance = null;

    /**
     * Get instance of this class
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Hook into plugins_loaded to ensure Gravity Forms is loaded
        add_action('plugins_loaded', array($this, 'init'));
    }

    /**
     * Initialize the plugin
     */
    public function init() {
        // Check if Gravity Forms is active
        if (!$this->is_gravityforms_active()) {
            add_action('admin_notices', array($this, 'gravityforms_missing_notice'));
            return;
        }

        // Check if Gravity Forms Stripe is active
        if (!$this->is_gravityforms_stripe_active()) {
            add_action('admin_notices', array($this, 'gravityforms_stripe_missing_notice'));
            return;
        }

        // Initialize the URL fixes
        $this->init_url_fixes();
    }

    /**
     * Check if Gravity Forms is active
     */
    private function is_gravityforms_active() {
        return class_exists('GFForms');
    }

    /**
     * Check if Gravity Forms Stripe is active
     */
    private function is_gravityforms_stripe_active() {
        return class_exists('GFStripe');
    }

    /**
     * Display notice if Gravity Forms is not active
     */
    public function gravityforms_missing_notice() {
        $message = sprintf(
            __('Gravity Forms Stripe Multisite Domain Filters requires Gravity Forms to be installed and activated. Please %spurchase and install Gravity Forms%s to continue.', 'gravityformsstripe-multisite-domain-filters'),
            '<a href="https://www.gravityforms.com/" target="_blank">',
            '</a>'
        );
        $this->display_admin_notice($message);
    }

    /**
     * Display notice if Gravity Forms Stripe is not active
     */
    public function gravityforms_stripe_missing_notice() {
        $message = __('Gravity Forms Stripe Multisite Domain Filters requires the Gravity Forms Stripe Add-On to be installed and activated.', 'gravityformsstripe-multisite-domain-filters');
        $this->display_admin_notice($message);
    }

    /**
     * Display admin notice
     */
    private function display_admin_notice($message, $type = 'error') {
        printf(
            '<div class="notice notice-%s is-dismissible"><p>%s</p></div>',
            esc_attr($type),
            wp_kses_post($message)
        );
    }

    /**
     * Initialize URL fixes
     */
    private function init_url_fixes() {
        // Add filters for success and cancel URLs
        add_filter('gform_stripe_success_url', array($this, 'fix_success_url'), 10, 3);
        add_filter('gform_stripe_cancel_url', array($this, 'fix_cancel_url'), 10, 2);
    }

    /**
     * Debug logging function
     */
    private function log($message) {
        if (defined('WP_DEBUG') && WP_DEBUG === true) {
            error_log('[GF Stripe Multisite Fix] ' . $message);
        }
    }

    /**
     * Get the correct site URL for the current context
     */
    private function get_site_url() {
        // Get the current site's URL
        $site_url = get_site_url();

        // Log the URL for debugging
        $this->log(sprintf(
            'Current site URL: %s, Server Name: %s, Request URI: %s',
            $site_url,
            $_SERVER['SERVER_NAME'],
            $_SERVER['REQUEST_URI']
        ));

        return $site_url;
    }

    /**
     * Fix the success URL domain in multisite setups
     */
    public function fix_success_url($url, $form_id, $query) {
        // Log the original URL
        $this->log(sprintf(
            'Original success URL: %s (Form ID: %d)',
            $url,
            $form_id
        ));

        // Get the correct site URL
        $site_url = $this->get_site_url();

        // Parse the original URL to preserve the path and query
        $parsed_url = parse_url($url);
        $path = isset($parsed_url['path']) ? $parsed_url['path'] : '/';
        $query = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';

        // Construct the new URL
        $new_url = $site_url . $path . $query;

        // Log the new URL
        $this->log(sprintf(
            'Modified success URL: %s',
            $new_url
        ));

        return $new_url;
    }

    /**
     * Fix the cancel URL domain in multisite setups
     */
    public function fix_cancel_url($url, $form_id) {
        // Log the original URL
        $this->log(sprintf(
            'Original cancel URL: %s (Form ID: %d)',
            $url,
            $form_id
        ));

        // Get the correct site URL
        $site_url = $this->get_site_url();

        // Parse the original URL to preserve the path and query
        $parsed_url = parse_url($url);
        $path = isset($parsed_url['path']) ? $parsed_url['path'] : '/';
        $query = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';

        // Construct the new URL
        $new_url = $site_url . $path . $query;

        // Log the new URL
        $this->log(sprintf(
            'Modified cancel URL: %s',
            $new_url
        ));

        return $new_url;
    }
}

// Initialize the plugin
function gf_stripe_multisite_domain_filters() {
    return GF_Stripe_Multisite_Domain_Filters::get_instance();
}

// Start the plugin
gf_stripe_multisite_domain_filters();
