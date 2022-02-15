<div class="popup-head"><?= __("Ouder")?></div>
<div class="popup-content">
    <?=$this->Form->create('User') ?>
    <table class="table">
        <tr>
            <th width="130">
            <?= __("Voornaam")?>
            </th>
            <td>
                <?=$this->Form->input('name_first', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Tussenvoegsel")?>
            </th>
            <td>
                <?=$this->Form->input('name_suffix', array('style' => 'width: 185px', 'label' => false)) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Achternaam")?>
            </th>
            <td>
                <?=$this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("E-mailadres")?>
            </th>
            <td>
                <?=$this->Form->input('username', array('style' => 'width: 185px', 'label' => false, 'verify' => 'email')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Wachtwoord")?>
            </th>
            <td>
                <?=$this->Form->input('password', array('style' => 'width: 185px', 'label' => false, 'type' => 'text', 'verify' => 'length-8')) ?>
            </td>
        </tr>
        <tr>
            <th colspan="2"><?= __("Notities")?></th>
        </tr>
        <tr>
            <td colspan="2">
                <?=$this->Form->input('note', [
                    'style' => 'width:330px; height:100px',
                    'type' => 'textarea',
                    'label' => false
                ])?>
            </td>
        </tr>
    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnAddUser">
    <?= __("Aanmaken")?>
    </a>
</div>

<script type="text/javascript">
    $('#UserEditForm').formify(
        {
            confirm : $('#btnAddUser'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify('<?= __("Ouder aangemaakt")?>', "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify('<?= __("Ouder kon niet worden aangemaakt")?>', "error");
            }
        }
    );
</script>