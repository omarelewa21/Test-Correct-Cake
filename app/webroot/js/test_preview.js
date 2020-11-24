var TestPreview = {

    count : 0,
    countInterval : null,
    questionSaved : false,

    loadQuestionPreview : function(test_id, index_id) {
        window.location = '/tests/preview/' + test_id + '/' + index_id;
    },

    loadQuestion : function(test_id, question_id) {
        $('#question_load').load('/questions/preview/' + test_id + '/' + question_id);
    },

    loadAttachment : function(attachment_id) {
        $('#question_load').hide();
        $('#attachmentFrame').show().attr({'src' : '/answers/attachment/' + attachment_id});
        $('#btnAttachmentFrame').show().unbind().click(function() {
            $('#attachmentFrame').hide();
            $('#question_load').show();
            $(this).hide();
        });
    },

    startCount : function() {
        clearTimeout(Answer.countInterval);
        Answer.count = 0;
        Answer.countInterval = setInterval(function() {
            Answer.count++;
        }, 1000);
    },

    saveAnswer : function() {
        $.post('/answers/save/' + Answer.count,
            $('#AnswerQuestionForm').serialize(),
            function(data) {
                if(data.status == 'next') {
                    Notify.notify('Antwoord opgeslagen', 'info');
                    Navigation.load('/test_takes/take/' + data.take_id + '/' + data.question_id);
                    Answer.questionSaved = true;
                }else if(data.status == 'done') {
                    Notify.notify('Er zijn geen vragen meer. Lever de toets in.');
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
            alert('Selecteer maximaal ' + max + ' opties');
        }else{
            Answer.saveAnswer();
        }
    }
};