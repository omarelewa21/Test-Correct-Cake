<div class="popup-head">Open-vraag toevoegen</div>
<div class="popup-content">
    <div class="btn highlight pull-left mr10 mb10" style="cursor:pointer; width:150px; height: 100px; line-height:100px; text-align: center;" onclick="Questions.addOpenPopup('short', '<?=$owner?>', '<?=$owner_id?>');">
        Kort
    </div>
    <div class="btn highlight pull-left mb10" style="cursor:pointer; width:150px; height: 100px; line-height:100px; text-align: center;" onclick="Questions.addOpenPopup('medium', '<?=$owner?>', '<?=$owner_id?>');">
        Lang
    </div>

    <Br clear="all" />
</div>

<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
</div>