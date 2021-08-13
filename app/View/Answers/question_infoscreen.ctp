<?= $this->element('take_attachments', ['question' => $question]); ?>
<?php
    $citoClass = '';
    if(substr_count($question['metadata'],'cito') > 0){
$citoClass = 'cito';
}
?>
<h1 class="question_type <?=$citoClass?> infoscreen"><?= __("Infoscherm")?><?=AppHelper::showExternalId($question);?></h1>
<?= $this->element('take_question', ['question' => $question]) ?>

<br clear="all" />
<?= $this->Form->create('Answer') ?>
<input type="hidden" name="answer" value="infoscherm vraag"/>
<?= $this->Form->end(); ?>

<div style="position: absolute; bottom:40px; font-style: italic">* <?= __("Dit is een informatiescherm, een antwoord is niet nodig.")?></div>

<?= $this->element('take_footer', ['has_next_question' => $has_next_question]); ?>

<script>
    Answer.answerChanged = true;
</script>
<?=$this->element('question_styling',['question' => $question]);?>