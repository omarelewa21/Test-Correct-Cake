<div id="ItemBank">
    <div id="buttons">
        <a href="#" class="btn white mr2" onclick="Popup.load('/test_takes/add',1000);">
            <span class="fa fa-calendar-o mr5"></span>
            <?= __("Toetsen inplannen")?>
        </a>
        <a href="#" class="btn white" onclick="Popup.load('/tests/add', 1000);">
            <span class="fa fa-plus mr5"></span>
            <?= __("Toets construeren")?>
        </a>
        <div class='popup' id='popup_search' style="display:none">
            <div class="popup-head" id="modal-head"><?= __("Zoeken")?></div>
            <div class="popup-content">
                <div id="testsFilter">
                    <?= $this->Form->create('Test') ?>
                    <div class="row">
                        <div class="col-md-5">
                            <label><?= __("Titel (trefwoord)")?></label>
                            <?= $this->Form->input('name', array('label' => false)) ?>
                        </div>
                        <div class="col-md-5">
                            <label><?= __("Type")?></label>
                            <?= $this->Form->input('kind', array('options' => $kinds, 'label' => false)) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5"><label for=""><?= __("Vak")?></label>
                            <?= $this->Form->input('subject', array('style' => 'width: 100%', 'options' => $subjects, 'label' => false, 'multiple' => true)) ?>
                        </div>

                        <div class="col-md-5">
                            <label for="Periode"><?= __("Periode")?></label>
                            <?= $this->Form->input('period', array('options' => $periods, 'label' => false)) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <label for=""><?= __("Niveau")?></label>
                            <?= $this->Form->input('education_levels', array('style' => 'width: 100%', 'options' => $education_levels, 'label' => false, 'multiple' => true)) ?>
                        </div>
                        <div class="col-md-5">
                            <label for=""><?= __("Leerjaar")?></label>
                            <?= $this->Form->input('education_level_years', array('placeholder' => __("Alle"), 'style' => 'width: 100%', 'label' => false, 'options' => $education_level_years, 'multiple' => true)) ?>
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

                    <?php if (false): ?>
                        <div class="row">
                            <div class="col-md-5">
                                <label for=""><?= __("Bron")?></label>
                                <?= $this->Form->input('is_open_sourced_content', array(
                                    'options' => [__("Alles"), __("Eigen content"), __("Gratis content")], 'label' => false
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
                   class="btn blue pull-right mr5 mt5 inline-block"><?= __("Opslaan")?></a>
                <a href="#" style="float:right"
                   id="jquery-save-filter-as-from-modal"
                   class="btn grey pull-right mr5 mt5 inline-block"><?= __("Opslaan als")?></a>
                <a href="#" id="jquery-cache-filter-from-modal"  style="float:right"
                   class="btn grey pull-right mr5 mt5 inline-block"><?= __("Bevestigen")?></a>

            </div>

        </div>

    </div>

    <h1><?= __("Toetsitems binnen uw schoollocatie")?></h1>
    <div class="block">
        <div class="block-content">
            <div class="block-head"><?= __("Filteren")?></div>
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
    <!--    <div class="block-head">Toetsen</div>-->
    <div class="block-content" id="testsContainter">
        <table class="table table-striped" id="testsTable">
            <thead>
            <tr>
                <th></th>
                <th sortkey="abbriviation" width="50"><?= __("Afk")?>.</th>
                <th sortkey="name"><?= __("Titel")?></th>
                <th width="70" style="text-align: center" sortkey="subject"><?= __("Vragen")?></th>
                <th width="170" sortkey="subject"><?= __("Vak")?></th>
                <th width="170" sortkey="author"><?= __("Auteur")?></th>
                <th width="170" sortkey="kind"><?= __("Type")?></th>
                <th width="150" sortkey="level"><?= __("Niveau")?></th>
                <th width="100">&nbsp;</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">


            $(document).ready(function () {
                var itemBankFirstTimeRun = false;
                if (typeof (itembankFiltermanager) === 'undefined') {
                    itemBankFirstTimeRun = true;
                    itembankFiltermanager = new FilterManager({
                        filterFields: [
                            {field: 'name', label: '<?= __("Toets")?>', type: 'text'},
                            {field: 'kind', label: '<?= __("Type")?>', type: 'select'},
                            {field: 'subject', label: '<?= __("Vak")?>', type: 'multiSelect'},
                            {field: 'period', label: '<?= __("Periode")?>', type: 'select'},
                            {field: 'educationLevels', label: '<?= __("Niveau")?>', type: 'multiSelect'},
                            {field: 'educationLevelYears', label: '<?= __("Leerjaar")?>', type: 'multiSelect'},
                            // // {field: 'isOpenSourcedContent', label: 'Bron'},
                            {field: 'createdAtStart', label: '<?= __("Aanmaakdatum van")?>', type: 'datePicker'},
                            {field: 'createdAtEnd', label: '<?= __("Aanmaakdatum tot")?>', type: 'datePicker'},
                        ],
                        eventScope: '#ItemBank',
                        formPrefix: '#Test',
                        table: '#testsTable',
                        tablefy: {
                            'source': '/tests/load',
                            'filters': '#TestIndexForm',
                            'container': '#testsContainter'
                        },
                        filterKey: 'item_bank',
                    });
                }
                
                itembankFiltermanager.init(itemBankFirstTimeRun);
            });

        </script>
    </div>
    <div class="block-footer"></div>
</div>

