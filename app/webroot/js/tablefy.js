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

        // INIT AUTOSCROLL
        $(settings.container).scroll(function() {
            var scrollT = $(this).scrollTop();
            var conH = $(settings.container).height();
            var tableH = $(element).height();
            var difference = (scrollT + conH) - tableH;

            if(difference > -100 && !loading && !endResults) {
                settings.page++;
                loadResults();
            }
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

    function afterSortOrFilter() {
        settings.page = 1;
        $(element).find('tbody').html("");
        endResults = false;
        loadResults();
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
                if(results == "") {
                    endResults = true;
                }else{
                    $(element).find('tbody').append(results);
                    Core.afterHTMLload();
                }

                if($(settings.container).height() > $(element).height() && !endResults) {
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

    function getFilters() {
        var filters = [];
        return filters;
    }

    function hideEmptyCols(table) {

    }

}( jQuery ));