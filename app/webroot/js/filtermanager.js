function FilterManager(settings) {
    this.el = false;
    this.filters = false;
    this.activeFilter = false;
    this.newFilter = {};
    this.editFilter = {'filters': {}};
    this.isInitalizingEvents = true;
    this.filterFields = settings.filterFields;
    this.settings = settings;


    this.initializeDatePickerFields = function () {
        this.filterFields.forEach(function (input) {
            if (input.type === 'datePicker') {
                this.getJqueryFilterInput(input.field).datepicker({
                    dateFormat: 'dd-mm-yy'
                });
            }
        }.bind(this));
    };

    this.init = function (firstTimeRun) {
        this.el = '#jquery-saved-filters';
        this.developmentErrors();
        if(!firstTimeRun){
            this.renderActiveFilter();
            this.isInitalizingState = true;
            this.initializeDatePickerFields();
            this.renderSelectFilterBoxNotFirstRun();
            this.registerEvents();
            this.addChangeEventsToFilter(this);
            this.initTablefy();
            this.isInitalizingState = false;
            return;
        }

        $.getJSON('/search_filter/get/' + this.settings.filterKey, function (response) {
            this.filters = response.data;
            this.isInitalizingState = true;
            this.initializeDatePickerFields();
            this.initializeSavedFilterSelect();
            if (this.isInitalizingEvents) {
                if (firstTimeRun) {
                    this.registerEvents();
                    this.addChangeEventsToFilter(this);
                    this.initNewFilter();
                }

            }
            this.initTablefy();
            // this.reloadData();
            this.isInitalizingEvents = false;
            this.isInitalizingState = false;

        }.bind(this));
    };

    this.initializeSelect2Fields = function () {
        this.filterFields.forEach(function (field) {
            if (field.type === 'multiSelect') {
                this.getJqueryFilterInput(field.field).select2();
            }
        }.bind(this));
    };

    this.reloadData = function () {
        this.getJqueryFilterInput(this.filterFields[0].field).trigger('change');
    };

    this.initializeSavedFilterSelect = function () {
        $('#jquery-applied-filters').hide();
        this.renderSelectFilterBox();
    };

    this.renderSelectFilterBox = function (valueToSelect) {
        $(this.el).html('')
            .append(
                $('<option></option>')
                    .attr('value', '')
                    .text('Kies een filter (geen filter)')
            );
        this.enableDeleteButton();
        $(this.filters).each(function (key, filter) {
            $(this.el).append($('<option></option>').attr('value', filter.id).text(filter.name));
        }.bind(this));
        if (valueToSelect) {
            $(this.el).val(valueToSelect);
        } else if (valueToSelect == '') {
            this.resetSearchForm();
            this.disableDeleteButton();
            $.getJSON('/search_filter/deactivate/' + this.activeFilter.uuid, function (response) {

            }.bind(this));
            this.activeFilter = false;
            this.editFilter = {'filters': {}};
        } else if (this.filters) {
            let activeItem = this.filters.find(function (item) {
                return item.active == 1;
            });
            if (activeItem) {
                $(this.el).val(activeItem.id);
                this.setActiveFilter(activeItem.id);
            } else {
                this.activeFilter = false;
                this.disableDeleteButton();
            }
        } else {
            this.activeFilter = false;
            this.disableDeleteButton();
        }
        this.renderActiveFilter();
    };

    this.renderSelectFilterBoxNotFirstRun = function (valueToSelect) {
        $(this.el).html('')
            .append(
                $('<option></option>')
                    .attr('value', '')
                    .text('Kies een filter (geen filter)')
            );
        this.enableDeleteButton();
        $(this.filters).each(function (key, filter) {
            $(this.el).append($('<option></option>').attr('value', filter.id).text(filter.name));
        }.bind(this));
        if (valueToSelect) {
            $(this.el).val(valueToSelect);
        } else if (this.filters) {
            let activeItem = this.filters.find(function (item) {
                return item.active == 1;
            });
            if (activeItem) {
                $(this.el).val(activeItem.id);
                this.setActiveFilter(activeItem.id);
            }
        }
        this.renderActiveFilter();
    };

    this.initNewFilter = function () {
        this.filterFields.forEach(function (item) {
            this.newFilter[item.field];
        }.bind(this));
    };
    this.setActiveFilterToEmpty = function () {
        this.activeFilter = false;
        this.editFilter = false;
        this.filters = this.filters.map(function(filter) {
            filter.active = false;
            return filter;
        });
    };
    this.registerEvents = function () {
        $(document)
            .on('change',this.settings.eventScope+' '+this.el, function (e) {
                    var value = $(e.target).val();
                    this.activeFilter.changed = false;
                    this.setActiveFilter(value);
                    if (value === '') {
                        $('#jquery-applied-filters').hide();
                        this.disableDeleteButton();
                        this.resetSearchForm();
                        $.getJSON('/search_filter/deactivate/' + this.activeFilter.uuid, function (response) {
                            this.setActiveFilterToEmpty();
                        }.bind(this));
                        this.activeFilter = false;
                    } else {
                        this.enableDeleteButton();
                        $('#jquery-applied-filters').show();
                        $.getJSON('/search_filter/activate/' + this.activeFilter.uuid, function (response) {
                        });
                    }

                    this.reloadData();

                    this.disableSaveButton();
                }.bind(this)
            )

            .on('click', this.settings.eventScope+' .jquery-remove-filter', function (e) {
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

            .on('click', this.settings.eventScope+' #jquery-add-filter', function (e) {
                $(this.el).val('');
                this.resetSearchForm();
                this.setSearchFormTitle('Filter aanmaken');
                $('#jquery-save-filter-as-from-modal').hide();
                Popup.showSearch();
                this.activeFilter = {
                    id: '',
                    name: 'Nieuw',
                    filters: this.newFilter
                }
                this.renderActiveFilter(e);
            }.bind(this))



            .on('click', this.settings.eventScope+' #jquery-edit-filter', function (e) {
                this.setSearchFormTitle('Filter aanpassen: ' + this.activeFilter.name);
                $('#jquery-save-filter-as-from-modal').show();
                Popup.showSearch();
                // this.bindActiveFilterDataToFilterModal();
            }.bind(this))

            .on('click', this.settings.eventScope+' #jquery-save-filter', function (e) {
                this.saveFilter(e);
            }.bind(this))


            .on('click', this.settings.eventScope+' #jquery-reset-filter', function (e) {
                if (!$(e.target).hasClass('disabled')) {
                    this.resetSearchForm();
                    this.renderSelectFilterBox('');
                }
            }.bind(this))

            .on('click', this.settings.eventScope+' #jquery-delete-filter', function (e) {
                this.deleteFilter();
            }.bind(this))
            .on('click', this.settings.eventScope+' #jquery-save-filter-from-modal', function (e) {
                Popup.closeSearch();
                this.saveFilter(e);
            }.bind(this))
            .on('click', this.settings.eventScope+' #jquery-save-filter-as-from-modal', function (e) {
                Popup.closeSearch();

                this.saveFilter(e);
            }.bind(this))

            .on('click', this.settings.eventScope+' #jquery-cache-filter-from-modal', function (e) {
                Popup.closeSearch();
            }.bind(this))

        };

    this.resetSearchForm = function () {
        this.filterFields.forEach(function (item) {
            let input = this.getJqueryFilterInput(item.field);
            let newValue = '';

            if (input.get(0).tagName == 'SELECT') {
                newValue = '0';
            }
            input.val(newValue).trigger('change');
        }.bind(this));
    };

    this.saveFilter = function (e) {
        const saveAs = e.target.id === 'jquery-save-filter-as-from-modal';

        if (!$(e.target).hasClass('disabled')) {
            const isNewFilter = (this.activeFilter.id === '')
            Popup.prompt({
                    text: 'Wat is de naam van dit filter?',
                    title: saveAs ? 'Opslaan als' : 'Opslaan',
                    inputValue: isNewFilter
                        ? 'Nieuw Filter'
                        : saveAs ? this.activeFilter.name + ' copy' : this.activeFilter.name,
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
    };

    this.saveActiveFilterAs = function (newFilterName) {
        const copyActiveFilter = JSON.parse(JSON.stringify(this.activeFilter));
        delete (copyActiveFilter.uuid);
        copyActiveFilter.key = this.settings.filterKey;
        copyActiveFilter.name = newFilterName;

        $.ajax({
            url: '/search_filter/add',
            data: {
                data: {
                    search_filter: copyActiveFilter,
                }
            },
            method: 'POST',
            context: this,
            dataType: 'json',
            success: function (response) {
                this.filters.push(response.data);
                this.renderSelectFilterBox(response.data.id);
                this.initNewFilter();
                this.activeFilter.changed = false;

                Notify.notify('Filter opgeslagen');
                this.enableDeleteButton();
                this.disableSaveButton();
            },
        });
    };

    this.disableSaveButton = function () {
        $('#jquery-save-filter').addClass('disabled');
    },
    this.enableSaveButton = function () {
        $('#jquery-save-filter').removeClass('disabled');
    },

    this.saveNewFilter = function (newFilterName) {
        $.ajax({
            url: '/search_filter/add',
            data: {
                data: {
                    search_filter: {
                        key: this.settings.filterKey,
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
                this.activeFilter = response.data;
                this.initNewFilter()
                this.activeFilter.changed = false;
                this.disableSaveButton();
                Notify.notify('Filter opgeslagen');
            },
        });
    };

    this.saveActiveFilter = function (newFilterName) {
        this.activeFilter.name = newFilterName;
        $.ajax({
            url: '/search_filter/edit/' + this.activeFilter.uuid,
            type: 'PUT',
            dataType: 'json',
            data: {
                search_filter: this.activeFilter,
            },
            context: this,
            success: function (response) {
                Notify.notify('Filter opgeslagen');
                this.renderSelectFilterBox(this.activeFilter.id);
                //TODO splice the current filter from the array and replace with the new one;
                this.filters = this.filters.map(function (filter) {
                    if (filter.id == this.activeFilter.id) {
                        return this.activeFilter;
                    }
                    return filter;
                }.bind(this));


                this.setActiveFilter(this.activeFilter.id);
                this.renderSelectFilterBox(this.activeFilter.id);
                this.activeFilter.changed = false;
                this.disableSaveButton();
            },
        });
    };

    this.deleteFilter = function () {
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
                        Notify.notify('Het filter is succesvol verwijderd.');
                        this.disableDeleteButton();
                    },
                });
            }
        }.bind(this));
    };

    this.disableDeleteButton = function () {
        $('#jquery-delete-filter').addClass('disabled');
    };
    this.enableDeleteButton = function () {
        $('#jquery-delete-filter').removeClass('disabled');
    };

    this.setActiveFilter = function (filterId) {
        if (filterId == '') return;

        let filterToClone = this.filters.find(function (filter) {
            return filter.id == filterId;
        });
        this.editFilter = JSON.parse(JSON.stringify(filterToClone));
        // clone the object using the oldest trick in the book because we have no deep clone helper;

        this.activeFilter = this.editFilter;
        this.filters.forEach(function(filter,index){
            filter.active = false;
            if(filter.id==filterId){
                filter.active = true;
            }
        },filterId);
        this.renderActiveFilter();
    };

    this.bindActiveFilterDataToFilterModal = function () {
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
        this.initializeSelect2Fields();
    };

    this.getJqueryFilterInput = function (name) {
        return $(this.settings.formPrefix + name.charAt(0).toUpperCase() + name.slice(1));
    };

    this.renderActiveFilter = function (e) {
        this.bindActiveFilterDataToFilterModal();
        this.initializeSelect2Fields();
        if (e instanceof Event) {
            e.stopPropagation();
        }
        let hasActualFilter = false;
        $('#jquery-filter-filters').html('');
        if (this.activeFilter) {

            $('#jquery-applied-filters').show();
            for (const [key, filterDetail] of Object.entries(this.activeFilter.filters)) {

                if (filterDetail.filter && filterDetail.name) {
                    let input = this.getJqueryFilterInput(key);

                    if (input.get(0).tagName === 'SELECT' && filterDetail.filter == '0') continue;

                    if (input.get(0).tagName === 'INPUT' && filterDetail.filter == '') continue;

                    hasActualFilter = true;


                    let label = Array.isArray(filterDetail.filter)
                        ? filterDetail.name + ': ' + input.find(':selected').toArray().map(function (option) {
                        return option.innerText;
                    }).sort().join(', ')
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
        if (this.activeFilter.hasOwnProperty('changed') && this.activeFilter.changed) {
            this.enableSaveButton();
        } else {
            this.disableSaveButton();
        }
    };

    this.addChangeEventsToFilter = function (context) {
        this.filterFields.forEach(function (item) {
            var selector = this.settings.formPrefix + item.field.charAt(0).toUpperCase() + item.field.slice(1);
            $(document).on('change', this.settings.eventScope +' '+ selector, function (e) {
                this.syncFilterField($(e.target), item);
            }.bind(this));
        }.bind(this));
    };

    this.syncFilterField = function (el, item) {
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
        if (!this.isInitalizingState) {
            this.activeFilter.changed = true;
        }
        this.renderActiveFilter();
    };

    this.getFilterLabelByField = function (field, context) {
        let labelField = context.filterFields.find(function (item) {
            return item.field == field;
        });
        return labelField.label;
    };
    this.setSearchFormTitle = function (title) {
        $('#modal-head').html(title);
    };
    this.developmentErrors = function () {
        if (!this.settings.hasOwnProperty('filterKey')) {
            alert('settings needs a valid filterKey');
        }
        if ($(this.el).length === 0) {
            alert(`${this.el} not present`);
        }
    };
    this.initTablefy = function(){
        let tablefySettings = {
                            'source': this.settings.tablefy.source,
                            'filters': $(this.settings.tablefy.filters),
                            'container': $(this.settings.tablefy.container)
                        }
        $(this.settings.table).tablefy(tablefySettings);
    }
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
