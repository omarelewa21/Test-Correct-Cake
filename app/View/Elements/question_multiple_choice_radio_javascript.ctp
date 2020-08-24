<script type="text/javascript">

    $('.input_radio_<?=$question['id']?>').click(function() {
        var checkbox = $('.checkbox_radio_'+$(this).val());
        var newChecked = !checkbox.is(':checked');
        $('.input_<?=$question['id']?>').prop('checked',false);
        $(this).prop('checked' , newChecked);
        checkbox.prop('checked',newChecked);
        Answer.answerChanged = true;
    });

    Answer.answerChanged = false;
</script>