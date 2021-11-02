<div class="popup-head"><?= __("Bijlage")?></div>
<iframe src="/tests/pdf_container/<?=$test_id?>/<?=$attachment_id?>?file=/tests/pdf_attachmentpdf/<?=$test_id?>/<?=$attachment_id?>" width="100%" height="550" frameborder="0"></iframe>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Sluiten")?>
    </a>
</div>
