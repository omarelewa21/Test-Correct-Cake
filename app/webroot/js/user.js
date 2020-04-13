var User = {

    info : null,
    inactive : 0,
    surpressInactive : false,

    initialise : function() {
        $.getJSON('/users/info',
            function(info) {
                User.info = info;

                if(User.info.isStudent) {
                    $("<link/>", {
                        rel: "stylesheet",
                        type: "text/css",
                        href: "/css/buttons.green.css"
                    }).appendTo("head");
                    $('#menu, #header, #tiles').addClass('green');
                }else{
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
                    User.info.name
                );

                if(User.info.isTeacher){
                    $("#supportpage_link, #upload_test_link").remove();
                }
            }
        );

        $('#header #top #user').click(function() {
            $('#header #top #user_menu').slideDown();
            setTimeout(function() {
                $('#header #top #user_menu').slideUp();
            }, 5000);
        });

        $('#header #top #user_menu').mouseleave(function() {
            $(this).slideUp();
        });

        $('body').mouseover(function() {
            User.inactive = 0;
        });

        $('body').keydown(function() {
            User.inactive = 0;
        });

        setInterval(function() {
            User.inactive++;


            // Student
            if (User.info.isStudent && User.inactive == 900 && !User.surpressInactive) {
                TestTake.lostFocus();
                setTimeout(function() {
                    User.logout();
                }, 2000);
            }

            // Teacher
            if (User.info.isTeacher && User.inactive == 300 && !User.surpressInactive) {
                User.logout();
            }

        }, 1000);
    },

    actOnLogout : function(){
        $("#supportpage_link, #upload_test_link").remove();
    },

    welcome : function() {
        if(!TestTake.active) {
            Navigation.load('/users/welcome');
        }
    },

    checkLogin : function() {
        if(Utils.notOnLoginScreen()) {
            $.get('/users/status',
                function (status) {
                    if (status == 1) {
                        Core.afterLogin();
                    } else {
                        Popup.load('/users/login', 500);
                    }
                }
            );
        }
    },

    logout : function() {
            $.get('/users/logout',
                function () {
                    User.actOnLogout();
                    window.location.href = '/';
                }
            );

    },

    resetPassword : function() {
        Popup.load('/users/password_reset', 400);
    },

    sendWelcomeMails : function(type) {
        Popup.message({
            btnOk: 'Ja',
            btnCancel: 'Annuleer',
            title: 'Weet u het zeker?',
            message: 'Weet u zeker dat u alle nieuwe gebruikers een welkomst-email wilt versturen?'
        }, function() {
            Notify.notify('Welkomstmails verstuurd', 'info');

            $.get('/users/notify_welcome/' + type);
        });
    },

    delete : function(id) {

        Popup.message({
            btnOk: 'Ja',
            btnCancel: 'Annuleer',
            title: 'Weet u het zeker?',
            message: 'Weet u zeker dat u deze gebruiker wilt verwijderen?'
        }, function() {
            $.get('/users/delete/' + id,
                function() {
                    Notify.notify('Gebruiker verwijderd', 'info');
                    Navigation.refresh();
                }
            );
        });
    },

    forgotPassword : function() {
        var email = $('#UserEmail').val();

        if(email == "") {
            Notify.notify('Voer eerst uw emailadres in.', 'error');
        }else{
            $.post('/users/forgot_password', {
                'email' : email
            },
            function(response) {
                Notify.notify('Er is een email naar je toegestuurd met instructies.', 'info');
            });
        }
    }
};