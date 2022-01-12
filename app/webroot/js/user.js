var User = {

    info: null,
    inactive: 0,
    surpressInactive: false,

    userLogoutInterval: null,
    secondsBeforeTeacherLogout: 300, //300 default
    logoutWarningTimer: 30,
    logoutCountdownInterval: null,

    initialise: function () {
        $.ajax({
            dataType: 'json',
            url: '/users/info',
            async: false,
            success:
                function (info) {
                    User.info = info;
                    if (User.info.guest) {
                        var guest_username = User.info.name_first + ' ' +
                            User.info.name_suffix + ' ' +
                            User.info.name;
                        $('#header #guest_user').prepend('<span>'+guest_username+'</span>');

                        $("<link/>", {
                            rel: "stylesheet",
                            type: "text/css",
                            href: "/css/buttons.green.css"
                        }).appendTo("head");

                        User.surpressInactive = true;
                    } else {

                        var activeSchool = '';
                        var activeSchoolName = '';

                        if (User.info.isTeacher && User.info.hasOwnProperty('school_location_list') && User.info.school_location_list.length > 1) {
                            var result = User.info.school_location_list.find(function (school_location) {
                                return school_location.active;
                            });
                            if (result) {
                                activeSchool = '(<span id="active_school">' + result.name + '</span>)';
                                $.i18n().locale = result.language;
                                activeSchoolName = '(' + result.name + ')';
                            }
                        }

                        if (User.info.isStudent) {
                            $("<link/>", {
                                rel: "stylesheet",
                                type: "text/css",
                                href: "/css/buttons.green.css"
                            }).appendTo("head");
                            $('#menu, #header, #tiles').addClass('green');
                        } else {
                            $("<link/>", {
                                rel: "stylesheet",
                                type: "text/css",
                                href: "/css/buttons.blue.css"
                            }).appendTo("head");
                            $('#menu, #header, #tiles').addClass('blue');
                        }

                        var username = User.info.name_first + ' ' +
                            User.info.name_suffix + ' ' +
                            User.info.name;
                        $('#header #user').html(username + ' ' + activeSchool).attr('title', username + ' ' + activeSchoolName);

                if (activeSchool) {
                    $('#header #user_school_locations').html('<a href="#" onclick="Popup.showSchoolSwitcher(User.info.school_location_list)" class="btn white mb5">'+$.i18n('Wissel van school')+'</a>');
                }

                if (User.info.isTeacher) {
                    $("#supportpage_link, #upload_test_link").remove();
                    // var cookielawConsentScript = document.createElement('script');
                    // cookielawConsentScript.setAttribute('src','https://cdn.cookielaw.org/consent/59ebfb6a-8dcb-443e-836a-329cb8623832/OtAutoBlock.js');
                    // document.head.insertBefore(cookielawConsentScript,document.head.firstChild);
                    // var cookieLawScript = document.createElement('script');
                    // cookieLawScript.setAttribute('src','https://cdn.cookielaw.org/scripttemplates/otSDKStub.js');
                    // cookieLawScript.setAttribute('charset','UTF-8');
                    // cookieLawScript.setAttribute('data-domain-script','59ebfb6a-8dcb-443e-836a-329cb8623832');
                    // document.head.insertBefore(cookieLawScript,document.head.firstChild);
                    //
                    // function OptanonWrapper() { }
                    // window.oneTrustInjected = true;

                    var hubspotScript = document.createElement('script');
                    hubspotScript.setAttribute('src','//js.hs-scripts.com/3780499.js');
                    document.head.appendChild(hubspotScript);

                }
            }
        }
        });

        $('#header #top #user').click(function () {
            if ($('#support_menu').is(':visible')) {
                $('#support_menu').slideUp();
            }
            $('#header #top #user_menu').slideDown();
            setTimeout(function () {
                $('#header #top #user_menu').slideUp();
            }, 5000);
        });

        $('#header #top #user_menu').mouseleave(function () {
            $(this).slideUp();
        });

        $('body').mouseover(function () {
            User.inactive = 0;
        });

        $('body').keydown(function () {
            User.inactive = 0;
        });

        this.startUserLogoutInterval();
    },

	clearClipboard: function () {
        //source: https://stackoverflow.com/a/30810322
		function fallbackCopyTextToClipboard(text) {
			var textArea = document.createElement("textarea");
			textArea.value = text;

			// Avoid scrolling to bottom
			textArea.style.top = "0";
			textArea.style.left = "0";
			textArea.style.position = "fixed";

			document.body.appendChild(textArea);
			textArea.focus();
			textArea.select();

			try {
				var successful = document.execCommand("copy");
				var msg = successful ? "successful" : "unsuccessful";
			} catch (err) {
			}

			document.body.removeChild(textArea);
		}
		function copyTextToClipboard(text) {
			return new Promise((resolve, reject) => {
				if (!navigator.clipboard) {
					fallbackCopyTextToClipboard(text);
					resolve();
				}
                navigator.clipboard.writeText(text).then(() => {
                    resolve();
                }).catch(() => { fallbackCopyTextToClipboard(text); resolve(); });
			});
		}

		return copyTextToClipboard("");
	},

    actOnLogout: function () {
        return new Promise(resolve => {
            $("#supportpage_link, #upload_test_link").remove();

            if (User.info.isStudent) {
                 User.clearClipboard().then(() => {
                     resolve()
                 });
            } else {
                resolve();
            }
        })
    },

    welcome: function () {
        if (!TestTake.active) {
            Navigation.load('/users/welcome');
            Menu.clearActiveMenu('dashboard');
        }
    },

    checkLogin: function () {
        if (Utils.notOnLoginScreen()) {
            $.get('/users/status',
                function (status) {
                    if (status == 1) {
                        Core.afterLogin();
                    } else if (Utils.urlContainsEduIx()) {
                        Popup.load('/users/registereduix/' + window.location.search, 800);
                    } else if (Utils.urlContainsRegisterNewTeacherSuccessful()) {
                        Popup.load('/users/register_new_teacher_successful/', 800);
                    } else if (Utils.urlContainsRegisterNewTeacher()) {
                        Popup.load('/users/register_new_teacher/', 800);
                    } else {
                        Popup.load('/users/login', 500);
                    }
                }
            );
        }
    },

    logout: function (closeApp) {
        if(typeof closeapp == 'undefined'){
            closeApp = false;
        }
        $.get('/users/logout',
            function () {
                Core.resetCache();
                User.actOnLogout().then(() => {

                        window.location.href = '/';

                    try {
                        if (typeof(electron.closeApp) === typeof(Function)) {
                            if (typeof(electron.reloadApp) === typeof(Function)) {
                                if (closeApp) {
                                    electron.closeApp();
                                } else {
                                    electron.reloadApp();
                                }
                            } else {
                                electron.closeApp();
                            }
                        }
                    } catch (error) {}
                });
            }
        );

    },

    resetPassword: function () {
        Popup.load('/users/password_reset', 400);
    },

    sendWelcomeMails: function (type) {
        Popup.message({
            btnOk: $.i18n('Ja'),
            btnCancel: $.i18n('Annuleer'),
            title: $.i18n('Weet u het zeker?'),
            message: $.i18n('Weet u zeker dat u alle nieuwe gebruikers een welkomst-email wilt versturen?')
        }, function () {
            Notify.notify($.i18n('Welkomstmails verstuurd'), 'info');

            $.get('/users/notify_welcome/' + type);
        });
    },

    delete: function (id) {

        Popup.message({
            btnOk: $.i18n('Ja'),
            btnCancel: $.i18n('Annuleer'),
            title: $.i18n('Weet u het zeker?'),
            message: $.i18n('Weet u zeker dat u deze gebruiker wilt verwijderen?')
        }, function () {
            $.ajax({
                url: '/users/delete/' + id,
                type: 'DELETE',
                success: function (response) {
                    var json_response = JSON.parse(response);
                    if(!json_response.status){
                        Notify.notify(json_response.data, 'error');
                        return;
                    }
                    Notify.notify($.i18n('Gebruiker verwijderd'), 'info');
                    Navigation.refresh();
                }
            });
        });
    },

    forgotPassword: function () {
        var email = $('#UserEmail').val();

        if (email == "") {
            Notify.notify($.i18n('Voer eerst uw emailadres in.'), 'error');
        } else {
            $.post('/users/forgot_password', {
                    'email': email
                },
                function (response) {
                    Notify.notify($.i18n('Binnen enkele minuten ontvang je een email met instructies om je wachtwoord te veranderen. Vergeet niet je spamfolder te checken als je de mail niet binnenkrijgt.'), 'info', 10000);
                });
        }
    },

    switchLocation: function (link, uuid) {
        $.getJSON('/users/setActiveSchoolLocation/' + uuid, function (data) {
            Popup.closeLast();
            User.info.school_location_list = data.data;
            var active_location = data.data.find(function (location) {
                return location.active;
            });
            User.info.school_location_id = active_location.id;

            document.getElementById('active_school').innerHTML = active_location.name;
            Notify.notify($.i18n('Gewisseld naar school ') + active_location.name);
            // disable before unload;
            window.onbeforeunload = function () {  }
            window.location.reload();
            // I would rather do refresh but this might break things.
            //Navigation.refresh();
        });
    },
    addExistingTeacherToSchoolLocation: function (uuid) {
        $.ajax({
            url: '/users/add_existing_teacher_to_schoolLocation',
            method: 'POST',
            data: {user: uuid},
            success: function (data) {
                Notify.notify($.i18n('Docent succesvol toegevoegd'));
                var selector = '#'+uuid;
                $(selector).removeClass('white').addClass('blue').find('span:first').removeClass('fa-link').addClass('fa-trash');
            }
        });
    },
    removeExistingTeacherFromSchoolLocation: function (uuid) {
        $.ajax({
            url: '/users/delete_existing_teacher_from_schoolLocation',
            type: 'DELETE',
            data: {user: uuid},
            success: function (result) {
                Notify.notify($.i18n('Docent succesvol verwijderd'));
                var selector = '#'+uuid;
                $(selector).removeClass('blue').addClass('white').find('span:first').removeClass('fa-trash').addClass('fa-link');
            }
        });
    },

    existingTeacherAction:function(uuid) {
        var element = '#'+uuid;
        if ($(element).find('span:first').hasClass('fa-trash')) {
            this.removeExistingTeacherFromSchoolLocation(uuid);
        } else {
            this.addExistingTeacherToSchoolLocation(uuid);
        }
    },

    importTeachersChooseTypePopup : function(){
        Popup.promptChooseImportTeachersType();
        this.importTeachersChooseTypeEvents();
    },

    importTeachersChooseTypeEvents : function(){
        var userObj = this;
        if(!this.teacherImportTypeEvents) {
            $(document).on("click", "#teacher_import_type_confirm", function () {
                if ($('#teacher_import_type_confirm').hasClass("disabled")) {
                    return false;
                }
                if(userObj.teacherImportType=='standard'){
                    Popup.closeLast();
                    Navigation.load('/users/import/teachers');
                }
                if(userObj.teacherImportType=='bare'){
                    Popup.closeLast();
                    Navigation.load('/users/import/teachers_bare');
                }
            });

            $(document).on("click", "#teacher_import_type_standard", function () {
                $('#teacher_import_type_standard').addClass("highlight");
                userObj.teacherImportTypeConfirmButtonActive();
                userObj.teacherImportChooseTypeInActive('teacher_import_type_bare');
                userObj.teacherImportType = 'standard';
            });

            $(document).on("click", "#teacher_import_type_bare", function () {
                $('#teacher_import_type_bare').addClass("highlight");
                userObj.teacherImportTypeConfirmButtonActive();
                userObj.teacherImportChooseTypeInActive('teacher_import_type_standard');
                userObj.teacherImportType = 'bare';
            });
            this.teacherImportTypeEvents = true;
        }
    },
    teacherImportTypeConfirmButtonActive : function(){
        $('#teacher_import_type_confirm').removeClass("disabled");
        $('#teacher_import_type_confirm').removeClass("grey");
        $('#teacher_import_type_confirm').addClass("blue");
    },

    teacherImportChooseTypeInActive : function(id){
        $('#'+id).addClass("grey");
        $('#'+id).removeClass("highlight");
    },

    postponeAutoUserLogout : function(seconds) {
        if (seconds != null) {
            User.secondsBeforeTeacherLogout = seconds*60;
        }
        this.resetPreventLogoutData();
    },

    resetPreventLogoutData : function() {
        clearInterval(User.logoutCountdownInterval);
        User.logoutWarningTimer = 30;
        this.startUserLogoutInterval();
    },

    startUserLogoutInterval : function() {
        User.userLogoutInterval = setInterval(function () {
            User.inactive++;
            // Student
            if (User.info.isStudent && User.inactive >= 900 && !User.surpressInactive) {
                Core.lostFocus();
                setTimeout(function () {
                    User.logout(false);
                }, 3000);
            }

            // Teacher
            if (User.info.isTeacher && User.inactive >= User.secondsBeforeTeacherLogout && !User.surpressInactive) {
                clearInterval(User.userLogoutInterval);
                Popup.load('/users/prevent_logout', 600);
            }

        }, 1000);
    },
    returnToLaravelLogin : function(desiredUrl) {
        $.ajax({
            url: '/users/return_to_laravel/true',
            method: 'get',
            success: function (url) {
                if (desiredUrl != null) {
                    url = desiredUrl;
                }
                url = typeof url == 'undefined' ? '/' : url;
                window.open(url, '_self');
                try {
                    electron.loadUrl(url);
                } catch(error) {}
            }
        });
    },
    connectToPusher : function (pusherKey) {
        Navigation.usingPusher = true;

        window.pusher = new Pusher(pusherKey, {
            cluster: 'eu',
            forceTLS: true,
            authEndpoint: "/users/pusher_auth"
        });
    },
    goToLaravel: function (path, autoLogout = null) {
        if(autoLogout){
            Core.stopCheckUnreadMessagesListener();
        }
        $.ajax({
            url: '/users/goToLaravelPath',
            method: 'post',
            data: {'path': path, autoLogout: autoLogout},
            success: function (url) {
                document.removeEventListener("visibilitychange", onchange);
                if (autoLogout) {
                    Core.resetCache();
                }
                url = JSON.parse(url);
                url = Core.getCorrectLaravelUrl(url.data.url);
                window.open(url, '_self');
                try {electron.loadUrl(url);} catch (error) {}
            }
        });
    }
};
