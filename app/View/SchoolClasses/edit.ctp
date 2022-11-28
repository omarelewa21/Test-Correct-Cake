<?
$levels = [];
$levelyears = [];

foreach($education_levels as $education_level) {
    $levels[$education_level['uuid']] = $education_level['name'];
    $levelyears[$education_level['uuid']] = $education_level['max_years'];
}
?>

<div class="popup-head"><?= __("Klas")?></div>
<div class="popup-content">
    <?=$this->Form->create('SchoolClass') ?>
    <table class="table">
        <tr>
            <th width="130">
            <?= __("Naam")?>
            </th>
            <td>
                <?=$this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th><?= __("Schoollocatie")?></th>
            <td>
                <?=$this->Form->input('school_location_id', ['options' => $locations, 'style' => 'width:200px;', 'label' => false]) ?>
            </td>
        </tr>

        <tr>
            <th><?= __("Stamklas")?></th>
            <td>
                <?=$this->Form->input('is_main_school_class', ['type' => 'checkbox', 'value' => 1, 'label' => false]) ?>
            </td>
        </tr>

        <tr>
            <th><?= __("Niveau")?></th>
            <td>
                <?=$this->Form->input('education_level_id', ['options' => $levels, 'selected' => $SchoolClassEducationLevelUuid, 'label' => false, 'onchange' => 'updateEducationYears();']) ?>
            </td>
        </tr>

        <tr>
            <th><?= __("Niveau-jaar")?></th>
            <td>
                <?=$this->Form->input('education_level_year', ['options' => $initEducationLevelYears,  'label' => false]) ?>
            </td>
        </tr>
        <tr>
            <th><?= __("Schooljaar")?></th>
            <td>
                <?=$this->Form->input('school_year_id', ['options' => $school_years, 'label' => false]) ?>
            </td>
        </tr>

        <?php if($is_rtti == 1 || $lvs_type != null): ?>
            <tr>
                <th><?= __("LVS")?></th>
                <td>
                    <?=$this->Form->input('do_not_overwrite_from_interface', array('style' => 'width: 185px', 'label' => false, 'type' => 'checkbox', 'value' => 1, 'div' => false, 'style' => 'width:20px;')) ?>
                    <?= __("Klas niet overschrijven")?>
                </td>
            </tr>
        <?php endif; ?>

    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();" selid="cancel-btn">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSave">
    <?= __("Wijzigen")?>
    </a>
</div>

<script type="text/javascript">
    $('#SchoolClassEditForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify('<?= __("Klas is gewijzigd")?>', "info");
                Navigation.refresh();
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

    function initEducationYears(){
        var val = '<? echo $SchoolClassEducationLevelYear ?>';
        $("#SchoolClassEducationLevelYear").val(val);
    }

    updateEducationYears();
    initEducationYears();
</script>