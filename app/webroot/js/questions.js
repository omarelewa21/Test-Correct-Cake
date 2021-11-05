var Questions = {
    openType : null,

    addPopup : function(type, owner, owner_id) {
        Popup.closeLast();
        setTimeout(function() {
            Popup.load('/questions/add/' + owner + '/' + owner_id + '/' + type, 800);
        }, 500);
    },

    addOpenPopup : function(type, owner, owner_id) {

        Questions.openType = type;

        Popup.closeLast();
        setTimeout(function() {
            Popup.closeLast();
        }, 500);

        setTimeout(function() {
            Popup.load('/questions/add/' + owner + '/' + owner_id + '/OpenQuestion', 800);
        }, 1000);
    },

    add : function(type, owner, owner_id) {
        $.post('/questions/add/' + owner + '/' + owner_id + '/' + type,
            $('#QuestionAddForm').serialize(),
            function(response) {
                response = JSON.parse(response);
                if(response['status'] == 1) {
                    Popup.closeLast();
                    Navigation.refresh();
                }else{
                    // console.log(response['data']);
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
                    Navigation.refresh();
                }else{
                    $.each(response['data'], function() {
                        Notify.notify(response['data'].join('<br />'), 'error');
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

    loadAddAttachments : function(is_clone) {
        is_clone = typeof is_clone != 'undefined' ? !!is_clone : false;
        if(is_clone){
            $('div[page=sources][tabs=edit_question], #groupAttachments').load('/questions/attachments/add?' + new Date().getTime());
        }else{
            $('div[page=sources][tabs=add_question], #groupAttachments').load('/questions/attachments/add?' + new Date().getTime());
        }
    },

    loadEditAttachments : function(owner, owner_id, id) {
        Attachments.owner_id = owner_id;
        $('div[page=sources][tabs=edit_question], #groupAttachments').load('/questions/attachments/edit/' + owner + '/' + owner_id + '/' + id);
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
