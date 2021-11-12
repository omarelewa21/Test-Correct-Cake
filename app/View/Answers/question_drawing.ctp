<?=$this->element('take_attachments', ['question' => $question]);?>
<?php
    $citoClass = '';
    if(substr_count($question['metadata'],'cito') > 0){
$citoClass = 'cito';
}
?>
<?=$this->Form->create('Answer')?>
<h1 class="question_type <?=$citoClass?>"><?= __("Tekenvraag")?> [<?=$question['score']?>pt]<?=AppHelper::showExternalId($question);?></h1>

<?=$this->element('take_question', ['question' => $question])?>
<center>
    <a href="#" class="btn highlight large inline-block" onclick="Popup.load('/answers/drawing_answer/<?=getUUID($question, 'get');?>', 1220); Answer.answerChanged = true;">
        <span class="fa fa-edit"></span>
        <?= __("Antwoord tekenen")?>
    </a>
</center>

<?= $this->Form->end(); ?>
<?= $this->element('take_footer', ['has_next_question' => $has_next_question]); ?>

<script type="text/javascript">
    Answer.answerChanged = false;
</script>
<?=$this->element('question_styling',['question' => $question]);?>

