<div class="popup-head"><?= __("Vraag toevoegen")?></div>
<div class="popup-content">
    <div><?= __("Gesloten vraagtypes")?></div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup('MultipleChoiceQuestion', '<?=$owner?>', '<?=$owner_id?>', 'MultipleChoice',true);">
    <?= __("Meerkeuze")?>
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup('MatchingQuestion', '<?=$owner?>', '<?=$owner_id?>');">
    <?= __("Combineer")?>
    </div>
    <div class="btn highlight pull-left defaultMenuButton" onclick="Questions.addPopup('ClassifyQuestion', '<?=$owner?>', '<?=$owner_id?>');">
    <?= __("Rubriceer")?>
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup('RankingQuestion', '<?=$owner?>', '<?=$owner_id?>');">
    <?= __("Rangschik")?>
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup('TrueFalseQuestion', '<?=$owner?>', '<?=$owner_id?>');">
    <?= __("Juist / Onjuist")?>
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup('CompletionQuestion', '<?=$owner?>', '<?=$owner_id?>', 'multi');">
    <?= __("Selectie")?>
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup('ARQQuestion', '<?=$owner?>', '<?=$owner_id?>');">
    <?= __("ARQ")?>
    </div>

    <div style="display:none" class="btn highlight pull-left defaultMenuButton" onclick="Questions.addPopup('CompletionQuestion', '<?=$owner?>', '<?=$owner_id?>');">
    <?= __("Gesloten gatentekst")?>
    </div>

    <div class="pt15" style="clear:both"><?= __("Open vraagtypes")?></div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addOpenPopup('short', '<?=$owner?>', '<?=$owner_id?>', <?=$newEditor?>);">
    <?= __("Leg uit kort")?>
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addOpenPopup('medium', '<?=$owner?>', '<?=$owner_id?>', <?=$newEditor?>);">
    <?= __("Leg uit lang")?>
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup('CompletionQuestion', '<?=$owner?>', '<?=$owner_id?>','completion', <?=$newEditor?>);">
    <?= __("Open gatentekst")?>
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup('DrawingQuestion', '<?=$owner?>', '<?=$owner_id?>');">
    <?= __("Tekenen")?>
    </div>
    <div style="display:none;| class="btn highlight pull-left mb20 defaultMenuButton" onclick="Questions.addOpenPopup('long', '<?=$owner?>', '<?=$owner_id?>');">
    <?= __("Open vraag")?>
    </div>

    <div class="pt15" style="clear:both"><?= __("Overig")?></div>
    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup('InfoscreenQuestion', '<?=$owner?>', '<?=$owner_id?>');">
    <?= __("Infoscherm")?>
    </div>
    <Br clear="all" />
</div>

<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
</div>
