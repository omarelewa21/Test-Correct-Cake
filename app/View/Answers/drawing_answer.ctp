<div class="popup-head"><?= __("Antwoord tekenen")?></div>

<img id="imgDrawing" style="width:100%; height: 530px;" />

<iframe src="/answers/drawing_answer_canvas/<?=$question_id?>" frameborder="0" id="drawingFrame" width="100%" height="640"></iframe>
<center>
    <a href="#" class="btn highlight" id="btn-change" style="display: none;"><?= __("Tekening wijzigen")?></a>
    <a href="#" class="btn white" onclick="Popup.closeLast();"><?= __("Terug")?></a>
</center>


<script type="text/javascript">
    window.drawingSaveUrl = '/answers/save_drawing/<?=$question_id?>';
    window.drawingCallback = function(){
        window.parent.Answer.drawingPadClose('<?=$question_id?>');
        Popup.closeLast();
    };

    <?php
     if(isset($drawing_data)) {
        ?>

        $('#drawingFrame').hide();

        $('#btn-change').show().click(function(){

        Popup.message({
            btnOk: '<?= __("Ja") ?>',
            btnCancel: '<?= __("Annuleren")?>',
            title: '<?= __("Weet u het zeker?") ?>',
            message: '<?= __("De huidige tekening gaat verloren.")?>'
        }, function() {

            $('#imgDrawing').hide();
            $('#drawingFrame').show();
            $('#btn-change').hide();
        });
    });

        $('#imgDrawing').show().attr({
            'src' : '<?=isset($drawing_data) && isset($drawing_data['drawing']) ? $drawing_data['drawing'] : ''?>'
        });
        <?php
    }else{
        ?>
        $('#imgDrawing').hide();
        $('#drawingFrame').show();
        <?php
    }
    ?>
</script>

