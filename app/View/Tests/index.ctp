<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Popup.load('/test_takes/add',1000);">
        <span class="fa fa-calendar-o mr5"></span>
        Toetsen inplannen
    </a>


    <a href="#" class="btn white" onclick="Popup.load('/tests/add', 1000);">
        <span class="fa fa-plus mr5"></span>
        Toets construeren
    </a>

    <div class="dropblock" for="jquery-add-filter">
        <?= $this->Form->create('Test') ?>
        <table id="testsFilter" class="mb5">
            <tr>
                <th>Toets</th>
                <td>
                    <?= $this->Form->input('name', array('label' => false)) ?>
                </td>
            </tr>
            <tr>
                <th>Type</th>
                <td>
                    <?= $this->Form->input('kind', array('options' => $kinds, 'label' => false)) ?>
                </td>
            </tr>
            <tr>
                <th>Vak</th>
                <td>
                    <?= $this->Form->input('subject', array('options' => $subjects, 'label' => false)) ?>
                </td>
            </tr>
            <tr>
                <th>Periode</th>
                <td>
                    <?= $this->Form->input('period', array('options' => $periods, 'label' => false)) ?>
                </td>
            </tr>
            <tr>
                <th>Niveau</th>
                <td>
                    <?= $this->Form->input('education_levels', array('options' => $education_levels, 'label' => false)) ?>
                </td>
            </tr>
            <tr>
                <th>Niveau jaar</th>
                <td>
                    <?= $this->Form->input('education_level_years', array('options' => $education_level_years, 'label' => false)) ?>
                </td>
            </tr>

            <?php if (true): ?>
                <th>Bron</th>
                <td>
                    <?= $this->Form->input('is_open_sourced_content', array(
                        'options' => ['Alles', 'Eigen content', 'Gratis content'], 'label' => false
                    )) ?>
                </td>
            <?php endif; ?>

            <tr>
                <th>Aangemaakt van</th>
                <td>
                    <?= $this->Form->input('created_at_start', array('label' => false)) ?>
                </td>
            </tr>

            <tr>
                <th>Aangemaakt tot</th>
                <td>
                    <?= $this->Form->input('created_at_end', array('label' => false)) ?>
                </td>
            </tr>
        </table>
        <?= $this->Form->end(); ?>

        <a href="#" class="btn btn-close white small pull-right">Sluiten</a>
        <a href="#" class="btn btn-reset white small pull-right mr5">Reset</a>
        <br clear="all"/>
    </div>
</div>

