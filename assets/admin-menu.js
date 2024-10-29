jQuery(function($) {
    $('.awar-color-picker').wpColorPicker();

    $('.awar-image-picker').aweosImagePicker();
    $('#awar-style-preset-control').aweosGroupedButtons(
        {
            print: 'for-table',
            includeSubmitButton: true,
            columns: '1fr 1fr 1fr'
        },
        [
            {
                label: 'Birthday Picker - Darkmode',
                image: awarPath.pluginURI + 'assets/img/birthday-dark.png',
                width: '170px',
                height: '170px'
            },
            {
                label: 'Birthday Picker - Daymode',
                image: awarPath.pluginURI + 'assets/img/birthday-day.png',
                width: '170px',
                height: '170px'
            },
            {
                label: 'Birthday Picker - Green',
                image: awarPath.pluginURI + 'assets/img/birthday-green.png',
                width: '170px',
                height: '170px'
            },
            {
                label: 'Simple Question - Darkmode',
                image: awarPath.pluginURI + 'assets/img/simple-dark.png',
                width: '170px',
                height: '170px'
            },
            {
                label: 'Simple Question - Daymode',
                image: awarPath.pluginURI + 'assets/img/simple-day.png',
                width: '170px',
                height: '170px'
            },
            {
                label: 'Simple Question - Green',
                image: awarPath.pluginURI + 'assets/img/simple-green.png',
                width: '170px',
                height: '170px'
            },
        ]
    );

    $('.aweos-grouped-buttons .item-0').click(function (e) {
        awarDarkmode();
        awarBirhday();
    });

    $('.aweos-grouped-buttons .item-1').click(function (e) {
        awarDaymode();
        awarBirhday();
    });

    $('.aweos-grouped-buttons .item-2').click(function (e) {
        awarGreenmode();
        awarBirhday();
    });

    $('.aweos-grouped-buttons .item-3').click(function (e) {
        awarDarkmode();
        awarSimplePopup();
    });

    $('.aweos-grouped-buttons .item-4').click(function (e) {
        awarDaymode();
        awarSimplePopup();
    });

    $('.aweos-grouped-buttons .item-5').click(function (e) {
        awarGreenmode();
        awarSimplePopup();
    });



    $('.awar-options-hidden').parent().parent().hide();

    // --------------------------------------
    // - disable control elements if needed -
    // --------------------------------------

    $('#awar-submit-button-has-shadow').on('change', function (e) {
        var isChecked = $(this).attr('checked') === 'checked';
        if (isChecked) {
            $('#awar-color-picker-submit-shadow').parent().parent().prev().removeAttr('disabled');
        } else {
            $('#awar-color-picker-submit-shadow').parent().parent().prev().attr('disabled', 'disabled');
        }
    });
    $('#awar-submit-button-has-shadow').trigger('change');

    $('#awar-submit-button-has-hover').on('change', function (e) {
        var isChecked = $(this).attr('checked') === 'checked';
        if (isChecked) {
            $('#awar-color-picker-submit-hover').parent().parent().prev().removeAttr('disabled');
        } else {
            $('#awar-color-picker-submit-hover').parent().parent().prev().attr('disabled', 'disabled');
        }
    });
    $('#awar-submit-button-has-hover').trigger('change');

    $('#awar-lock-is-active').on('change', function (e) {
        var isChecked = $(this).attr('checked') === 'checked';
        if (isChecked) {
            $('#awar-lock-position').removeAttr('disabled');
        } else {
            $('#awar-lock-position').attr('disabled', 'disabled');
        }
    });
    $('#awar-lock-is-active').trigger('change');

    // Privacy page
    $('#awar-privacy-policy-is-active').on('change', function (e) {
        var isChecked = $(this).attr('checked') === 'checked';
        if (isChecked) {
            $('#awar-privacy-policy-page').removeAttr('disabled');
            $('#awar-privacy-policy-anchor-text').removeAttr('disabled');
        } else {
            $('#awar-privacy-policy-page').attr('disabled', 'disabled');
            $('#awar-privacy-policy-anchor-text').attr('disabled', 'disabled');
        }
    });
    $('#awar-privacy-policy-is-active').trigger('change');

    $('#awar-imprint-is-active').on('change', function (e) {
        var isChecked = $(this).attr('checked') === 'checked';
        if (isChecked) {
            $('#awar-imprint-page').removeAttr('disabled');
            $('#awar-imprint-anchor-text').removeAttr('disabled');
        } else {
            $('#awar-imprint-page').attr('disabled', 'disabled');
            $('#awar-imprint-anchor-text').attr('disabled', 'disabled');
        }
    });
    $('#awar-imprint-is-active').trigger('change');
});

