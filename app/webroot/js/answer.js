var Answer = {

    count : 0,
    countInterval : null,
    questionSaved : false,
    answerChanged : false,
    activeIndex : null,
    takeId : null,
    timeoutStart : null,
    timeoutDeadline : null,

    loadQuestionAnswer : function(question_id) {

        Answer.answerChanged = false;

        $('#question_load').load('/answers/question/' + question_id,
            function() {
                $('input, textarea').bind('change, click', function() {
                    Answer.answerChanged = true;
                });
            }
        );
    },

    loadAttachment : function(attachment_id, timeout,draggable) {
        $("#attachmentContainer").attr("style","");
        $('#attachmentContainer').removeClass('draggable').show().resizable();
        if(draggable == 1){
            $("#attachmentContainer").addClass('draggable').draggable();
        }


        $('#attachmentFrame').attr({'src' : '/answers/attachment/' + attachment_id});
        $('#btnAttachmentFrame').unbind().click(function() {

            if(timeout != undefined && timeout != '' && timeout > 0) {
                Answer.timeoutStart = Answer.count;
                Answer.timeoutDeadline = (parseInt(Answer.count) + parseInt(timeout));

                $('#divAttachments').hide();
                Answer.answerChanged = true;
            }

            $('#attachmentFrame').attr({'src' : '/answers/clear'});
            $('#attachmentContainer').hide();
            $('#question_load').show();
            // $(this).hide();
        });

        return false;
    },

    startCount : function() {
        clearTimeout(Answer.countInterval);
        Answer.count = 0;
        Answer.countInterval = setInterval(function() {
            Answer.count++;

            if(Answer.timeoutDeadline != null) {

                var timeLeft = (Answer.timeoutDeadline - Answer.count);
                var percentage = (100 / (Answer.timeoutDeadline - Answer.timeoutStart)) * timeLeft;

                $('#barAnswerTimeout').show().find('.progress-bar').css({
                    'width' : percentage + '%'
                }).html(timeLeft + ' sec resterend');

                if(timeLeft <= 0) {
                    Answer.saveAnswer();
                    clearInterval(Answer.countInterval);
                    $('#question_load').find('input, textarea').attr('disabled', 'disabled');
                }
            }

        }, 1000);
    },

    loadQuestion : function(url, el) {
        TestTake.nextUrl = url;
        Answer.saveAnswer(url, el);
        Answer.timeoutDeadline = null;
        Answer.timeoutStart = null;
    },

    saveAnswer: function(url, el) {
        if (Answer.partOfCloseableGroup && typeof el !== 'undefined' && Answer.currentGroupId != $(el).attr('group-id')) {
                Popup.confirm(
                    {
                        'title' : 'Let op! Vraaggroep sluit',
                        'text': 'Door naar deze vraag te gaan, sluit je de vraaggroep af waar je nu mee bezig bent. Je kan hierna niet meer terugkeren.“',
                        'okBtn': 'Ja'
                    },function() {
                        return Answer.saveAnswerComplete(url, 'close_group');
                    });

        } else if (Answer.closeable) {
            Popup.confirm(
                {
                    'title' : 'Let op! Vraag sluit',
                    'text': 'Door naar deze vraag te gaan, sluit je de vraag af waar je nu mee bezig bent. Je kan hierna niet meer terugkeren.“',
                    'okBtn': 'Ja'
                },function() {
                    return Answer.saveAnswerComplete(url, 'close_question');
                });
        } else {
            this.saveAnswerComplete(url);
        }

    },

    saveAnswerComplete : function(url, closeAction) {
        if (!closeAction){
            closeAction = false;
        }
        if (Answer.answerChanged) {
           this.postSaveAnswer(url, closeAction);
        } else if (closeAction) {
            this.postSaveAnswer(url, closeAction);
        } else {
            if (url != "void") {
                Navigation.load(TestTake.nextUrl);
            }
        }
    },

    saveAndNextAnswerPlease : function() {
        $(document.getElementById(Answer.nextId)).trigger('click');
    },


    postSaveAnswer: function(url, closeAction) {
        $.post('/answers/save/' + Answer.count+'/'+closeAction,
            $('#AnswerQuestionForm').serialize(),
            function (data) {

                if (data.error != undefined) {
                    Notify.notify(data.error, 'error');
                    return true;
                }

                if (data.alert != undefined) {
                    TestTake.alert = data.alert;
                    TestTake.markBackground();
                }

                Answer.questionSaved = true;

                if (url != undefined) {
                    if (url != "void") {
                        Navigation.load(url);
                    }
                } else {
                    if (data.status == 'next') {
                        Notify.notify('Antwoord opgeslagen', 'info');
                        Navigation.load('/test_takes/take/' + data.take_id + '/' + data.question_id);
                    } else if (data.status == 'done') {
                        Navigation.load('/test_takes/take_answer_overview/' + Answer.takeId);
                    }
                }
            },
            'JSON'
        );
    },

    completeAnswer : function(tag_id) {
        var answer = prompt("Antwoord");

        if(answer != "" && answer != undefined) {
            $('#tag_' + tag_id).html(answer);
            $('#answer_' + tag_id).val(answer);
        }
    },

    checkMultipleChoice : function(max) {
        if($('.multiple_choice_option:checked').length > max) {
            if(max == 1) {
                Notify.notify('Selecteer maximaal ' + max + ' optie', 'info');
            }else{
                Notify.notify('Selecteer maximaal ' + max + ' opties', 'info');
            }
        }else{
            Answer.saveAnswer();
        }
    },

    notePad : function(question_id) {
        if($('#notePad_' + question_id).length == 0) {
            var htmlBlock = "<iframe id='notePad_" + question_id + "' class='notePad' frameborder=0></iframe>";
            $('body').append(htmlBlock);
            $("#notePad_" + question_id).show().attr('src', '/answers/note_pad/' + question_id);
        }else{
            $("#notePad_" + question_id).show();
        }
    },

    drawingPad : function(question_id) {
        if($('#drawingPad_' + question_id).length == 0) {
            var htmlBlock = "<iframe id='drawingPad_" + question_id + "' class='drawingPad' frameborder=0></iframe>";
            $('body').append(htmlBlock);
            $("#drawingPad_" + question_id).show().attr('src', '/answers/drawing_pad/' + question_id);
        }else{
            $("#drawingPad_" + question_id).show();
        }
    },

    drawingPadClose : function(question_id) {
        $("#drawingPad_" + question_id).hide();
    },

    notePadClose : function(question_id) {
        $("#notePad_" + question_id).hide();
    }
};
