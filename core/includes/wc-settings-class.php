<?php

class AWAR_WC_Settings extends WC_Settings_Page {
    public function __construct() {
        $this->id = 'awar_options';
        $this->label = esc_html_x('Age Verification', 'Admin menu tab title',  'awar-domain');

        add_filter('woocommerce_settings_tabs_array', [$this, 'add_settings_page'], 20);
        add_action('woocommerce_settings_' . $this->id, [$this, 'output']);
        add_action('woocommerce_settings_save_' . $this->id, [$this, 'save']);
        add_action('woocommerce_sections_' . $this->id, [$this, 'output_sections']);
    }

    public function get_sections() {
        $sections = [
            '' => esc_html_x('General', 'Admin menu section headline', 'awar-domain'),
            'style' => esc_html_x('Style', 'Admin menu section headline', 'awar-domain'),
            'privacy' => esc_html_x('Privacy', 'Admin menu section headline', 'awar-domain'),
            'custom-texts' => esc_html_x('Custom Text', 'Admin menu section headline', 'awar-domain'),
        ];

        return apply_filters('woocommerce_get_sections_' . $this->id, $sections);
    }

    public function get_settings($current_section = '') {

        /*
            * type's to choose from:
            * https://docs.woocommerce.com/document/implementing-wc-integration/
            *
            * 'text'
            * 'price'
            * 'decimal'
            * 'password'
            * 'textarea'
            * 'checkbox'
            * 'select'
            * 'multiselect'
            *
            */

        // SINGLE PRODUCTS

        $products = wc_get_products([
            'status' => 'publish',
            'nopaging' => true,
            'limit' => -1,
        ]);

        $products_to_output = [];

        foreach ($products as $product) {
            $id = $product->get_id();
            $name = $product->get_name();
            $products_to_output[$id] = $name;
        }

        // PRODUCT CATEGORIES

        $categories = get_categories([
            'taxonomy' => 'product_cat',
        ]);

        $categories_to_output = [];

        foreach ($categories as $category) {
            $slug = $category->slug;
            $name = $category->name;
            $categories_to_output[$slug] = $name;
        }

        // PAGES

        $pages = get_pages([
            'status' => 'publish',
            'nopaging' => true,
        ]);
        $pages_to_output = [];

        foreach ($pages as $page) {
            $id = $page->ID;
            $title = $page->post_title;
            $pages_to_output[$id] = $title;
        }

        // POSTS

        $posts = get_posts([
            'status' => 'publish',
            'nopaging' => true,
        ]);
        $posts_to_output = [];

        foreach ($posts as $post) {
            $id = $post->ID;
            $title = $post->post_title;
            $posts_to_output[$id] = $title;
        }

        if ($current_section === 'custom-texts') {

            $settings = apply_filters('awar_woocommerce_tab_settings', [
                [
                    'name' => esc_html_x('Privacy', 'Admin menu headline', 'awar-domain'),
                    'class' => 'awar-descs',
                    'type' => 'title',
                    'id' => 'awar_texts',
                    'desc' => esc_html_x(
                        'If you are looking to change the texts of your pop-up, you are at the right place. Leave fields blank if you want the default text.',
                        'Admin menu label',
                        'awar-domain'
                    )
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-custom-texts-title',
                    'name' => esc_html_x('Popup/Main Title', 'Admin menu label', 'awar-domain'),
                    'class' => 'awar-descs',
                ],
                [
                    'type' => 'textarea',
                    'id' => 'awar-custom-texts-subtitle',
                    'name' => esc_html_x('Popup Subtitle', 'Admin menu label', 'awar-domain'),
                    'class' => 'awar-descs',
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-custom-texts-datepicker-title',
                    'name' => esc_html_x('Datepicker title', 'Admin menu label', 'awar-domain'),
                    'class' => 'awar-descs',
                ],
                [
                    'type' => 'textarea',
                    'id' => 'awar-custom-texts-checkbox-description',
                    'name' => esc_html_x('Checkbox description', 'Admin menu label', 'awar-domain'),
                    'class' => 'awar-descs',
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-not-old-enough-text',
                    'name' => esc_html_x('Text for an underaged visitor', 'Admin menu label', 'awar-domain'),
                    'class' => 'awar-descs',
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-custom-texts-checkbox-submit-button',
                    'name' => esc_html_x('Button text', 'Admin menu label', 'awar-domain'),
                    'class' => 'awar-descs',
                ],
                [
                    'type' => 'sectionend',
                    'id' => 'awar_texts'
                ],
                [
                    'name' => esc_html_x('Birthday Datepicker', 'Admin menu headline', 'awar-domain'),
                    'type' => 'title',
                    'id' => 'awar_birthday_datepicker'
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-custom-texts-datepicker-day',
                    'name' => esc_html_x('Datepicker day', 'Admin menu label', 'awar-domain'),
                    'class' => 'awar-descs',
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-custom-texts-datepicker-month',
                    'name' => esc_html_x('Datepicker month', 'Admin menu label', 'awar-domain'),
                    'class' => 'awar-descs',
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-custom-texts-datepicker-year',
                    'name' => esc_html_x('Datepicker year', 'Admin menu label', 'awar-domain'),
                    'class' => 'awar-descs',
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-custom-texts-datepicker-or-earlier',
                    'name' => esc_html_x('Text for "or earlyer"', 'Admin menu label', 'awar-domain'),
                    'class' => 'awar-descs',
                ],
                [
                    'type' => 'sectionend',
                    'id' => 'awar_yesno'
                ],
                [
                    'name' => esc_html_x('Yes/No Question', 'Admin menu headline', 'awar-domain'),
                    'type' => 'title',
                    'id' => 'awar_yesno',
                    'desc' => esc_html_x(
                        'If you need to change the Yes/No button texts, you can do this here.',
                        'Admin menu label',
                        'awar-domain'
                    )
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-custom-texts-simple-yes',
                    'name' => esc_html_x('Yes button text', 'Admin menu label', 'awar-domain'),
                    'class' => 'awar-descs',
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-custom-texts-simple-no',
                    'name' => esc_html_x('No button text', 'Admin menu label', 'awar-domain'),
                    'class' => 'awar-descs',
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-custom-texts-simple-back',
                    'name' => esc_html_x('Back button text', 'Admin menu label', 'awar-domain'),
                    'class' => 'awar-descs',
                ],
                [
                    'type' => 'sectionend',
                    'id' => 'awar_yesno'
                ],
            ]);
        }

        if ($current_section === "privacy") {

            $settings = apply_filters('awar_woocommerce_tab_settings', [
                [
                    'name' => esc_html_x('Privacy', 'Admin menu headline', 'awar-domain'),
                    'type' => 'title',
                    'class' => 'awar-descs',
                    'id' => 'awar_privacy_setting',
                ],
                [
                    'type' => 'checkbox',
                    'class' => 'awar-descs',
                    'id' => 'awar-privacy-policy-is-active',
                    'desc' => esc_html_x('Activate', 'admin menu description for the privacy policy', 'awar-domain'),
                    'name' => esc_html_x('Privacy Policy', 'admin menu description for the checkbox', 'awar-domain'),
                    'desc_tip' => _x(
                        'Display a small privacy link under the "confirm" button inside your modal popup.',
                        'Admin menu description on: terms and conditions is active',
                        'awar-domain'
                    ),
                    'default' => 'no'
                ],
                [
                    'type' => 'select',
                    'id' => 'awar-privacy-policy-page',
                    'desc' => esc_html_x('Select a page for your privacy policy.', 'admin menu description for the privacy policy', 'awar-domain'),
                    'options' => $pages_to_output,
                    'class' => 'wc-enhanced-select awar-descs',
                ],
                [
                    'type' => 'text',
                    'class' => 'awar-descs',
                    'id' => 'awar-privacy-policy-anchor-text',
                    'desc' => sprintf(
                        esc_html_x('%s The privacy policy %s text %s inside your link.', 'Admin menu label', 'awar-domain'), '<br>', '<strong>', '</strong>'
                    ),
                    'default' => _x('Privacy', 'default value for an admin option', 'awar-domain'),
                ],
                [
                    'type' => 'checkbox',
                    'class' => 'awar-descs',
                    'id' => 'awar-imprint-is-active',
                    'desc' => esc_html_x('Activate', 'admin menu description for the terms and conditions', 'awar-domain'),
                    'name' => esc_html_x('Terms and Conditions', 'admin menu description for the checkbox', 'awar-domain'),
                    'desc_tip' => _x(
                        'Display a small "terms and conditions" link under the "confirm" button inside your modal popup.',
                        'Admin menu description on: Terms and conditions is active',
                        'awar-domain'
                    ),
                    'default' => 'no'
                ],
                [
                    'type' => 'select',
                    'id' => 'awar-imprint-page',
                    'desc' => esc_html_x('Select a page for your terms and conditions.', 'admin menu description for the terms and conditions', 'awar-domain'),
                    'options' => $pages_to_output,
                    'class' => 'wc-enhanced-select awar-descs',
                ],
                [
                    'type' => 'text',
                    'class' => 'awar-descs',
                    'id' => 'awar-imprint-anchor-text',
                    'desc' => sprintf(
                        esc_html_x('%s The terms and conditions %s text %s inside your link.', 'Admin menu label', 'awar-domain'), '<br>', '<strong>', '</strong>'
                    ),
                    'default' => _x('Terms and Conditions', 'default value for an admin option', 'awar-domain'),
                ],
                [
                    'type' => 'sectionend',
                    'class' => 'awar-descs',
                    'id' => 'awar_privacy_setting'
                ],
            ]);
        }

        if ($current_section === "style") {

            $settings = apply_filters('awar_woocommerce_tab_settings', [
                [
                    'name' => esc_html_x('Themes', 'Admin menu headline', 'awar-domain'),
                    'desc' => sprintf(esc_html_x('%s Choose a %s Preset/Configuration%s. We will automatically change the styling options according to your choice. You can decide between a birthday-datepicker and a simple yes/no question and a dark/bright mode', 'Admin menu label', 'awar-domain'), '<br>', '<strong>', '</strong>', '<br>'),
                    'type' => 'title',
                    'id' => 'style',
                ],
                [
                    'id' => 'awar-style-preset-control',
                    'type' => 'text',
                    'class' => 'awar-style-preset-control awar-options-hidden',
                ],
                [
                    'type' => 'sectionend',
                    'id' => 'style'
                ],
                [
                    'name' => esc_html_x('Behavior', 'Admin menu headline', 'awar-domain'),
                    'type' => 'title',
                    'desc' => '',
                    'id' => 'awar_verification_setting',
                    'class' => 'awar-general'
                ],
                [
                    'name' => esc_html_x('Verification Method', 'Admin menu headline', 'awar-domain'),
                    'type' => 'select',
                    'id' => 'awar-verification-method',
                    'desc' => sprintf(esc_html_x('%s Choose the %s Verification Method%s. This option will change the behavior of your popup. %sChoose between a simple Yes/No popup or a birthday datepicker.', 'Admin menu label', 'awar-domain'), '<br>', '<strong>', '</strong>', '<br>'),
                    'options' => ['Birthday Date-Picker', 'Yes/No Question'],
                    'default' => 0,
                ],
                [
                    'type' => 'sectionend',
                    'id' => 'awar_verification_setting'
                ],
                [
                    'name' => esc_html_x('Submit Button', 'Admin menu headline', 'awar-domain'),
                    'type' => 'title',
                    'desc' => '',
                    'id' => 'awar_submit_button_setting',
                    'class' => 'awar-general'
                ],
                [
                    'name' => esc_html_x('Button Appearance', 'Admin menu headline', 'awar-domain'),
                    'type' => 'checkbox',
                    'id' => 'awar-submit-button-has-shadow',
                    'desc' => esc_html_x('Draw a shadow under the submit button.', 'admin menu label', 'awar-domain'),
                    'default' => 'no',
                ],
                [
                    'type' => 'checkbox',
                    'id' => 'awar-submit-button-is-rounded',
                    'desc' => esc_html_x('Curve/Round the corners of your submit button.', 'admin menu label', 'awar-domain'),
                    'default' => 'yes',
                ],
                [
                    'type' => 'checkbox',
                    'id' => 'awar-submit-button-has-hover',
                    'desc' => esc_html_x('Change the color of your submit button on hover. Shown if a visitor rests his mouse on top.', 'admin menu label', 'awar-domain'),
                    'default' => 'yes',
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-color-picker-submit',
                    'class' => 'awar-color-picker',
                    'name' => esc_html_x('Button Color', 'Admin menu sub-headline', 'awar-domain'),
                    'desc' => sprintf(esc_html_x('The %s background %s color for your button.', 'Admin menu label', 'awar-domain'), '<strong>', '</strong>'),
                    'default' => '#279f0f',
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-color-picker-submit-shadow',
                    'class' => 'awar-color-picker',
                    'desc' => sprintf(esc_html_x('The %s shadow %s color for your button.', 'Admin menu label', 'awar-domain'), '<strong>', '</strong>'),
                    'default' => '#106603',
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-color-picker-submit-hover',
                    'class' => 'awar-color-picker',
                    'desc' => sprintf(esc_html_x('The %s hover %s color for your button. Shown on resting your mouse on the button.', 'Admin menu label', 'awar-domain'), '<strong>', '</strong>'),
                    'default' => '#1f9108',
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-color-picker-submit-text-color',
                    'class' => 'awar-color-picker',
                    'desc' => sprintf(esc_html_x('The %s text %s color for your button. Make sure that your text is easy to read.', 'Admin menu label','awar-domain'), '<strong>', '</strong>'),
                    'default' => '#ffffff',
                ],
                [
                    'type' => 'sectionend',
                    'id' => 'awar_submit_button_setting'
                ],
                [
                    'name' => esc_html_x('Frame', 'Admin menu headline', 'awar-domain'),
                    'type' => 'title',
                    'id' => 'awar_frame',
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-frame-background-color',
                    'name' => esc_html_x('Background Color', 'Admin menu option', 'awar-domain'),
                    'class' => 'awar-color-picker',
                    'desc' => sprintf(esc_html_x('The %s background %s color for the popup itself. Make sure your text is easy to read.', 'Admin menu label', 'awar-domain'), '<strong>', '</strong>'),
                    'default' => '#e7e7ef',
                ],
                [
                    'type' => 'checkbox',
                    'id' => 'awar-frame-is-rounded',
                    'desc' => esc_html_x('Frame is rounded.', 'Admin menu option', 'awar-domain'),
                    'default' => 'yes',
                    'desc_tip' => _x(
                        'Round the corners from you popup window for a smooth appearance.',
                        'Admin menu description for a checkbox',
                        'awar-domain'
                    ),
                ],
                [
                    'type' => 'checkbox',
                    'id' => 'awar-frame-shadow',
                    'desc' => esc_html_x('Frame has shadow.', 'Admin menu option', 'awar-domain'),
                    'default' => 'yes',
                    'desc_tip' => _x(
                        'Add a box shadow to your popup and emphasise/raise the window.',
                        'Admin menu description for a checkbox',
                        'awar-domain'
                    ),
                ],
                [
                    'type' => 'sectionend',
                    'id' => 'awar_frame'
                ],
                [
                    'type' => 'title',
                    'name' => esc_html_x('Fonts', 'Admin menu headline', 'awar-domain'),
                    'desc' => '',
                    'id' => 'awar_fonts_setting',
                    'class' => 'awar-general'
                ],
                [
                    'type' => 'select',
                    'id' => 'awar-text-align',
                    'desc' => sprintf(esc_html_x('%s The %s text align %s for your written content. %s Its recommendet to use a "left" text align on bigger texts. %s The checkbox text will always stay left aligned.', 'Admin menu label','awar-domain'), '<br>', '<strong>', '</strong>', '<br>', '<br>'),
                    'options' => ['Left', 'Center'],
                    'name' => esc_html_x('Text Align', 'admin menu description', 'awar-domain'),
                    'class' => 'awar-descs',
                    'default' => '1',
                ],
                [
                    'type' => 'number',
                    'id' => 'awar-headline-font-size',
                    'title' => esc_html_x('Headline Size', 'Admin menu sub-headline', 'awar-domain'),
                    'class' => 'awar-descs',
                    'default' => '17',
                    'desc' => sprintf(esc_html_x('%s The size of your headline.', 'Admin menu label','awar-domain'), '<br>')
                ],
                [

                    'type' => 'select',
                    'id' => 'awar-legal-align',
                    'desc' => sprintf(esc_html_x('%s The %s text align/spacing %s for your legal/bottom links. %s', 'Admin menu label','awar-domain'), '<br>', '<strong>', '</strong>', '<br>'),
                    'options' => ['Left', 'Center', 'Right', 'Space Between'],
                    'name' => esc_html_x('Botton/Legal Links Text Align/Spacing', 'admin menu description', 'awar-domain'),
                    'class' => 'awar-descs',
                    'default' => 0,
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-legal-links-color',
                    'name' => esc_html_x('Legal/Bottom Links Color', 'Admin menu option', 'awar-domain'),
                    'class' => 'awar-color-picker',
                    'desc' => sprintf(esc_html_x('The %s background %s color for the popup itself. Make sure you text is easy to read.', 'Admin menu label', 'awar-domain'), '<strong>', '</strong>'),
                    'default' => '#666666',
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-headline-color',
                    'name' => esc_html_x('Color of Headlines', 'Admin menu option', 'awar-domain'),
                    'class' => 'awar-color-picker',
                    'desc' => sprintf(esc_html_x('The %s text %s color for your headlines inside the popup. Make sure your text is easy to read.', 'Admin menu label', 'awar-domain'), '<strong>', '</strong>'),
                    'default' => 'black',
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-text-color',
                    'name' => esc_html_x('Subtitle color', 'Admin menu option', 'awar-domain'),
                    'class' => 'awar-color-picker',
                    'desc' => sprintf(esc_html_x('The %s subtitle %s color, we recommend a grey color. Make sure your text is easy to read', 'Admin menu label', 'awar-domain'), '<strong>', '</strong>'),
                    'default' => '#777',
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-information-paragraph-color',
                    'name' => esc_html_x('Information Paragraph Color', 'Admin menu option', 'awar-domain'),
                    'class' => 'awar-color-picker',
                    'desc' => sprintf(esc_html_x('The %s text %s color for your bottom text inside the popup, above the submit button.', 'Admin menu label', 'awar-domain'), '<strong>', '</strong>'),
                    'default' => 'black',
                ],
                [
                    'type' => 'sectionend',
                    'id' => 'awar_fonts_setting'
                ],
                [
                    'name' => esc_html_x('Branding', 'Admin menu headline', 'awar-domain'),
                    'type' => 'title',
                    'desc' => '',
                    'id' => 'awar_branding_setting',
                    'class' => 'awar-general'
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-popup-logo',
                    'name' => esc_html_x('Logo', 'Admin menu sub-headline', 'awar-domain'),
                    'class' => 'awar-popup-logo awar-image-picker',
                    'desc' => sprintf(esc_html_x('%s Insert a %s smaller%s sized image on your popup. %s Recommended resolution: 100x100', 'Admin menu label', 'awar-domain'), '<br>', '<strong>', '</strong>', '<br>'),
                ],
                [
                    'type' => 'text',
                    'id' => 'awar-popup-picture',
                    'name' => esc_html_x('Picture', 'Admin menu sub-headline', 'awar-domain'),
                    'class' => 'awar-popup-picture awar-image-picker',
                    'desc' => sprintf(esc_html_x('%s Use a %s bigger %s image to catch the visitors interest. %s Recommended resolution: 330x455', 'Admin menu label', 'awar-domain'), '<br>', '<strong>', '</strong>', '<br>'),
                ],
                [
                    'type' => 'sectionend',
                    'id' => 'awar_branding_setting'
                ],
                [
                    'name' => esc_html_x('Navigation', 'Admin menu headline', 'awar-domain'),
                    'type' => 'title',
                    'desc' => '',
                    'id' => 'awar_navigation_setting',
                ],
                [
                    'type' => 'checkbox',
                    'id' => 'awar-back-button-is-active',
                    'desc' => esc_html_x('Activate a back-button.', 'admin menu description for the terms and conditions', 'awar-domain'),
                    'name' => esc_html_x('Back-Button', 'admin menu description for the checkbox', 'awar-domain'),
                    'desc_tip' => _x(
                        'Show a tiny back button on top of your popup, visitors can leave the site if desired.',
                        'Admin menu description',
                        'awar-domain'
                    ),
                    'default' => 'no',
                ],
                [
                    'type' => 'checkbox',
                    'id' => 'awar-lock-is-active',
                    'desc' => esc_html_x('Activate a lock icon.', 'admin menu description for the terms and conditions', 'awar-domain'),
                    'name' => esc_html_x('Lock-Icon', 'admin menu description for the checkbox', 'awar-domain'),
                    'desc_tip' => _x(
                        "Show a tiny lock icon on top of your popup.",
                        'Admin menu description',
                        'awar-domain'
                    ),
                    'default' => 'no'
                ],
                [
                    'type' => 'select',
                    'id' => 'awar-lock-position',
                    'desc' => '<br>' . _x('Position of your lock icon.', 'Admin menu label', 'awar-domain'),
                    'options' => ['Right', 'Middle'],
                    'default' => 'Right',
                ],
                [
                    'type' => 'sectionend',
                    'id' => 'awar_navigation_setting'
                ],
            ]);
        }

        if ($current_section === '') {

            $settings = apply_filters('awar_woocommerce_tab_settings', [
                [
                    'name' => esc_html_x('Function', 'Admin menu headline', 'awar-domain'),
                    'type' => 'title',
                    'desc' => '',
                    'id' => 'awar_general_settings',
                    'class' => 'awar-general awar-descs'
                ],
                [
                    'type' => 'checkbox',
                    'class' => 'awar-descs',
                    'id' => 'awar-popup-only-in-single-pages',
                    'name' => 'Allow Popup just on single pages',
                    'default' => 'yes'
                ],
                [
                    'type' => 'number',
                    'title' => __('Minimum age', 'awar-domain'),
                    'id' => 'awar-minimum-age',
                    'desc' => sprintf(esc_html_x('%s The %s minimum age %s for a user to pass the verification.', 'Admin menu label', 'awar-domain'), '<br>', '<strong>', '</strong>'),
                    'default' => '21',
                ],
                [
                    'type' => 'number',
                    'title' => __('Remember visitor', 'awar-domain'),
                    'id' => 'awar-cookie-duration',
                    'desc' => sprintf(esc_html_x('%s The %s cooldown %s for the verification to remember the user, in days.', 'Admin menu label', 'awar-domain'), '<br>', '<strong>', '</strong>'),
                    'default' => '90',
                ],
                [
                    'type' => 'sectionend',
                    'id' => 'awar_general_settings'
                ],
                [
                    'name' => esc_html_x('Placement', 'Admin menu headline', 'awar-domain'),
                    'type' => 'title',
                    'desc' => '',
                    'class' => 'awar-headline-product-categories awar-descs',
                    'id' => 'awar_configuration_setting',
                ],
                [
                    'type' => 'multiselect',
                    'name' => esc_html_x('Products with verification', 'awar-domain'),
                    'id' => 'awar-restrict-for-products',
                    'options' => $products_to_output,
                    'class' => 'wc-enhanced-select awar-dropdown awar-descs',
                    'desc' => esc_html_x(
                        'You can select WooCommerce products to restrict with a verification.
                        you should select every product you want to lock out for specific user groups.
                        If your age restricted products belongs to a category, use the category selector.',
                        'admin menu description for the post multiselect',
                        'awar-domain'
                    ),
                ],
                [
                    'type' => 'multiselect',
                    'name' => esc_html_x('Categories with verification', 'awar-domain'),
                    'id' => 'awar-restrict-for-categories',
                    'options' => $categories_to_output,
                    'class' => 'wc-enhanced-select awar-dropdown awar-descs',
                    'desc' => esc_html_x(
                        'The categories will appear if you click on products from your admin side menu,
                        you should select every category you want to lock out for specific user groups.',
                        'admin menu description for the category multiselect',
                        'awar-domain'
                    ),
                ],
                [
                    'type' => 'multiselect',
                    'name' => esc_html_x('Posts with verification', 'awar-domain'),
                    'id' => 'awar-restrict-for-posts',
                    'options' => $posts_to_output,
                    'class' => 'wc-enhanced-select awar-posts awar-dropdown awar-descs',
                    'desc' => esc_html_x(
                        'You can select typical WordPress posts to restrict with a verification.
                        you should select every post you want to lock out for specific user groups.',
                        'admin menu description for the post multiselect',
                        'awar-domain'
                    ),
                ],
                [
                    'type' => 'multiselect',
                    'name' => esc_html_x('Pages with verification', 'awar-domain'),
                    'id' => 'awar-restrict-for-pages',
                    'options' => $pages_to_output,
                    'class' => 'wc-enhanced-select awar-pages awar-dropdown awar-descs',
                    'desc' => esc_html_x(
                        'You can select typical WordPress pages to restrict with a verification.
                        you should select every page you want to lock out for specific user groups.',
                        'admin menu description for the post multiselect',
                        'awar-domain'
                    ),
                ],
                [
                    'type' => 'sectionend',
                    'id' => 'awar_configuration_setting',
                ],
            ]);

        }

        return apply_filters('woocommerce_get_settings_' . $this->id, $settings, $current_section);
    }

    public function output() {
        global $current_section;

        $settings = $this->get_settings($current_section);
        WC_Admin_Settings::output_fields($settings);
    }

    public function save() {
        global $current_section;

        $settings = $this->get_settings($current_section);

        if (is_admin()) {
            WC_Admin_Settings::save_fields($settings);
        }
    }
}
