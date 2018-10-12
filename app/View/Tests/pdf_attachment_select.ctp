<?
if(isset($attachmentArray)){

<p>
	Selecteer de bijlages die u mee wilt printen.
</p>

foreach($attachmentArray['attachments'] as $attKey => $attVal) {
?>
<input type="checkbox"><?= $attVal['title']; ?>
<br>
<?
}
}else{
?>
<div class="popup-head">Toets in PDF</div>
<iframe src="/tests/pdf_container/<?=$test_id?>?file=/tests/pdf/<?=$test_id?>" width="100%" height="550" frameborder="0"></iframe>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Sluiten
    </a>
</div>
<?
}
?>
