<div class="popup-head"><?= __("Toets in PDF")?></div>

<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Sluiten")?>
    </a>
</div>

<iframe src='' width="100%" height="550" frameborder="0" class="pdf-iframe" id="pdfiframe_<?=$filename?>"></iframe>
<script>
    // MarkO todo: code in pdf_preview_attachment. Popup.js blijkt niet okay te zijn, daar zit een
    // globale variabele of race condition in. Reproduceren door meerdere PDF bijlages te openen.
    handleBase64PDF("pdfiframe_<?=$filename?>", "<?=$filename?>", '<?=$base64?>')
</script>