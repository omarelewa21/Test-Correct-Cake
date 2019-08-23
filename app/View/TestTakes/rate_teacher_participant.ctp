
<a href="#" class="btn red" id="btnAttachmentFrame">
    <span class="fa fa-remove"></span>
</a>
<iframe id="attachmentFrame" frameborder="0"></iframe>
<div id="attachmentFade"></div>

<div style="float:left; width: 300px;">
    <div class="block">
        <div class="block-head">Studenten</div>
        <div class="block-content">
                <?
                $i = -1;

                $active_id  = null;
                $next_index = null;

                foreach($participants as $participant) {
                    $i++;

                    if($participant['id'] == $participant_id && empty($active_id)) {
                        $active_id = $participant_id;
                    }elseif(empty($next_index) && !empty($active_id)) {
                        $next_index = $i;
                    }

                    ?>
                    <a href="#" class="btn <?=$participant['id'] == $participant_id ? 'highlight' : 'grey'?> mb1"  onclick="Navigation.load('/test_takes/rate_teacher_participant/<?=$take_id?>/<?=$i?>');">
                        <?=$participant['user']['name_first']?>
                        <?=$participant['user']['name_suffix']?>
                        <?=$participant['user']['name']?>
                    </a>
                    <?
                }
                ?>
        </div>
    </div>

    <a href="#" onclick="Navigation.load('/test_takes/view/<?=$take_id?>');"  class="btn highlight" style="text-align: center">
        Terug
    </a>
</div>

<div style="float:right; width:calc(100% - 320px);">

    <center>
        <a href="#" class="btn highlight inline-block mb15" style="display: none;" id="btnShowAll" onclick="$('.questionblock').slideDown();$(this).remove();">Alle antwoorden weergeven</a>
    </center>
    <?
        $i = 0;
        foreach($questions as $question) {
            $i++;
            ?>
            <div id="questionblock_<?=$participant_id?><?=$question['question']['id']?>" class="questionblock" style="display: none;;">
                <div class="block">
                    <div class="block-head">Vraag #<?=$i?> voorbeeld</div>
                    <div class="block-content" id="question_preview_<?=$question['question']['id']?>">
                        Laden..
                    </div>
                </div>

                <div class="block" style="border-left: 3px solid #197cb4;">
                    <div class="block-head">Vraag #<?=$i?> antwoordmodel</div>
                    <div class="block-content" id="question_answer_preview_<?=$question['question']['id']?>">
                        Laden..
                    </div>
                </div>

                <div class="block" style="width:280px; float:right;">
                    <div class="block-head">
                        Score
                    </div>
                    <div class="block-content" id="score_<?=$participant_id?><?=$question['question']['id']?>">
                        Laden..
                    </div>
                </div>

                <div class="block" style="width:calc(100% - 300px); margin-bottom: 100px; border-left: 3px solid #3D9D36">
                    <div class="block-head">Vraag #<?=$i?> antwoord</div>
                    <div class="block-content" id="question_answer_<?=$question['question']['id']?>">
                        Laden..
                    </div>
                </div>

                <br clear="all" />

                <script type="text/javascript">

                    Core.surpressLoading = true;
                    Core.cacheLoad('/questions/preview_single_load/<?=$question['question']['id']?>/<?=isset($question['group_id']) ? $question['group_id'] : ''?>', '#question_preview_<?=$question['question']['id']?>');
                    Core.cacheLoad('/questions/preview_answer_load/<?=$question['question']['id']?>', '#question_answer_preview_<?=$question['question']['id']?>');

                    $('#question_answer_<?=$question['question']['id']?>').load('/test_takes/rate_teacher_answer/<?=$participant_id?>/<?=$question['question']['id']?>',
                        function() {
                            $('#score_<?=$participant_id?><?=$question['question']['id']?>').load('/test_takes/rate_teacher_score/<?=$participant_id?>/<?=$question['question']['id']?>');
                        }
                    );

                    setTimeout(function() {
                        Core.surpressLoading = false;
                    }, 30000);
                </script>
            </div>
            <?
        }
    ?>

    <br /><br />
    <center>
        <? if(!empty($next_index)) { ?>
            <a href="#" class="btn highlight" onclick="Navigation.load('/test_takes/rate_teacher_participant/<?=$take_id?>/<?=$next_index?>');">
                Volgende
            </a>
        <? } ?>
    </center>
</div>

<br clear="all" />

