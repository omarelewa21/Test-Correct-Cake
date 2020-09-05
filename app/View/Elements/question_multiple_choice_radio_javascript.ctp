<script type="text/javascript">

    $('.input_radio_<?=getUUID($question, 'get')?>').click(function() {
        var checkbox = $('.checkbox_radio_'+$(this).val());
        var newChecked = !checkbox.is(':checked');
        $('.input_<?=getUUID($question, 'get')?>').prop('checked',false);
        $(this).prop('checked' , newChecked);
        checkbox.prop('checked',newChecked);
        Answer.answerChanged = true;
    });

    Answer.answerChanged = false;
</script>