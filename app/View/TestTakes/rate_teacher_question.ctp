<a href="#" class="btn highlight" id="btnHandIn" onclick="Navigation.load('/test_takes/view/<?=$take_id?>');">
    Terug
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
        <div class="question <?=$class?>" onclick="Navigation.load('/test_takes/rate_teacher_question/<?=$take_id?>/<?=$index?>');"><?=$i?></div>
        <?
    }
    ?>
    <br clear="all" />
</div>

<?= $this->element("pdf_popup"); ?>

<br clear="all" />
<div class="block">
    <div class="block-head">Vraag</div>
    <div class="block-content" id="question_load"></div>
</div>

<div class="block" style="border-left: 3px solid #197cb4;">
    <div class="block-head">Antwoordmodel</div>
    <div class="block-content" id="question_answer_load"></div>
</div>

<center>
    <a href="#" class="btn highlight inline-block mb15" style="display: none;" id="btnShowAll" onclick="$('.questionblock').slideDown();$(this).remove();">Alle antwoorden weergeven</a>
</center>

<?

shuffle($participants);
foreach($participants as $participant) {
    if($participant['test_take_status_id'] != 1 && $participant['test_take_status_id'] != 2) {

        $name = $participant['user']['name_first'] . ' ' . $participant['user']['name_suffix'] . ' ' . $participant['user']['name'];
        $name = str_replace("'", "", $name);

        ?>
        <div id="questionblock_<?=$participant['id']?><?=$question_id?>" style="display: none;" class="questionblock">
            <div class="block" style="float:left; width:calc(100% - 250px); border-left: 3px solid #3D9D36">
                <div class="block-head">
                    <span id="name_student_<?=$participant['id']?>">
                        Student antwoord
                    </span>
                    <span class="fa fa-eye" onclick="$('#name_student_<?=$participant['id']?>').html('<?=$name?>'); $(this).hide();"></span>
                </div>
                <div id="participant_answer_<?=$participant['id']?>" class="block-content">
                    Laden..
                </div>
            </div>

            <div class="block" style="float:right; width: 230px;">
                <div class="block-head">Score</div>
                <div class="block-content" id="score_<?=$participant['id']?><?=$question_id?>">
                    --
                </div>
            </div>

            <br clear="all" />

        </div>
        <script type="text/javascript">
            $('#participant_answer_<?=$participant['id']?>').load('/test_takes/rate_teacher_answer/<?=$participant['id']?>/<?=$question_id?>',
                function() {
                    $('#score_<?=$participant['id']?><?=$question_id?>').load('/test_takes/rate_teacher_score/<?=$participant['id']?>/<?=$question_id?>');
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
        <a href="#" class="btn highlight mb15" onclick="Navigation.load('/test_takes/rate_teacher_question/<?=$take_id?>/<?=$question_index +1 ?>');">Volgende vraag</a>
        <?
    }
    ?>
</center>

<script type="text/javascript">
    $('#question_load').load('/questions/preview_single_load/<?=$questions[$question_index]['question_id']?>/<?=isset($questions[$question_index]['group_id']) ? $questions[$question_index]['group_id'] : ''?>');
    $('#question_answer_load').load('/questions/preview_answer_load/<?=$questions[$question_index]['question_id']?>');
</script>