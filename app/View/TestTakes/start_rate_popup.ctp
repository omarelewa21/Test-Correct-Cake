<div class="popup-head">
<?= __("Nakijken starten")?>
</div>
<div class="popup-content">
    <div id="rate_options">
        <div class="btn highlight pull-left" style="cursor:pointer; width:150px; height: 100px; margin-right:10px; line-height:100px; text-align: center;" onclick="Navigation.load('/test_takes/rate_teacher_question/<?=$take_id?>'); Popup.closeLast();">
        <?= __("Alles per vraag")?>
        </div>
        <div class="btn highlight pull-left" style="cursor:pointer; width:150px; height: 100px; line-height:100px; text-align: center;" onclick="Navigation.load('/test_takes/rate_teacher_participant/<?=$take_id?>'); Popup.closeLast();">
        <?= __("Alles per Student")?>
        </div>
        <div class="btn highlight pull-right" style="cursor:pointer; width:150px; height: 100px; line-height:100px; text-align: center;" onclick="$('#rate_options').slideUp(); $('#random_options').slideDown();">
        <?= __("Steekproef")?>
        </div>
    </div>

    <div id="random_options" style="display: none;">
        <div id="random_percentage_val" style="text-align: center; padding:20px; font-size: 22px;">50 %</div>
        <div id="random_percentage"></div>

        <input type="text" id="random_percentage_input" value="50" style="display: none;"/>

        <center>
            <a href="#" class="btn highlight large inline-block mt15" onclick="Navigation.load('/test_takes/rate_teacher_random/<?=$take_id?>/' + $('#random_percentage_input').val()); Popup.closeLast();"><?= __("Starten")?></a>
        </center>
    </div>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();" selid="cancel-btn">
    <?= __("Annuleer")?>
    </a>
</div>

<script type="text/javascript">
    $('#random_percentage').slider({
        value:50,
        min: 10,
        max: 100,
        step: 10,
        slide: function (event, ui) {
            $('#random_percentage_val').html(ui.value + ' %');
            $('#random_percentage_input').val(ui.value);
        }
    });
</script>