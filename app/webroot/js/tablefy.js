(function ( $ ) {

    var settings = [];
    var loading = false;
    var element;
    var filterTimeout = null;
    var endResults = false;

    $.fn.tablefy = function( options ) {

        element = this;

        settings = $.extend({
            'results' : 60,
            'page' : 1,
            'sort' : '',
            'direction' : 'down',
            'hideEmpty' : false,
            'fixedHeadersInitialized':false,
            'scrollInitialized': false,
        }, options );

        initialise();
    };

    function initialise() {

        endResults = false;

        // INIT SORT
        $.each($(element).find('th'), function() {
            var key = $(this).attr('sortkey');

            if(key != undefined) {
                $(this).click(function() {
                    settings.sort = key;
                    afterSortOrFilter();
                });
            }
        });

        $('.btn-reset').click(function() {
            $(settings.filters).find('input').val('');
            $(settings.filters).find('select').val('0');
            $(settings.filters).find('input, select').first().change();
        });

        $(window).scroll(function(){
           console.log('windows scroll');
        });

        // INIT FILTERS
        if(settings.filters != undefined) {

            $(settings.filters).find('input, select').bind('change keyup', function() {
                setCookie($(this).attr('id'), $(this).val());

                if($(this).val() != '' && $(this).val() != 0) {
                    $('.fa-filter').parent().addClass('highlight');
                }else{
                    $('.fa-filter').parent().removeClass('highlight');
                }

                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(function() {
                    afterSortOrFilter();
                }, 500);
            });

            $.each($(settings.filters).find('input, select'), function() {
                /*var filter = getCookie($(this).attr('id'));

                if(filter != '' && filter != 0) {
                    $(this).val(filter);
                    $('.fa-filter').parent().addClass('highlight');
                }*/
            });
        }

         loadResults();
    }

    function scrollInitialize(){
        // INIT AUTOSCROLL
        $(settings.scrollContainer).scroll(function() {
            var scrollT = $(this).scrollTop();
            var conH = $(settings.scrollContainer).height();
            var tableH = $(element).height();
            var difference = (scrollT + conH) - tableH;

            if(difference > -100 && !loading && !endResults) {
                settings.page++;
                loadResults();
            }
        });
        settings.scrollInitialized = true;
    }

    function afterSortOrFilter() {
        settings.page = 1;
        $(element).find('tbody').html("");
        endResults = false;
        loadResults();
    }

    function hasComputedStyle(){
        return typeof(window.getComputedStyle) == 'function';
    }

    function makeHeadersFixed(){
        if(!hasComputedStyle()) {
            return false;
        }
        var containerEl = $(settings.container).get(0);
        var containerHeight = parseInt($(settings.container).height());
        var theadHeight = parseInt($(settings.container).find('table:first thead').height());
        var paddingTop = parseInt(window.getComputedStyle(containerEl).getPropertyValue('padding-top'));
        var tbodyHeight = containerHeight - paddingTop - theadHeight;

        $(settings.container).find(' table:first thead tr th').each(function(index){
            $(this).width($(this).width());
        });
        $(settings.container).css('overflow','initial');
        $(settings.container).find(' table:first thead').css('display','block');
        $(settings.container).find(' table:first tbody').css({display:'block',overflow:'auto'}).height(tbodyHeight);
        settings.scrollContainer = $(settings.container).find(' table:first tbody').get(0);
        if(settings.scrollInitialized == false){
            scrollInitialize();
        }
        makeTdsFixedWidth();
    }

    function makeTdsFixedWidth(){
        var tbodyRow = $(settings.container).find(' table:first tbody tr:first');
        $(settings.container).find(' table:first thead tr:first').find('td,th').each(function(index){
            tbodyRow.find('td').eq(index).width($(this).width());
        });
    }

    function loadResults() {
        if(endResults || loading) {
            return;
        }
        loading = true;
        $.post(settings.source,
            {
                sort : settings.sort,
                results : settings.results,
                page : settings.page,
                filters : $(settings.filters).serialize()
            },
            function(results) {
                loading = false;
                if(results == "" || results == "\n") {
                    endResults = true;
                }else{
                    $(element).find('tbody').append(results);
                    Core.afterHTMLload();
                }

                if($(settings.container).height() > $(element).height() && !endResults) {
                    settings.page++;
                    loadResults();
                }

                if(settings.fixedHeadersInitialized === false){
                    makeHeadersFixed();
                    settings.fixedHeadersInitialized = true;
                } else if(settings.page == 1){ // after filter click
                    makeTdsFixedWidth();
                }


                if(settings.hideEmpty == true) {

                    $('#' + $(element).attr('id') + ' tr td, #' + $(element).attr('id') + ' tr th').show();

                    $('#' + $(element).attr('id') + ' tr th').each(function(i) {

                        var tds = $(this).parents('table').find('tr td:nth-child(' + (i + 1) + ')');
                        var empty = true;

                        $.each(tds, function() {
                            if($(this).html() != '') {
                                empty = false;
                            }
                        });

                        if(empty && tds.length > 0) {
                            $(this).hide();
                            tds.hide();
                        }else{
                            $(this).show();
                            tds.show();
                        }
                    });
                }
            }
        )
    }

    function getFilters() {
        var filters = [];
        return filters;
    }

    function hideEmptyCols(table) {

    }

}( jQuery ));
