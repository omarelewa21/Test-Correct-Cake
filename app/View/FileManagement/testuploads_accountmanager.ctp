<h1><?= __("Bestanden")?></h1>

<div id="FileMangementTestuploads">
    <div class='popup' id='popup_search' style="display:none">
        <div class="popup-head" id="modal-head"><?= __("Zoeken")?></div>
        <div class="popup-content">
            <div id="testsFilter">
                <?= $this->Form->create('FileManagement') ?>
                <div class="row">
                    <div class="col-md-5">
                        <label><?= __("Toets")?></label>
                        <?= $this->Form->input('test_name', array('label' => false)) ?>
                        <label for=""><?= __("Vak")?></label>
                        <?= $this->Form->input('subject', array('label' => false)) ?>
                    </div>
                    <div class="col-md-5">
                        <label><?= __("Schoollocatie")?></label>
                        <?= $this->Form->input('schoolLocation', array('style' => 'width: 100%', 'options' => $schoolLocations, 'label' => false, 'multiple' => true)) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <label for=""><?= __("Status")?></label>
                        <?= $this->Form->input('statusIds', array('style' => 'width: 100%', 'options' => $statuses, 'label' => false, 'multiple' => true)) ?>
                    </div>

                    <div class="col-md-5">
                        <label for=""><?= __("Klantcode")?></label>
                        <?= $this->Form->input('customercode', array('label' => false)) ?>
                        <label for=""><?= __("Notitie")?></label>
                        <?= $this->Form->input('notes', array('label' => false)) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <label for=""><?= __("Docent")?></label>
                        <?= $this->Form->input('teacherId', array('placeholder' => __('Alle'), 'style' => 'width: 100%', 'label' => false, 'options' => [], 'multiple' => true)) ?>
                    </div>

                    <div class="col-md-5">
                        <label for=""><?= __("Behandelaar")?></label>
                        <?= $this->Form->input('handlerId', array('placeholder' => __('Alle'), 'style' => 'width: 100%', 'label' => false, 'options' => [], 'multiple' => true)) ?>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-5">
                        <label for=""><?= __("Niveau")?></label>
                        <?= $this->Form->input('education_levels', array('style' => 'width: 100%', 'options' => $education_levels, 'label' => false, 'multiple' => true)) ?>
                    </div>
                    <div class="col-md-5">
                        <label for=""><?= __("Leerjaar")?></label>
                        <?= $this->Form->input('education_level_years', array('style' => 'width: 100%','options' => $education_level_years, 'label' => false, 'multiple' => true)) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <label for=""><?= __("Aangemaakt van")?></label>
                        <?= $this->Form->input('created_at_start', array('label' => false)) ?>
                    </div>

                    <div class="col-md-5">
                        <label for=""><?= __("Aangemaakt tot")?></label>
                        <?= $this->Form->input('created_at_end', array('label' => false)) ?>
                    </div>
                </div>

                <?= $this->Form->end(); ?>
            </div>
        </div>
        <div class="popup-footer">
            <a href="#" style="float:right"
               id="jquery-save-filter-from-modal"
               class="btn blue pull-right mr5 mt5 inline-block"><?= __("Opslaan")?></a>
            <a href="#" style="float:right"
               id="jquery-save-filter-as-from-modal"
               class="btn grey pull-right mr5 mt5 inline-block"><?= __("Opslaan als")?></a>
            <a href="#" id="jquery-cache-filter-from-modal" style="float:right"
               class="btn grey pull-right mr5 mt5 inline-block"><?= __("Bevestigen")?></a>

        </div>

    </div>

    <!--    <h1>Toetsitems binnen uw schoollocatie</h1>-->

    <div class="block">
        <div class="block-content">
            <table id="filterTable" class="table ">
                <tbody>
                <tr>
                    <th width="150"><?= __("Kies filter")?></th>
                    <td colspan="2">
                        <select name="opgelagen filters" id="jquery-saved-filters">
                        </select>
                    </td>
                    <td width="380">
                        <a href="#" class="btn inline-block btn-default grey disabled mr2" id="jquery-delete-filter"><?= __("Verwijderen")?></a>
                        <a href="#" class="btn inline-block grey mr2" id="jquery-add-filter">
                            <span class="fa mr5"></span>
                            <?= __("Nieuw filter maken")?>
                        </a>
                    </td>
                </tr>

                <tr id="jquery-applied-filters" style="display:none">
                    <th><?= __("Toegepast filter")?></th>
                    <td colspan="2" id="jquery-filter-filters"></td>
                    <td>
                        <a href="#" class="btn inline-block grey mr2" id="jquery-edit-filter">
                            <span class="fa mr5"></span><?= __("Filter aanpassen")?>
                        </a>
                        <a href="#" class="btn inline-block blue mr2 disabled" id="jquery-save-filter"><?= __("Opslaan")?></a>
                        <a href="#" class="btn inline-block grey" id="jquery-reset-filter"><?= __("Reset Filter")?></a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="block autoheight">
    <div class="block-head"><?= __("Toetsen")?></div>
    <div class="block-content" id="filesContainter">
        <table class="table table-striped" id="filesTable">
            <thead>
            <tr>
                <th width="250" sortkey="createdAt"><?= __("Datum")?></th>
                <th width="200" sortkey="schoollocation"><?= __("School")?></th>
                <th width="74" sortkey="schoolLocationCode"><?= __("Code")?></th>
                <th width="250" sortkey="teacher"><?= __("Docent")?></th>
                <th sortkey="subject"><?= __("Vak")?></th>
                <th sortkey="name"><?= __("Naam")?></th>
                <th width="150"><?= __("Status")?></th>
                <th width="250" sortkey="handledby"><?= __("Behandelaar")?></th>
                <th width="100">&nbsp;</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">

            $(document).ready(function () {
                var fileMangementTestuploadsFirstTimeRun = false;
                if (typeof (Window.file_management_users) === 'undefined') {
                    Window.file_management_users = {};
                }

                if (typeof (fileMangementTestuploadsFiltermanager) === 'undefined') {
                    fileMangementTestuploadsFirstTimeRun = true;
                    fileMangementTestuploadsFiltermanager = new FilterManager({
                        filterFields: [
                            {field: 'testName', label: '<?= __("Toets")?>', type: 'text'},
                            {field: 'subject', label: '<?= __("Vak")?>', type: 'text'},
                            {field: 'customercode', label: '<?= __("Klantcode")?>', type: 'text'},
                            {field: 'notes', label: '<?= __("Notitie")?>', type: 'text'},
                            {field: 'schoolLocation', label: '<?= __("School location")?>', type: 'select'},
                            {field: 'educationLevels', label: '<?= __('Niveau')?>', type: 'multiSelect'},
                            {field: 'educationLevelYears', label: '<?= __('Leerjaar')?>', type: 'multiSelect'},
                            {field: 'teacherId', label: '<?= __("Teacher")?>', type: 'multiSelect'},
                            {field: 'handlerId', label: '<?= __("Handler")?>', type: 'multiSelect'},
                            {field: 'createdAtStart', label: '<?= __("Aanmaakdatum van")?>', type: 'datePicker'},
                            {field: 'statusIds', label: '<?= __('Status')?>', type: 'multiSelect'}
                        ],
                        eventScope: '#FileMangementTestuploads',
                        formPrefix: '#FileManagement',
                        table: '#filesTable',
                        tablefy: {
                            'source': '/file_management/load_testuploads',
                            'filters': '#FileManagementTestuploadsForm',
                            'container':  '#filesContainter',
                            'positionRuns': 6,
                            'afterFirstRunCallback': function (callback) {
                                Loading.hide();
                                Core.surpressLoading = true;
                                fileMangementTestuploadsFiltermanager.lockFilters();
                                $.ajax({
                                    url: '/file_management/get_users/testupload',
                                    type: 'GET',
                                    success: function (data) {
                                        var json = $.parseJSON(data);
                                        Window.file_management_users = json.data;
                                        setUsers();
                                        fileMangementTestuploadsFiltermanager.initCustom();
                                        Core.surpressLoading = false;
                                        fileMangementTestuploadsFiltermanager.unlockFilters();
                                        if (typeof (callback) == 'function') {
                                            callback();
                                        }
                                    }
                                });
                            }
                        },
                        filterKey: 'file_management_tests'
                    });
                }
                // if(!authorsIsEmpty()){
                //     setAuthors();
                // }else{
                //     itembankFiltermanager.prepareForAuthors();
                // }

                fileMangementTestuploadsFiltermanager.init(fileMangementTestuploadsFirstTimeRun, true);
            });

            function setUsers() {
                var teacher_select = $('#FileManagementTeacherId');
                var handler_select = $('#FileManagementHandlerId');
                handler_select.html('');
                teacher_select.html('');
                $.each(Window.file_management_users, function (key, value) {
                    var option = $('<option value="' + key + '">' + value + '</option>');
                    teacher_select.append(option);
                    handler_select.append(option.clone());
                });
            }

        </script>
    </div>
    <div class="block-footer"></div>
</div>
