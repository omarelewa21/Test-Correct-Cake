<?
$selectedInvigilator = [];

foreach($take['invigilator_users'] as $invigilator) {
    $selectedInvigilator[] = $invigilator['id'];
}

$practice = ($take['test']['test_kind_id'] == "1") ? true : false;

?>

<div class="popup-head">Geplande toets wijzigen</div>
<div class="popup-content">
    <?=$this->Form->create('TestTake') ?>
        <table class="table mb15">
            <tr>
                <th width="140">
                    Datum
                </th>
                <td>
                    <?=$this->Form->input('time_start', array('style' => 'width: 270px', 'label' => false, 'verify' => 'notempty', 'value' => date('d-m-Y', strtotime($take['time_start'])), 'onchange' => 'TestTake.updatePeriodOnDate(this, '.$take_id.')')) ?>
                </td>
            </tr>
            <? if($take['retake'] == 0) { ?>
                <tr>
                    <th width="140">
                        Periode
                    </th>
                    <td>
                        <?=$this->Form->input('period_id', array('style' => 'width: 280px', 'id' => 'TestTakePeriodId_'.$take_id, 'label' => false, 'verify' => 'notempty max-length-5', 'value' => $take['period_id'])) ?>
                    </td>
                </tr>
                <tr>
                    <th width="140">
                        Weging
                    </th>
                    <td>
                        <?=$this->Form->input('weight', array('style' => 'width: 50px', 'label' => false, 'value' => $take['weight'], "disabled" => $practice)) ?>
                    </td>
                </tr>
            <? } ?>
            <tr>
                <th width="140">
                    Surveillanten
                </th>
                <td>
                    <?= $this->Form->input('invigilators', array('style' => 'width:280px', 'label' => false, 'options' => $inviligators, 'multiple' => true, 'class' => 'takers_select', 'value' => $selectedInvigilator)) ?>
                </td>
            </tr>
            <tr>
                <th width="140">
                    Instructies
                </th>
                <td>
                    <?=$this->Form->input('invigilator_note', array('style' => 'width: 98%; height: 100px;', 'label' => false, 'value' => $take['invigilator_note'], 'type' => 'textarea')) ?>
                </td>
            </tr>

            <?php if($is_rtti_school_location == '1'): ?>
                <tr class="testTakeRow">
                    <th>Is RTTI</th>
                    <td>
                        <?=$this->Form->input('is_rtti_test_take', array('style' => 'width: 185px', 'label' => false, 'type' => 'checkbox', 'div' => false, 'style' => 'width:20px;', 'checked' => (bool) $take['is_rtti_test_take'])) ?>
                    </td>
                </tr>
            <?php endif; ?>
        </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnEditTestTake">
        Geplande toets wijzigen
    </a>
</div>

<script type="text/javascript">

    var confirmPopup = false;

    <?php if($is_rtti_school_location == '1'): ?>
        confirmPopup = true;
    <?php endif;?>

    $('#TestTakeEditForm').formify({
        confirm: $('#btnEditTestTake'),
        confirmPopup: confirmPopup,
        confirmMessage: 'Weet u zeker dat u deze toets niet wilt exporteren naar RTTI Online?',
        skipOnChecked: $("#TestTakeIsRttiTestTake"),
        onsuccess: function (result) {
            Notify.notify("Toetsen zijn ingepland", "info");
            Navigation.refresh();
            Popup.closeLast();
        },
        onfailure: function (result) {
            console.log(result);

            for (var i = result.length - 1; i >= 0; i--) {
                Notify.notify(result[i], "error");
            }

            // Notify.notify("Niet alle velden zijn correct ingevuld", "error");
        }
    });

    $(function () {
        $('.takers_select').select2();
        $('.dateField').datepicker({
            minDate: new Date(),
            dateFormat: 'dd-mm-yy'
        });
    });

    // $('#TestTakeEditForm').formify(
    //     {
    //         confirm : $('#btnEditTestTake'),
    //         onsuccess : function(result) {
    //             Popup.closeLast();
    //             Navigation.refresh();
    //             Notify.notify("Toets-afname gewijzigd", "info");
    //         },
    //         onfailure : function(result) {
    //             Notify.notify("Er ging iets mis", "error");
    //         }
    //     }
    // );

    // $('#TestTakeInvigilators').select2();
    // $('#TestTakeTimeStart').datepicker({
    //     dateFormat : 'dd-mm-yy'
    // });
</script>