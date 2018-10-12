<div class="popup-head">Contactpersoon</div>
<div class="popup-content">
    <?=$this->Form->create('Contact') ?>
    <table class="table">
        <tr>
            <th width="130">
                Naam
            </th>
            <td>
                <?=$this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Telefoon nr
            </th>
            <td>
                <?=$this->Form->input('phone', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Mobiel nr
            </th>
            <td>
                <?=$this->Form->input('mobile', array('style' => 'width: 185px', 'label' => false)) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Adres
            </th>
            <td>
                <?=$this->Form->input('address', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Postcode
            </th>
            <td>
                <?=$this->Form->input('postal', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Stad
            </th>
            <td>
                <?=$this->Form->input('city', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Land
            </th>
            <td>
                <?=$this->Form->input('country', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th colspan="2">
                Notitie
            </th>
        </tr>
        <tr>
            <td colspan="2">
                <?=$this->Form->input('note', array('style' => 'width: 332px; height:130px', 'label' => false, 'type' => 'textarea')) ?>
            </td>
        </tr>
    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSave">
        Wijzigen
    </a>
</div>

<script type="text/javascript">
    $('#ContactEditForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("Contactpersoon gewijzigd", "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify("Contactpersoon kon niet worden gewijzigd", "error");
            }
        }
    );
</script>