var Notify = {

    count : 0,

    notify : function(message, type, timeout) {

        if(type == undefined){
            type = '';
        }

        if(timeout == undefined) {
            timeout = 4000;
        }

        Notify.count++;

        var htmlBlock = "<div id='notification_" + Notify.count + "' class='notification " + type + "'>" + message + "</div>";

        $('#notifications').append(htmlBlock);

        $('#notification_' + Notify.count).slideDown();

        setTimeout("$('#notification_" + Notify.count + "').slideUp(function() {$(this).remove();});", timeout);
    }
};