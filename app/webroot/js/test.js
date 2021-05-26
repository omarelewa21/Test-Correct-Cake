var Test = {
    groupQuestionType : 'standard',
    test_id : null,
    groupQuestionTypeEvents : false,
    delete : function(test_id, view) {

        Popup.message({
            btnOk: $.i18n('Ja'),
            btnCancel: $.i18n('Annuleer'),
            title: $.i18n('Weet u het zeker?'),
            message: $.i18n('Weet u zeker dat u deze toets wilt verwijderen?')
        }, function() {
            $.ajax({
                url: '/tests/delete/' + test_id,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    if(response['status'] == 1) {
                        if(view) {
                            Navigation.load('/tests/index');
                        }else{
                            Navigation.refresh();
                        }
                        Notify.notify($.i18n('Toets verwijderd'), 'info', 3000);
                    }else{
                        Notify.notify($.i18n('Toets kon niet worden verwijderd'), 'error', 3000);
                    }
                }
            });
        });
    },

    duplicate : function(test_id) {
        $.ajax({
            url: '/tests/duplicate/' + test_id,
            type: 'PUT',
            dataType: 'json',
            success: function(response) {
                if(response['status'] == 1) {
                    Notify.notify($.i18n('Toets gedupliceerd'), 'info', 3000);
                    Navigation.load('/tests/view/' + response.data.uuid);
                }else{
                    Notify.notify($.i18n('Toets kon niet worden gedupliceerd'), 'error', 3000);
                }

                Navigation.refresh();
            }
        });
    },

    groupQuestionCreatePopup : function(){
        if(this.test_id==null){
            return false;
        }
        Popup.load('/questions/add_group/'+this.test_id+'/'+this.groupQuestionType, 600); return false;
    },

    groupQuestionChooseTypePopup : function(test_id){
        this.test_id = test_id;
        Popup.promptChooseGroupQuestionType();
        this.groupQuestionChooseTypeEvents();
    },

    groupQuestionChooseTypeEvents : function(){
        var testObj = this; 
        if(!this.groupQuestionTypeEvents) {
            $(document).on("click", "#groupquestion_type_confirm", function () {
                if ($('#groupquestion_type_confirm').hasClass("disabled")) {
                    return false;
                }
                var url = '/questions/add_group/'+testObj.test_id+'/'+testObj.groupQuestionType;
                Popup.closeWithNewPopup(url);
            });

            $(document).on("click", "#groupquestion_type_standard", function () {
                $('#groupquestion_type_standard').addClass("highlight");
                testObj.groupQuestionChooseTypeConfirmButtonActive();
                testObj.groupQuestionChooseTypeInActive('groupquestion_type_carousel');
                testObj.groupQuestionType = 'standard';
            });

            $(document).on("click", "#groupquestion_type_carousel", function () {
                $('#groupquestion_type_carousel').addClass("highlight");
                testObj.groupQuestionChooseTypeConfirmButtonActive();
                testObj.groupQuestionChooseTypeInActive('groupquestion_type_standard');
                testObj.groupQuestionType = 'carousel';
            });
            this.groupQuestionTypeEvents = true;
        }
    },

    groupQuestionChooseTypeConfirmButtonActive : function(){
        $('#groupquestion_type_confirm').removeClass("disabled");
        $('#groupquestion_type_confirm').removeClass("grey");
        $('#groupquestion_type_confirm').addClass("blue");
    },

    groupQuestionChooseTypeInActive : function(id){
        $('#'+id).addClass("grey");
        $('#'+id).removeClass("highlight");
    }
};