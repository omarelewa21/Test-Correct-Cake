if (!Array.isArray) {
    Array.isArray = function(arg) {
        return Object.prototype.toString.call(arg) === '[object Array]';
    };
}
(function ($) {

    var settings = [];
    var element = null;
    var posting = false;

    $.fn.formify = function (options) {

        element = this;

        settings = $.extend({
            'onsuccess': null,
            'confirmPopup': false, 
            'action': $(element).attr('action'),
            'onbeforesubmit': false,
            'onaftersubmit' : false
        }, options);

        initialise();
    };

    function setEnterConfirm(elm){
        $(elm).keydown(function (e) {
            if (e.which == 13) {
                if (verifyAll()) {
                    postForm();
                } else {
                    Notify.notify($.i18n('Niet alle velden zijn correct ingevuld'), 'error');
                }
            }
        });
    }

    function initialise() {
        $(element).find('input, select').bind('change keyup', function () {
            verifyElement(this);
        });
        $(element).find('input, select').bind('blur', function () {
            verifyLengthElement(this);
        });

        if(settings.confirmPopup == false)
        {
            $(settings.confirm).click(function () {
                if(settings.onbeforesubmit != false){
                    settings.onbeforesubmit();
                }
                if (verifyAll()) {
                    postForm();
                } else {
                    Notify.notify($.i18n('Niet alle velden zijn correct ingevuld'), 'error');
                }
                if(settings.onaftersubmit != false){
                    settings.onaftersubmit();
                }
            });

            if(Array.isArray(settings.enterConfirm)){
                settings.enterConfirm.forEach(setEnterConfirm);
            } else {
                setEnterConfirm(settings.enterConfirm);
            }
        }else {
            $(settings.confirm).click(function () {

                if( settings.skipOnChecked.is(':checked') ) {
                    if (verifyAll()) {
                        postForm();
                    } else {
                        Notify.notify($.i18n('Niet alle velden zijn correct ingevuld'), 'error');
                    }

                    return;
                }


                Popup.message({
                    btnOk: $.i18n('Ja'),
                    btnCancel: $.i18n('Annuleer'),
                    title: $.i18n('Weet u het zeker?'),
                    message: settings.confirmMessage
                }, function() {
                    if (verifyAll()) {
                        postForm();
                    } else {
                        Notify.notify($.i18n('Niet alle velden zijn correct ingevuld'), 'error');
                    }
                });
            });
        }


    }

    function postForm() {
        if (posting == false) {
            posting = true;
            Loading.show();

            $.post(settings.action,
                    $(element).serialize(),
                    function (response) {
                        Loading.hide();
                        posting = false;

                        response = $.parseJSON(response);

                        if (response.status == '1') {
                            if (settings.onsuccess != undefined) {
                                settings.onsuccess(response.data);
                            }
                        } else {
                            if (settings.onfailure != undefined) {
                                settings.onfailure(response.data);
                            }
                        }
                    }
            );
        }
    }

    function verifyAll() {

        var passed = true;

        $.each($(element).find('input:visible, select:visible'), function () {
            if (!verifyElement(this)) {
                passed = false;
            }
        });

        return passed;
    }
    function verifyLengthElement(element) {
        var checks = $(element).attr('verify');
        if (checks != undefined) {
            checks = checks.split(' ');
            $.each(checks, function (index, check) {
                if (check == 'length-6') {
                    if ($(element).val().length < 6) {
                        Notify.notify($.i18n('Het nieuwe wachtwoord moet minimaal 6 karakters bevatten.'), 'error');
                    }
                }
            });
        }
    }

    function verifyElement(element) {
        var checks = $(element).attr('verify');

        var passed = true;

        if (checks != undefined) {
            checks = checks.split(' ');
            $.each(checks, function (index, check) {

                // NOT EMPTY
                if (check == 'notempty') {
                    if ($(element).val() == '') {
                        passed = false;
                    }
                }

                // LENGTHS
                if (check == 'length-10') {
                    if ($(element).val().length < 10) {
                        passed = false;
                    }
                }

                if (check == 'length-6') {
                    if ($(element).val().length < 6) {
                        passed = false;
                    }
                }

                if (check == 'max-length-5') {
                    if ($(element).val().length > 5) {
                        passed = false;
                    }
                }

                if (check == 'length-3') {
                    if ($(element).val().length < 3) {
                        passed = false;
                    }
                }

                if (check == 'length-4') {
                    if ($(element).val().length != 4) {
                        passed = false;
                    }
                }

                // DATE
                if (check == 'date' && $(element).val() != '') {
                    passed = true;
                }

            });
        }

        if (passed) {
            $(element).removeClass('verify-error').addClass('verify-ok');
        } else {
            $(element).removeClass('verify-ok').addClass('verify-error');
        }

        return passed;
    }

}(jQuery));