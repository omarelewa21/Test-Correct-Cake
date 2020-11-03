<?= $this->element('preview_attachments2019',['questions' => $questions, 'hideExtra' => $hideExtra]);?>
<?php
    $citoClass = '';
    if(substr_count($question['metadata'],'cito') > 0){
$citoClass = 'cito';
}
?>
<h1 class="question_type <?=$citoClass?>">Teken-vraag<?=AppHelper::showExternalId($question);?></h1>

<div style="font-size: 20px;">
    <?
    if(isset($question['question_group']['text']) && !empty($question['question_group']['text'])) {
        echo '<p>'. $question['question_group']['text'].'</p>';
    }
    ?>
    <?=$question['question']?><br /><br />
    <center>
        <a href="#" class="btn highlight large inline-block" onclick="false">
            <span class="fa fa-edit"></span>
            Antwoord tekenen
        </a>
    </center>
</div>

<? if(isset($next_question)) { ?>
    <br />
    <center>
        <a href="#" class="btn highlight large" onclick="TestPreview.loadQuestionPreview('<?=$test_id?>', '<?=$next_question?>');">
            <span class="fa fa-check"></span>
            Volgende vraag
        </a>
    </center>
<? } ?>
<?=$this->element('question_styling',['question' => $question]);?>