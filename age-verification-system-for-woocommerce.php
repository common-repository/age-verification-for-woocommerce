<?php
/*
Plugin Name: Age Verification for WooCommerce
Description: Restrict WooCommerce products for a certain age group using a beautiful and small popup window, after completing the setup and selecting your categories, visitors need to type in their date of birth in a compact popup window, if they pass, we store a browser-cookie to remember it next time. You will be able to control the product access for certain age groups precisely. Control everything with our highly customizable, easy and advanced admin backend.
Version: 1.4.3
Author: AWEOS GmbH
Author URI: https://aweos.de
Text Domain: awar-domain
WC tested up to: 8.1.1
*/

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'core/includes/lib/vendor/autoload.php';
require_once plugin_dir_path(__FILE__) . 'core/includes/admin-menu.php';
require_once plugin_dir_path(__FILE__) . 'core/includes/twig-loader.php';
require_once plugin_dir_path(__FILE__) . 'core/includes/enqueue.php';

if (!class_exists('AWEOSVerificationPlugin')) {
    class AWEOSVerificationPlugin
    {
        private $documentation_url;
        private $last_supported_wordpress_version;
        private $plugin_version;
        private $plugin_slug;
        private $required_plugins;

        public function __construct($config)
        {
            $this->documentation_url = $config['documentation_url'];
            $this->last_supported_wordpress_version = $config['last_supported_wordpress_version'];
            $this->plugin_version = $config['plugin_version'];
            $this->plugin_slug = $config['plugin_slug'];
            $this->required_plugins = $config['required_plugins'];

            register_activation_hook(__FILE__, [$this, 'on_activation']);

            add_action('admin_menu', [$this, 'alter_side_menu']);
            add_action('plugins_loaded', [$this, 'load_text_domain']);
            add_filter('plugin_row_meta', [$this, 'alter_plugin_row_meta'], 10, 2);
            add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'alter_plugin_action_link']);
        }

        public function on_activation()
        {
            if (version_compare(get_bloginfo('version'), $this->last_supported_wordpress_version, '<')) {
                wp_die(__('Please update WordPress to use this plugin', 'awar-domain'));
            }

            if (!is_plugin_active('woocommerce/woocommerce.php')) {
                $back = __('Back to the plugins dashboard', 'awar-domain');
                $url = admin_url('plugins.php');
                $link = "<br><a href='$url'>&laquo; $back</a>";
                wp_die(__('Sorry, but Age verification requires WooCommerce to be installed and active.', 'awar-domain') . $link);
            }
        }

        public function alter_plugin_row_meta($links, $file)
        {
            if (plugin_basename(__FILE__) === $file) {
                $help_text = esc_html__('Help', 'awar-domain');

                $row_meta = [
                    'help' => "<a href='{$this->documentation_url}' target='_blank' style='color: green;'>$help_text</a>",
                ];

                return array_merge($links, $row_meta);
            }
            return $links;
        }

        public function alter_plugin_action_link($actions)
        {
            $settings_url = admin_url('admin.php?page=wc-settings&tab=awar');
            $settings_text = esc_html__('Settings', 'awar-domain');

            $actions['settings'] = "<a href='$settings_url'>$settings_text</a>";

            return $actions;
        }

        public function alter_side_menu()
        {
            add_menu_page('Age Verification', 'Age Verification', 'activate_plugins', 'awar-menu-page', function () {
                $url = admin_url('admin.php?page=wc-settings&tab=awar_options');
                header("Location:$url");
            }, 'dashicons-admin-network');
        }

        public function load_text_domain()
        {
            load_plugin_textdomain('awar-domain', false, basename(dirname(__FILE__)) . '/languages/');
        }
    }
    new AWEOSVerificationPlugin([
        'documentation_url' => esc_url('https://plugins.aweos.de/age-verification-system-for-woocommerce/'),
        'last_supported_wordpress_version' => '4.9',
        'plugin_version' => '1.3.5',
        'plugin_slug' => 'aweos-verification',
        'plugin_file' => __FILE__,
        'required_plugins' => [
            'woocommerce/woocommerce.php' => __(
                'Sorry, but Age verification requires WooCommerce to be installed and active.',
                'awar-domain'
            ),
        ]
    ]);
}
