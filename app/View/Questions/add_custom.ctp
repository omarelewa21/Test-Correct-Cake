<div class="popup-head">Vraag toevoegen</div>
<div class="popup-content">
    <div>Gesloten vraagtypes</div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup('MultipleChoiceQuestion', '<?=$owner?>', <?=$owner_id?>);">
        Meerkeuze
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup('MatchingQuestion', '<?=$owner?>', <?=$owner_id?>);">
        Combineer
    </div>
    <div class="btn highlight pull-left defaultMenuButton" onclick="Questions.addPopup('ClassifyQuestion', '<?=$owner?>', <?=$owner_id?>);">
        Rubriceer
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup('RankingQuestion', '<?=$owner?>', <?=$owner_id?>);">
        Rangschik
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup('TrueFalseQuestion', '<?=$owner?>', <?=$owner_id?>);">
        Juist / Onjuist
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup('MultiCompletionQuestion', '<?=$owner?>', <?=$owner_id?>);">
        Selectie
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup('ARQQuestion', '<?=$owner?>', <?=$owner_id?>);">
        ARQ
    </div>

    <div style="display:none" class="btn highlight pull-left defaultMenuButton" onclick="Questions.addPopup('CompletionQuestion', '<?=$owner?>', <?=$owner_id?>);">
        Gesloten gatentekst
    </div>

    <div class="pt15" style="clear:both">Open vraagtypes</div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addOpenPopup('short', '<?=$owner?>', <?=$owner_id?>);">
        Leg uit kort
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addOpenPopup('medium', '<?=$owner?>', <?=$owner_id?>);">
        Leg uit lang
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup('CompletionQuestion', '<?=$owner?>', <?=$owner_id?>);">
        Open gatentekst
    </div>

    <div class="btn highlight pull-left  defaultMenuButton" onclick="Questions.addPopup('DrawingQuestion', '<?=$owner?>', <?=$owner_id?>);">
        Tekenen
    </div>
    <div style="display:none;| class="btn highlight pull-left mb20 defaultMenuButton" onclick="Questions.addOpenPopup('long', '<?=$owner?>', <?=$owner_id?>);">
        Wiskunde vraag
    </div>
    <Br clear="all" />
</div>

<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
</div>