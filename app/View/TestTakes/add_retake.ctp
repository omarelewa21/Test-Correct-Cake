<?
$selectedInvigilator = [];

foreach($test_take['invigilator_users'] as $invigilator) {
    $selectedInvigilator[] = $invigilator['id'];
}

?>

<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        Terug
    </a>
    <a href="#" class="btn highlight mr2" id="btnAddTestRetake">
        Plannen
    </a>
</div>

<h1>Inhaaltoets plannen</h1>

    <?=$this->Form->create('TestTake') ?>

    <div class="block">
        <div class="block-head">Inhaal-toets maken</div>
        <div class="block-content">
            <table class="table mb15">
                <tr>
                    <th width="140">
                        Datum
                    </th>
                    <td>
                        <?=$this->Form->input('time_start', array('style' => 'width: 270px', 'label' => false, 'verify' => 'notempty', 'onchange' => 'TestTake.updatePeriodOnDate(this, 0)')) ?>
                    </td>
                    <th width="140">
                        Surveillanten
                    </th>
                    <td>
                        <?= $this->Form->input('invigilators', array('style' => 'width:280px', 'label' => false, 'options' => $inviligators, 'value' => $defaultInviligator, 'multiple' => true, 'class' => 'takers_select', 'verify' => 'notempty')) ?>
                    </td>
                </tr>
                <tr>
                    <th width="140">
                        Inhaaltoets van
                    </th>
                    <td>
                        <a href="#" class="btn highlight small btnSelectTestTake" style="text-align: center; width:270px;" id="TestTakeSelect" onclick="TestTake.selectTestTake();"><?=isset($test_take['id']) ? $test_take['test']['name'] . ' op ' .$test_take['time_start'] : 'Selecteer toets'?></a>
                        <?= $this->Form->input('retake_test_take_id', array('type' => 'hidden', 'style' => 'width:150px', 'label' => false, 'value' => isset($test_take['id']) ? getUUID($test_take, 'get') : '')) ?>
                    </td>
                    <th width="140">
                        Inhaal toets
                    </th>
                    <td>
                        <a href="#" class="btn highlight small btnSelectTest" style="text-align: center; width:270px;" id="TestTakeSelect_0" onclick="TestTake.selectTest(0);"><?=isset($test_take['test']['id']) ? $test_take['test']['name'] : 'Selecteer toets'?></a>
                        <?= $this->Form->input('test_id', array('type' => 'hidden', 'name' => 'data[TestTake][test_id]', 'id' => 'TestTakeTestId_0', 'style' => 'width:150px', 'label' => false, 'value' => isset($test_take['test']['id']) ? getUUID($test_take['test'], 'get') : '')) ?>
                    </td>
                </tr>
                <tr>
                    <th colspan="4">Surveillant notities</th>
                </tr>
                <tr>
                    <td colspan="4">
                        <?=$this->Form->input('invigilator_note', [
                            'style' => 'width:98%; height:100px',
                            'type' => 'textarea',
                            'label' => false
                        ])?>
                    </td>
                </tr>
            </table>
            <?=$this->Form->input('period_id', array('type' => 'hidden', 'value' => $test_take['period_id'])) ?>
            <?=$this->Form->input('weight', array('type' => 'hidden', 'style' => 'width: 50px', 'label' => false, 'value' => isset($test_take['weight']) ? $test_take['weight'] : 5)) ?>

        </div>
    </div>

    <div class="block">
        <div class="block-head">Studenten</div>
        <div class="block-content">
            <table class="table table-striped">
                <tr>
                    <th width="12"></th>
                    <th width="200">Student</th>
                    <th width="75">Gemaakt</th>
                    <th>Cijfer</th>
                </tr>
                <?
                foreach($participants as $participant) {
                    ?>
                    <tr>
                        <td><?=$this->Form->input('User.' . getUUID($participant, 'get'), array('type' => 'checkbox', 'value' => getUUID($participant, 'get'), 'label' => false, 'checked' => $participant['test_take_status_id'] <= 3)) ?></td>
                        <td>
                            <?=$participant['user']['name_first']?>
                            <?=$participant['user']['name_suffix']?>
                            <?=$participant['user']['name']?>
                        </td>
                        <td>
                            <?=$participant['test_take_status_id'] > 2 ? 'Ja' : 'Nee' ?>
                        </td>
                        <td>
                            <?=empty($participant['rating']) ? '-' : $participant['rating']?>
                        </td>
                    </tr>
                    <?
                }
                ?>
            </table>
        </div>
    </div>

<?=$this->Form->end();?>

<script type="text/javascript">
    $('#TestTakeAddRetakeForm').formify(
        {
            confirm : $('#btnAddTestRetake'),
            onsuccess : function(result) {
                Navigation.back()
                Notify.notify("Inhaaltoets gepland", "info");
            },
            onfailure : function(result) {
                if($('#TestTakeInvigilators').val() == null) {
                    Notify.notify("Selecteer minimaal 1 surveillant", "error");
                }else{
                    Notify.notify("Er ging iets mis", "error");
                }
            }
        }
    );

    $('#TestTakeInvigilators').select2();
    $('#TestTakeTimeStart').datepicker({
        minDate: new Date(),
        dateFormat: 'dd-mm-yy'
    });
</script>