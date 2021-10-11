<?
$selectedInvigilator = [];

foreach($take['invigilator_users'] as $invigilator) {
    $selectedInvigilator[] = $invigilator['id'];
}

$practice = ($take['test']['test_kind_id'] == "1") ? true : false;

?>

<div class="popup-head">Geplande toets wijzigen</div>
<div class="popup-content overflow-visible">
    <?=$this->Form->create('TestTake') ?>
        <table class="table mb15">
            <tr>
                <th width="140">
                    Datum
                </th>
                <td>
                    <?=$this->Form->input('time_start', array('style' => 'width: 270px', 'label' => false, 'verify' => 'notempty','class' => 'dateField', 'value' => date('d-m-Y', strtotime($take['time_start'])), 'onchange' => 'TestTake.updatePeriodOnDate(this, '.$take_id.')')) ?>
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
            <?php if($is_rtti_school_location == '1'): ?>
                <tr class="testTakeRow">
                    <td colspan="7">
                        <div style="display: flex;">
                            <div style="display:flex;align-items: center; color: var(--system-base); width: 100%;">
                                <span class="fa fa-upload"></span>
                                <span style="color: black; margin-left: 10px; margin-right: 10px;"><strong>Resultaten toetsafname exporteren naar RTTI Online</strong></span>
                                <div style="display: flex; align-items: center; margin-left: auto">
                                    <?php echo $this->element('questionmark_tooltip_rtti', array('id' => 1)) ?>
                                    <label class="switch">
                                        <?php echo $this->Form->checkbox('is_rtti_test_take', array('checked' => $take['is_rtti_test_take'], 'value' => 1, 'label' => false)); ?>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
            <?php if ($school_allows_inbrowser_testing) { ?>
            <tr style="<?= $i > 0 ? 'display: none;' : '' ?>">
                <td colspan="7">
                    <div style="display: flex;">
                        <div style="display:flex; align-items: center; color: var(--system-base); width: 100%;">
                            <span class="fa fa-chrome"></span>
                            <span style="color: black; margin-left: 10px; margin-right: 10px"><strong>Browsertoetsen voor iedereen toestaan</strong></span>
                            <div style="display: flex; align-items: center; margin-left: auto">
                                <?php echo $this->element('questionmark_tooltip', array('id' => 1)) ?>
                                <label class="switch">
                                    <?php echo $this->Form->checkbox('allow_inbrowser_testing', array('checked' => $take['allow_inbrowser_testing'],'value' => 1,'label' => false)); ?>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <?php } ?>
            <tr style="<?= $i > 0 ? 'display: none;' : '' ?>">
                <td colspan="7">
                    <div style="display: flex;">
                        <div style="display:flex;align-items: center; color: var(--system-base); width: 100%;">
                            <?= $this->element('profile') ?>
                            <span style="color: black; margin-left: 10px; margin-right: 10px"><strong>Gastprofielen van studenten toelaten in toets</strong></span>
                            <div style="display: flex; align-items: center; margin-left: auto">
                                <?php echo $this->element('questionmark_tooltip_guest_accounts', array('id' => 1)) ?>
                                <label class="switch">
                                    <?php echo $this->Form->checkbox('guest_accounts', array('checked' => $take['guest_accounts'], 'value' => 1, 'label' => false)); ?>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
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
            // console.log(result);

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
