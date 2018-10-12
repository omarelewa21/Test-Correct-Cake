<div class="popup-head">IP toevoegen</div>
<div class="popup-content">
    <?=$this->Form->create('Ip') ?>
    <table class="table">
        <tr>
            <th width="130">
                Ip
            </th>
            <td>
                <?=$this->Form->input('ip', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th>
                Netmask
            </th>
            <td>
                <?=$this->Form->input('netmask', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
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
        Toevoegen
    </a>
</div>

<script type="text/javascript">
    $('#IpAddIpForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("IP toegevoegd", "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify("IP kon niet worden aangemaakt", "error");
            }
        }
    );
</script>