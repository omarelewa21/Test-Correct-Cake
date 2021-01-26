var Menu = {

    menuTmp : null,
    menu : null,
    tile : null,

    initialise : function() {

        $('#menu').load('/users/menu', function() {
            $('#tiles').hide();
            $('#header #menu .item').mouseover(function() {

                Menu.menuTmp = $(this).attr('id');

                // Highlight menu
                $('#header #menu .item').removeClass('active');
                $(this).addClass('active');

                $('#tiles .tile').hide();
                if($('#tiles .tile[menu='+Menu.menuTmp + ']').length > 0) {

                    $('#tiles .tile[menu=' + Menu.menuTmp + ']').show();

                    clearTimeout(window.menuTimer);

                    $('#tiles').show();
                    $('#tiles').stop().animate({
                        'top': '93px'
                    });
                }
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
                var type = null;
                var pWidth = 800;
                if($(this)[0].hasAttribute("type")) {
                    type = $(this).attr('type');
                }
                if($(this)[0].hasAttribute("pwidth")) {
                    pWidth = $(this).attr('pwidth');
                }
                if(type === 'externallink'){
                    window.open($(this).attr('path'),'_blank');
                    return false;
                }
                else if(type === 'popup'){
                    Popup.load($(this).attr('path'),pWidth);
                    return false;
                }
                else if(type === 'externalpopup'){
                    Popup.show('<i class="fa fa-times" title="Sluiten" onClick="Popup.closeLast();" style="position:absolute;right:6px;top:6px;"></i><iframe style="border:0;padding:0;margin:0" width="100%" height="500" src="'+$(this).attr('path')+'"></iframe>',800);
                    return false;
                }
                else if(type === 'download') {
                    window.location.href = $(this).attr('path');
                    return false;
                }
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
        },function(){
            $('#tiles').hide();
        });

        Menu.setHighlights();
    },

    setHighlights : function() {
        $('#menu .item, #tiles .tile').removeClass('active');
        $('#tiles #' + Menu.tile).addClass('active');
        $('#menu #' + Menu.menu).addClass('active');
    }
};