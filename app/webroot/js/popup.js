var Popup = {
    zIndex : 120,
    index : 0,
    callback : null,
    timeout : null,


    load : function(url, width) {

        $('.dropblock').slideUp();
        $('.dropblock-owner').removeClass('active');
        User.inactive = 0;
        $.get(url,
            function(html) {
                Popup.show(html, width);
            }
        );
    },

    show : function(html, width) {

        $('#container, #background, #header').addClass('blurred');

        Popup.index++;
        Popup.zIndex += 2;

        var htmlBlock = "<div class='popup' id='popup_" + Popup.index + "'>" + html + "</div>";

        $('body').append(htmlBlock);

        $('#fade').css({
            'zIndex' : (Popup.zIndex - 1)
        }).fadeIn();

        if(width == undefined) {
            width = 600;
        }

        var height = $('#popup_' + Popup.index).height();

        $('#popup_' + Popup.index).css({
            'margin-left' : (0 - (width / 2)) + 'px',
            'margin-top' : (0 - (height / 2)) + 'px',
            'width' : width + 'px',
            'zIndex' : Popup.zIndex
        }).fadeIn(function(){
            $(this).addClass('center');
        });
    },

    closeLast : function() {

        $('#fade').fadeOut();

        if(Popup.index === 1 ) {
            $('#popup_' + Popup.index).stop().removeClass('center').fadeOut(function() {
                $(this).remove();
            });
            Popup.index = 0;
            $('#container, #background, #header').removeClass('blurred');
        }else{
            $('#popup_' + Popup.index).stop().removeClass('center').fadeOut(function() {
                $(this).remove();
            });
            Popup.index--;
            Popup.zIndex -= 2;
            $('#fade').css({
                'zIndex' : (Popup.zIndex - 1)
            });
        }
    },

    messageOk : function() {

        if(Popup.callback != null) {
            Popup.callback();
            Popup.callback = null;
        }

        Popup.closeLast();
    },

    message : function(settings, callback) {
        if(settings.btnOk == undefined) {
            settings.btnOk = 'Oke';
        }

        if(callback != undefined) {
            Popup.callback = callback;
        }else{
            Popup.callback = null;
        }

        var btnBlock = "<a href='#' onclick='Popup.messageOk();' class='btn highlight mt5 mr5 pull-right'>" + settings.btnOk + "</a>";

        if(settings.btnCancel != undefined) {
            btnBlock += "<a href='#' onclick='Popup.closeLast();' class='btn grey mt5 mr5 pull-right'>" + settings.btnCancel + "</a>";
        }

        var htmlBlock = "<div class='popup-head'>" + settings.title + "</div>";
        htmlBlock += "<div class='popup-content'>" + settings.message + "</div>";
        htmlBlock += "<div class='popup-footer'>" + btnBlock + "</div>";

        Popup.show(htmlBlock, 500);
    },

    calcPosition : function() {
        $.each($('.popup.center'),
            function() {
                var popH = $(this).height();
                var winH = $(window).height();

                $(this).animate({
                    'margin-top' : (0 - (popH / 2)) + 'px'
                });

                $('.popup-content').css({
                    'max-height' : (winH - 200) + 'px'
                });
            }
        );
    }
};