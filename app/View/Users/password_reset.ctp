<div class="popup-head"><?= __("Wachtwoord wijzigen")?></div>
<div class="popup-content">
    <?=$this->Form->create('User') ?>
        <table class="table">
            <tr>
                <th width="130">
                <?= __("Huidig wachtwoord")?>
                </th>
                <td>
                    <?=$this->Form->input('password_old', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty', 'type' => 'password')) ?>
                </td>
            </tr>
            <tr>
                <th width="130">
                <?= __("Nieuw wachtwoord")?>
                </th>
                <td>
                    <?=$this->Form->input('password', array('style' => 'width: 185px', 'label' => false, 'verify' => 'length-8', 'type' => 'password')) ?>
                </td>
            </tr>
            <tr>
                <th width="130">
                <?= __("Herhaal nieuw wachtwoord")?>
                </th>
                <td>
                    <?=$this->Form->input('password_new', array('style' => 'width: 185px', 'label' => false, 'verify' => 'length-8', 'type' => 'password')) ?>
                </td>
            </tr>
        </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSavePassword">
    <?= __("Aanmaken")?>
    </a>
</div>

<script type="text/javascript">
    $('#UserPasswordResetForm').formify(
        {
            confirm : $('#btnSavePassword'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify('<?= __("Wachtwoord gewijzigd")?>', "info");
            },
            onfailure : function(result) {
                for (var [key, message] of Object.entries(result.errors)) {
                    Notify.notify(message, "error");
                }
            }
        }
    );
</script>