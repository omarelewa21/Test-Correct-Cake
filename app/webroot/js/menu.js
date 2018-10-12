var Menu = {

    menuTmp : null,
    menu : null,
    tile : null,

    initialise : function() {

        $('#menu').load('/users/menu', function() {
            $('#header #menu .item').mouseover(function() {

                Menu.menuTmp = $(this).attr('id');

                // Highlight menu
                $('#header #menu .item').removeClass('active');
                $(this).addClass('active');

                $('#tiles .tile').hide();
                $('#tiles .tile[menu=' + Menu.menuTmp + ']').show();

                clearTimeout(window.menuTimer);

                $('#tiles').stop().animate({
                    'top' : '93px'
                });
            }).mouseout(function() {
                window.menuTimer = setTimeout(function() {
                    Menu.hideTiles();
                }, 500);
            });
        });

        $('#tiles').load('/users/tiles', function() {
            $('#tiles').mouseover(function() {
                clearTimeout(window.menuTimer);
                $(this).stop().animate({
                    'top' : '93px'
                });
            }).mouseout(function() {
                window.menuTimer = setTimeout(function() {
                    Menu.hideTiles();
                }, 500);
            });

            $('#tiles .tile').click(function() {
                $(this).addClass('active');
                Menu.menu = Menu.menuTmp;
                Menu.tile = $(this).attr('id');

                Menu.hideTiles();

                Navigation.load($(this).attr('path'));
            })
        });
    },

    hideTiles : function() {
        $('#tiles').stop().animate({
            'top': '-20px'
        });
        Menu.setHighlights();
    },

    setHighlights : function() {
        $('#menu .item, #tiles .tile').removeClass('active');
        $('#tiles #' + Menu.tile).addClass('active');
        $('#menu #' + Menu.menu).addClass('active');
    }
};