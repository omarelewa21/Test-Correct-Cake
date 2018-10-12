var Analyses = {
    loadStudentEndterms : function(user_id) {
        $.post('/analyses/student_endterms/' + user_id, $('#StudentEndtermsStudentForm').serialize(),
            function(response) {
                $('#divAnalyseTerms').html(response);
            }
        );
    },

    loadStudentSubjectRatings : function(user_id) {
        $.post('/analyses/student_subject_ratings/' + user_id, $('#StudentRatingsStudentForm').serialize(),
            function(response) {
                $('#divAnalyseSubjectRatings').html(response);
            }
        );
    }
};