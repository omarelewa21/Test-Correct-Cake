var Questions = {
    openType : null,
    getCorrectQuestionTypeIfNotLaravel(type,sub_type){
        if(type == 'CompletionQuestion' && sub_type == 'multi'){
            type = 'MultiCompletionQuestion';
        }
        if (type === 'MultipleChoiceQuestion' && sub_type === 'truefalse') {
            type = 'TrueFalseQuestion';
        }
        return type;
    },

    /**
     * @param type
     * @param owner 'test' | 'group'
     * @param test_id
     * @param sub_type
     * @param test_question_id
     * @param group_question_question_id
     */
    addPopup : function(newEditor, type, owner, test_id, sub_type, test_question_id) {
        if (newEditor) {
            test_question_id =  (owner == 'test') ? '' : test_question_id;
            this.openInEditorInLaravel('add', type, owner, test_id, sub_type, test_question_id);
            return;
        }

        type = this.getCorrectQuestionTypeIfNotLaravel(type, sub_type);
        popup.closelast();
        settimeout(function() {
            var owner_id = owner == 'test' ? test_id : test_question_id;
            navigation.load('/questions/add/' + owner + '/' + owner_id + '/' + type);
        }, 500);
    },
    /**
     * @param type
     * @param owner 'test' | 'group'
     * @param test_id
     * @param sub_type
     * @param test_question_id
     * @param group_question_question_id
     */
    editPopup: function (type, owner, test_id, sub_type, test_question_id, group_question_question_id) {
       this.openInEditorInLaravel('edit', type, owner, test_id, sub_type, test_question_id, group_question_question_id);
    },
    /**
     * @param verb 'add'|'edit
     * @param type
     * @param owner 'test' | 'group'
     * @param test_id
     * @param sub_type
     * @param test_question_id
     * @param group_question_question_id
     */
    openInEditorInLaravel: function (verb, type, owner, test_id, sub_type, test_question_id, group_question_question_id) {
        User.goToLaravel(
            'teacher/questions/'+verb+'/' +
            type + '/' + sub_type +
            '?owner=' + owner +
            '&testId=' + test_id +
            '&testQuestionId=' + test_question_id +
            '&groupQuestionQuestionId=' + (group_question_question_id || '')
        );
        return;
    },


    addOpenPopup : function(type, owner, owner_id, goToLaravel) {
        if (goToLaravel && owner === 'test') {
            var path = 'teacher/questions/add/OpenQuestion/'+type+'/?owner=' + owner + '&owner_id=' + owner_id;
            User.goToLaravel(path);
            return;
        }

        Questions.openType = type;

        Popup.closeLast();
        setTimeout(function() {
            Popup.closeLast();
        }, 500);

        setTimeout(function() {
            Navigation.load('/questions/add/' + owner + '/' + owner_id + '/OpenQuestion');
        }, 1000);
    },

    add : function(type, owner, owner_id) {
        $.post('/questions/add/' + owner + '/' + owner_id + '/' + type,
            $('#QuestionAddForm').serialize(),
            function(response) {
                response = JSON.parse(response);
                if(response['status'] == 1) {
                    Popup.closeLast();
                    // Navigation.refresh();
                    Notify.notify($.i18n('Vraag opgeslagen'), 'info');
                    Questions.closeQuestionEditor();

                }else{
                    Object.entries(response['data']).map(item => {
                        typeof item[1] == 'object' ? item[1][0]=$.i18n(item[1][0]) : item[1]=$.i18n(item[1])
                    })      // Add translation to error message

                    Notify.notify(response['data'].join('<br />'), 'error');
                }
            }
        );
    },

    edit : function(owner, owner_id, type, question_id) {
        $.post('/questions/edit/' + owner + '/' + owner_id + '/' + type + '/' + question_id,
            $('#QuestionEditForm').serialize(),
            function(response) {
                response = JSON.parse(response);
                if(response['status'] == 1) {
                    Popup.closeLast();
                    // Navigation.refresh();
                    Notify.notify($.i18n('Vraag opgeslagen'), 'info');
                    Questions.closeQuestionEditor();
                }else{
                    // if(typeof ){

                    // }
                    // else{

                    // }
                    $.each(response['data'], function() {
                        Object.entries(response['data']).map(item => {
                            typeof item[1] == 'object' ? item[1][0]=$.i18n(item[1][0]) : item[1]=$.i18n(item[1])
                        })      // Add translation to error message
                        Notify.notify($.i18n(response['data'].join('<br />')), 'error');
                    });
                }
            }
        );
    },

    addMultiChoiceOption : function() {
        $('#tableMultiChoiceOptions tr:hidden').first().fadeIn();
        Questions.updateMultiChoiceOrder();

        var count = $('#tableMultiChoiceOptions tr:visible').length;
        count--;
    },

    removeMultiChoiceOption : function(e) {
        $(e).closest('tr').find('input').val('');
        $(e).closest('tr').hide();
        Questions.updateMultiChoiceOrder();
    },

    updateMultiChoiceOrder : function() {
        var i = 0;
        $.each($('#tableMultiChoiceOptions tr:visible'), function() {
            $(this).find('.order').val(i);
            i++;
        });
    },

    addMatchingOption : function() {
        $('#tableMatchingOptions tr:hidden').first().fadeIn();
        Questions.updateMatchingOrder();
    },

    removeMatchingOption : function(e) {
        $(e).closest('tr').find('input').val('');
        $(e).closest('tr').fadeOut();
        Questions.updateMatchingOrder();
    },

    updateMatchingOrder : function() {
        var i = 0;
        $.each($('#tableMatchingOptions tr:visible'), function() {
            $(this).find('.order').val(i);
            i++;
        });
    },

    loadExistionQuestionsList : function(test_id) {
        Popup.closeLast();

        setTimeout(function() {
            Popup.load('/questions/add_existing_question_list/' + test_id, 900);
        }, 1000);
    },

    showPvalueError : function(error) {
        Popup.message({
            btnOk: 'Oke',
            title: 'Opmerking bij p-waarde',
            message: error
        });
    },

    addExistingQuestion : function(question_id, subquestion) {

        if(subquestion) {

            Popup.message({
                btnOk: $.i18n('Importeren'),
                btnCancel: $.i18n('Annuleren'),
                title: $.i18n('Onderdeel van groepvraag'),
                message: $.i18n('Deze vraag is onderdeel van een groep-vraag, wanneer u deze importeert worden eventuele bijlages niet meegenomen.')
            }, function() {
                $.get('/questions/add_existing_question/' + question_id,
                    function(response) {
                        Notify.notify($.i18n('De bestaande vraag is toegevoegd.'), "info", "5000");
                        Navigation.refresh();
                    }
                );
            });


        }else{
            $.get('/questions/add_existing_question/' + question_id,
                function(response) {
                    Notify.notify($.i18n('De bestaande vraag is toegevoegd.'), "info", "5000");
                    Navigation.refresh();
                }
            );
        }
    },

    addExistingQuestionToGroup : function(question_id, subquestion) {

        if(subquestion) {

            Popup.message({
                btnOk: $.i18n('Importeren'),
                btnCancel: $.i18n('Annuleren'),
                title: $.i18n('Onderdeel van groepvraag'),
                message: $.i18n('Deze vraag is onderdeel van een groep-vraag, wanneer u deze importeert worden eventuele bijlages niet meegenomen.')
            }, function() {
                $.get('/questions/add_existing_question_to_group/' + question_id,
                    function(response) {
                        Notify.notify($.i18n('De bestaande vraag is toegevoegd.'), "info", "5000");
                        Navigation.refresh();
                    }
                );
            });


        }else{
            $.get('/questions/add_existing_question_to_group/' + question_id,
                function(response) {
                    Notify.notify($.i18n('De bestaande vraag is toegevoegd.'), "info", "5000");
                    Navigation.refresh();
                }
            );
        }
    },


    addExistingQuestionGroup : function(question_group_id) {
        $.get('/questions/add_existing_question_group/' + question_group_id,
            function(response) {
                Navigation.refresh();
                Popup.closeLast();
            }
        );
    },

    addClassifyOption : function() {
        $('#tableClassifyOptions tr:hidden').first().fadeIn();
        Questions.updateClassifyOrder();
    },

    removeClassifyOption : function(e) {
        $(e).closest('tr').find('input').val('');
        $(e).closest('tr').fadeOut();
        Questions.updateClassifyOrder();
    },

    updateClassifyOrder : function() {
        var i = 0;
        $.each($('#tableClassifyOptions tr:visible'), function() {
            $(this).find('.order').val(i);
            i++;
        });
    },

    addRankingOption : function() {
        $('#tableRankingOptions tr:hidden').first().fadeIn();
        Questions.updateRankingOrder();
    },

    removeRankingOption : function(e) {
        $(e).closest('tr').find('input').val('');
        $(e).closest('tr').fadeOut();
        Questions.updateRankingOrder();
    },

    updateRankingOrder : function() {
        var i = 0;
        $.each($('#tableRankingOptions tr:visible'), function() {
            $(this).find('.order').val(i);
            i++;
        });
    },

    loadAddAttachments : function(is_clone,owner, owner_id,id) {
        is_clone = typeof is_clone != 'undefined' ? !!is_clone : false;
        if(is_clone){
            $('div[sources][tabs=edit_question] > .loadhere, #groupAttachments').load('/questions/attachments/add/'+owner+'/'+owner_id+'/'+id+'?' + new Date().getTime(), function(){
                var form = jQuery('#QuestionAddForm');
                jQuery('.cloneAttachment').each(function(){
                   $(this).prependTo(form);
                });
            });
        }else{
            $('div[sources][tabs=add_question] > .loadhere, #groupAttachments').load('/questions/attachments/add?' + new Date().getTime());
        }
    },

    loadEditAttachments : function(owner, owner_id, id) {
        Attachments.owner_id = owner_id;
        $('div[sources][tabs=edit_question] > .loadhere, #groupAttachments').load('/questions/attachments/edit/' + owner + '/' + owner_id + '/' + id);
    },

    updateIndex : function(question, test_id) {
        var i = 0;

        question_parts = question.split("_");

        question_id = question_parts[1];
        question_type = question_parts[0];


        $.each($('#tableQuestions tr'), function() {
            if($(this).attr('id') == question) {

                $.get('/questions/update_index/' + question_type + '/' + question_id + '/' + test_id + '/' + i,
                    function() {
                        Navigation.refresh();
                    }
                );
            }

            i++;
        });
    },

    updateGroupIndex : function(question_id, group_id) {
        var i = 0;

        $.each($('#tableQuestions tr'), function() {
            if($(this).attr('id') == question_id) {

                $.get('/questions/update_group_question_index/' + question_id + '/' + group_id + '/' + i,
                    function() {
                        Navigation.refresh();
                    }
                );
            }

            i++;
        });
    },

    delete : function(owner, owner_id, question_id) {
        Popup.message({
                title: $.i18n('Weet u het zeker?'),
                message: $.i18n('Weet u zeker dat u deze vraag wilt verwijderen?'),
                btnCancel: $.i18n('Annuleren'),
                btnOk : $.i18n('Ja')

            },
            function() {
                $.ajax({
                    url: '/questions/delete/' + owner + '/' + owner_id + '/' + question_id,
                    type: 'DELETE',
                    dataType: 'json',
                    success: function(response) {
                        if(response['status'] == 1) {
                            Navigation.refresh();
                            Notify.notify($.i18n('Vraag verwijderd'), 'info', 3000);
                        }else{
                            Notify.notify($.i18n('Vraag kon niet worden verwijderd'), 'error', 3000);
                        }
                    }
                });
            }
        );
    },

    deleteGroup : function(test_id, group_id) {

        Popup.message({
                title: $.i18n('Weet u het zeker?'),
                message: $.i18n('Weet u zeker dat u deze vraaggroep wilt verwijderen?'),
                btnCancel: $.i18n('Annuleren'),
                btnOk : $.i18n('Ja')

            },
            function() {
                $.ajax({
                    url: '/questions/delete_group/' + test_id + '/' + group_id,
                    type: 'DELETE',
                    dataType: 'json',
                    success: function(response) {
                        if(response['status'] == 1) {
                            Navigation.refresh();
                            Notify.notify($.i18n('Groep verwijderd'), 'info', 3000);
                        }else{
                            Notify.notify($.i18n('Groep kon niet worden verwijderd'), 'error', 3000);
                        }
                    }
                });
            }
        );


    },

    moveToGroup : function(group_id) {
        $.post('/questions/move_to_group/',
            {
                group_id : group_id
            },
            function(response) {
                Popup.closeLast();
                Navigation.refresh();
            }
        );
    },
    closeQuestionEditor: function () {
        Navigation.back();
        $('#container').removeClass('question-editor');
        $('#header, #tiles').slideDown();
        $('.question-editor-header').slideUp();
    }
};

