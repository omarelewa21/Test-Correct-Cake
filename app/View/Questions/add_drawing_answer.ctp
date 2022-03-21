<div class="popup-head"><?= __("Antwoord tekenen")?></div>

<center>
    <img id="imgDrawing" style="width:970px; height: 530px;" />
</center>

<iframe src="/questions/add_drawing_answer_canvas" frameborder="0" id="drawingFrame" width="100%" height="540"></iframe>
<center>
    <a href="#" class="btn highlight" id="btn-change" style="display: none;" selid="draw-answer-1"><?= __("Tekening wijzigen")?></a>
    <a href="#" class="btn white" id="btn-cancel" onclick="Popup.closeLast();" style="display: none;"><?= __("Annuleren")?></a>
</center>

<script type="text/javascript">
    window.drawingSaveUrl = '/questions/save_drawing';
    window.drawingCallback = function(){
        window.parent.Answer.drawingPadClose('<?=$question_id?>');
        Popup.closeLast();
    };

    <?php if(isset($drawing_data)) {
        ?>
        $('#imgDrawing').show().attr({
            'src' : '<?=$drawing_data?>'
        });
        $('#drawingFrame').hide();

        $('#btn-change').show().click(function(){
            if(confirm('<?= __("Weet u het zeker? De huidige tekening gaat verloren.")?>')) {
                $('#imgDrawing').hide();
                $('#drawingFrame').show();
                $(this).hide();
            }
        });
        $('#btn-cancel').show();
        <?php
    }else{
        ?>
        $('#imgDrawing').hide();
        $('#drawingFrame').show();
        <?php
    }
    ?>
</script>

