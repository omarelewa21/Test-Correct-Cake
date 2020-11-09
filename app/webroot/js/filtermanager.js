window.FilterManager = {
    el: false,
    filters: false,
    activeFilter: false,
    newFilter: {},
    editFilter: {'filters': {}},
    isInitalizing: true,
    filterFields: [
        {field: 'name', label: 'Toets', type:'text'},
        {field: 'kind', label: 'Type', type:'select'},
        {field: 'subject', label: 'Vak', type:'multiSelect'},
        {field: 'period', label: 'Periode', type:'select'},
        {field: 'educationLevels', label: 'Niveau', type:'multiSelect'},
        {field: 'educationLevelYears', label: 'Leerjaar', type:'multiSelect'},
        // // {field: 'isOpenSourcedContent', label: 'Bron'},
        {field: 'createdAtStart', label: 'Aanmaakdatum van'},
        {field: 'createdAtEnd', label: 'Aanmaakdatum tot'},
    ],

    init: function () {
        this.el = '#jquery-saved-filters';
        $.getJSON('/search_filter/get/item_bank', function (response) {
            this.filters = response.data;
            this.initializeSavedFilterSelect();
            if (this.isInitalizing) {
                this.registerEvents();
                this.addChangeEventsToFilter(this);
                this.initNewFilter();
                this.bindActiveFilterDataToFilterModal();
            }
            this.reloadData();
            this.isInitalizing = false;
            this.initializeSelect2Fields();

        }.bind(this));
    },

    initializeSelect2Fields:function(){
        this.filterFields.forEach(function(field) {
            if (field.type === 'multiSelect') {
                this.getJqueryFilterInput(field.field).select2();
            }
        }.bind(this));
    },


    reloadData: function () {
        this.getJqueryFilterInput(this.filterFields[0].field).trigger('change');
    },

    initializeSavedFilterSelect: function () {

        $('#jquery-applied-filters').hide();
        this.renderSelectFilterBox();
    },

    renderSelectFilterBox: function (valueToSelect) {
        $(this.el).html('')
            .append(
                $('<option></option>')
                    .attr('value', '')
                    .text('Kies een filter (geen filter)')
            );

        $(this.filters).each(function (key, filter) {
            $(this.el).append($('<option></option>').attr('value', filter.id).text(filter.name));
        }.bind(this));
        if (valueToSelect) {
            $(this.el).val(valueToSelect);
        } else if (valueToSelect == '') {
            this.activeFilter = false;
        } else {
            let activeItem = this.filters.find(function (item) {
                return item.active == 1;
            })
            if (activeItem) {
                $(this.el).val(activeItem.id);
                this.setActiveFilter(activeItem.id);
            }
        }
        this.renderActiveFilter();
    },

    initNewFilter: function () {
        this.filterFields.forEach(function (item) {
            this.newFilter[item.field]
        }.bind(this))
    },
    registerEvents: function () {
        $(document)
            .on('change', this.el, function (e) {
                    var value = $(e.target).val();
                    if (value === '') {
                        $('#jquery-applied-filters').hide();
                        $('#jquery-delete-filter').addClass('disabled');
                        $.getJSON('/search_filter/deactivate/' + this.activeFilter.uuid, function (response) {
                        });
                        this.activeFilter = false;
                    } else {

                        $('#jquery-delete-filter').removeClass('disabled');
                        $('#jquery-applied-filters').show();
                        $.getJSON('/search_filter/activate/' + this.activeFilter.uuid, function (response) {
                        });
                    }

                    this.reloadData();
                    this.activeFilter.changed = false;
                    this.setActiveFilter(value);

                    $('#jquery-save-filter').addClass('disabled');
                }.bind(this)
            )

            .on('click', '.jquery-remove-filter', function (e) {
                e.stopPropagation();
                var prop = $(e.target).attr('jquery-filter-key');

                let input = this.getJqueryFilterInput(prop);
                let newValue = '';
                if (input.get(0).tagName === 'SELECT') {
                    newValue = '0';
                }

                if (input.is(':checkbox')) {
                    input.prop('checked', false);
                    newValue = '1';
                }

                input.val(newValue).trigger('change');
                this.activeFilter.filters[prop] = {name: '', filter: '', label: ''};
                this.activeFilter.changed = true;
                this.renderActiveFilter();
            }.bind(this))

            .on('click', '#jquery-add-filter', function (e) {
                $(this.el).val('');
                this.resetSearchForm();
                this.setSearchFormTitle('Filter aanmaken');
                $('#jquery-save-filter-as-from-modal').hide();
                Popup.showSearch()
                this.activeFilter = {
                    id: '',
                    name: 'Nieuw',
                    filters: this.newFilter
                }
                this.renderActiveFilter(e);
            }.bind(this))

            .on('click', '#jquery-edit-filter', function (e) {
                this.setSearchFormTitle('Filter aanpassen: ' + this.activeFilter.name);
                $('#jquery-save-filter-as-from-modal').show();
                Popup.showSearch()
                this.bindActiveFilterDataToFilterModal();
            }.bind(this))

            .on('click', '#jquery-save-filter', function (e) {
                this.saveFilter(e);
            }.bind(this))


            .on('click', '#jquery-reset-filter', function (e) {
                if (!$(e.target).hasClass('disabled')) {
                    this.resetSearchForm();
                    this.renderSelectFilterBox('');
                }
            }.bind(this))

            .on('click', '#jquery-delete-filter', function (e) {
                this.deleteFilter();
            }.bind(this))
            .on('click', '#jquery-save-filter-from-modal', function (e) {
                Popup.closeSearch();
                this.saveFilter(e);
            }.bind(this))
            .on('click', '#jquery-save-filter-as-from-modal', function (e) {
                Popup.closeSearch();

                this.saveFilter(e);
            }.bind(this))

    },

    resetSearchForm: function () {
        this.filterFields.forEach(function (item) {
            let input = this.getJqueryFilterInput(item.field);
            let newValue = '';

            if (input.get(0).tagName == 'SELECT') {
                newValue = '0';
            }
            input.val(newValue).trigger('change');
        }.bind(this));
    },

    saveFilter: function (e) {
        const saveAs = e.target.id === 'jquery-save-filter-as-from-modal';

        if (!$(e.target).hasClass('disabled')) {
            const isNewFilter = (this.activeFilter !== this.editFilter);
            Popup.prompt({
                    text: 'Wat is de naam van dit filter?',
                    title: saveAs ? 'Opslaan als' : 'Opslaan',
                    inputValue: isNewFilter
                        ? 'Nieuw Filter'
                        : saveAs ? this.editFilter.name + ' copy' : this.editFilter.name,
                },
                function (filterName) {
                    if (filterName === null) {
                        return;
                    }
                    if (filterName === "") {
                        Notify.notify('Geen geldige naam opgegeven filter niet opgeslagen!', 'error');
                    } else {
                        if (isNewFilter) {
                            this.saveNewFilter(filterName);
                        } else if (saveAs) {
                            this.saveActiveFilterAs(filterName);
                        } else {
                            this.saveActiveFilter(filterName);
                        }
                    }
                }.bind(this));
        }
    },

    saveActiveFilterAs: function (newFilterName) {
        const copyActiveFilter = JSON.parse(JSON.stringify(this.activeFilter));
        delete (copyActiveFilter.uuid);

        $.ajax({
            url: '/search_filter/add',
            data: {
                data: {
                    search_filter: {
                        key: 'item_bank',
                        name: newFilterName,
                        filters: copyActiveFilter,
                    }
                }
            },
            method: 'POST',
            context: this,
            dataType: 'json',
            success: function (response) {
                this.filters.push(response.data);
                this.renderSelectFilterBox(response.data.id);
                this.initNewFilter()
                this.activeFilter.changed = false;
                $('#jquery-save-filter').addClass('disabled');
                Notify.notify('Filter opgeslagen');
            },
        });
    },

    saveNewFilter: function (newFilterName) {
        $.ajax({
            url: '/search_filter/add',
            data: {
                data: {
                    search_filter: {
                        key: 'item_bank',
                        name: newFilterName,
                        filters: this.newFilter,
                    }
                }
            },
            method: 'POST',
            context: this,
            dataType: 'json',
            success: function (response) {
                this.filters.push(response.data);
                this.renderSelectFilterBox(response.data.id);
                this.initNewFilter()
                this.activeFilter.changed = false;
                $('#jquery-save-filter').addClass('disabled');
                Notify.notify('Filter opgeslagen');
            },
        });
    },

    saveActiveFilter: function (newFilterName) {
        this.editFilter.name = newFilterName;
        $.ajax({
            url: '/search_filter/edit/' + this.editFilter.uuid,
            type: 'PUT',
            dataType: 'json',
            data: {
                search_filter: this.editFilter,
            },
            context: this,
            success: function (response) {
                Notify.notify('Filter opgeslagen');
                this.renderSelectFilterBox(this.editFilter.id);
                //TODO splice the current filter from the array and replace with the new one;
                this.filters = this.filters.map(function(filter){
                    if (filter.id == this.activeFilter.id) {
                        return this.activeFilter;
                    }
                    return filter;
                }.bind(this));


                this.setActiveFilter(this.editFilter.id);
                this.activeFilter.changed = false;
                $('#jquery-save-filter').addClass('disabled');
            },
        });
    },

    deleteFilter: function () {
        if (this.activeFilter === false) {
            Notify.notify('Selecteer het filter dat u wilt verwijderen.', 'error')
            return;
        }
        Popup.confirm({
            title: '',
            text: 'Weet je zeker dat je dit filter wilt verwijderen?'
        }, function (confirmValue) {
            if (confirmValue) {
                $.ajax({
                    url: '/search_filter/delete/' + this.activeFilter.uuid,
                    type: 'DELETE',
                    context: this,
                    success: function (response) {
                        this.filters = this.filters.filter(function (filter) {
                            return filter.id !== this.activeFilter.id;
                        }.bind(this));

                        this.renderSelectFilterBox('');
                        this.activeFilter = false;
                        this.renderActiveFilter();
                        Notify.notify('Het filter is succesvol verwijderd.')
                    },
                });
            }
        }.bind(this));
    },

    setActiveFilter(filterId) {
        if (filterId == '') return;

        let filterToClone = this.filters.find(function (filter) {
            return filter.id == filterId;
        });
        this.editFilter = JSON.parse(JSON.stringify(filterToClone));
        // clone the object using the oldest trick in the book because we have no deep clone helper;

        this.activeFilter = this.editFilter;

        this.renderActiveFilter();
    },

    bindActiveFilterDataToFilterModal: function () {
        this.filterFields.forEach(function (item) {
            if (this.activeFilter && this.activeFilter.filters.hasOwnProperty(item.field)) {
                let newValue = this.activeFilter.filters[item.field].filter;
                let input = this.getJqueryFilterInput(item.field);
                if (!newValue && input.get(0).tagName === 'SELECT') {
                    newValue = '0';
                }
                input.val(newValue);
            }
        }.bind(this));
    },

    getJqueryFilterInput: function (name) {
        return $('#Test' + name.charAt(0).toUpperCase() + name.slice(1));
    },

    renderActiveFilter: function (e) {
        if (e instanceof Event) {
            e.stopPropagation();
        }
        let hasActualFilter = false;
        $('#jquery-filter-filters').html('');
        if (this.activeFilter) {

            $('#jquery-applied-filters').show();
            $('#jquery-delete-filter').removeClass('disabled');
            for (const [key, filterDetail] of Object.entries(this.activeFilter.filters)) {

                if (filterDetail.filter && filterDetail.name) {
                    let input = this.getJqueryFilterInput(key);

                    if (input.get(0).tagName === 'SELECT' && filterDetail.filter == '0') continue;

                    if (input.get(0).tagName === 'INPUT' && filterDetail.filter == '') continue;

                    hasActualFilter = true;


                    let label = Array.isArray(filterDetail.filter)
                        ? filterDetail.name+': '+ input.find(':selected').toArray().map(function(option) { return option.innerText; }).sort().join(', ')
                        : filterDetail.label;

                    $('#jquery-filter-filters').append($(
                        `<span class="mr2 inline-block">
                                        <button title="Filter verwijderen" class="label-search-filter jquery-remove-filter fa fa-times-x-circle-o" jquery-filter-key="${key}">
                                         ${label}
                                        </button>
                                    </span>`)
                    );
                }
            }

        } else {
            $('#jquery-applied-filters').hide();
        }

        if (hasActualFilter) {
            $('#jquery-reset-filter').removeClass('disabled');
        } else {
            $('#jquery-reset-filter').addClass('disabled');
        }
       // if (this.activeFilter.hasOwnProperty('changed') && this.activeFilter.changed) {
            $('#jquery-save-filter').removeClass('disabled');
       // }
    },

    addChangeEventsToFilter: function (context) {
        this.filterFields.forEach(function (item) {
            var selector = '#Test' + item.field.charAt(0).toUpperCase() + item.field.slice(1);
            $(document).on('change', selector, function (e) {
                this.syncFilterField($(e.target), item);
            }.bind(context));
        });
    },

    syncFilterField: function (el, item) {
        let filter = {
            name: this.getFilterLabelByField(item.field, this),
            filter: el.val(),
            label: el.val(),
        }
        if (el.is('select')) {
            filter.label = el.find(':selected').text();
        }
        if (el.is(':checkbox')) {
            filter.label = el.attr('jquery-option');
        }
        this.newFilter[item.field] = filter;
        this.editFilter.filters[item.field] = filter;
        if (!this.isInitalizing) {
            this.editFilter.changed = true;
        }
        this.renderActiveFilter();
    },

    getFilterLabelByField: function (field, context) {
        let labelField = context.filterFields.find(function (item) {
            return item.field == field;
        });
        return labelField.label;
    },
    setSearchFormTitle: function (title) {
        $('#modal-head').html(title);
    },
}


// polyfill for object entries when old javascript is used;
if (!Object.entries) {
    Object.entries = function (obj) {
        var ownProps = Object.keys(obj),
            i = ownProps.length,
            resArray = new Array(i); // preallocate the Array
        while (i--)
            resArray[i] = [ownProps[i], obj[ownProps[i]]];

        return resArray;
    };
}