<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('AWEOSVerificationTwigLoader')) {
    class AWEOSVerificationTwigLoader {
        public function enqueue_templates() {
            static $do_once = false;

            if ($do_once) {
                return;
            }

            $do_once = true;

            if (!is_single()) {
                $only_single = esc_html(WC_Admin_Settings::get_option('awar-popup-only-in-single-pages', 'yes'));
                if ($only_single === 'yes') {
                    return;
                }
            }

            $loader = new \Twig\Loader\FilesystemLoader(dirname(__FILE__, 3) . '/assets/template');
            $twig = new \Twig\Environment($loader);

            // ----------------
            // -- Datepicker --
            // ----------------

            // custom values (if available)
            $datepicker_day = esc_html(WC_Admin_Settings::get_option(
                'awar-custom-texts-datepicker-day', ''
            ));

            $datepicker_month = esc_html(WC_Admin_Settings::get_option(
                'awar-custom-texts-datepicker-month', ''
            ));

            $datepicker_year = esc_html(WC_Admin_Settings::get_option(
                'awar-custom-texts-datepicker-year', ''
            ));

            $datepicker_or_earlier = esc_html(WC_Admin_Settings::get_option(
                'awar-custom-texts-datepicker-or-earlier', ''
            ));

            // default values
            $datepicker_day = $datepicker_day ?: esc_html_x('Day', 'Front-End age popup', 'awar-domain');
            $datepicker_month = $datepicker_month ?: esc_html_x('Month', 'Front-End age popup', 'awar-domain');
            $datepicker_year = $datepicker_year ?: esc_html_x('Year', 'Front-End age popup', 'awar-domain');
            $datepicker_or_earlier = $datepicker_or_earlier ?: esc_html_x('or earier', 'Front-End age popup', 'awar-domain');

            // prepare template output
            $birthday_datepicker = $twig->render('birthday-datepicker.twig', [
                'prefix' => 'awar',
                'from_year' => date('Y') - 25,
                'to_year' => date('Y') - 15,
                'picker_day' => $datepicker_day,
                'picker_month' => $datepicker_month,
                'picker_year' => $datepicker_year,
                'picker_early' => $datepicker_or_earlier,
            ]);

            // -------------------
            // -- Popup Content --
            // -------------------

            // custom values (if available)
            $picker_button = esc_html(WC_Admin_Settings::get_option(
                'awar-custom-texts-checkbox-submit-button', ''
            ));

            $picker_confirm_description = esc_html(WC_Admin_Settings::get_option(
                'awar-custom-texts-checkbox-description', ''
            ));

            // default values
            $picker_button = $picker_button ?: esc_html_x('Confirm my age', 'Front-End datepicker', 'awar-domain');
            $picker_confirm_description = $picker_confirm_description ?: esc_html_x('By verifying, you declare that the information made is correct and agree that your data will be collected electronically in order to confirm your age.', 'Front-End datepicker', 'awar-domain');

            // prepare template output

            $picker_title = esc_html(
                WC_Admin_Settings::get_option('awar-custom-texts-datepicker-title', '')
            );

            $title = esc_html(
                WC_Admin_Settings::get_option('awar-custom-texts-title', '')
            );

            $popup_subtitle = esc_html(
                WC_Admin_Settings::get_option('awar-custom-texts-subtitle', '')
            );

            $not_old_enough_text = esc_html(
                WC_Admin_Settings::get_option('awar-not-old-enough-text', '')
            );

            $popup_subtitle = esc_html(WC_Admin_Settings::get_option(
                'awar-custom-texts-subtitle', ''
            ));


            $popup_subtitle = $popup_subtitle ?: esc_html_x('Verify your age to enter.', 'Front-End age popup', 'awar-domain');
            $not_old_enough_text = $not_old_enough_text ?: esc_html_x('You are not old enough to use this service/product.', 'Text for underaged user', 'awar-domain');
            $picker_title = $picker_title ?: esc_html_x('Enter your age', 'Front-End age popup', 'awar-domain');
            $title = $title ?: esc_html_x('Confirm your age', 'Front-End age popup', 'awar-domain');

            $popup_picture = json_decode(WC_Admin_Settings::get_option('awar-popup-picture', ''));
            $popup_logo = json_decode(WC_Admin_Settings::get_option('awar-popup-logo', ''));

            if ($popup_picture) {
                $has_picture = property_exists($popup_picture, 'url') ? 'yes' : 'no';
            } else {
                $has_picture = 'no';
            }

            if ($popup_logo) {
                $has_logo = property_exists($popup_logo, 'url') ? 'yes' : 'no';
            } else {
                $has_logo = 'no';
            }

            $awar_legal_align_setting = WC_Admin_Settings::get_option('awar-legal-align', '0');

            if ($awar_legal_align_setting === '1') $awar_legal_align = 'center';
            else if ($awar_legal_align_setting === '2') $awar_legal_align = 'space_right';
            else if ($awar_legal_align_setting === '3') $awar_legal_align = 'space_between';
            else $awar_legal_align = 'left';

            $general_template_options = [
                'prefix' => 'awar',
                'required' => true,
                'picker_title' => $picker_title,
                'cookie_duration' => esc_html(WC_Admin_Settings::get_option('awar-cookie-duration', '90')),
                'title' => $title,
                'submit_button_is_rounded' => esc_html(WC_Admin_Settings::get_option('awar-submit-button-is-rounded', 'yes')),
                'frame_background_color' => esc_html(WC_Admin_Settings::get_option('awar-frame-background-color', '#e7e7ef')),
                'frame_is_rounded' => esc_html(WC_Admin_Settings::get_option('awar-frame-is-rounded', 'yes')),
                'frame_shadow' => esc_html(WC_Admin_Settings::get_option('awar-frame-shadow', 'yes')),
                'legal_links_color' => esc_html(WC_Admin_Settings::get_option('awar-legal-links-color', '#666666')),
                'popup_subtitle' => $popup_subtitle,
                'not_old_enough_text' => $not_old_enough_text,
                'root_url' => plugin_dir_url(dirname(__FILE__, 2)),
                'color_picker_submit_hover' => esc_html(WC_Admin_Settings::get_option('awar-color-picker-submit-hover', '#1f9108')),
                'submit_button_has_hover' => esc_html(WC_Admin_Settings::get_option('awar-submit-button-has-hover', 'yes')),
                'text_align' => esc_html(WC_Admin_Settings::get_option('awar-text-align', '1')),
                'submit_button_has_shadow' => esc_html(WC_Admin_Settings::get_option('awar-submit-button-has-shadow', 'no')),
                'back_button_is_active' => esc_html(WC_Admin_Settings::get_option('awar-back-button-is-active', 'no')),
                'lock_is_active' => esc_html(WC_Admin_Settings::get_option('awar-lock-is-active', 'no')),
                'lock_position' => esc_html(WC_Admin_Settings::get_option('awar-lock-position', 'right')),
                'popup_picture' => $popup_picture,
                'popup_logo' => $popup_logo,
                'has_picture' => $has_picture,
                'has_logo' => $has_logo,
                'privacy_polecy_active' => esc_html(WC_Admin_Settings::get_option('awar-privacy-policy-is-active', 'no')),
                'privacy_url' => get_permalink(
                    esc_html(WC_Admin_Settings::get_option('awar-privacy-policy-page', ''))
                ),
                'privacy_text' => esc_html(WC_Admin_Settings::get_option('awar-privacy-policy-anchor-text', '')),
                'imprint_active' => esc_html(WC_Admin_Settings::get_option('awar-imprint-is-active', 'no')),
                'imprint_url' => get_permalink(
                    esc_html(WC_Admin_Settings::get_option('awar-imprint-page', ''))
                ),
                'imprint_text' => esc_html(WC_Admin_Settings::get_option('awar-imprint-anchor-text', '')),
                'legal_space_between' => $awar_legal_align === 'space_between' ? 'yes' : 'no',
                'legal_space_right' => $awar_legal_align === 'space_right' ? 'yes' : 'no',
                'legal_center' => $awar_legal_align === 'center' ? 'yes' : 'no',
            ];

            if (WC_Admin_Settings::get_option('awar-verification-method', 0) === '1') {
                // yes/no question selected

                $general_template_options['custom_texts_simple_yes'] = esc_html_x(
                    WC_Admin_Settings::get_option('awar-custom-texts-simple-yes', 'Yes') ?: 'Yes',
                    'Simple popup button - Yes',
                    'awar-domain'
                );

                $general_template_options['custom_texts_simple_no'] = esc_html_x(
                    WC_Admin_Settings::get_option('awar-custom-texts-simple-no', 'No') ?: 'No',
                    'Simple popup button - No',
                    'awar-domain'
                );

                $general_template_options['custom_texts_simple_back'] = esc_html_x(
                    WC_Admin_Settings::get_option('awar-custom-texts-simple-back', 'Back') ?: 'Back',
                    'Simple popup button - Back',
                    'awar-domain'
                );

                echo $twig->render('popup-yes-no.twig', $general_template_options);

                return;
            }

            $popup_content = $twig->render('popup-content.twig', [
                'prefix' => 'awar',
                'birthdaydatepicker' => $birthday_datepicker,
                'picker_confirm_description' => $picker_confirm_description,
                'picker_color_hex' => esc_html(WC_Admin_Settings::get_option('awar-color-picker-submit', '#279f0f')),
                'picker_color_hex_shadow' => esc_html(WC_Admin_Settings::get_option('awar-color-picker-submit-shadow', '#106603')),
                'picker_color_text' => esc_html(WC_Admin_Settings::get_option('awar-color-picker-submit-text-color', '#ffffff')),
                'picker_button' => $picker_button,
                'privacy_polecy_active' => esc_html(WC_Admin_Settings::get_option('awar-privacy-policy-is-active', '')),
                'privacy_url' => get_permalink(
                    esc_html(WC_Admin_Settings::get_option('awar-privacy-policy-page', ''))
                ),
                'privacy_text' => esc_html(WC_Admin_Settings::get_option('awar-privacy-policy-anchor-text', '')),
                'imprint_active' => esc_html(WC_Admin_Settings::get_option('awar-imprint-is-active', '')),
                'imprint_url' => get_permalink(
                    esc_html(WC_Admin_Settings::get_option('awar-imprint-page', ''))
                ),
                'imprint_text' => esc_html(WC_Admin_Settings::get_option('awar-imprint-anchor-text', '')),
            ]);


            // -----------
            // -- Popup --
            // -----------

            // custom values
            $picker_title = esc_html(WC_Admin_Settings::get_option(
                'awar-custom-texts-datepicker-title', ''
            ));

            $title = esc_html(WC_Admin_Settings::get_option(
                'awar-custom-texts-title', ''
            ));

            // add data into twig options
            $general_template_options['content'] = $popup_content;
            $popup = $twig->render('popup.twig', $general_template_options);

            echo $popup;
        }
    }
}
