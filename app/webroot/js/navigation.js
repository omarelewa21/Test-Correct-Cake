var Navigation = {
    loading : false,
    callback : null,

    history : [],
    usingPusher: false,

    load : function(url) {
        Loading.show();

        Navigation.history.push(url);

        window.onbeforeunload = null;

        clearInterval(TestTake.heartBeatInterval);

        User.inactive = 0;
        User.surpressInactive = false;

        if (Navigation.usingPusher) {
            Navigation.usingPusher = false;
            if (window.pusherSurveillanceTimeout !== null) {
                clearTimeout(window.pusherSurveillanceTimeout);
            }
            if (typeof (window.pusher) !== 'undefined') {
                pusher.disconnect();
                pusher = undefined;
                //Clear instances of the global Pusher class. When disconnecting from channels, the instances still remain. RR - 29-03-2022
                Pusher.instances = [];
            }
        }

        $('#page_fade').fadeIn(function() {
            $.get(url,
                function(html) {
                    $('#container').html(html);
                    // if ('com' in window && 'wiris' in window.com && 'js' in window.com.wiris && 'JsPluginViewer' in window.com.wiris.js) {
                    //     // With this method all non-editable objects are parsed.
                    //     // com.wiris.js.JsPluginViewer.parseElement(element) can be used in order to parse a custom DOM element.
                    //     // com.wiris.JsPluginViewer are called on page load so is not necessary to call it explicitly (I'ts called to simulate a custom render).
                    //     com.wiris.js.JsPluginViewer.parseDocument();
                    // }
                    Loading.hide();
                    if ('com' in window && 'wiris' in window.com && 'js' in window.com.wiris && 'JsPluginViewer' in window.com.wiris.js) {
                        // With this method all non-editable objects are parsed.
                        // com.wiris.js.JsPluginViewer.parseElement(element) can be used in order to parse a custom DOM element.
                        // com.wiris.JsPluginViewer are called on page load so is not necessary to call it explicitly (I'ts called to simulate a custom render).
                        com.wiris.js.JsPluginViewer.parseDocument();
                    }
                    $('#page_fade').fadeOut();

                    if(Navigation.callback != null) {
                        Navigation.callback();
                        Navigation.callback = null;
                    }

                    var _hsq = window._hsq = window._hsq || [];
                    _hsq.push(['setPath', url]);
                    _hsq.push(['trackPageView']);
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
                    // if ('com' in window && 'wiris' in window.com && 'js' in window.com.wiris && 'JsPluginViewer' in window.com.wiris.js) {
                    //     // With this method all non-editable objects are parsed.
                    //     // com.wiris.js.JsPluginViewer.parseElement(element) can be used in order to parse a custom DOM element.
                    //     // com.wiris.JsPluginViewer are called on page load so is not necessary to call it explicitly (I'ts called to simulate a custom render).
                    //     com.wiris.js.JsPluginViewer.parseDocument();
                    // }
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
    },

    home : function(){
        User.welcome();
    }
};