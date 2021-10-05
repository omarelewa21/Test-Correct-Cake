<div class="popup-head"><?= __("Cijferlijst")?></div>
<iframe src="/test_takes/rates_pdf_container/<?=$take_id?>?file=/test_takes/rates_pdf/<?=$take_id?>" width="100%" height="550" frameborder="0"></iframe>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Sluiten")?>
    </a>
</div>
