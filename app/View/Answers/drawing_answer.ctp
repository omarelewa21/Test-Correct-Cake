<div class="popup-head">Antwoord tekenen</div>

<img id="imgDrawing" style="width:100%; height: 530px;" />

<iframe src="/answers/drawing_answer_canvas/<?=$question_id?>" frameborder="0" id="drawingFrame" width="100%" height="640"></iframe>
<center>
    <a href="#" class="btn highlight" id="btn-change" style="display: none;">Tekening wijzigen</a>
    <a href="#" class="btn white" onclick="Popup.closeLast();">Terug</a>
</center>


<script type="text/javascript">
    window.drawingSaveUrl = '/answers/save_drawing/<?=$question_id?>';
    window.drawingCallback = function(){
        Popup.closeLast();
    };

    <?
     if(isset($drawing_data)) {
        ?>

        $('#drawingFrame').hide();

        $('#btn-change').show().click(function(){

        Popup.message({
            btnOk: 'Ja',
            btnCancel: 'Annuleren',
            title: 'Weet u het zeker?',
            message: 'De huidige tekening gaat verloren.'
        }, function() {

            $('#imgDrawing').hide();
            $('#drawingFrame').show();
            $('#btn-change').hide();
        });
    });

        $('#imgDrawing').show().attr({
            'src' : '<?=isset($drawing_data) && isset($drawing_data['drawing']) ? $drawing_data['drawing'] : ''?>'
        });
        <?
    }else{
        ?>
        $('#imgDrawing').hide();
        $('#drawingFrame').show();
        <?
    }
    ?>
</script>

