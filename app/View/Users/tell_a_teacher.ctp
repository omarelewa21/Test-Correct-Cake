<div class="popup-head">Collega uitnodigen</div>
<div class="popup-content">
    <?=$this->Form->create('User') ?>
    <table class="table">
        <tr>
            <th width="130">
                Voornaam
            </th>
            <td>
                <?=$this->Form->input('name_first', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Tussenvoegsel
            </th>
            <td>
                <?=$this->Form->input('name_suffix', array('style' => 'width: 185px', 'label' => false)) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Achternaam
            </th>
            <td>
                <?=$this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Afkorting
            </th>
            <td>
                <?=$this->Form->input('abbreviation', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                E-mailadres
            </th>
            <td>
                <?=$this->Form->input('username', array('style' => 'width: 185px', 'label' => false, 'verify' => 'email')) ?>
            </td>
        </tr>

    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnAddUser">
        Uitnodigen
    </a>
</div>

<script type="text/javascript">
    $('#UserTellATeacherForm').formify(
        {
            confirm : $('#btnAddUser'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("Super bedankt!<br />We hebben "+$('#UserTellATeacherForm #UserNameFirst').val()+" uitgenodigd voor Test-Correct", "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                if(result.error == 'username') {
                    Notify.notify("Er is al een collega met dit e-mailadres bij ons bekend", "error");
                } else if (result.error == 'user_roles'){
                    Notify.notify('U kunt een collega pas uitnodigen nadat er een actuele periode is aangemaakt.','error')
                }
                else{
                    Notify.notify("Collega kon niet worden uitgenodigd", "error");
                }
            }
        }
    );
</script>