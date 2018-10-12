<div class="popup-head">Periode</div>
<div class="popup-content">
    <?=$this->Form->create('Period') ?>
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
                Datum van
            </th>
            <td>
                <?=$this->Form->input('start_date', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Datum tot
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
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSave">
        Aanmaken
    </a>
</div>

<script type="text/javascript">
    $('#PeriodAddPeriodForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("Periode aangemaakt", "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify("Deze data overlapt met andere periode's", "error");
            }
        }
    );

    $('#PeriodStartDate, #PeriodEndDate').datepicker({

    });
</script>