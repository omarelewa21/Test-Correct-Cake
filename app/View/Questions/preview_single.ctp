<div class="popup-head"><?= __("Vraag voorbeeld")?></div>
<div class="popup-content">
    <div id="question_preview"></div>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Sluiten")?>
    </a>
</div>

<a href="#" class="btn red" id="btnAttachmentFrame">
    <span class="fa fa-remove"></span>
</a>

<iframe id="attachmentFrame" frameborder="0"></iframe>
<div id="attachmentFade"></div>

<script type="text/javascript">
    $('#question_preview').load('/questions/preview_single_load/<?=$question_id?>');
</script>