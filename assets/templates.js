jQuery(function ($) {
    var overlay = $('.awar-overlay');

    if (Cookies.get('awarStatus') === 'accepted') {
        overlay.removeClass('visible');
    }

    if (overlay.hasClass('popup-type-yes-no')) {
        $('.awar-yesno .yes').click(function () {
            overlay.removeClass('visible');
            Cookies.set('awarStatus', 'accepted', { expires: window.awarCookieDuration });
        });

        $('.awar-yesno .no').click(function () {
            $('.awar-subtitle').html(window.awarNotOldEnoughText);
            $('.awar-subtitle').addClass('not-old-enough');
            $('.awar-yesno .no').hide()
            $('.awar-yesno .yes').hide()
            $('.awar-yesno .home').show()

        });
        $('.awar-yesno .home').click(function (gotToURL){
            window.location.href = "//" + window.location.host
        });
    }

    else if (overlay.hasClass('popup-type-default')) {
        $('.select-birth').selectize();
        $(".selectize-input input").attr('readonly','readonly');

        var submit = $('.awar-picker-submit');

        submit.off();
        submit.click(function () {

            // validate
            var validDate = awarCheckDate();
            var picker = $('.awar-picker');

            // make input fields red or let user see the site
            if (validDate) {
                picker.removeClass('not-valid');
                overlay.removeClass('visible');
                Cookies.set('awarStatus', 'accepted', { expires: window.awarCookieDuration });
            } else {
                picker.addClass('not-valid');

                $('.awar-subtitle').html(window.awarNotOldEnoughText);
                $('.awar-subtitle').addClass('not-old-enough');
            }
        });
    }
});

function awarGetUsersBirthday() {
    var day = parseInt(jQuery('select.select-birth.day').last().val());
    var month = parseInt(jQuery('select.select-birth.month').last().val()) - 1;
    var year = parseInt(jQuery('select.select-birth.year').last().val());

    return new Date(year, month, day);
}

function awarCheckDate() {
    // fetch date - check if valid - month starts at 0
    var birthday = awarGetUsersBirthday();
    var awarMinAge = parseInt(
        jQuery('meta[name=awarMinAge]').attr('content')
    );

    var shouldHaveDate = new Date();

    if (!birthday) return false;
    shouldHaveDate.setFullYear(shouldHaveDate.getFullYear() - awarMinAge);
    shouldHaveDate.setDate(shouldHaveDate.getDate() + 1);

    console.log('birthday: ');
    console.log(birthday);

    console.log('min age: ');
    console.log(awarMinAge);

    console.log('should have date: ');
    console.log(shouldHaveDate);

    return shouldHaveDate > birthday;
}
