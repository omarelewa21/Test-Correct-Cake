<a id="btnResetAnswerPreview" href="#" class="btn highlight" style="text-align: center; display: none; margin-bottom: 10px; width: 190px" onclick="TestTake.resetAnswerPreview('<?=$take['discussing_question_uuid']?>', '<?=getUUID($take, 'get')?>');">
    Reset antwoordmodel
</a>
<script type="text/javascript">
    TestTake.discussingAllDiscussed = true;
</script>
<table class="table table-condensed">
    <?

    $abnormalities = 0;
    $abnormalityCount = 0;
    $average = 0;

    foreach($take['test_participants'] as $participant) {
        if(isset($participant['abnormalities'])) {
            $abnormalities += $participant['abnormalities'];
            $abnormalityCount++;
        }
    }

    if($abnormalityCount > 0) {
        $average = $abnormalities / $abnormalityCount;
    }
    $discussingAllDiscussed = true;
    $participants = ['red' => [], 'orange' => [], 'green' => [], 'grey' => []];

    foreach($take['test_participants'] as $participant) {
        if(isset($participant['answer_rated'])) {
            if ($participant['answer_rated'] == 0) {
                $color = 'red';
                $discussingAllDiscussed = false;
            } elseif ($participant['answer_rated'] == $participant['answer_to_rate']) {
                    $color = 'green';
            } else {
                    $color = 'orange';
                    $discussingAllDiscussed = false;
            }
        }else{
            $color = 'grey';
        }
        $participants[$color][] = $participant;
    }

    foreach($participants as $color => $_participants) {
        foreach($_participants as $participant){
            if($participant['active']) {

                $percentage = 100;

                if($average != 0) {
                    $percentage = (100 / $average) * $participant['abnormalities'];
                }

                ?>
                <tr>
                    <td width="13">
                        <?php if ($color == 'grey') { ?>
                            <div title="Student heeft geen antwoorden om te beoordelen" style="width:13px; height:13px; border: 2px solid #808080; border-radius: 15px;">
                                <svg style="position:relative; top:-1px; left:0px;" width="13px" height="13px" viewBox="0 0 13 13" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <title>icons/diagonal-line</title>
                                    <g id="icons/diagonal-line" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round">
                                        <line x1="0" y1="0" x2="13" y2="9" id="Line" stroke="#808080" stroke-width="2"></line>
                                    </g>
                                </svg>
                            </div>
                        <?php } else { ?>
                            <div style="width:13px; height:13px; background: <?= $color ?>; border-radius: 15px;"></div>
                        <?php } ?>
                    </td>
                    <td>
                        <?= $participant['user']['name_first'] ?>
                        <?= $participant['user']['name_suffix'] ?>
                        <?= $participant['user']['name'] ?>
                        <?
                        if($percentage < 95) {
                            ?><span class="fa fa-smile-o" style="font-size:1.4rem"></span><?
                        }elseif($percentage < 115) {
                            ?><span class="fa fa-meh-o" style="font-size:1.4rem"></span><?
                        }else{
                            ?><span class="fa fa-frown-o" style="font-size:1.4rem"></span><?
                        }
                        ?>
                    </td>
                    <td width="20">
                        <? if (isset($participant['answer_rated']) && $participant['answer_rated'] != $participant['answer_to_rate']) { ?>
                            <span class="fa fa-eye" style="cursor: pointer;" onclick="TestTake.loadParticipantAnswerPreview('<?=getUUID($take, 'get')?>', '<?=getUUID($participant['user'], 'get')?>');"></span>
                        <? } ?>
                    </td>
                </tr>
            <?
            }
        }
    }
    ?>
    <? if($discussingAllDiscussed === false){ ?>
    <script>
        TestTake.discussingAllDiscussed = false;
    </script>
    <? } ?>
</table>
