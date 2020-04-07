<div class="popup-head">Toets plannen</div>
<div class="popup-content">
    <?= $this->Form->create('TestTake') ?>
    <table class="table mb15" id="tableTestTakes">
        <tr>
            <th width="70">Datum</th>
            <th width="110">Periode</th>
            <th>Surveillanten</th>
            <th width="110">Klas</th>
            <th width="150">Toets</th>
            <th width="50">Weging</th>
            <th width="30"></th>
        </tr>

        <?
        if(array_key_exists(AuthComponent::user()['id'], $inviligators)) {
            $selectedInviligator = AuthComponent::user()['id'];
        }else{
            $selectedInviligator = null;
        }
        ?>
        <? for($i = 0; $i < 10; $i++) {

        ?>
        <tr style="<?= $i > 0 ? 'display: none;' : '' ?>" id="<?= $i ?>" class="testTakeRow">
            <td>
                <?= $this->Form->hidden('visible', array('name' => 'data[TestTake][' . $i . '][visible]', 'id' => 'TestTakeVisible' . $i, 'class' => 'testIsVisible', 'label' => false,'value' => $i == 0 ? 1 : '' )) ?>
                <?= $this->Form->input('date', array('name' => 'data[TestTake][' . $i . '][date]', 'id' => 'TestTakeDate' . $i, 'class' => 'dateField', 'style' => 'width:70px', 'label' => false, 'verify' => 'notempty', 'onchange' => 'TestTake.updatePeriodOnDate(this, ' . $i . ')')) ?>
            </td>
            <td>
                <?= $this->Form->input('period_id', array('name' => 'data[TestTake][' . $i . '][period_id]', 'id' => 'TestTakePeriodId_' . $i, 'style' => 'width:110px', 'label' => false, 'options' => $periods)) ?>
            </td>
            <td>
                <?= $this->Form->input('invigilators', array('name' => 'data[TestTake][' . $i . '][invigilators]', 'style' => 'width:300px', 'label' => false, 'options' => $inviligators, 'value' => $selectedInviligator, 'multiple' => true, 'class' => 'takers_select')) ?>
            </td>
            <td>
                <?= $this->Form->input('class_id', array('name' => 'data[TestTake][' . $i . '][class_id]', 'style' => 'width:110px', 'label' => false, 'options' => $classes)) ?>
            </td>
            <td>
                <a href="#" class="btn highlight small btnSelectTest" style="text-align: center;" id="TestTakeSelect_<?= $i ?>" onclick="TestTake.selectTest(<?= $i ?>);"><?= $i == 0 ? $test_name : 'Selecteer' ?></a>
                <?= $this->Form->input('test_id', array('type' => 'hidden', 'name' => 'data[TestTake][' . $i . '][test_id]', 'id' => 'TestTakeTestId_' . $i, 'style' => 'width:150px', 'label' => false, 'value' => $i == 0 ? $test_id : '')) ?>
            </td>
            <td>
                <?
                    if(isset($test)){
                        if($test['test_kind_id'] == 1 || $test['test_kind_id'] == 2) {
                            $value = 0;
                        } else {
                            $value = 5;
                        }
                    }
                ?>
                <?= 
                    $this->Form->input('weight',
                array(
                'name' => 'data[TestTake][' . $i . '][weight]',
                'id' => 'TestTakeWeight_' . $i, 'style' => 'width:50px',
                'label' => false,
                'disabled' => isset($test) && $test['test_kind_id'] == 1 ? true : false,
                // 'value' => isset($test) && $test['test_kind_id'] == 1 ? 0 : $test['test_kind_id'] == 2 ? 0 : 5,
                'value' => $value,
                'verify' => 'notempty'
                )
                )
                ?>
            </td>
            <td>
                <a href="#" class="btn red small" onclick="TestTake.removeTestRow(this, <?= $i ?>);">
                    <span class="fa fa-remove"></span>
                </a>
            </td>
        </tr>

        <?php if(count($locations) > $i && $locations[$i]['is_rtti_school_location'] == '1'): ?>
        <tr style="<?= $i > 0 ? 'display: none;' : '' ?>" id="<?= $i ?>" class="testTakeRttiRow">
            <th>Is RTTI</th>
            <td>
                <?=$this->Form->input('is_rtti_test_take', array('style' => 'width: 185px', 'label' => false, 'type' => 'checkbox', 'div' => false, 'style' => 'width:20px;', 'name' => 'data[TestTake][' . $i . '][is_rtti_test_take]', 'value' => '1', 'checked' => true)) ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr style="<?= $i > 0 ? 'display: none;' : '' ?>" id="notes_<?= $i ?>" class="testTakeRowNotes">
            <td colspan="7">
                <strong>Notities voor surveillant</strong><br />
                <?= $this->Form->input('invigilator_note', array('name' => 'data[TestTake][' . $i . '][invigilator_note]', 'style' => 'width:98%; height:100px;', 'label' => false, 'type' => 'textarea')) ?>
            </td>
        </tr>
        <?
        }
        ?>
    </table>
    <?= $this->Form->end(); ?>

    <center>
        <a href="#" class="btn highlight small inline-block" onclick="TestTake.addTestRow();">
            <span class="fa fa-plus"></span>
            Extra toets plannen
        </a>
    </center>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnAddTestTakes">
        Toetsen plannen
    </a>
</div>

<script type="text/javascript">

    var confirmPopup = false;

    <?php if($locations[0]['is_rtti_school_location'] == '1'): ?>
    confirmPopup = true;
    <?php endif;?>

    $('#TestTakeAddForm').formify({
        confirm: $('#btnAddTestTakes'),
        confirmPopup: confirmPopup,
        confirmMessage: 'Weet u zeker dat u deze toets niet wilt exporteren naar RTTI Online?',
        skipOnChecked: $("#TestTakeIsRttiTestTake"),
        onsuccess: function (result) {
            Notify.notify("Toetsen zijn ingepland", "info");
            Navigation.refresh();
            Popup.closeLast();
        },
        onfailure: function (result) {

            console.log(result["errors"]);

            for (var i = 0; i < result["errors"].length; i++) {
                Notify.notify(result["errors"][i], "error");
            }
        }
    });

    $(function () {
        $('.takers_select').select2();
        $('.dateField').datepicker({
            minDate: new Date(),
            dateFormat: 'dd-mm-yy'
        });
    });

</script>