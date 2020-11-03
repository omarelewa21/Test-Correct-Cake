<div class="popup-head">Schoolbeheerder</div>
<div class="popup-content">
    <?=$this->Form->create('User') ?>
    <table class="table">
        <? if($parameter1 == 'school') { ?>
            <tr>
                <th>Scholengemeens.</th>
                <td>
                    <?=$this->Form->input('school_id', array('style' => 'width: 185px', 'value' => $parameter2, 'label' => false, 'options' => $schools)) ?>
                </td>
            </tr>
        <? } ?>
        <? if($parameter1 == 'location') { ?>
            <tr>
                <th>Schoollocatie</th>
                <td>
                    <?=$this->Form->input('school_location_id', array('style' => 'width: 185px', 'value' => $current_school, 'label' => false, 'options' => $school_locations)); ?>
                </td>
            </tr>
        <? } ?>
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
                E-mailadres
            </th>
            <td>
                <?=$this->Form->input('username', array('style' => 'width: 185px', 'label' => false, 'verify' => 'email')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Wachtwoord
            </th>
            <td>
                <?=$this->Form->input('password', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty', 'type' => 'text')) ?>
            </td>
        </tr>
        <tr>
            <th colspan="2">Notities</th>
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
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnAddUser">
        Aanmaken
    </a>
</div>

<script type="text/javascript">
    $('#UserAddForm').formify(
        {
            confirm : $('#btnAddUser'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("Gebruiker aangemaakt", "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify("Gebruiker kon niet worden aangemaakt", "error");
            }
        }
    );
</script>