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
            'hideEmpty' : false
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
        clearTbodyData();
        loadResults();
    }

    var lastScrollTop = 0;
    function scrollInitialize() {
        getScrollContainer().off("scroll").on("scroll", function () {
            var scrollT = $(this).scrollTop();
            if (scrollT > lastScrollTop) {
                var tableH = getScrollContainer().prop('scrollHeight');
                var difference = scrollT + getScrollContainer().height() - tableH;

                if (difference > -100 && !loading && !endResults) {
                    settings.page++;
                    loadResults();
                }
            }
            lastScrollTop = scrollT;
        });
    }


    function afterSortOrFilter() {
        settings.page = 1;
        clearTbodyData();
        endResults = false;
        loadResults();
    }

    function clearTbodyData(){
        $(element).find('tbody').html("");
    }

    function doWeUseFixedHeaders(){
        return (typeof(window.getComputedStyle) == 'function');
    }
    function areHeadersPrepared() {
        return ($(settings.container).attr('fixedHeaderInitialized') == 1)
    }

    function setHeadersPrepared() {
        $(settings.container).attr('fixedHeaderInitialized',1);
    }

    function getScrollContainer(){
        return $(settings.container).find('table:first tbody');
    }

    function setScrollContainerHeight(tbodyHeight) {
        if(typeof tbodyHeight !== 'undefined') {
            getScrollContainer().height(tbodyHeight);
        }
    }

    function resetStyling(tbodyHeight){
        $(settings.container).find('table:first').removeClass('tablefied');
        $(settings.container).css('overflow','initial');
        $(settings.container).find(' table:first thead').css('display','table-header-group');
        $(settings.container).find(' table:first tbody').css({display:'table-row-group'});
        setScrollContainerHeight(tbodyHeight);
    }

    function applyFixStyling(tbodyHeight){
        $(settings.container).find('table:first').addClass('tablefied');
        $(settings.container).css('overflow','initial');
        $(settings.container).find(' table:first thead').css('display','block');
        $(settings.container).find(' table:first tbody').css({display:'block',overflow:'auto'});
        setScrollContainerHeight(tbodyHeight);
    }

    function prepareHeadersFixed(){
        if(!doWeUseFixedHeaders()) {
            return false;
        }

        if(areHeadersPrepared()){
            return false;
        }

        var containerEl = $(settings.container).get(0);
        var containerHeight = parseInt($(settings.container).height());
        var theadHeight = parseInt($(settings.container).find('table:first thead').height());
        var paddingTop = parseInt(window.getComputedStyle(containerEl).getPropertyValue('padding-top'));
        var tbodyHeight = containerHeight - paddingTop - theadHeight;

        makeElementsFixed(tbodyHeight);

        scrollInitialize();

        setHeadersPrepared();
    }

    function makeElementsFixed(tbodyHeight){
        resetStyling(tbodyHeight);
        var theadRow = $(settings.container).find('table:first thead tr:first');
        var tbodyRow = $(settings.container).find('table:first tbody tr:first');
        tbodyRow.find('td,th').each(function(index){
            theadRow.find('td,th').eq(index).width($(this).width());
            tbodyRow.find('td,th').eq(index).width($(this).width());
            if($(this).hasClass('nopadding')){
                theadRow.find('td,th').eq(index).addClass('nopadding');
            }
        });
        applyFixStyling(tbodyHeight);
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
                needsToLoadIntoShadow = false;
                if(results == "" || results == "\n") {
                    endResults = true;
                }else{
                    if(settings.page ===1 ){
                        clearTbodyData();
                    }
                    $(element).find('tbody').append(results);
                    Core.afterHTMLload();
                    needsToLoadIntoShadow = true;
                }

                if(!endResults) {
                    if (!areHeadersPrepared()) {
                        prepareHeadersFixed();
                    } else { // after filter click
                        makeElementsFixed();
                    }

                    if (!checkOverflow(getScrollContainer().get(0))) {
                        settings.page++;
                        loadResults();
                    }
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

    function checkOverflow(el)
    {
        var curOverflow = el.style.overflow;

        if ( !curOverflow || curOverflow === "visible" )
            el.style.overflow = "hidden";

        var isOverflowing = el.clientHeight < el.scrollHeight;

        el.style.overflow = curOverflow;

        return isOverflowing;
    }

    function getFilters() {
        var filters = [];
        return filters;
    }

    function hideEmptyCols(table) {

    }

}( jQuery ));
