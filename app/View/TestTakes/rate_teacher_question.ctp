<div>
<a href="#" class="btn highlight" id="btnHandIn" onclick="Navigation.load('/test_takes/view/<?=$take_id?>');">
<?= __("Terug")?>
</a>
<div id="test_progress">
    <?
    $i = 0;
    foreach($questions as $index => $questions_item) {

        $i++;

        if($index == $question_index) {
            $class = 'active';
        }else{
            $class = 'grey';
        }

        ?>
        <div class="question <?=$class?>" onclick="loadQuestion(<?=$index?>);"><?=$i?></div>
        <?
    }
    ?>
    <br clear="all" />
</div>


<?= $this->element("attachment_popup"); ?>

<br clear="all" />
<div class="block">
    <div class="block-head"><?= __("Vraag")?></div>
    <div class="block-content" id="question_load"></div>
</div>

<div id="answerModel" class="block" style="border-left: 3px solid var(--menu-blue);">
    <div class="block-head"><?= __("Antwoordmodel")?><button id="pinAnswerModel" class="fa fa-unlock pull-right" style="background-color:white; border:none;"></button></div>
    <div class="block-content" id="question_answer_load"></div>
</div>

<center>
    <a href="#" class="btn highlight inline-block mb15" style="display: none;" id="btnShowAll" onclick="$('.questionblock').slideDown();$(this).remove();"><?= __("Alle antwoorden weergeven")?></a>
</center>

<?

shuffle($participants);
foreach($participants as $participant) {
    if($participant['test_take_status_id'] != 1 && $participant['test_take_status_id'] != 2) {

        $name = $participant['user']['name_first'] . ' ' . $participant['user']['name_suffix'] . ' ' . $participant['user']['name'];
        $name = str_replace("'", "", $name);

        ?>
        <div id="questionblock_<?=getUUID($participant, 'get')?><?=$question_id?>" style="display: none;" class="questionblock">
            <div class="block" style="float:left; width:calc(100% - 250px); border-left: 3px solid #3D9D36">
                <div class="block-head">
                    <span id="name_student_<?=getUUID($participant, 'get')?>">
                    <?= __("Student antwoord")?>
                    </span>
                    <span class="fa fa-eye" onclick="$('#name_student_<?=getUUID($participant, 'get')?>').html('<?=$name?>'); $(this).hide();"></span>
                </div>
                <div id="participant_answer_<?=getUUID($participant, 'get')?>" class="block-content">
                <?= __("Laden..")?>
                </div>
            </div>

            <div style="float:right; width: 230px;">
                <div class="block score" style="width: 100%;">
                    <div class="block-head"><?= __("Score")?></div>
                    <div class="block-content" id="score_<?=getUUID($participant, 'get')?><?=$question_id?>">
                        --
                    </div>
                </div>

                <? if($allow_feedback){ ?>
                    <div style="width: 100%; text-align: center">
                        <a href="#" class="btn highlight mb15 feedback" style="border-radius: 10px;"
                            onclick="loadFeedback(this.parentElement, <?= $question_index ?>);"
                        >
                            <i class="fa fa-pencil-square-o" aria-hidden="true" style="margin-right:2%"></i>
                            <span style="position:relative; bottom:1px" id="feedback_<?=getUUID($participant, 'get')?><?=$question_id?>"><?= __('Geef feedback') ?></span>
                        </a>
                    </div>
                <? } ?>
            </div>
            
            <br clear="all" />

        </div>
        <script type="text/javascript">
            $('#participant_answer_<?=getUUID($participant, 'get')?>').load('/test_takes/rate_teacher_answer/<?=getUUID($participant, 'get');?>/<?=$question_id?>',
                function() {
                    $('#score_<?=getUUID($participant, 'get')?><?=$question_id?>').load('/test_takes/rate_teacher_score/<?=getUUID($participant, 'get');?>/<?=$question_id?>');
                }
            );
        </script>
        <?
    }
}
?>

<br />
<center>
    <? if($question_index < (count($questions) - 1)) {
        ?>
        <a href="#" class="btn highlight mb15" onclick="loadQuestion(<?=$question_index +1 ?>);"><?= __("Volgende vraag")?></a>
        <?
    }
    ?>
</center>
</div>

<script type="text/javascript">
    var sticky = <?= $sticky ?>;
    function loadQuestion(index){
        Navigation.load('/test_takes/rate_teacher_question/<?=$take_id?>/'+index+'/'+sticky);
    }

    function loadFeedback(elem, q_index){
        answer_id = $(elem).siblings('.score').find('table').attr("data-answer");
        Popup.load('/test_takes/getFeedback/write/'+ answer_id + '/' + q_index, 700);
    }

    function changeFeedbackButtonText(participant_id, question_id, reverse=false){
        if(<?= $allow_feedback ? 'true' : 'false'?>){
            let elem = $('#feedback_'+ participant_id+question_id);
            if(reverse){
                elem.text("<?=__('Geef feedback')?>");
            }else{
                elem.text("<?=__('Wijzig feedback')?>");
            }
        }
    }

    $('#question_load').load('/questions/preview_single_load/<?=$question_id?>/<?=isset($questions[$question_index]['group_id']) ? $questions[$question_index]['group_id'] : ''?>');
    $('#question_answer_load').load('/questions/preview_answer_load/<?=$question_id?>');
    $('#pinAnswerModel').click(function(){
        if ($(this).hasClass('fa-unlock')) {
            sticky = 1;
            $('#answerModel').css({'position':'sticky', 'top':'170px'})
            $(this).addClass('fa-lock').removeClass('fa-unlock');
        } else {
            sticky = 0;
            $('#answerModel').css({'position':'relative', 'top':'0'})
            $(this).addClass('fa-unlock').removeClass('fa-lock');
        }
    })<?php if($sticky){ echo ".trigger('click');";} ?>
</script>
