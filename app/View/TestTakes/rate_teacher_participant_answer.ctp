<div class="block">
    <div class="block-head">Vraag #<?=$i?> voorbeeld</div>
    <div class="block-content" id="question_preview_<?=$question['question']['id']?>">
        Laden..
    </div>
</div>

<div class="block" style="border-left: 3px solid #197cb4;">
    <div class="block-head">Vraag #<?=$i?> antwoordmodel</div>
    <div class="block-content" id="question_answer_preview_<?=$question['question']['id']?>">
        Laden..
    </div>
</div>

<div class="block" style="width:280px; float:right;">
    <div class="block-head">
        Score
    </div>
    <div class="block-content" id="score_<?=$participant_id?><?=$question['question']['id']?>">
        Laden..
    </div>
</div>

<div class="block" style="width:calc(100% - 300px); margin-bottom: 100px; border-left: 3px solid #689236">
    <div class="block-head">Vraag #<?=$i?> antwoord</div>
    <div class="block-content" id="question_answer_<?=$question['question']['id']?>">
        Laden..
    </div>
</div>