// Birthday Date-Picker
function awarBirhday() {

    // submit button (checkbox inputs)
    jQuery('#awar-submit-button-has-shadow').attr('checked', false);
    jQuery('#awar-submit-button-is-rounded').attr('checked', true);
    jQuery('#awar-submit-button-has-hover').attr('checked', true);
    jQuery('#awar-back-button-is-active').attr('checked', true);
    jQuery('#awar-lock-is-active').attr('checked', true);
    jQuery('#awar-frame-is-rounded').attr('checked', true);

    // value inputs
    jQuery('#awar-text-align').val(1);              // 0 -> left 1 -> center
    jQuery('#awar-headline-font-size').val(17);
    jQuery('#awar-lock-position').val(0);            // 0 -> right 1 -> middle
    jQuery('#awar-verification-method').val(0);      // 0 -> birthday picker 1 -> yes/no question
}

// Yes/No Popup
function awarSimplePopup() {
    // submit button (checkbox inputs)
    jQuery('#awar-submit-button-has-shadow').attr('checked', false);
    jQuery('#awar-submit-button-is-rounded').attr('checked', false);
    jQuery('#awar-submit-button-has-hover').attr('checked', true);
    jQuery('#awar-back-button-is-active').attr('checked', false);
    jQuery('#awar-lock-is-active').attr('checked', false);
    jQuery('#awar-frame-is-rounded').attr('checked', false);

    // value inputs
    jQuery('#awar-text-align').val(0);               // 0 -> left 1 -> center
    jQuery('#awar-verification-method').val(1);      // 0 -> birthday picker 1 -> yes/no question
    jQuery('#awar-headline-font-size').val(32);
}

// Darkmode
function awarDarkmode() {
    jQuery('#awar-color-picker-submit').val('#dd3333').trigger('change');
    jQuery('#awar-color-picker-submit-shadow').val('#7f1919').trigger('change');
    jQuery('#awar-color-picker-submit-hover').val('#c62d2d').trigger('change');
    jQuery('#awar-color-picker-submit-text-color').val('#ffffff').trigger('change');
    jQuery('#awar-frame-background-color').val('#141414').trigger('change');
    jQuery('#awar-legal-links-color').val('#e8e8e8').trigger('change');
    jQuery('#awar-headline-color').val('#ffffff').trigger('change');
    jQuery('#awar-text-color').val('#eeeeee').trigger('change');
    jQuery('#awar-information-paragraph-color').val('#ffffff').trigger('change');
}

// Daymode
function awarDaymode() {
    jQuery('#awar-color-picker-submit').val('#101010').trigger('change');
    jQuery('#awar-color-picker-submit-shadow').val('#595959').trigger('change');
    jQuery('#awar-color-picker-submit-hover').val('#343434').trigger('change');
    jQuery('#awar-color-picker-submit-text-color').val('#ffffff');
    jQuery('#awar-frame-background-color').val('#eeeeee').trigger('change');
    jQuery('#awar-legal-links-color').val('#666666').trigger('change');
    jQuery('#awar-headline-color').val('#000000').trigger('change');
    jQuery('#awar-text-color').val('#141414').trigger('change');
    jQuery('#awar-information-paragraph-color').val('#000000').trigger('change');
}

function awarGreenmode() {
    awarDaymode();
    jQuery('#awar-color-picker-submit').val('#279f0f').trigger('change');
    jQuery('#awar-color-picker-submit-hover').val('#1f9108').trigger('change');
    jQuery('#awar-color-picker-submit-text-color').val('#ffffff').trigger('change');
}