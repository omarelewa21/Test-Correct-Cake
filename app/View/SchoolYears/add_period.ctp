<div class="popup-head"><?= __("Periode")?></div>
<div class="popup-content">
    <?=$this->Form->create('Period') ?>
    <table class="table">
        <tr>
            <th width="130">
            <?= __("Naam")?>
            </th>
            <td>
                <?=$this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Datum van")?>
            </th>
            <td>
                <?=$this->Form->input('start_date', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Datum tot")?>
            </th>
            <td>
                <?=$this->Form->input('end_date', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSave">
    <?= __("Aanmaken")?>
    </a>
</div>

<script type="text/javascript">
    $('#PeriodAddPeriodForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify('<?= __("Periode aangemaakt")?>', "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                if (result.errors && result.errors.end_date) {
                    Notify.notify('<?= __("\"Datum van\" moet een datum voor \"Datum tot\" zijn.")?>', 'error')
                } else {
                    Notify.notify('<?= __("Deze data overlapt met een andere periode.")?>', "error");
                }
            }
        }
    );

    $('#PeriodStartDate, #PeriodEndDate').datepicker({

    });
</script>
