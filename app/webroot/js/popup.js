var Popup = {
    zIndex: 120,
    index: 0,
    callback: null,
    timeout: null,
    cancelCallback: null,
    debounceTime: 0,
    debounceLocktimeInMiliseconds: 750,
    shouldCloseWithIndex: false,

    setDebounceLock: function () {

        this.debounceTime = new Date().getTime();

    },

    hasDebounceLock: function(){
        var now = new Date().getTime();

        if (this.debounceTime > now - this.debounceLocktimeInMiliseconds) {
            return true;
        }
        return false;
    },

    load: function (url, width) {

        if(this.hasDebounceLock()){
            return;
        }
        this.setDebounceLock();

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
        if(this.hasDebounceLock()){
            return;
        }
        this.setDebounceLock();

        this.messageWithPreventDefault({
            'btnOk': 'Ok',
            'title': action ? action : $.i18n('gefeliciteerd'),
            'message': '<img style="display:block; margin:auto; width:200px; height:200px;" src="/img/logo_1.png">',
        }
        );

    },

    promptCallBack: function () {
        Popup.closeLast();
        return Popup.callbackFromPrompt(jQuery('#prompt').val());
    },

    prompt: function (options, callback) {

        if(this.hasDebounceLock()){
            return;
        }
        this.setDebounceLock();

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
                '<a href="#" class="btn mt5 mr5 grey pull-right" onclick="Popup.closeLast(); return null">' + $.i18n('Annuleren') + '</a>' +
                '<a href="#" class="btn  mt5 mr5 blue pull-right" onclick="Popup.promptCallBack()">' + $.i18n('Opslaan') + '</a>' +
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
        if(this.hasDebounceLock()){
            return;
        }
        this.setDebounceLock();

        $('#container, #background, #header').addClass('blurred');

        Popup.index++;
        Popup.zIndex += 2;
        Popup.callbackFromPrompt = callback;

        var htmlBlock = '<div class="popup" id="popup_' + Popup.index + '">' +
                '<div class="popup-head">' + $.i18n('Toets innemen') + '</div>' +
                '<div class="popup-content">' +
                $.i18n('Wilt u de toets volledig afsluiten of de studenten met tijdsdispensatie extra tijd geven?<br/>Selecteer de gewenste optie.') +
                '</div>' +
                '<div class="popup-footer">' +
                '<div id="test_close_non_dispensation" class="btn grey pull-left mr10 mb10" style="margin-left:5px;display:block;width: 235px;word-break: keep-all; text-align: center; height: 70px;;cursor:pointer">' +
                $.i18n('Alleen afsluiten voor de studenten zonder tijdsdispensatie.') +
                '</div>' +
                '<div id="test_close_all" class="btn grey pull-right mr10 mb10" style="margin-right:5px;display:block;width: 235px;word-break: keep-all; text-align: center; height: 70px;;cursor:pointer">' +
                $.i18n('Volledig afsluiten, ook voor de studenten met tijdsdispensatie.') +
                ' </div>' +
                '<a href="#" id="test_close_confirm" class="btn mt5 mr5 grey pull-right disabled" onclick="">' + $.i18n('Bevestigen') + '</a> <a href="#" class="btn mt5 mr5 grey pull-right" onclick="Popup.closeLast(); return null">' +  $.i18n('Annuleren') + '</a>' +
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
        if(this.hasDebounceLock()){
            return;
        }
        this.setDebounceLock();

        $('#container, #background, #header').addClass('blurred');

        Popup.index++;
        Popup.zIndex += 2;
        Popup.callbackFromPrompt = callback;

        var htmlBlock = '<div class="popup" id="popup_' + Popup.index + '">' +
                '<div class="popup-head">' + $.i18n('Vraaggroep type kiezen') + '</div>' +
                '<div class="popup-content">' +
                $.i18n('Wilt u een standaard vraaggroep maken of een carrousel vraaggroep maken? Selecteer een type.') +
                '</div>' +
                '<div class="popup-footer">' +
                '<div id="groupquestion_type_standard" class="btn grey pull-left mr10 mb10" style="margin-left:5px;display:block;width: 235px;word-break: keep-all; text-align: center; height: 100px;;cursor:pointer">' +
                '<h4 class="mt1 mb2">' + $.i18n('Standaard') + '</h4>' +
                '<div>'+
                $.i18n('In een standaard vraaggroep worden alle vragen uit de vraaggroep gesteld aan de student.')+
                '</div>'+
                '</div>' +
                '<div id="groupquestion_type_carousel" class="btn grey pull-right mr10 mb10" style="margin-right:5px;display:block;width: 235px;word-break: keep-all; text-align: center; height: 100px;;cursor:pointer">' +
                '<h4 class="mt1 mb2">'+ $.i18n('Carrousel') + '</h4>' +
                '<div>'+
                $.i18n('In een carrousel vraaggroep worden een aantal vragen uit de vraaggroep willekeurig gesteld aan de student.')+
                '</div>'+
                ' </div>' +
                '<a href="#" id="groupquestion_type_confirm" class="btn mt5 mr5 grey pull-right disabled" onclick="">' + $.i18n('Bevestigen') + '</a> <a href="#" class="btn mt5 mr5 grey pull-right" onclick="Popup.closeLast(); return null">' + $.i18n('Annuleren') + '</a>' +
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
    promptChooseImportTeachersType: function(options, callback){
        if(this.hasDebounceLock()){
            return;
        }
        this.setDebounceLock();

        $('#container, #background, #header').addClass('blurred');

        Popup.index++;
        Popup.zIndex += 2;
        Popup.callbackFromPrompt = callback;

        var htmlBlock = '<div class="popup" id="popup_' + Popup.index + '">' +
            '<div class="popup-head">' + $.i18n('Docent import type kiezen')  + '</div>' +
            '<div class="popup-content">' +
            $.i18n('Wat wilt u importeren?') +
            '</div>' +
            '<div class="popup-footer">' +
            '<div id="teacher_import_type_standard" class="btn grey pull-left mr10 mb10" style="margin-left:5px;display:block;width: 235px;word-break: keep-all; text-align: center; height: 100px;;cursor:pointer">' +
            '<h4 class="mt1 mb2">' + $.i18n('Docentaccounts met klaskoppeling') + '</h4>' +
            '<div>'+
            $.i18n('Bij deze keuze dienen er ook klasnamen in de import te staan')+
            '</div>'+
            '</div>' +
            '<div id="teacher_import_type_bare" class="btn grey pull-right mr10 mb10" style="margin-right:5px;display:block;width: 235px;word-break: keep-all; text-align: center; height: 100px;;cursor:pointer">' +
            '<h4 class="mt1 mb2">Docentaccounts</h4>' +
            '<div>'+
            $.i18n('Deze import dient ter voorbereiding op een RTTI import')+
            '</div>'+
            ' </div>' +
            '<a href="#" id="teacher_import_type_confirm" class="btn mt5 mr5 grey pull-right disabled" onclick="">' + $.i18n('Bevestigen') + '</a> <a href="#" class="btn mt5 mr5 grey pull-right" onclick="Popup.closeLast(); return null">' + $.i18n('Annuleren') +'</a>' +
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

        if(this.hasDebounceLock()){
            return;
        }
        this.setDebounceLock();

        $('#container, #background, #header').addClass('blurred');

        Popup.index++;
        Popup.zIndex += 2;
        Popup.callbackFromConfirm = callback;

        var okBtn = options.okBtn ? options.okBtn: 'OK';

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
                '<a href="#" class="btn red pull-right mr5 mt5 " onclick="Popup.confirmCallBack(true)">'+ okBtn +'</a>' +
                '<a href="#" class="btn grey pull-right mr5 mt5" onclick="Popup.closeLast();">' + $.i18n('Annuleren') + '</a>' +
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

        $('#container, #background, #header').removeClass('blurred');
    },

    showSearch: function () {

        if(this.hasDebounceLock()){
            return;
        }
        this.setDebounceLock();

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

    closeWithIndex: function (index) {
        $('#fade').fadeOut();
        $('#popup_' + index).stop().removeClass('center').fadeOut(function () {
                $(this).remove();
        });

        $('#container, #background, #header').removeClass('blurred');
    },

    closeWithNewPopup: function (url,width) {
        if(typeof width == "undefined"){
            width = 600;
        }
        $('#fade').fadeOut();
        $('#popup_' + Popup.index).stop().removeClass('center').hide();
        $('#popup_' + Popup.index).remove();
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
        Popup.load(url, width);
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
        if(this.hasDebounceLock()){
            return;
        }
        this.setDebounceLock();

        var index = Popup.index;

        if (Popup.callback != null) {
            Popup.callback();
            Popup.callback = null;
        }
        if (Popup.shouldCloseWithIndex) {
            Popup.closeWithIndex(index);
            Popup.shouldCloseWithIndex = false;
        } else {
            Popup.closeLast();
        }
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
            settings.btnOk = $.i18n('Oke');
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

        this.message({btnOk: $.i18n('Annuleren'), title: $.i18n('Wissel van school'), message: schoolLocationsTemplate});
    },

    showPreviewTest: function (testId) {
        var url;
        var liveUrl = window.location.protocol+'//'+window.location.host.replace('portal.test-','welcome.test-');
        var windowReference = window.open(liveUrl);
        $.ajax({
            type: 'post',
            url: '/tests/get_preview_url/' + testId,
            dataType: 'json',
            data: {},
            success: function (data) {
                url = Core.getCorrectLaravelUrl(data.data.url);
                windowReference.location = url;
                windowReference.focus();
            }
        });
    },

    showExternalPage: function(path, width, height) {
        var pWidth = typeof width !== 'undefined' ? width : 800 ;
        var pHeight = typeof height !== 'undefined' ? height : 500 ;
        var html = '<i class="fa fa-times" title="Sluiten" onClick="Popup.closeLast();" style="position:absolute;right:6px;top:6px;"></i>' +
                    '<iframe id="PopupIframe" style="display:flex;border:0;padding:0;margin:0 ;height:' + pHeight + 'px;" width="100%" src="' + path + '"></iframe>';
        Popup.show(html , pWidth);
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


