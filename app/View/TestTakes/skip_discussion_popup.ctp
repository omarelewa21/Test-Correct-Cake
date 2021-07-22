<div class="popup-head">
    Meteen naar nakijken
</div>
<div class="popup-content">
    <div class="alert alert-info">
        Weet je zeker dat je meteen naar nakijken wilt gaan?
    </div>
</div>
<div class="popup-footer">
    <a href="#" class="btn blue mt5 mr5 pull-right" onclick="$.get('/test_takes/skip_discussion/<?=$take_id?>',[], function(){ Navigation.load('/test_takes/to_rate'); Popup.closeLast();Menu.updateMenuFromRedirect('taken', 'tests_examine')})" >
        Doorgaan
    </a>
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
</div>
