<div class="progress" id="barAnswerTimeout" style="margin: 10px 0px 0px 0px; background: white; display: none;">
    <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100">

    </div>
</div>

<br><br><br>
<a href="#" class="btn highlight large" onclick="Answer.saveAndNextAnswerPlease();"
   style="position: fixed; bottom: 0px; left: 0px; width: 100%; text-align: center">
    <span class="fa fa-check"></span>
    <?= $has_next_question ? 'Volgende vraag' : 'Gereed' ?>
</a>

<script type="text/javascript">
    Answer.startCount();

    Answer.closeable = <?php echo (int) $question['closeable'] == 1 ? 'true;' : 'false;' ?>


    function calcMaxLength(e) {
        Answer.answerChanged = true;
        var text = $(e).val();

        $('#barInputLength').css({
            'width': ((100 / 140) * text.length) + '%'
        }).html(text.length + '/140 tekens');
    }
</script>
