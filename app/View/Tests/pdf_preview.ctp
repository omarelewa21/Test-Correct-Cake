<div class="popup-head"><?= __("Toets in PDF")?></div>
<iframe src="/tests/pdf_container/<?=$test_id?>?file=/tests/pdf/<?=$test_id?>" width="100%" height="550" frameborder="0" class="pdf-iframe"> </iframe>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Sluiten")?>
    </a>
</div>
