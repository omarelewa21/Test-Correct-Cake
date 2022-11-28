<div class="popup-head"><?= __("IP toevoegen")?></div>
<div class="popup-content">
    <?=$this->Form->create('Ip') ?>
    <table class="table">
        <tr>
            <th width="130">
            <?= __("Ip")?>
            </th>
            <td>
                <?=$this->Form->input('ip', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th>
            <?= __("Netmask")?>
            </th>
            <td>
                <?=$this->Form->input('netmask', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();" selid="cancel-btn">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSave">
    <?= __("Toevoegen")?>
    </a>
</div>

<script type="text/javascript">
    $('#IpAddIpForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify('<?= __("IP toegevoegd")?>', "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify('<?= __("IP kon niet worden aangemaakt")?>', "error");
            }
        }
    );
</script>