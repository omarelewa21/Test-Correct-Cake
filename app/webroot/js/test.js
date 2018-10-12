var Test = {
    delete : function(test_id, view) {

        Popup.message({
            btnOk: 'Ja',
            btnCancel: 'Annuleer',
            title: 'Weet u het zeker?',
            message: 'Weet u zeker dat u deze toets wilt verwijderen?'
        }, function() {
            $.getJSON('/tests/delete/' + test_id,
                function(response) {
                    if(response['status'] == 1) {
                        if(view) {
                            Navigation.load('/tests/index');
                        }else{
                            Navigation.refresh();
                        }
                        Notify.notify('Toets verwijderd', 'info', 3000);
                    }else{
                        Notify.notify('Toets kon niet worden verwijderd', 'error', 3000);
                    }
                }
            );
        });
    },

    duplicate : function(test_id) {
        $.getJSON('/tests/duplicate/' + test_id,
            function(response) {
                if(response['status'] == 1) {
                    Notify.notify('Toets gedupliceerd', 'info', 3000);
                    Navigation.load('/tests/view/' + response.data.id);
                }else{
                    Notify.notify('Toets kon niet worden gedupliceerd', 'error', 3000);
                }

                Navigation.refresh();
            }
        );
    }
};