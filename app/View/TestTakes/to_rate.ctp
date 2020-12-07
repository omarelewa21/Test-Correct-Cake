<div id="TestTakesToRate">
    <div id="buttons">

        <div class='popup' id='popup_search' style="display:none">
            <div class="popup-head" id="modal-head">Zoeken</div>
            <div class="popup-content">
                <div id="testTakeFilters">
                    <?= $this->Form->create('TestTake') ?>
                    <?
                    $retakeOptions = array(
                        -1 => 'Alle',
                        0  => 'Standaard',
                        1  => 'Inhaaltoetsen'
                    );
                    $archivedOptions = array(
                        0 => 'Niet tonen',
                        1 => 'Tonen',
                    );
                    ?>
                    <table id="testTakeFilters" class="mb5">
                        <div class="row">
                            <div class="col-md-5">
                                <label>Periode</label>
                                <?= $this->Form->input('period_id', array('options' => $periods, 'label' => false)) ?>
                            </div>
                            <div class="col-md-5">
                                <label>Type</label>
                                <?= $this->Form->input('retake', array('options' => $retakeOptions, 'label' => false)) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <label>Gepland van</label>
                                <?= $this->Form->input('time_start_from', array('label' => false)) ?>
                            </div>
                            <div class="col-md-5">
                                <label>Gepland tot</label>
                                <?= $this->Form->input('time_start_to', array('label' => false)) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <label>Gearchiveerd</label>
                                <?= $this->Form->input('archived', array('options' => $archivedOptions, 'label' => false)) ?>
                            </div>
                            <div class="col-md-5">
                                <label>Klas</label>
                                <?= $this->Form->input('school_class_name', array('label' => false)) ?>
                            </div>
                        </div>
                        
                    </table>
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

    <h1>Na te kijken</h1>

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
                <th>Toets</th>
                <th>Klas</th>
                <th>Surveillanten</th>
                <th width="50">Vragen</th>
                <th width="200">Ingepland door</th>
                <th width="120">Vak</th>
                <th width="90">Afname</th>
                <th width="80">Type</th>
                <th width="60">Weging</th>
                <th width="60">Aanwezig</th>
                <th width="60">Afwezig</th>
                <th width="80">&nbsp;</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">
            // $('#testsTable').tablefy({
            //     'source': '/test_takes/load_to_rate',
            //     'filters': $('#TestTakeToRateForm'),
            //     'container': $('#testsContainter')
            // });

            // $('#TestTakeTimeStartFrom, #TestTakeTimeStartTo').datepicker({
            //     dateFormat: 'dd-mm-yy'
            // });
        </script>

        <script type="text/javascript">
            $(document).ready(function () {

                let testtakesToRateFirstTimeRun = false;
                if (typeof (testtakesToRateFiltermanager) === 'undefined') {
                    testtakesToRateFirstTimeRun = true;
                }

                testtakesToRateFiltermanager = new FilterManager({
                    filterFields: [
                        {field: 'periodId', label: 'Periode', type: 'select'},
                        {field: 'retake', label: 'Type', type: 'select'},
                        {field: 'timeStartFrom', label: 'Gepland van', type: 'datePicker'},
                        {field: 'timeStartTo', label: 'Gepland tot', type: 'datePicker'},
                        {field: 'archived', label: 'Gearchiveerd', type: 'select'},
                        {field: 'schoolClassName', label: 'Klas', type: 'text'},
                    ],
                    eventScope: '#TestTakesToRate',
                    formPrefix: '#TestTake',
                    table: '#testsTable',
                    tablefy: {
                        'source': '/test_takes/load_to_rate',
                        'filters': $('#TestTakeToRateForm'),
                        'container': $('#testsContainter')
                    },
                    filterKey: 'testtakes_to_rate',
                });


                testtakesToRateFiltermanager.init(testtakesToRateFirstTimeRun);


            });

        </script>
    </div>
    <div class="block-footer"></div>
</div>
<style>
    .jquery-not-archived .jquery-show-not-archived {
        display: block;
    }

    .jquery-not-archived .jquery-show-when-archived {
        display: none;
    }

    .jquery-archived .jquery-show-not-archived {
        display: none;
    }

    .jquery-archived .jquery-show-when-archived {
        display: block;
    }

    .jquery-archived {
        color: grey;
    }

</style>