var Attachments = {
    owner_id : null,

    fileInserted : function() {
        Loading.show();
        $('#FormAddAttachment').submit();
    },

    removeAddAttachment : function(id) {
        $.ajax({
            url: '/questions/remove_add_attachment/' + id,
            type: 'DELETE',
            success: function(response) {
                Questions.loadAddAttachments();
            }
        });
    },

    removeCloneAttachment : function(id,el) {
        jQuery('#cloneAttachment'+id).remove();
        jQuery(el).parents('tr').remove();
    },

    removeEditAttachment : function(owner, owner_id, id) {
        $.ajax({
            url: '/questions/remove_edit_attachment/' + owner + '/' + owner_id + '/' + id,
            type: 'DELETE',
            success: function(response) {
                Questions.loadEditAttachments(owner, Attachments.owner_id, owner_id);
            }
        });
    },

    addVideoAdd : function() {
        var url = $('#url').val();

        if(url != "" && url != undefined) {
            $.post('/questions/add_video_attachment',
                {
                    url : url
                },
                function(response) {
                    if(response == 1) {
                        Questions.loadAddAttachments();
                        Popup.closeLast();
                    }else{
                        Notify.notify($.i18n('Geen goedgekeurde link'), 'error');
                    }
                }
            );
        }
    },

    addVideoEdit : function(owner, owner_id, id) {
        var url = $('#url').val();

        if(url != "" && url != undefined) {
            $.post('/questions/add_edit_video_attachment/' + owner + '/' + owner_id + '/' + id,
                {
                    url : url
                },
                function(response) {
                    if(response == 1) {
                        Questions.loadEditAttachments(owner, owner_id, id);
                        Popup.closeLast();
                    }else{
                        Notify.notify($.i18n('Geen goedgekeurde link'), 'error');
                    }
                }
            );
        }
    },

    uploadError : function(error) {
        if(error == 'file_type') {
            Notify.notify($.i18n("Dit bestandstype wordt niet ondersteund"), "error");
        }else if(error == 'file_size') {
            Notify.notify($.i18n("Dit bestand is te groot"), "error");
        }else if(error == 'no_file') {
            Notify.notify($.i18n("Geen bestand geselecteerd"), "error");
        }
    }
};