<h1>Toetsen</h1>
<div class="block">
    <div class="block-content">
        <div class="block-head">Zoeken/Filteren</div>
        <table id="filterTable" class="table ">
            <tbody>
            <tr>
                <th>Opgelagen filters</th>
                <td colspan="2">
                    <select name="opgelagen filters" id="jquery-saved-filters">
                    </select>
                </td>
                <td>
                    <a href="#" class="btn inline-block btn-default grey mr2" id="jquery-delete-filter">Verwijderen</a>
                    <a href="#" class="btn inline-block grey dropblock-owner dropblock-left mr2" id="jquery-add-filter">
                        <span class="fa fa-filter mr5"></span>
                        Nieuw Filter maken
                    </a>
                </td>
            </tr>

            <tr id="jquery-applied-filters">
                <th>Toegepast filter</th>
                <td id="jquery-filter-name">[naam filter]</td>

                <td id="jquery-filter-filters">

                </td>
                <td>
                    <a href="#" class="btn inline-block grey mr2" id="jquery-edit-filter">Aanpassen</a>
                    <a href="#" class="btn inline-block grey mr2" id="jquery-save-filter">Opslaan</a>
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
                filterFields: [
                    {field: 'name', label: 'Toets'},
                    {field: 'kind', label: 'Type'},
                    {field: 'subject', label: 'Vak'},
                    {field: 'period', label: 'Periode'},
                    {field: 'educationLevels', label: 'Niveau'},
                    {field: 'educationLevelYears', label: 'Niveau jaar'},
                    {field: 'isOpenSourcedContent', label: 'Bron'},
                    {field: 'createdAtStart', label: 'Aanmaakdatum van'},
                    {field: 'createdAtEnd', label: 'Aanmaakdatum tot'},
                ],

                init: function () {
                    this.load();
                    this.el = '#jquery-saved-filters';
                    this.initializeSavedFilterSelect();
                    this.registerEvents();
                    this.addChangeEventsToNewFilter(this);
                },
                initializeSavedFilterSelect: function () {
                    $('#jquery-applied-filters').hide();
                    $(this.el).append($('<option></option>').attr('value', '').text('Kies een opgeslagen filter...'));
                    var that = this;
                    $(this.filters).each(function (key, filter) {
                        $(that.el).append($('<option></option>').attr('value', filter.id).text(filter.name));
                    });
                },
                initNewFilter: function () {
                    var that = this;
                    this.filterFields.forEach(function (item) {
                        that.newFilter[item.field]
                    })
                },
                registerEvents: function () {
                    var that = this;
                    $(document)
                        .on('change', that.el, function () {
                            if ($(that.el).val() === '') {
                                $('#jquery-applied-filters').hide();
                            } else {
                                that.setActiveFilter($(that.el).val());
                                $('#jquery-applied-filters').show();
                            }
                        })
                        .on('click', '.jquery-remove-filter', function (e) {
                            var prop = $(e.target).attr('jquery-filter-key');
                            that.activeFilter.filters[prop] = {name: '', filter: '', label: ''};
                            that.renderActiveFilter();
                        })
                        .on('click', '#jquery-add-filter', function (e) {
                            $(that.el).val('');
                            that.activeFilter = {
                                id: '',
                                name: 'New Filter',
                                filters: that.newFilter
                            }
                            that.renderActiveFilter(e);
                        })
                        .on('click', '#jquery-edit-filter', function (e) {
                            alert('edit filter');
                        })
                        .on('click', '#jquery-save-filter', function (e) {
                            alert('save filter');
                        })
                        .on('click', '#jquery-reset-filter', function (e) {
                            alert('reset filter');
                        })
                        .on('click', '#jquery-delete-filter', function (e) {
                            alert('delete filter');
                        })
                        .on('click', '#jquery-create-filter', function (e) {
                            alert('Create filter');
                        });
                },
                setActiveFilter(filterId) {
                    if (filterId == '') return;

                    this.activeFilter = this.filters.find(function (filter) {
                        return filter.id === filterId;
                    });
                    this.renderActiveFilter();
                },
                renderActiveFilter: function (e) {
                    if (e instanceof Event) {
                        e.stopPropagation();
                    }
                    $('#jquery-applied-filters').show();
                    $('#jquery-filter-name').html(this.activeFilter.name);
                    $('#jquery-filter-filters').html('');
                    for (const [key, filterDetail] of Object.entries(this.activeFilter.filters)) {
                        if (filterDetail.filter && filterDetail.name) {
                            $('#jquery-filter-filters').append($(
                                `<span class="mr2 inline-block">
                            <button class="label label-default jquery-remove-filter" jquery-filter-key="${key}">x</button>
                            <div style="display:inline-block; padding-left:4px">${filterDetail.name}: ${filterDetail.label}</div>
                        </span>`));
                        }
                    }
                },
                addChangeEventsToNewFilter: function (context) {
                    this.filterFields.forEach(function (item) {
                        var selector = '#Test' + item.field.charAt(0).toUpperCase() + item.field.slice(1);
                        $(document).on('change', selector, function (e) {
                            this.foo($(e.target), item);
                        }.bind(context));
                    });

                    $(document).on('click', '.btn-reset', function (e) {
                        context.filterFields.forEach(function (item) {
                            let selector = '#Test' + item.field.charAt(0).toUpperCase() + item.field.slice(1);
                            this.foo($(selector), item);
                        }.bind(context));
                    });
                },
                foo: function (el, item) {
                    console.dir(item);
                    if (el.is('select')) {
                        this.newFilter[item.field] = {
                            name:this.getFilterLabelByField(item.field, this),
                            filter: el.val(),
                            label: el.find(':selected').text(),
                        }
                    } else {
                        this.newFilter[item.field] = {
                            name: this.getFilterLabelByField(item.field, this),
                            filter: el.val(),
                            label: el.val(),
                        }
                    }
                    this.renderActiveFilter();
                },

                getFilterLabelByField: function(field, context){
                    let labelField = context.filterFields.find(function(item){
                        return item.field == field;
                    });
                    return labelField.label;
                },

                load: function () {
                    this.filters = [
                        {
                            id: 'abc',
                            name: 'Toetsen 2019',
                            filters: {
                                name: {name: '', filter: '', label: ''},
                                kind: {name: '', filter: '', label: ''},
                                subject: {name: 'Vak', filter: 'Wiskunde', label: 'Wiskunde'},
                                period: {name: 'Niveau', filter: 'havo', label: 'Havo'},
                                eductionLevels: {name: '', filter: '', label: ''},
                                isOpenSourceContent: {name: '', filter: '', label: ''},
                                createdAtStart: {name: '', filter: '', label: ''},
                                createdAtEnd: {name: '', filter: '', label: ''},
                            }
                        }, {
                            id: 'def',
                            name: 'Toetsen 2020',
                            filters: {
                                name: {name: '', filter: '', label: ''},
                                kind: {name: '', filter: '', label: ''},
                                subject: {name: 'Vak', filter: 'Natuurkunde', label: 'Natuurkunde'},
                                period: {name: 'Niveau', filter: 'havo', label: 'havo'},
                                eductionLevels: {name: '', filter: '', label: ''},
                                isOpenSourceContent: {name: '', filter: '', label: ''},
                                createdAtStart: {name: '', filter: '', label: ''},
                                createdAtEnd: {name: '', filter: '', label: ''},
                            }
                        }
                    ];
                }
            }

            $(document).ready(function () {
                FilterManager.init();
            });

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
