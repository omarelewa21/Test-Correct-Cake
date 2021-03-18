<div id="TestTakesPlannedTeacher">
<div id="buttons">

    <a href="#" class="btn white mr2" onclick="Popup.load('/test_takes/add',1000);">
        <span class="fa fa-calendar-o mr5"></span>
        <?= __("Toetsen inplannen")?>
    </a>

    <a href="#" class="btn white mr2" onclick="Popup.load('/test_takes/start_multiple', 1000);">
        <span class="fa fa-calendar-o mr5"></span>
        <?= __("Toetsen afnemen")?>
    </a>


    <div class='popup' id='popup_search' style="display:none">
        <div class="popup-head" id="modal-head"><?= __("Zoeken")?></div>
        <div class="popup-content">
            <div id="testTakeFilters">
            <?=$this->Form->create('TestTake')?>
            <?
            $retakeOptions = array(
                -1 => __("Alle"),
                0 => __("Standaard"),
                1 => __("Inhaaltoetsen")
            );
            ?>
                <div class="row">
                    <div class="col-md-5">
                        <label><?= __("Periode")?></label>

                        <?=$this->Form->input('period_id', array('options' => $periods, 'label' => false)) ?>
                    </div>
                    <div class="col-md-5">
                        <label><?= __("Type")?></label>

                        <?=$this->Form->input('retake', array('options' => $retakeOptions, 'label' => false)) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <label><?= __("Gepland van")?></label>

                        <?=$this->Form->input('time_start_from', array('label' => false)) ?>
                    </div>
                    <div class="col-md-5">
                        <label><?= __("Gepland tot")?></label>

                        <?=$this->Form->input('time_start_to', array('label' => false)) ?>
                    </div>
                </div>
                <?=$this->Form->end();?>

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
</div>

<h1><?= __("Geplande toetsen")?></h1>

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
    <div class="block-content" id="testsContainter">
        <table class="table table-striped" id="testsTable">
            <thead>
            <tr>
                <th><?= __("Toets")?></th>
                <th><?= __("Klas")?></th>
                <th width="50"><?= __("Vragen")?></th>
                <th width="200"><?= __("Ingepland door")?></th>
                <th width="120"><?= __("Vak")?></th>
                <th width="90"><?= __("Afname")?></th>
                <th width="80"><?= __("Type")?></th>
                <th width="60"><?= __("Weging")?></th>
                <th width="80">&nbsp;</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">

        </script>
       <script type="text/javascript">




            $(document).ready(function () {


                var testtakesPlannedFirstTimeRun = false;
                    if (typeof (testtakesPlannedFiltermanager) === 'undefined') {
                        testtakesPlannedFiltermanager = new FilterManager({
                            filterFields: [
                                {field: 'periodId', label: '<?= __("Periode")?>', type: 'select'},
                                {field: 'retake', label: '<?= __("Type")?>', type: 'select'},
                                {field: 'timeStartFrom', label: '<?= __("Gepland van")?>', type: 'datePicker'},
                                {field: 'timeStartTo', label: '<?= __("Gepland tot")?>', type: 'datePicker'},
                            ],
                            eventScope: '#TestTakesPlannedTeacher',
                            formPrefix: '#TestTake',
                            table: '#testsTable',
                            tablefy: {
                                'source' : '/test_takes/load_planned_teacher',
                                'filters' : '#TestTakePlannedTeacherForm',
                                'container' : '#testsContainter'
                            },
                            filterKey: 'testtakes_planned',
                        });
                        testtakesPlannedFirstTimeRun = true;
                    }


                    


                testtakesPlannedFiltermanager.init(testtakesPlannedFirstTimeRun);


            });

        </script>
    </div>
    <div class="block-footer"></div>
</div>
