(function ( $ ) {

    var settings = [];
    var loading = false;
    var element;
    var filterTimeout = null;
    var endResults = false;
    var scrollContainer = null;

    $.fn.tablefy = function( options ) {

        element = this;

        settings = $.extend({
            'results' : 60,
            'page' : 1,
            'sort' : '',
            'direction' : 'down',
            'hideEmpty' : false,
            'fixedHeadersInitialized':false,
            'scrollInitialized': false
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

        loadResults();
    }

    function scrollInitialize(){
        // INIT AUTOSCROLL
        $(scrollContainer).scroll(function() {
            var scrollT = $(this).scrollTop();
            var conH = $(scrollContainer).height();
            var tableH = $(element).height();
            var difference = (scrollT + conH) - tableH;

            if(difference > -50 && !loading && !endResults) {
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

    function prepareHeadersFixed(){
        if(!hasComputedStyle()) {
            return false;
        }
        var containerEl = $(settings.container).get(0);
        var containerHeight = parseInt($(settings.container).height());
        var theadHeight = parseInt($(settings.container).find('table:first thead').height());
        var paddingTop = parseInt(window.getComputedStyle(containerEl).getPropertyValue('padding-top'));
        var tbodyHeight = containerHeight - paddingTop - theadHeight;

        scrollContainer = $(settings.container).find('table:first tbody').get(0);

        jQuery('#shadowFixedHeaderContainer').remove();
        var shadowFixedHeaderContainer = jQuery('<div id="shadowFixedHeaderContainer" style="position:absolute;right:10000px;bottom:5000px;"></div>');
        shadowFixedHeaderContainer.width($(scrollContainer).width()).height(tbodyHeight);
        shadowFixedHeaderContainer.html($(settings.container).find('table:first').parent().html());
        jQuery('body').append(shadowFixedHeaderContainer);
        settings.shadowFixedHeaderContainer = shadowFixedHeaderContainer;

        makeElementsFixed();
        $(settings.container).css('overflow','initial');
        $(settings.container).find(' table:first thead').css('display','block');
        $(settings.container).find(' table:first tbody').css({display:'block',overflow:'auto'}).height(tbodyHeight);

        if(settings.scrollInitialized == false){
            scrollInitialize();
        }
    }

    function makeElementsFixed(){
        var theadRow = $(settings.container).find('table:first thead tr:first');
        var tbodyRow = $(settings.container).find('table:first tbody tr:first');
        $(settings.shadowFixedHeaderContainer).find('table:first tbody tr:first').find('td,th').each(function(index){
            theadRow.find('td,th').eq(index).width($(this).width());
            tbodyRow.find('td,th').eq(index).width($(this).width());
            if($(this).hasClass('nopadding')){
                theadRow.find('td,th').eq(index).addClass('nopadding');
            }
        });
    }

    function loadResultsIntoShadow(data){
        if(typeof settings.shadowFixedHeaderContainer != 'undefined'){
            $(settings.shadowFixedHeaderContainer).find('tbody').append(data);
        }
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
                    if(settings.page === 1){
                        $(element).find('tbody').html("");
                    }
                    $(element).find('tbody').append(results);
                    Core.afterHTMLload();
                    needsToLoadIntoShadow = true;
                }

                if(settings.fixedHeadersInitialized === false){
                    prepareHeadersFixed();
                    settings.fixedHeadersInitialized = true;
                } else if(needsToLoadIntoShadow === true) { // after filter click
                    loadResultsIntoShadow(results);
                    makeElementsFixed();
                }

                if(!checkOverflow(scrollContainer) && !endResults) {
                    settings.page++;
                    loadResults();
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
