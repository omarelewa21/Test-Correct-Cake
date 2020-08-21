<div class="popup-head">Licentiepakket</div>
<div class="popup-content">
    <?=$this->Form->create('Licence') ?>
    <table>
        <tr>
            <th width="130">
                Licenties
            </th>
            <td>
                <?=$this->Form->input('amount', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Startdatum
            </th>
            <td>
                <?=$this->Form->input('start', array('style' => 'width: 185px', 'label' => false, 'verify' => 'date')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Einddatum
            </th>
            <td>
                <?=$this->Form->input('end', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>

    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnAddLicence">
        Aanmaken
    </a>
</div>

<script type="text/javascript">


    $('#LicenceStart, #LicenceEnd').datepicker();

    $('#LicenceAddLicenceForm').formify(
        {
            confirm : $('#btnAddLicence'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("Licentiepakket aangemaakt", "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify("Licentiepakket kon niet worden aangemaakt. Controleer of u alle velden correct heeft ingevuld.", "error");
            }
        }
    );
</script>