<div class="popup-head">Deployment</div>
<div class="popup-content">
    <?=$this->Form->create('WhitelistIp') ?>
    <table class="table">
        <tr>
            <th width="130">
                Ip
            </th>
            <td>
                <?=$this->Form->input('ip', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty','placeholder' => 'ip4 of ip6')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Van
            </th>
            <td>
                <?=$this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty','placeholder' => '')) ?>
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
        Aanmaken
    </a>
</div>

<script type="text/javascript">

    $('#WhitelistIpAddForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("Whitelisted ip adres aangemaakt", "info");
                Navigation.refresh()
            },
            onfailure : function(result) {
                Notify.notify("Ip adres kon niet worden toegevoegd", "error");
            }
        }
    );
</script>