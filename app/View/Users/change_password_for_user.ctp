<div class="popup-head"><?= $user['name_first']?> <?= $user['name_suffix']?> <?= $user['name']?></div>
<div class="popup-content">
    <?=$this->Form->create('User', ['']) ?>
    <table class="table">
        <tr>
            <td colspan="2"><?= __("Wijzig het wachtwoord van de gebruiker")?></td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Nieuw wachtwoord")?>
            </th>
            <td>
                <?=$this->Form->input('password', array('style' => 'width: 185px', 'label' => false, 'verify' => 'length-8')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Herhaal wachtwoord")?>
            </th>
            <td>
                <?=$this->Form->password('password_confirmation', array('style' => 'width: 185px', 'label' => false, 'verify' => 'length-8')) ?>
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
    <?= __("Wijzigen")?>
    </a>
</div>


<script type="text/javascript">
    $('#UserChangePasswordForUserForm').formify(
        {
            confirm : $('#btnAddUser'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify('<?= __("Gebruiker gewijzigd")?>', "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                if ('password' in result) {
                    Notify.notify(result.password, "error");
                }

                if(result.error != undefined) {
                    Notify.notify(result.error, "error");
                }
                else{
                    Notify.notify('<?= __("Er is iets niet goed gegaan bij het wijzigen van de gegevens")?>', "error");
                }
            }
        }
    );

</script>