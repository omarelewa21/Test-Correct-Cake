<div class="popup-head"><?= __("Toets plannen")?></div>
<div class="popup-content">
    <?= $this->Form->create('TestTake') ?>
    <table class="table mb15" id="tableTestTakes">
        <tr>
            <th width="70"><?= __("Datum")?></th>
            <th width="110"><?= __("Periode")?></th>
            <th colspan="2"><?= __("Surveillanten")?></th>
            <th width="110"><?= __("Klas")?></th>
            <th width="150"><?= __("Toets")?></th>
            <th width="50"><?= __("Weging")?></th>
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
                <?= $this->Form->hidden('visible', array('name' => 'data[TestTake][' . $i . '][test_kind]', 'id' => 'TestTakeTestKind_' . $i, 'class' => 'testIsVisible', 'label' => false,'value' => $test_kind_id )) ?>
            </td>

            <td>
                <?= $this->Form->input('period_id', array('name' => 'data[TestTake][' . $i . '][period_id]', 'id' => 'TestTakePeriodId_' . $i, 'style' => 'width:110px', 'label' => false, 'options' => $periods)) ?>
            </td>
            <td colspan="2">
                <?= $this->Form->input('invigilators', array('name' => 'data[TestTake][' . $i . '][invigilators]', 'style' => 'width:300px', 'label' => false, 'options' => $inviligators, 'value' => $selectedInviligator, 'multiple' => true, 'class' => 'takers_select')) ?>
            </td>
            <td>
                <?= $this->Form->input('class_id', array('name' => 'data[TestTake][' . $i . '][class_id]', 'style' => 'width:110px', 'label' => false, 'options' => $classes, 'empty' => true, 'verify' => 'notempty')) ?>
            </td>
            <td>
                <a href="#" class="btn highlight small btnSelectTest" style="text-align: center;" id="TestTakeSelect_<?= $i ?>" onclick="TestTake.selectTest(<?= $i ?>);"><?= $i == 0 ? $test_name : __("Selecteer") ?></a>
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
            <tr style="<?= $i > 0 ? 'display: none;' : '' ?>" id="periodRow_<?= $i ?>" class="testTakePeriod">
                <th>Datum van</th>
                <td>
                    <?= $this->Form->input('date', array('name' => 'data[TestTake][' . $i . '][date_from]', 'id' => 'TestTakeDateFrom' . $i, 'class' => 'dateField', 'style' => 'width:70px', 'label' => false, 'verify' => 'notempty', 'onchange' => 'TestTake.updatePeriodOnDate(this, ' . $i . ')')) ?>
                </td>
                <th>Datum tot

                </th>
                <td><?= $this->Form->input('date', array('name' => 'data[TestTake][' . $i . '][date_till]', 'id' => 'TestTakeDateTill' . $i, 'class' => 'dateField', 'style' => 'width:70px', 'label' => false, 'verify' => 'notempty', 'onchange' => 'TestTake.updatePeriodOnDate(this, ' . $i . ')')) ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        <?php if(count($locations) > $i && $locations[$i]['is_rtti_school_location'] == '1'): ?>
            <tr style="<?= $i > 0 ? 'display: none;' : '' ?>" id="<?= $i ?>" class="testTakeRttiRow">
                <td colspan="7">
                    <div style="display: flex;">
                        <div style="display:flex;width:60%;align-items: center; color: var(--system-base);">
                            <span class="fa fa-upload"></span>
                            <span style="color: black; margin-left: 10px; margin-right: 10px"><strong><?= __("Resultaten toetsafname exporteren naar RTTI Online")?></strong></span>
                            <div style="display: flex; align-items: center; margin-left: auto">
                                <?php echo $this->element('questionmark_tooltip_rtti', array('id' => $i)) ?>
                                <label class="switch">
                                    <?=$this->Form->checkbox('is_rtti_test_take', array('name' => 'data[TestTake][' . $i . '][is_rtti_test_take]', 'value' => 1, 'label' => false)) ?>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endif; ?>

        <tr style="<?= $i > 0 ? 'display: none;' : '' ?>" id="inbrowser_toggle_<?= $i ?>" class="testTakeRowInbrowserToggle">
            <td colspan="7">
                <div style="display: flex; width: 100%;justify-content:space-between">
                    <?php if ($locations[0]['allow_inbrowser_testing']) { ?>
                    <div style="display: flex; flex-grow:1">
                        <div style="display:flex; ; align-items: center; color: var(--system-base)">
                            <span class="fa fa-chrome"></span>
                            <span style="color: black; margin-left: 10px; margin-right: 10px"><strong><?= __("Browsertoetsen voor iedereen toestaan")?></strong></span>
                            <div style="display: flex; align-items: center; margin-left: auto">
                                <?php echo $this->element('questionmark_tooltip', array('id' => $i)) ?>
                                <label class="switch">
                                    <?php echo $this->Form->checkbox('allow_inbrowser_testing', array('name' => 'data[TestTake][' . $i . '][allow_inbrowser_testing]', 'value' => 1, 'label' => false)); ?>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if ($locations[0]['allow_guest_accounts']) { ?>
                    <div style="display: flex;flex-grow:1">
                        <div style="display:flex;align-items: center; color: var(--system-base);">
                            <?= $this->element('profile') ?>
                            <span style="color: black; margin-left: 10px; margin-right: 10px"><strong>Gastprofielen van studenten toelaten in toets</strong></span>
                            <div style="display: flex; align-items: center; margin-left: auto">
                                <?php echo $this->element('questionmark_tooltip_guest_accounts', array('id' => $i)) ?>
                                <label id="guest_toggle" class="switch">
                                    <?php echo $this->Form->checkbox('guest_accounts', array('name' => 'data[TestTake][' . $i . '][guest_accounts]', 'value' => 1, 'label' => false)); ?>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </td>
        </tr>
        <tr style="<?= $i > 0 ? 'display: none;' : '' ?>">
        </tr>
        <tr style="<?= $i > 0 ? 'display: none;' : '' ?>" id="notes_<?= $i ?>" class="testTakeRowNotes">
            <td colspan="7">
                <strong><?= __("Notities voor surveillant")?></strong><br />
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
            <?= __("Extra toets plannen")?>
        </a>
    </center>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnAddTestTakes">
    <?= __("Toetsen plannen")?>
    </a>
</div>

<script type="text/javascript">
    <? if($carouselGroupQuestionNotify){ ?>
        $(document).ready(function(){
            Notify.notify('<? echo($carouselGroupQuestionNotifyMsg) ?>', 'error');
            Navigation.refresh();
            Popup.closeLast();
        })

    <? } ?>


    var confirmPopup = false;

    <?php if($locations[0]['is_rtti_school_location'] == '1'): ?>
    confirmPopup = true;
    <?php endif;?>

    $('#TestTakeAddForm').formify({
        confirm: $('#btnAddTestTakes'),
        confirmPopup: confirmPopup,
        confirmMessage: '<?= __("Weet u zeker dat u deze toets niet wilt exporteren naar RTTI Online?")?>',
        skipOnChecked: $("#TestTakeIsRttiTestTake"),
        onsuccess: function (result) {
            Notify.notify('<?= __("Toetsen zijn ingepland")?>', "info");
            Navigation.load('/test_takes/planned_teacher');
            Menu.updateMenuFromRedirect('tests', 'tests_planned')
            Popup.closeLast();
        },
        onfailure: function (result) {

            // console.log(result["errors"]);

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
        TestTake.i = 0;
        TestTake.updatedTestKind();
    });

    $('#TestTakeGuestAccounts').on('change', (function () {
        if ($('#TestTakeGuestAccounts').prop('checked')) {
            $('#TestTakeClassId').attr('verify', '');
        } else {
            $('#TestTakeClassId').attr('verify', 'notempty');
        }
    }));

</script>
