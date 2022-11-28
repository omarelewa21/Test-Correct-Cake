<div class="popup-head"><?= __("School locatie")?></div>
<div class="popup-content">
    <?=$this->Form->create('SchoolLocation') ?>
    <table class="table">
        <tr>
            <th><?= __("Naam")?></th>
            <td>
                <?=$this->Form->input('school_location_id', array('style' => 'width: 185px', 'options' => $schoolLocationIds, 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();" selid="cancel-btn">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSave">
    <?= __("Koppelen")?>
    </a>
</div>

<script type="text/javascript">
    $('#SchoolLocationAddSchoolLocationForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify('<?= __("School locatie gekoppeld")?>', "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify('<?= __("Het koppelen is mislukt, probeer het nogmaals")?>', "error");
            }
        }
    );
</script>