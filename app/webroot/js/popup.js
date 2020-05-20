var Popup = {
    zIndex: 120,
    index: 0,
    callback: null,
    timeout: null,
    cancelCallback: null,


    load: function (url, width) {

        $('.dropblock').slideUp();
        $('.dropblock-owner').removeClass('active');
        User.inactive = 0;
        $.get(url,
            function (html) {
                Popup.show(html, width);
            }
        );
    },


    showCongrats: function (action) {
        this.messageWithPreventDefault({
                'btnOk': 'Ok',
                'title': action? action : 'gefeliciteerd',
                'message': '<img style="display:block; margin:auto; width:200px; height:200px;" src="/img/logo_1.png">',
            }
        );

    },

    show: function (html, width) {

        $('#container, #background, #header').addClass('blurred');

        Popup.index++;
        Popup.zIndex += 2;

        var htmlBlock = "<div class='popup' id='popup_" + Popup.index + "'>" + html + "</div>";

        $('body').append(htmlBlock);

        $('#fade').css({
            'zIndex': (Popup.zIndex - 1)
        }).fadeIn();

        if (width == undefined) {
            width = 600;
        }

        var height = $('#popup_' + Popup.index).height();

        $('#popup_' + Popup.index).css({
            'margin-left': (0 - (width / 2)) + 'px',
            'margin-top': (0 - (height / 2)) + 'px',
            'width': width + 'px',
            'zIndex': Popup.zIndex
        }).fadeIn(function () {
            $(this).addClass('center');
        });
    },

    closeLast: function () {

        $('#fade').fadeOut();

        if (Popup.index === 1) {
            $('#popup_' + Popup.index).stop().removeClass('center').fadeOut(function () {
                $(this).remove();
            });
            Popup.index = 0;
            $('#container, #background, #header').removeClass('blurred');
        } else {
            $('#popup_' + Popup.index).stop().removeClass('center').fadeOut(function () {
                $(this).remove();
            });
            Popup.index--;
            Popup.zIndex -= 2;
            $('#fade').css({
                'zIndex': (Popup.zIndex - 1)
            });
        }
    },

    messageCancel: function () {
        if (Popup.cancelCallback != null) {
            Popup.cancelCallback();
            Popup.cancelCallback = null;
        }

        Popup.closeLast();
    },

    messageOk: function () {

        if (Popup.callback != null) {
            Popup.callback();
            Popup.callback = null;
        }

        Popup.closeLast();
    },

    message: function (settings, callback, cancelCallback) {
        if (settings.btnOk == undefined) {
            settings.btnOk = 'Oke';
        }

        if (cancelCallback != undefined) {
            Popup.cancelCallback = cancelCallback;
        } else {
            Popup.cancelCallback = null;
        }

        if (callback != undefined) {
            Popup.callback = callback;
        } else {
            Popup.callback = null;
        }

        var btnBlock = "<a href='#' onclick='Popup.messageOk();' class='btn highlight mt5 mr5 pull-right'>" + settings.btnOk + "</a>";

        if (settings.btnCancel != undefined) {
            btnBlock += "<a href='#' onclick='Popup.messageCancel();' class='btn grey mt5 mr5 pull-right'>" + settings.btnCancel + "</a>";
        }

        var htmlBlock = "<div class='popup-head'>" + settings.title + "</div>";
        htmlBlock += "<div class='popup-content'>" + settings.message + "</div>";
        htmlBlock += "<div class='popup-footer'>" + btnBlock + "</div>";

        Popup.show(htmlBlock, 500);
    },

    messageWithPreventDefault: function (settings, callback, cancelCallback) {
        if (settings.btnOk == undefined) {
            settings.btnOk = 'Oke';
        }

        if (cancelCallback != undefined) {
            Popup.cancelCallback = cancelCallback;
        } else {
            Popup.cancelCallback = null;
        }

        if (callback != undefined) {
            Popup.callback = callback;
        } else {
            Popup.callback = null;
        }

        var btnBlock = "<a href='#0' onclick='Popup.messageOk();' class='btn highlight mt5 mr5 pull-right'>" + settings.btnOk + "</a>";

        if (settings.btnCancel != undefined) {
            btnBlock += "<a href='#0' onclick='Popup.messageCancel();' class='btn grey mt5 mr5 pull-right'>" + settings.btnCancel + "</a>";
        }

        var htmlBlock = "<div class='popup-head extra-padding'>" + settings.title + "</div>";
        htmlBlock += "<div class='popup-content'>" + settings.message + "</div>";
        htmlBlock += "<div class='popup-footer'>" + btnBlock + "</div>";

        Popup.show(htmlBlock, 500);
    },

    calcPosition: function () {
        $.each($('.popup.center'),
            function () {
                var popH = $(this).height();
                var winH = $(window).height();

                $(this).animate({
                    'margin-top': (0 - (popH / 2)) + 'px'
                });

                $('.popup-content').css({
                    'max-height': (winH - 200) + 'px'
                });
            }
        );
    }
};