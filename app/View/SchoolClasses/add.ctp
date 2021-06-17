<?
$levels = [];
$levelyears = [];

foreach($education_levels as $education_level) {
    $levels[$education_level['uuid']] = $education_level['name'];
    $levelyears[$education_level['uuid']] = $education_level['max_years'];
}
?>
<div class="popup-head">Klas</div>
<div class="popup-content">
    <?
    if(empty($school_years)) {
        ?>
        <div class="alert alert-danger">
            U heeft nog geen schooljaren
        </div>
        <?
    }else {
        ?>
        <?= $this->Form->create('SchoolClass') ?>
        <table class="table">
            <tr>
                <th width="130">
                    Naam
                </th>
                <td>
                    <?= $this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
                </td>
            </tr>
            <tr>
                <th>Schoollocatie</th>
                <td>
                    <?= $this->Form->input('school_location_id', ['options' => $locations, 'style' => 'width:200px;', 'label' => false]) ?>
                </td>
            </tr>

            <tr>
                <th>Stamklas</th>
                <td>
                    <?= $this->Form->input('is_main_school_class', ['type' => 'checkbox', 'value' => 1, 'label' => false]) ?>
                </td>
            </tr>

            <tr>
                <th>Niveau</th>
                <td>
                    <?= $this->Form->input('education_level_id', ['options' => $levels, 'label' => false, 'onchange' => 'updateEducationYears();']) ?>
                </td>
            </tr>

            <tr>
                <th>Niveau-jaar</th>
                <td>
                    <?= $this->Form->input('education_level_year', ['options' => [], 'label' => false]) ?>
                </td>
            </tr>
            <tr>
                <th>Schooljaar</th>
                <td>
                    <?= $this->Form->input('school_year_id', ['options' => $school_years, 'label' => false, 'verify' => 'notempty']) ?>
                </td>
            </tr>

           <?php if($is_rtti == 1 || $lvs_type != null): ?>
            <tr>
                <th>LVS</th>
                <td>
                    <?=$this->Form->input('do_not_overwrite_from_interface', array('style' => 'width: 185px', 'label' => false, 'type' => 'checkbox', 'checked'=>1, 'div' => false, 'style' => 'width:20px;')) ?>
                    Klas niet overschrijven
                </td>
            </tr>
        <?php endif; ?>


        </table>
        <?= $this->Form->end(); ?>
        <?
    }
    ?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSave">
        Aanmaken
    </a>
</div>

<script type="text/javascript">

    $('#SchoolClassAddForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("Klas aangemaakt", "info");
                Navigation.load('/school_classes/view/' + result.uuid)
            },
            onfailure : function(result) {
                Notify.notify(result, "error");
            }
        }
    );

    $('#SchoolYearSchoolLocations').select2();

    function updateEducationYears() {
        var val = $('#SchoolClassEducationLevelId').val();
        var years = 0;

        <?php
        foreach($levelyears as $year => $years) {
            ?>
            if(val == '<?=$year?>') {
                years = '<?=$years?>';
            }
            <?php
        }
        ?>

        $("#SchoolClassEducationLevelYear option").remove();

        for(var i = 1; i <= years; i++) {
            $("#SchoolClassEducationLevelYear").append('<option value="' + i + '">' + i + '</option>');
        }
    }

    updateEducationYears();
</script>