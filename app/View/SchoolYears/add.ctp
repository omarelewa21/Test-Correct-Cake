<div class="popup-head"><?= __("Schooljaar")?></div>
<div class="popup-content">
    <?=$this->Form->create('SchoolYear') ?>
    <table class="table">
        <tr>
            <th width="130">
            <?= __("Jaar")?>
            </th>
            <td>
                <?=$this->Form->input('year', array('style' => 'width: 185px', 'label' => false, 'verify' => 'length-4')) ?>
            </td>
        </tr>
        <tr>
            <th><?= __("Locaties")?></th>
            <td>
                <?=$this->Form->input('school_locations', ['options' => $locations, 'style' => 'width:200px;', 'multiple' => true, 'label' => false]) ?>
            </td>
        </tr>
    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSave">
    <?= __("Aanmaken")?>
    </a>
</div>

<script type="text/javascript">
    $('#SchoolYearAddForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                
                // console.log(result);

                setTimeout(function() {
                    Popup.load('/school_years/add_period/' + result.uuid, 400);
                }.bind(result), 1000);
                
                Popup.closeLast();
                Notify.notify('<?= __("Schooljaar aangemaakt")?>', "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify('<?= __("SchoolYear kon niet worden aangemaakt")?>', "error");
            }
        }
    );

    $('#SchoolYearSchoolLocations').select2();
</script>