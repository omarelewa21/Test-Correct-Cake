var FileManagement = {

    duplicateTestToSchool : function(file_management_id) {

        $.ajax({
            url: '/file_management/duplicate_test_to_school/' + file_management_id,
            type: 'PUT',
            dataType: 'json',
            success: function(response) {
                if(response['status'] == 1) {
                    Notify.notify($.i18n('Toets gedupliceerd'), 'info', 3000);
                    // Navigation.load('/tests/view/' + response.data.uuid);
                }else{
                    Notify.notify($.i18n('Toets kon niet worden gedupliceerd'), 'error', 3000);
                }

                Navigation.refresh();
            }
        });
    }
}