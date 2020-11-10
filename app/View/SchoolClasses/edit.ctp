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
    <?=$this->Form->create('SchoolClass') ?>
    <table class="table">
        <tr>
            <th width="130">
                Naam
            </th>
            <td>
                <?=$this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th>Schoollocatie</th>
            <td>
                <?=$this->Form->input('school_location_id', ['options' => $locations, 'style' => 'width:200px;', 'label' => false]) ?>
            </td>
        </tr>

        <tr>
            <th>Stamklas</th>
            <td>
                <?=$this->Form->input('is_main_school_class', ['type' => 'checkbox', 'value' => 1, 'label' => false]) ?>
            </td>
        </tr>

        <tr>
            <th>Niveau</th>
            <td>
                <?=$this->Form->input('education_level_id', ['options' => $levels, 'selected' => $SchoolClassEducationLevelUuid, 'label' => false, 'onchange' => 'updateEducationYears();']) ?>
            </td>
        </tr>

        <tr>
            <th>Niveau-jaar</th>
            <td>
                <?=$this->Form->input('education_level_year', ['options' => $initEducationLevelYears, 'selected' => $SchoolClassEducationLevelYear, 'label' => false]) ?>
            </td>
        </tr>
        <tr>
            <th>Schooljaar</th>
            <td>
                <?=$this->Form->input('school_year_id', ['options' => $school_years, 'label' => false]) ?>
            </td>
        </tr>

        <?php if($is_rtti == 1): ?>
            <tr>
                <th>LVS</th>
                <td>
                    <?=$this->Form->input('do_not_overwrite_from_interface', array('style' => 'width: 185px', 'label' => false, 'type' => 'checkbox', 'value' => 1, 'div' => false, 'style' => 'width:20px;')) ?>
                    Klas niet overschrijven
                </td>
            </tr>
        <?php endif; ?>

    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSave">
        Wijzigen
    </a>
</div>

<script type="text/javascript">
    $('#SchoolClassEditForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("Klas is gewijzigd", "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify("SchoolYear kon niet worden aangemaakt", "error");
            }
        }
    );

    $('#SchoolYearSchoolLocations').select2();

    function updateEducationYears() {
        console.log('hier');
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