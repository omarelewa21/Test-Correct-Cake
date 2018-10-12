<div class="popup-head">Vraag toevoegen</div>
<div class="popup-content">
    <div class="btn highlight pull-left mr10 mb10" style="cursor:pointer; width:150px; height: 100px; line-height:100px; text-align: center;" onclick="Popup.load('/questions/add_open_selection/<?=$owner?>/<?=$owner_id?>', 430);">
        Open vraag
    </div>
    <div class="btn highlight pull-left mr10 mb10" style="cursor:pointer; width:150px; height: 100px; line-height:100px; text-align: center;" onclick="Questions.addPopup('CompletionQuestion', '<?=$owner?>', <?=$owner_id?>);">
        Gatentekst
    </div>
    <div class="btn highlight pull-left mr10 mb10" style="cursor:pointer; width:150px; height: 100px; line-height:100px; text-align: center;" onclick="Questions.addPopup('MatchingQuestion', '<?=$owner?>', <?=$owner_id?>);">
        Combineervraag
    </div>
    <div class="btn highlight pull-left mb10" style="cursor:pointer; width:150px; height: 100px; line-height:100px; text-align: center;" onclick="Questions.addPopup('ClassifyQuestion', '<?=$owner?>', <?=$owner_id?>);">
        Rubriceervraag
    </div>
    <div class="btn highlight pull-left mb10 mr10" style="cursor:pointer; width:150px; height: 100px; line-height:100px; text-align: center;" onclick="Questions.addPopup('MultipleChoiceQuestion', '<?=$owner?>', <?=$owner_id?>);">
        Multiple Choice
    </div>
    <div class="btn highlight pull-left mr10 mb10" style="cursor:pointer; width:150px; height: 100px; line-height:100px; text-align: center;" onclick="Questions.addPopup('RankingQuestion', '<?=$owner?>', <?=$owner_id?>);">
        Rangschikvraag
    </div>
    <div class="btn highlight pull-left mb10 mr10" style="cursor:pointer; width:150px; height: 100px; line-height:100px; text-align: center;" onclick="Questions.addPopup('DrawingQuestion', '<?=$owner?>', <?=$owner_id?>);">
        Tekenvraag
    </div>
    <div class="btn highlight pull-left" style="cursor:pointer; width:150px; height: 100px; line-height:100px; text-align: center;" onclick="Questions.addPopup('TrueFalseQuestion', '<?=$owner?>', <?=$owner_id?>);">
        Juist / Onjuist
    </div>
    <div class="btn highlight pull-left mb10 mr10" style="cursor:pointer; width:150px; height: 100px; line-height:100px; text-align: center;" onclick="Questions.addPopup('MultiCompletionQuestion', '<?=$owner?>', <?=$owner_id?>);">
        Selectievraag
    </div>
    <div class="btn highlight pull-left mb10 mr10" style="cursor:pointer; width:150px; height: 100px; line-height:100px; text-align: center;" onclick="Questions.addPopup('ARQQuestion', '<?=$owner?>', <?=$owner_id?>);">
        ARQ-vraag
    </div>
    <div class="btn highlight pull-left mb10" style="cursor:pointer; width:150px; height: 100px; line-height:100px; text-align: center;" onclick="Questions.addOpenPopup('long', '<?=$owner?>', <?=$owner_id?>);">
        Wiskunde vraag
    </div>
    <Br clear="all" />
</div>

<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
</div>