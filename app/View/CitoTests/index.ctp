<div id="TestTakesCito">
<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Popup.load('/test_takes/add',1000);">
        <span class="fa fa-calendar-o mr5"></span>
        Toetsen inplannen
    </a>



    <div class='popup' id='popup_search' style="display:none">
        <div class="popup-head" id="modal-head">Zoeken</div>
        <div class="popup-content">
        <div id="testFilters">
            <?=$this->Form->create('Test')?>
                <div class="row">
                    <div class="col-md-5">
                        <label>Toets</label>
                        <?=$this->Form->input('name', array('label' => false)) ?>
                    </div>
                    <div class="col-md-5">
                        <label>Type</label>
                        <?=$this->Form->input('kind', array('options' => $kinds, 'label' => false)) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <label>Vak</label>
                        <?=$this->Form->input('subject', array('options' => $subjects, 'label' => false)) ?>
                    </div>
                    <div class="col-md-5">
                        <label>Periode</label>
                        <?=$this->Form->input('period', array('options' => $periods, 'label' => false)) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <label>Niveau</label>
                        <?=$this->Form->input('education_levels', array('options' => $education_levels, 'label' => false)) ?>
                    </div>
                    <div class="col-md-5">
                        <label>Niveau jaar</label>
                        <?=$this->Form->input('education_level_years', array('options' => $education_level_years, 'label' => false)) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <label>Aangemaakt van</label>
                        <?=$this->Form->input('created_at_start', array('label' => false)) ?>
                    </div>
                    <div class="col-md-5">
                        <label>Aangemaakt tot</label>
                        <?=$this->Form->input('created_at_end', array('label' => false)) ?>
                    </div>
                </div>

                <?php if(true): ?>
                <div class="row">
                  <div class="col-md-5">
                        <label>Bron</label>
                        <?=$this->Form->input('is_open_sourced_content',array(
                      'options'=>[ 'Alles', 'Eigen content', 'Gratis content' ],'label'=>false
                    )) ?>
                  </div>
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
</div>
<div class="block autoheight">
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
        $(document).ready(function () {
            if (typeof (citoTestsFiltermanager) === 'undefined') {
                const settings = {
                    filterFields: [
                        {field: 'name', label: 'Toets', type: 'text'},
                        {field: 'kind', label: 'Type', type: 'select'},
                        {field: 'subject', label: 'Vak', type: 'multiSelect'},
                        {field: 'period', label: 'Periode', type: 'select'},
                        {field: 'educationLevels', label: 'Niveau', type: 'multiSelect'},
                        {field: 'educationLevelYears', label: 'Leerjaar', type: 'multiSelect'},
                        {field: 'isOpenSourcedContent', label: 'Bron'},
                        {field: 'createdAtStart', label: 'Aanmaakdatum van', type: 'datePicker'},
                        {field: 'createdAtEnd', label: 'Aanmaakdatum tot', type: 'datePicker' },
                    ],
                    eventScope:'#TestTakesCito',
                    formPrefix: '#Test',
                    table: '#testsTable',
                    tablefy: {
                        'source' : '/cito_tests/load',
                        'filters' : $('#TestIndexForm'),
                        'container' : $('#testsContainter')
                    },
                    filterKey: 'cito_tests',
                };

                citoTestsFiltermanager = new FilterManager(settings);
            }

            citoTestsFiltermanager.init();


        });

    </script>
    </div>
    <div class="block-footer"></div>
</div>
