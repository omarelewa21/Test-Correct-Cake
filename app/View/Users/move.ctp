<div class="popup-head"><?= __("Overplaatsen")?></div>
<div class="popup-content">
    <?=$this->Form->create('User') ?>
    <table class="table">
        <tr>
            <th width="130">
            <?= __("Schoollocatie")?>
            </th>
            <td>
                <?=$this->Form->input('school_location_id', array('style' => 'width: 185px', 'label' => false, 'options' => $school_locations)) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("E-mailadres")?>
            </th>
            <td>
                <?=$this->Form->input('email', array('style' => 'width: 185px', 'label' => false, 'verify' => 'email')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Studentnummer")?>
            </th>
            <td>
                <?=$this->Form->input('external_id', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty', 'type' => 'text')) ?>
            </td>
        </tr>
    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();" selid="cancel-btn">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnAddUser">
    <?= __("Overplaatsen")?>
    </a>
</div>

<script type="text/javascript">
    $('#UserMoveForm').formify(
        {
            confirm : $('#btnAddUser'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify('<?= __("Gebruiker overgeplaatst")?>', "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify('<?= __("Gebruiker kon niet worden aangemaakt")?>', "error");
            }
        }
    );
</script>