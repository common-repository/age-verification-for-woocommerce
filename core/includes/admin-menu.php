<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('AWEOSVerificationAdminMenu')) {
    class AWEOSVerificationAdminMenu {
        public function add_settings() {
            require_once 'wc-settings-class.php';
            return new AWAR_WC_Settings();
        }

        public function __construct() {
            if (is_admin()) {
                add_filter('woocommerce_get_settings_pages', [$this, 'add_settings'], 15);
            }

            add_action('admin_print_scripts', [$this, 'admin_print_scripts']);
        }

        public function admin_print_scripts() {
            if (is_admin()) {
                // scripts
                wp_enqueue_media();
                wp_enqueue_script('aweos-lib-image-picker', plugins_url('assets/lib/aweos-image-picker.js', dirname(dirname(__FILE__))), ['jquery'], '1.1', true);
                wp_enqueue_script('aweos-lib-grouped-buttons', plugins_url('assets/lib/aweos-grouped-buttons.js', dirname(dirname(__FILE__))), ['jquery'], '1.1', true);
                wp_enqueue_script('awar-js-admin-menu', plugins_url('assets/admin-menu.js', dirname(dirname(__FILE__))), ['wp-color-picker', 'jquery'], '1.1', true);
                wp_localize_script('awar-js-admin-menu', 'awarPath', [
                    'pluginURI' => plugin_dir_url(dirname(dirname(__FILE__)))
                ]);

                // styles
                wp_enqueue_style('wp-color-picker');
                wp_enqueue_style('aweos-lib-image-picker', plugins_url('assets/lib/aweos-image-picker.css', dirname(dirname(__FILE__))), [], '1.1');
                wp_enqueue_style('aweos-lib-grouped-buttons', plugins_url('assets/lib/aweos-grouped-buttons.css', dirname(dirname(__FILE__))), [], '1.1');
            }
        }
    }

    new AWEOSVerificationAdminMenu();
}
