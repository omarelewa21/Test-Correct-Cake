<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Popup.load('/test_takes/add',1000);">
        <span class="fa fa-calendar-o mr5"></span>
        Toetsen inplannen
    </a>
    <a href="#" class="btn white" onclick="Popup.load('/tests/add', 1000);">
        <span class="fa fa-plus mr5"></span>
        Toets construeren
    </a>
    <div class='popup' id='popup_search' style="display:none">
        <div class="popup-head" id="modal-head">Zoeken</div>
        <div class="popup-content">
            <div id="testsFilter">
                <?= $this->Form->create('Test') ?>
                <div class="row">
                    <div class="col-md-5">
                        <label>Titel (trefwoord)</label>
                        <?= $this->Form->input('name', array('label' => false)) ?>
                    </div>
                    <div class="col-md-5">
                        <label>Type</label>
                        <?= $this->Form->input('kind', array('options' => $kinds, 'label' => false)) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5"><label for="">Vak</label>
                        <?= $this->Form->input('subject', array('options' => $subjects, 'label' => false)) ?>
                    </div>

                    <div class="col-md-5">
                        <label for="Periode">Periode</label>
                        <?= $this->Form->input('period', array('options' => $periods, 'label' => false)) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <label for="">Niveau</label>
                        <?= $this->Form->input('education_levels', array('options' => $education_levels, 'label' => false)) ?>
                    </div>
                    <div class="col-md-5">
                        <label for="">Leerjaar</label>
                        <?= $this->Form->input('education_level_years', array('placeholder' => 'Alle', 'style' => 'width: 100%', 'label' => false, 'options' => $education_level_years, 'multiple' => true)) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <label for="">Aangemaakt van</label>
                        <?= $this->Form->input('created_at_start', array('label' => false)) ?>
                    </div>

                    <div class="col-md-5">
                        <label for="">Aangemaakt tot</label>
                        <?= $this->Form->input('created_at_end', array('label' => false)) ?>
                    </div>
                </div>

                <?php if (false): ?>
                    <div class="row">
                        <div class="col-md-5">
                            <label for="">Bron</label>
                            <?= $this->Form->input('is_open_sourced_content', array(
                                'options' => ['Alles', 'Eigen content', 'Gratis content'], 'label' => false
                            )) ?>

                        </div>
                        <div class="col-md-5"> &nbsp;</div>
                    </div>
                <?php endif; ?>


                <?= $this->Form->end(); ?>
            </div>
        </div>
        <div class="popup-footer">
            <a href="#" style="float:right"
               id="jquery-save-filter-from-modal"
               class="btn blue pull-right mr5 mt5 inline-block">Opslaan</a>
            <a href="#" style="float:right"
               id="jquery-save-filter-as-from-modal"
               class="btn grey pull-right mr5 mt5 inline-block">Opslaan als</a>
            <a href="#" onclick="Popup.closeSearch()" style="float:right"
               class="btn grey pull-right mr5 mt5 inline-block">Bevestigen</a>

        </div>

    </div>

</div>

