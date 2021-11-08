<div class="popup-head"><?= __("Support")?></div>
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
                <?=$this->Form->input('password', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty', 'type' => 'text')) ?>
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
    $('#UserAddForm').formify(
        {
            confirm : $('#btnAddUser'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify('<?= __("Gebruiker aangemaakt")?>', "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                if (result.error == 'username') {
                    Notify.notify('<?= __("E-mailadres al in gebruik")?>', "error");
                }else if(result.error== 'dns'){
                    Notify.notify('<?= __("Het domein van het opgegeven e-mailadres is niet geconfigureerd voor e-mailadressen")?>', "error");
                }else if(result.error== 'external_code'){
                    Notify.notify('<?= __("Deze externe code is al in gebruik")?>', "error");
                }else if (result.error == 'user_roles'){
                    Notify.notify('<?= __("U kunt een docent pas aanmaken nadat u een actuele periode heeft aangemaakt. Dit doet u door als schoolbeheerder in het menu Database -> Schooljaren een schooljaar aan te maken met een periode die in de huidige periode valt.")?>','error')
                }
                else{
                    Notify.notify('<?= __("Gebruiker kon niet worden aangemaakt")?>', "error");
                }
            }
        }
    );
</script>