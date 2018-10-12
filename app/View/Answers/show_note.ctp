<div class="popup-head">Aantekening</div>
<div class="popup-content">
    <?
    if($answer['question']['note_type'] == 'TEXT') {
        echo $answer['note'];
    }elseif($answer['question']['note_type'] == 'DRAWING') {
        ?>
        <img src="<?=$answer['note']?>" width="100%" />
        <?
    }
    ?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Sluiten
    </a>
</div>