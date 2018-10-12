
<a href="#" class="btn red" id="btnAttachmentFrame">
    <span class="fa fa-remove"></span>
</a>
<iframe id="attachmentFrame" frameborder="0"></iframe>
<div id="attachmentFade"></div>

<a href="#" class="btn highlight" id="btnHandIn" onclick="TestTake.handIn();">
    Inleveren
</a>
<div id="test_progress">
    <?
    $i = 0;
    foreach($questions as $index => $questions_item) {

        $i++;

        if($questions_item['done'] == 1) {
            $class = 'green';
        }else{
            $class = 'grey';
        }

        ?>
        <div class="question <?=$class?>" onclick="Answer.loadQuestion('/test_takes/take/<?=$take_id?>/<?=$index?>');"><?=$i?></div>
        <?
    }
    ?>
    <div class="question active" onclick="Answer.loadQuestion('/test_takes/take_answer_overview/<?=$take_id?>');">
        <span class="fa fa-list"></span>
    </div>
    <br clear="all" />
</div>

<br clear="all" />

<?
$i = 0;
foreach($questions as $question) {
    $i++;
    ?>
    <div class="block">
        <div class="block-head">Vraag #<?=$i?> voorbeeld</div>
        <div class="block-content" id="question_preview_<?=$question['id']?>">
            Laden..
        </div>
    </div>

    <div class="block" style="margin-bottom: 100px; border-left: 3px solid #689236">
        <div class="block-head">Vraag #<?=$i?> antwoord</div>
        <div class="block-content" id="question_answer_<?=$question['id']?>">
            Laden..
        </div>
    </div>

    <br clear="all" />

    <script type="text/javascript">
        $('#question_preview_<?=$question['id']?>').load('/questions/preview_single_load/<?=$question['question_id']?>/0/1');
        $('#question_answer_<?=$question['id']?>').load('/test_takes/rate_teacher_answer/<?=$participant_id?>/<?=$question['question_id']?>');
    </script>
<?
}
?>

<script>
    TestTake.startHeartBeat('active');
    Answer.answerChanged = false;
    Answer.questionSaved = true;
</script>

<style>
    .toggleOption {
        display: none;
    }
</style>