<h1>Toetsen</h1>
<div class="block">
    <div class="block-content">
        <div class="block-head">Filteren</div>
        <table id="filterTable" class="table ">
            <tbody>
            <tr>
                <th width="150">Kies filter</th>
                <td colspan="2">
                    <select name="opgelagen filters" id="jquery-saved-filters">
                    </select>
                </td>
                <td width="380">
                    <a href="#" class="btn inline-block btn-default grey disabled mr2" id="jquery-delete-filter">Verwijderen</a>
                    <a href="#" class="btn inline-block grey mr2" id="jquery-add-filter">
                        <span class="fa mr5"></span>
                        Nieuw filter maken
                    </a>
                </td>
            </tr>

            <tr id="jquery-applied-filters" style="display:none">
                <th>Toegepast filter</th>
                <td colspan="2" id="jquery-filter-filters"></td>
                <td>
                    <a href="#" class="btn inline-block grey mr2" id="jquery-edit-filter">
                        <span class="fa mr5"></span>Filter aanpassen
                    </a>
                    <a href="#" class="btn inline-block blue mr2 disabled" id="jquery-save-filter">Opslaan</a>
                    <a href="#" class="btn inline-block grey" id="jquery-reset-filter">Reset Filter</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="block autoheight">
    <!--    <div class="block-head">Toetsen</div>-->
    <div class="block-content" id="testsContainter">
        <table class="table table-striped" id="testsTable">
            <thead>
            <tr>
                <th></th>
                <th sortkey="abbriviation" width="50">Afk.</th>
                <th sortkey="name">Titel</th>
                <th width="70" style="text-align: center" sortkey="subject">Vragen</th>
                <th width="170" sortkey="subject">Vak</th>
                <th width="170" sortkey="author">Auteur</th>
                <th width="170" sortkey="kind">Type</th>
                <th width="150" sortkey="level">Niveau</th>
                <th width="100">&nbsp;</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">

            $('#TestCreatedAtStart, #TestCreatedAtEnd').datepicker({
                dateFormat: 'dd-mm-yy'
            });

            $('#testsTable').tablefy({
                'source': '/tests/load',
                'filters': $('#TestIndexForm'),
                'container': $('#testsContainter')
            });

            window.FilterManager = {
                el: false,
                filters: false,
                activeFilter: false,
                newFilter: {},
                editFilter: {'filters': {}},
                isInitalizing: true,
                filterFields: [
                    {field: 'name', label: 'Toets'},
                    {field: 'kind', label: 'Type'},
                    {field: 'subject', label: 'Vak'},
                    {field: 'period', label: 'Periode'},
                    {field: 'educationLevels', label: 'Niveau'},
                    {field: 'educationLevelYears', label: 'Leerjaar'},
                    // // {field: 'isOpenSourcedContent', label: 'Bron'},
                    {field: 'createdAtStart', label: 'Aanmaakdatum van'},
                    {field: 'createdAtEnd', label: 'Aanmaakdatum tot'},
                ],

                init: function () {
                    this.el = '#jquery-saved-filters';
                    $.getJSON('/search_filter/get/item_bank', function (response) {
                        this.filters = response.data;
                        this.initializeSavedFilterSelect();
                        this.registerEvents();
                        this.addChangeEventsToFilter(this);
                        this.initNewFilter();
                        this.bindActiveFilterDataToFilterModal();
                        this.reloadData();
                        this.isInitalizing = false;
                        $('#TestEducationLevelYears').select2();
                    }.bind(this));
                },
                initCheckboxListWithAllOption: function () {
                    $('.jquery-checkbox-list-with-all-option input:checkbox').each(function (input) {

                    });
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
                                var value = $(e.target).val()
                                if (value === '') {
                                    $('#jquery-applied-filters').hide();
                                    $('#jquery-delete-filter').addClass('disabled');
                                    $.getJSON('/search_filter/deactivate/' + this.activeFilter.uuid, function (response) {
                                    });
                                    this.activeFilter = false;
                                } else {
                                    this.setActiveFilter(value);
                                    $('#jquery-delete-filter').removeClass('disabled');
                                    $('#jquery-applied-filters').show();
                                    $.getJSON('/search_filter/activate/' + this.activeFilter.uuid, function (response) {
                                    });



                                }
                                this.reloadData();
                                this.activeFilter.changed = false;
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

                                let label = Array.isArray(filterDetail.filter) ? filterDetail.name+': '+filterDetail.filter.join(', ') : filterDetail.label;

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
                        $('#jquery-save-filter').removeClass('disabled');
                    }
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

            $(document).ready(function () {
                FilterManager.init();
            });
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

        </script>
    </div>
    <div class="block-footer"></div>
</div>
<style>
    a.btn.disabled {
        color: grey;
        cursor: not-allowed;
        background-color: #eeeeee !important;
    }

    a.btn.disabled:hover {
        color: grey;
        background-color: #eeeeee !important;
        cursor: not-allowed;
    }

    button.fa.fa-times-x-circle-o::after {
        content: "\f05c";
        font-family: FontAwesome;
        font-weight: normal;
        font-style: normal;
        margin: 0px 0px 0px 10px;
        text-decoration: none;
        color: #212529;
    }

    button.fa.fa-times-x-circle-o:hover::after {
        color: #fff;
        content: "\f014";
        padding-right: 1px;
    }

    button.label-search-filter {
        color: #212529;
        background-color: rgb(238, 238, 238);
    / / #f8f9fa;
        display: inline-block;
        padding: .45em .45em;
        font-size: 80%;
        font-weight: 500;
        font-family: Myriad Pro, Arial !important;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25rem;
        border: none;
    }

    button.label-search-filter:hover {
        color: #fff;
        text-decoration: none;
        background-color: #197cb4;
    / / #117a8b;
        cursor: pointer;
    }

    #modal-head.popup-head{
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding-left:22px;
        padding-right: 22px;
    }
    .select2-selection.select2-selection--multiple:hover{
        border:1px solid #1d93d6;
        cursor:pointer;
    }


</style>
