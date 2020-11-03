<script type="text/javascript">
    $('input_.<?=getUUID($question, 'get')?>').click(function() {
        Answer.answerChanged = true;
    });

    Answer.answerChanged = false;
</script>