var Popup = {
    zIndex: 120,
    index: 0,
    callback: null,
    timeout: null,
    cancelCallback: null,
    debounceTime: 0,

    debounce: function () {

        var now = new Date().getTime();

        if (this.debounceTime > now - 2000) {
            return false;
        }

        this.debounceTime = now;

        return true;
    },

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
            'title': action ? action : 'gefeliciteerd',
            'message': '<img style="display:block; margin:auto; width:200px; height:200px;" src="/img/logo_1.png">',
        }
        );

    },

    promptCallBack: function () {
        Popup.closeLast();
        return Popup.callbackFromPrompt(jQuery('#prompt').val());
    },

    prompt: function (options, callback) {

        this.debounce();

        $('#container, #background, #header').addClass('blurred');

        Popup.index++;
        Popup.zIndex += 2;
        Popup.callbackFromPrompt = callback;

        var htmlBlock = '<div class="popup" id="popup_' + Popup.index + '">' +
                '<div class="popup-head">' + options.title + '</div>' +
                '<div class="popup-content">' +
                '<form>' +
                '<div class="form-group">' +
                '<label for="prompt">' + options.text + '</label>' +
                '<input type="email" class="form-control" id="prompt" placeholder="' + options.placeholder + '" value="' + options.inputValue + '">' +
                '</div>' +
                '</form>' +
                '</div>' +
                '<div class="popup-footer">' +
                '<a href="#" class="btn mt5 mr5 grey pull-right" onclick="Popup.closeLast(); return null">Annuleren</a>' +
                '<a href="#" class="btn  mt5 mr5 blue pull-right" onclick="Popup.promptCallBack()">Opslaan</a>' +
                '</div>' +
                '</div>'
                ;

        $('body').append(htmlBlock);

        $('#fade').css({
            'zIndex': (Popup.zIndex - 1)
        }).fadeIn();


        var width = 600;

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

    promptDispensation: function (options, callback) {
        $('#container, #background, #header').addClass('blurred');

        Popup.index++;
        Popup.zIndex += 2;
        Popup.callbackFromPrompt = callback;

        var htmlBlock = '<div class="popup" id="popup_' + Popup.index + '">' +
                '<div class="popup-head">Toets innemen</div>' +
                '<div class="popup-content">' +
                'Wilt u de toets volledig afsluiten of de studenten met tijdsdispensatie extra tijd geven?<br/>Selecteer de gewenste optie.' +
                '</div>' +
                '<div class="popup-footer">' +
                '<div id="test_close_non_dispensation" class="btn grey pull-left mr10 mb10" style="margin-left:5px;display:block;width: 235px;word-break: keep-all; text-align: center; height: 70px;;cursor:pointer">' +
                'Alleen afsluiten voor de studenten zonder tijdsdispensatie.' +
                '</div>' +
                '<div id="test_close_all" class="btn grey pull-right mr10 mb10" style="margin-right:5px;display:block;width: 235px;word-break: keep-all; text-align: center; height: 70px;;cursor:pointer">' +
                'Volledig afsluiten, ook voor de studenten met tijdsdispensatie.' +
                ' </div>' +
                '<a href="#" id="test_close_confirm" class="btn mt5 mr5 grey pull-right disabled" onclick="">Bevestigen</a> <a href="#" class="btn mt5 mr5 grey pull-right" onclick="Popup.closeLast(); return null">Annuleren</a>' +
                '</div>' +
                '</div>'
                ;


        $('body').append(htmlBlock);

        $('#fade').css({
            'zIndex': (Popup.zIndex - 1)
        }).fadeIn();

        var width = 550;

        var height = $('#popup_' + Popup.index).height();

        $('#popup_' + Popup.index).css({
            'margin-left': (0 - (width / 2)) + 'px',
            'margin-top': (0 - (height / 2)) + 'px',
            'width': width + 'px',
            'height': 300 + 'px',
            'zIndex': Popup.zIndex
        }).fadeIn(function () {
            $(this).addClass('center');
        });

    },
    promptChooseGroupQuestionType: function (options, callback) {
        $('#container, #background, #header').addClass('blurred');

        Popup.index++;
        Popup.zIndex += 2;
        Popup.callbackFromPrompt = callback;

        var htmlBlock = '<div class="popup" id="popup_' + Popup.index + '">' +
                '<div class="popup-head">Vraaggroep type kiezen</div>' +
                '<div class="popup-content">' +
                'Wilt u een standaard vraaggroep maken of een carrousel vraaggroep maken? Selecteer een type.' +
                '</div>' +
                '<div class="popup-footer">' +
                '<div id="groupquestion_type_standard" class="btn grey pull-left mr10 mb10" style="margin-left:5px;display:block;width: 235px;word-break: keep-all; text-align: center; height: 100px;;cursor:pointer">' +
                '<h4 class="mt1 mb2">Standaard</h4>' +
                '<div>'+
                'In een standaard vraaggroep worden alle vragen uit de vraaggroep gesteld aan de student.'+
                '</div>'+
                '</div>' +
                '<div id="groupquestion_type_carousel" class="btn grey pull-right mr10 mb10" style="margin-right:5px;display:block;width: 235px;word-break: keep-all; text-align: center; height: 100px;;cursor:pointer">' +
                '<h4 class="mt1 mb2">Carrousel</h4>' +
                '<div>'+
                'In een carrousel vraaggroep worden een aantal vragen uit de vraaggroep willekeurig gesteld aan de student.'+
                '</div>'+
                ' </div>' +
                '<a href="#" id="groupquestion_type_confirm" class="btn mt5 mr5 grey pull-right disabled" onclick="">Bevestigen</a> <a href="#" class="btn mt5 mr5 grey pull-right" onclick="Popup.closeLast(); return null">Annuleren</a>' +
                '</div>' +
                '</div>'
                ;


        $('body').append(htmlBlock);

        $('#fade').css({
            'zIndex': (Popup.zIndex - 1)
        }).fadeIn();

        var width = 550;

        var height = $('#popup_' + Popup.index).height();

        $('#popup_' + Popup.index).css({
            'margin-left': (0 - (width / 2)) + 'px',
            'margin-top': (0 - (height / 2)) + 'px',
            'width': width + 'px',
            'height': 300 + 'px',
            'zIndex': Popup.zIndex
        }).fadeIn(function () {
            $(this).addClass('center');
        });

    },
    confirmCallBack: function (value) {
        Popup.closeLast();
        return Popup.callbackFromConfirm(value);
    },
    confirm: function (options, callback) {

        this.debounce();

        $('#container, #background, #header').addClass('blurred');

        Popup.index++;
        Popup.zIndex += 2;
        Popup.callbackFromConfirm = callback;

        var htmlBlock = '<div class="popup" id="popup_' + Popup.index + '">' +
                '<div class="popup-head">' + options.title + '</div>' +
                '<div class="popup-content">' +
                '<form>' +
                '<div class="form-group">' +
                '<label for="prompt">' + options.text + '</label>' +
                '</div>' +
                '</form>' +
                '</div>' +
                '<div class="popup-footer">' +
                '<a href="#" class="btn red pull-right mr5 mt5 " onclick="Popup.confirmCallBack(true)">OK</a>' +
                '<a href="#" class="btn grey pull-right mr5 mt5" onclick="Popup.closeLast();">Annuleren</a>' +
                '</div>' +
                '</div>';

        $('body').append(htmlBlock);

        $('#fade').css({
            'zIndex': (Popup.zIndex - 1)
        }).fadeIn();

        var width = 600;

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

    closeSearch: function () {
        $('#fade').fadeOut();

        $('#popup_search').stop().removeClass('center').fadeOut(function () {
            $(this).hide();
        });
        Popup.index = 0;
        $('#container, #background, #header').removeClass('blurred');
    },

    showSearch: function () {

        this.debounce();

        $('#container, #background, #header').addClass('blurred');

        Popup.zIndex += 2;

        $('#popup_search').show();
        $('#fade').css({
            'zIndex': (Popup.zIndex - 1)
        }).fadeIn();

        var width = 800;
        var height = $('#popup_search').height();

        $('#popup_search').css({
            'margin-left': (0 - (width / 2)) + 'px',
            'margin-top': (0 - (height / 2)) + 'px',
            'width': width + 'px',
            'zIndex': Popup.zIndex
        }).fadeIn(function () {
            $(this).addClass('center');
        });
    },

    show: function (html, width) {

        this.debounce();

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

    closeWithNewPopup: function (url) {

        $('#fade').fadeOut();
        $('#popup_' + Popup.index).stop().removeClass('center').fadeOut(function () {
            $(this).remove();
        });
        $('#container, #background, #header').removeClass('blurred');
        if (Popup.index === 1) {
            Popup.index = 0;
        } else {
            Popup.index--;
            Popup.zIndex -= 2;
        }
        $('#fade').css({
            'zIndex': (Popup.zIndex - 1)
        });
        Popup.load(url, 600); 
        return false;
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
    },

    showSchoolSwitcher: function (locations) {
        schoolLocationsTemplate = '';
        locations.forEach(function (schoolLocation, index) {
            var activeClass = schoolLocation.active ? 'blue' : 'white';
            schoolLocationsTemplate += '<a href="#" onclick="User.switchLocation(this, \'' + schoolLocation.uuid + '\');" class="btn hover-blue ' + activeClass + ' mb5">' + schoolLocation.name + '</a>';
        });

        this.message({btnOk: 'Annuleren', title: 'Wissel van school', message: schoolLocationsTemplate});
    }
};
// // overload of window.prompt to always show a descently formatted prompt box.
// function prompt(message, value, callback)
// {
//     var options =
//         {
//             title: "title",
//             text: message,
//             type: "input",
//             showCancelButton: true,
//             inputValue: value
//         }
//
//     if(typeof(message) === "object")
//     {
//         options = message;
//     }
//
//     return Popup.prompt(options, function(inputValue) {
//         return callback ? callback(inputValue) : inputValue
//     });
// }
//
// function confirm(message, callback)
// {
//     callback = callback || function(){}
//     var options =
//         {
//             title: "Weet je t zeker?",
//             text: message,
//             type: "warning",
//             showCancelButton: true
//         }
//
//     if(typeof(message) === "object")
//     {
//         options = message
//     }
//
//     Popup.confirm(options, function(isConfirm)
//     {
//         return callback(isConfirm)
//     });
// }


