<?= $this->element('preview_attachments2019',['questions' => $questions, 'hideExtra' => $hideExtra]);?>
<?php
    $citoClass = '';
    if(AppHelper::isCitoQuestion($question)){
$citoClass = 'cito';
}
?>
<h1 class="question_type <?=$citoClass?>">Rangschikvraag<?=AppHelper::showExternalId($question);?></h1>

<div style="font-size: 20px;">
    <?
    if(isset($question['question_group']['text']) && !empty($question['question_group']['text'])) {
        echo '<p>'. $question['question_group']['text'].'</p>';
    }
    ?>
    <?=$question['question']?>
</div>

<?php

    echo sprintf('<div class="answer_container %s">',$citoClass);
?>

    <div id="answers" style="">
        <?
        $answers = $question['ranking_question_answers'];
        shuffle($answers);

        foreach($answers as $answer) {
            ?>
            <div style="padding:10px; margin-bottom: 2px; background: grey;" class="ranking_answer">
                <?=$answer['answer']?>
            </div>
            <?
        }
        ?>
    </div>
</div>
<script type="text/javascript">
    $('#answers').sortable();
</script>

<? if(isset($next_question)) { ?>
    <br />
    <center>
        <a href="#" class="btn highlight large" onclick="TestPreview.loadQuestionPreview('<?=$test_id?>', '<?=$next_question?>');">
            <span class="fa fa-check"></span>
            Volgende vraag
        </a>
    </center>
<? } ?>