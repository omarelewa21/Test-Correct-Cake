<div class="popup-head">Deployment</div>
<div class="popup-content">
    <div class="popup-content">
        <?=$this->Form->create('Deployment') ?>
        <table class="table">
            <tr>
                <th width="130">
                    Datum
                </th>
                <td>
                    <?=$this->Form->input('deployment_day', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty','placeholder' => 'Y-m-d')) ?>
                </td>
                <th width="130">
                    Status
                </th>
                <td>
                    <?=$this->Form->input('status', array('style' => 'width: 185px', 'label' => false, 'options' => $statuses, 'selected' => getUUID($deployment['status'], 'get'))) ?>
                </td>
            </tr>
            <tr>
                <th colspan="2">
                    Onderhouds bericht
                </th>
                <th colspan="2">
                    Vooraankondiging
                </th>
            </tr>
            <tr>
                <td colspan="2">
                    <?=$this->Form->input('content', array('style' => 'width:100%; height: 100px;', 'type' => 'textarea', 'div' => false, 'label' => false, 'autocorrect' => 'off', 'spellcheck' => 'false', 'autocomplete' => 'off')); ?>
                </td>
                <td colspan="2">
                    <?=$this->Form->input('notification', array('style' => 'width:100%; height: 100px;', 'type' => 'textarea', 'div' => false, 'label' => false, 'autocorrect' => 'off', 'spellcheck' => 'false', 'autocomplete' => 'off')); ?>
                </td>
            </tr>
        </table>
        <?=$this->Form->end();?>
    </div>
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
    $('#DeploymentContent').ckeditor({});
    $('#DeploymentNotification').ckeditor({});
    $('#DeploymentEditForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("Deployment gewijzigd", "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify(result.join('<br />'), 'error');
            }
        }
    );
</script>
