jQuery.fn.aweosImagePicker = function() {

    // replace text field with a button
    this.hide();
    this.after('<span class="aweos-image-picker-control">Select Image</span>');
    
    // show media library on click 'select image'
    var control = this.next();
    control.click(aweosImagePickerOpen);
    
    aweosLoadThumbnails(this);
}; 

function aweosLoadThumbnails() {
    jQuery('.aweos-image-picker-control').each(function (index, target) {
        var input = jQuery(target).prev();
        if (input.val().length === 0) return;
        var inputObj = JSON.parse(input.val());
        var url = inputObj.url;

        aweosImagePickerDisplayImage(jQuery(target), url);
    });
}

function aweosImagePickerOpen(e) {
    var target = jQuery(e.target).closest('.aweos-image-picker-control');
    var input = target.prev();

    image_frame = wp.media({
        title: 'Select Media',
        multiple : false,
        library : {
            type : 'image',
        }
    });

    image_frame.on('select', function() {
        var attachment = image_frame.state().get('selection').first().toJSON();

        aweosImagePickerSaveImage(input, attachment);
        aweosImagePickerDisplayImage(target, attachment.url);
    });

    image_frame.open();
}

function aweosImagePickerDisplayImage(target, url) {
    target.html('<img src="' + url + '">');
    target.addClass('filled');

    var imageActions = target.next();
    
    if (imageActions.is('div')) {
        imageActions.remove();
    }

    target.after(
        '<div class="aweos-image-actions">\
            <span class="remove"></span>\
            <span class="edit"></span>\
        <div>'
    );
    

    jQuery('.aweos-image-actions span').off();
    jQuery('.aweos-image-actions span').click(function () {
        if (jQuery(this).hasClass('remove')) {
            var imageActions = jQuery(this).parent();
            var control = imageActions.prev();
            var input = control.prev();

            input.removeAttr('value');
            imageActions.remove();
            control.remove();
            input.aweosImagePicker();
        }
        if (jQuery(this).hasClass('edit')) {
            jQuery(this).parent().prev().click();
        }
    });
}

function aweosImagePickerSaveImage(input, attachment) {
    input.val(JSON.stringify(attachment));
}