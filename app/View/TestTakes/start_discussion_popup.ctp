<div class="popup-head">
    Bespreking starten
</div>
<div class="popup-content">

    <div class="alert alert-info">
        Wilt u de gesloten vragen overslaan of bespreken?
    </div>

    <div class="btn highlight pull-left" style="cursor:pointer; width:150px; height: 100px; line-height:100px; text-align: center;" onclick="TestTake.startDiscussion('<?=$take_id?>', 'OPEN_ONLY')">
        Overslaan
    </div>
    <div class="btn highlight pull-right" style="cursor:pointer; width:150px; height: 100px; line-height:100px; text-align: center;" onclick="TestTake.startDiscussion('<?=$take_id?>', 'ALL')">
        Bespreken
    </div>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
</div>