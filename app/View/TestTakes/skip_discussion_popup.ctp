<div class="popup-head">
<?= __("Meteen naar nakijken")?>
</div>
<div class="popup-content">
    <div class="alert alert-info">
    <?= __("Weet je zeker dat je meteen naar nakijken wilt gaan?")?>
    </div>
</div>
<div class="popup-footer">
    <a href="#" class="btn blue mt5 mr5 pull-right" onclick="$.get('/test_takes/skip_discussion/<?=$take_id?>',[], function(){ User.goToLaravel('teacher/test_takes/taken?openTab=norm'); Popup.closeLast();Menu.updateMenuFromRedirect('taken', 'tests_examine')})" >
    <?= __("Doorgaan")?>
    </a>
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();" selid="cancel-btn">
        <?= __("Annuleer")?>
    </a>
</div>
