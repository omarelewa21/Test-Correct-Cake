<?=$this->element('take_attachments', ['question' => $question]);?>

<?=$this->Form->create('Answer')?>
<h1>Tekenvraag [<?=$question['score']?>pt]</h1>

<?=$this->element('take_question', ['question' => $question])?>
<center>
    <a href="#" class="btn highlight large inline-block" onclick="Popup.load('/answers/drawing_answer/<?=getUUID($question, 'get');?>', 1220); Answer.answerChanged = true;">
        <span class="fa fa-edit"></span>
        Antwoord tekenen
    </a>
</center>

<?= $this->Form->end(); ?>
<?= $this->element('take_footer', ['has_next_question' => $has_next_question]); ?>

<script type="text/javascript">
    Answer.answerChanged = false;
</script>

