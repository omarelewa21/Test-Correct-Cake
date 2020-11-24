<div id="AnalysesStudentOverview">
    <div id="buttons">
        
        <div class='popup' id='popup_search' style="display:none">
                <div class="popup-head" id="modal-head">Zoeken</div>
                <div class="popup-content">
                    <div id="studentsOverviewFilter">
                        <?= $this->Form->create('User') ?>
                            <div class="row">
                                <div class="col-md-5">
                                    <label>Voornaam</label>
                                    <?=$this->Form->input('name_first', array('label' => false)) ?>
                                </div>
                                <div class="col-md-5">
                                    <label>Achternaam</label>
                                    <?=$this->Form->input('name', array('label' => false)) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <label>Studentnummer</label>
                                    <?=$this->Form->input('external_id', array('label' => false, 'type' => 'text')) ?>
                                </div>
                                <div class="col-md-5">
                                    <label>Locatie</label>
                                    <?=$this->Form->input('school_location_id', ['label' => false, 'options' => $school_location])?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <label>Klas</label>
                                    <?=$this->Form->input('school_class_id', ['label' => false, 'options' => $school_classes])?>
                                </div>
                            </div>
                        <?=$this->Form->end();?>
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


    <h1>Studenten analyse</h1>

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
    <div class="block-head">Studenten overzicht</div>
    <div class="block-content" id="studentsContainer">
        <table class="table" id="studentsTable" cellpadding="1">
            <thead>
                <th>Nummer</th>
                <th>Voornaam</th>
                <th>Achternaam</th>
                <th>Klas</th>
                <?
                foreach($subjects as $subject) {
                    echo '<th>' . $subject['abbreviation'] . '</th>';
                }
                ?>
                <th width="30"></th>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

<?
if ($is_temp_teacher) {
?>
    <script>Notify.notify("Je kunt nog geen analyses bekijken omdat je in een tijdelijke school zit. Zodra we je verplaatst hebben naar je school kun je analyses wel bekijken. We sturen je een bericht zodra we je gekoppeld hebben aan je school.", "info", 15000);</script>
<?
} else 
{
?>
<script type="text/javascript">
    $(document).ready(function () {
        let analysesStudentOverviewFirstTimeRun = false;
        if (typeof (analysesStudentOverviewFiltermanager) === 'undefined') {
            analysesStudentOverviewFirstTimeRun = true;
        }

        analysesStudentOverviewFiltermanager = new FilterManager({
            filterFields: [
                {field: 'nameFirst', label: 'Voornaam', type: 'text'},
                {field: 'name', label: 'Achternaam', type: 'text'},
                {field: 'externalId', label: 'Studentnummer', type: 'text'},
                {field: 'schoolLocationId', label: 'Locatie', type: 'select'},
                {field: 'schoolClassId', label: 'Klas', type: 'select'},
            ],
            eventScope: '#AnalysesStudentOverview',
            formPrefix: '#User',
            table: '#studentsTable',
            tablefy: {
                    'source' : '/analyses/load_students_overview',
                    'container' : $('#studentsContainer'),
                    'filters' : $('#UserStudentsOverviewForm'),
                    'hideEmpty' : true
            },
            filterKey: 'analyses_student_overview',
        });
        
        analysesStudentOverviewFiltermanager.init(analysesStudentOverviewFirstTimeRun);

    });

</script>
<? } ?>