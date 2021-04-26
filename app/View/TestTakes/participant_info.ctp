<div class="popup-head">
    <?= $participant['user']['name_first'] ?>
    <?= $participant['user']['name_suffix'] ?>
    <?= $participant['user']['name'] ?>
</div>

<div class="popup-content" style="max-height:400px; overflow: auto;">
    <div style="width:450px; float:left">
        <center>
            <img src="/users/profile_picture/<?=getUUID($participant['user'], 'get');?>" width="100" height="100" style="border-radius: 100px; margin-bottom: 20px;" />
        </center>
        <table class="table table-striped">
            <tr>
                <th><?= __("Tijdsdispensatie")?></th>
                <td>
                    <?= ((bool) $participant['user']['time_dispensation'] === true) ? __("Ja") : __("Nee")?>
                </td>
            </tr>
            <tr>
                <th><?= __("Tekst-naar-spraak")?></th>
                <td>
                    <?= ((bool) $participant['user']['active_text2speech'] === true) ? __("Ja") : __("Nee")?>
                </td>
            </tr>
            <tr>
                <th width="170"><?= __("Vragen beantwoord")?></th>
                <td>
                    <?
                    $answerCount = 0;

                    foreach($participant['answers'] as $answer) {
                        if(!empty($answer['json'])) {
                            $answerCount ++;
                        }
                    }

                    echo $answerCount;
                    ?>
                    <?
                    if(count($participant['answers']) > 0) {
                        $percentage = 100 / count($participant['answers']);
                        $percentage = round($percentage * $answerCount);

                        echo '(' . $percentage . '%)';
                    }else{
                        echo '-';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th><?= __("Tijd per vraag")?></th>
                <td>
                    <?
                    $total = 0;
                    foreach($participant['answers'] as $answer) {
                        $total += $answer['time'];
                    }

                    if(count($participant['answers']) == 0) {
                        echo 'n.v.t.';
                    }else {
                        $time = round($total / count($participant['answers']));

                        echo $time . __(" seconden (gemiddeld)");
                    }
                    ?>
                </td>
            </tr>
            <Tr>
                <th><?= __("Huidige vraag")?></th>
                <td><?=$question_index?></td>
            </Tr>
            <tr>
                <th>
                <?= __("Notities")?>
                </th>
            </tr>
        </table>
        <?=$this->Form->create('Participant')?>
        <?=$this->Form->input('invigilator_note', [
            'value' => $participant['invigilator_note'],
            'type' => 'textarea',
            'style' => 'width:98%; height:100px;',
            'label' => false
        ]);?>
        <?=$this->Form->end() ?>
    </div>
    <div style="width:280px; float:right">
        <? foreach($events as $event) {
            ?>
            <div style="padding:8px 15px 6px 15px; background: #197cb4; margin-bottom: 2px; color: white;">
                <?=date('H:i', strtotime($event['created_at']))?> -

                <?
                $translations = [
                    'Start' => __("Gestart met toets"),
                    'Stop' => __("Gestopt met toets"),
                    'Lost focus' => __("App verlaten"),
                    'Screenshot' => __("Screenshot gemaakt"),
                    'Started late' => __("Laat gestart"),
                    'Application closed' => __("Opnieuw gestart met toets")
                ];

                echo isset($translations[$event['test_take_event_type']['name']]) ? $translations[$event['test_take_event_type']['name']] : $event['test_take_event_type']['name'];
                ?>

            </div>
            <?
        } ?>
    </div>
    <br clear="all" />
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSaveParticipantNotes">
    <?= __("Notities opslaan")?>
    </a>
</div>

<script type="text/javascript">

    $('#ParticipantParticipantInfoForm').formify(
        {
            action : '/test_takes/participant_info/<?=$test_id?>/<?=getUUID($participant, 'get');?>',
            confirm : $('#btnSaveParticipantNotes'),
            onsuccess : function(result) {
                Popup.closeLast();
            },
            onfailure : function(result) {

            }
        }
    );
</script>