jQuery.fn.aweosGroupedButtons = function(options, buttons) {

    buttons = buttons.map(function (button, number) {
        if (button.width) {
            var width = button.width;
        } else {
            var width = 'auto';
        }

        if (button.height) {
            var height = button.height;
        } else {
            var height = 'auto';
        }
        return '\
            <div class="aweos-grouped-buttons-item">\
                <div class="item item-' + number + '" style="background-image: url(' + button.image + '); width: ' + width + '; height: ' + height + '"></div>\
                <p>' + button.label + '</p>\
            </div>\
        ';
    });

    buttons = buttons.join('');
    buttons_style = options.columns ? 'style="grid-template-columns: ' + options.columns + '"' : '';

    var html = '\
        <div class="aweos-grouped-buttons"' + buttons_style + '>\
            ' + buttons + '\
        </div>\
    ';

    if (options.includeSubmitButton) {
        html += '\
            <br><br>\
            <button name="save" class="button-primary" type="submit" value="Save changes">\
                Save changes\
            </button>\
        '
    }

    if (options.print === 'for-table') {
        jQuery(this).closest('table').append('<tr><th>' + html + '</th></tr>');
    } else {
        jQuery(this).after(html);
    }
};
