<div class="popup-head">Antwoord tekenen</div>

<center>
    <img id="imgDrawing" style="width:970px; height: 530px;" />
</center>

<iframe src="/questions/add_drawing_answer_canvas" frameborder="0" id="drawingFrame" width="100%" height="540"></iframe>
<center>
    <a href="#" class="btn highlight" id="btn-change" style="display: none;">Tekening wijzigen</a>
    <a href="#" class="btn white" id="btn-cancel" onclick="Popup.closeLast();" style="display: none;">Annuleren</a>
</center>

<script type="text/javascript">
    window.drawingSaveUrl = '/questions/save_drawing';
    window.drawingCallback = function(){
        Popup.closeLast();
    };

    <? if(isset($drawing_data)) {
        ?>
        $('#imgDrawing').show().attr({
            'src' : '<?=$drawing_data?>'
        });
        $('#drawingFrame').hide();

        $('#btn-change').show().click(function(){
            if(confirm("Weet u het zeker? De huidige tekening gaat verloren.")) {
                $('#imgDrawing').hide();
                $('#drawingFrame').show();
                $(this).hide();
            }
        });
        $('#btn-cancel').show();
        <?
    }else{
        ?>
        $('#imgDrawing').hide();
        $('#drawingFrame').show();
        <?
    }
    ?>
</script>

