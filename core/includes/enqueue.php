<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('AWEOSVerificationEnqueue')) {
    class AWEOSVerificationEnqueue {
        public function __construct() {
            // add_shortcode('aweos_verification_popup', [$this, 'popup_scripts']);
            add_action('get_footer', [$this, 'enqueue_for_category']);
            add_action('admin_enqueue_scripts', [$this, 'admin_enqueue']);
            add_action('wp_head', [$this, 'enqueue_styles']);
            add_action('wp_head', [$this, 'print_overriding_inline_style_for_submit_button']);
        }

        public function popup_scripts() {
            // load scripts just for the first time
            // the popup will appear just once after success

            if (isset($_COOKIE['awarStatus']) && $_COOKIE['awarStatus'] === 'accepted') return;

            wp_enqueue_script('awar-selectize', plugin_dir_url(dirname(__FILE__, 2)) . 'assets/lib/selectize.js', [], '1.1', true );
            wp_enqueue_script('awar-js-cookie', plugin_dir_url(dirname(__FILE__, 2)) . 'assets/lib/js-cookie.js', [], '1.1', true );
            wp_enqueue_script('awar-templates', plugin_dir_url(dirname(__FILE__, 2)) . 'assets/templates.js', [], '1.1', true );

            $twig_loader = new AWEOSVerificationTwigLoader();
            add_action('wp_footer', [$twig_loader, 'enqueue_templates']);
        }

        public function enqueue_for_category() {
            // load scripts if the category is listed as vulnerable for underage users

            global $post;
            if (!$post) return;
            $id = $post->ID;

            // -------------------------
            // -- Restrict categories --
            // -------------------------

            if (is_shop()) return; // is in the loop
            $all_product_cats = get_the_terms( $post->ID, 'product_cat' );

            if ($all_product_cats) {
                foreach ($all_product_cats as $product_cat) {
                    $current_slug = $product_cat->slug;
                    $restrict_for = WC_Admin_Settings::get_option('awar-restrict-for-categories', '');

                    if (!$restrict_for) break;

                    foreach ($restrict_for as $restrict_slug) {
                        if ($restrict_slug === $current_slug) {
                            $this->popup_scripts();
                            break;
                        }
                    }
                }
            }

            // --------------------------------------
            // -- Restrict products, posts & pages --
            // --------------------------------------

            $restricted_product_ids = WC_Admin_Settings::get_option('awar-restrict-for-products', '');
            $restricted_post_ids = WC_Admin_Settings::get_option('awar-restrict-for-posts', '');
            $restricted_page_ids = WC_Admin_Settings::get_option('awar-restrict-for-pages', '');

            if (in_array($id, $restricted_product_ids)) {
                $this->popup_scripts();
            }

            if (in_array($id, $restricted_post_ids)) {
                $this->popup_scripts();
            }

            if (in_array($id, $restricted_page_ids)) {
                $this->popup_scripts();
            }
        }

        public function admin_enqueue() {
            wp_enqueue_style('awar-admin-menu', plugin_dir_url(dirname(__FILE__, 2)) . 'assets/admin-menu.css', [], '1.1');
        }

        public function enqueue_styles() {
            // load styles, styles will always be enqueued

            wp_enqueue_style('awar-selectize', plugin_dir_url(dirname(__FILE__, 2)) . 'assets/lib/selectize.css', [], '1.1');
            wp_enqueue_style('awar-templates', plugin_dir_url(dirname(__FILE__, 2)) . 'assets/templates.css', [], '1.1');

            $min_age = WC_Admin_Settings::get_option('awar-minimum-age', '21');
            echo "<meta name='awarMinAge' content='$min_age' />";
        }

        public function print_overriding_inline_style_for_submit_button() {
            $picker_color_hex = WC_Admin_Settings::get_option('awar-color-picker-submit', '#279f0f');
            $picker_color_hex_shadow = WC_Admin_Settings::get_option('awar-color-picker-submit-shadow', '#106603');
            $picker_color_text = WC_Admin_Settings::get_option('awar-color-picker-submit-text-color', '#ffffff');
            $headline_font_size = WC_Admin_Settings::get_option('awar-headline-font-size', 17) . 'px';
            $picker_color_hex_hover = WC_Admin_Settings::get_option('awar-color-picker-submit-hover', '#1f9108');
            $frame_background_color = WC_Admin_Settings::get_option('awar-frame-background-color', '#e7e7ef');
            $legal_links_color = WC_Admin_Settings::get_option('awar-legal-links-color', '#666');
            $headline_color = WC_Admin_Settings::get_option('awar-headline-color', 'black');
            $text_color = WC_Admin_Settings::get_option('awar-text-color', '#777');
            $information_paragraph_color = WC_Admin_Settings::get_option('awar-information-paragraph-color', 'black');

            switch ($picker_color_text) {
                case 0:
                $picker_color_text = 'white';
                break;

                case 1:
                $picker_color_text = 'black';
                break;

                case 2:
                $picker_color_text = 'grey';
                break;
            }

            $submit_button_css = "
            .awar-content .awar-popup .awar-picker-submit {
                background-color: $picker_color_hex;
                box-shadow: 0px 5px 0px 0px $picker_color_hex_shadow;
                color: $picker_color_text;
            }
            .submit-hover-yes .awar-content .awar-popup .awar-picker-submit:not(.disabled):hover {
                background-color: $picker_color_hex_hover;
            }
            .awar-overlay .popup .awar-title {
                font-size: $headline_font_size;
            }
            .awar-overlay .popup {
                background-color: $frame_background_color;
            }
            .awar-overlay .legal-info a {
                color: $legal_links_color;
            }
            .awar-overlay .awar-title {
                color: $headline_color;
            }
            .awar-overlay .awar-subtitle {
                color: $text_color;
            }
            .awar-switch label {
                color: $text_color;
            }
            .awar-popup.nav p {
                color: $information_paragraph_color;
            }
            ";

            wp_register_style('awar-override-submit-buttons-inline-style', false);
            wp_enqueue_style('awar-override-submit-buttons-inline-style');
            wp_add_inline_style('awar-override-submit-buttons-inline-style', $submit_button_css);
        }
    }
    new AWEOSVerificationEnqueue();
}
