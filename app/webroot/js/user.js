var User = {

    info: null,
    inactive: 0,
    surpressInactive: false,

    initialise: function () {
        $.getJSON('/users/info',
            function (info) {
                User.info = info;
                var activeSchool = '';

                if (User.info.hasOwnProperty('school_location_list')) {
                    var result = User.info.school_location_list.find(function (school_location) {
                        return school_location.active;
                    });
                    if (result) {
                        activeSchool = '(<span id="active_school">' + result.name + '</span>)';
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

                $('#header #user').html(
                    User.info.name_first + ' ' +
                    User.info.name_suffix + ' ' +
                    User.info.name + ' ' +
                    activeSchool
                );


                if (activeSchool) {
                    $('#header #user_school_locations').html(`<a href="#" onclick="Popup.showSchoolSwitcher(User.info.school_location_list)" class="btn white mb5">Wissel van school</a>`);
                }


                if (User.info.isTeacher) {
                    $("#supportpage_link, #upload_test_link").remove();
                }
            }
        );

        $('#header #top #user').click(function () {
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

        setInterval(function () {
            User.inactive++;
            // Student
            if (User.info.isStudent && User.inactive == 900 && !User.surpressInactive) {
                TestTake.lostFocus();
                setTimeout(function () {
                    User.logout();
                }, 2000);
            }

            // Teacher
            if (User.info.isTeacher && User.inactive == 300 && !User.surpressInactive) {
                User.logout();
            }

        }, 1000);
    },

    actOnLogout: function () {
        $("#supportpage_link, #upload_test_link").remove();
    },

    welcome: function () {
        if (!TestTake.active) {
            Navigation.load('/users/welcome');
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

    logout: function () {
        $.get('/users/logout',
            function () {
                User.actOnLogout();
                window.location.href = '/';
            }
        );

    },

    resetPassword: function () {
        Popup.load('/users/password_reset', 400);
    },

    sendWelcomeMails: function (type) {
        Popup.message({
            btnOk: 'Ja',
            btnCancel: 'Annuleer',
            title: 'Weet u het zeker?',
            message: 'Weet u zeker dat u alle nieuwe gebruikers een welkomst-email wilt versturen?'
        }, function () {
            Notify.notify('Welkomstmails verstuurd', 'info');

            $.get('/users/notify_welcome/' + type);
        });
    },

    delete: function (id) {

        Popup.message({
            btnOk: 'Ja',
            btnCancel: 'Annuleer',
            title: 'Weet u het zeker?',
            message: 'Weet u zeker dat u deze gebruiker wilt verwijderen?'
        }, function () {
            $.ajax({
                url: '/users/delete/' + id,
                type: 'DELETE',
                success: function (response) {
                    Notify.notify('Gebruiker verwijderd', 'info');
                    Navigation.refresh();
                }
            });
        });
    },

    forgotPassword: function () {
        var email = $('#UserEmail').val();

        if (email == "") {
            Notify.notify('Voer eerst uw emailadres in.', 'error');
        } else {
            $.post('/users/forgot_password', {
                    'email': email
                },
                function (response) {
                    Notify.notify('Binnen enkele minuten ontvang je een email met instructies om je wachtwoord te veranderen. Vergeet niet je spamfolder te checken als je de mail niet binnenkrijgt.', 'info', 10000);
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
            Notify.notify('Gewisseld naar school ' + active_location.name);
        });
    }
};
