<div class="popup-head"><?= __("Vraag toevoegen")?></div>
<div class="popup-content">
    <div><?= __("Gesloten vraagtypes")?></div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup(<?=$newEditor?>, 'MultipleChoiceQuestion', '<?=$owner?>', '<?=$test_id?>','multiplechoice', '<?= $owner_id ?>');">
    <?= __("Meerkeuze")?>
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup(<?=$newEditor?>, 'MatchingQuestion', '<?=$owner?>', '<?=$test_id?>','Matching', '<?= $owner_id ?>');">
    <?= __("Combineer")?>
    </div>
    <div class="btn highlight pull-left defaultMenuButton" onclick="Questions.addPopup(<?=$newEditor?>, 'MatchingQuestion', '<?=$owner?>', '<?=$test_id?>','Classify', '<?= $owner_id ?>');">
    <?= __("Rubriceer")?>
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup(<?=$newEditor?>, 'RankingQuestion', '<?=$owner?>', '<?=$test_id?>','ranking', '<?= $owner_id ?>');">
    <?= __("Rangschik")?>
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup(<?=$newEditor?>, 'MultipleChoiceQuestion', '<?=$owner?>', '<?=$test_id?>','TrueFalse', '<?= $owner_id ?>');">
    <?= __("Juist / Onjuist")?>
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup(<?=$newEditor?>, 'CompletionQuestion', '<?=$owner?>', '<?=$test_id?>','multi', '<?= $owner_id ?>');">
    <?= __("Selectie")?>
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup(<?=$newEditor?>, 'MultipleChoiceQuestion', '<?=$owner?>', '<?=$test_id?>','ARQ', '<?= $owner_id ?>');">
    <?= __("ARQ")?>
    </div>

    <div style="display:none" class="btn highlight pull-left defaultMenuButton" onclick="Questions.addPopup(<?=$newEditor?>, 'CompletionQuestion', '<?=$owner?>', '<?=$test_id?>','completion', '<?= $owner_id ?>');">
    <?= __("Gesloten gatentekst")?>
    </div>

    <div class="pt15" style="clear:both"><?= __("Open vraagtypes")?></div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup(<?=$newEditor?>, 'OpenQuestion', '<?=$owner?>', '<?=$test_id?>','short', '<?= $owner_id ?>');">
    <?= __("Leg uit kort")?>
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup(<?=$newEditor?>, 'OpenQuestion', '<?=$owner?>', '<?=$test_id?>','medium', '<?= $owner_id ?>');">
    <?= __("Leg uit lang")?>
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup(<?=$newEditor?>, 'CompletionQuestion', '<?=$owner?>', '<?=$test_id?>','completion', '<?= $owner_id ?>')">
    <?= __("Open gatentekst")?>
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup(<?=$newEditor?>, 'DrawingQuestion', '<?=$owner?>', '<?=$test_id?>','drawing', '<?= $owner_id ?>');">
    <?= __("Tekenen")?>
    </div>
    <div style="display:none;" class="btn highlight pull-left mb20 defaultMenuButton" onclick="Questions.addOpenPopup('long', '<?=$owner?>', '<?=$owner_id?>');">
    <?= __("Open vraag")?>
    </div>

    <div class="pt15" style="clear:both"><?= __("Overig")?></div>
    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup(<?=$newEditor?>, 'InfoscreenQuestion', '<?=$owner?>', '<?=$test_id?>','info', '<?= $owner_id ?>')">
    <?= __("Infoscherm")?>
    </div>
    <Br clear="all" />
</div>

<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
</div>
