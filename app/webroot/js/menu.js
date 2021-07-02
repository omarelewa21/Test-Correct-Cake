var Menu = {

    menuTmp: null,
    menu: null,
    tile: null,

    initialise: function () {

        $('#menu').load('/users/menu', function () {
            // $('#tiles').hide();
            $('#header #menu .item').mouseover(function () {

                Menu.menuTmp = $(this).attr('id');

                // Highlight menu
                $('#header #menu .item').removeClass('active');


                $('#tiles .tile').hide();
                if ($('#tiles .tile[menu=' + Menu.menuTmp + ']').length > 0) {

                    $(this).addClass('active');
                    $('#tiles .tile[menu=' + Menu.menuTmp + ']').show();

                    clearTimeout(window.menuTimer);

                    $('#tiles').show();
                    $('#tiles').stop().animate({
                        'top': '100px'
                    });
                } else {
                    if (!$(this).find('.withActiveHover')) {
                        $(this).addClass('noItemHover');
                    }
                }
            }).mouseout(function () {
                window.menuTimer = setTimeout(function () {
                    Menu.hideTiles();

                }, 500);
            });
            $(document).ready(function () {
                Menu.addDashboardToMenu();
                Menu.addActionIconsToHeader();
            });
        });

        $('#tiles').load('/users/tiles', function () {
            $('#tiles .tile').hide();
            $('#tiles').mouseover(function () {
                clearTimeout(window.menuTimer);
                $(this).stop().animate({
                    'top': '100'
                });
            }).mouseout(function () {
                window.menuTimer = setTimeout(function () {
                    Menu.hideTiles();
                }, 500);
            });

            $('#tiles .tile').click(function () {
                var type = null;
                var pWidth = 800;
                if ($(this)[0].hasAttribute("type")) {
                    type = $(this).attr('type');
                }
                if ($(this)[0].hasAttribute("pwidth") && $(this).attr("pwidth") !== '') {
                    pWidth = $(this).attr('pwidth');
                }
                if (type === 'externallink') {
                    window.open($(this).attr('path'), '_blank');
                    return false;
                } else if (type === 'popup') {
                    Popup.load($(this).attr('path'), pWidth);
                    return false;
                } else if (type === 'externalpopup') {
                    Popup.showExternalPage($(this).attr('path'), pWidth);
                    return false;
                } else if (type === 'download') {
                    window.location.href = $(this).attr('path');
                    return false;
                }
                $(this).addClass('active');
                Menu.menu = Menu.menuTmp;
                Menu.tile = $(this).attr('id');

                Menu.hideTiles();

                Navigation.load($(this).attr('path'));
            });
        });
    },

    hideTiles: function () {
        $('#tiles .tile').hide();
        $('#tiles .tile[menu=' + Menu.menu + ']').show();
        $('#menu .item').removeClass('active');

        if(!$('#tiles .tile').is(':visible')) {
                $('#tiles').slideUp();
        }
        // $('#tiles').stop().animate({
        //     'top': '-20px'
        // },function(){
        //     $('#tiles').hide();
        // });

        Menu.setHighlights();
    },

    setHighlights: function () {
        $('#tiles .tile').removeClass('active');
        if (Menu.tile !== '') {
            $('#tiles #' + Menu.tile).addClass('active');
        }
        $('#menu #' + Menu.menu).addClass('active');
    },

    dashboardButtonAction: function () {
        Navigation.home();
        Menu.clearActiveMenu('dashboard');
    },

    clearActiveMenu: function(placeholder) {
        Menu.menu = typeof placeholder !== 'undefined' ? placeholder : 'empty';
        Menu.tile = '';
        $('#tiles .tile').removeClass('active');
    },

    addDashboardToMenu: function () {
        $("<div id='dashboard' class='item' onclick='Menu.dashboardButtonAction()'>Dashboard</div>").prependTo("#menu");
        Menu.hideTiles();
    },

    addActionIconsToHeader: function () {
        var support =   '<div class="menu_support_icon">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24">'+
                            '    <g fill="none" fill-rule="evenodd">'+
                            '        <g>'+
                            '            <g>'+
                            '                <g>'+
                            '                    <g>'+
                            '                        <path stroke="currentColor" d="M11.705 8.865c0-3.137-2.543-5.68-5.68-5.68-3.137 0-5.68 2.543-5.68 5.68" transform="translate(-1174.000000, -18.000000) translate(24.000000, 10.000000) translate(1150.250000, 0.000000) translate(-0.000000, 6.250000) translate(6.024586, 6.024586) rotate(-45.000000) translate(-6.024586, -6.024586)"/>'+
                            '                        <path stroke="currentColor" d="M27.096 8.865c0-3.137-2.543-5.68-5.68-5.68-3.137 0-5.68 2.543-5.68 5.68" transform="translate(-1174.000000, -18.000000) translate(24.000000, 10.000000) translate(1150.250000, 0.000000) translate(-0.000000, 6.250000) translate(21.415647, 6.024586) scale(-1, 1) rotate(-45.000000) translate(-21.415647, -6.024586)"/>'+
                            '                        <path stroke="currentColor" d="M11.705 24.256c0-3.137-2.543-5.68-5.68-5.68-3.137 0-5.68 2.543-5.68 5.68" transform="translate(-1174.000000, -18.000000) translate(24.000000, 10.000000) translate(1150.250000, 0.000000) translate(-0.000000, 6.250000) translate(6.024586, 21.415647) scale(1, -1) rotate(-45.000000) translate(-6.024586, -21.415647)"/>'+
                            '                        <path stroke="currentColor" d="M27.096 24.256c0-3.137-2.543-5.68-5.68-5.68-3.137 0-5.68 2.543-5.68 5.68" transform="translate(-1174.000000, -18.000000) translate(24.000000, 10.000000) translate(1150.250000, 0.000000) translate(-0.000000, 6.250000) translate(21.415647, 21.415647) scale(-1, -1) rotate(-45.000000) translate(-21.415647, -21.415647)"/>'+
                            '                        <path fill="currentColor" d="M16.165 18.128l3.604 3.605c-.66.498-1.39.92-2.178 1.247-3.228 1.344-6.88.892-9.66-1.098l3.633-3.635c1.266.615 2.76.68 4.107.119.17-.07.335-.15.494-.238zM5.765 7.727l3.607 3.608c-.09.161-.17.33-.242.503-.603 1.457-.475 3.085.288 4.41l-3.596 3.597c-2.16-2.807-2.693-6.588-1.313-9.921.329-.795.753-1.531 1.255-2.197zm17.983 5.997c.006 2.175-.694 4.236-1.922 5.92l-3.622-3.622c.354-.693.548-1.473.546-2.284-.003-.78-.184-1.516-.504-2.173l3.633-3.63c1.17 1.631 1.861 3.63 1.87 5.79zm-9.995-9.976c2.292.005 4.403.779 6.088 2.078L16.247 9.42c-.734-.425-1.586-.668-2.495-.67-.807 0-1.583.194-2.274.546L7.853 5.67c1.678-1.224 3.732-1.924 5.9-1.923z" transform="translate(-1174.000000, -18.000000) translate(24.000000, 10.000000) translate(1150.250000, 0.000000) translate(-0.000000, 6.250000)"/>'+
                            '                        <path stroke="currentColor" d="M13.753 3.748c5.508.01 9.974 4.468 9.995 9.976.01 4.047-2.42 7.701-6.157 9.256-3.737 1.556-8.042.705-10.907-2.154C3.82 17.967 2.961 13.663 4.51 9.924c1.548-3.74 5.197-6.177 9.244-6.176zm0 5.002c-2.024 0-3.849 1.218-4.623 3.088s-.344 4.021 1.088 5.45c1.432 1.43 3.585 1.856 5.453 1.078 1.868-.778 3.084-2.605 3.079-4.628-.01-2.754-2.243-4.983-4.998-4.988z" transform="translate(-1174.000000, -18.000000) translate(24.000000, 10.000000) translate(1150.250000, 0.000000) translate(-0.000000, 6.250000)"/>'+
                            '                    </g>'+
                            '                </g>'+
                            '            </g>'+
                            '        </g>'+
                            '    </g>'+
                            '</svg>'+
                        '</div>';

        var messages =  '<div class="menu_messages_icon" onclick="Navigation.load(\'/messages\'); Menu.clearActiveMenu()">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">' +
                            '    <g fill="none" fill-rule="evenodd" stroke-linecap="round">' +
                            '        <g stroke="currentColor">' +
                            '            <g>' +
                            '                <g>' +
                            '                    <g>' +
                            '                        <path stroke-width="2" d="M23 5v14c0 .552-.448 1-1 1H2c-.552 0-1-.448-1-1V5c0-.552.448-1 1-1h20c.552 0 1 .448 1 1z" transform="translate(-1216.000000, -18.000000) translate(24.000000, 10.000000) translate(1150.250000, 0.000000) translate(41.750000, 8.000000)"/>' +
                            '                        <path d="M1.786 5.643l8.904 7.72c.752.653 1.868.653 2.62 0l8.904-7.72h0M1.786 19.214L9.159 12.408M14.837 12.405L22.214 19.214" transform="translate(-1216.000000, -18.000000) translate(24.000000, 10.000000) translate(1150.250000, 0.000000) translate(41.750000, 8.000000)"/>' +
                            '                    </g>' +
                            '                </g>' +
                            '            </g>' +
                            '        </g>' +
                            '    </g>' +
                            '</svg>' +
                            '<span class="counter"></span>'+
                        '</div>';

        $('#action_icons').append('<div class="action_icon_container">'+support+''+ messages+'</div>');

        $('.menu_support_icon').click(function () {
            if ($('#user_menu').is(':visible')) {
                $('#user_menu').slideUp();
            }
            $('#header #top #support_menu').slideDown();
            setTimeout(function () {
                $('#header #top #support_menu').slideUp();
            }, 5000);
        });

        $('#header #top #support_menu').mouseleave(function () {
            $(this).slideUp();
        });
    }
};