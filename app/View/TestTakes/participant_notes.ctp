<div class="block">
    <div class="block-head"><?= __("Notities")?></div>
    <div class="block-content" style="max-height:400px; overflow: auto;">
        <textarea id="participant_notes" style="height:300px;"><?=nl2br($participant['invigilator_note'])?></textarea>
    </div>
    <div class="block-footer">
        <a href="#" class="btn highlight mr1 pull-right" onclick="TestTake.saveParticipantNotes('<?=$take_id?>', '<?=$participant_id?>');">
        <?= __("Opslaan")?>
        </a>
        <a href="#" class="btn grey pull-right" onclick="Popup.closeLast()">
        <?= __("Sluiten")?>
        </a>
    </div>
</div>