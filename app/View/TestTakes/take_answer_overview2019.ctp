
<a href="#" class="btn red" id="btnAttachmentFrame">
    <span class="fa fa-remove"></span>
</a>
<iframe id="attachmentFrame" frameborder="0"></iframe>
<div id="attachmentFade"></div>

<a href="#" class="btn highlight" id="btnHandIn" onclick="TestTake.handIn();">
<?= __("Inleveren")?>
</a>
<div id="test_progress">
    <?
    $i = 0;
    foreach($answers as $index => $questions_item) {

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
foreach($questions as $questionAr) {

    $question = $questionAr['question'];
    $answer = $questionAr['answer'];

    $i++;
    ?>
    <div class="block">
        <div class="block-head"><?= __("Vraag")?> #<?=$i?></div>
        <div class="block-content" id="question_preview_<?=getUUID($question, 'get')?>">
            <?php
            if($answer['closed'] == 1 || $answer['closed_group'] == 1){
                echo "<div>__(\"Het is niet mogelijk om het antwoord in te zien omdat deze vraag is afgesloten\")</div>";
            } else {

             echo $this->element('take_overview_question2019',['question' => $question]);
             echo $this->element($questionAr['answerView'],['rating' => ['answer' => $answer],'question_id' => getUUID($question, 'get'),'question' => $question]);
            }
            ?>
        </div>
    </div>

    <br clear="all" />

<?
}
?>

<script>
    TestTake.startHeartBeat('active');
    Answer.answerChanged = false;
    Answer.questionSaved = true;
    Answer.partOfCloseableGroup = false;
    Answer.closeable = false;
</script>

<style>
    .toggleOption {
        display: none;
    }
</style>

