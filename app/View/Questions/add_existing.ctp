
<div class="popup-head"><?= __("Toetsvraag importeren") ?></div>
<div class="popup-content">
    <div id="QuestionBank">
        <div class='popup' id='popup_search' style="display:none;">
            <div class="popup-head" id="modal-head"><?= __("Zoeken") ?></div>
            <div class="popup-content">
                <div id="testsFilter">
                    <?= $this->Form->create('Question') ?>
                    <div class="row">
                        <div class="col-md-5">
                            <label><?= __("Titel (trefwoord)") ?></label>
                            <?= $this->Form->input('search', array('style' => 'width: 100%','label' => false)) ?>
                        </div>
                        <div class="col-md-5">
                            <label for=""><?= __("Uniek ID") ?></label>
                            <?= $this->Form->input('id', array('label' => false, 'type' => 'text')) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label for=""><?= __("Examenvak") ?></label>
                            <?= $this->Form->input('base_subject_id', array('options' => $baseSubjects, 'label' => false)) ?>
                        </div>
                        <div class="col-md-5">
                            <label for=""><?= __("Vak")?></label>
                            <?= $this->Form->input('subject', array('style' => 'width: 100%', 'options' => $subjects, 'label' => false, 'multiple' => true)) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label for=""><?= __("Niveau") ?></label>
                            <?= $this->Form->input('education_levels', array('style' => 'width: 100%', 'options' => $education_levels, 'label' => false, 'multiple' => true)) ?>
                        </div>
                        <div class="col-md-5">
                            <label for=""><?= __("Leerjaar") ?></label>
                            <?= $this->Form->input('education_level_years', array('options' => $education_level_years, 'label' => false)) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label for=""><?= __("Bron") ?></label>
                            <?= $this->Form->input('source', array('options' => $filterSource, 'label' => false)) ?>
                        </div>
                        <div class="col-md-5">
                            <label><?= __("Type") ?></label>
                            <?= $this->Form->input('type', array('options' => $filterTypes, 'label' => false)) ?>
                        </div>
                    </div>
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
                <a href="#" id="jquery-cache-filter-from-modal" style="float:right"
                   class="btn grey pull-right mr5 mt5 inline-block">Bevestigen</a>

            </div>
        </div>
        <div class="block">
            <div class="block-content">
                <table id="filterTable" class="table ">
                    <tbody>
                    <tr>
                        <th width="150">Kies filter</th>
                        <td colspan="2">
                            <select name="opgelagen filters" id="jquery-saved-filters">
                            </select>
                        </td>
                        <td width="380">
                            <a href="#" class="btn inline-block btn-default grey disabled mr2"
                               id="jquery-delete-filter">Verwijderen</a>
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
    </div>
    <br clear="all"/>

    <div id="questionsContainter" style="height:500px; overflow: auto;">
        <table class="table table-striped" id="questionsTable">
            <thead>
            <tr>
                <th></th>
                <th></th>
                <th><?= __("Vraag") ?></th>
                <th><?= __("Leerjaar") ?></th>
                <th><?= __("Niveau") ?></th>
                <th><?= __("Type") ?></th>
                <th><?= __("Punten") ?></th>
                <th><?= __("Tags") ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#popup_search').prependTo('#hereComesFilterPopup');
                var QuestionBankFirstTimeRun = false;
                if (typeof (Window.authors) === 'undefined') {
                    Window.authors = {};
                }

                if (typeof (QuestionBankFiltermanager) === 'undefined') {
                    QuestionBankFirstTimeRun = true;
                    QuestionBankFiltermanager = new FilterManager({
                        filterFields: [
                            {field: 'search', label: 'titel', type: 'text'},
                            {field: 'subject', label: 'Vak', type: 'multiSelect'},
                            {field: 'educationLevels', label: 'Niveau', type: 'multiSelect'},
                            {field: 'educationLevelYears', label: 'Leerjaar', type: 'multiSelect'},
                            {field: 'source', label: 'Bron'},
                        ],
                        eventScope: 'body:first',
                        formPrefix: '#Question',
                        table: '#questionsTable',
                        tablefy: {
                            'source': '/questions/add_existing_question_list',
                            'filters': '#QuestionAddExistingForm',
                            'container': '#questionsContainter',
                        },
                        filterKey: 'question_bank'
                    });
                }
                QuestionBankFiltermanager.init(QuestionBankFirstTimeRun, true);

            });
        </script>
    </div>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        <?= __("Annuleer") ?>
    </a>
</div>

<script type="text/javascript">
    $('#QuestionSearch').select2({
        tags: true
    });
</script>
