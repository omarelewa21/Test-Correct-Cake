<?= $this->element('take_attachments', ['question' => $question]); ?>

<h1>Infoscherm</h1>
<?= $this->element('take_question', ['question' => $question]) ?>

<br clear="all" />
<?= $this->Form->create('Answer') ?>
<input type="hidden" name="answer" value="infoscherm vraag"/>
<?= $this->Form->end(); ?>

<?= $this->element('take_footer', ['has_next_question' => $has_next_question]); ?>

<script>
    Answer.answerChanged = true;
</script>