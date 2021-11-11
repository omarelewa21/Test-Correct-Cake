<div class="popup-head"><?= __("Deployment")?></div>
<div class="popup-content">
    <div class="popup-content">
        <?=$this->Form->create('WhitelistIp') ?>
        <table class="table">
            <tr>
                <th width="130">
                <?= __("Ip")?>
                </th>
                <td>
                    <?=$this->Form->input('ip', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty','placeholder' => 'ip4 of ip6')) ?>
                </td>
            </tr>
            <tr>
                <th width="130">
                <?= __("Van")?>
                </th>
                <td>
                    <?=$this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty','placeholder' => '')) ?>
                </td>
            </tr>
        </table>
        <?=$this->Form->end();?>
    </div>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSave">
    <?= __("Wijzigen")?>
    </a>
</div>

<script type="text/javascript">
    $('#WhitelistIpEditForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify('<?= __("Whitelisted ip adres gewijzigd")?>', "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify(result.join('<br />'), 'error');
            }
        }
    );
</script>