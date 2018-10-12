var Navigation = {
    loading : false,
    callback : null,

    history : [],

    load : function(url) {
        Loading.show();

        Navigation.history.push(url);

        window.onbeforeunload = null;

        clearInterval(TestTake.heartBeatInterval);

        User.inactive = 0;
        User.surpressInactive = false;

        $('#page_fade').fadeIn(function() {
            $.get(url,
                function(html) {
                    $('#container').html(html);
                    Loading.hide();
                    $('#page_fade').fadeOut();

                    if(Navigation.callback != null) {
                        Navigation.callback();
                        Navigation.callback = null;
                    }
                }
            );
        });
    },

    back : function() {
        Navigation.history.pop();

        $('#page_fade').fadeIn(function() {
            $.get(Navigation.history[Navigation.history.length - 1],
                function(html) {
                    $('#container').html(html);
                    Loading.hide();
                    $('#page_fade').fadeOut();
                }
            );
        });
    },

    reload : function() {
        User.inactive = 0;
        clearInterval(TestTake.heartBeatInterval);
        $('#page_fade').fadeIn(function() {
            $.get(Navigation.history[Navigation.history.length - 1],
                function(html) {
                    $('#container').html(html);
                    Loading.hide();
                    $('#page_fade').fadeOut();
                }
            );
        });
    },

    refresh : function() {
        User.inactive = 0;
        clearInterval(TestTake.heartBeatInterval);
        $.get(Navigation.history[Navigation.history.length - 1],
            function(html) {
                $('#container').html(html);
            }
        );
    }
};