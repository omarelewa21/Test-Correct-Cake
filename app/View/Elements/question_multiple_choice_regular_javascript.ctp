<script type="text/javascript">
    $('input_.<?=$question['id']?>').click(function() {
        Answer.answerChanged = true;
    });

    Answer.answerChanged = false;
</script>