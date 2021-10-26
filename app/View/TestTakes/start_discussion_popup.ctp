<div class="popup-head">
<?= __("Bespreking starten")?>
</div>
<div class="popup-content">

    <div class="alert alert-info">
    <?= __("Wilt u de gesloten vragen zoverslaan of bespreken?")?>
    </div>
    <?php if ($has_carousel) { ?>
    <div class="warning" style="padding-top:5px;padding-bottom:5px;margin-top:13px;margin-bottom:13px;color:#000000;text-align: center;font-size:14px">
        <b><?= __("Vragencarrousel wordt automatisch overgeslagen!")?></b>
    </div>
    <?php } ?>
    <div class="btn highlight pull-left" style="cursor:pointer; width:150px; height: 100px; line-height:100px; text-align: center;" onclick="TestTake.startDiscussion('<?=$take_id?>', 'OPEN_ONLY')">
    <?= __("Overslaan")?>
    </div>
    <div class="btn highlight pull-right" style="cursor:pointer; width:150px; height: 100px; line-height:100px; text-align: center;" onclick="TestTake.startDiscussion('<?=$take_id?>', 'ALL')">
    <?= __("Bespreken")?>
    </div>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
</div>
