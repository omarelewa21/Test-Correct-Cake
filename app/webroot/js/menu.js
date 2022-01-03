var Menu = {

    menuTmp: null,
    menu: null,
    tile: null,
    visibilityTimeout: null,
    supportInfo: null,

    initialise: function () {

        if (User.info.guest) {
            return;
        }

        if (User.info.isStudent && User.info.laravel_look == 1) {
            $('body').attr('laravel-look', '');
            this.loadNewLookMenu();
            this.handleAppVersion();

            return;
        }

        this.loadMenuItems();

        this.loadTilesForMenu();

    },
    loadMenuItems: function () {
        $('#menu').load('/users/menu', function () {
            Menu.addDashboardAndResultsToMenu();
            Menu.addActionIconsToHeader();
            if (Menu.supportInfo !== null) {
                Menu.addReturnToSupportButton(Menu.supportInfo);
            }
            $('#header #menu .item').mouseover(function () {

                Menu.menuTmp = $(this).attr('id');
                $('#header #menu .item').removeClass('active');

                $('#tiles .tile').hide();
                clearTimeout(window.menuTimer);
                if ($('#tiles .tile[menu=' + Menu.menuTmp + ']').length > 0) {

                    $(this).addClass('active');
                    $('#tiles .tile[menu=' + Menu.menuTmp + ']').show();
                    $('#tiles').show();
                    $('#tiles').stop(true, true).animate({
                        'top': '98px',
                        'padding-left': Menu.getPaddingForActiveMenuItem(Menu.menuTmp)
                    });
                } else {
                    if (!$(this).find('.withActiveHover')) {
                        $(this).addClass('noItemHover');
                    }
                    $('#tiles').stop(true, true).animate({
                        'top': '0'
                    });
                }
            }).mouseout(function () {
                window.menuTimer = setTimeout(function () {
                    if ($('#tiles .tile[menu=' + Menu.menuTmp + ']').length === 0 && Menu.menu !== null) {
                        Menu.hideInactiveTiles();
                        $('#tiles').stop(true, true).animate({
                            'top': '98px',
                            'padding-left': Menu.getPaddingForActiveMenuItem(Menu.menu)
                        });
                    }
                }, 500);
            });
            Menu.initScrollForMenu();
            window.addEventListener('resize', function () {
                Menu.initScrollForMenu();
            });
        });
    },
    loadTilesForMenu: function () {
        $('#tiles').load('/users/tiles', function () {
            $('#tiles .tile').hide();
            $('#tiles').mouseover(function () {
                clearTimeout(window.menuTimer);
                $(this).stop(true, true).animate({
                    'top': '98px'
                });
            }).mouseout(function () {
                window.menuTimer = setTimeout(function () {
                    Menu.hideInactiveTiles();
                    if (Menu.shouldRemoveTilesBar()) {
                        $('#tiles').stop(true, true).animate({
                            'top': 0,
                            'height': '48px'
                        });
                    } else {
                        $('#tiles').stop(true, true).animate({
                            'padding-left': Menu.getPaddingForActiveMenuItem(Menu.menu)
                        });
                    }
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
                } else if (type === 'laravelpage') {
                    User.goToLaravel($(this).attr('path'));
                    return false;
                }
                $(this).addClass('active');
                Menu.menu = Menu.menuTmp;
                Menu.tile = $(this).attr('id');

                Menu.hideInactiveTiles();

                Navigation.load($(this).attr('path'));
            });
        });
    },
    loadNewLookMenu: function () {
        $.ajax({
            dataType: 'json',
            url: '/users/menu',
            async: false,
            complete:
                function (menu) {
                    var menuItemsHtml = menu.responseText;
                    $('[laravel-look] .logo-container').after('' +
                        '<div id="student-menu">' +
                        '   <div class="item" id="dashboard" onclick="User.goToLaravel(\'/student/dashboard\')">\n' +
                        '        <span class="item-title">Dashboard</span>\n' +
                        '        <span class="counter"></span>\n' +
                        '    </div>' +
                        '' + menuItemsHtml + '</div>' +
                        '');

                    $('#logo_1, #logo_2').attr('onClick', 'User.goToLaravel(\'/student/dashboard\')');

                    $('#student-menu > .item').each(function () {
                        $(this).click(function () {
                            Menu.removeMenuStates();
                            Menu.menu = $(this).get(0).id;

                            // $('#tiles .tile').hide();
                            // $('#tiles .tile[menu=' + Menu.menu + ']').css("display", "flex")
                            //     .hide()
                            //     .fadeIn();
                            //
                            // $('#tiles').css("display", "flex")
                            //     .hide()
                            //     .fadeIn();

                            $(this).addClass('active');
                            $('#tiles .tile[menu=' + Menu.menu + ']').first().addClass('active');
                        });
                    });

                    // $('.logo-container').click(function () {
                    //     Menu.removeMenuStates();
                    //     Menu.dashboardButtonAction();
                    // });

                    $('#tiles').load('/users/tiles', function () {
                        $('#tiles > .tile').each(function () {
                            $(this).click(function () {

                                if (typeof $(this).attr('path') !== 'undefined') {
                                    Navigation.load($(this).attr('path'));
                                }

                                $('#tiles > .tile').removeClass('active');
                                $(this).addClass('active');

                            });
                        });
                    });
                },
        });

        this.addActionIconsToHeader();
    },
    handleAppVersion: function () {
        setTimeout(function () {
            $.ajax({
                type: 'get',
                url: '/users/getAppDetailsForMenu',
                success: function (result) {
                    result = JSON.parse(result);
                    if (result.app_version !== 'undefined' && result.app_version !== 'x') {
                        var versionTag = $('.student_version_tag');
                        versionTag.get(0).innerText = $.i18n('Versie')+': '+result.app_version;
                        versionTag.addClass(result.status);
                        versionTag.show();
                    }
                }
            });
        }, 1000);
    },
    shouldRemoveTilesBar: function() {
        var emptyMenuItems = [];
        $('#header #menu .item').each(function() {
            var item = $(this).attr('id');
            if ($('#tiles .tile[menu=' + item + ']').length === 0) {
                emptyMenuItems.push(item);
            }
        });

        return Menu.menu === null || emptyMenuItems.includes(Menu.menu);
    },

    hideInactiveTiles: function () {
        $('#tiles .tile').hide();
        $('#tiles .tile[menu=' + Menu.menu + ']').show();
        $('#menu .item').removeClass('active');

        if (!User.info.laravel_look == 1) {
            if (!$('#tiles .tile').is(':visible')) {
                $('#tiles').slideUp();
                $('#container').animate({'marginTop': '90px'});
            } else {
                $('#container').animate({'marginTop': ($('#tiles').height() + 100) + 'px'});
            }
        }
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
        $('#menu').animate({
            scrollLeft: 0
        }, 1000);
    },

    clearActiveMenu: function(placeholder) {
        Menu.menu = typeof placeholder !== 'undefined' ? placeholder : 'empty';
        Menu.tile = '';
        if (!TestTake.active) {
            Menu.hideInactiveTiles();
        }
    },

    addDashboardAndResultsToMenu: function () {
        Menu.hideInactiveTiles();
    },

    addActionIconsToHeader: function () {
        var support =   '<div class="menu_support_icon" title="Support">' +
                            '<?xml version="1.0" encoding="UTF-8"?>' +
                            '<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">' +
                            '    <g id="icons/support" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">' +
                            '        <path d="M9.95462046,7.11460331 C9.95462046,3.97760698 7.41158248,1.43456901 4.27458616,1.43456901 C1.13758983,1.43456901 -1.40544814,3.97760698 -1.40544814,7.11460331" id="Oval" stroke="currentColor" transform="translate(4.274586, 4.274586) rotate(-45.000000) translate(-4.274586, -4.274586) "></path>' +
                            '        <path d="M25.3456811,7.11460331 C25.3456811,3.97760698 22.8026432,1.43456901 19.6656468,1.43456901 C16.5286505,1.43456901 13.9856125,3.97760698 13.9856125,7.11460331" id="Oval" stroke="currentColor" transform="translate(19.665647, 4.274586) scale(-1, 1) rotate(-45.000000) translate(-19.665647, -4.274586) "></path>' +
                            '        <path d="M9.95462046,22.505664 C9.95462046,19.3686677 7.41158248,16.8256297 4.27458616,16.8256297 C1.13758983,16.8256297 -1.40544814,19.3686677 -1.40544814,22.505664" id="Oval-Copy-2" stroke="currentColor" transform="translate(4.274586, 19.665647) scale(1, -1) rotate(-45.000000) translate(-4.274586, -19.665647) "></path>' +
                            '        <path d="M25.3456811,22.505664 C25.3456811,19.3686677 22.8026432,16.8256297 19.6656468,16.8256297 C16.5286505,16.8256297 13.9856125,19.3686677 13.9856125,22.505664" id="Oval-Copy" stroke="currentColor" transform="translate(19.665647, 19.665647) scale(-1, -1) rotate(-45.000000) translate(-19.665647, -19.665647) "></path>' +
                            '        <path d="M14.415439,16.3778799 L18.0192957,19.9830372 C17.358281,20.4814591 16.6286021,20.9025803 15.84089,21.2304194 C12.6134567,22.5736499 8.96154672,22.1221321 6.18036047,20.1317099 L9.81448458,16.4970961 C11.0803396,17.1120331 12.5746877,17.1764504 13.9212532,16.6160212 C14.0915498,16.5451452 14.2564216,16.4655495 14.415439,16.3778799 Z M4.01432599,5.97670966 L7.62214364,9.58451214 C7.53292577,9.74633318 7.45207288,9.91421175 7.3802653,10.0876934 C6.7772099,11.544629 6.9047866,13.1728727 7.66815939,14.4971656 L4.07203282,18.0952371 C1.91163423,15.2881965 1.37930994,11.5068466 2.75893319,8.17378258 C3.08791874,7.3789787 3.51183514,6.64297893 4.01432599,5.97670966 Z M21.9983747,11.9741856 C22.0035905,14.1488778 21.3041809,16.2100456 20.0761819,17.8930552 L16.4540086,14.2720065 C16.8077624,13.5788752 17.0019458,12.7991863 17,11.9878987 C16.997006,11.2087712 16.8161642,10.471706 16.4959853,9.81513925 L20.1286944,6.18447444 C21.2984934,7.81633047 21.9900754,9.81445767 21.9983747,11.9741856 Z M12.0032183,1.9984048 C14.2946298,2.0027587 16.4056261,2.77660539 18.0913021,4.0755118 L14.4965444,7.66992365 C13.7625764,7.24534229 12.9108522,7.00172612 12.0024146,7 C11.1946877,6.99962204 10.4186593,7.1935955 9.72849708,7.54573523 L6.10304443,3.92109793 C7.78092815,2.69661691 9.83496897,1.9973902 12.0032183,1.9984048 Z" id="Combined-Shape" fill="currentColor"></path>' +
                            '        <path d="M12.0032183,1.9984048 C17.5114889,2.00887105 21.9772078,6.46594572 21.9983747,11.9741856 C22.0080813,16.0213483 19.5773714,19.6753275 15.84089,21.2304194 C12.1044087,22.7855113 7.79898955,21.935052 4.93443577,19.0760426 C2.06988199,16.2170331 1.21108313,11.9132697 2.75893319,8.17378258 C4.30678325,4.43429549 7.95604647,1.996511 12.0032183,1.9984048 Z M12.0024146,7 C9.9788257,6.9990531 8.15419145,8.21794711 7.3802653,10.0876934 C6.60633915,11.9574396 7.0357392,14.1093245 8.46801816,15.5388313 C9.90029713,16.9683381 12.0530098,17.3935683 13.9212532,16.6160212 C15.7894966,15.8384742 17.0048533,14.0114819 17,11.9878987 C16.9894165,9.2337737 14.7565538,7.00523313 12.0024146,7 Z" id="Combined-Shape" stroke="currentColor"></path>' +
                            '    </g>' +
                            '</svg>' +
                        '</div>';

        var messages =  '<div class="menu_messages_icon" onclick="Navigation.load(\'/messages\'); Menu.clearActiveMenu()" title="Berichten">' +
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

        $('#action_icons').append('<div class="action_icon_container">'+ support + messages +'</div>');

        $('.menu_support_icon').click(function () {
            if ($('#user_menu').is(':visible')) {
                $('#user_menu').slideUp();
            }
            $('#header #top #support_menu').slideDown();
            setTimeout(function () {
                $('#header #top #support_menu').slideUp();
            }, 5000);
        });

        $('.menu_messages_icon').click(function() {
            Menu.removeMenuStates();
            $(this).addClass('active');
        });

        $('#header #top #support_menu').mouseleave(function () {
            $(this).slideUp();
            Menu.hideInactiveTiles();
        });

        var right = $('#header').width() - $('.menu_support_icon').get(0).getBoundingClientRect().right;
        $('#support_menu').css({'right': right});

        if (User.info.isTeacher) {
            $('#menu_support_icon').append('<span class="ml6">Support</span>');
        }
    },

    getPaddingForActiveMenuItem: function (activeMenu) {
        activeMenu = activeMenu === null ? 'dashboard' : activeMenu;

        var subItemsWidth = 0;
        $('#tiles .tile[menu=' + activeMenu + ']').each(function() {
            subItemsWidth += this.offsetWidth;
        });

        var menuItem = $('#' + activeMenu).get(0);
        var minimalOffset = $('#menu .item:first-child').get(0).offsetLeft;
        var maxOffset = $('#tiles').width() - subItemsWidth;
        var calculatedOffset = menuItem.getBoundingClientRect().right - (menuItem.offsetWidth / 2) - (subItemsWidth / 2);

        if (calculatedOffset < minimalOffset) {
            return minimalOffset;
        }
        if (calculatedOffset > maxOffset) {
            return maxOffset;
        }
        return calculatedOffset;
    },

    handleHandIn: function() {
        Menu.updateMenuFromRedirect('tests', 'tests_discussed');
    },

    updateMenuFromRedirect: function(menu, tile) {
        Menu.menu = menu;
        Menu.tile = tile;
        Menu.hideInactiveTiles();
        if (!Menu.shouldRemoveTilesBar()) {
            $('#tiles').show();
            $('#tiles').stop(true, true).animate({
                'top': '98px'
            });
            $('#container').animate({'marginTop': ($('#tiles').height()+100)+'px'});
        }
    },

    initScrollForMenu: function() {
        var menu = $('#menu');
        var totalMenuWidth = menu.outerWidth(true) - menu.innerWidth() + 40;

        $('#menu .item').each(function(item) {
            totalMenuWidth += this.offsetWidth;
        });

        if (totalMenuWidth >= menu.width()) {
            $('.menu-scroll-button').css('display', 'flex');
            menu.css('paddingRight','40px');
            clearTimeout(Menu.visibilityTimeout);
            Menu.startVisibilityTimer();
        } else {
            $('.menu-scroll-button').hide();
            menu.css('paddingRight','0');
        }

        $('.menu-scroll-button.left').on('click', function () {
            menu.animate({
                scrollLeft: 0
            }, 1000);
            clearTimeout(Menu.visibilityTimeout);
            Menu.startVisibilityTimer();
        });
        $('.menu-scroll-button.right').on('click', function () {
            menu.animate({
                scrollLeft: totalMenuWidth
            }, 1000);
            clearTimeout(Menu.visibilityTimeout);
            Menu.startVisibilityTimer();
        });

    },

    startVisibilityTimer: function() {
        this.visibilityTimeout = setTimeout(function() {
            var activeEl = $('.item.active');
            if(activeEl.length !== 0 && !isElementInViewport(activeEl)) {
                $('#menu').animate({
                    scrollLeft: activeEl.offset().left - activeEl.parent().offset().left
                }, 1000);
                Menu.getPaddingForActiveMenuItem(activeEl.get(0).id);
            }
        }, 5000);
        function isElementInViewport (el) {
            if (typeof jQuery === "function" && el instanceof jQuery) {
                el = el[0];
            }

            var rect = el.getBoundingClientRect();

            return (
                rect.top >= 0 &&
                rect.left >= 35 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /* or $(window).height() */
                rect.right <= (window.innerWidth || document.documentElement.clientWidth) /* or $(window).width() */
            );
        }
    },

    addReturnToSupportButton : function (userInfo) {
        var button =    '<div class="return_to_support" style="color: var(--error-text)" title="'+ userInfo.text +'" onclick="Menu.handleReturnToSupportAction(\''+ userInfo.user +'\')">' +
                        '   <svg xmlns="http://www.w3.org/2000/svg" style="height: 30px; width:30px" fill="none" viewBox="0 0 24 24" stroke="currentColor">\n' +
                        '       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />\n' +
                        '   </svg>';
                        '</div>';

        $('.action_icon_container').prepend(button);
    },

    handleReturnToSupportAction: function (userId) {
        $.get('support/return_as_support_user/'+userId, function (response) {
                if (response) {
                    location.reload();
                } else {
                    Notify.notify($.i18n('Er is iets misgegaan. Log uit en opnieuw in voor de Support omgeving'), 'error', 6000);
                }
            }
        );
    },
    removeMenuStates : function() {
        $('#student-menu > .item').removeClass('active');
        $('#tiles > .tile').removeClass('active');
        $('.action_icon_container > div').removeClass('active');
    }
};

function showDropdown(menuElement, chevronElement) {
    var menu = $(menuElement);
    var chevron = $(chevronElement);

    if(menu.get(0).style.display === 'none') {
        menu.fadeIn({duration: 100});
        chevron.css({'transform' : 'rotate(-90deg)'});
    } else {
        menu.fadeOut({duration: 100});
        chevron.css({'transform' : 'rotate(90deg)'});
    }
}