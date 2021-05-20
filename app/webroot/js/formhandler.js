var FormHandler = {
    element: null,
    settings: {
        'onsuccess': null,
        'confirmPopup': false,
        'action': null,
        'onbeforesubmit': false,
        'onaftersubmit' : false
    },
    posting: false,
    init: function(element,settings){
        this.flush();
        this.element = element;
        this.settings = $.extend({
            'onsuccess': null,
            'confirmPopup': false,
            'action': false,
            'onbeforesubmit': false,
            'onaftersubmit' : false
        }, settings);
    },
    flush(){
        this.element= null;
        this.settings= {
            'onsuccess': null,
            'confirmPopup': false,
            'action': null,
            'onbeforesubmit': false,
            'onaftersubmit' : false
        };
    },
    submit: function (){
        if(!this.element){
            return false;
        }
        if(this.settings.onbeforesubmit != false){
            this.settings.onbeforesubmit();
        }
        if (this.verifyAll()) {
            this.postForm();
        } else {
            Notify.notify('Niet alle velden zijn correct ingevuld', 'error');
        }
        if(this.settings.onaftersubmit != false){
            this.settings.onaftersubmit();
        }
    },
     postForm : function() {
        if (this.posting == false) {
            this.posting = true;
            var currentObj = this;
            Loading.show();
            $.post(currentObj.settings.action,
                $('#'+currentObj.element).serialize(),
                function (response) {
                    Loading.hide();
                    this.posting = false;

                    response = $.parseJSON(response);

                    if (response.status == '1') {
                        if (currentObj.settings.onsuccess != undefined) {
                            currentObj.settings.onsuccess(response.data);
                        }
                    } else {
                        if (currentObj.settings.onfailure != undefined) {
                            currentObj.settings.onfailure(response.data);
                        }
                    }
                }
            );
        }
    },
    verifyAll : function () {
        var passed = true;

        $.each($('#'+this.element).find('input:visible, select:visible'), function () {
            if (!verifyElement(this)) {
                passed = false;
            }
        });

        return passed;
    },
    verifyElement : function(element) {
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
};